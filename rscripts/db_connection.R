library(RPostgreSQL);

source('config.R');


DBConnection.Driver <- function() { 
  dbDriver("PostgreSQL")
}
# con<-dbConnect(drv,dbname="telemetering",user="postgres",pass="postgres");

DBConnection.OpenDBConnection <- function(verbose = FALSE) {
  # Open PostgreSQL connection, create RPostgreSQL connection object
  # Connection object must be closed after used using
  # DBConnection.CloseDBConnection(dbCon).
  #
  # Args:
  #   verbose: (Boolean) verbose mode
  #
  # Returns:
  #   RPostgreSQL connection object

  if(verbose) {
    cat('Connecting to', Config.databaseName, ' using user:', Config.databaseUser, '\n')
  }

  dbCon = dbConnect(DBConnection.Driver(),
                    dbname = Config.databaseName,
                    user   = Config.databaseUser,
                    pass   = Config.databasePassword)
  return(dbCon)
}

DBConnection.CloseDBConnection <- function(dbCon, verbose=FALSE){
  #verbose
  if (verbose) {
    cat("Closing database connection\n")
  }

  # Release RPostgreSQL connection
  dbDisconnect(dbCon);
}

DBConnection.Query <- function (queryString) {
  dbConnection <- DBConnection.OpenDBConnection()
  data <- dbGetQuery(dbConnection, queryString)
  DBConnection.CloseDBConnection(dbConnection)

  return(data)
}

DBConnection.SendQuery <- function (queryString) {
  dbConnection <- DBConnection.OpenDBConnection()
  dbSendQuery(dbConnection, queryString)
  DBConnection.CloseDBConnection(dbConnection)
}