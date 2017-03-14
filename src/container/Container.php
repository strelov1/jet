<?php declare(strict_types=1);

namespace jet\container;

use jet\container\exception\ContainerException;
use jet\container\exception\ServiceNotFoundException;

class Container implements \Psr\Container\ContainerInterface
{
    private $dependency;
    private $store;

    /**
     * Init configuration.
     * @param array $config
     */
    public function __construct(array $config = [])
    {
        $this->dependency = $config;
        $this->store = [];
    }

    /**
     * Add dependency
     * @param $name
     * @param $callback
     */
    public function set($name, $callback = null)
    {
        if (!$callback) {
            $this->dependency[$name] = $name;
        } else {
            $this->dependency[$name] = $callback;
        }
    }

    /**
     * @param string $id
     * @return bool
     */
    public function has($id)
    {
        return isset($this->dependency[$id]) || in_array($id, $this->dependency, true);
    }

    /**
     * @param string $id
     * @return object
     * @throws ServiceNotFoundException | ContainerException
     */
    public function get($id)
    {
        if (!$this->has($id)) {
            throw new ServiceNotFoundException("Service not found: $id");
        }

        if ($this->existInitDependency($id)) {
            return $this->getInitDependency($id);
        }

        $dependencyClass = $this->getDependency($id);

        if ($this->existConstructor($dependencyClass)) {
            $initClass = $this->initConstructor($dependencyClass);
            $this->addStore($id, $initClass);
            return $initClass;
        }
        $initClass = new $dependencyClass();
        $this->addStore($id, $initClass);
        return $initClass;
    }

    protected function existInitDependency($name)
    {
        return isset($this->store[$name]);
    }

    protected function getInitDependency($name)
    {
        return $this->store[$name];
    }

    protected function addStore($id, $object)
    {
        $this->store[$id] = $object;
    }

    /**
     * @param $name
     * @return mixed
     */
    protected function getDependency($name)
    {
        if (isset($this->dependency[$name])) {
            return $this->dependency[$name];
        }
        if (in_array($name, $this->dependency, true)) {
            return $name;
        }
    }

    /**
     * @param $class
     * @return bool
     */
    protected function existConstructor($class)
    {
        return method_exists($class, '__construct');
    }

    /**
     * @param $dependencyClass
     * @return object
     * @throws ContainerException | ServiceNotFoundException
     */
    protected function initConstructor($dependencyClass)
    {
        $refMethod = new \ReflectionMethod($dependencyClass, '__construct');
        $params = $refMethod->getParameters();
        $args = [];
        foreach ($params as $param) {
            if ($param->isDefaultValueAvailable()) {
                $args[$param->name] = $param->getDefaultValue();
            } else {
                $class = $param->getClass();
                if ($class !== null) {
                    $initClass = $this->get($class->name);
                    $this->addStore($class->name, $initClass);
                    $args[$param->name] = $initClass;
                } else {
                    throw new ContainerException("Not found {$class->name} in container");
                }
            }
        }
        $refClass = new \ReflectionClass($dependencyClass);
        $initClass = $refClass->newInstanceArgs((array)$args);
        $this->addStore($dependencyClass, $initClass);
        return $initClass;
    }

}