import urllib2
import json
import datetime
import random

# this is a sample python client for tadding multiple data values
# author: Jiri Kadlec
# to check if data values were added, go to worldwater.byu.edu/app and site 'Teva Test' inside Utah Lake.

username = 'admin'
password = 'password'

sourceID = 15
variableID = 43
siteID = 170
methodID = 10
dataValue1 = random.random() * 100
dataValue2 = random.random() * 100
dataValue3 = random.random() * 100

currentDateTime = datetime.datetime.now()
date1 = currentDateTime + datetime.timedelta(minutes=10)
date2 = currentDateTime + datetime.timedelta(minutes=20)
date3 = currentDateTime + datetime.timedelta(minutes=30)

#STEP 1: prepare the uploaded data in JSON format
data = {
    "SiteID": siteID,
    "VariableID": variableID,
    "MethodID": methodID,
    "SourceID": sourceID,
    "values": [(date1.strftime("%Y-%m-%d %H:%M:%S"), dataValue1),
               (date2.strftime("%Y-%m-%d %H:%M:%S"), dataValue2),
               (date3.strftime("%Y-%m-%d %H:%M:%S"), dataValue3)]
}

postdata = json.dumps(data)
uploadURL = 'http://worldwater.byu.edu/app/index.php/default/services/api/values'
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
