source('datalog.R')
source('boundary.R')
source('email.R')

dataType <- "WATER"
problemType <- "BD"

stationList <- getStationCodeList();
allBdProblem <- NA

cat("###########################################\n")
cat(" Date Executed: ")
cat(strftime(Sys.time()))
cat("\n")
cat("###########################################\n")

flush.console()

currentTime <- Sys.time()

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
  
  # data <- get24HrWaterLevelData(station, startDateTime, currentTime)
  data <- get24HrWaterLevelData(station, startDateTime = as.POSIXct("2012-02-20"), endDateTime = as.POSIXct("2012-02-28"), debug=TRUE)
  
  str(data)
  
  if(nrow(data) > 0) {
  
    cat("Detecting BD Problem...\n")
    flush.console()
    
    bdProblem <- searchBoundaryProblem(dataType, data)
    
    str(bdProblem)
    
    if(is.na(allBdProblem)) {
      allBdProblem <- bdProblem
    } else {
      allBdProblem <- rbind(allBdProblem, bdProblem)
    }
    
    
    if(is.data.frame(bdProblem)) {
      
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

cat("=================\n")
cat("Sending Emails...\n")
cat("=================\n")
flush.console()

newProblemStation <- getNewProblemStationList(dataType, problemType, currentTime, allBdProblem)
sendProblemMailNotification(dataType, problemType, currentTime, newProblemStation)