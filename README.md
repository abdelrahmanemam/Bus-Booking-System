# Bus-Booking-System

1. create env file

2. Adjust your env variables. Make sure you set your database env vars. Add any values as the database will be created per your env var values.

``` 
Example databas env Values

DB_CONNECTION=mysql <--- Don't change.
DB_HOST=database <--- Don't change.
DB_PORT=3306 <--- Don't change.
DB_DATABASE=laravel
DB_USERNAME=root
DB_PASSWORD= 
```
3. To build the app run `docker-compose build --no-cache && docker-compose up --force-recreate`


* Default PHP port is 8000. Connect via http://127.0.0.1:8000
* Default DB port is 3306.

* Attached postman collection for testing.

# Administration Part
To access admin part you need to sign in with dump admin data in the seeder 
username => admin@test.com
password => 123456

# User Part
To access user part you need to sign in with dump user data in the seeder
username => user@test.com
password => 123456
