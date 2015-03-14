source('out_of_range_controller.R')
source('missing_gap_controller.R')
source('flat_value_controller.R')

t.or.w <- system.time(OutOfRange.Controller.Batch("WATER", as.POSIXct("2012-01-01"), as.POSIXct("2012-07-01")))
t.or.r <- system.time(OutOfRange.Controller.Batch("RAIN", as.POSIXct("2012-01-01"), as.POSIXct("2012-07-01")))

t.mg.w <- system.time(MissingGap.Controller.Batch("WATER", as.POSIXct("2012-01-01"), as.POSIXct("2012-07-01")))
t.mg.r <- system.time(MissingGap.Controller.Batch("RAIN", as.POSIXct("2012-01-01"), as.POSIXct("2012-07-01")))

t.fv.w <- system.time(FlatValue.Controller.Batch("WATER", as.POSIXct("2012-01-01"), as.POSIXct("2012-07-01")))
t.fv.r <- system.time(FlatValue.Controller.Batch("RAIN", as.POSIXct("2012-01-01"), as.POSIXct("2012-07-01")))

print("or - WATER")
print(t.or.w)

print("or - RAIN")
print(t.or.w)

print("mg - WATER")
print(t.mg.w)

print("mg - RAIN")
print(t.mg.w)

print("fv - WATER")
print(t.fv.w)

print("fv - RAIN")
print(t.fv.w)