library(RPostgreSQL);
drv<-dbDriver("PostgreSQL");
# con<-dbConnect(drv,dbname="telemetering",user="postgres",pass="postgres");

openDbConnection <- function() {
  dbConnect(drv,dbname="telemetering",user="postgres",pass="postgres");
}

closeDbConnection <- function(con){
  dbDisconnect(con);
}