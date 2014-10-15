library(RUnit);


##### Testing boundary.R
source('boundary.R')

boundary.test.suite <- defineTestSuite("boundary",
                              dirs = file.path("tests"),
                              testFileRegexp = '^test\\.boundary\\.\\d+\\.R')

boundary.test.result <- runTestSuite(boundary.test.suite)

printTextProtocol(boundary.test.result)


##### Testing email.R
source('email.R')

email.test.suite <- defineTestSuite("email",
                                    dirs = file.path("tests"),
                                    testFileRegexp = '^test\\.email\\.\\d+\\.R')

email.test.result <- runTestSuite(email.test.suite)

printTextProtocol(email.test.result)
