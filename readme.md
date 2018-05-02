## CoCo - Collaborative Courses

## Install
```
$ git clone git@gitlab.com:csc483-w2018/483-capstone.git

$ cd 483-capstone

$ cp .env.example .env                     # Change .env according to your db credentials...

$ php artisan key:generate                

$ composer install

$ npm install

$ php artisan migrate:fresh                # Set up db migrations.

$ php artisan voyager:install --with-dummy # Install Voyager files & seed users and roles.

$ php artisan import:courses courses.csv   # Import courses.

$ php artisan serve                        # Spins up a webserver on port 8000.
```
