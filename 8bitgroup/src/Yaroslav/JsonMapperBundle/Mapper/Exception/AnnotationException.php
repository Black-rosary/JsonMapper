<?php

namespace Yaroslav\JsonMapperBundle\Mapping\Exception\Exception;

use Exception;

/**
 * DefaultWrapperException Exceptions class
 */
class AnnotationException extends Exception {

    /**
     * 
     * @param string $message
     * @param int $code
     * @param Exception $previous
     */
    public function __construct($message = "", $code = 0, Exception $previous = null) {
        parent::__construct($message, $code, $previous);
    }

}