source('config.R')
source('helper.R')
source('flat_value.R')


FlatValue.Controller.FindFlatValue(stationCode, dataType, startDateTime, endDateTime) <- function () {

  cat("Flat Value: ", stationCode , "\n")
  data <- DataLog.GetData(stationCode, dataType, startDateTime, endDateTime)

  return(FlatValue.FindFlatValue(data, dataType))

}

FlatValue.Controller.FindAllFlatValue() <- function (dataType, startDateTime, endDateTime) {

  resultAllStation <- data.frame(stationCode = c(),
                                startDateTime = c(),
                                endDateTime = c())

  stations <- DataLog.GetStationCodeList()

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

FlatValue.Controller.DailyOperation() <- function (dataType, interval=NULL) {

  currentTime <- Sys.time()
  problemType <- "FV"

  # set default interval
  if (is.null(interval)) {
    if (Config.FlatValue.defaultThreshold > 60*60) {
      interval <- Config.FlatValue.defaultThreshold + (3 * Config.defaultDataInterval)
    } else {
      interval <- 60 * 60 + (3 * Config.defaultDataInterval)
    }
  }

  startDateTime = currentTime - interval

  flatValue <- FlatValue.Controller.FindFlatValue(dataType, startDateTime, endDateTime)

  # update problem
  problemsStationCode <- unique(flatValue$stationCode)
  newStation <- setdiff(problemsStationCode, alreadySentStationCode)
  Problems.AddProblems(flatValue, dataType, problemType)
  Problems.SendNewProblemNotification(newStation, dataType, problemType, currentTime)

  # send email
  return(flatValue)
}