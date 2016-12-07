<?php

namespace Neyaoz\Notation\Path;

use InvalidArgumentException;
use IteratorAggregate;
use Traversable;

use Neyaoz\Support\Collection;

/**
 * Class Path
 * @package Neyaoz\Notation\Path
 */
class Path implements IteratorAggregate
{
    /**
     * @var Collection
     */
    protected $fields;

    /**
     * @var string
     */
    protected $string;

    /**
     * Path constructor.
     * @param string|null $path
     */
    public function __construct($path = null, $basic = false)
    {
        if (is_string($path)) {
            if (! $basic) {
                $this->parsePath($path);
            } else {
                $this->parsePathBasic($path);
            }
        }
    }

    /**
     * @param  string $pathString
     * @return $this
     */
    public function parsePath($pathString)
    {
        $pathOffset = 0;
        while (! empty($pathString))
        {
            switch (true)
            {
                case (preg_match('/^((?:\.?)([^\.|\{|\}|\[|\]]+))(.*)$/', $pathString, $matches)):
                    $pathField = new PathField(trim($matches[2]), 'property');
                    break;
                case (preg_match('/^((?:\{)([^\{|\}]+)(?:\}))(.*)$/', $pathString, $matches)):
                    $pathField = new PathField(trim($matches[2]), 'property');
                    break;
                case (preg_match('/^((?:\[)([^\[|\]]*)(?:\]))(.*)$/', $pathString, $matches)):
                    $pathField = new PathField(trim($matches[2]), 'key');
                    break;

                default:
                    throw new InvalidArgumentException(sprintf(
                        'Compilation failed for "%s" because of an unexpected token "%s" at position %d.',
                        $this->getString(), substr($pathString, 0, 1), $pathOffset
                    ));
            }

            $this->getFields()->push($pathField);
            $this->addString($matches[1]);

            $pathOffset += strlen($matches[1]);
            $pathString = $matches[3];
        }

        return $this;
    }

    /**
     * @param  string $pathString
     * @param  string $delimiter
     * @return $this
     */
    public function parsePathBasic($pathString, $delimiter = '.')
    {
        $this->addString($pathString);

        $pathString = explode($delimiter, $pathString);
        foreach ($pathString as $name) {
            $this->getFields()->push(new PathField($name));
        }

        return $this;
    }

    public function clearPath()
    {
        $this->setString();
        $this->setFields();
    }

    /**
     * @return string|null
     */
    public function getString()
    {
        return $this->string;
    }

    /**
     * @param string|null $pathString
     */
    public function setString($pathString = null)
    {
        $this->string = $pathString;
    }

    /**
     * @param string $pathString
     */
    public function addString($pathString)
    {
        $this->string .= $pathString;
    }

    /**
     * @return Collection
     */
    public function getFields()
    {
        if (! $this->fields) {
            $this->setFields();
        }

        return $this->fields;
    }

    /**
     * @param Collection|array $fields
     */
    public function setFields($fields = null)
    {
        if (! $fields instanceof Collection) {
            $fields = new Collection($fields);
        }

        $this->fields = $fields;
    }

    /**
     * Retrieve an external iterator
     * @link http://php.net/manual/en/iteratoraggregate.getiterator.php
     * @return Traversable An instance of an object implementing <b>Iterator</b> or
     * <b>Traversable</b>
     * @since 5.0.0
     */
    public function getIterator()
    {
        return new PathIterator($this);
    }

    /**
     * 
     */
    public function __clone()
    {
        $this->setFields(clone $this->getFields());
    }
}