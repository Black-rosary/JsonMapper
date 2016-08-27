<?php

namespace Yaroslav\JsonMapperBundle\HttpWrapper;

use Yaroslav\JsonMapperBundle\HttpWrapper\Exception\DefaultWrapperException;

/**
 * 
 */
class DefaultWrapper extends JsonWrapper implements WrapperInterface {
    
     /**
     * 
     * @param type $url
     * @param array $requestParams
     */
    public function find($url, array $requestParams = []) {
        $response = $this->get($url, $requestParams);
        if (!$response['status']) {
            throw  new DefaultWrapperException($response['message']);
        }
        return $response;
    }

}