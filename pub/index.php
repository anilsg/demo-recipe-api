<?php
namespace recipe\pub;

require '../vendor/autoload.php';

/**
* Entry point to start the recipe REST API app.
* Dependencies and settings are configured here and loaded into the DI container.
* Further processing and instantiation occurs in the invoked classes.
* @package    recipe
* @subpackage pub
* @version    $Id:$
* @author     anilsg
* @link       https://github.com/anilsg/demo-recipe-api
*/

// Configure SlimPHP app settings in the DI container.
// Settings like displayErrorDetails are suitable for dev only.
// Enhance this by loading settings from the environment,
// to support transition to other settings in stage and prod.
$settings['displayErrorDetails'] = true;
$settings['addContentLengthHeader'] = false;

// Configure logger settings for logging module to use.
// Logger expected to use 'logger_name' for log file name.
$settings['logger_name'] = 'recipe';
$settings['logger_directory'] = '../logs/';

// Pass in configuration such as data load location.
// This can be set from here for dev and prod as required.
// (The run directory for the app is pub so need to refer up to parent.)
// \recipe\app\DataProvider will load data to $container['data'].
$settings['data_directory'] = '../data/';

// Create Slim\App instance using configuration.
$app = new \Slim\App(['settings' => $settings]);

// Slim\App creates a \Psr\Container\ContainerInterface \Pimple\Container on construction.
// Retrieve the container and register required providers.
// LoggerProvider must be available first because DataProvider uses it.
$container = $app->getContainer(); // Retrieve dependency injection container to register services.
$container->register(new \recipe\app\LoggerProvider($container)); // Make logging available from $container['logger'].
$container->register(new \recipe\app\DataProvider($container)); // Make data available from $container['data'].

// Create Slim\App URI endpoints for REST API.
$app->get('/ingredients', \recipe\app\Router::class . ':getIngredients');
$app->get('/recipes', \recipe\app\Router::class . ':getRecipes');
$app->get('/lunch', \recipe\app\Router::class . ':getLunch');

// Start serving the Slim\App.
$app->run();

