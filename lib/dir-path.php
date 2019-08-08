<?php
declare(strict_types=1);

/**
 * created by Ali Masood<ali@jsys.pk>
 * Date: 2019-08-08 14:31
 */

namespace J\FS;

interface DirPath
{
    public function withTrailingSlash(): string ;

    public function noTrailingSlash(): string ;
}
