<?php
declare(strict_types=1);

namespace J\FS;

use \InvalidArgumentException;

class WritableFile extends AnyFile
{

    public function __construct(FilePath $file)
    {
        parent::__construct($file);
        if(!is_writable($file->value()))
        {
            throw new InvalidArgumentException("Given file '$file' is not writable. Please check permissions", 20004);
        }
    }

}
