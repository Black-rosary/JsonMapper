<?php

namespace Yaroslav\JsonMapperBundle\HttpWrapper\Exception;

/**
 * Curl Exceptions class
 */
class CurlException extends \Exception {

    /**
     * 
     * @param resource $curlHandler
     * @param \Exception $previous
     */
    public function __construct($curlHandler, \Exception $previous = null) {
        $message = curl_error($curlHandler);
        $code = curl_errno($curlHandler);
        parent::__construct($message, $code, $previous);
    }
}