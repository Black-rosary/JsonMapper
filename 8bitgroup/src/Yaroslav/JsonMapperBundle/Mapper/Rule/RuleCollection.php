<?php

namespace Yaroslav\JsonMapperBundle\Mapper\Rule;


class RuleCollection {
    
    protected $rules;    
    
    public function addRule(SampleRule $rule) {
        $this->rules[$rule->getName()] = $rule;
    }

    public function hasRule($name) {
        return isset($this->rules[$name]);
    }
    
    public function removeRule(SampleRule $rule) {
        if ($this->hasRule($rule->getName)) { 
            unset($this->rules[$rule->getNmae()]);
        }    
    }
    
    public function getRules() {
        return $this->rules;
    }
    
    public function setRules(array $rules) {
        $this->rules = $rules;
    }

    public function getRule($name) {
        if ($this->hasRule($name)) { 
            return $this->rules[$name];
        }    
    }
    
}