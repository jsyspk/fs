<?php
declare(strict_types=1);

namespace J\FS;

interface Image
{

    public function width(): int;

    public function height(): int;

    public function exif(): string;

}
