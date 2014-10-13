library("httr")

## global email config

emailApiUrl <- "http://localhost:8888"

## end of global email config


sendProblemMailNotification <- function(dataType, problemType, time, problemStation) {
  
  
  body <- ""
  body <- paste0(body, "{")
  
  body <- paste0(body, '"data_type":', '"',dataType,'",')
  body <- paste0(body, '"problem_type":', '"',problemType,'",')
  body <- paste0(body, '"time":', '"',strftime(time, "%Y-%m-%d %H:%M:%S"),'",')
  body <- paste0(body, '"stations":', '[')
  
  stationsStr <- mapply(function(x) paste0('"',x,'"'),problemStation)
  stationsStr <- paste(stationsStr, collapse=",")
  body <- paste0(body, stationsStr)
  
  body <- paste0(body, ']')
  
  body <- paste0(body, "}")
  
  POST(emailApiUrl, body = list(x=body), encode = "json") 
  
}