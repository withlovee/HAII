source('config.R')

Helper.POSIXctToString <- function(time) {
  return(strftime(time, "%Y-%m-%d %H:%M:%S"))
}

Helper.StartOfDay <- function(t) {
  t <- as.POSIXct(t)
  
  a <- as.POSIXlt(t)
  a$hour <- 7
  a$min <- 0
  a$sec <- 0
  
  a <- as.POSIXct(a)
  
  if(a > t) {
    # yesterday
    a <- a - 86400
  }
  
  return(a)
}

Helper.MergeDateTime <- function(startDateTimeList, endDateTimeList) {
  merged <- list(startDateTime = min(startDateTimeList), endDateTimeList = max(endDateTimeList))
  return(merged)
}

Helper.CountDataNum <- function(startDateTime, endDateTime, dataType, waterDataInterval = Config.defaultDataInterval, rainDataInterval = Config.defaultRainDataInterval) {

  dataInterval <- NA
  if (dataType == "WATER") {
    dataInterval <- waterDataInterval
  } else if (dataType == "RAIN") {
    dataInterval <- rainDataInterval
  }

  diff <- as.numeric(endDateTime - startDateTime, units="secs")
  num <- round(diff / dataInterval) + 1

  return(num)
}


Helper.FullProblemNameFromAbbr <- function(abbr) {
  
  fullName <- ""
  
  if (abbr == "OR") {
    fullName <- "Out of Range"
  } else if (abbr == "FV") {
    fullName <- "Flat Value"
  } else if (abbr == "MG") {
    fullName <- "Missing Gap"
  } else if (abbr == "OL") {
    fullName <- "Outliers"
  } else if (abbr == "HM") {
    fullName <- "Inhomogenity"
  } else if (abbr == "MP") {
    fullName <- "Missing Pattern"
  }
  
  return(fullName)
}

Helper.MergeConsecutiveDateTime <- function(dateTimeList, dataType, 
                                            waterDataInterval = Config.defaultDataInterval,
                                            rainDataInterval = Config.defaultRainDataInterval,
                                            consecutiveThreshold = Config.consecutiveProblemGapThreshold) {
  
  dataInterval <- NA
  if (dataType == "WATER") {
    dataInterval <- waterDataInterval
  } else if (dataType == "RAIN") {
    dataInterval <- rainDataInterval
  }

  l <- length(dateTimeList)

  if (l == 0) {
    return(data.frame(startDateTime=c(), endDateTime=c()))
  }

  startDateTime <- c()
  endDateTime <- c()

  dateTimeList <- dateTimeList[order(dateTimeList)]
  
  # print(dateTimeList)
  
  i <- 1
  
  if (l >= 2) {
    for (j in 2:l) {
      # cat("checking", dateTimeList[j-1], ":", dateTimeList[j], "\n")
      if (as.numeric(dateTimeList[j] - dateTimeList[j-1], unit="secs") > dataInterval * consecutiveThreshold) {
        # i to j-1 is consecutive
        # cat("cut\n")
        startDateTime <- c(startDateTime, dateTimeList[i])
        endDateTime <- c(endDateTime, dateTimeList[j-1])
        
        # cat(i, " ", j-1, "\n")
        i <- j
      }
    }
  }
  
  startDateTime <- c(startDateTime, dateTimeList[i])
  endDateTime <- c(endDateTime, dateTimeList[l])

  class(startDateTime) <- "POSIXct"
  class(endDateTime) <- "POSIXct"

  return(data.frame(startDateTime = startDateTime, endDateTime = endDateTime))

}

Helper.CheckDataType <- function (dataType) {

  if(!any(dataType == Config.allowDataType)) {
    stop("dataType", dataType ,"incorrect")
  }

}