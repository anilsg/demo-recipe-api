<?php
namespace recipe\app;

/**
* Implements the Lunch processing and filtering logic.
* Used as an invokable class.
* Provide data to the constructor and then just call the object.
* @package    recipe
* @subpackage app
* @version    $Id:$
* @author     anilsg
* @link       https://github.com/anilsg/demo-recipe-api
*/
class Lunch
{
    protected $ingredients;
    protected $recipes;
    protected $today;

    /**
    * Accepts ingredients and recipes and optional date for evaluation which defaults to today.
    * @param array ingredients
    * @param array recipes
    * @param string date for evaluation
    */
    public function __construct(array $ingredients, array $recipes, $today = null)
    {
        $this->ingredients = $ingredients; // List of ingredient descriptors.
        $this->recipes = $recipes; // List of recipe descriptors.
        $this->today = $today; // Over-ride today's date if required.
    }

    /**
    * Return filtered recipe list based on criteria applied to ingredients.
    * @return array
    */
    public function __invoke()
    {
        // $ingredients = $this->container['data']['ingredients'];
        // $recipes = $this->container['data']['recipes'];
        // $lunch = new Lunch($ingredients, $recipes);
        // return $lunch();
    }
}
