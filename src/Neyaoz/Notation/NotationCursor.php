<?php

namespace Neyaoz\Notation;

use Neyaoz\Notation\Path\PathField;
use Neyaoz\Support\Stringy;
use ReflectionClass;

/**
 * Class NotationCursor
 * @package Neyaoz\Notation
 */
class NotationCursor
{
    /**
     * @var mixed
     */
    protected $data;

    /**
     * NotationCursor constructor.
     * @param mixed $data
     */
    public function __construct(&$data = null)
    {
        $this->putData($data);
    }

    /**
     * @param  PathField $pathField
     * @param  bool      $orSet
     * @return bool
     */
    public function get(PathField $pathField, $orSet = false)
    {
        $typeMethod = $this->camelize('get', $pathField->getType());
        return $this->{$typeMethod}($pathField->getName(), $orSet);
    }

    /**
     * @param  string $name
     * @param  bool   $orSet
     * @return bool
     */
    public function getProperty($name, $orSet = false)
    {
        if (is_object($this->data)) {
            if (
                method_exists($this->data, $getter = $this->camelize('get', $name)) ||
                method_exists($this->data, $getter = $this->camelize($name))
            ) {
                $data = $this->data->{$getter}();
                $this->putData($data);
                return true;
            }

            if (property_exists($this->data, $name)) {
                $this->putData($this->data->{$name});
                return true;
            }
        }

        if ($orSet) {
            $this->setData((object) $this->data);

            if (! property_exists($this->data, $name)) {
                $this->setProperty($name);
            }

            $this->putData($this->data->{$name});
            return true;
        }

        $this->putData();
        return false;
    }

    /**
     * @param  string $name
     * @param  bool   $orSet
     * @return bool
     */
    public function getKey($name, $orSet = false)
    {
        if (is_array($this->data)) {
            if (in_array($name, ['', '@']) && ! empty($this->data)) {
                $this->putData($this->data[count($this->data) - 1]);
                return true;
            }

            if (array_key_exists($name, $this->data)) {
                $this->putData($this->data[$name]);
                return true;
            }
        }

        if ($orSet) {
            $this->setData((array) $this->data);

            if (! array_key_exists($name, $this->data)) {
                $this->setKey($name);
            }

            $this->putData($this->data[$name]);
            return true;
        }

        $this->putData();
        return false;
    }

    /**
     * @param  PathField $pathField
     * @param  mixed     $value
     * @return mixed
     */
    public function set(PathField $pathField, $value = null)
    {
        $typeMethod = $this->camelize('set', $pathField->getType());
        return $this->{$typeMethod}($pathField->getName(), $value);
    }

    /**
     * @param  string $name
     * @param  mixed  $value
     * @return $this
     */
    public function setProperty($name, $value = null)
    {
        $this->setData((object) $this->data);

        if (
            method_exists($this->data, $setter = $this->camelize('set', $name)) ||
            method_exists($this->data, $setter = $this->camelize($name))
        ) {
            $this->data->{$setter}($value);
        } else {
            $this->data->{$name} = $value;
        }

        return $this;
    }

    /**
     * @param  string $name
     * @param  mixed  $value
     * @return $this
     */
    public function setKey($name, $value = null)
    {
        $this->setData((array) $this->data);

        if ($name === '') {
            $this->data[] = $value;
        } else if ($name === '@') {
            $this->data[max(count($this->data) - 1, 0)] = $value;
        } else {
            $this->data[$name] = $value;
        }

        return $this;
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
     * @param  string[] ...$strings
     * @return string
     */
    protected function camelize(...$strings)
    {
        $result = '';
        foreach ($strings as $string) {
            $result .= Stringy::create($string)->{empty($result) ? 'camelize' : 'upperCamelize'}();
        }

        return (string) $result;
    }

}