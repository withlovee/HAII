source('config.R')
source('helper.R')

FlatValue.findFlatValue <- function(data, dataType,
                                    dataInterval = Config.defaultDataInterval,
                                    flatInterval = Config.FlatValue.defaultInterval) {

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

  i <- 1
  
  print(data)
  
  while (i <= len) {

    if (is.na(value[i])) {
      i <- i + 1
    } else {
      j <- i + 1
      while (j <= len) {
        
        if (is.na(value[j])) {
          break;
        }

        diffTime <- as.numeric(data$datetime[j] - data$datetime[j-1], units="secs")

        if (diffTime != dataInterval | value[j] != value[j-1]) {
          break;
        } else {
          j <- j + 1
        }

      }
      
      diffTime <- as.numeric((data$datetime[j - 1]) - data$datetime[i], units="secs")

      if(diffTime  >= flatInterval) {
        # add to list
        startDateTime  <- c(startDateTime, data$datetime[i])
        endDateTime <- c(endDateTime, data$datetime[j-1])
      }

      i <- j

    }

  }

  if (length(startDateTime) > 0 & length(endDateTime) > 0) {
    class(startDateTime) <- "POSIXct"
    class(endDateTime) <- "POSIXct"
  }

  # return data frame
  return(data.frame(startDateTime=startDateTime, endDateTime=endDateTime))

}