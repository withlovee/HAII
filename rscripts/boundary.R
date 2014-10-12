source('db_connection.R')

# Calculate max bank of water data by max(leftBank, rightBank)
getMaxBank <- function(leftBank, rightBank) {
  if( is.na(leftBank) & is.na(rightBank)) {
    NA  
  } else {
    max(leftBank, rightBank, na.rm = TRUE)
  }
}

isWaterLevelHaveMachineError <- function(waterLevel) {
  if(!is.na(waterLevel) & waterLevel == 999999) {
    TRUE
  } else {
    FALSE
  }
}

isOutOfBound <- function(waterLevel, groundLevel, maxBank, groundLevelOffset = -1, maxBankOffset = 4) {
  
  if(isWaterLevelHaveMachineError(waterLevel)) {
    return(FALSE)
  }
  if( any(is.na(c(waterLevel, groundLevel, maxBank))) ) {
    return(FALSE)
  }
  groundLevelOffset < -1
  maxBankOffset <- 4
  isOverGroundLevel <- waterLevel >= groundLevel + groundLevelOffset
  isUnderMaxBank <- waterLevel <= maxBank + maxBankOffset
  
  !(isOverGroundLevel & isUnderMaxBank)
}

searchBoundaryProblem <- function(data) {
  
  if(nrow(data) <= 0) {
    return(NA)
  }
  
  data$max_bank <- mapply(getMaxBank, data$left_bank, data$right_bank)
  hasBoundaryProblem <- mapply(isOutOfBound, data$water1, data$ground_level, data$max_bank)
  
  bd <- data[hasBoundaryProblem,]
  
  if(nrow(bd) <= 0) {
    return(NA)
  }

  bd$datetime <- mapply(paste, bd$date, bd$time)
  bdProblem <- data.frame(station_code = bd$code,
                          problem_type = "BD",
                          data_type = "WATER",
                          start_datetime = bd$datetime,
                          end_datetime = bd$datetime,
                          num = 1
                          )
  
  bdProblem <- bdProblem[order(bdProblem$start_datetime), ]
  
  return(bdProblem)
}

groupProblem <- function(data) {
  
  # split by station
  problemByStation <- split(data, data$code)
  
  for(code_i in seq_along(problemByStation)) {
    
    stationName <- names(problemByStation)[[code_i]]
    
  }
  
}


group <- function(data) {
  
  # sort by datetime ascending
  dataRow <- dataRow[order(dataRow$date, dataRow$time), ]
  
  startTime <- c()
  endTime <- c()
  num <- c()
  
  dataRow <- nrow(data)
  i <- 1
  
  while(i <= dataRow) {
    
    j <- i + 1
    
    while(j <= dataRow) {
      
      curr_time_str <- paste(data$date[j], data$time[j])
      prev_time_str <- paste(data$date[j-1], data$time[j-1])
      curr_time <- as.POSIXct(curr_time_str)
      prev_time <- as.POSIXct(prev_time_str)
      
      if(difftime(curr_time, prev_time, units="mins") <= 10) {
        j <- j + 1
      } else {
        break
      }
      
    }
    
    startTimeStr <- as.POSIXct( paste(data[i,"date"], data[i,"time"]))
    endTimeStr <- as.POSIXct( paste(data[j-1,"date"], data[j-1,"time"]))
    startTime <- c(startTime, strftime(startTimeStr))
    endTime <- c(endTime, strftime(endTimeStr))
    num <- c(num, j-i)
    
    i <- j
  }
  
  data.frame(startTime = startTime, endTime = endTime, num=num)
}


# rs <- dbGetQuery(con, "SELECT 
#   data_log.code, 
#   data_log.date,
#   data_log.time,
#   data_log.water1,
#   tele_wl_detail.left_bank, 
#   tele_wl_detail.right_bank,
#   tele_wl_detail.ground_level
# FROM 
#   data_log
# inner join tele_wl_detail on tele_wl_detail.code = data_log.code
# where data_log.water1 is not null and data_log.date = DATE '2012-02-28'
# and tele_wl_detail.left_bank > tele_wl_detail.right_bank
# limit 20")

# rs$max_bank <- getMaxBank(rs)
# rs
#searchBoundaryProblem(rs)

# dbDisconnect(con);
# dbUnloadDriver(drv);