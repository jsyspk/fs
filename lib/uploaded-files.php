<?php
declare(strict_types=1);

namespace J\FS;

use \ArrayIterator;
use \IteratorAggregate;

class UploadedFiles extends AnyFile implements IteratorAggregate
{
    private $files;

    public function __construct(UploadedFile ...$uploadedFiles)
    {
        $this->files = $uploadedFiles;
    }

    public function getIterator(): ArrayIterator
    {
        return new ArrayIterator($this->files);
    }
}
