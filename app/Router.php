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
        $ingredients = $this->container['data']['ingredients'];
        return $response->withJson($ingredients);
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
        // $json = '[{"title":"Ham and Cheese Toastie","ingredients":["Ham","Cheese","Bread","Butter"]},{"title":"Fry-up","ingredients":["Bacon","Eggs","Baked Beans","Mushrooms","Sausage","Bread"]},{"title":"Salad","ingredients":["Lettuce","Tomato","Cucumber","Beetroot","Salad Dressing"]},{"title":"Hotdog","ingredients":["Hotdog Bun","Sausage","Ketchup","Mustard"]},{"title":"Omelette","ingredients":["Eggs","Mushrooms","Milk","Salt","Pepper","Spinach"]}]';
        // $recipes = json_decode($json);
        $recipes = $this->container['data']['recipes']; // Get data loaded by DataProvider.
        return $response->withJson($recipes);
    }

    /**
    * API endpoint to call recipe\app\Lunch and return the specified lunch recipes JSON.
    * @param \Psr\Http\Message\ServerRequestInterface
    * @param \Psr\Http\Message\ResponseInterface
    * @param array
    * @return \Psr\Http\Message\ResponseInterface
    */
    public function getLunch(Request $request, Response $response, $args = [])
    {
        // construction in progress
        return $response->getBody()->write('<h1 style="color:red">Lunch Time!</h1>');
    }

}
