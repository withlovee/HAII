source('datalog2.R')
source('config.R')
source('problems.R')
source('out_of_range.R')

OutOfRange.Controller.FindOutOfRange <- function (stationCode, dataType, startDateTime, endDateTime) {

  cat("Out of Range: ", stationCode , "\n")

  data <- DataLog.GetData(stationCode, dataType, startDateTime, endDateTime)
  
  # print(data)
  
  outOfRange <- OutOfRange.FindOutOfRange(data, dataType)
  return(outOfRange)

}

OutOfRange.Controller.FindAllOutOfRange <- function (dataType, startDateTime, endDateTime) {

  resultAllStation <- data.frame(stationCode = c(),
                                startDateTime = c(),
                                endDateTime = c())

  stations <- DataLog.GetStationCodeList()

  for (station in stations) {

    result <- OutOfRange.Controller.FindOutOfRange(station, dataType, startDateTime, endDateTime)

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

OutOfRange.Controller.DailyOperation <- function (dataType) {

  problemType <- "OR"

  currentDateTime <- Sys.time()

  startDateTime <- currentDateTime - Config.OutOfRange.backwardThreshold

  outOfRange <- OutOfRange.Controller.FindAllOutOfRange(dataType, startDateTime, endDateTime)

  # update problem
  problemsStationCode <- unique(outOfRange$stationCode)

  newStation <- setdiff(problemsStationCode, alreadySentStationCode)
  
  Problems.AddProblems(outOfRange, dataType, problemType)

  Problems.SendNewProblemNotification(newStation, dataType, problemType, currentTime)

  # send email
  return(outOfRange)
}