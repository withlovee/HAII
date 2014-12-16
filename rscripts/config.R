
# PostgreSQL config
Config.databaseName     <- "telemetering"
Config.databaseUser     <- "postgres"
Config.databasePassword <- "postgres"

# Data setup
Config.defaultDataInterval <- 600     # 10 minutes

# Missing Gap config
Config.MissingGap.defaultInterval <- 7 * 24 * 60 * 60    # 7 days