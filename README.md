## PHP Demo Recipe API

### Environment

- Built on Ubuntu 18.10 Cosmic
- PHP7 and modules required as shown
- Documented for PHP composer installed globally
- Docker has not been included in the time available

`apt install php7 composer php-mbstring php-xml php-xdebug`

### Installation

Clone `composer.json` and `composer.lock` then:

    composer install

### Usage

Convenience script provided in composer.json
(executes `php -S localhost:8080 -t pub`):

    composer start

### References

- https://www.php-fig.org/psr/psr-1/
- https://www.php-fig.org/psr/psr-2/
- https://getcomposer.org/
- https://www.slimframework.com/

