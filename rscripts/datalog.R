source('db_connection.R')

getWaterLevelData <- function(startDateTime, endDateTime) {
  
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

      WHERE data_log.date >= DATE '", startDateString ,"'
      AND   data_log.time >= TIME '", startTimeString ,"'
      AND   data_log.date <= DATE '", endDateString ,"'
      AND   data_log.time <= TIME '", endTimeString ,"'
  
    ", sep="")
  )
  
  closeDbConnection(con)
  
  return(data)
  
}

get24HrWaterLevelData <- function (startDateTime = NA, endDateTime = Sys.time()) {

  last24Hr <- endDateTime - 24*60*60
  
  if(is.na(startDateTime) | last24Hr > startDateTime) {
    startDateTime <- last24Hr
  }
  
  getWaterLevelData(startDateTime, endDateTime)
  
}