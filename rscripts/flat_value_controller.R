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

FlatValue.Controller.DailyOperation() <- function (dataType) {
  problemType <- "FV"
  currentDateTime <- Sys.time()
  startDateTime <- currentDateTime - Config.OutOfRange.backwardThreshold

  flatValue <- FlatValue.Controller.FindFlatValue(dataType, startDateTime, endDateTime)

  # update problem
  problemsStationCode <- unique(flatValue$stationCode)
  newStation <- setdiff(problemsStationCode, alreadySentStationCode)
  Problems.AddProblems(flatValue, dataType, problemType)
  Problems.SendNewProblemNotification(newStation, dataType, problemType, currentTime)

  # send email
  return(flatValue)
}