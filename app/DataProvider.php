<?php
namespace recipe\app;

use \Pimple\Container;
use \Pimple\ServiceProviderInterface;

/**
* Data service to load data into DI container so classes have access to data.
* Loads JSON files from 'data_directory' specified in container at registration.
* Loads all files with '.json' extension from data_directory discarding filenames.
* Other files are ignored and can contain junk or versioned data.
* Top level object keys in JSON files used as primary key for internal store.
* A list of objects is expected below each top level key.
* Multiple files can specify same object key and listed values will be appended.
* Data is presented in the container as PHP object at 'data' key.
* Update not supported because data format inadequate without persistent unique IDs.
*
* $container['settings']['data_directory'] relative directory to load JSON files from
* $container['settings']['data'] PHP object containing collated data
*
* @package    recipe
* @subpackage app
* @version    $Id:$
* @author     anilsg
* @link       https://github.com/anilsg/demo-recipe-api
*/
class DataProvider implements ServiceProviderInterface
{
    /**
    * Load data from JSON sources and present in the container.
    * Data presented as nested array.
    * Keys 'ingredients' and 'recipes' are always guaranteed to exist.
    *
    * $container['settings']['data_directory'] relative directory to load JSON files from
    * $container['settings']['data'] PHP object containing collated data
    *
    * @param \Pimple\Container
    *
    * TODO: Check for missing data_directory and handle read and syntax exceptions etc.
    * TODO: Could provider a higher level reliable interface such as DataProvider->getIngredients().
    */
    public function register(Container $container)
    {
        $data = array(); // Target data storage.
        $data_directory = $container['settings']['data_directory']; // JSON source location.
        $file_list = array_diff(scandir($data_directory), array('.', '..')); // Exhaustive file list.
        foreach ($file_list as $file) { // Load all matching files sequentially.
            if (substr_compare($file, '.json', -strlen('.json')) === 0) { // Ignore non '.json' files.
                // Per file JSON decode and load. /////////
                $object = json_decode(file_get_contents($data_directory.$file), true);
                foreach ($object as $data_type=>$data_list) { // One or more top-level keys supported.
                    foreach ($data_list as $data_item) { // Top level keys provide a list of items.
                        $data[$data_type][] = $data_item; // E.g. $data['ingredients'][] = { key=>value }.
                    }
                }
                // End per file JSON decode and load. /////
            }
        }
        // Check for missing data.
        if (!$data and $container->has('logger')) { // Check for no data and warn.
            $container['logger']->addWarning('No data loaded', array('data_directory'=>$data_directory, 'getcwd'=>getcwd()));
        }
        if (!array_key_exists('ingredients', $data)) { // Protect users against missing key.
            $data['ingredients'] = []; // Provide default empty list.
        }
        if (!array_key_exists('recipes', $data)) { // Protect users against missing key.
            $data['recipes'] = []; // Provide default empty list.
        }
        $container['data'] = $data; // Make data available in container.
    }
}

