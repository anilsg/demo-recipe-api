<?php

use \Mockery\Adapter\Phpunit\MockeryTestCase;

/**
* Test the recipe\app\Lunch class for correct processing and filterning of results.
*
* testLunchExcludeUseBy:
* GIVEN that I am an API client AND I have made a GET request to the /lunch endpoint
* AND an ingredient is past its use-by date
* THEN I should not receive any recipes containing this ingredient 
*
* testLunchSortBestBefore:
* GIVEN that I am an API client AND I have made a GET request to the /lunch endpoint
* AND an ingredient is past its best-before date AND is still within its use-by date
* THEN any recipe containing this ingredient should be sorted to the bottom of the JSON response object 
*/
class LunchTest extends \Mockery\Adapter\Phpunit\MockeryTestCase
{
    /**
    * testLunchList:
    * GIVEN that I am an API client AND have made a GET request to the /lunch endpoint
    * THEN I should receive a JSON response of the recipes that I can prepare
    * based on the availability of the ingredients in my fridge 
    */
    public function testLunchList()
    {
        $json = '[{"title":"Ham","best-before":"2019-03-09","use-by":"2019-03-14"},{"title":"Cheese","best-before":"2019-03-09","use-by":"2019-03-14"},{"title":"Bread","best-before":"2019-03-09","use-by":"2019-03-14"},{"title":"Butter","best-before":"2019-03-09","use-by":"2019-03-14"},{"title":"Bacon","best-before":"2019-03-04","use-by":"2019-03-09"},{"title":"Eggs","best-before":"2019-03-04","use-by":"2019-03-09"},{"title":"Mushrooms","best-before":"2019-02-22","use-by":"2019-02-25"},{"title":"Sausage","best-before":"2019-03-04","use-by":"2019-03-09"},{"title":"Hotdog Bun","best-before":"2019-02-22","use-by":"2019-03-09"},{"title":"Ketchup","best-before":"2019-03-09","use-by":"2019-03-14"},{"title":"Mustard","best-before":"2019-03-09","use-by":"2019-03-14"},{"title":"Lettuce","best-before":"2019-03-04","use-by":"2019-03-09"},{"title":"Tomato","best-before":"2019-03-04","use-by":"2019-03-09"},{"title":"Cucumber","best-before":"2019-03-04","use-by":"2019-03-09"},{"title":"Beetroot","best-before":"2019-03-04","use-by":"2019-03-09"},{"title":"Salad Dressing","best-before":"2019-02-22","use-by":"2019-02-25"}]';
        $ingredients = json_decode($json); // Array of ingredients.

        $json = '[{"title":"Ham and Cheese Toastie","ingredients":["Ham","Cheese","Bread","Butter"]},{"title":"Fry-up","ingredients":["Bacon","Eggs","Baked Beans","Mushrooms","Sausage","Bread"]},{"title":"Salad","ingredients":["Lettuce","Tomato","Cucumber","Beetroot","Salad Dressing"]},{"title":"Hotdog","ingredients":["Hotdog Bun","Sausage","Ketchup","Mustard"]},{"title":"Omelette","ingredients":["Eggs","Mushrooms","Milk","Salt","Pepper","Spinach"]}]';
        $recipes = json_decode($json); // Array of recipes.

        $json = '[{"title":"Ham and Cheese Toastie","ingredients":["Ham","Cheese","Bread","Butter"]},{"title":"Fry-up","ingredients":["Bacon","Eggs","Baked Beans","Mushrooms","Sausage","Bread"]},{"title":"Salad","ingredients":["Lettuce","Tomato","Cucumber","Beetroot","Salad Dressing"]},{"title":"Hotdog","ingredients":["Hotdog Bun","Sausage","Ketchup","Mustard"]},{"title":"Omelette","ingredients":["Eggs","Mushrooms","Milk","Salt","Pepper","Spinach"]}]';
        $expected = json_decode($json); // Array of expected results.

        $lunch = new \recipe\app\Lunch($ingredients, $recipes); // Create Lunch object.
        $this->assertSame($expected, (array)$lunch()); // Invoke Lunch object for results.
    }

}

