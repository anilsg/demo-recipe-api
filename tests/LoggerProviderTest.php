<?php

use \PHPUnit\Framework\TestCase;
use \Mockery\Adapter\Phpunit\MockeryTestCase;
use \Slim\Container;

/**
* Test the recipe\app\LoggerProvider at least has generated an instance of Monolog.
*/
class LoggerProviderTest extends PHPUnit\Framework\TestCase
{
    public function testLoggerProvider()
    {
        $container = [ 'settings'=>[ 'logger_name'=>'recipe', 'logger_directory'=>'../logs/' ] ];
        $container = new \Slim\Container($container); // Create dependency injection container.
        $provider = new \recipe\app\LoggerProvider(); // Instance of LoggerProvider to test.
        $provider->register($container); // Pass container in when setting up with call to register.
        $this->assertSame('Monolog\Logger', get_class($container['logger'])); // Logger should be present now.
    }
}
