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
  diff <- as.numeric(result$endDateTime - result$startDateTime)
  num <- round(diff / Config.defaultDataInterval) + 1

  return(num)
}