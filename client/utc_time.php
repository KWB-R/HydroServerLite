<?php
$time = '2012-06-02 18:00:00';
$h = "-7";// Hour for time zone goes here e.g. +7 or -4, just remove the + or -
$hm = $h * 60;
$ms = $hm * 60;
$gmdate = gmdate("m/d/Y g:i:s A", ($time)-($ms)); // the "-" can be switched to a plus if that's what your time zone is.
echo "Local time is: $time . ";
echo "utc time is :  $gmdate . ";
?> 
