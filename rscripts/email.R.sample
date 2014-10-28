library("httr")
library("jsonlite")

## global email config
BASE_URL <- "http://localhost:8888"
EMAIL_API_URL <- paste0(BASE_URL, "/api/email/send_alert/instantly")
USE_EMAIL_NOTIFICATION <- TRUE
EMAIL_KEY <- "HAIIEMAILKEY"

convertAbbrToFullProblemName <- function(abbr) {
  
  fullName <- NA
  
  if(abbr == "BD") {
    fullName <- "Out of Range"
  } else if(abbr == "FV") {
    fullName <- "Flat Value"
  } else if(abbr == "MV") {
    fullName <- "Missing Value"
  } else if(abbr == "OL") {
    fullName <- "Outliers"
  } else if(abbr == "IH") {
    fullName <- "Inhomogenity"
  } else if(abbr == "MP") {
    fullName <- "Missing Pattern"
  }
  
  return(fullName)
}

sendProblemMailNotification <- function(dataType, problemType, dateTime, problemStation,
                                        sendEmail=TRUE, returnJson=FALSE,key=EMAIL_KEY) {
  
  rain <- list()
  water <- list()
  
  problemName <- convertAbbrToFullProblemName(problemType)
  
  if(dataType == "RAIN") {
    rain <- list(list(name=unbox(problemName),
                 stations=problemStation))
  } else if (dataType == "WATER") {
    water <- list(list(name=unbox(problemName),
                     stations=problemStation))
  }
  
  body <- list(key=unbox(key),
               num=unbox(length(problemStation)),
               date=unbox(strftime(dateTime, "%Y-%m-%d %H:%M:%S")),
               rain=rain,
               water=water)
  
  json <- toJSON(body)
  json <- as.character(json)

  if(USE_EMAIL_NOTIFICATION & sendEmail){
    POST(EMAIL_API_URL, body = json, encode = "json")   
  }
  
  if(returnJson) {
    return(json)    
  } 
  
}