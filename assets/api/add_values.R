#you must install the packages httr and RJSONIO.
library(httr)
library(RJSONIO)

sourceID = 15
variableID = 43
siteID = 170
methodID = 10
dataValue1 = runif(1, 0, 100)
dataValue2 = runif(1, 0, 100)
dataValue3 = runif(1, 0, 100)

now = Sys.time()
date1 = now + 5 * 60
date2 = now + 10 * 60
date3 = now + 15 * 60

values <- c(dataValue1, dataValue2, dataValue3)
dates <- c(date1, date2, date3)
dates_formatted <- format(dates, "%Y-%m-%d %H:%M:%S")
value_list <- as.matrix(cbind(dates_formatted, values))
colnames(value_list) <- NULL
rownames(value_list) <- NULL

x <- list(
    user = "admin",
    password = "password",
    SiteID = siteID,
    VariableID = variableID,
    MethodID = methodID,
    SourceID = sourceID,
    values = value_list
    )

resp = POST("http://worldwater.byu.edu/app/index.php/default/services/api/values",
         body = RJSONIO::toJSON(x),
         add_headers("Content-Type" = "application/json"),
         verbose()
    )
response_status = content(resp, type="application/json")
print(response_status)
