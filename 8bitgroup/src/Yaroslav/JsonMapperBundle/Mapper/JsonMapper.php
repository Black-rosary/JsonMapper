<?php

namespace Yaroslav\JsonMapperBundle\Mapper;

use Yaroslav\JsonMapperBundle\Adapter\AdapterFindInterface;
use Yaroslav\JsonMapperBundle\Mapper\Rule\RuleCollection;
use Yaroslav\JsonMapperBundle\Mapper\Rule\SampleRule;
use Yaroslav\JsonMapperBundle\Mapper\Exception\JsonMapperException;


class JsonMapper extends RuleCollection {

    protected $url;
    protected $wrapper = null;
    protected $mapClass = null;
    protected $mapping = array();

    /**
     * Constructor of megaservice
     * 
     * @param string $url  - URL of source service 
     * @param type $mapClass - root mapped class
     * @param type $mapping - config data determinate of rules of mapping
     * @param AdapterFindInterface $wrapper - curl wrapper for access to data.
     */
    public function __construct($url, $mapClass, $mapping, AdapterFindInterface $wrapper) {
        $this->url = $url;
        $this->mapClass = $mapClass;
        $this->buildRules($mapping, $this);
        $this->wrapper = $wrapper;
    }

    /**
     * Build config mapper rules. 
     * 
     * @param type $mapping
     * @param type $root
     * @return SampleRule
     */
    protected function buildRules($mapping, $root = null) {
        $rules = [];
        foreach ($mapping as $key => $value) {
            $rule = new SampleRule($key, $value['type']);
            if (isset($value['mapping'])) {
                $rule->setRules($this->buildRules($value['mapping'], $rule));
            }
            $rules[$key] = $rule;
        }
        $root->setRules($rules);
        return $rules;
    }

    /**
     * Request and map all data. Return instances of $mapClass
     * 
     * @param array $params
     * @return mixed
     */
    public function findAll($params = []) {
        $data = $this->wrapper->find($this->url, $params);
        if (!is_array($data) || !$data) {
            throw  new JsonMapperException('Not found data for mapping');
        }
        return $this->map($data['data'], $this->mapClass, $this);
    }
    
    /**
     * Create object from class string 
     * @param type $class
     * @return \Yaroslav\JsonMapperBundle\Mapper\className
     */
    private function getObject($class) {
        $className = '\\' . str_replace('/', '\\', $class);
        return new $className();
    }            

    /**
     * The map method. Understand simple types, classes and array of class
     * 
     * @param array $jsonData
     * @param string $class
     * @param RulesCollection $rules
     * @return type
     */
    protected function map(array $jsonData, $class, $rules) {
        $object = $this->getObject($class);
        foreach ($jsonData as $key => $value) {
            if ($rules->hasRule($key)) {
                $rule = $rules->getRule($key);
                $this->setValue($object, $key, $value, $rule);
            }
        }
        return $object;
    }

    /**
     * Cast and return json value 
     * 
     * @param SampleRule $rule
     * @param mixed $value
     * @return mixed
     * @throws JsonMapperException
     */
    protected function getTypedValue(SampleRule $rule, $value) {
        if ($rule->isSimpleType()) {
            settype($value, $rule->getType());
            return $value;
        } elseif ($rule->isArrayOfClass()) {
            if (!is_array($value)) {
                throw new JsonMapperException(
                sprintf('The field %s is not array', $rule->getName()));
            }
            $array = [];
            $arrayObjectType = rtrim($rule->getType(), '[]');
            foreach ($value as $index => $arrayObject) {
                $array[$index] = $this->map($arrayObject, $arrayObjectType, $rule);
            }
            return $array;
        } else {
            return $this->map($value, $rule->getType(), $rule);
        }
    }

    /**
     * Set value to object. It is find setter and invoke him. 
     * Raise exception if can't find setter.
     * 
     * @param object $object
     * @param string $key
     * @param mixed $value
     * @param SampleRule $rule
     * @throws JsonMapperException
     */
    protected function setValue($object, $key, $value, SampleRule $rule) {
        $setter = $this->getSetter($key);
        if (method_exists($object, $setter)) {
            $typedValue = $this->getTypedValue($rule, $value);
            $object->$setter($typedValue);
        } else {
            throw new JsonMapperException(sprintf("Setter for %s not found", $key));
        }
    }

    
    /**
     * 
     * 
     * @param type $property
     * @return type
     */
    private function getSetter($property) {
        return 'set' . $this->classify($property);
    }

    /**
     * 
     * @param type $value
     * @return type
     */
    private function classify($value) {
        return str_replace(' ', '', ucwords(strtr($value, '_-', '  ')));
    }

}
