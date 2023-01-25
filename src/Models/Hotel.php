<?php

namespace Henriques\XLR8\Models;

/**
 * Hotel
 */
class Hotel
{

    protected $name;
    protected $latitude;
    protected $longitude;
    protected $proximity;
    protected $pricepernight;
    
    /**
     * __construct
     *
     * @param  mixed $entity
     * @return void
     */
    public function __construct(array $entity = null)
    {
        $this->name = $entity["name"];
        $this->latitude = $entity["latitude"];
        $this->longitude = $entity["longitude"];
        $this->proximity = $entity["proximity"];
        $this->pricepernight = $entity["pricepernight"];
    }
    
    /**
     * name
     *
     * @return String
     */
    public function name() : String
    {
        return $this->name;
    }
    
    /**
     * latitude
     *
     * @return float
     */
    public function latitude() : float
    {
        return $this->latitude;
    }    

    /**
     * longitude
     *
     * @return float
     */
    public function longitude() : float
    {
        return $this->longitude;
    }
        
    /**
     * proximity
     *
     * @param  mixed $decimalPlaces
     * @return float
     */
    public function proximity(int $decimalPlaces = 0) : float
    {
        return round($this->proximity, $decimalPlaces);
    }

    public function pricepernight() : float
    {
        return $this->pricepernight;
    }
    
    /**
     * toArray
     *
     * @return array
     */
    public function toArray() : array
    {
        return [
           "name" => $this->name(),
           "latitude" => $this->latitude(),
           "longitude" => $this->longitude(),
           "proximity" => $this->proximity(),
           "pricepernight" => $this->pricepernight(),
        ];
    }
    
    /**
     * toString
     *
     * @return String
     */
    public function toString() : String
    {
        return $this->name().', '.$this->proximity(2).' Km, '.$this->pricepernight().'€';
    }
    
    /**
     * __toString
     *
     * @return String
     */
    public function __toString() : String
    {
        return $this->toString();
    }
    
}
