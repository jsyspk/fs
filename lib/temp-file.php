<?php
declare(strict_types=1);

namespace J\FS;

class TempFile extends AnyFile
{

    public function __construct(string $file)
    {
        parent::__construct($file);
    }
}
