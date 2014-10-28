source('datalog.R')
source('boundary.R')

DAY <- 60*60*24





boundaryBatch <- function(startDateTime , endDateTime, dataType) {
  t <- startDateTime
  
  stations <- getStationCodeList()

  while(t < endDateTime) {
    
    cat("\n#### running ")
    cat(strftime(t))
    cat("\n")
    
    for(station in stations) { 
      boundaryBatchOneDay(t, station, dataType)
    }
    
    t <- t + DAY
  } 
}

boundaryBatchOneDay <- function(startDateTime, stationCode, dataType) {
  
  data <- data.frame()
  
  if(dataType == "WATER") {
    data <- getWaterLevelData(stationCode, startDateTime, startDateTime + DAY)
  } else if(dataType == "RAIN") {
    data <- getRainLevelData(stationCode, startDateTime, startDateTime + DAY)
  }
  
  
  
  if(nrow(data) > 0) {
    bdProblem <- searchBoundaryProblem(data=data, dataType=dataType)
    if(is.data.frame(bdProblem)) {
      cat(strftime(startDateTime))
      cat(" ")
      cat(stationCode)
      cat(" !!!!!\n")
    }
    updateProblemLog(problems=bdProblem , 60*10)
  }
  
}


# change to command args later
main <- function() {
  
  cat("Execution start: ")
  cat(strftime(Sys.time()))
  cat("\n\n")
  
  startDateTime <- getStartOfDayOperationDateTime(as.POSIXct(args[1]))
  endDateTime <- getStartOfDayOperationDateTime(as.POSIXct(args[2]))
  
  # startDateTime <- getStartOfDayOperationDateTime(as.POSIXct("2014-01-01"))
  # endDateTime <- getStartOfDayOperationDateTime(as.POSIXct("2014-10-01"))

  # boundaryBatch(startDateTime, endDateTime, "WATER")
  boundaryBatch(startDateTime, endDateTime, "RAIN")
  
  cat("\nExecution end: ")
  cat(strftime(Sys.time()))
  cat("\n")
}

main()