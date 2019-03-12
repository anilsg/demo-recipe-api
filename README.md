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
    composer require phpunit/phpunit --dev
    composer require mockery/mockery --dev
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

- pub: **namespace recipe\pub**

  - pub/index.php: entry point initialises and starts server

- app: **namespace recipe\app**

  - LoggerProvider.php: injected logging service
  - DataProvider.php: injected data service
  - Router.php: sets up URI routes

- data: dev location of JSON data files

  - ingredients.json
  - recipes.json

- data-none: test data directory containing no files
- tests: PHPUnit test classes

  - DummyTest.php: trivial sanity test
  - LoggerProviderTest.php: test logger service
  - DataProviderTest.php: test data service
  - RouterTest.php: test Router

### Notes

Manual loading or composer optimised auto-loading is recommended for production.

Variations in the use of trailing slashes on URIs have not been handled.
This can be handled by Slim middleware.

In some places code could have been compressed
but multi-line staged logic has been used to make the code more readable.

The PSRs do not specify caps style for namespaces but StudlyCaps seems popular.
Namespaces were actually specified in lower case to compare to the directory names.

An OPTIONS request to the root or generic URI could be used to check status
and confirm data is being loaded and service is available.

### References

- https://www.php-fig.org/psr/psr-1/
- https://www.php-fig.org/psr/psr-2/
- https://getcomposer.org/
- https://www.slimframework.com/
- https://secure.php.net/manual/en/


