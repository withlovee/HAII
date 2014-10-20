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

isWaterLevelOutOfBound <- function(waterLevel, groundLevel, maxBank, groundLevelOffset = -1, maxBankOffset = 4) {
  
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

isRainLevelOutOfBound <- function(rainLevel) {
  
  if(is.na(rainLevel)) {
    return(FALSE)
  } else if(rainLevel < 0 | rainLevel > 150) {
    return(TRUE)
  } else {
    return(FALSE)
  }
}

searchBoundaryProblem <- function(dataType, data) {
  
  if(nrow(data) <= 0) {
    return(NA)
  }
  
  hasBoundaryProblem <- FALSE
  
  if(dataType == "WATER") {
    data$max_bank <- mapply(getMaxBank, data$left_bank, data$right_bank)
    hasBoundaryProblem <- mapply(isWaterLevelOutOfBound, data$water1, data$ground_level, data$max_bank)
  } else if(dataType == "RAIN") {
    hasBoundaryProblem <- mapply(isRainLevelOutOfBound, data$rain1h)
  } else {
    return(NA)
  }
  
  bd <- data[hasBoundaryProblem,]
  
  if(nrow(bd) <= 0) {
    return(NA)
  }

  bd$datetime <- mapply(paste, bd$date, bd$time)
  bdProblem <- data.frame(station_code = bd$code,
                          problem_type = "BD",
                          data_type = dataType,
                          start_datetime = bd$datetime,
                          end_datetime = bd$datetime,
                          num = 1
                          )
  
  bdProblem <- bdProblem[order(bdProblem$start_datetime), ]
  
  return(bdProblem)
}
