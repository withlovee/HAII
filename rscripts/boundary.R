source('db_connection.R')

getSettings <- function(){
  rs <- dbSendQuery(con, "select * from tele_wl_detail");
  fetch(rs,n=2);
}

settings <- getSettings()
settings$1

dbDisconnect(con);
dbUnloadDriver(drv);