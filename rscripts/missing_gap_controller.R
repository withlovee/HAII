source('datalog2.R')
source('config.R')
source('problems.R')
source('missing_gap.R')

MissingGap.Controller.FindMissingGap <- function(
  stationCode, dataType, startDateTime, endDateTime) {

  cat("Missing Gap: ", stationCode , "\n")

  data <- DataLog.GetData(stationCode, dataType, startDateTime, endDateTime)
  missingGap <- MissingGap.FindMissingGap(data, startDateTime, endDateTime)
  return(missingGap)
}

MissingGap.Controller.FindAllMissingGap <- function(dataType, startDateTime, endDateTime) {
  resultAllStation <- data.frame(stationCode = c(),
                                startDateTime = c(),
                                endDateTime = c())

  stations <- DataLog.GetStationCodeList()

  for (station in stations) {

    result <- MissingGap.Controller.FindMissingGap(station, dataType, startDateTime, endDateTime)

    if(is.data.frame(result)) {
      if(nrow(result) > 0) {
        result$stationCode <- station
        resultAllStation <-rbind(resultAllStation, result)
      }
    }
  }

  if (nrow(resultAllStation) == 0) {
    return(NULL)
  }

  return(resultAllStation)

} 


MissingGap.Controller.HourlyOperation <- function(dataType,
  interval = NULL) {

  currentTime <- Sys.time()
  problemType <- "MV"

  # set default interval
  if (is.null(interval)) {
    if (Config.MissingGap.defaultInterval > 60*60) {
      interval <- Config.MissingGap.defaultInterval + (2 * Config.defaultDataInterval)
    } else {
      interval <- 60 * 60 + (2 * Config.defaultDataInterval)
    }
  }

  startTime = currentTime - interval

  alreadySentStationCode <- Problems.GetLatestProblemStationCodeList(dataType, problemType, currentTime)

  missingGap <- MissingGap.Controller.FindAllMissingGap(dataType, startTime, currentTime)

  # update problem
  problemsStationCode <- unique(missingGap$stationCode)

  newStation <- setdiff(problemsStationCode, alreadySentStationCode)
  
  Problems.AddProblems(missingGap, dataType, problemType)

  Problems.SendNewProblemNotification(newStation, dataType, problemType, currentTime)

  # send email
  return(missingGap)
}