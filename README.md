# gc
Symfony API - pulling fake data from http://jsonplaceholder.typicode.com

Symfony v4.4
PHP v7.4

# installation
1. Copy `.env` to `.env.local`
2. Run `docker-compose up -d` and then `docker-compose exec web bash`
3. Run `composer install`
4. Run `bin/console doctrine:migrations:migrate`
5. Run `bin/console users:posts:sync` to fill the database with data

Testable Swagger docs available on homepage.

For static analysis, run `composer run phpstan`. 
For coding style check, run `composer run cs-fixer-fix`. 

Data pulling is done using command that can be executed anytime in console and it's meant to run on server as cronjob.
