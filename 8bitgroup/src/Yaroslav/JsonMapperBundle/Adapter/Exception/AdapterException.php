<?php

namespace Yaroslav\JsonMapperBundle\Adapter\Exception;

use Exception;

/**
 * DefaultWrapperException Exceptions class
 */
class AdapterException extends Exception {

    /**
     * 
     * @param string $message
     * @param integer $code
     * @param Exception $previous
     */
    public function __construct($message = "", $code = 0, Exception $previous = null) {
        parent::__construct($message, $code, $previous);
    }

}