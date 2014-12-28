library("httr")
source("config.R")

emailResult <- GET(Config.Email.Daily.fullURL)

return(emailResult)