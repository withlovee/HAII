source('config.R')

MissingGap.FindMissingGap <- function(data, startDateTime, endDateTime,
  dataInterval = Config.defaultDataInterval,
  missingInterval    = Config.MissingGap.defaultInterval,
  verbose = FALSE) {

  # null
  if (is.null(data)) {
    diff <- as.numeric(endDateTime - startDateTime, units="secs")
    
    if (diff > dataInterval & diff > missingInterval) {
      result <- data.frame(start = c(startDateTime + 1), end = c(endDateTime - 1))
      return(result)
    } else {
      return(NULL)
    }
  }


  # Error Handling
  if (!("data.frame" %in% class(data))) {
    stop("Data must be dataframe")
  }
  if (nrow(data) == 0) {
    return(data.frame(startDateTime=c(), endDateTime=c()))
  }

  dt <- data$datetime
  dt <- dt[order(dt)]

  # More Error Handling
  if (dt[1] < startDateTime) {
    stop("Data must be in range of startDateTime, endDateTime")
  }
  if (tail(dt, n = 1) > endDateTime) {
    stop("Data must be in range of startDateTime, endDateTime")
  }

  # start
  dt <- dt[!is.na(dt)]

  missingStart <- c()
  missingEnd <- c()
  
  dt1 <- c(startDateTime, dt)
  dt2 <- c(dt, endDateTime)

  diff <- as.numeric(dt2 - dt1, units="secs")

  missingIdx <- which(diff > dataInterval & diff > missingInterval)

  result <- data.frame(start = dt1[missingIdx] + 1, end = dt2[missingIdx] - 1)

  return(result)

}

#####################
#   Old Algorithm   #
#####################

# MissingGap.FindMissingPoint <- function(data, startDateTime, endDateTime,
#   dataInterval = Config.defaultDataInterval,
#   verbose = FALSE) {

#   # Find all missing time point between startDateTime and endDateTime
#   #
#   # Args:
#   #   data: data frame of raw data, must contain $datetime column
#   #   startDateTime: (POSIXct) start time of interval
#   #   endDateTime: (String) end time of interval
#   # Optional:
#   #   dataInterval: (Numeric) time between each data in seconds
#   #   verbose: (Boolean) verbose mode
#   #
#   # Returns:
#   #   vector of POSIXct missing time point

#   # use only datetime of data
#   dataRows <- nrow(data)
#   dt <- data$datetime

#   # order by datetime
#   data <- data[order(data$datetime),]

#   # remove na
#   dt <- dt[!is.na(dt)]

#   # find missing point by differencing with all possible datetime
#   allTimePossible = seq(startDateTime, endDateTime, dataInterval)
#   missingPoint <- setdiff(allTimePossible, dt)
#   class(missingPoint) <- "POSIXct"

#   # order again
#   missingPoint <- missingPoint[order(missingPoint)]

#   # verbose
#   if (verbose) {
#     cat("Missing Point:\n")
#     print(missingPoint)
#     cat("\n")
#   }

#   return(missingPoint)

# }

# MissingGap.FindMissingGap <- function (data, startDateTime, endDateTime,
#   dataInterval = Config.defaultDataInterval,
#   missingInterval    = Config.MissingGap.defaultInterval,
#   verbose = FALSE) {

#   # Find all missing time gap between startDateTime and endDateTime
#   #
#   # Args:
#   #   data: data frame of raw data, must contain $datetime column
#   #   startDateTime: (POSIXct) start time of interval
#   #   endDateTime: (String) end time of interval
#   # Optional:
#   #   dataInterval: (Numeric) time between each data in seconds
#   #   missingInterval: (Numeric) threshold for gap to be marked as missing gap 
#   #   verbose: (Boolean) verbose mode
#   #
#   # Returns:
#   #   data frame of startDateTime and endDateTime

  
#   # Error Handling
#   if (!("data.frame" %in% class(data))) {
#     stop("Data must be dataframe")
#   }
#   if (nrow(data) == 0) {
#     return(data.frame(startDateTime=c(), endDateTime=c()))
#   }
#   dt <- data$datetime

#   if (dt[1] < startDateTime) {
#     stop("Data must be in range of startDateTime, endDateTime")
#   }
#   if (tail(dt, n = 1) > endDateTime) {
#     stop("Data must be in range of startDateTime, endDateTime")
#   }

#   # find missing time point
#   missingPoint <- MissingGap.FindMissingPoint(data, startDateTime, endDateTime,
#                                               dataInterval, verbose)  

#   # merge missing time point into missing range
#   # and keep only range that exceed threshold (missingInterval)
#   missingStart <- c()
#   missingEnd <- c()

#   # sliding window
#   ti <- 1
#   missingPointNum = length(missingPoint)
#   while (ti <= missingPointNum) {
#     tj <- ti

#     while (tj < missingPointNum) {
#       if (missingPoint[tj + 1] - missingPoint[tj] == dataInterval) {
#         tj <- tj + 1
#       } else {
#         break
#       }
#     }

#     # filter if range > 7 days ? (defaultInterval)
#     if (missingPoint[tj] - missingPoint[ti] >= missingInterval) {
#       missingStart <- c(missingStart, missingPoint[ti])
#       missingEnd <- c(missingEnd, missingPoint[tj])
#     }
    
#     ti <- tj + 1
#   }

#   # convert data to POSIXct (time)
#   if (length(missingStart) > 0 & length(missingEnd) > 0) {
#     class(missingStart) <- "POSIXct"
#     class(missingEnd) <- "POSIXct"
#   }

#   result <- data.frame(startDateTime = missingStart, endDateTime = missingEnd)

#   if (verbose) {
#     cat("Missing Gap\n")
#     print(result)
#   }

#   return(result)

# }