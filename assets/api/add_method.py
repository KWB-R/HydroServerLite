import urllib2
import json
import random

# this is a sample python client for the JSON API for adding a method
# author: Jiri Kadlec

username = 'admin'
password = 'password'

methID = int(random.random() * 1000)
methName = "Python Method %s" % methID

#STEP 1: prepare the uploaded data in JSON format
data = {
    "user": username,
    "password": password,
    "MethodDescription": methName,
    "MethodLink": "http://example.com",
    "VariableID": 0
}
postdata = json.dumps(data)


#STEP 2: post the data values to HydroServer and check response status
uploadURL = 'http://worldwater.byu.edu/app/index.php/default/services/api/methods'
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
