library('fpc')
source('helper.R')

ou <- function(data) {
	eps <- 0.1
	minPts <- 10

	x <- c(rnorm(1000, mean=-1.2, sd=0.5), rnorm(1000, mean=1.2, sd=0.5))
	y <- c(rnorm(1000, mean=-1.2, sd=0.5), rnorm(1000, mean=1.2, sd=0.5))

	z <- data.frame(x=sample(x), y=sample(y))

	res <- dbscan(z, eps=eps, MinPts=minPts)

	return(res)
}