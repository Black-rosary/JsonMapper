<?php

namespace Yaroslav\JsonMapperBundle\Tests\Resources\Adapter;


use Yaroslav\JsonMapperBundle\Adapter\AdapterFindInterface;

/**
 * Class is some implementation of a concrete protocol
 */
class DummyAdapter implements AdapterFindInterface {
    
    public function find($url, array $requestParams = array()) {
        return ['data'=> [
            "locations" => [
                ["name" => "Eiffel Tower",
                "coordinates" => [
                   "lat" => 21.12,
                   "long" => 19.56
                ]],
                ["name" => "House",
                "coordinates" => [
                   "lat" => 0.12,
                   "long" => 0.56
                ]],
            ],
        ], 'status' => true];
    }
    
}