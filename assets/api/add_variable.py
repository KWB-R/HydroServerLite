import urllib2
import json
import random

# this is a sample python client for the JSON API for adding a variable
# author: Jiri Kadlec

username = 'admin'
password = 'password'

varID = random.random() * 1000
varName = "python variable %s" % varID

#STEP 1: prepare the uploaded data in JSON format
data = {
    "user": username,
    "password": password,
    "VariableCode": "python-%s" % varID,
    "VariableName": "Color",
    "Speciation": "Not Applicable",
    "VariableUnitsID": 189,
    "SampleMedium": "Groundwater",
    "ValueType": "Sample",
    "IsRegular": 1,
    "TimeSupport": 0,
    "TimeUnitsID": 100,
    "DataType": "Average",
    "GeneralCategory": "Hydrology",
    "NoDataValue": -9999
}
postdata = json.dumps(data)


#STEP 2: post the data values to HydroServer and check response status
uploadURL = 'http://worldwater.byu.edu/app/index.php/default/services/api/variables'
req = urllib2.Request(uploadURL)
req.add_header('Content-Type', 'application/json')

try:
    response = urllib2.urlopen(req, postdata)
    print response.read()

except urllib2.HTTPError, e:
    print e.code
    print e.msg
    print e.headers
    print e.fp.read()
