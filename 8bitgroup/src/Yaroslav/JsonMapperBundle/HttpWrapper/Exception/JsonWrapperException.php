<?php

namespace Yaroslav\JsonMapperBundle\HttpWrapper\Exception;

/**
 * CurlWrapper Exceptions class
 */
class JsonWrapperException extends \Exception {

    /**
     * Mapping of PHPs error constants to error messages.
     *
     * @var array
     */
    private static $errors = array(
        JSON_ERROR_DEPTH            => 'Maximum stack depth exceeded',
        JSON_ERROR_STATE_MISMATCH   => 'Underflow or the modes mismatch',
        JSON_ERROR_CTRL_CHAR        => 'Unexpected control character found',
        JSON_ERROR_SYNTAX           => 'Syntax error, malformed JSON',
        JSON_ERROR_UTF8             => 'Malformed UTF-8 characters, possibly incorrectly encoded',
        JSON_ERROR_RECURSION        => 'One or more recursive references in the value to be encoded',
        JSON_ERROR_INF_OR_NAN       => 'One or more NAN or INF values in the value to be encoded',
        JSON_ERROR_UNSUPPORTED_TYPE => 'A value of a type that cannot be encoded was given'
    );

     /**
     * Returns the error of the last decode() call or FALSE if there was no error.
     *
     * @return mixed Either a string containing an error message or FALSE
     *
     * @throws JsonDecodeException
     */
    private function getError($error)
    {
        if (isset(self::$errors[$error])) {
            return self::$errors[$error];
        }
        return 'Unknown error';
    }

    /**
     * @param int $json_error - JSON error code
     */
    public function __construct($json_error)
    {
        $this->message = $this->getError($json_error);
    }

}