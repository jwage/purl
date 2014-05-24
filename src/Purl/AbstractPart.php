<?php

/*
 * This file is part of the Purl package, a project by Jonathan H. Wage.
 *
 * (c) 2013 Jonathan H. Wage
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Purl;

/**
 * AbstractPart class is implemented by each part of a Url where necessary.
 *
 * @author      Jonathan H. Wage <jonwage@gmail.com>
 * @implements ArrayAccess
 */
abstract class AbstractPart implements \ArrayAccess
{
    /**
     * Flag for whether or not this part has been initialized.
     *
     * @var boolean
     */
    protected $initialized = false;

    /**
     * Array of data for this part.
     *
     * @var array
     */
    protected $data = array();

    /**
     * Array mapping part names to classes.
     *
     * @var array
     */
    protected $partClassMap = array();

    /**
     * Gets the data for this part. This method will initialize the part if it is not already initialized.
     *
     * @return array
     */
    public function getData()
    {
        $this->initialize();
        return $this->data;
    }

    /**
     * Sets the data for this part. This method will initialize the part if it is not already initialized.
     *
     * @param array $data
     */
    public function setData(array $data)
    {
        $this->initialize();
        $this->data = $data;
    }

    /**
     * Check if this part has been initialized yet.
     *
     * @return boolean
     */
    public function isInitialized()
    {
        return $this->initialized;
    }

    /**
     * Check if this part has data by key.
     *
     * @param string $key
     * @return boolean
     */
    public function has($key)
    {
        $this->initialize();
        return isset($this->data[$key]);
    }

    /**
     * Gets data from this part by key.
     *
     * @param string $key
     * @return boolean
     */
    public function get($key)
    {
        $this->initialize();
        return isset($this->data[$key]) ? $this->data[$key] : null;
    }

    /**
     * Set data for this part by key.
     *
     * @param string $key
     * @param mixed $value
     * @return self
     */
    public function set($key, $value)
    {
        $this->initialize();
        $this->data[$key] = $value;

        return $this;
    }

    /**
     * Add data for this part.
     *
     * @param mixed $value
     */
    public function add($value)
    {
        $this->initialize();
        $this->data[] = $value;

        return $this;
    }

    /**
     * Remove data from this part by key.
     */
    public function remove($key)
    {
        $this->initialize();
        unset($this->data[$key]);
    }

    /** Property Overloading */

    public function __isset($key)
    {
        return $this->has($key);
    }

    public function __get($key)
    {
        return $this->get($key);
    }

    public function __set($key, $value)
    {
        return $this->set($key, $value);
    }

    public function __unset($key)
    {
        return $this->remove($key);
    }

    /** ArrayAccess */

    public function offsetExists($key)
    {
        $this->initialize();
        return isset($this->data[$key]);
    }

    public function offsetGet($key)
    {
        return $this->get($key);
    }

    public function offsetSet($key, $value)
    {
        return $this->set($key, $value);
    }

    public function offsetUnset($key)
    {
        return $this->remove($key);
    }

    protected function initialize()
    {
        if ($this->initialized === true) {
            return;
        }

        $this->initialized = true;

        $this->doInitialize();
    }

    /**
     * Prepare a part value.
     *
     * @param string $key
     * @param string|AbstractPart $value
     * @return AbstractPart $part
     */
    protected function preparePartValue($key, $value)
    {
        if (!isset($this->partClassMap[$key])) {
            return $value;
        }

        $className = $this->partClassMap[$key];

        return !$value instanceof $className ? new $className($value) : $value;
    }

    /**
     * Convert the instance back in to string form from the internal parts.
     *
     * @return string
     */
    abstract public function __toString();

    /**
     * Each part that extends AbstractPart must implement an doInitialize() method.
     *
     * @return void
     */
    abstract protected function doInitialize();
}
