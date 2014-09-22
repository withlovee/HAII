source('db_connection.R')

# Calculate max bank of water data by max(leftBank, rightBank)
# params:
#   data.frame waterData - Data frame of water level and station detail
# return:
#   vector - maximum bank level
getMaxBank <- function(waterData) {
  # bind left and right bank level together
  leftRightBank <- cbind(waterData$left_bank, waterData$right_bank)
  
  # return vector of pairwise maximum
  apply(leftRightBank, 1, max)
}

isOutOfBound <- function(waterLevel, groundLevel, maxBank, groundLevelOffset = -1, maxBankOffset = 4) {
  groundLevelOffset < -1
  maxBankOffset <- 4
  isOverGroundLevel <- waterLevel >= groundLevel + groundLevelOffset
  isUnderMaxBank <- waterLevel <= maxBank + maxBankOffset
  
  !(isOverGroundLevel & isUnderMaxBank)
}

searchBoundaryProblem <- function(data) {
  data$max_bank <- getMaxBank(data)
  hasBoundaryProblem <- mapply(isOutOfBound, data$water1, data$ground_level, data$max_bank)
  
  data[hasBoundaryProblem,]
}


rs <- dbGetQuery(con, "SELECT 
  data_log.code, 
  data_log.date,
  data_log.time,
  data_log.water1,
  tele_wl_detail.left_bank, 
  tele_wl_detail.right_bank,
  tele_wl_detail.ground_level
FROM 
  data_log
inner join tele_wl_detail on tele_wl_detail.code = data_log.code
where data_log.water1 is not null and data_log.date = DATE '2012-02-28'
and tele_wl_detail.left_bank > tele_wl_detail.right_bank
limit 20")

rs$max_bank <- getMaxBank(rs)
rs
#searchBoundaryProblem(rs)

dbDisconnect(con);
# dbUnloadDriver(drv);