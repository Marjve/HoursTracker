**Support Hour Tracking**

Simple time tracking APP for support users.


Get it up and running.
After you clone this project, do the following:

# modify .env for your dev
* cp .env.example .env

# install composer dependencies
* composer install

# install npm dependencies
* npm install

# generate a key for your application
* php artisan key:generate

# generate Server secret for JWT
* php artisan jwt:secret

# run the migration files to generate the schema
* php artisan migrate

# run the seeder to fill users table
* php artisan db:seed

# run webpack and watch for changes
* npm run watch
* php artisan serve

# make sure storage dir is writable
* chmod -R 777 storage


