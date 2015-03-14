source('inhomogeneity_controller.R')
source('outliers_controller.R')
source('missing_pattern_controller.R')

Exec.MonthlyOperation <- function() {
	Inhomogeneity.Controller.MonthlyOperation()
	Outliers.Controller.MonthlyOperation()
	MissingPattern.Controller.MonthlyOperation("WATER")
	MissingPattern.Controller.MonthlyOperation("RAIN")
}

# for testing
# currentTime <- as.POSIXct('2012-02-01')
# res1 <- Inhomogeneity.Controller.MonthlyOperation(currentTime, TRUE)
# res2 <- Outliers.Controller.MonthlyOperation(currentTime, TRUE)
# res3 <- MissingPattern.Controller.MonthlyOperation("WATER", currentTime, TRUE)
# res4 <- MissingPattern.Controller.MonthlyOperation("RAIN", currentTime, TRUE)
# print(head(res1))
# print(head(res2))
# str(res3)
# str(res4)