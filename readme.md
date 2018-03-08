## WhiteBoard

[![build status](https://gitlab.com/csc483-w2018/483-capstone/badges/master/build.svg)](https://gitlab.com/csc483-w2018/483-capstone/commits/master)

## Install
```
$ git clone git@gitlab.com:csc483-w2018/483-capstone.git

$ cd 483-capstone

$ composer install

$ npm install

$ cp .env.example .env                     # change .env according to your db credentials...

$ php artisan key:generate

$ php artisan migrate:fresh                # Set up db migrations

$ php artisan voyager:install --with-dummy # Install Voyager files & seed users and roles.

$ php artisan import:courses courses.csv   # Import courses

$ php artisan serve
```
