<?php

namespace Yaroslav\JsonMapperBundle\HttpWrapper\Exception;

/**
 * CurlWrapper Exceptions class
 */
class CurlWrapperException extends Exception {

    public function __construct($message = "", $code = 0, Exception $previous = null) {
        parent::__construct($message, $code, $previous);
    }

}
