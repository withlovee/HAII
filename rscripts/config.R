
# PostgreSQL config
Config.databaseName     <- "telemetering"
Config.databaseUser     <- "postgres"
Config.databasePassword <- "postgres"

# Data setup
Config.defaultDataInterval <- 600     # 10 minutes

# Email setup
Config.Email.baseURL <- "http://qc.haii.or.th/HAII/public/index.php"
Config.Email.basePath <- "/api/email/send_alert/instantly"
Config.Email.fullURL <- paste0(Config.Email.baseURL, Config.Email.basePath)
Config.Email.useEmailNotification <- FALSE
Config.Email.APIKey <- "HAIIEMAILKEY"

# Missing Gap config
# Config.MissingGap.defaultInterval <- 7 * 24 * 60 * 60    # 7 days
Config.MissingGap.defaultInterval <- 0