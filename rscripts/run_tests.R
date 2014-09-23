library(RUnit);
source('boundary.R')

test.suite <- defineTestSuite("boundary",
                              dirs = file.path("tests"),
                              testFileRegexp = '^boundary\\d+\\.R')

test.result <- runTestSuite(test.suite)

printTextProtocol(test.result)
