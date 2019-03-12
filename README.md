## PHP Demo Recipe API

### Environment

- Built on Ubuntu 18.10 Cosmic
- PHP7 and modules required as shown
- Documented for PHP composer installed globally
- Docker has not been included in the time available
- Uses SlimPHP web application framework.
- Uses PHPUnit.

`apt install php7 composer php-mbstring php-xml php-xdebug`

### Installation

Clone `composer.json` and `composer.lock` then:

    composer install

Install reproduces:

    composer require slim/slim
    composer require phpunit/phpunit
    composer require mockery/mockery
    composer require monolog/monolog

During build modifications may also require:

    composer dump-autoload

### Usage

Convenience script to start the application, provided in composer.json
(executes `php -S localhost:8080 -t pub`):

    composer start

Convenience script to run tests and dump coverage, provided in composer.json
(executes `php vendor/bin/phpunit ; cat tests/build/coverage.txt`):

    composer test

### Structure

- pub: Entry point containing index.php.
- app: Classes implementing application.
- data: dev location of JSON data files.
- tests: PHPUnit test classes.

### References

- https://www.php-fig.org/psr/psr-1/
- https://www.php-fig.org/psr/psr-2/
- https://getcomposer.org/
- https://www.slimframework.com/
- https://secure.php.net/manual/en/


