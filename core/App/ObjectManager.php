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
 * Class ObjectManager
 *
 * @category Core
 * @package Infy\Core\App
 * @author <maksimglaz@gmail.com>
 */
class ObjectManager
{
    /**
     * Singleton ObjectManager object
     *
     * @var ObjectManager
     */
    private static $instance;

    /**
     * @var DIContainer
     */
    protected $_di;

    /**
     * ObjectManager constructor.
     * @param DIContainer $di
     */
    public function __construct(DIContainer $di)
    {
        $this->_di = $di;
    }

    /**
     * Get singleton ObjectManager
     *
     * @access public
     * @return ObjectManager|object
     */
    public static function getSingleton()
    {
        if (!isset(self::$instance)) {
            $di = new DIContainer();
            self::$instance = new self($di);
            $di->setObjectManager(self::$instance);
        }
        return self::$instance;
    }

    /**
     * Get class object
     *
     * @access public
     * @param $className
     * @return mixed
     */
    public function getObject($className)
    {
        try {
            if (class_exists($className)) {
                return $this->_di->getDIObject($className);
            } else {
                throw new InfyException(
                    'CLASS_DOES_NOT_EXISTS',
                    InfyException::ERROR_TYPE_CRITICAL,
                    ['class_name' => $className]
                );
            }
        } catch (InfyException $e) {
            $e->getErrorMessage();
        }
    }

    /**
     * Remove object
     *
     * @access public
     * @param $object
     * @return void
     * @throws InfyException
     */
    public function removeObject(&$object)
    {
        if (is_object($object)) {
            $this->_di->removeDIObject($object);
        } else {
            throw new InfyException(
                'NOT_OBJECT',
                InfyException::ERROR_TYPE_CRITICAL,
                ['var' => $object]
            );
        }
    }
}