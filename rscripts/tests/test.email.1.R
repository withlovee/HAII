test.convertAbbrToFullName <- function()
{
  checkEquals("Out of Range", convertAbbrToFullProblemName("BD"))
  checkEquals("Flat Value", convertAbbrToFullProblemName("FV"))
  checkEquals("Missing Value", convertAbbrToFullProblemName("MV"))
  checkEquals("Outliers", convertAbbrToFullProblemName("OL"))
  checkEquals("Inhomogenity", convertAbbrToFullProblemName("IH"))
  checkEquals("Out of Range", convertAbbrToFullProblemName("BD"))
  checkEquals("Missing Pattern", convertAbbrToFullProblemName("MP"))
  checkTrue(is.na(convertAbbrToFullProblemName("AB")))
}

test.sendProblemMailNotification <- function() {
  
  checkEquals("{\"key\":\"TESTKEY\",\"num\":3,\"date\":\"2012-01-01 00:00:00\",\"rain\":[],\"water\":[{\"name\":\"Out of Range\",\"stations\":[\"S1\",\"S2\",\"S3\"]}]}",
              sendProblemMailNotification("WATER", "BD", as.POSIXct("2012-01-01 00:00:00"), c("S1","S2","S3"), sendEmail=FALSE, returnJson=TRUE, key="TESTKEY"))
  checkEquals("{\"key\":\"TESTKEY\",\"num\":3,\"date\":\"2012-01-01 00:00:00\",\"rain\":[{\"name\":\"Missing Pattern\",\"stations\":[\"S1\",\"S2\",\"S3\"]}],\"water\":[]}",
              sendProblemMailNotification("RAIN", "MP", as.POSIXct("2012-01-01 00:00:00"), c("S1","S2","S3"), sendEmail=FALSE, returnJson=TRUE, key="TESTKEY"))
  
  checkEquals("{\"key\":\"TESTKEY\",\"num\":1,\"date\":\"2012-01-01 00:00:00\",\"rain\":[{\"name\":\"Missing Pattern\",\"stations\":[\"S1\"]}],\"water\":[]}",
              sendProblemMailNotification("RAIN", "MP", as.POSIXct("2012-01-01 00:00:00"), c("S1"), sendEmail=FALSE, returnJson=TRUE, key="TESTKEY"))
}


