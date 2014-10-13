library("httr")

## global email config

emailApiUrl <- "http://localhost:8888"

## end of global email config


sendProblemMailNotification <- function(dataType, problemType, problems) {
  
  stations <- levels(problem$station_code)
  
  body <- ""
  body <- paste0(body, "{")
  
  body <- paste0(body, '"data_type":', '"',dataType,'",')
  body <- paste0(body, '"problem_type":', '"',problemType,'",')
  
  body <- paste0(body, '"stations":', '[')
  
  stationsStr <- mapply(function(x) paste0('"',x,'"'),stations)
  stationsStr <- paste(stationsStr, collapse=",")
  body <- paste0(body, stationsStr)
  
  body <- paste0(body, ']')
  
  body <- paste0(body, "}")
  
  POST(emailApiUrl, body = body, encode = "json") 
  
}