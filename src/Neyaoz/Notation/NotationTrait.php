<?php

namespace Neyaoz\Notation;

use Neyaoz\Notation\Path\Path;

/**
 * Class NotationTrait
 * @package Neyaoz\Notation
 */
trait NotationTrait
{

    /**
     * @var Notation
     */
    protected $notation;

    /**
     * @param  Path|string $path
     * @return bool
     */
    public function has($path)
    {
        return $this->getNotation()->has($path);
    }

    /**
     * @param  Path|string $path
     * @param  mixed       $default
     * @return mixed
     */
    public function get($path, $default = null)
    {
        return $this->getNotation()->get($path, $default);
    }

    /**
     * @param  Path|string $path
     * @param  mixed|null  $value
     * @return $this
     */
    public function set($path, $value = null)
    {
        $this->getNotation()->set($path, $value);
        return $this;
    }

    /**
     * @return Notation
     */
    public function getNotation()
    {
        if (is_null($this->notation)) {
            $this->setNotation();
        }

        return $this->notation;
    }

    /**
     * @param  Notation $notation
     * @return $this
     */
    public function setNotation(Notation $notation = null)
    {
        if (is_null($notation)) {
            $notation = new Notation($this);
        }
        $this->notation = $notation;

        return $this;
    }

    /**
     *
     */
    public function __clone()
    {
        $this->setNotation(clone $this->getNotation());
    }

}