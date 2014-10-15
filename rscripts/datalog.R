source('db_connection.R')

getWaterLevelData <- function(stationCode, startDateTime, endDateTime) {
  
  cat("Actual start time: ")
  cat(strftime(startDateTime))
  cat("\n")
  cat("Actual end time: ")
  cat(strftime(endDateTime))
  cat("\n")
  
  con <- openDbConnection()
  
  startDateString <- strftime(startDateTime, "%Y-%m-%d");
  endDateString   <- strftime(endDateTime, "%Y-%m-%d");
  
  startTimeString <- strftime(startDateTime, "%H:%M:%S");
  endTimeString   <- strftime(endDateTime, "%H:%M:%S");
  
  data <- dbGetQuery(con,
                     paste("
                           SELECT 
                           data_log.code, 
                           data_log.date,
                           data_log.time,
                           data_log.water1,
                           tele_wl_detail.left_bank, 
                           tele_wl_detail.right_bank,
                           tele_wl_detail.ground_level
                           
                           FROM data_log
                           
                           INNER JOIN tele_wl_detail ON tele_wl_detail.code = data_log.code
                           
                           WHERE 
                           (data_log.date > DATE '", startDateString ,"'
                           OR
                           data_log.date = DATE '", startDateString ,"' AND data_log.time >= TIME '", startTimeString ,"')
                           AND
                           (data_log.date < DATE '", endDateString ,"'
                           OR
                           data_log.date = DATE '", endDateString ,"' AND data_log.time < TIME '", endTimeString ,"')
                           AND data_log.code = '", stationCode ,"'
                           
                           ", sep="")
                     )
  
  closeDbConnection(con)
  
  return(data)
  
}

get24HrWaterLevelData <- function (stationCode, startDateTime = NA, endDateTime = Sys.time(), debug=FALSE) {
  
  last24Hr <- endDateTime - 24*60*60
  
  if(!debug & (is.na(startDateTime) | last24Hr > startDateTime)) {
    startDateTime <- last24Hr
  }
  
  getWaterLevelData(stationCode, startDateTime, endDateTime)
  
}

getProblemTableName <- function(problemType) {
  
  tableName <- NA
  
  if(problemType == "BD") {
    tableName <- "problems_boundary"
  }
  
  return(tableName)
  
}

getLatestProblemCheckedTime <- function(stationCode, dataType, problemType) {
  
  problemTableName <- getProblemTableName(problemType)
  
  query <- paste("SELECT * FROM ", problemTableName ,"
                 WHERE station_code = '",stationCode,"'
                  AND problem_type = '", problemType ,"'
                  AND data_type = '", dataType ,"'
                 ",sep="")
  con <- openDbConnection()
  
  data <- dbGetQuery(con, query)
  
  closeDbConnection(con)
  if(nrow(data) > 0) {
    return(data[1,]$latest_datetime)
  } else {
    return(NA)
  }
}

updateLatestProblemCheckedTime <- function(stationCode, dataType, problemType, latestDateTime) {
  
  query <- NA
  problemTableName <- getProblemTableName(problemType)
  
  latestDateTimeString <- strftime(latestDateTime, "%Y-%m-%d %H:%M:%S")
  
  if(is.na(getLatestProblemCheckedTime(stationCode, dataType, problemType))) {
    # insert
    query <- paste("
                    INSERT INTO ", problemTableName ,"(station_code, latest_datetime, data_type, problem_type)
                    VALUES ('", stationCode ,"','", latestDateTimeString, "', '", dataType,"'  ,'", problemType,"')
                   ", sep="")
  } else {
    # update
    query <- paste("
                    UPDATE ", problemTableName ,"
                    SET latest_datetime = '", latestDateTimeString ,"'
                    WHERE station_code = '",stationCode,"'
                    AND data_type = '", dataType ,"'
                    AND problem_type = '", problemType ,"'
                   ", sep="")
  }
  
  con <- openDbConnection()
  
  dbSendQuery(con, query)
  
  closeDbConnection(con)
  
  TRUE
}

getStationCodeList <- function() {
  query <- paste("SELECT tele_wl_detail.code FROM tele_wl_detail", sep="")
  
  con <- openDbConnection()
  
  data <- dbGetQuery(con, query)
  
  closeDbConnection(con)
  
  return(data$code)
}

opDate <- function(d) {
  
  d <- as.POSIXct(d)
  
  a <- as.POSIXlt(d)
  a$hour <- 7
  a$min <- 0
  a$sec <- 0
  
  a <- as.POSIXct(a)
  
  if(a > d) {
    # yesterday
    a <- a - 86400
  }
  
  return(a)
}

getNewProblemStationList <- function(dataType, problemType, time, problems) {
  
  allStationList <- levels(problems$station_code)
  oldStationList <- getAlreadyCheckedStationList(dataType, problemType, time)
  
 return(allStationList[!(allStationList %in% oldStationList)])
}

getAlreadyCheckedStationList <- function(dataType, problemType, time) {
  operationDateTime <- opDate(time)
  endOfOperationDateTime <- operationDateTime + 86400
  
  operationDateTimeString <- strftime(operationDateTime, "%Y-%m-%d %H:%M:%S")
  endOfOperationDateTimeString <- strftime(endOfOperationDateTime, "%Y-%m-%d %H:%M:%S")
  
  query <- paste0("
                   SELECT problems.station_code
                   FROM problems
                   WHERE start_datetime >= timestamp '", operationDateTimeString ,"'
                   AND end_datetime < timestamp '", endOfOperationDateTimeString ,"'                  
                   AND data_type = '", dataType ,"'
                   AND problem_type = '", problemType ,"'
                   ")
  
  con <- openDbConnection()
  
  data <- dbGetQuery(con, query)
  
  closeDbConnection(con)
  
  return(data$station_code)
  
}

updateProblemLog <- function(problems, intervalSecond) {
  
  problems <- problems[order(problems$start_datetime), ]
  
  
  con <- openDbConnection()
  
  for(i in 1:nrow(problems)) {
    p <- problems[i,]
    
    previousDateTime <- as.POSIXct(p$start_datetime) - intervalSecond
    previousDateTimeString <- strftime(previousDateTime, "%Y-%m-%d %H:%M:%S")
    
    
    currentDateTimeString <- strftime(Sys.time(), "%Y-%m-%d %H:%M:%S")
    
    operationDateTimeString <- strftime(opDate(p$start_datetime), "%Y-%m-%d %H:%M:%S")
    
    query <- paste("
                   SELECT *
                   FROM problems
                   WHERE station_code = '", p$station_code ,"'
                   AND end_datetime = timestamp '", previousDateTimeString ,"'
                   AND start_datetime >= timestamp '", operationDateTimeString ,"'                  

                   AND data_type = '", p$data_type ,"'
                  AND problem_type = '", p$problem_type ,"'
                   
                   ", sep="")
    
    
    previousProblem <- dbGetQuery(con, query)
    # print(query)
    # str(previousProblem)
    
    if(nrow(previousProblem) > 0) {
      # merge problem together
      
      # print("update")
      
      query <- paste("
                     UPDATE problems
                     SET
                     end_datetime = timestamp '", p$end_datetime ,"' ,
                     updated_at = timestamp '", currentDateTimeString ,"' ,
                     num = ",as.numeric(previousProblem$num) + 1,"

                     WHERE id=", previousProblem$id ,"
                     ", sep="")
      
      
      dbSendQuery(con, query)
      
      
    } else {
      # create new row
      
      # print("add")
      
      query <- paste("
                     INSERT INTO problems(station_code,data_type,problem_type,start_datetime,end_datetime,num,status,created_at,updated_at)
                     VALUES (
                     '", p$station_code ,"' ,
                     '", p$data_type ,"' ,
                     '", p$problem_type ,"' ,
                     timestamp '", p$start_datetime ,"' ,
                     timestamp '", p$end_datetime ,"' ,
                     '", p$num ,"' ,
                     'undefined' ,
                     timestamp '", currentDateTimeString ,"' ,
                     timestamp '", currentDateTimeString ,"' 
                      )
                     ", sep="")
      
      
      dbSendQuery(con, query)
      
    }
  }
  
  
  closeDbConnection(con);

}



