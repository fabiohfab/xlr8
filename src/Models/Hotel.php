<?php

namespace Henriqes\XLR8\Models;

class Hotel
{

    protected $name;
    protected $latitude;
    protected $longitude;
    protected $proximity;
    protected $pricepernight;

    public function __construct(array $entity = null)
    {
        $this->name = $entity["name"];
        $this->latitude = $entity["latitude"];
        $this->longitude = $entity["longitude"];
        $this->proximity = $entity["proximity"];
        $this->pricepernight = $entity["pricepernight"];
    }

    public function name()
    {
        return $this->name;
    }

    public function latitude()
    {
        return $this->latitude;
    }
    public function longitude()
    {
        return $this->longitude;
    }
    public function proximity($decimalPlaces = 0)
    {
        return round($this->proximity, $decimalPlaces);
    }
    public function pricepernight()
    {
        return $this->pricepernight;
    }


    public function get()
    {
        return 'teste';
    }

    public function toArray()
    {
        return [
           "name" => $this->name(),
           "latitude" => $this->latitude(),
           "longitude" => $this->longitude(),
           "proximity" => $this->proximity(),
           "pricepernight" => $this->pricepernight(),
        ];
    }

    public function toString()
    {
        return $this->name().', '.$this->proximity(2).' Km, '.$this->pricepernight().'€';
    }

    public function __toString()
    {
        return $this->toString();
    }
    
}
