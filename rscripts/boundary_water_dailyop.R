cat("###########################################\n")
cat(" Execute start at: ")
cat(strftime(Sys.time()))
cat("\n")
cat("###########################################\n")



source('datalog.R')
source('boundary.R')
source('email.R')

dataType <- "WATER"
problemType <- "BD"

stationList <- getStationCodeList();
allBdProblem <- NA


flush.console()

currentTime <- Sys.time()

# for email purpose
oldProblemStationList <- getAlreadyCheckedStationList(dataType, problemType, currentTime)

data <- NA

for(station in stationList) {
  
  cat("\n==========[ STATION:")
  cat(station)
  cat(" ]==========\n")
  flush.console()
  
  cat("Getting latest run time...\n")
  flush.console()
  
  startDateTime <- getLatestProblemCheckedTime(station, dataType, problemType)
  
  if(!is.na(startDateTime)) {
    startDateTime <- startDateTime + 1
  }
  
  str(startDateTime)
  
  cat("Loading 24hr data...\n")
  flush.console()
  
  data <- get24HrWaterLevelData(station, startDateTime, currentTime)
  # data <- get24HrWaterLevelData(station, startDateTime = as.POSIXct("2012-02-20"), endDateTime = as.POSIXct("2012-02-28"), debug=TRUE)
  
  str(data)
  
  if(nrow(data) > 0) {
    
    cat("Detecting BD Problem...\n")
    flush.console()
    
    bdProblem <- searchBoundaryProblem(dataType, data)
    
    str(bdProblem)
    
    
    if(is.data.frame(bdProblem)) {
      
      if(is.na(allBdProblem)) {
        allBdProblem <- bdProblem
      } else {
        allBdProblem <- rbind(allBdProblem, bdProblem)
      }
      
      cat("Writing Logs...\n")
      flush.console()
      
      
      updateProblemLog(bdProblem, 60*10)
      
      
      
    } else {
      cat("Problem not found...\n")
    }
    
    
    
    cat("Update latest runtime...\n")
    flush.console()
    
    end_datetime <- mapply(paste, data$date, data$time)
    latestTime <- max(as.POSIXct(end_datetime))
    
    
    updateLatestProblemCheckedTime(station, dataType, problemType, latestTime)
    
  } else {
    cat("No data...\n")
  }
  
  cat("DONE!...\n")
  flush.console()
  
  
}

if(!is.na(allBdProblem)) {
  
  cat("====================\n")
  cat("Sending Emails...\n")
  cat("====================\n")
  flush.console()
  
  allStationList <- levels(allBdProblem$station_code)
  
  newProblemStation <- allStationList[!(allStationList %in% oldProblemStationList)]
  
  sendProblemMailNotification(dataType, problemType, currentTime, newProblemStation)
} else {
  cat("====================\n")
  cat("No problem found...\n")
  cat("====================\n")
  flush.console()
}


cat("###########################################\n")
cat(" Execute fininsh at: ")
cat(strftime(Sys.time()))
cat("\n")
cat("###########################################\n")

