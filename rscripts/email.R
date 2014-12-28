library("httr")
library("jsonlite")

source('config.R')
source('helper.R')


Email.sendMailNotification <- function(dataType, problemType, dateTime, problemStation,
                                        sendEmail=TRUE, returnJson=FALSE,key=Config.Email.APIKey) {
  cat("Email: Generating Email\n")
  str(problemStation)

  if (length(problemStation) <= 0) {
    cat("Email: No new station, abort sending.\n")
    return(NA)
  }
  
  rain <- list()
  water <- list()
  
  problemName <- Helper.FullProblemNameFromAbbr(problemType)
  
  if(dataType == "RAIN") {
    rain <- list(list(name = unbox(problemName),
                 stations = problemStation))
  } else if (dataType == "WATER") {
    water <- list(list(name = unbox(problemName),
                     stations = problemStation))
  }
  
  body <- list(key = unbox(key),
               num = unbox(length(problemStation)),
               date = unbox(Helper.POSIXctToString(dateTime)),
               rain = rain,
               water = water)
  
  # json <- as.character(toJSON(body))

  emailResult <- NA
  if(Config.Email.useEmailNotification & sendEmail){
    cat("Sending email...\n")
    emailResult <- POST(Config.Email.fullURL, body = body, encode = "json")
  }
  
  if(returnJson) {
    return(json)    
  } 

  cat("Email: ", as.character(toJSON(body)) , "\n")

  return(emailResult)
  
}