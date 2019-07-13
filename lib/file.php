<?php
declare(strict_types=1);

namespace J\FS;

interface File
{

    public function dir(): string;

    public function name(): string;

    public function coreName(): string;

    public function fullPath(): string;

    public function size(): int;

    public function extension(): string;

    public function mime(): string;

}
