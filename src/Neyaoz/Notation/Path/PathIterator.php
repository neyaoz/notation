<?php

namespace Neyaoz\Notation\Path;

use ArrayIterator;

/**
 * Class PathIterator
 * @package Neyaoz\Notation\Path
 */
class PathIterator extends ArrayIterator
{
    /**
     * The traversed path.
     *
     * @var Path
     */
    protected $path;

    /**
     * Constructor.
     *
     * @param Path $path The path to traverse
     */
    public function __construct(Path $path)
    {
        $this->setPath($path);
        parent::__construct($path->getFields()->toArray());
    }

    /**
     * @return Path|null
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * @param  Path $path
     * @return $this
     */
    public function setPath(Path $path)
    {
        $this->path = $path;
        return $this;
    }
}