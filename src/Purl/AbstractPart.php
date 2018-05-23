<?php

declare(strict_types=1);

namespace Purl;

use ArrayAccess;

/**
 * AbstractPart class is implemented by each part of a Url where necessary.
 *
 * @implements ArrayAccess
 */
abstract class AbstractPart implements ArrayAccess
{
    /** @var bool */
    protected $initialized = false;

    /** @var mixed[] */
    protected $data = [];

    /** @var string[] */
    protected $partClassMap = [];

    /**
     * @return mixed[]
     */
    public function getData() : array
    {
        $this->initialize();

        return $this->data;
    }

    /**
     * @param mixed[] $data
     */
    public function setData(array $data) : void
    {
        $this->initialize();

        $this->data = $data;
    }

    public function isInitialized() : bool
    {
        return $this->initialized;
    }

    public function has(string $key) : bool
    {
        $this->initialize();

        return isset($this->data[$key]);
    }

    /**
     * @return mixed|null
     */
    public function get(string $key)
    {
        $this->initialize();

        return $this->data[$key] ?? null;
    }

    /**
     * @param mixed $value
     */
    public function set(string $key, $value) : AbstractPart
    {
        $this->initialize();
        $this->data[$key] = $value;

        return $this;
    }

    /**
     * @param mixed $value
     */
    public function add($value) : AbstractPart
    {
        $this->initialize();
        $this->data[] = $value;

        return $this;
    }

    public function remove(string $key) : void
    {
        $this->initialize();

        unset($this->data[$key]);
    }

    public function __isset(string $key) : bool
    {
        return $this->has($key);
    }

    /**
     * @return mixed
     */
    public function __get(string $key)
    {
        return $this->get($key);
    }

    /**
     * @param mixed $value
     */
    public function __set(string $key, $value) : AbstractPart
    {
        return $this->set($key, $value);
    }

    public function __unset(string $key) : void
    {
        $this->remove($key);
    }

    /**
     * @param string $key
     */
    public function offsetExists($key) : bool
    {
        $this->initialize();

        return isset($this->data[$key]);
    }

    /**
     * @param string $key
     *
     * @return mixed
     */
    public function offsetGet($key)
    {
        return $this->get($key);
    }

    /**
     * @param string $key
     * @param mixed  $value
     *
     * @return void
     */
    public function offsetSet($key, $value)
    {
        $this->set($key, $value);
    }

    /**
     * @param string $key
     *
     * @return void
     */
    public function offsetUnset($key)
    {
        $this->remove($key);
    }

    protected function initialize() : void
    {
        if ($this->initialized === true) {
            return;
        }

        $this->initialized = true;

        $this->doInitialize();
    }

    /**
     * @param string|AbstractPart $value
     *
     * @return mixed
     */
    protected function preparePartValue(string $key, $value)
    {
        if (! isset($this->partClassMap[$key])) {
            return $value;
        }

        $className = $this->partClassMap[$key];

        return ! $value instanceof $className ? new $className($value) : $value;
    }

    abstract public function __toString() : string;

    abstract protected function doInitialize() : void;
}
