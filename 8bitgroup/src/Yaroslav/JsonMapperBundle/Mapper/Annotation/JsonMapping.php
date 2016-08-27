<?php

namespace Yaroslav\JsonMapperBundle\Mapper;


use InvalidArgumentException;

/**
 * Annotation class for @JsonMapping().
 *
 * @Annotation
 * @Target({"PROPERTY", "METHOD"})
 *
 */
class JsonMapping
{
    /**
     * @var array
     */
    private $type;

    /**
     * @param array $data
     *
     * @throws InvalidArgumentException
     */
    public function __construct(array $data)
    {
        if (!isset($data['type']) || !$data['type']) {
            throw new InvalidArgumentException(sprintf('Parameter "type" of annotation "%s" cannot be empty.', get_class($this)));
        }

        $this->type = $data['type'];
    }

    /**
     * Gets groups.
     *
     * @return array
     */
    public function getType()
    {
        return  $this->type;
    }
    
    
    private function isSimpleType($type) {
        return in_array($this->getType(), ['string', 'boolean', 'bool', 'integer', 'int', 'float', 'array', 'object']);
    }
}