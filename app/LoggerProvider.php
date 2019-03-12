<?php
namespace recipe\app;

use \Pimple\Container;
use \Pimple\ServiceProviderInterface;

/**
* Logger service to dump messages to log file.
* Loads logger name from 'logger_name' specified in container at registration.
* Logs to file named with 'logger_name.log' in 'logger_directory'.
*
* $container['settings']['logger_name'] name of logger and name of log file
* $container['settings']['logger_directory'] directory to store log files
*
* @package    recipe
* @subpackage app
* @version    $Id:$
* @author     anilsg
* @link       https://github.com/anilsg/demo-recipe-api
*/
class LoggerProvider implements ServiceProviderInterface
{
    public function register(Container $container)
    {
        $logger_name = $container['settings']['logger_name']; // Nomination for logs e.g. 'recipe'.
        $logger = new \Monolog\Logger($logger_name); // Create instance of chosen logger.
        $file_name = $container['settings']['logger_directory'] . $logger_name . '.log'; // Log file name.
        $logger->pushHandler(new \Monolog\Handler\StreamHandler($file_name)); // Log to the file.
        $container['logger'] = $logger; // Make the logger available to the app.
    }
}

