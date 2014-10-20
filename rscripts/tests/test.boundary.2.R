generateSampleRainDataWithBoundaryProblem <- function() {

  #     code       date     time rain1h
  # 1 CHI001 2012-06-08 17:00:00     -1
  # 2 CHI002 2012-06-09 18:10:00      0
  # 3 CHI003 2012-06-08 19:20:00     50
  # 4 CHI004 2012-06-08 19:00:00    150
  # 5 CHI005 2012-06-12 20:20:00    200
  
  code <- c("CHI001", "CHI002", "CHI003", "CHI004", "CHI005")
  date <- c("2012-06-08", "2012-06-09", "2012-06-08", "2012-06-08", "2012-06-12")
  time <- c("17:00:00", "18:10:00", "19:20:00", "19:00:00", "20:20:00")
  rain1h <- c(-1, 0, 50, 150, 200)

  data <- data.frame(
    code=code,
    date=date,
    time=time,
    rain1h=rain1h,
    stringsAsFactors=FALSE
  )
  
  return(data)
}

generateSampleRainDataWithoutBoundaryProblem <- function() {

  code <- c("CHI001", "CHI002")
  date <- c("2012-06-08", "2012-06-12")
  time <- c("17:00:00", "18:10:00")
  rain1h <- c("0","150")

  data <- data.frame(
    code=code,
    date=date,
    time=time,
    rain1h=rain1h,
    stringsAsFactors=FALSE
  )
  
  return(data)

}

test.isRainLevelOutOfBound <- function() {
  rainLevel <- c(-10, 0, 100, 150, 170, NA)
  expected <- c(TRUE, FALSE, FALSE, FALSE, TRUE, FALSE)
  actual <- mapply(isRainLevelOutOfBound, rainLevel)
  mapply(checkEquals, expected, actual)
}

test.searchBoundaryProblemWithNoProblem <- function() {
  data <- generateSampleRainDataWithoutBoundaryProblem()

  results <- searchBoundaryProblem("RAIN", data)
  checkTrue(is.na(results))
}

test.searchRainBoundaryProblem <- function() {

  #     code       date     time rain1h
  # 1 CHI001 2012-06-08 17:00:00     -1
  # 2 CHI002 2012-06-09 18:10:00      0
  # 3 CHI003 2012-06-08 19:20:00     50
  # 4 CHI004 2012-06-08 19:00:00    150
  # 5 CHI005 2012-06-12 20:20:00    200

  data <- generateSampleRainDataWithBoundaryProblem()
  results <- searchBoundaryProblem("RAIN", data)

  # Chronological Orsource('datalog.R')
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
  
  newProblemStation <- getNewProblemStationList(dataType, problemType, currentTime, allBdProblem)
  sendProblemMailNotification(dataType, problemType, currentTime, newProblemStation)
} else {
  cat("====================\n")
  cat("No problem found...\n")
  cat("====================\n")
  flush.console()
}


der
  checkEquals("CHI001", as.character(results$station_code[1]))
  checkEquals("CHI005", as.character(results$station_code[2]))
  
  checkTrue(all(results$problem_type == "BD"))
  checkTrue(all(results$data_type == "RAIN"))
  checkTrue(all(results$num == 1))
  
  checkEquals(as.POSIXct("2012-06-08 17:00:00"), results$start_datetime[1])
  checkEquals(as.POSIXct("2012-06-12 20:20:00"), results$start_datetime[2])

  checkEquals(as.POSIXct("2012-06-08 17:00:00"), results$end_datetime[1])
  checkEquals(as.POSIXct("2012-06-12 20:20:00"), results$end_datetime[2])
  
}