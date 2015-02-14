#you must install the packages httr and RJSONIO.
library(httr)
library(RJSONIO)

random_id = round(runif(1, 0, 1000))
random_name = paste("R method", random_id)
random_code = paste("R", random_id, sep="-")

x <- list(
  user = "admin",
  password = "password",
  MethodDescription = random_name,
  MethodLink = "http://example.com",
  VariableID = 0
)

response <- POST("http://worldwater.byu.edu/app/index.php/default/services/api/methods",
                 body = RJSONIO::toJSON(x),
                 add_headers("Content-Type" = "application/json"),
                 verbose()
)

response_status = content(response, type="application/json")
print(response_status)
