<?php

namespace Henriques\XLR8;

use Henriques\XLR8\Models\Hotel;

class Search
{    
    /**
     * getNearbyHotels
     *
     * @param  mixed $latitude
     * @param  mixed $longitude
     * @param  mixed $sortBy
     * @return array
     */
    public static function getNearbyHotels(float $latitude, float $longitude, String $sortBy = 'proximity') : array
    {
        // Get order by and order direction
        $sortDirection = str_starts_with($sortBy, '-') ? -1 : 1;
        $sortBy = str_starts_with($sortBy, '-') ? substr($sortBy, 1) : $sortBy;

        // Load sources
        $hotels = self::loadSources($latitude, $longitude);

        // Sort hotels
        usort($hotels, function($a, $b) use ($sortDirection, $sortBy) { 
            $compareValue = $a->toArray()[$sortBy] <=> $b->toArray()[$sortBy];
            return $sortDirection * $compareValue; 
        });
       
        // Return the sorted array
        return array_map(function($i) {
            return $i->toString();
        }, $hotels);
    }
    
    /**
     * getSources
     *
     * @return array
     */
    private static function getSources() : array
    {
        $files = array();

        // Find valid sources in sources folder
        $iterator = new \FilesystemIterator(dirname(__FILE__).'/sources');
        foreach($iterator as $entry) {

            // Ignore sub directories
            if ($entry->isDir()) {
                continue;
            }
            
            // Only json files will be considered
            $extension = $entry->getExtension();
            if($extension != 'json') {
                continue;
            }

            $files[] = dirname(__FILE__).'/sources/'.$entry->getFilename();
        }

  
        return $files;
    }
    
    /**
     * loadSources
     *
     * @param  mixed $latitude
     * @param  mixed $longitude
     * @return Hotel[]
     */
    private static function loadSources(float $latitude, float $longitude) : array
    {
        $sources = [];

        // Get valid sources
        foreach (self::getSources() as $file) {

            // Parse each file
            $data = file_get_contents($file);
            
            $source = self::parseSource(json_decode($data, true)['message'], $latitude, $longitude);

            $sources = array_merge($sources, $source);
        }

        return $sources;
    }
    
    /**
     * parseSource
     *
     * @param  mixed $items
     * @param  mixed $latitude
     * @param  mixed $longitude
     * @return Hotel[]
     */
    private static function parseSource(array $items, float $latitude, float $longitude) : array
    {
        $parsed = [];

        // Iterate over each item and create a Hotel instance
        foreach($items as $item) {
            // Only items with exactly four characteristics will be considered
            // 1 - Hotel name
            // 2 - latitude
            // 3 - longitude
            // 4 - price per night
            if(count($item) == 4) {
                 // Caluclate the distance between the hotel and the given coordinates
                $distance = distance(
                    $latitude, $longitude,
                    (float) $item[1], (float) $item[2]
                );

                $item = new Hotel([
                    'name' => $item[0],
                    'latitude' => (float) $item[1],
                    'longitude' => (float) $item[2],
                    'proximity' => $distance,
                    'pricepernight' => (float) $item[3],
                ]);
                
                array_push($parsed, $item);
            }
        }

        return $parsed;
    }
}