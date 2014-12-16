source('config.R')

MissingGap.FindMissingPoint <- function(data, startDateTime, endDateTime,
  dataInterval    = Config.defaultDataInterval,
  missingInterval = Config.MissingGap.defaultInterval,
  verbose = FALSE) {

  # use only datetime of data
  dataRows <- nrow(data)
  dt <- data$datetime

  # order by datetime
  data <- data[order(data$datetime),]

  # remove na
  dt <- dt[!is.na(dt)]

  # find missing point by differencing with all possible datetime
  allTimePossible = seq(startDateTime, endDateTime, dataInterval)
  missingPoint <- setdiff(allTimePossible, dt)
  class(missingPoint) <- "POSIXct"

  # order again
  missingPoint <- missingPoint[order(missingPoint)]

  # verbose
  if (verbose) {
    cat("Missing Point:\n")
    print(missingPoint)
    # cat(paste(strftime(missingPoint), collapse="\n"))
    cat("\n")
  }

  return(missingPoint)

}

MissingGap.FindMissingGap <- function (data, startDateTime, endDateTime,
  dataInterval = Config.defaultDataInterval,
  missingInterval    = Config.MissingGap.defaultInterval,
  verbose = FALSE) {

  dt <- data$datetime
  # Error Handling
  if (dt[1] < startDateTime) {
    stop("Data must be in range of startDateTime, endDateTime")
  }
  if (tail(dt, n = 1) > endDateTime) {
    stop("Data must be in range of startDateTime, endDateTime")
  }


  # find missing time point
  missingPoint <- MissingGap.findMissingPoint(data, startDateTime, endDateTime,
                                              dataInterval, missingInterval,
                                              verbose)  

  # merge missing time point into missing range
  # and keep only range that exceed threshold (missingInterval)
  missingStart <- c()
  missingEnd <- c()

  # sliding window
  ti <- 1
  missingPointNum = length(missingPoint)
  while (ti <= missingPointNum) {
    tj <- ti

    while (tj < missingPointNum) {
      if (missingPoint[tj + 1] - missingPoint[tj] == dataInterval) {
        tj <- tj + 1
      } else {
        break
      }
    }

    # filter if range > 7 days ? (defaultInterval)
    if (missingPoint[tj] - missingPoint[ti] >= missingInterval) {
      missingStart <- c(missingStart, missingPoint[ti])
      missingEnd <- c(missingEnd, missingPoint[tj])
    }
    
    ti <- tj + 1
  }

  # convert data to POSIXct (time)
  if (length(missingStart) > 0 & length(missingEnd) > 0) {
    class(missingStart) <- "POSIXct"
    class(missingEnd) <- "POSIXct"
  }

  result <- data.frame(startDateTime = missingStart, endDateTime = missingEnd)

  if (verbose) {
    cat("Missing Gap\n")
    print(result)
  }

  return(result)

}