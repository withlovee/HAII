source('config.R')


OutOfRange.WaterLevel <- function (waterLevel, groundLevel, leftBankLevel, rightBankLevel, 
                                  groundLevelOffset = Config.OutOfRange.Water.groundLevelOffset,
                                  bankLevelOffset = Config.OutOfRange.Water.bankOffset) {

  if(all(is.na(c(leftBankLevel, rightBankLevel)))) {
    return(FALSE)
  }
  bankLevel <- max(leftBankLevel, rightBankLevel, na.rm = TRUE)
  
  # cat(waterLevel, " ", groundLevel, " ", leftBankLevel, " ", rightBankLevel, "\n")
  
  if (any(is.na(c(waterLevel, groundLevel, bankLevel))) | waterLevel == 999999 | waterLevel == -9999) {
    return(FALSE)
  }
  # cat("valid\n")

  bankLevel <- bankLevel + bankLevelOffset
  groundLevel <- groundLevel + groundLevelOffset

  return(waterLevel < groundLevel | waterLevel > bankLevel)

}

OutOfRange.RainLevel <- function (rainLevel,
                                  threshold = Config.OutOfRange.Rain.threshold) {

  if (is.na(rainLevel)) {
    return(FALSE)
  }

  if (rainLevel == 999999 | rainLevel == -9999) {
    return(FALSE)
  }
  
  # cat(rainLevel, " " , threshold, " " , rainLevel > threshold, " ", rainLevel < 0 | rainLevel > threshold, "\n")
  # print(rainLevel)
  return(rainLevel < 0 | rainLevel > threshold)
}

OutOfRange.FindOutOfRange <- function(data, dataType) {

  if (!is(data, "data.frame")) {
    return(NULL)
  }

  problemIdx <- NA

  if (dataType == "WATER") {
    problemIdx <- mapply(OutOfRange.WaterLevel, data$water1, data$ground_level, data$left_bank, data$right_bank)
  } else if (dataType == "RAIN") {
    problemIdx <- mapply(OutOfRange.RainLevel, data$rain1h)
  }

  if(any(is.na(problemIdx))) {
    return(NULL)  
  }
  
  problemData <- data[problemIdx, ]
  
  # print(problemIdx)
  # print(problemIdx)
  # print(problemData)
  

  result <- Helper.MergeConsecutiveDateTime(problemData$datetime)

  return(result)

}