<?php
namespace samdark\hydrator;

/**
 * Hydrator can be used for two purposes:
 *
 * - To extract data from a class to be futher stored in a persistent storage.
 * - To instantiate a class having its data.
 *
 * In both cases it is saving and filling protected and private properties without calling
 * any methods which leads to ability to persist state of an object with properly incapsulated
 * data.
 */
class Hydrator
{
    /**
     * Mapping of keys in data array to property names.
     * @var array
     */
    private $map;

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

        $this->hydrateInto($data, $object);

        return $object;
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
        foreach ($this->map as $dataKey => $propertyName) {
            if (isset($data[$dataKey]))
                $this->setProperty($object, $propertyName, $data[$dataKey]);
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

        foreach ($this->map as $dataKey => $propertyName) {
            $data[$dataKey] = $this->getProperty($object, $propertyName);
        }

        return $data;
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
    
    /**
     * Set value to object property using his inheritance
     *
     * @param object $object
     * @param string $propertyName
     * @param mixed $value
     */
    private function setProperty($object, $propertyName, $value)
    {
        $className = get_class($object);
        $reflection = $this->getReflectionClass($className);

        do {
            if ($reflection->hasProperty($propertyName)) {
                $property = $reflection->getProperty($propertyName);
                $property->setAccessible(true);
                $property->setValue($object, $value);
                return ;
            }
        } while ($reflection = $reflection->getParentClass());

        throw new \InvalidArgumentException("There's no $propertyName property in $className.");
    }

    /**
     * @param object $object
     * @param string $propertyName
     * @return mixed|null
     */
    private function getProperty($object, $propertyName)
    {
        $className = get_class($object);
        $reflection = $this->getReflectionClass($className);

        do {
            if ($reflection->hasProperty($propertyName)) {
                $property = $reflection->getProperty($propertyName);
                $property->setAccessible(true);
                return $property->getValue($object);
            }
        } while ($reflection = $reflection->getParentClass());

        return null;
    }
}
