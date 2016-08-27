<?php

namespace Yaroslav\JsonMapperBundle\Mapper\Loader;

use Doctrine\Common\Annotations\Reader;
use Yaroslav\JsonMapperBundle\Mapper\Annotation\JsonMapping;


class AnnotationLoader {

    /**
     * @var Reader
     */
    private $reader;
    
    /**
     * @var array
     */
    private $loadedClasses;

    /**
     * @param Reader $reader
     */
    public function __construct(Reader $reader) {
        $this->reader = $reader;
    }

    public function isMapperAnnotation($annotation) {
        return $annotation instanceof JsonMapping;
    }

    /**
     *
     * @param \ReflectionProperty $property
     * @return null|JsonMapping
     */
    protected function getPropertyAnnotation(\ReflectionProperty $property) {
        foreach ($this->reader->getPropertyAnnotations($property) as $annotation) {
            if ($this->isMapperAnnotation($annotation)) {
                return $annotation;
            }
        }
    }

    /**
     *
     * @param \ReflectionMethod $method
     * @return null|JsonMapping
     */
    protected function getMethodAnnotation(\ReflectionMethod $method) {
        foreach ($this->reader->getMethodAnnotations($method) as $annotation) {
            if ($this->isMapperAnnotation($annotation)) {
                if (preg_match('/^set(.+)$/i', $method->name, $matches)) {
                    return $annotation;
                } else {
                    throw new AnnotationException('The JsonMapping is allow '
                            . 'on setters method only');
                }
            }
        }
    }

    /**
     * 
     * @param string $className
     * @return array
     */
    public function loadAnnotations($className) {
        $reflectionClass = new \ReflectionClass($className);

        $annotations = [];

        $filterProperty = \ReflectionProperty::IS_PUBLIC | \ReflectionProperty::IS_PROTECTED;
        foreach ($reflectionClass->getProperties($filterProperty) as $property) {
            $propertyAnnotaion = $this->getPropertyAnnotation($property);
            if ($propertyAnnotaion) {
                $annotations[$property->name] = $propertyAnnotaion;
            }
        }

        $filterMethod = \ReflectionMethod::IS_PUBLIC | \ReflectionMethod::IS_PROTECTED | \ReflectionMethod::IS_FINAL;
        foreach ($reflectionClass->getMethods($filterMethod) as $method) {
            $methodAnnotaion = $this->getMethodAnnotation($method);
            if ($methodAnnotaion) {
                $annotations[$property->name] = $methodAnnotaion;
            }
        }

        return $annotations;
    }

    public function getMapperAnnotationsFor($value) {
        $class = $this->getClass($value);
        if (!$class) {
            throw new InvalidArgumentException(sprintf('Cannot create metadata for non-objects. Got: "%s"', gettype($value)));
        }

        if (isset($this->loadedClasses[$class])) {
            return $this->loadedClasses[$class];
        }

        if (!class_exists($class) && !interface_exists($class)) {
            throw new InvalidArgumentException(sprintf('The class or interface "%s" does not exist.', $class));
        }

        $annotations = $this->loadAnnotations($class);
        
        return $this->loadedClasses[$class] = $annotations;
    }

    public function hasMetadataFor($value) {
        $class = $this->getClass($value);

        return class_exists($class) || interface_exists($class);
    }

    /**
     * Gets a class name for a given class or instance.
     *
     * @param mixed $value
     *
     * @return string|bool
     */
    private function getClass($value) {
        if (!is_object($value) && !is_string($value)) {
            return false;
        }

        return ltrim(is_object($value) ? get_class($value) : $value, '\\');
    }

}
