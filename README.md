# Welcome to my COSC 61 repo
## Required setup before project
1. Setup EC2 instance on AWS
2. Run the following lines of bash code to setup your server environment 
**sudo -i**
**systemctl start httpd** 
**systemctl start mariadb**
3. Create database on your EC2 instance by importing the .sql file provided in the repo using the import wizard on MYSQL workbench
4. Create files global_countries.html and global_countries_search.php in your depository **/var/www/html**
5. Go to http://yourinstanceIP/global_countries.html
6. You can then use the front-end webapp search tool to easily query the global countries database
![54 173 194 251_global_countries html](https://github.com/brianng882/cosc61/assets/90651095/26f49a4b-af64-4c05-a454-c8d8545d9368)
