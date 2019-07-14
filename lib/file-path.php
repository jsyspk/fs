<?php
declare(strict_types=1);

namespace J\FS;

use J\Types\AnyPath;
use \InvalidArgumentException;

class FilePath extends AnyPath
{
    public function __construct(string $filePath)
    {
        parent::__construct($filePath);

        if(is_dir($filePath))
        {
            throw new InvalidArgumentException("Given file path '$filePath' is not a file but it is a directory", 20001);
        }

        if(!is_file($filePath))
        {
            throw new InvalidArgumentException("Given file path '$filePath' doesn't map to a valid file", 20005);
        }
    }
}