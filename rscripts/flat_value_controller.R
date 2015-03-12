source('config.R')
source('helper.R')
source('datalog2.R')
source('flat_value.R')
source('problems.R')


FlatValue.Controller.FindFlatValue <- function (stationCode, dataType, startDateTime, endDateTime) {

  cat("Flat Value: ", stationCode , "\n")
  data <- DataLog.GetData(stationCode, dataType, startDateTime, endDateTime)

  return(FlatValue.FindFlatValue(data, dataType))

}

FlatValue.Controller.FindAllFlatValue <- function (dataType, startDateTime, endDateTime) {

  resultAllStation <- data.frame(stationCode = c(),
                                startDateTime = c(),
                                endDateTime = c())

  stations <- DataLog.GetStationCodeList(dataType)

  for (station in stations) {

    result <- FlatValue.Controller.FindFlatValue(station, dataType, startDateTime, endDateTime)

    if(is.data.frame(result)) {
      if(nrow(result) > 0) {
        
        cat(nrow(result), " Found.\n")
        
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

FlatValue.Controller.Batch <- function (dataType, startDateTime, endDateTime, addToDB=TRUE) {

  problemType <- "FV"
  flatValue <- FlatValue.Controller.FindAllFlatValue(dataType, startDateTime, endDateTime)

  if (addToDB) {
    print("Adding Problems")
    Problems.AddProblems(flatValue, dataType, problemType)
  }

  return(flatValue)

}

FlatValue.Controller.DailyOperation <- function (dataType, interval=NULL) {

  currentDateTime <- Sys.time()
  problemType <- "FV"

  dataInterval <- NA
  
  if(dataType == "WATER") {
    dataInterval <- Config.defaultDataInterval
  } else if(dataType == "RAIN") {
    dataInterval <- Config.defaultRainDataInterval
  }

  # set default interval
  if (is.null(interval)) {
    if (Config.FlatValue.defaultThreshold > 60*60) {
      interval <- Config.FlatValue.defaultThreshold + (3 * dataInterval)
    } else {
      interval <- 60 * 60 + (3 * dataInterval)
    }
  }

  startDateTime = currentDateTime - interval

  alreadySentStationCode <- Problems.GetLatestProblemStationCodeList(dataType, problemType, currentDateTime)

  flatValue <- FlatValue.Controller.Batch(dataType, startDateTime, currentDateTime)

  # update problem
  problemsStationCode <- unique(flatValue$stationCode)
  newStation <- setdiff(problemsStationCode, alreadySentStationCode)
  # Problems.SendNewProblemNotification(newStation, dataType, problemType, currentDateTime)
  Email.sendMailNotification(dataType, problemType, currentDateTime, newStationCode, "instantly")

  # send email
  return(flatValue)
}