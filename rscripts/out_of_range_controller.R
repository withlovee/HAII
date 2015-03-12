source('datalog2.R')
source('config.R')
source('problems.R')
source('out_of_range.R')
source('email.R')

OutOfRange.Controller.FindOutOfRange <- function (stationCode, dataType, startDateTime, endDateTime) {

  cat("Out of Range: ", stationCode , "\n")

  data <- DataLog.GetData(stationCode, dataType, startDateTime, endDateTime)

  riverLevel <- DataLog.GetWaterStationRiverLevel(stationCode)
  
  if(nrow(riverLevel) > 0) {
    data <- merge(data, riverLevel[1,])
  } else {
    data$left_bank = NA
    data$right_bank = NA
    data$ground_level = NA
  }
  
  outOfRange <- OutOfRange.FindOutOfRange(data, dataType)
  return(outOfRange)

}

OutOfRange.Controller.FindAllOutOfRange <- function (dataType, startDateTime, endDateTime) {

  resultAllStation <- data.frame(stationCode = c(),
                                startDateTime = c(),
                                endDateTime = c())

  stations <- DataLog.GetStationCodeList(dataType)

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

OutOfRange.Controller.Batch <- function (dataType, startDateTime, endDateTime, addToDB = TRUE) {

  problemType <- "OR"
  outOfRange <- OutOfRange.Controller.FindAllOutOfRange(dataType, startDateTime, endDateTime)

  if (addToDB) {
    # update problem
    print("Adding Problems")
    # str(outOfRange)
    Problems.AddProblems(outOfRange, dataType, problemType)
  }

  return(outOfRange)

}

OutOfRange.Controller.DailyOperation <- function (dataType) {
  problemType <- "OR"

  currentDateTime <- Sys.time()
  startDateTime <- currentDateTime - Config.OutOfRange.backwardThreshold

  alreadySentStationCode <- Problems.GetLatestProblemStationCodeList(dataType, problemType, currentDateTime)

  outOfRange <- OutOfRange.Controller.Batch(dataType, startDateTime, currentDateTime)

  problemsStationCode <- unique(outOfRange$stationCode)
  newStation <- setdiff(problemsStationCode, alreadySentStationCode)
  # Problems.SendNewProblemNotification(newStation, dataType, problemType, currentDateTime)
  Email.sendMailNotification(dataType, problemType, currentDateTime, newStation, "instantly")

  # send email
  return(outOfRange)
}