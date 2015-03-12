source('datalog2.R')
source('config.R')
source('problems.R')
source('inhomogeneity.R')

Inhomogeneity.Controller.Find <- function(
  stationCode, dataType, startDateTime, endDateTime) {

  cat("Inhomogeneity: ", stationCode , "\n")
  changePoint <- NA
  data <- DataLog.GetData(stationCode, dataType, startDateTime, endDateTime)
  changePoint <- Inhomogeneity.Find(data, dataType)
  return(changePoint)
}

Inhomogeneity.Controller.FindAll <- function(dataType, startDateTime, endDateTime) {
  resultAllStation <- data.frame(stationCode = c(),
                                startDateTime = c(),
                                endDateTime = c())

  stations <- DataLog.GetStationCodeList(dataType)

  for (station in stations) {

    result <- Inhomogeneity.Controller.Find(station, dataType, startDateTime, endDateTime)

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

Inhomogeneity.Controller.Batch <- function (dataType, startDateTime, endDateTime, addToDB = TRUE) {

  problemType <- "HM"
  changePoint <- Inhomogeneity.Controller.FindAll(dataType, startDateTime, endDateTime)

  if (addToDB) {
    # update problem
    print("Adding Problems")
    # str(outOfRange)
    Problems.AddProblems(changePoint, dataType, problemType)
  }

  return(changePoint)
}

Inhomogeneity.Controller.MonthlyOperation <- function(currentDateTime = Sys.time(), addToDB = TRUE) {

  dataType <- "WATER"
  problemType <- "HM"

  lastMonth <- Helper.LastMonth(currentDateTime, Config.defaultWaterDataInterval)
  print(lastMonth)

  changePoint <- Inhomogeneity.Controller.Batch(dataType, lastMonth$start, lastMonth$end, addToDB)

  # no need, send monthly report instead
  # Problems.SendNewProblemNotification(station, dataType, problemType, currentDateTime)

  return(changePoint)

}
