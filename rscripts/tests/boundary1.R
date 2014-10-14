getDataBD <- function() {
  # Condition: Passed if max_bank + 4 and ground_level - 1
  # CHI001: Passed (Above right_bank but below left_bank)
  # CHI002: Passed (Below both banks)
  # KRN001: Below ground level
  # KRN002: Above maxbank (left)
  # KRN003: Above maxbank (right)
  # KRN004: Below negative ground level
  
  # code       date     time     water1 left_bank right_bank ground_level
  # 1 CHI001 2012-06-08 17:00:00 999999.000   175.937    152.212      128.037
  # 2 CHI002 2012-06-08 17:10:00      0.000    12.778     15.140       -2.341
  # 3 KRN001 2012-06-08 17:20:00     12.200   175.937    152.212      143.435
  # 4 KRN002 2012-06-08 00:00:00    136.060     9.830     15.412       -2.200
  # 5 KRN003 2012-06-08 00:10:00    185.250   165.700    175.937      128.037
  # 6 KRN004 2012-06-08 00:20:00    -15.432    15.937     25.230      -13.512
  
  
  code <- c("CHI001", "CHI002", "KRN001", "KRN002", "KRN003", "KRN004")
  date <- c("2012-06-08", "2012-06-08", "2012-06-08", "2012-06-08", "2012-06-08", "2012-06-08")
  time <- c("17:00:00", "17:10:00", "17:20:00", "00:00:00", "00:10:00", "00:20:00")
  water1       <- c(999999, 0, 12.2, 136.06, 185.25, -15.432)
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

test.isWaterLevelHaveMachineError <- function() {
  checkEquals(TRUE, isWaterLevelHaveMachineError(999999))
  checkEquals(FALSE, isWaterLevelHaveMachineError(123455))
  checkEquals(FALSE, isWaterLevelHaveMachineError(123455.5))
}

test.isWaterLevelOutOfBound <- function() {
  waterLevel  <- c(10.0, 10.0, 10.0, 10.0, NA , 0.0, 0.0)
  groundLevel <- c(0.0 , 11.0, 11.5, 10.0, 0.0, NA , 0.0)
  maxBank     <- c(20.0, 6.0 , 20.0, 5.0 , 0.0, 0.0, NA )
  
  expected <- c(FALSE, FALSE, TRUE, TRUE, FALSE, FALSE, FALSE)
  actual <- mapply(isWaterLevelOutOfBound, waterLevel, groundLevel, maxBank)
  mapply(checkEquals, expected, actual)
  
}

test.searchBoundaryProblemWithNoProblem <- function() {
  
  data <-  data.frame(
    code="CHI001",
    date="2014-01-01",
    time="00:00:00",
    water1=10,
    left_bank=100,
    right_bank=100,
    ground_level=0, 
    stringsAsFactors=FALSE
  )
  
  results <- searchBoundaryProblem("WATER", data)
  checkTrue( is.na(results) )
  
}

test.searchBoundaryProblem <- function() {
  
  # code       date     time     water1 left_bank right_bank ground_level
  # 1 CHI001 2012-06-08 17:00:00 999999.000   175.937    152.212      128.037
  # 2 CHI002 2012-06-08 17:10:00      0.000    12.778     15.140       -2.341
  # 3 KRN001 2012-06-08 17:20:00     12.200   175.937    152.212      143.435
  # 4 KRN002 2012-06-08 00:00:00    136.060     9.830     15.412       -2.200
  # 5 KRN003 2012-06-08 00:10:00    185.250   165.700    175.937      128.037
  # 6 KRN004 2012-06-08 00:20:00    -15.432    15.937     25.230      -13.512
  
  data <- getDataBD()
  results <- searchBoundaryProblem("WATER", data)
  
  # Chronological Order
  checkEquals("KRN002", as.character(results$station_code[1]))
  checkEquals("KRN003", as.character(results$station_code[2]))
  checkEquals("KRN004", as.character(results$station_code[3]))
  checkEquals("KRN001", as.character(results$station_code[4]))
  
  checkTrue(all(results$problem_type == "BD"))
  checkTrue(all(results$data_type == "WATER"))
  checkTrue(all(results$num == 1))
  
  checkEquals(as.POSIXct("2012-06-08 00:00:00"), results$start_datetime[1])
  checkEquals(as.POSIXct("2012-06-08 00:10:00"), results$start_datetime[2])
  checkEquals(as.POSIXct("2012-06-08 00:20:00"), results$start_datetime[3])
  checkEquals(as.POSIXct("2012-06-08 17:20:00"), results$start_datetime[4])
  
  checkEquals(as.POSIXct("2012-06-08 00:00:00"), results$end_datetime[1])
  checkEquals(as.POSIXct("2012-06-08 00:10:00"), results$end_datetime[2])
  checkEquals(as.POSIXct("2012-06-08 00:20:00"), results$end_datetime[3])
  checkEquals(as.POSIXct("2012-06-08 17:20:00"), results$end_datetime[4])

}

test.deactivation <- function()
{
  DEACTIVATED('Deactivating this test function')
}
