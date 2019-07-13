<?php
declare(strict_types=1);

namespace J\FS;

interface Uploaded
{
    public function tempName(): string;

    public function originalName(): string;

    public function size(): int;

}
