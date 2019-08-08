<?php
declare(strict_types=1);

/**
 * created by Ali Masood<ali@jsys.pk>
 * Date: 2019-08-08 12:06
 */

namespace J\FS;

abstract class AnyPath implements Path
{
    protected $path;
    public function __construct(string $path)
    {
        if(mb_strlen($path) === 0)
        {
            throw new \InvalidArgumentException('An empty string is not a valid path', 10020);
        }
        $this->path = $path;
    }

    public function path(): string
    {
        return $this->path;
    }

    public function __toString()
    {
        return $this->path();
    }
}
