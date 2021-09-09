Exercise 1:
 - create project directory in your xampp or wamp
 - extract attached zip file
 - import DB/assignment_db.sql in your database
 - update config/database.php as per your database details: 
 	'hostname' => 'localhost',
	'username' => 'root',
	'password' => '',
	'database' => 'assignmentdb', 

For sample data generator run below command in project directory
php cli_command.php

Exercise 2:
- please check Exercise2.doc file in extracted zip file.


Exercise 3:
- using postman tool you can execute below curl commands

2) update record
curl --location --request PUT 'http://localhost/assignment/routerapi' \
--header 'Content-Type: application/json' \
--header 'X-API-KEY: MyRandomToken' \
--data-raw '{
    "sapid":"123",
    "hostname":"test42.com",
    "loopback":"127.0.0.2",
    "mac_address":"test"
}'

3) create new record
curl --location --request POST 'http://localhost/assignment/routerapi' \
--header 'Content-Type: application/json' \
--header 'X-API-KEY: MyRandomToken' \
--data-raw '{
    "sapid":"123",
    "hostname":"test32.com",
    "loopback":"127.0.0.2",
    "mac_address":"test"
}'

4) Get router list based on sap Id 
curl --location --request GET 'http://localhost/assignment/routerapi/?sapId=sampleSapId_1' \
--header 'Content-Type: application/json' \
--header 'X-API-KEY: MyRandomToken'

5) Get router list based on ip range 
curl --location --request GET 'http://localhost/assignment/routerapi/?ipStart=127.0.0.1&ipEnd=127.0.0.1' \
--header 'Content-Type: application/json' \
--header 'X-API-KEY: MyRandomToken'


