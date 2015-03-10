#you must install the packages httr and RJSONIO.
library(httr)
library(RJSONIO)

random_id = round(runif(1, 0, 1000))
random_name = paste("R test", random_id)
random_code = paste("R", random_id, sep="-")

x <- list(
    user = "admin",
    password = "password",
    VariableCode = random_code,
    VariableName = "Color",
    Speciation = "Not Applicable",
    VariableUnitsID = 189,
    SampleMedium = "Groundwater",
    ValueType = "Sample",
    IsRegular = 1,
    TimeSupport = 0,
    TimeUnitsID = 100,
    DataType = "Average",
    GeneralCategory = "Hydrology",
    NoDataValue = -9999
    )

response <- POST("http://worldwater.byu.edu/app/index.php/default/services/api/variables",
         body = RJSONIO::toJSON(x),
         add_headers("Content-Type" = "application/json"),
         verbose()
    )

response_status = content(response, type="application/json")
print(response_status)
