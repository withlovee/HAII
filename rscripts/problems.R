source('db_connection.R')
source('helper.R')
source('config.R')


Problems.GetProblemStationCodeList <- function(dataType, problemType, ,startDateTime, endDateTime) {

  startDateTimeString <- Helper.POSIXctToString(startDateTime)
  endDateTimeString <- Helper.POSIXctToString(endDateTime)

  queryString <- paste0("
    SELECT station_code
    FROM problems
    WHERE problem_type = '", problemType,"'
    AND data_type = '", dataType,"'
    AND (problems.start_datetime, problems.start_datetime) OVERLAPS (TIMESTAMP '", startDateTimeString, "', TIMESTAMP '", endDateTimeString, "' + INTERVAL '1 second')
    GROUP BY station_code
    ")

  print(queryString)

  data <- DBConnection.Query(queryString)

  return(data$station_code)
}

Problems.GetLatestProblemStationCodeList <- function(dataType, problemType, endDateTime) {
  startDateTime <- Helper.StartOfDay(endDateTime)
  stationCode <- Problems.GetProblemStationCodeList(dataType, problemType, startDateTime, endDateTime)

  return(stationCode)
}

Problems.AddProblem <- function(stationCode, dataType, problemType, startDateTime, endDateTime) {

  startDateTimeString <- Helper.POSIXctToString(startDateTime)
  endDateTimeString <- Helper.POSIXctToString(endDateTime)

  currentDateTimeString <- Helper.POSIXctToString(Sys.time())

  # query for overlap problem
  queryString <- paste0("
    SELECT *
    FROM problems
    WHERE problem_type = '", problemType, "'
    AND station_code = '", stationCode, "'
    AND data_type = '", dataType,"'
    AND (problems.start_datetime, problems.end_datetime + INTERVAL '1 second') OVERLAPS (TIMESTAMP '", startDateTimeString, "', TIMESTAMP '", endDateTimeString, "' + INTERVAL '1 second')
    ")

  result <- DBConnection.Query(queryString)

  if(nrow(result) > 0) {
    # update
    newDateTime <- Helper.MergeDateTime(c(result$start_datetime, startDateTime), c(result$end_datetime, endDateTime))

    for (id in result$id) {
      # update
      num <- Helper.CountDataNum(newDateTime$startDateTime, newDateTime$endDateTime)

      newStartDateTimeString <- Helper.POSIXctToString(newDateTime$startDateTime)
      newEndDateTimeString <- Helper.POSIXctToString(newDateTime$endDateTime)

      # calculate 
      queryString <- paste0("
        UPDATE problems
        SET
          start_datetime = timestamp '", newStartDateTimeString ,"',
          end_datetime = timestamp '", newEndDateTimeString ,"',
          num = ", num, ",
          updated_at = timestamp '", currentDateTimeString, "'
        WHERE id = ", id, "
        ")

      DBConnection.SendQuery(queryString)

      return(FALSE)

    }
  } else {
    # insert
    num <- Helper.CountDataNum(startDateTime, endDateTime)

    queryString <- paste0("
      INSERT INTO problems(station_code,data_type,problem_type,start_datetime,end_datetime,num,status,created_at,updated_at)
      VALUES (
        '", stationCode ,"' ,
        '", dataType ,"' ,
        '", problemType ,"' ,
        timestamp '", startDateTime ,"' ,
        timestamp '", endDateTime ,"' ,
        '", num ,"' ,
        'undefined' ,
        timestamp '", currentDateTimeString ,"' ,
        timestamp '", currentDateTimeString ,"' 
      )
      ")

    DBConnection.SendQuery(queryString)

    return(TRUE)

  }
}

Problems.AddProblems <- function() {

}