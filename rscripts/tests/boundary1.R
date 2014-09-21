test.boundary <- function()
{
  checkEquals(6, 5)
}

test.deactivation <- function()
{
  DEACTIVATED('Deactivating this test function')
}