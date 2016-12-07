<?php

namespace Neyaoz\Notation;

use Neyaoz\Notation\Path\Path;
use Neyaoz\Notation\Path\PathField;
use Neyaoz\Notation\Path\PathIterator;
use Neyaoz\Support\Collection;

/**
 * Class Notation
 * @package Neyaoz\Notation
 * @todo flexible pathField type for gets or sets
 * @todo isReadable & isWritable & isExists
 * @todo access methods by '[0].foo.bar()'
 */
class Notation
{

    /**
     * @var mixed
     */
    protected $data;

    /**
     * Notation constructor.
     * @param mixed $data
     */
    public function __construct($data = null)
    {
        $this->setData($data);
    }

    /**
     * @param  Path|string $path
     * @return bool
     */
    public function has($path)
    {
        $cursor = new NotationCursor($this->data);

        foreach ($this->parsePath($path) as $pathField)
        {
            if ($cursor->get($pathField) === false) {
                return false;
            }
        }

        return true;
    }

    /**
     * @param  Path|string $path
     * @param  mixed|null  $default
     * @return mixed|null
     */
    public function get($path, $default = null)
    {
        $cursor = new NotationCursor($this->data);

        foreach ($this->parsePath($path) as $pathField)
        {
            if ($cursor->get($pathField) === false) {
                return $default;
            }
        }

        return $cursor->getData();
    }

    /**
     * @param  Path|string $path
     * @param  mixed|null  $value
     * @return mixed|null
     * @todo return true veya false
     */
    public function set($path, $value = null)
    {
        $cursor = new NotationCursor($this->data);

        $pathFieldsGet = $this->parsePath($path)->getFields();
        $pathFieldsSet = $pathFieldsGet->pop();

        $pathFieldsGet->each(function (PathField $pathField) use ($cursor) {
            $cursor->get($pathField, true);
        });

        if ($pathFieldsSet instanceof PathField) {
            $cursor->set($pathFieldsSet, $value);
        }

        return $this;
    }

    /**
     * @param  Path|string $path
     * @return Path
     */
    protected function parsePath($path)
    {
        if ($path instanceof Path) {
            return clone $path;
        }

        return new Path($path);
    }

    /**
     * @return mixed
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @param  mixed $value
     * @return $this
     */
    public function setData($value = null)
    {
        $this->data = $value;

        return $this;
    }

    /**
     * @param  mixed $data
     * @return $this
     */
    public function putData(&$data = null)
    {
        $this->data = &$data;
        return $this;
    }

    /**
     *
     */
    public function __clone()
    {
        $this->setData(clone $this->getData());
    }

}