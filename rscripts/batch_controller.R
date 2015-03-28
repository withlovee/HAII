source('batch.R')
source('all_controller.R')
library('jsonlite')

id <- as.numeric(args[1])
batch <- Batch.Get(id)
# Batch.SetStatusAsRunning(batch)

# Call proper method

str(batch)

allResult <- NULL

for (problemType in batch$problem_type) {
  cat('==> batch:', problemType, "\n")
  if (problemType == 'OR') {
    result <- OutOfRange.Controller.Batch(
                dataType      = batch$data_type,
                startDateTime = batch$start_datetime,
                endDateTime   = batch$end_datetime,
                addToDB       = FALSE,
                stations      = batch$stations,
                allStation    = batch$all_station
              )
  } else if (problemType == 'FV') {
    result <- FlatValue.Controller.Batch(
                dataType      = batch$data_type,
                startDateTime = batch$start_datetime,
                endDateTime   = batch$end_datetime,
                addToDB       = FALSE,
                stations      = batch$stations,
                allStation    = batch$all_station
              )
  } else if (problemType == 'MG') {
    result <- MissingGap.Controller.Batch(
                dataType      = batch$data_type,
                startDateTime = batch$start_datetime,
                endDateTime   = batch$end_datetime,
                addToDB       = FALSE,
                stations      = batch$stations,
                allStation    = batch$all_station
              )
  } else if (problemType == 'OL') {
    result <- Outliers.Controller.Batch(
                dataType      = batch$data_type,
                startDateTime = batch$start_datetime,
                endDateTime   = batch$end_datetime,
                addToDB       = FALSE,
                stations      = batch$stations,
                allStation    = batch$all_station
              )
  } else if (problemType == 'HM') {
    result <- Inhomogeneity.Controller.Batch(
                dataType      = batch$data_type,
                startDateTime = batch$start_datetime,
                endDateTime   = batch$end_datetime,
                addToDB       = FALSE,
                stations      = batch$stations,
                allStation    = batch$all_station
              )
  } else if (problemType == 'MP') {
    result <- MissingPattern.Controller.Batch(
                dataType      = batch$data_type,
                startDateTime = batch$start_datetime,
                endDateTime   = batch$end_datetime,
                addToDB       = FALSE,
                stations      = batch$stations,
                allStation    = batch$all_station,
                returnProblemOnly = TRUE
              )
  }

  print(result)

  if (is.data.frame(result)) {
    result$problemType <- problemType

    if (is.data.frame(allResult)) {
      allResult <- rbind(allResult, result)
    } else {
      allResult <- result
    }
  }

}

# Batch.SetFinishTime(batch)
Batch.SetStatusAsSuccess(batch)

# Save CSV
csvPath <- paste0(Config.Batch.CsvPath, batch$id, ".csv")

if (is.data.frame(allResult)) {
  write.csv(allResult, csvPath)
} else {
  write.csv("None", csvPath)
}

print(allResult)