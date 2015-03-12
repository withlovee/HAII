Bolingers <- function(data, backInterval) {
	upper <- c()
	lower <- c()
	for(i in 1:length(data)) {
		if(i == 1) {
			upper <- c(upper,0) + data[i]
			lower <- c(lower,0) + data[i]
		} else {
			upper <- c(upper, 2*sd(data[max(i-backInterval-1,1):i-1]) + mean(data[max(i-backInterval-1,1):i-1]))
			lower <- c(lower, (-2)*sd(data[max(i-backInterval-1,1):i-1]) + mean(data[max(i-backInterval-1,1):i-1]))
		}
	}

	return(data.frame(upper=upper, lower=lower))
}