<?php
declare(strict_types=1);

namespace J\FS;

class ReadableFile extends AnyFile
{

    public function __construct(string $file)
    {
        parent::__construct($file);
        if(!is_readable($file))
        {
            throw new \InvalidArgumentException("Given file '$file' is not accessible. Please check permissions", 20003);
        }
    }
}
