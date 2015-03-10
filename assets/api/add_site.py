import urllib2
import json
import random

# this is a sample python client for the JSON API for adding a site
# author: Jiri Kadlec

username = 'admin'
password = 'password'

siteID = int(random.random() * 1000)
randomLat = random.random() * 180.0 - 90.0
randomLon = random.random() * 360.0 - 180.0
randomElev = random.random() * 1000
siteName = "Python Site %s" % siteID

#STEP 1: prepare the uploaded data in JSON format
data = {
    "user": username,
    "password": password,
    "SourceID": 35,
    "SiteName": siteName,
    "SiteCode": "python-%s" % siteID,
    "Latitude": randomLat,
    "Longitude": randomLon,
    "SiteType": "Atmosphere",
    "Elevation_m": randomElev,
    "Comments": "site uploaded from Python"
}
print data
postdata = json.dumps(data)


#STEP 2: post the data values to HydroServer and check response status
uploadURL = 'http://worldwater.byu.edu/app/index.php/default/services/api/sites'
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
