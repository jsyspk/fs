<?php
declare(strict_types=1);

namespace J\FS;

class TempFile extends AnyFile
{

    public function __construct(FilePath $file)
    {
        parent::__construct($file);
    }
}
