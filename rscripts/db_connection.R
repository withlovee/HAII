library(RPostgreSQL);
drv<-dbDriver("PostgreSQL");
con<-dbConnect(drv,dbname="telemetering",user="postgres",pass="postgres");