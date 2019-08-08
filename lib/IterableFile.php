<?php
declare(strict_types=1);

/**
 * created by Ali Masood<ali@jsys.pk>
 * Date: 2019-08-08 11:19
 */

namespace J\FS;

class IterableFile extends \SplFileObject
{
    public function __construct(ReadableFile $file)
    {
        parent::__construct($file->fullPath());
    }
}
