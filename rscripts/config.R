
# PostgreSQL config
Config.databaseName     <- "telemetering"
Config.databaseUser     <- "postgres"
Config.databasePassword <- "postgres"

# Data setup
Config.defaultDataInterval <- 600     # 10 minutes
Config.allowDataType <- c("WATER", "RAIN")

# Email setup
Config.Email.baseURL <- "http://qc.haii.or.th/HAII/public/index.php"
Config.Email.basePath <- "/api/email/send_alert/instantly"
Config.Email.fullURL <- paste0(Config.Email.baseURL, Config.Email.basePath)
Config.Email.useEmailNotification <- FALSE
Config.Email.APIKey <- "HAIIEMAILKEY"

# Out of Range config
Config.OutOfRange.Water.groundLevelOffset <- -1
Config.OutOfRange.Water.bankOffset <- 4

Config.OutOfRange.Rain.threshold <- 120

# Config.OutOfRange.backwardThreshold <- 60 * 60 * 24
Config.OutOfRange.backwardThreshold <- 3600 * 24 * 30 * 5


# Missing Gap config
# Config.MissingGap.defaultInterval <- 7 * 24 * 60 * 60    # 7 days
Config.MissingGap.defaultInterval <- 0

# Flat Value Config
# Config.FlatValue.defaultIntervall <- 3 * 24 * 60 * 60 # 3 days
Config.FlatValue.defaultThreshold <- 10 * 60 # 3 days