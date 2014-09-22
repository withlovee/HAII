getDataBD <- function() {
  # Condition: Passed if max_bank + 4 and ground_level - 1
  # CHI001: Passed (Above right_bank but below left_bank)
  # CHI002: Passed (Below both banks)
  # KRN001: Below ground level
  # KRN002: Above maxbank (left)
  # KRN003: Above maxbank (right)
  # KRN004: Below negative ground level
  code <- c("CHI001", "CHI002", "KRN001", "KRN002", "KRN003", "KRN004")
  date <- c("2012-06-08", "2012-06-08", "2012-06-08", "2012-06-08", "2012-06-08", "2012-06-08")
  time <- c("17:00:00", "17:10:00", "17:20:00", "00:00:00", "00:10:00", "00:20:00")
  water1       <- c(166.53, 0, 12.2, 136.06, 185.25, -15.432)
  left_bank    <- c(175.937, 12.778, 175.937, 9.83, 165.7, 15.937)
  right_bank   <- c(152.212, 15.14, 152.212, 15.412, 175.937, 25.23)
  ground_level <- c(128.037, -2.341, 143.435, -2.2, 128.037, -13.512)
  data.frame(
    code=code,
    date=date,
    time=time,
    water1=water1,
    left_bank=left_bank,
    right_bank=right_bank,
    ground_level=ground_level, 
    stringsAsFactors=FALSE
  )
}


test.getMaxBank <- function() {
  left_bank    <- c(10.0, 20.0, 30.0, NA  , 10.0, NA)
  right_bank   <- c(10.0, 30.0, 20.0, 10.0, NA  , NA)
  
  expected <- c(10.0, 30.0, 30.0, 10.0, 10.0, NA)
  actual <- mapply(getMaxBank, left_bank, right_bank)
  mapply(checkEquals, expected, actual)
}

test.isOutOfBound <- function() {
  waterLevel <- c()
  groundLevel <- c()
  maxBank <- c()
  
}

test.searchBoundaryProblem <- function() {
  data <- getDataBD()
  results <- searchBoundaryProblem(data)
  checkEquals("KRN001", results$code[1])
  checkEquals("KRN002", results$code[2])
  checkEquals("KRN003", results$code[3])
  checkEquals("KRN004", results$code[4])
  checkEquals(12.2, results$water1[1])
  checkEquals(136.06, results$water1[2])
  checkEquals(185.25, results$water1[3])
  checkEquals(-15.432, results$water1[4])
  checkEquals(175.937, results$max_bank[1])
  checkEquals(15.412, results$max_bank[2])
  checkEquals(175.937, results$max_bank[3])
  checkEquals(25.230, results$max_bank[4])
}

test.deactivation <- function()
{
  DEACTIVATED('Deactivating this test function')
}
