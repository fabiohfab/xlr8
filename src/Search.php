<?php

namespace Henriqes\XLR8;

use Henriqes\XLR8\Models\Hotel;

class Search
{
    public static function getNearbyHotels(float $latitude, float $longitude, String $orderBy = 'proximity')
    {
        $order = str_starts_with($orderBy, '-') ? 'desc' : 'asc';
        $orderBy = str_starts_with($orderBy, '-') ? substr($orderBy, 1) : $orderBy;

        $hotels = self::loadSources($latitude, $longitude);

        switch($order) {
            case 'asc':
                $hotels = $hotels->sortBy(function ($key) use ($orderBy) {
                    return $key->toArray()[$orderBy];
                });
                break;
            case 'desc':
                $hotels = $hotels->sortByDesc(function ($key) use ($orderBy) {
                    return $key->toArray()[$orderBy];
                });
                break;
        }
       
        return $hotels->values()->map(function($i) {
            return $i->toString();
        })->toArray();
    }

    

    private static function getSources()
    {
        $files = array();
        $iterator = new \FilesystemIterator("./XLR8/sources");
        foreach($iterator as $entry) {
            if ($entry->isDir()) {
                continue;
            }
            
            $extension = $entry->getExtension();

            if($extension != 'json') {
                continue;
            }

            $files[] = './XLR8/sources/'.$entry->getFilename();
        }

  
        return $files;
    }

    private static function loadSources(float $latitude, float $longitude)
    {
        $sources = [];
        foreach (self::getSources() as $file) {
            $data = file_get_contents($file);

            $source = self::parseSource(json_decode($data, true)['message'], $latitude, $longitude);

            $sources = array_merge($sources, $source);
        }

        return collect($sources);
    }

    private static function parseSource(array $items, float $latitude, float $longitude)
    {
        $parsed = [];

        foreach($items as $item) {
            $distance = distance(
                $latitude, $longitude,
                (float) $item[1], (float) $item[2]
            );
            
            if(count($item) == 4) {
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