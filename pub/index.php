<?php
namespace recipe\pub;

require '../vendor/autoload.php';

$settings['displayErrorDetails'] = true;
$settings['addContentLengthHeader'] = false;
$app = new \Slim\App(['settings' => $settings]);

$app->get('/hello/{name}', \recipe\app\App::class);

$app->run();

