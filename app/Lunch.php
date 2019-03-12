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

        // Available ingredients are culled on construction.
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
    * Return filtered recipe list removing recipes with ingredients not in the available list.
    * Return recipe list is sorted so that oldest best-before date recipes appear last.
    * Recipes are tagged with their worst best-before data and this is included in returned data.
    * Note array_filter is unsuitable because it preserves keys creating arbitrary staggered numeric keys.
    * Invoke by simply calling Lunch object e.g. $recipes = (new \recipe\app\Lunch($x, $y))();
    * @return array
    */
    public function __invoke()
    {
        $recipes = []; // Start new filtered recipes array.

        // Tag recipes with oldest best-before data and remove recipes without available ingredients.
        foreach ($this->recipes as $key => $recipe) { // $recipe = {"title":"Fry-up","ingredients":["Bacon", ... ]}
            foreach ($recipe['ingredients'] as $ingredient) { // Check each ingredient in the recipe.
                if (!array_key_exists($ingredient, $this->ingredients)) { // Discard recipe if any ingredient missing.
                    continue 2; // Continue with the next recipe, discarding this one.
                } elseif (!array_key_exists('best-before', $this->ingredients[$ingredient])) { // No best-before date available.
                    continue; // Keep the recipe so far, and continue to the next ingredient.
                } elseif (!array_key_exists('best-before', $recipe)) { // No best-before date recorded against this recipe yet.
                    $recipe['best-before'] = $this->ingredients[$ingredient]['best-before']; // Tag recipe with best-before date.
                    continue; // Keep this recipe and continue to the next ingredient.
                } elseif ($recipe['best-before'] > $this->ingredients[$ingredient]['best-before']) { // Compare best-before dates.
                    $recipe['best-before'] = $this->ingredients[$ingredient]['best-before']; // Oldest best-before date.
                }
            } // End foreach ingredient.
            $recipes[] = $recipe; // All ingredients present so add recipe to the filtered list.
        } // End foreach recipe.

        // Sort recipes by their oldest best-before date if available.
        usort($recipes, [$this, 'cmp']);
        return $recipes; // Return reduced list of recipes.
    }

    /**
    * Custom array sort comparison function.
    * If the first argument should be listed first return -1.
    * Violates PSR-2 to achieve better readability in this case.
    * @return integer
    */
    protected function cmp($a, $b)
    {
        if (!array_key_exists('best-before', $a)) {
            if (!array_key_exists('best-before', $b)) { return 0; }
            else { return -1; } // $a goes first.
        } else {
            if (!array_key_exists('best-before', $b)) { return 1; } // $a goes second.
            else {
                if ($a['best-before'] < $b['best-before']) { return 1; } // $a goes second.
                elseif ($a['best-before'] > $b['best-before']) { return -1; } // $a goes first.
                else { return 0; }
            }
        }
    }
}
