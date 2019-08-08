<?php
declare(strict_types=1);

/**
 * created by Ali Masood<ali@jsys.pk>
 * Date: 2019-08-08 11:58
 */

namespace J\FS;

interface Directory
{
    public function countFiles(array $extensions=[]): int;

    public function files(array $extensions=[]): array;

    public function fullPath(): string;

    public function hiddenFiles(): array ;

    public function countHidden(): int;

    public function directories(): array;

    public function countDirectories(): int;

}
