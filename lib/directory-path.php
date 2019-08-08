<?php
declare(strict_types=1);

/**
 * created by Ali Masood<ali@jsys.pk>
 * Date: 2019-08-08 11:58
 */

namespace J\FS;

class DirectoryPath extends AnyPath implements DirPath
{
    public function __construct(string $path)
    {
        parent::__construct($path);
    }

    public function withTrailingSlash(): string
    {
        return $this->noTrailingSlash() . '/';
    }

    public function noTrailingSlash(): string
    {
        return rtrim($this->path(), '/');
    }
}
