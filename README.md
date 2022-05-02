# job-offers

## Prerequisites
* Composer
* PHP 8.0.2 or higher

## Launching the project

* Fork the repository
* Clone it on your computer
* Run `composer install`
* Create the database with `symfony database:create`
* Run the migrations with `symfony migrations:migrate`
* If you have installed Symfony binary, run this command:
* `symfony server:start`
* Then access the application in your browser at the given URL (https://localhost:8000 by default).
* If you don't have the Symfony binary installed, configure a web server like Nginx or Apache to run the application.

## Usage
* Run `symfony job:fetch-offers` or `php bin/console job:fetch-offers` to fetch job offers from Pôle Emploi [Pôle Emploi](https://pole-emploi.io/data/api/offres-emploi)
