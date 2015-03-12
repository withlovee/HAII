library("httr")
library("jsonlite")
library("lubridate")

source('config.R')
source('helper.R')
source('problems.R')


Email.sendMailNotification <- function(dataType, problemType, dateTime, problemStation, mailType="instantly",
                                        sendEmail=TRUE, returnJson=FALSE, key=Config.Email.APIKey) {
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

  url <- ""

  if(mailType == "instantly") {
    url <- Config.Email.Instantly.fullURL  
  } else if(mailType == "daily") {
    url <- Config.Email.Daily.fullURL  
  } else if(mailType == "monthly") {
    url <- Config.Email.Monthly.fullURL
  }

  

  emailResult <- NA
  if(Config.Email.useEmailNotification & sendEmail){
    cat("Sending email...\n")
    emailResult <- POST(url, body = body, encode = "json")
  }
  
  if(returnJson) {
    return(json)    
  } 

  cat("Email: ", as.character(toJSON(body)) , "\n")

  return(emailResult)
  
}

Email.FindOverlapProblemJSON <- function(dataType, problemType, startDateTime, endDateTime) {
  res <- list(name = unbox(problemType),
          stations = Problems.GetProblemStationCodeListOverlapInterval(
                      dataType, problemType ,datetime$start, datetime$end)
         )

  return(res)
}

Email.SendDailyReport <- function(currentTime  = Sys.time(),
                                      sendEmail  = TRUE,
                                      returnJson = FALSE,
                                      key        = Config.Email.APIKey) {
  
  # start, end time
  datetime <- Helper.LastOperationDay(currentTime)
  print(datetime)

  water_or <- Email.FindOverlapProblemJSON("WATER", "OR" ,datetime$start, datetime$end)
  water_mg <- Email.FindOverlapProblemJSON("WATER", "MG" ,datetime$start, datetime$end)
  water_fv <- Email.FindOverlapProblemJSON("WATER", "FV" ,datetime$start, datetime$end)
  water_ol <- Email.FindOverlapProblemJSON("WATER", "OL" ,datetime$start, datetime$end)
  water_hm <- Email.FindOverlapProblemJSON("WATER", "HM" ,datetime$start, datetime$end)
  water_mp <- Email.FindOverlapProblemJSON("WATER", "MP" ,datetime$start, datetime$end)

  rain_or <- Email.FindOverlapProblemJSON("RAIN", "OR" ,datetime$start, datetime$end)
  rain_mg <- Email.FindOverlapProblemJSON("RAIN", "MG" ,datetime$start, datetime$end)
  rain_fv <- Email.FindOverlapProblemJSON("RAIN", "FV" ,datetime$start, datetime$end)
  rain_mp <- Email.FindOverlapProblemJSON("RAIN", "MP" ,datetime$start, datetime$end)

  water <- list(water_or, water_mg, water_fv)
  rain <- list(rain_or, rain_mg, rain_fv)
  
  num <- sum(sapply(water, function(x){length(x$stations)})) + sum(sapply(rain, function(x){length(x$stations)}))

  body <- list(key = unbox(key),
               num = unbox(num),
              date = unbox(Helper.POSIXctToString(currentTime)),
         startdate = unbox(Helper.POSIXctToString(datetime$start)),
           enddate = unbox(Helper.POSIXctToString(datetime$end)),
              rain = rain,
             water = water)

  emailResult <- NA
  url <- Config.Email.Daily.fullURL

  return(Email.SendEmail(body, url, sendEmail, returnJson))

}


Email.SendMonthlyReport <- function(currentTime  = Sys.time(),
                                      sendEmail  = TRUE,
                                      returnJson = FALSE,
                                      key        = Config.Email.APIKey) {
  
  # start, end time
  datetime <- Helper.LastMonth(currentTime)

  water_or <- Email.FindOverlapProblemJSON("WATER", "OR" ,datetime$start, datetime$end)
  water_mg <- Email.FindOverlapProblemJSON("WATER", "MG" ,datetime$start, datetime$end)
  water_fv <- Email.FindOverlapProblemJSON("WATER", "FV" ,datetime$start, datetime$end)
  water_ol <- Email.FindOverlapProblemJSON("WATER", "OL" ,datetime$start, datetime$end)
  water_hm <- Email.FindOverlapProblemJSON("WATER", "HM" ,datetime$start, datetime$end)
  water_mp <- Email.FindOverlapProblemJSON("WATER", "MP" ,datetime$start, datetime$end)

  rain_or <- Email.FindOverlapProblemJSON("RAIN", "OR" ,datetime$start, datetime$end)
  rain_mg <- Email.FindOverlapProblemJSON("RAIN", "MG" ,datetime$start, datetime$end)
  rain_fv <- Email.FindOverlapProblemJSON("RAIN", "FV" ,datetime$start, datetime$end)
  rain_mp <- Email.FindOverlapProblemJSON("RAIN", "MP" ,datetime$start, datetime$end)

  water <- list(water_or, water_mg, water_fv, water_ol, water_hm, water_mp)
  rain <- list(rain_or, rain_mg, rain_fv, rain_mp)
  
  num <- sum(sapply(water, function(x){length(x$stations)})) + sum(sapply(rain, function(x){length(x$stations)}))

  body <- list(key = unbox(key),
               num = unbox(num),
              date = unbox(Helper.POSIXctToString(currentTime)),
         startdate = unbox(Helper.POSIXctToString(datetime$start)),
           enddate = unbox(Helper.POSIXctToString(datetime$end)),
              rain = rain,
             water = water)

  emailResult <- NA
  url <- Config.Email.Monthly.fullURL

  return(Email.SendEmail(body, url, sendEmail, returnJson))

}

Email.SendEmail <- function(body, url, sendEmail = TRUE, returnJson = FALSE) {
  if(Config.Email.useEmailNotification & sendEmail){
    cat("Sending email...\n")
    emailResult <- POST(url, body = body, encode = "json")
  }
  
  if(returnJson) {
    return(toJSON(body))    
  } 

  cat("Email: ", as.character(toJSON(body)) , "\n")

  return(emailResult)
}