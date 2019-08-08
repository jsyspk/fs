<?php
declare(strict_types=1);

/**
 * created by Ali Masood<ali@jsys.pk>
 * Date: 2019-08-08 14:23
 */

namespace J\FS;

abstract class AnyDirectory implements Directory
{
    protected $path;

    public function __construct(DirectoryPath $dirPath)
    {
        $this->path = $dirPath;
    }

    public function fullPath():string
    {
        return $this->path->path();
    }

}
