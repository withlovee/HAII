source('flat_value_controller.R')
source('missing_gap_controller.R')

Exec.DailyOperation <- function() {
	FlatValue.Controller.DailyOperation("WATER")
	MissingGap.Controller.DailyOperation("WATER")
	FlatValue.Controller.DailyOperation("RAIN")
	MissingGap.Controller.DailyOperation("RAIN")
}