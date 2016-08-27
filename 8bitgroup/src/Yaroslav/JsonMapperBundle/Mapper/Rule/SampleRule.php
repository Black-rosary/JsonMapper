<?php

namespace Yaroslav\JsonMapperBundle\Mapper\Rule;

use Yaroslav\JsonMapperBundle\Mapper\Rule\RuleCollection;


class SampleRule extends RuleCollection {
    
    private $name = '';
    
    private $type = null;

    public function __construct($name, $type) {
        $this->name = $name;
        $this->type = $type;        
    }
    
    public function getName() {
        return $this->name;
    }

    public function getType() {
        return $this->type;
    }

    public function setName($name) {
        $this->name = $name;
        return $this;
    }

    public function setType($type) {
        $this->type = $type;
        return $this;
    }

    public function isSimpleType() {
        return in_array($this->getType(), ['string', 'boolean', 'bool', 'integer', 'int', 'float', 'array', 'object']);
    }    
    
    public function isArrayOfClass() {
        return substr($this->getType(), -2) === '[]';
    }
   
}
