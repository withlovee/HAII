source('datalog2.R')
source('config.R')
source('problems.R')
source('missing_gap.R')

MissingGap.Controller.FindMissingGap <- function(
  stationCode, dataType, startDateTime, endDateTime) {

  cat("Missing Gap: ", stationCode , "\n")
  missingGap <- NA
  data <- DataLog.GetData(stationCode, dataType, startDateTime, endDateTime)
  installedDate <- DataLog.GetInstallationDate(stationCode)
  
  missingGap <- MissingGap.FindMissingGap(data, dataType, startDateTime, endDateTime, installedDate)
  return(missingGap)
}

MissingGap.Controller.FindAllMissingGap <- function(dataType, startDateTime, endDateTime) {
  resultAllStation <- data.frame(stationCode = c(),
                                startDateTime = c(),
                                endDateTime = c())

  stations <- DataLog.GetStationCodeList(dataType)

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

MissingGap.Controller.Batch <- function (dataType, startDateTime, endDateTime, addToDB = TRUE) {

  problemType <- "MG"
  missingGap <- MissingGap.Controller.FindAllMissingGap(dataType, startDateTime, endDateTime)

  if (addToDB) {
    # update problem
    print("Adding Problems")
    # str(outOfRange)
    Problems.AddProblems(missingGap, dataType, problemType)
  }

  return(missingGap)
}


MissingGap.Controller.DailyOperation <- function(dataType, interval = NULL) {

  currentDateTime <- Sys.time()
  problemType <- "MG"
  
  dataInterval <- NA
  
  if(dataType == "WATER") {
    dataInterval <- Config.defaultWaterDataInterval
  } else if(dataType == "RAIN") {
    dataInterval <- Config.defaultRainDataInterval
  }

  # set default interval
  if (is.null(interval)) {
    if (Config.MissingGap.defaultInterval > 60*60) {
      interval <- Config.MissingGap.defaultInterval + (3 * dataInterval)
    } else {
      interval <- 60 * 60 + (3 * dataInterval)
    }
  }
  
  startDateTime <- currentDateTime - interval
  
  alreadySentStationCode <- Problems.GetLatestProblemStationCodeList(dataType, problemType, currentDateTime)
      
  missingGap <- MissingGap.Controller.Batch(dataType, startDateTime, currentDateTime)

  # update problem
  # send email

  problemsStationCode <- unique(missingGap$stationCode)
  newStation <- setdiff(problemsStationCode, alreadySentStationCode)
  # Problems.SendNewProblemNotification(newStation, dataType, problemType, currentDateTime)
  Email.sendMailNotification(dataType, problemType, currentDateTime, newStation, "instantly")

  return(missingGap)
}
