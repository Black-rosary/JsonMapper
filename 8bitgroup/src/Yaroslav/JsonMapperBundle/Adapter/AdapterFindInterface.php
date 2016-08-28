<?php

namespace Yaroslav\JsonMapperBundle\Adapter;

interface AdapterFindInterface {
    
    /**
     * Retrun array data for mapping
     * 
     * 
     * return $mappedObjects
     */
    public function find($url, array $requestParams = []);
}

