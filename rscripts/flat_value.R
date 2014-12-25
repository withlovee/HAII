source('config.R')
source('helper.R')

FlatValue.FindFlatValue <- function(data, dataType,
                                    dataInterval = Config.defaultDataInterval,
                                    flatThreshold = Config.FlatValue.defaultThreshold) {

  Helper.CheckDataType(dataType)

  if (is.null(data)) {
    return(NULL)
  }
  if (!is(data, "data.frame")) {
    return(NULL)
  }

  # sort by datetime
  data <- data[order(data$datetime),]

  value <- NA
  if (dataType == "WATER") {
    value <- data$water1
  } else if (dataType == "RAIN") {
    value <- data$rain1h
  }

  len <- length(value)

  startDateTime <- c()
  endDateTime <- c()
  v <- c()
  n <- c()

  i <- 1
  
  # find consecutive data [i,j) which have same value
  while (i <= len) {

    if (is.na(value[i])) {
      # null cannot be in sequence
      i <- i + 1
    } else {
      j <- i + 1

      # sliding j until out of sequence
      while (j <= len) {
        
        # null cannot be in sequence
        if (is.na(value[j])) {
          break;
        }

        diffTime <- as.numeric(data$datetime[j] - data$datetime[j-1], units="secs")

        if (diffTime != dataInterval | value[j] != value[j-1]) {
          # j is not consecutive (either temporal or value)
          break;
        } else {
          j <- j + 1
        }

      }
      
      # total time of sequence
      flatDiffTime <- as.numeric((data$datetime[j - 1]) - data$datetime[i], units="secs")
      if(flatDiffTime  >= flatThreshold) {
        startDateTime  <- c(startDateTime, data$datetime[i])
        endDateTime <- c(endDateTime, data$datetime[j-1])
        v <- c(v, value[i])
        n <- c(n, flatDiffTime)
      }

      # find new consecutive sequence
      i <- j

    }

  }

  # change class to POSIXct
  if (length(startDateTime) > 0 & length(endDateTime) > 0) {
    class(startDateTime) <- "POSIXct"
    class(endDateTime) <- "POSIXct"
  }

  # return data frame
  return(data.frame(startDateTime=startDateTime, endDateTime=endDateTime, v=v, n=n, hr=n/3600))
}