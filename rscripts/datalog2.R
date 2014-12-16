source('db_connection.R')


DataLog.GetData <- function (stationCode, dataType, startDateTime, endDateTime, verbose = FALSE) {
  # Get raw data from databases in interval [startTime, endTime].
  #
  # Args:
  #   stationCode: (String) code of telemetering station (ex. CHI001)
  #   dataType: (String) "WATER" or "RAIN"
  #   startDateTime: (POSIXct) start time of data
  #   endDateTime: (String) end time of data
  #   verbose: (Boolean) verbose mode
  #
  # Returns:
  #   Data frame of raw data.
  #   if dataType == "WATER", data frame will contains:
  #     code, date, time, water1, left_bank, right_bank, ground_level, datetime, value
  #   if dataType == "RAIN", data frame will contains:
  #     code, date, time, rain1h, datetime, value

  # Error handling
  if (!("POSIXct" %in% class(startDateTime))) {
    stop("startDateTime (", startDateTime, ") must be POSIXct class.")
  }
  if (!("POSIXct" %in% class(endDateTime))) {
    stop("endDateTime (", endDateTime, ") must be POSIXct class.")
  }
  if (!(dataType %in% c("WATER", "RAIN"))) {
    stop("Invalid dataType ", dataType, ". dataType must be WATER or RAIN.")
  }

  # verbose
  if (verbose) {
    cat("Getting", dataType, "data of", stationCode, "station from", strftime(startDateTime), "to", strftime(endDateTime), "\n")
  }

  # because datalog store data and time seperately, so we need to split and convert to string
  startDateString <- strftime(startDateTime, "%Y-%m-%d")
  endDateString   <- strftime(endDateTime, "%Y-%m-%d")
  
  startTimeString <- strftime(startDateTime, "%H:%M:%S")
  endTimeString   <- strftime(endDateTime, "%H:%M:%S")

  # columns to be pulled from database
  fields <- c("data_log.code", "data_log.date", "data_log.time")

  if (dataType == "WATER") {
    fields <- c(fields, "data_log.water1", "tele_wl_detail.left_bank",
                "tele_wl_detail.right_bank", "tele_wl_detail.ground_level")
  } else if(dataType == "RAIN") {
    fields <- c(fields, "data_log.rain1h")
  }

  # concat fields together, seperate with ","
  fieldsString <- paste0(fields, collapse=", ")

  # generate query string
  queryString <- paste0("SELECT ", fieldsString,"
                       FROM data_log
                       INNER JOIN tele_wl_detail ON tele_wl_detail.code = data_log.code
                       WHERE 
                           (data_log.date > DATE '", startDateString ,"'
                           OR
                           data_log.date = DATE '", startDateString ,"' AND data_log.time >= TIME '", startTimeString ,"')
                         AND
                           (data_log.date < DATE '", endDateString ,"'
                           OR
                           data_log.date = DATE '", endDateString ,"' AND data_log.time <= TIME '", endTimeString ,"')
                         AND data_log.code = '", stationCode ,"'
                       ")

  # query from database
  dbConnection <- DBConnection.OpenDBConnection(verbose=verbose)
  data <- dbGetQuery(dbConnection, queryString)
  DBConnection.CloseDBConnection(dbConnection, verbose=verbose)

  # add datetime fields
  data$datetime = as.POSIXct(paste(data$date, data$time))

  # add value fields
  if (dataType == "WATER") {
    data$value = data$water1
  } else if (dataType == "RAIN") {
    data$value = data$rain1h
  }

  # order by datetime
  data <- data[order(data$datetime),]

  return(data)

}

DataLog.GetStationCodeList <- function (verbose = FALSE) {
  # Get list of code of all telemetering station.
  #
  # Returns:
  #   Vector of string (all telemetering station's code).

  #verbose
  if (verbose) {
    cat("Getting station list\n")
  }

  queryString <- "SELECT tele_wl_detail.code FROM tele_wl_detail"
  dbConnection <- DBConnection.OpenDBConnection(verbose=verbose)
  data <- dbGetQuery(dbConnection, queryString)
  DBConnection.CloseDBConnection(con, verbose=verbose)
  return(data$code)

}