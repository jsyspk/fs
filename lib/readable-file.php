<?php
declare(strict_types=1);

namespace J\FS;

use \InvalidArgumentException;

class ReadableFile extends AnyFile
{

    public function __construct(FilePath $filePth)
    {
        parent::__construct($filePth);
        $path = $filePth->value();
        if(!is_readable($path))
        {
            throw new InvalidArgumentException("Given file '$path' is not accessible. Please check permissions", 20003);
        }
    }

}
