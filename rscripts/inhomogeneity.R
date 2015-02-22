library('changepoint')
source('config.R')
source('helper.R')

Inhomogeneity.Find <- function (data, dataType,
																maxChangePoint = Config.Inhomogeneity.maximumChangePoint,
																inhomogeneityThreshold = Config.Inhomogeneity.threshold) {

  print(maxChangePoint)
	data <- Helper.FilterAndSort(data)

  if (nrow(data) <= 0) {
    return(NULL)
  }
 	
 	cpt <- multiple.meanvar.norm(data$value,
															 mul.method = "BinSeg",
															 penalty    = "Manual",
															 pen.value  = "4*log(n)",
															 Q          = maxChangePoint)

  changePoints <- cpt@cpts

  filteredChangePoints <- c()

  # check whether value is different more that {inhomogeneityThreshold}
  
  print(changePoints)

  for (changePoint in changePoints) {
  	if(changePoint < nrow(data)) {

      valueShifted <- abs(data[changePoint + 1,]$value - data[changePoint,]$value)
  		if(valueShifted >= inhomogeneityThreshold) {
  			filteredChangePoints <- c(filteredChangePoints, changePoint)
  		}
  	}
  }

  dataWithChangePoint <- data[filteredChangePoints, ]

  if(nrow(dataWithChangePoint) > 0) {
    # convert into problem format
    changePointProblem <- data.frame(stationCode = dataWithChangePoint$code,
                                     startDateTime = dataWithChangePoint$datetime,
                                     endDateTime = dataWithChangePoint$datetime);

    return(changePointProblem)
  }

  return(NULL)
}