<?php

namespace Samdark\Hydrator;

use Samdark\Hydrator\Strategy\StrategyInterface;

class Hydrator {

    private $map;

    private $strategies = [];

    /**
     * Local cache of reflection class instances
     * @var array
     */
    private $reflectionClassMap = [];

    public function __construct(array $map)
    {
        $this->map = $map;
    }

    /**
     * @param $dataKey
     * @param StrategyInterface $strategy
     */
    public function addStrategy($dataKey, StrategyInterface $strategy)
    {
        $this->strategies[$dataKey] = $strategy;
    }

    /**
     * Creates an instance of a class filled with data according to map
     *
     * @param array $data
     * @param string $className
     * @return object
     *
     * @since 1.0.2
     */
    public function hydrate($data, $className)
    {
        $reflection = $this->getReflectionClass($className);
        $object = $reflection->newInstanceWithoutConstructor();

        foreach ($this->map as $dataKey => $propertyName) {
            if (!$reflection->hasProperty($propertyName)) {
                throw new \InvalidArgumentException("There's no $propertyName property in $className.");
            }

            if (isset($data[$dataKey])) {
                $value = $this->hydrateValue($dataKey, $data[$dataKey]);
                $property = $reflection->getProperty($propertyName);
                $property->setAccessible(true);
                $property->setValue($object, $value);
            }
        }

        return $object;
    }

    /**
     * Extract data from field
     *
     * @param $dataKey
     * @param $data
     * @return mixed
     */
    protected function hydrateValue($dataKey, $data)
    {
        if (!isset($this->strategies[$dataKey])) {
            return $data;
        }
        /** @var StrategyInterface $strategy */
        return $this->strategies[$dataKey]->hydrate($data);
    }

    /**
     * Fills an object passed with data according to map
     *
     * @param array $data
     * @param object $object
     * @return object
     *
     * @since 1.0.2
     */
    public function hydrateInto($data, $object)
    {
        $className = get_class($object);
        $reflection = $this->getReflectionClass($className);

        foreach ($this->map as $dataKey => $propertyName) {
            if (!$reflection->hasProperty($propertyName)) {
                throw new \InvalidArgumentException("There's no $propertyName property in $className.");
            }

            if (isset($data[$dataKey])) {
                $property = $reflection->getProperty($propertyName);
                $property->setAccessible(true);
                $property->setValue($object, $data[$dataKey]);
            }
        }

        return $object;
    }

    /**
     * Extracts data from an object according to map
     *
     * @param object $object
     * @return array
     */
    public function extract($object)
    {
        $data = [];

        $className = get_class($object);
        $reflection = $this->getReflectionClass($className);

        foreach ($this->map as $dataKey => $propertyName) {
            if ($reflection->hasProperty($propertyName)) {
                $property = $reflection->getProperty($propertyName);
                $property->setAccessible(true);
                $value = $property->getValue($object);
                $data[$dataKey] = $this->extractValue($dataKey, $value);
            }
        }

        return $data;
    }

    /**
     * Extract data from on field
     *
     * @param $dataKey
     * @param $value
     * @return mixed
     */
    protected function extractValue($dataKey, $value)
    {
        if (!isset($this->strategies[$dataKey])) {
            return $value;
        }
        return $this->strategies[$dataKey]->extract($value);
    }

    /**
     * Returns instance of reflection class for class name passed
     *
     * @param string $className
     * @return \ReflectionClass
     * @throws \ReflectionException
     */
    protected function getReflectionClass($className)
    {
        if (!isset($this->reflectionClassMap[$className])) {
            $this->reflectionClassMap[$className] = new \ReflectionClass($className);
        }
        return $this->reflectionClassMap[$className];
    }
}