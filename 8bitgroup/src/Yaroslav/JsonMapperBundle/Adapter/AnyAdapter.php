<?php

namespace Yaroslav\JsonMapperBundle\Adapter;

use Yaroslav\JsonMapperBundle\Adapter\Exception\AdapterException;
use Yaroslav\JsonMapperBundle\HttpWrapper\JsonWrapper;

/**
 * Class is some implementation of a concrete protocol
 */
class AnyAdapter extends JsonWrapper implements AdapterFindInterface {
    
     /**
     * 
     * @param type $url
     * @param array $requestParams
     */
    public function find($url, array $requestParams = []) {
        $response = $this->get($url, $requestParams);
        if (!isset($response['status'])) {
            throw  new AdapterException('Syntax error, malformed response');
        }
        if (!isset($response['data'])) {
            throw  new AdapterException('Syntax error, malformed response');
        }
        if (!$response['status']) {
            throw  new AdapterException($response['data']['message'], $response['data']['code']);
        }
        return $response;
    }

}