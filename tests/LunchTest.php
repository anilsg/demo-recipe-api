<?php

use \Mockery\Adapter\Phpunit\MockeryTestCase;

/**
* Test the recipe\app\Lunch class for correct processing and filterning of results.
* Use-By and Best-Before dates are both treated as expired if the given date is reached (is today).
*/
class LunchTest extends \Mockery\Adapter\Phpunit\MockeryTestCase
{
    /**
    * testLunchList: Test based on no use-by or best-before.
    * GIVEN that I am an API client AND have made a GET request to the /lunch endpoint
    * THEN I should receive a JSON response of the recipes that I can prepare
    * based on the availability of the ingredients in my fridge 
    */
    public function testLunchList()
    {
        $ingredients = json_decode('[{"title":"Ham"},{"title":"Cheese"},{"title":"Bread"},{"title":"Butter"},{"title":"Bacon"},{"title":"Eggs"},{"title":"Mushrooms"},{"title":"Sausage"},{"title":"Hotdog Bun"},{"title":"Ketchup"},{"title":"Mustard"},{"title":"Lettuce"},{"title":"Tomato"},{"title":"Cucumber"},{"title":"Beetroot"},{"title":"Salad Dressing"}]', true);
        $recipes = json_decode('[{"title":"Ham and Cheese Toastie","ingredients":["Ham","Cheese","Bread","Butter"]},{"title":"Fry-up","ingredients":["Bacon","Eggs","Baked Beans","Mushrooms","Sausage","Bread"]},{"title":"Salad","ingredients":["Lettuce","Tomato","Cucumber","Beetroot","Salad Dressing"]},{"title":"Hotdog","ingredients":["Hotdog Bun","Sausage","Ketchup","Mustard"]},{"title":"Omelette","ingredients":["Eggs","Mushrooms","Milk","Salt","Pepper","Spinach"]}]', true);
        $expected = json_decode('[{"title":"Ham and Cheese Toastie","ingredients":["Ham","Cheese","Bread","Butter"]},{"title":"Salad","ingredients":["Lettuce","Tomato","Cucumber","Beetroot","Salad Dressing"]},{"title":"Hotdog","ingredients":["Hotdog Bun","Sausage","Ketchup","Mustard"]}]', true);
        $lunch = (new \recipe\app\Lunch($ingredients, $recipes))(); // Create Lunch object and invoke.
        $this->assertSame($expected, (array)$lunch);
    }

    /**
    * testLunchExcludeUseBy: Test based on use-by but not best-before.
    * GIVEN that I am an API client AND I have made a GET request to the /lunch endpoint
    * AND an ingredient is past its use-by date
    * THEN I should not receive any recipes containing this ingredient 
    *
    * 2019-03-14: "Ham and Cheese Toastie"
    * 2019-03-09: "Ham and Cheese Toastie", "Hotdog"
    * 2019-02-25: "Ham and Cheese Toastie", "Hotdog", "Salad"
    *
    * Only use-by dates are retained in the test data, so the Lunch algorithm will ignore effects of best-before dates.
    * Given the above dates those particular recipes will still be before their use-by dates.
    */
    public function testLunchExcludeUseBy()
    {
        $ingredients = json_decode('[{"title":"Ham","use-by":"2019-03-14"},{"title":"Cheese","use-by":"2019-03-14"},{"title":"Bread","use-by":"2019-03-14"},{"title":"Butter","use-by":"2019-03-14"},{"title":"Bacon","use-by":"2019-03-09"},{"title":"Eggs","use-by":"2019-03-09"},{"title":"Mushrooms","use-by":"2019-02-25"},{"title":"Sausage","use-by":"2019-03-09"},{"title":"Hotdog Bun","use-by":"2019-03-09"},{"title":"Ketchup","use-by":"2019-03-14"},{"title":"Mustard","use-by":"2019-03-14"},{"title":"Lettuce","use-by":"2019-03-09"},{"title":"Tomato","use-by":"2019-03-09"},{"title":"Cucumber","use-by":"2019-03-09"},{"title":"Beetroot","use-by":"2019-03-09"},{"title":"Salad Dressing","use-by":"2019-02-25"}]');
        $recipes = json_decode('[{"title":"Ham and Cheese Toastie","ingredients":["Ham","Cheese","Bread","Butter"]},{"title":"Fry-up","ingredients":["Bacon","Eggs","Baked Beans","Mushrooms","Sausage","Bread"]},{"title":"Salad","ingredients":["Lettuce","Tomato","Cucumber","Beetroot","Salad Dressing"]},{"title":"Hotdog","ingredients":["Hotdog Bun","Sausage","Ketchup","Mustard"]},{"title":"Omelette","ingredients":["Eggs","Mushrooms","Milk","Salt","Pepper","Spinach"]}]');
        $expected = json_decode('[{"title":"Ham and Cheese Toastie","ingredients":["Ham","Cheese","Bread","Butter"]}]');
        $lunch = new \recipe\app\Lunch($ingredients, $recipes, '2019-03-13'); // Create Lunch object for test date.
        $this->assertSame($expected, (array)$lunch()); // Invoke Lunch object for results.
    }

