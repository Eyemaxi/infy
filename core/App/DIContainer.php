<?php
/**
 * Infy Framework
 *
 * @author    <maksimglaz@gmail.com>
 * @category  Core
 * @package   Infy\Core
 * @copyright Copyright (c) 2018 Infy
 * @license   https://www.infy-team.com/license.txt
 */

namespace Infy\Core\App;

use Infy\Core\App\Exception\InfyException;

/**
 * Class DIContainer
 *
 * @category Core
 * @package Infy\Core\App
 * @author <maksimglaz@gmail.com>
 */
final class DIContainer
{
    const WITHOUT_ARGUMENTS = 'non_args';
    const WITH_ARGUMENTS = 'args';

    /**
     * List of all DI classes
     *
     * @var array
     */
    private $collectionObjects;

    /**
     * Get dependency injection object
     *
     * @access public
     * @param $className
     * @return bool|object
     * @throws InfyException
     */
    public function getDIObject($className)
    {
        if (isset($this->collectionObjects[$className])) {
            return $this->collectionObjects[$className];
        } else {
            return $this->createDIObject($className);
        }
    }

    /**
     * Remove object
     *
     * @access public
     * @param $object
     * @return void
     */
    public function removeDIObject(&$object)
    {
        $className = get_class($object);
        if (isset($this->collectionObjects[$className])) {
            unset($object);
            unset($this->collectionObjects[$className]);
        } else {
            try {
                throw new InfyException(
                    'OBJECT_DOES_NOT_EXISTS_IN_DIC',
                    InfyException::ERROR_TYPE_WARNING,
                    ['object_class' => $object]
                );
            } catch (InfyException $e) {
                $e->getErrorMessage();
            }

        }

    }

    /**
     * Set ObjectManager class object to the collection objects
     *
     * @access public
     * @param ObjectManager $object
     * @return void
     */
    public function setObjectManager(ObjectManager & $object)
    {
        $className = get_class($object);
        $this->collectionObjects[$className] = $object;
    }

    /**
     * Set object to the collection objects
     *
     * @access private
     * @param $item
     * @param string $type
     * @param \ReflectionClass|null $reflector
     * @return void
     */
    private function setObject($item, $type = self::WITHOUT_ARGUMENTS, \ReflectionClass $reflector = null)
    {
        switch ($type) {
            case self::WITHOUT_ARGUMENTS:
                $this->collectionObjects[$item] = new $item;
                break;
            case self::WITH_ARGUMENTS:
                $className = $reflector->name;
                $this->collectionObjects[$className] = $reflector->newInstanceArgs($item);
                break;
        }
    }

    /**
     * Build an instance of the given class
     *
     * @access private
     * @param $className
     * @return bool|object
     * @throws InfyException
     */
    private function createDIObject($className)
    {
        try {
            $reflector = new \ReflectionClass($className);
        } catch (\ReflectionException $e) {
            throw new InfyException(
                'CLASS_DOES_NOT_EXISTS',
                InfyException::ERROR_TYPE_CRITICAL,
                ['class_name' => $className]
            );
        }


        if (!$reflector->isInstantiable()) {
            throw new InfyException(
                'IS_NOT_INSTANTIABLE',
                InfyException::ERROR_TYPE_CRITICAL,
                ['class_name' => $className]
            );
        }

        $constructor = $reflector->getConstructor();

        if (is_null($constructor)) {
            $this->setObject($className);
            return $this->collectionObjects[$className];
        }

        $parameters = $constructor->getParameters();
        $dependencies = $this->getDependencies($parameters);
        $this->setObject($dependencies, self::WITH_ARGUMENTS, $reflector);
        return $this->collectionObjects[$className];
    }

    /**
     * Build up a list of dependencies for a given methods parameters
     *
     * @access private
     * @param array $parameters
     * @return array
     * @throws InfyException
     */
    private function getDependencies($parameters)
    {
        $dependencies = array();

        foreach ($parameters as $parameter) {
            $dependency = $parameter->getClass();

            if (is_null($dependency)) {
                $dependencies[] = $this->getNonClass($parameter);
            } else {
                $dependencies[] = $this->getDIObject($dependency->name);
            }
        }

        return $dependencies;
    }

    /**
     * Determine what to do with a non-class value
     *
     * @access private
     * @param \ReflectionParameter $parameter
     * @return mixed
     */
    private function getNonClass(\ReflectionParameter $parameter)
    {
        if ($parameter->isDefaultValueAvailable()) {
            return $parameter->getDefaultValue();
        }
    }
}