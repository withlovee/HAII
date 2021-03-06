library('lubridate')
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

Helper.LastMonth <- function(ti, dataInterval=1) {
  month(ti) <- month(ti) - 1
  start <- floor_date(ti, "month")
  end <- start
  month(end) <- month(end) + 1
  end <- end - dataInterval
  return(list(start=start, end=end))
}

Helper.LastOperationDay <- function(ti, dataInterval=1) {

  end <- NA

  # less than or equal 07:00:00
  if(hour(ceiling_date(ti, "hour")) <= 7){
      day(ti) <- day(ti) - 1
  }

  end <- floor_date(ti, "hour")
  hour(end) <- 7

  start <- end
  day(start) <- day(start) - 1
  start <- start + dataInterval

  return(list(start = start, end = end))
}

Helper.MergeDateTime <- function(startDateTimeList, endDateTimeList) {
  merged <- list(startDateTime = min(startDateTimeList), endDateTimeList = max(endDateTimeList))
  return(merged)
}

Helper.CountDataNum <- function(startDateTime, endDateTime, dataType, waterDataInterval = Config.defaultWaterDataInterval, rainDataInterval = Config.defaultRainDataInterval) {

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
                                            waterDataInterval = Config.defaultWaterDataInterval,
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

Helper.FilterData <- function (data) {
  filteredData <- data[!(data$value == 999999 | data$value == -9999 | is.na(data$value)), ]
  return(filteredData)
}

Helper.SortByStartDateTime <- function (data) {
  sortedData <- data[order(data$datetime), ]
  return(sortedData)
}

Helper.FilterAndSort <- function (data) {
  return(Helper.SortByStartDateTime(Helper.FilterData(data)))
}

# t1 - t2
Helper.TimeDiffSecs <- function (t1, t2) {
  return(as.numeric(t1 - t2, unit="secs"))
}

Helper.AbsoluteTimeDiffSecs <- function(t1, t2) {
  return(abs(Helper.TimeDiffSecs(t1,t2)))
}