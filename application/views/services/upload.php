<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8"/>
	<title>Hydroserver Data Upload Service</title>
	<link rel="shortcut icon" href="<?=base_url()?>assets/images/favicon.ico" type="image/x-icon" />
	<link rel="bookmark" href="<?=base_url()?>assets/images/favicon.ico" />

	<link href="<?=base_url()?>assets/css/style.css" rel="stylesheet"/>

	<link href="<?=base_url()?>assets/css/smoothness/jquery-ui-1.10.3.custom.css" rel="stylesheet"/>

	<script type="text/javascript" src="<?=base_url()?>assets/js/jquery-1.9.1.min.js"></script>
	<script type="text/javascript" src="<?=base_url()?>assets/js/jquery-ui-1.10.3.custom.min.js"></script>

</head>
<body>

<div id="container">
	<h1>HydroServer Data Upload API</h1>

	<div id="body">
		
		<p>You can use the data upload API for automatically uploading values to the Hydroserver ODM database. Send
		the data values using <strong>HTTP POST</strong> request in <strong>json</strong> format to:</p>
		<p><?=base_url()?>index.php/upload/values</p>
		<br />
		<h2>example script: python</h2>
<pre>
import json
import urllib2

url = '<?=base_url()?>index.php/upload/values'
data = {
    "user": "YOUR_USERNAME",     
    "password": "YOUR_PASSWORD", 
    "sitecode": "SITE01",        
    "variablecode": "tmin",      
    "methodid": 60,              
    "sourceid": 1,              
    "values": [("2014-09-01 04:00:00", 7.5),
               ("2014-09-01 05:00:00", 7.6),
               ("2014-09-01 10:00:00", 8.98)]
}
req = urllib2.Request(url)
req.add_header('Content-Type', 'application/json')
postdata = json.dumps(data)

try:
    response = urllib2.urlopen(req, postdata)
    status = json.load(response)
    print status

except urllib2.HTTPError, e:
    print e.code
    print e.msg
    print e.headers
    print e.fp.read()
</pre>
		<div id="generated_url"></div>

	</div>

	<p class="footer"><font color=#000000 face=Arial, Helvetica, sans-serif size=2><i>Copyright &copy; 2014. <a href='http://hydroserverlite.codeplex.com/' target='_blank' class='reversed'>Hydroserver Lite</a>. All Rights Reserved. <a href='http://hydroserverlite.codeplex.com/team/view' target='_blank' class='reversed'>Meet the Developers</a></i></font></p>
</div>

</body>
</html>