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
$app = new \Slim\App(['settings' => $settings]);

// Create Slim\App URI endpoints for REST API.
$app->get('/ingredients', \recipe\app\App::class . ':getIngredients');
$app->get('/recipes', \recipe\app\App::class . ':getRecipes');
$app->get('/lunch', \recipe\app\App::class . ':getLunch');

$app->run();

