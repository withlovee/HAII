library('fpc')
source('helper.R')
source('config.R')
source('cluster.R')

Outliers.Find <- function(data, dataType,
													dataInterval    = Config.defaultDataInterval,
													valueThreshold	= Config.Outliers.valueThreshold,
													noiseThreshold  = Config.Outliers.noiseThreshold) {

	data <- Helper.FilterAndSort(data)

	if (nrow(data) <= 0) {
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

	print(clusterResult)

	for(noiseId in noiseDataIds) {
		noiseData <- data[noiseId,]

		leftTimeDiff <- Helper.TimeDiffSecs(noiseData$datetime,
																				data[noiseId - 1,]$datetime)
		rightTimeDiff <- Helper.TimeDiffSecs(data[noiseId + 1,]$datetime,
																				 noiseData$datetime)

		haveLeftData = (noiseId - 1 >= minDataId) &
									 (leftTimeDiff <= dataInterval)
		haveRightData = (noiseId + 1 <= maxDataId) &
										(rightTimeDiff <= dataInterval)

		if(haveLeftData & haveRightData) {
			# real noise, not jump point caused by missing gap
			outliersId <- c(outliersId, noiseId)
		}
	}

	# convert to problem format
	outliersData <- data[outliersId,]

	if(nrow(outliersData) <= 0) {
		return(NULL)
	}

	outliersProblem = data.frame(stationCode = outliersData$code,
															 startDateTime = outliersData$datetime,
															 endDateTime = outliersData$datetime)

	return(outliersProblem)

}