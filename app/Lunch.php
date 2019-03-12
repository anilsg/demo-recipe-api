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

    /**
    * @param array ingredients
    * @param array recipes
    */
    public function __construct(array $recipes, array $ingredients)
    {
        $this->recipes = $recipes;
        $this->ingredients = $ingredients;
    }

    /**
    * Return filtered recipe list based on criteria applied to ingredients.
    * @param ingredients
    * @param recipes
    * @param array
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
