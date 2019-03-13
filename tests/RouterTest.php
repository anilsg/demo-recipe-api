<?php

use \Mockery\Adapter\Phpunit\MockeryTestCase;

/**
* Test the recipe\app\Router route ingredients, recipes and lunch end points can be accessed.
* DataProvider is tested separately so dummy data is used here to isolate the tests.
*/
class RouterTest extends \Mockery\Adapter\Phpunit\MockeryTestCase
{
    /**
    * Test a REST GET on ingredients URI in app\Router.php.
    */
    public function testGetIngredients()
    {
        $logger = \Mockery::mock('recipe\app\LoggerProvider'); // Mock the LoggerProvider.
        $logger->shouldReceive('addInfo')->once()->andReturnNull(); // Expect a single call to log the GET request.

        $json = '[{"title":"Ham","best-before":"2019-03-09","use-by":"2019-03-14"},{"title":"Cheese","best-before":"2019-03-09","use-by":"2019-03-14"},{"title":"Bread","best-before":"2019-03-09","use-by":"2019-03-14"},{"title":"Butter","best-before":"2019-03-09","use-by":"2019-03-14"},{"title":"Bacon","best-before":"2019-03-04","use-by":"2019-03-09"},{"title":"Eggs","best-before":"2019-03-04","use-by":"2019-03-09"},{"title":"Mushrooms","best-before":"2019-02-22","use-by":"2019-02-25"},{"title":"Sausage","best-before":"2019-03-04","use-by":"2019-03-09"},{"title":"Hotdog Bun","best-before":"2019-02-22","use-by":"2019-03-09"},{"title":"Ketchup","best-before":"2019-03-09","use-by":"2019-03-14"},{"title":"Mustard","best-before":"2019-03-09","use-by":"2019-03-14"},{"title":"Lettuce","best-before":"2019-03-04","use-by":"2019-03-09"},{"title":"Tomato","best-before":"2019-03-04","use-by":"2019-03-09"},{"title":"Cucumber","best-before":"2019-03-04","use-by":"2019-03-09"},{"title":"Beetroot","best-before":"2019-03-04","use-by":"2019-03-09"},{"title":"Salad Dressing","best-before":"2019-02-22","use-by":"2019-02-25"}]';
        $ingredients = json_decode($json); // Artificially create the test data, not testing DataProvider.

        $container = ['data'=>['ingredients'=>$ingredients], 'logger'=>$logger ]; // Configure data and logger.
        $container = new \Slim\Container($container); // Converts array to \Slim\Container.
        $router = new \recipe\app\Router($container); // Not testing \Slim\App testing \recipe\app\Router.
        $environment = \Slim\Http\Environment::mock([ // Mock a GET /ingredients request.
            'REQUEST_METHOD' => 'GET',
            'REQUEST_URI' => '/ingredients',
            'QUERY_STRING'=>'']
        );
        $request = \Slim\Http\Request::createFromEnvironment($environment);
        $response = new \Slim\Http\Response();
        $response = $router->getIngredients($request, $response, []);
        $this->assertSame($json, (string)$response->getBody());
    }

    /**
    * Test a REST GET on recipes URI in app\Router.php.
    */
    public function testGetRecipes()
    {
        $logger = \Mockery::mock('recipe\app\LoggerProvider'); // Mock the LoggerProvider.
        $logger->shouldReceive('addInfo')->once()->andReturnNull(); // Expect a single call to log the GET request.

        $json = '[{"title":"Ham and Cheese Toastie","ingredients":["Ham","Cheese","Bread","Butter"]},{"title":"Fry-up","ingredients":["Bacon","Eggs","Baked Beans","Mushrooms","Sausage","Bread"]},{"title":"Salad","ingredients":["Lettuce","Tomato","Cucumber","Beetroot","Salad Dressing"]},{"title":"Hotdog","ingredients":["Hotdog Bun","Sausage","Ketchup","Mustard"]},{"title":"Omelette","ingredients":["Eggs","Mushrooms","Milk","Salt","Pepper","Spinach"]}]';
        $recipes = json_decode($json); // Artificially create the test data, not testing DataProvider.

        $container = ['data'=>['recipes'=>$recipes], 'logger'=>$logger ]; // Configure data and logger.
        $container = new \Slim\Container($container); // Includes array in new \Slim\Container.
        $router = new \recipe\app\Router($container); // Not testing \Slim\App testing \recipe\app\Router.
        $environment = \Slim\Http\Environment::mock([ // Mock a GET /recipes request.
            'REQUEST_METHOD' => 'GET',
            'REQUEST_URI' => '/recipes',
            'QUERY_STRING'=>'']
        );
        $request = \Slim\Http\Request::createFromEnvironment($environment);
        $response = new \Slim\Http\Response();
        $response = $router->getRecipes($request, $response, []);
        $this->assertSame($json, (string)$response->getBody());
    }

    /**
    * Test a REST GET on the lunch URI in app\Router.php.
    */
    public function testGetLunch()
    {
        $logger = \Mockery::mock('recipe\app\LoggerProvider'); // Mock the LoggerProvider.
        $logger->shouldReceive('addInfo')->once()->andReturnNull(); // Expect a single call to log the GET request.

        $container = ['data'=>['ingredients'=>[], 'recipes'=>[]], 'logger'=>$logger]; // Configure minimal data and logger.
        $container = new \Slim\Container($container);
        $router = new \recipe\app\Router($container); // Not testing \Slim\App testing \recipe\app\Router.
        $environment = \Slim\Http\Environment::mock([ // Mock a GET /recipes request.
            'REQUEST_METHOD' => 'GET',
            'REQUEST_URI' => '/lunch',
            'QUERY_STRING'=>'']
        );
        $request = \Slim\Http\Request::createFromEnvironment($environment);
        $response = new \Slim\Http\Response();
        $response = $router->getLunch($request, $response, []);
        $this->assertSame('[]', (string)$response->getBody());
    }

    /**
    * Test a REST GET on the lunch URI specifying a today's date.
    */
    public function testGetLunchWithToday()
    {
        $logger = \Mockery::mock('recipe\app\LoggerProvider'); // Mock the LoggerProvider.
        $logger->shouldReceive('addInfo')->once()->andReturnNull(); // Expect a single call to log the GET request.

        $container = ['data'=>['ingredients'=>[], 'recipes'=>[]], 'logger'=>$logger]; // Configure minimal data and logger.
        $container = new \Slim\Container($container);
        $router = new \recipe\app\Router($container); // Not testing \Slim\App testing \recipe\app\Router.
        $environment = \Slim\Http\Environment::mock([ // Mock a GET /recipes request.
            'REQUEST_METHOD' => 'GET',
            'REQUEST_URI' => '/lunch/2019-03-04',
            'QUERY_STRING'=>'']
        );
        $request = \Slim\Http\Request::createFromEnvironment($environment);
        $response = new \Slim\Http\Response();
        $response = $router->getLunch($request, $response, ['today'=>'2019-03-04']);
        $this->assertSame('[]', (string)$response->getBody());
    }
}

