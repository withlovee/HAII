source('config.R')
source('helper.R')
source('datalog2.R')
source('flat_value.R')
source('problems.R')
source('email.R')


FlatValue.Controller.Find <- function (stationCode, dataType, startDateTime, endDateTime) {

  cat("Flat Value: ", stationCode , "\n")
  data <- DataLog.GetData(stationCode, dataType, startDateTime, endDateTime)

  return(FlatValue.Find(data, dataType))

}

FlatValue.Controller.FindAll <- function (dataType, startDateTime, endDateTime, stations = NA, allStation = TRUE) {

  resultAllStation <- data.frame(stationCode = c(),
                                startDateTime = c(),
                                endDateTime = c())

  if (allStation) {
    stations <- DataLog.GetStationCodeList(dataType)
  }

  for (station in stations) {

    result <- FlatValue.Controller.Find(station, dataType, startDateTime, endDateTime)

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

FlatValue.Controller.Batch <- function (dataType, startDateTime, endDateTime, addToDB=TRUE, stations = NA, allStation = TRUE) {

  problemType <- "FV"
  flatValue <- FlatValue.Controller.FindAll(dataType, startDateTime, endDateTime, stations = stations, allStation = allStation)

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
    dataInterval <- Config.defaultWaterDataInterval
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
  problemsStationCode <- unique(flatValue[flatValue$endDateTime >= Helper.StartOfDay(currentDateTime)]$stationCode)
  newStation <- setdiff(problemsStationCode, alreadySentStationCode)
  # Problems.SendNewProblemNotification(newStation, dataType, problemType, currentDateTime)
  # Email.sendMailNotification(dataType, problemType, currentDateTime, newStation, "instantly")

  # send email
  return(flatValue)
}