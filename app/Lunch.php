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
    protected $ingredients; // E.g. ['Ham'=>{'title':'Ham','best-before':'2019-03-09','use-by':'2019-03-14'}, 'Cheese'=>{ ...
    protected $recipes; // Array of recipes as list of arrays (unchanged as supplied to constructor).
    protected $today; // 'Y-m-d' of date in effect for sort comparisons e.g. '2019-03-09'.

    /**
    * Accepts ingredients and recipes and optional date for comparisons which defaults to today.
    * Rebuilds ingredients list omitting expired ingredients and re-indexing with title as keys.
    * Incoming: [ 1=>{'title':'Ham','best-before':'2019-03-09','use-by':'2019-03-14'}, 2=>{ ...
    * Outgoing: [ 'Ham'=>{'title':'Ham','best-before':'2019-03-09','use-by':'2019-03-14'}, 'Cheese'=>{ ...
    * @param array ingredients supplied as [ {"title":"Ham","best-before":"2019-03-09","use-by":"2019-03-14"}, {"title":"Cheese", ...
    * @param array recipes supplied as [ {"title":"Fry-up","ingredients":["Bacon", ... ]}, {"title":"Salad","ingredients":["Lettuce", ...
    * @param string ISO date for evaluation 'YYYY-MM-DD'
    */
    public function __construct(array $ingredients, array $recipes, $today = null)
    {
        $this->today = $today === null ? date('Y-m-d') : $today; // Over-ride today's date if provided.
        $this->recipes = $recipes; // Array of arrays with 'title' and 'ingredients' sub keys, unchanged.
        $this->ingredients = []; // Re-indexed filtered array of ingredients using ingredient titles as keys.

        foreach ($ingredients as $key => $ingredient) { // $ingredient has keys 'title', 'best-before', 'use-by'.
            if (array_key_exists('use-by', $ingredient) and $ingredient['use-by'] <= $today) { // Discard expired ingredients.
                continue; // String comparison of ISO dates works and is quick and simple.
            }
            else { // Include this ingredient in available list.
                $this->ingredients[$ingredient['title']] = $ingredient; // Index on ingredient title.
            }
        }
    }

    /**
    * Return filtered recipe list based on criteria applied to ingredients.
    * Note array_filter is unsuitable because it preserves keys which are arbitrary anyway.
    * @return array
    */
    public function __invoke()
    {
        $recipes = []; // Start new filtered recipes array.

        // Remove recipes without available ingredients.
        foreach ($this->recipes as $key => $recipe) { // $recipe = {"title":"Fry-up","ingredients":["Bacon", ... ]}
            foreach ($recipe['ingredients'] as $ingredient) { // Check each ingredient in the recipe.
                if (!array_key_exists($ingredient, $this->ingredients)) { // Discard recipe if any ingredient missing.
                    continue 2; // Continue with the next recipe.
                } // End if.
            } // End foreach ingredient.
            $recipes[] = $recipe; // All ingredients present then add recipe to the filtered list.
        } // End foreach recipe.

        return $recipes; // Return reduced list of recipes.
    }
}