    /**
    * testLunchSortBestBefore: Test based on use-by and best-before.
    * GIVEN that I am an API client AND I have made a GET request to the /lunch endpoint
    * AND an ingredient is past its best-before date AND is still within its use-by date
    * THEN any recipe containing this ingredient should be sorted to the bottom of the JSON response object 
    *
    * Before 2019-03-14: "Ham and Cheese Toastie"
    * Before 2019-03-09: "Ham and Cheese Toastie", "Hotdog" (Hotdog last)
    * Before 2019-03-04: "Ham and Cheese Toastie", "Hotdog" (Hotdog last)
    * Before 2019-02-25: "Ham and Cheese Toastie", "Hotdog", "Salad" (Hotdog then Salad last)
    */
    public function testLunchSortBestBefore()
    {
        $ingredients = json_decode('[{"title":"Ham","best-before":"2019-03-09","use-by":"2019-03-14"},{"title":"Cheese","best-before":"2019-03-09","use-by":"2019-03-14"},{"title":"Bread","best-before":"2019-03-09","use-by":"2019-03-14"},{"title":"Butter","best-before":"2019-03-09","use-by":"2019-03-14"},{"title":"Bacon","best-before":"2019-03-04","use-by":"2019-03-09"},{"title":"Eggs","best-before":"2019-03-04","use-by":"2019-03-09"},{"title":"Mushrooms","best-before":"2019-02-22","use-by":"2019-02-25"},{"title":"Sausage","best-before":"2019-03-04","use-by":"2019-03-09"},{"title":"Hotdog Bun","best-before":"2019-02-22","use-by":"2019-03-09"},{"title":"Ketchup","best-before":"2019-03-09","use-by":"2019-03-14"},{"title":"Mustard","best-before":"2019-03-09","use-by":"2019-03-14"},{"title":"Lettuce","best-before":"2019-03-04","use-by":"2019-03-09"},{"title":"Tomato","best-before":"2019-03-04","use-by":"2019-03-09"},{"title":"Cucumber","best-before":"2019-03-04","use-by":"2019-03-09"},{"title":"Beetroot","best-before":"2019-03-04","use-by":"2019-03-09"},{"title":"Salad Dressing","best-before":"2019-02-22","use-by":"2019-02-25"}]');
        $recipes = json_decode('[{"title":"Ham and Cheese Toastie","ingredients":["Ham","Cheese","Bread","Butter"]},{"title":"Fry-up","ingredients":["Bacon","Eggs","Baked Beans","Mushrooms","Sausage","Bread"]},{"title":"Salad","ingredients":["Lettuce","Tomato","Cucumber","Beetroot","Salad Dressing"]},{"title":"Hotdog","ingredients":["Hotdog Bun","Sausage","Ketchup","Mustard"]},{"title":"Omelette","ingredients":["Eggs","Mushrooms","Milk","Salt","Pepper","Spinach"]}]');
        $expected = json_decode('[{"title":"Ham and Cheese Toastie","ingredients":["Ham","Cheese","Bread","Butter"]},{"title":"Hotdog","ingredients":["Hotdog Bun","Sausage","Ketchup","Mustard"]}]');
        $lunch = new \recipe\app\Lunch($ingredients, $recipes, '2019-03-03'); // Create Lunch object.
        $this->assertSame($expected, (array)$lunch()); // Invoke Lunch object for results.
    }

    /**
    * testLunchNoRecipes: Test based on use-by and best-before when all ingredients have expired.
    * GIVEN that I am an API client AND I have made a GET request to the /lunch endpoint
    * AND all ingredients are past their use-by date
    * THEN I should not receive any recipes
    */
    public function testLunchNoRecipes()
    {
        $ingredients = json_decode('[{"title":"Ham","best-before":"2019-03-09","use-by":"2019-03-14"},{"title":"Cheese","best-before":"2019-03-09","use-by":"2019-03-14"},{"title":"Bread","best-before":"2019-03-09","use-by":"2019-03-14"},{"title":"Butter","best-before":"2019-03-09","use-by":"2019-03-14"},{"title":"Bacon","best-before":"2019-03-04","use-by":"2019-03-09"},{"title":"Eggs","best-before":"2019-03-04","use-by":"2019-03-09"},{"title":"Mushrooms","best-before":"2019-02-22","use-by":"2019-02-25"},{"title":"Sausage","best-before":"2019-03-04","use-by":"2019-03-09"},{"title":"Hotdog Bun","best-before":"2019-02-22","use-by":"2019-03-09"},{"title":"Ketchup","best-before":"2019-03-09","use-by":"2019-03-14"},{"title":"Mustard","best-before":"2019-03-09","use-by":"2019-03-14"},{"title":"Lettuce","best-before":"2019-03-04","use-by":"2019-03-09"},{"title":"Tomato","best-before":"2019-03-04","use-by":"2019-03-09"},{"title":"Cucumber","best-before":"2019-03-04","use-by":"2019-03-09"},{"title":"Beetroot","best-before":"2019-03-04","use-by":"2019-03-09"},{"title":"Salad Dressing","best-before":"2019-02-22","use-by":"2019-02-25"}]');
        $recipes = json_decode('[{"title":"Ham and Cheese Toastie","ingredients":["Ham","Cheese","Bread","Butter"]},{"title":"Fry-up","ingredients":["Bacon","Eggs","Baked Beans","Mushrooms","Sausage","Bread"]},{"title":"Salad","ingredients":["Lettuce","Tomato","Cucumber","Beetroot","Salad Dressing"]},{"title":"Hotdog","ingredients":["Hotdog Bun","Sausage","Ketchup","Mustard"]},{"title":"Omelette","ingredients":["Eggs","Mushrooms","Milk","Salt","Pepper","Spinach"]}]');
        $expected = json_decode('[]'); // Don't expect any recipes.
        $lunch = new \recipe\app\Lunch($ingredients, $recipes, '2019-03-14'); // Create Lunch object.
        $this->assertSame($expected, (array)$lunch()); // Invoke Lunch object for results.
    }
}

