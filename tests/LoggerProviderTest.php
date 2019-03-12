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
        $settings = [ 'settings'=>[ 'logger_name'=>'recipe', 'logger_directory'=>'../logs/' ] ];
        $container = new \Slim\Container($settings);
        $provider = new \recipe\app\LoggerProvider();
        $provider->register($container);
        $this->assertSame('Monolog\Logger', get_class($container['logger']));
    }
}
