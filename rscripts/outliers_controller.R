source('datalog2.R')
source('config.R')
source('problems.R')
source('outliers.R')

Outliers.Controller.Find <- function(
  stationCode, dataType, startDateTime, endDateTime) {

  cat("Outliers: ", stationCode , "\n")
  outliers <- NA
  data <- DataLog.GetData(stationCode, dataType, startDateTime, endDateTime)
  outliers <- Outliers.Find(data, dataType)
  return(outliers)
}

Outliers.Controller.FindAll <- function(dataType, startDateTime, endDateTime, stations = NA, allStation = TRUE) {
  resultAllStation <- data.frame(stationCode = c(),
                                startDateTime = c(),
                                endDateTime = c())

  if (allStation) {
    stations <- DataLog.GetStationCodeList(dataType)
  }

  for (station in stations) {

    result <- Outliers.Controller.Find(station, dataType, startDateTime, endDateTime)

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

Outliers.Controller.Batch <- function (dataType, startDateTime, endDateTime, addToDB = TRUE, stations = NA, allStation = TRUE) {

  problemType <- "OL"
  outliers <- Outliers.Controller.FindAll(dataType, startDateTime, endDateTime, stations = stations, allStation = allStation)

  if (addToDB) {
    # update problem
    print("Adding Problems")
    # str(outOfRange)
    Problems.AddProblems(outliers, dataType, problemType)
  }

  return(outliers)
}

Outliers.Controller.MonthlyOperation <- function(currentDateTime = Sys.time(), addToDB = TRUE) {

  dataType <- "WATER"
  problemType <- "OL"

  lastMonth <- Helper.LastMonth(currentDateTime, Config.defaultWaterDataInterval)
  print(lastMonth)

  outliers <- Outliers.Controller.Batch(dataType, lastMonth$start, lastMonth$end, addToDB)

  # no need, send monthly report instead
  # Problems.SendNewProblemNotification(station, dataType, problemType, currentDateTime)

  return(outliers)

}
