<?php

use \Mockery\Adapter\Phpunit\MockeryTestCase;

// use \Pimple\Container as Container;

/**
* Test the recipe\app\Router route ingredients, recipes and lunch end points can be accessed.
* This work under construction currently using dummy data.
*/
class RouterTest extends \Mockery\Adapter\Phpunit\MockeryTestCase
{
    /**
    * Test a REST GET on ingredients URI in app\Router.php.
    */
    public function testGetIngredients()
    {
        $json = '{"ingredients":[{"title":"Ham","best-before":"2019-03-09","use-by":"2019-03-14"},{"title":"Cheese","best-before":"2019-03-09","use-by":"2019-03-14"},{"title":"Bread","best-before":"2019-03-09","use-by":"2019-03-14"},{"title":"Butter","best-before":"2019-03-09","use-by":"2019-03-14"},{"title":"Bacon","best-before":"2019-03-04","use-by":"2019-03-09"},{"title":"Eggs","best-before":"2019-03-04","use-by":"2019-03-09"},{"title":"Mushrooms","best-before":"2019-02-22","use-by":"2019-02-25"},{"title":"Sausage","best-before":"2019-03-04","use-by":"2019-03-09"},{"title":"Hotdog Bun","best-before":"2019-02-22","use-by":"2019-03-09"},{"title":"Ketchup","best-before":"2019-03-09","use-by":"2019-03-14"},{"title":"Mustard","best-before":"2019-03-09","use-by":"2019-03-14"},{"title":"Lettuce","best-before":"2019-03-04","use-by":"2019-03-09"},{"title":"Tomato","best-before":"2019-03-04","use-by":"2019-03-09"},{"title":"Cucumber","best-before":"2019-03-04","use-by":"2019-03-09"},{"title":"Beetroot","best-before":"2019-03-04","use-by":"2019-03-09"},{"title":"Salad Dressing","best-before":"2019-02-22","use-by":"2019-02-25"}]}';
        $ingredients = json_decode($json);
        $settings = []; // Settings not yet used in testing.
        $container = new \Slim\Container($settings); // \Pimple\Container not yet in use.
        $router = new \recipe\app\Router($container); // Not testing \Slim\App testing \recipe\app\Router.
        $environment = \Slim\Http\Environment::mock([ // Mock a GET /ingredients request.
            'REQUEST_METHOD' => 'GET',
            'REQUEST_URI' => '/ingredients',
            'QUERY_STRING'=>'']
        );
        $request = \Slim\Http\Request::createFromEnvironment($environment);
        $response = new \Slim\Http\Response();
        $response = $router->getIngredients($request, $response, []);
        $this->assertSame((string)$response->getBody(), $json);
    }

    /**
    * Test a REST GET on recipes URI in app\Router.php.
    */
    public function testGetRecipes()
    {
        $json = '{"recipes":[{"title":"Ham and Cheese Toastie","ingredients":["Ham","Cheese","Bread","Butter"]},{"title":"Fry-up","ingredients":["Bacon","Eggs","Baked Beans","Mushrooms","Sausage","Bread"]},{"title":"Salad","ingredients":["Lettuce","Tomato","Cucumber","Beetroot","Salad Dressing"]},{"title":"Hotdog","ingredients":["Hotdog Bun","Sausage","Ketchup","Mustard"]},{"title":"Omelette","ingredients":["Eggs","Mushrooms","Milk","Salt","Pepper","Spinach"]}]}';
        $recipes = json_decode($json);
        $settings = []; // Settings not yet used in testing.
        $container = new \Slim\Container($settings); // \Pimple\Container not yet in use.
        $router = new \recipe\app\Router($container); // Not testing \Slim\App testing \recipe\app\Router.
        $environment = \Slim\Http\Environment::mock([ // Mock a GET /recipes request.
            'REQUEST_METHOD' => 'GET',
            'REQUEST_URI' => '/recipes',
            'QUERY_STRING'=>'']
        );
        $request = \Slim\Http\Request::createFromEnvironment($environment);
        $response = new \Slim\Http\Response();
        $response = $router->getRecipes($request, $response, []);
        $this->assertSame((string)$response->getBody(), $json);
    }
}

