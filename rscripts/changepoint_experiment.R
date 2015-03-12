library('changepoint')

cp <- function(data, Q) {
    cpt <- multiple.meanvar.norm(data,mul.method="BinSeg",penalty="Manual",pen.value="4*log(n)", Q=Q)
    # cpt <- multiple.meanvar.norm(data,mul.method="PELT")

    meanCpt <- cpt@param.est$mean
    varCpt <- cpt@param.est$variance
    sdCpt <- sqrt(varCpt)
    changePoint <- cpt@cpts

    plot(1:length(data), data, type="l")

    for(i in 1:length(changePoint)) {
      start <- changePoint[i-1]
      if (i == 1) {
        start <- 1
      }
      end <- changePoint[i]

      lines(c(start,end), c(meanCpt[i], meanCpt[i]), col="red")
      lines(c(start,end), c(meanCpt[i] + sdCpt[i], meanCpt[i] + sdCpt[i]), col="blue")
      lines(c(start,end), c(meanCpt[i] - sdCpt[i], meanCpt[i] - sdCpt[i]), col="blue")
    }

    return(cpt)
}


shift <- function(data, sd) {
  min_x = 1
  max_x = length(data)

  start = sample(max_x,1)
  end = sample(max_x,1)
  offset = rnorm(1,mean=0,sd=sd)

  data[start:end] = data[start:end] + offset

  cat("Shift ", start, " to ", end, " with offset ", offset, "\n")

  return(data)

}
