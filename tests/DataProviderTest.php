<?php

use \PHPUnit\Framework\TestCase;
use \Mockery\Adapter\Phpunit\MockeryTestCase;

/**
* Test the recipe\app\DataProvider loads and presents data from JSON files correctly.
*/
class DataProviderTest extends \Mockery\Adapter\Phpunit\MockeryTestCase
{
    /**
    * Test the recipe\app\DataProvider loads and presents data from JSON files correctly.
    * Based on known JSON data files in the data directory.
    */
    public function testDataProvider()
    {
        $container = [ 'settings'=>[ 'data_directory'=>'data/' ] ]; // Create data_directory settings.
        $container = new \Slim\Container($container); // Create new \Slim\Container as \Slim\App() does.
        $provider = new \recipe\app\DataProvider(); // Instance of DataProvider to test.
        $provider->register($container); // Find, load and decode the JSON data.
        $expects = [
        'ingredients'=>[['title'=>'Ham','best-before'=>'2019-03-09','use-by'=>'2019-03-14'],['title'=>'Cheese','best-before'=>'2019-03-09','use-by'=>'2019-03-14'],['title'=>'Bread','best-before'=>'2019-03-09','use-by'=>'2019-03-14'],['title'=>'Butter','best-before'=>'2019-03-09','use-by'=>'2019-03-14'],['title'=>'Bacon','best-before'=>'2019-03-04','use-by'=>'2019-03-09'],['title'=>'Eggs','best-before'=>'2019-03-04','use-by'=>'2019-03-09'],['title'=>'Mushrooms','best-before'=>'2019-02-22','use-by'=>'2019-02-25'],['title'=>'Sausage','best-before'=>'2019-03-04','use-by'=>'2019-03-09'],['title'=>'Hotdog Bun','best-before'=>'2019-02-22','use-by'=>'2019-03-09'],['title'=>'Ketchup','best-before'=>'2019-03-09','use-by'=>'2019-03-14'],['title'=>'Mustard','best-before'=>'2019-03-09','use-by'=>'2019-03-14'],['title'=>'Lettuce','best-before'=>'2019-03-04','use-by'=>'2019-03-09'],['title'=>'Tomato','best-before'=>'2019-03-04','use-by'=>'2019-03-09'],['title'=>'Cucumber','best-before'=>'2019-03-04','use-by'=>'2019-03-09'],['title'=>'Beetroot','best-before'=>'2019-03-04','use-by'=>'2019-03-09'],['title'=>'Salad Dressing','best-before'=>'2019-02-22','use-by'=>'2019-02-25']],
        'recipes'=>[['title'=>'Ham and Cheese Toastie','ingredients'=>['Ham','Cheese','Bread','Butter']],['title'=>'Fry-up','ingredients'=>['Bacon','Eggs','Baked Beans','Mushrooms','Sausage','Bread']],['title'=>'Salad','ingredients'=>['Lettuce','Tomato','Cucumber','Beetroot','Salad Dressing']],['title'=>'Hotdog','ingredients'=>['Hotdog Bun','Sausage','Ketchup','Mustard']],['title'=>'Omelette','ingredients'=>['Eggs','Mushrooms','Milk','Salt','Pepper','Spinach']]]
        ];
        $this->assertSame($expects, $container['data']);
    }

    /**
    * Test the recipe\app\DataProvider handles missing or no data correctly.
    * Uses alternative data-none directory that must be available with no JSON files present.
    * Additional mocks logger that is triggered when no data condition detected.
    */
    public function testNoData()
    {
        $logger = \Mockery::mock('recipe\app\LoggerProvider'); // Mock LoggerProvider.
        $logger->shouldReceive('addWarning')->once()->andReturnNull(); // One call to log warning expected.

        $container = [ 'settings'=>[ 'data_directory'=>'data-none/' ], 'logger'=>$logger ]; // Pass in empty directory.
        $container = new \Slim\Container($container); // Create new \Slim\Container as \Slim\App() does.
        $provider = new \recipe\app\DataProvider(); // Instance of DataProvider to test.
        $provider->register($container); // Confirm DataProvider executes safely with no data.
        $this->assertSame(['ingredients'=>[], 'recipes'=>[]], $container['data']);
    }
}
