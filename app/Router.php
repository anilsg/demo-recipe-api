<?php
namespace recipe\app;

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use \Psr\Container\ContainerInterface;

/**
* Implements the REST API endpoints.
* Loads data, performs logging and calls other objects providing core functionality.
* @package    recipe
* @subpackage app
* @version    $Id:$
* @author     anilsg
* @link       https://github.com/anilsg/demo-recipe-api
*/
class Router
{
    protected $container;

    /**
    * Constructor required to pass through access to the dependency injection (DI) container.
    * Arbitrary properties of the container contain settings and references to data and services.
    * @param \Psr\Container\ContainerInterface $container  Slim\App DI container
    */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
    * Simply provide the current ingredients list as JSON.
    * DataProvider will ensure 'ingredients' key is available in $container['data'].
    * At a minimum an empty list will be available.
    * @param \Psr\Http\Message\ServerRequestInterface
    * @param \Psr\Http\Message\ResponseInterface
    * @param array
    * @return \Psr\Http\Message\ResponseInterface
    */
    public function getIngredients(Request $request, Response $response, $args = [])
    {
        $ingredients = $this->container['data']['ingredients']; // Retrieve data loaded by DataProvider.
        $this->container['logger']->addInfo('GET ingredients list', array('count'=>count($ingredients))); // Log the request.
        return $response->withJson($ingredients); // Provide JSON response.
    }

    /**
    * Simply provide the current recipes list as JSON.
    * @param \Psr\Http\Message\ServerRequestInterface
    * @param \Psr\Http\Message\ResponseInterface
    * @param array
    * @return \Psr\Http\Message\ResponseInterface
    */
    public function getRecipes(Request $request, Response $response, $args = [])
    {
        $recipes = $this->container['data']['recipes']; // Get data loaded by DataProvider.
        $this->container['logger']->addInfo('GET recipes list', array('count'=>count($recipes))); // Log the request.
        return $response->withJson($recipes); // JSON encoded response.
    }

    /**
    * API endpoint to call recipe\app\Lunch and return the specified lunch recipes JSON.
    * Accepts optional date URI parameter.
    * Due to lack of time parameter if forced to conforming pattern or discarded, rather than handled with reporting.
    * @param \Psr\Http\Message\ServerRequestInterface
    * @param \Psr\Http\Message\ResponseInterface
    * @param array may contain optional ISO date for current date
    * @return \Psr\Http\Message\ResponseInterface
    */
    public function getLunch(Request $request, Response $response, $args = [])
    {
        $today = array_key_exists('today', $args) ? $args['today'] : ''; // Potential optional YYYY-MM-DD to act as today's date.
        $today = preg_replace('/[^0-9]/', '', $today); // Strip all non-numerics: '2019-04-09' => '20190409'.
        $today = strlen($today) == 8 ? substr($today, 0, 4).'-'.substr($today, 4, 2).'-'.substr($today, 6, 2) : ''; // And back.
        $lunch = new \recipe\app\Lunch($this->container['data']['ingredients'], $this->container['data']['recipes'], $today); // Load data.
        $today = $lunch->today; // Capture the today date in use.
        $lunch = $lunch(); // Invoke to sort and return data.
        $this->container['logger']->addInfo('GET lunch list', ['count'=>count($lunch), 'today'=>$today]); // Log the request.
        return $response->withJson($lunch); // Encode response as JSON.
    }

}
