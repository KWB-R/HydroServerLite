#Hello!
#Here are the commands how you can install HydroServer Lite 3.0 on a private Linux Server. 
#We have tested it on Ubuntu 14.04 using the following commands in the terminal:

#install lamp
sudo apt-get install lamp-server^

#in the LAMP installation you'll be asked to enter a password for mysql root user. remember this password.

#install php5-mcrypt
sudo apt-get install php5-mcrypt
sudo php5enmod mcrypt && sudo service apache2 restart

#get the latest HydroServer Lite Files
sudo apt-get install git
git clone https://git.codeplex.com/hydroserverlite ~/hydroserverlite
sudo mv ~/hydroserverlite /var/www/html/hydroserverlite

#setup the MySQL Database (instead of ROOT_PASSWORD use your real mysql root password!)
mysql --user=root --password=ROOT_PASSWORD --execute="CREATE DATABASE hydrodb;"
mysql --user=root --password=ROOT_PASSWORD --execute="CREATE USER 'hydrouser'@'localhost' identified by 'mypass';"
mysql --user=root --password=ROOT_PASSWORD --execute="grant all on hydrodb.* to hydrouser@localhost idendified by 'mypass';"

#setup the HydroServer file permissions
cd /var/www/html/hydroserverlite
sudo chmod -R 777 application/config/installations
sudo chmod -R 777 application/language
sudo chmod -R 777 uploads

#create the default installation file: replace the mysql username, mysql db name, and mysql password.
sudo sed -i 's/YOUR_DATABASE_USER_NAME/hydrouser/' sample_installation_file.txt
sudo sed -i 's/YOUR_MYSQL_DATABASE_NAME/hydrodb/' sample_installation_file.txt
sudo sed -i 's/YOUR_MYSQL_DATABASE_PASSWORD/mypass/' sample_installation_file.txt
sudo mv sample_installation_file.txt default.php

#now open the Firefox browser. In firefox, go to:
http://localhost/hydroserverlite/index.php/default/home/installation

#enter the new HydroServer password for the user his_admin. For example you can use his_password.
#Select Setup Type: Basic
# Set "Use default database connection?" to Yes
# Set the Website Path to hydrodb.
# Set the Language Code to English, and enter the Organization's name, Parent Website's name, and Parent Website.
# Enter the Variable Code, Time Support, and UTCOffset.
# Click "Save Settings".
YOUR Private Linux HydroServer installation is complete!

