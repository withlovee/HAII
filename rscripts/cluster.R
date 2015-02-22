Cluster.TimeSeriesCluster <- function(t, y, dt, dy, noiseThreshold = 1) {

	if (length(y) != length(t)) {
		stop("lenght of y and t is not equal.")
	}

	currentCluster <- 0
	cluster <- c()
	clusterCount <- c()
	latestY <- NA
	latestT <- NA

	# sort by time
	y <- y[order(t)]
	t <- t[order(t)]

	for (i in 1:length(y)) {
		yi <- y[i]
		ti <- t[i]

		newCluster <- FALSE
		if (is.na(latestY) & is.na(latestT)) {
			newCluster <- TRUE
		} else if (abs(latestT - ti) > dt | abs(latestY - yi) > dy) {
			newCluster <- TRUE
		}

		if (newCluster) {
			currentCluster <- currentCluster + 1
			cluster <- c(cluster, currentCluster)
			clusterCount <- c(clusterCount, 1)
		} else {
			cluster <- c(cluster, currentCluster)
			clusterCount[currentCluster] <- clusterCount[currentCluster] + 1
		}

		latestY <- yi
		latestT <- ti
	}

	return(list(
					cluster= cluster,
					count  = clusterCount,
					noise  = clusterCount <= noiseThreshold
				))

}