# job-offers
The "Job Offers Application" allows to fetch job offers from Pôle Emploi. 

## Prerequisites
* Composer
* PHP 7.4 or higher

## Launching the project

* Fork the repository
* Clone it on your computer
* Run `composer install`
* Create the database with `php bin/console doctrine:database:create`
* Run the migrations with `php bin/console doctrine:migrations:migrate`
* If you have installed Symfony binary, run this command:
* `symfony server:start`
* Then access the application in your browser at the given URL (https://localhost:8000 by default).
* If you don't have the Symfony binary installed, configure a web server like Nginx or Apache to run the application.

## Usage
* Run `symfony job:fetch-offers` or `php bin/console job:fetch-offers` to fetch job offers from [Pôle Emploi](https://pole-emploi.io/data/api/offres-emploi)
