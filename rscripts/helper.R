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

Helper.CountDataNum <- function(startDateTime, endDateTime, dataInterval = Config.defaultDataInterval) {
  diff <- as.numeric(endDateTime - startDateTime)
  num <- round(diff / Config.defaultDataInterval) + 1

  return(num)
}


Helper.FullProblemNameFromAbbr <- function(abbr) {
  
  if (abbr == "BD") {
    fullName <- "Out of Range"
  } else if (abbr == "FV") {
    fullName <- "Flat Value"
  } else if (abbr == "MV") {
    fullName <- "Missing Value"
  } else if (abbr == "OL") {
    fullName <- "Outliers"
  } else if (abbr == "HM") {
    fullName <- "Inhomogenity"
  } else if (abbr == "MP") {
    fullName <- "Missing Pattern"
  }
  
  return(fullName)
}
