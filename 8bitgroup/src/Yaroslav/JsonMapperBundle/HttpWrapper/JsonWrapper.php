<?php

namespace Yaroslav\JsonMapperBundle\HttpWrapper;

use Yaroslav\JsonMapperBundle\HttpWrapper\Exception\JsonWrapperException;
use Yaroslav\JsonMapperBundle\HttpWrapper\Exception\CurlWrapperException;

/**
 * Json Wrapper extend CurlWrapper and can check and decode json
 */
class JsonWrapper extends CurlWrapper {

    const DECODE_ASSOC = true;
    const DECODE_OBJECT = false;

    /**
     *
     * @param type $url
     */
    public function __construct()
    {
        parent::__construct();
    }
    
    /**
     * 
     * @param string $url
     * @param string $method
     * @param array $requestParams
     * @return json
     * @throws CurlWrapperException
     */
    public function request($url, $method = 'GET', $requestParams = null) {
        $data = parent::request($url, $method, $requestParams);
        if (!$data) {
            throw new CurlWrapperException(
            sprintf('Empty data are returned on get "%s"', $url));
        }
        return $this->decode($data);
    }

    /**
     * Decodes a JSON string.
     *
     * @param string  $json    The json string.
     * @param boolean $assoc   When TRUE, returned objects will be converted into associative arrays.
     * @param integer $depth   User specified recursion depth.
     * @param integer $options Bitmask of JSON decode options.
     *
     * @return mixed
     *
     * @throws JsonWrapperException
     */
    protected function decode($json, $assoc = self::DECODE_ASSOC, $depth = 512, $options = 0)
    {
        $result = json_decode($json, $assoc, $depth, $options);
        $error = json_last_error();
        if ($error != JSON_ERROR_NONE) {
            throw new JsonWrapperException($error);
        }
        return $result;
    }

}
