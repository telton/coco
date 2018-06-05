## CoCo - Collaborative Courses

CoCo is a project that was built for my computer science capstone course for my Winter 2018 semester at the University of Michigan-Flint. CoCo was designed to solve a few problems that BlackBoard (UM-Flint's current learning management system) has. See the [notes document](docs/notes/notes.md) to see the general outline of the software.

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
