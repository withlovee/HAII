library('fpc')
source('config.R')
source('helper.R')
source('cluster.R')

MissingPattern.ConvertToHourlyData <- function(data, dataType, startDate, endDate,
                                               minimumDay = Config.MissingPattern.minimumDay,
                                               mergeGap = Config.MissingPattern.mergeGap) {

	data <- Helper.FilterAndSort(data)

	# round time to house
	data$roundedDatetime <- as.POSIXct(strftime(data$datetime, "%Y-%m-%d %H:00:00"))

	# aggreate hourly data together
	hourHaveData <- aggregate(value ~ roundedDatetime, data, function(values) {
		return(any(values != 999999 & !is.na(values) & values != -9999) & length(values))
	})
  
	# hourHaveData$haveData = hourHaveData$value
  names(hourHaveData)[names(hourHaveData)=="value"] <- "haveData"
  
  numDate <- as.numeric(endDate - startDate) + 1
  
  missingDataFrame <- NULL

  hourHaveData$date <- as.POSIXct(strftime(hourHaveData$roundedDatetime, "%Y-%m-%d 00:00:00"))
  hourHaveData <- hourHaveData[hourHaveData$date >= startDate & hourHaveData$date <= endDate,]
  hourHaveData$day <- as.numeric(hourHaveData$date - startDate, unit="days")
  hourHaveData$hournum <- as.numeric(strftime(hourHaveData$roundedDatetime,"%H"))

  day = 0:(numDate-1)
  hournum = 0:23

  blankData <- merge(data.frame(day=day), data.frame(hournum=hournum))
  blankData$date = startDate + blankData$day * 3600 * 24
  blankData$roundedDatetime = blankData$date + blankData$hournum * 3600

  allHourHaveData <- merge(x = blankData,y = hourHaveData, all.x = TRUE)

  allHourHaveData$haveData[is.na(allHourHaveData$haveData)] <- FALSE

  return(allHourHaveData)

  str(joinResult)
  return(joinResult)
  
  # fill the blank
  for(i in 0:(numDate-1)){
    for(h in 0:23) {
      if(nrow(hourHaveData[hourHaveData$day == i & hourHaveData$hournum == h,]) <= 0) {
        row = data.frame(roundedDatetime = startDate + (i*3600*24) + h*3600,
                         haveData = FALSE,
                         date     = startDate + (i*3600*24),
                         day      = i,
                         hournum  = h) 
        hourHaveData <- rbind(hourHaveData, row)
      }
    }
  }

	return(hourHaveData[order(hourHaveData$day, hourHaveData$hournum),])
  
}

MissingPattern.ConvertToFrequencyData <- function(hourlyData) {
  freq <- aggregate(haveData ~ hournum, hourlyData, sum)
  names(freq)[names(freq)=="haveData"] <- "freq"
  return(freq)
}

MissingPattern.FindMissingPattern <- function(hourlyData, dataType, startDay, endDay,
                                              minimumDay = Config.MissingPattern.minimumDay,
                                              mergeGap   = Config.MissingPattern.mergeGap) {

  numDate <- as.numeric(endDay - startDay) + 1

  dataInRange <- hourlyData[hourlyData$day >= startDay & hourlyData$day <= endDay,]

  if (numDate < minimumDay) {
    return(NULL)
  }
  if (all(dataInRange$haveData) | all(!dataInRange$haveData)) {
    return(NULL)
  }

  halfDay <-  floor((startDay + endDay)/2)

  leftMissing <- MissingPattern.FindMissingPattern(hourlyData, dataType, startDay, halfDay)
  rightMissing <- MissingPattern.FindMissingPattern(hourlyData, dataType, halfDay + 1, endDay)

  haveLeftPattern <- is.null(leftMissing)
  haveRightPattern <- is.null(rightMissing)

  # analyze
  freq <- MissingPattern.ConvertToFrequencyData(dataInRange)
  maxFreqPossible <- numDate
  eps <- 0.5 * maxFreqPossible
  
  clusterResult <- Cluster.TimeSeriesCluster(t=freq$hournum, y=freq$freq, dt=1, dy=eps)

  haveOverallPattern = length(unique(clusterResult$cluster)) > 1

  missingPattern <- NULL

  # 8 Cases
  if(haveOverallPattern) {
    if (haveLeftPattern == haveRightPattern) {
      missingPattern <- data.frame(startDay=startDay, endDay=endDay)
    } else if(haveLeftPattern & !haveRightPattern){
      missingPattern <- data.frame(startDay=startDay, endDay=halfDay)
    } else {
      missingPattern <- data.frame(startDay=halfDay+1, endDay=endDay)
    }
  } else {
    missingPattern <- rbind(leftMissing, rightMissing)
  }

  # merge gaps
  if (is.null(missingPattern)) {
    return(NULL)
  }

  missingPattern <- missingPattern[order(missingPattern$startDay),]
  mergedMissingPattern <- NULL

  for (mp in 1:nrow(missingPattern)) {
    if(is.null(mergedMissingPattern)) {
      mergedMissingPattern <- missingPattern[mp,]
    } else {
      latest <- nrow(mergedMissingPattern)
      if (missingPattern[mp,]$startDay - mergedMissingPattern[latest,]$endDay < mergeGap) {
        mergedMissingPattern[latest,]$endDay <- missingPattern[mp,]$endDay
      } else {
        mergedMissingPattern <- rbind(mergedMissingPattern, missingPattern[mp,])
      }
    }
  }

  return(mergedMissingPattern)
}

MissingPattern.Find <- function(data, dataType, startDate, endDate) {
  if (is.null(data)) {
    return(NULL)
  }
  if (nrow(data) <= 0) {
    return(NULL)
  }

  hourlyData <- MissingPattern.ConvertToHourlyData(data, dataType, startDate, endDate)
  startDay <- min(hourlyData$day)
  endDay <- max(hourlyData$day)
  missingPattern <- MissingPattern.FindMissingPattern(hourlyData, dataType, startDay, endDay)

  return(missingPattern)
}