library('jsonlite')
source('db_connection.R')
source('datalog2.R')
source('helper.R')

Batch.Get <- function(id) {

  fields <- c('id', 'data_type', 'problem_type', 'stations', 'all_station', 'start_datetime', 'end_datetime', 'add_datetime', 'finish_datetime')
  fieldsString <- paste0(fields, collapse=", ")

  queryString <- paste0("
    SELECT ", fieldsString, "
    FROM batches
    WHERE id = ", id, "
  ")

  batch <- DBConnection.Query(queryString)
  batch <- as.list(batch[1,])

  if (!is.na(batch$problem_type)) {
    batch$problem_type <- fromJSON(batch$problem_type)
  }
  if (!is.na(batch$stations)) {
    batch$stations <- fromJSON(batch$stations)
  }
  if (batch$all_station) {
    batch$stations <- DataLog.GetStationCodeList(batch$data_type)
  }

  return(batch)
}

Batch.SetFinishTime <- function(batch, t = Sys.time()) {
  queryString <- paste0("
    UPDATE batches
    SET finish_datetime = TIMESTAMP '", Helper.POSIXctToString(t) ,"'
    WHERE id = ", batch$id, "
  ")

  DBConnection.SendQuery(queryString)
}

Batch.SetStatus <- function(batch, status) {
  queryString <- paste0("
    UPDATE batches
    SET status = '", status ,"'
    WHERE id = ", batch$id, "
  ")

  DBConnection.SendQuery(queryString)
}

Batch.SetStatusAsRunning <- function(batch) {
  Batch.SetStatus(batch, "running")
}

Batch.SetStatusAsSuccess <- function(batch) {
  Batch.SetStatus(batch, "success")
}