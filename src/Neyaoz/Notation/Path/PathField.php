<?php

namespace Neyaoz\Notation\Path;

/**
 * Class PathField
 * @package Neyaoz\Notation\Path
 */
class PathField
{

    /**
     * @var string
     */
    protected $name;

    /**
     * @var string|null
     */
    protected $type;

    /**
     * PathObject constructor.
     * @param string $name
     * @param string|null $type
     */
    public function __construct($name, $type = null)
    {
        $this->setName($name);
        $this->setType($type);
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param  $name
     * @return $this
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return null|string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param  $type
     * @return $this
     */
    public function setType($type)
    {
        $this->type = $type;
        return $this;
    }

}