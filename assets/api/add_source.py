import urllib, urllib2, cookielib
import json
import datetime
import random

# this is a sample python client for the JSON API for adding a variable
# author: Jiri Kadlec

username = 'admin'
password = 'password'

sourceID = random.random() * 1000
sourceName = "python test %s" % sourceID

#STEP 1: prepare the uploaded data in JSON format
data = {
    "user": username,
    "password": password,
    "organization": "python source %s" % sourceID,
    "description": "uploaded from python: %s" % sourceID,
    "link": "http://example.com",
    "name": "name %s" % sourceID,
    "phone": "012-345-6789",
    "email": "test@gmail.com",
    "address": sourceName,
    "city": sourceName,
    "state": sourceName,
    "zipcode": sourceName,
    "citation": "uploaded from python as a test",
    "metadata": 10
}
postdata = json.dumps(data)


#STEP 2: post the data values to HydroServer and check response status
uploadURL = 'http://worldwater.byu.edu/app/index.php/default/services/api/sources'
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
