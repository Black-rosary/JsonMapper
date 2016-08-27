<?php

namespace Yaroslav\JsonMapperBundle\HttpWrapper;

interface WrapperInterface {
    
    /**
     * Retrun array data for mapping
     * 
     * 
     * return $mappedObjects
     */
    public function find($url, array $requestParams = []);
}

