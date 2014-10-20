############################## Configuration ##############################
# 1. path: working directory path                                         #
###########################################################################

path <- '/Applications/XAMPP/xamppfiles/htdocs/HAII/rscripts'



###########################[ End Configuration ]###########################



setwd(path)

args <- commandArgs(trailingOnly = TRUE)

print(args)
source(args[1])