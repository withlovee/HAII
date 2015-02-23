library('fpc')
source('helper.R')
source('config.R')
source('cluster.R')

Outliers.Find <- function(data, dataType,
													dataInterval    = Config.defaultDataInterval,
													valueThreshold	= Config.Outliers.valueThreshold,
													noiseThreshold  = Config.Outliers.noiseThreshold) {
	if(!is.data.frame(data)) {
    return(NULL)
  }
	if (nrow(data) <= 1) {
		return(NULL)
	}

	data <- Helper.FilterAndSort(data)

	if (nrow(data) <= 1) {
		return(NULL)
	}

	x <- as.numeric(data$datetime)
	y <- data$value

	minDataId <- 1
	maxDataId <- nrow(data)

	outliersId <- c()

	#### New Algorithm
	clusterResult <- Cluster.TimeSeriesCluster(
										t  = x,
										y  = y,
										dt = dataInterval,
										dy = valueThreshold,
										noiseThreshold = noiseThreshold)

	clusterIds <- clusterResult$cluster
	noiseDataIds <- which(clusterResult$noise[clusterIds])

	# print(clusterResult)

	for(noiseId in noiseDataIds) {
		# cat("noiseId", noiseId, "\n");

		isOutliers <- FALSE
		noiseData <- data[noiseId,]

		if (noiseId == minDataId) {
			rightTimeDiff <- Helper.TimeDiffSecs(data[noiseId + 1,]$datetime,
																				   noiseData$datetime)
			haveRightData = rightTimeDiff <= dataInterval
			if (haveRightData) {
				isOutliers <- TRUE
			}
		} else if (noiseId == maxDataId) {
			leftTimeDiff <- Helper.TimeDiffSecs(noiseData$datetime,
																				  data[noiseId - 1,]$datetime)
			haveLeftData = leftTimeDiff <= dataInterval
		  if (haveLeftData) {
		  	isOutliers <- TRUE
		  }
		} else {
			leftTimeDiff <- Helper.TimeDiffSecs(noiseData$datetime,
																					data[noiseId - 1,]$datetime)
			rightTimeDiff <- Helper.TimeDiffSecs(data[noiseId + 1,]$datetime,
																					 noiseData$datetime)

			haveLeftData = leftTimeDiff <= dataInterval
			haveRightData = rightTimeDiff <= dataInterval

			if (haveLeftData & haveRightData) {
				isOutliers <- TRUE
			}

		}

		if(isOutliers) {
			# real noise, not jump point caused by missing gap
			outliersId <- c(outliersId, noiseId)
		}
	}

	# convert to problem format
	outliersData <- data[outliersId,]

	if(nrow(outliersData) <= 0) {
		return(NULL)
	}

	outliersProblem = data.frame(startDateTime = outliersData$datetime,
															 endDateTime = outliersData$datetime)

	return(outliersProblem)

}