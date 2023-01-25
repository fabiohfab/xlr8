# Install
``composer require henriques/xlr8``

# Usage
    use Henriques\XLR8\Search

    Search::getNearbyHotels(float $latitude, float $longitude, String $sortBy = 'proximity')

- The method getNearbyHotels return all hotels
- The parameter $sortBy can be one of the follows:
    - proximity
    - -proximity
    - pricepernight
    - -pricepernight
- By default the value of $sortBy is 'proximity'