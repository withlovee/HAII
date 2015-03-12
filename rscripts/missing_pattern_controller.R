source('datalog2.R')
source('config.R')
source('problems.R')
source('missing_pattern.R')

MissingPattern.Controller.Find <- function(
  stationCode, dataType, startDateTime, endDateTime) {

  cat("Missing Pattern: ", stationCode , "\n")
  missingPatternResult <- NA

  data <- DataLog.GetData(stationCode, dataType, startDateTime, endDateTime)

  startDate <- floor_date(startDateTime, "day")
  endDate <- floor_date(endDateTime, "day")

  missingPatternResult <- MissingPattern.Find(data, dataType, startDate, endDate)

  return(missingPatternResult)
}

MissingPattern.Controller.FindAll <- function(dataType, startDateTime, endDateTime) {
  resultAllStation <- data.frame(stationCode = c(),
                                startDateTime = c(),
                                endDateTime = c())
  hourlyDataAllStation <- list()

  stations <- DataLog.GetStationCodeList(dataType)

  for (station in stations) {

    result <- MissingPattern.Controller.Find(station, dataType, startDateTime, endDateTime)
    if(is.data.frame(result$missingPattern)) {
      if(nrow(result$missingPattern) > 0) {
        result$missingPattern$stationCode <- station
        print(result$missingPattern)
        resultAllStation <-rbind(resultAllStation, result$missingPattern)
        hourlyDataAllStation[[station]] <- result$hourlyData
      }
    }
  }

  if (nrow(resultAllStation) == 0) {
    return(NULL)
  }

  return(list(problems=resultAllStation, hourlyData = hourlyDataAllStation))

} 

MissingPattern.Controller.Batch <- function (dataType, startDateTime, endDateTime, addToDB = TRUE, mergeProblem = TRUE) {

  problemType <- "MP"
  allMissingPattern <- MissingPattern.Controller.FindAll(dataType, startDateTime, endDateTime)

  if (addToDB) {
    # update problem
    print("Adding Problems")
    # str(outOfRange)
    Problems.AddProblems(allMissingPattern$problems, dataType, problemType, mergeProblem = mergeProblem)
  }

  return(allMissingPattern)
}

MissingPattern.Controller.MonthlyOperation <- function(dataType, currentDateTime = Sys.time(), addToDB = TRUE) {

  problemType <- "MP"

  lastMonth <- Helper.LastMonth(currentDateTime, Config.defaultWaterDataInterval)
  print(lastMonth)

  allMissingPattern <- MissingPattern.Controller.Batch(dataType, lastMonth$start, lastMonth$end, addToDB, mergeProblem = FALSE)

  # draw graph

  for (stationCode in names(allMissingPattern$hourlyData)) {
    
    if (nrow(allMissingPattern$hourlyData[[stationCode]]) == 0) {
      cat("Skip image for", stationCode, "\n")
      next
    }
    cat("Creating image for", stationCode, "\n")
    
    

    MissingPattern.Controller.plotHourlyMap(stationCode,
                  dataType,
	  							allMissingPattern$hourlyData[[stationCode]],
	  							lastMonth$start,
	  							Config.MissingPattern.monthlyImagePath)
	}

  return(allMissingPattern)

}

MissingPattern.Controller.plotHourlyMap <- function(stationCode, dataType, hourlyData, startDate, path) {
  
  colorPalette <- c("#5555FF", "#AAAAFF", "#555555", "#AAAAAA")
  
  if (nrow(hourlyData[!hourlyData$haveData & hourlyData$pattern, ]) > 0)
    hourlyData[!hourlyData$haveData & hourlyData$pattern, ]$caption <- "Have Pattern - Data Missing"
  
  if (nrow(hourlyData[hourlyData$haveData & hourlyData$pattern, ]) > 0)
    hourlyData[hourlyData$haveData & hourlyData$pattern, ]$caption <- "Have Pattern - Not Missing"
  
  if (nrow(hourlyData[!hourlyData$haveData & !hourlyData$pattern, ]) > 0)
    hourlyData[!hourlyData$haveData & !hourlyData$pattern, ]$caption <- "No Pattern - Data Missing"
  
  if (nrow(hourlyData[hourlyData$haveData & !hourlyData$pattern, ]) > 0)
    hourlyData[hourlyData$haveData & !hourlyData$pattern, ]$caption <- "No Pattern - Not Missing"
  
	imagePath <- Config.MissingPattern.monthlyImagePath
	hourlyMap <- ggplot(hourlyData, aes(x=date, y=hournum)) + geom_tile(aes(fill=caption)) + scale_fill_manual(values=colorPalette)
	hourlyMap
	ggsave(file=paste0(path, startDate,'_',stationCode, '_', dataType ,'.png'), width=10, height=6, dpi=72)
}
