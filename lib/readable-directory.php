<?php
declare(strict_types=1);

/**
 * created by Ali Masood<ali@jsys.pk>
 * Date: 2019-08-08 11:59
 */

namespace J\FS;

class ReadableDirectory extends AnyDirectory
{

    public function __construct(DirectoryPath $dirPath)
    {
        parent::__construct($dirPath);

        $fullPath = $dirPath->path();

        if(!is_readable($fullPath))
        {
            throw new \InvalidArgumentException("The given directory path '$fullPath' is not accessible. Please check permissions.", 200001);
        }
    }

    public function files(array $extensions=[]): array
    {
        if(!empty($extensions))
        {
            $exts = implode(',', $extensions);
            $filter = $this->path->withTrailingSlash() . '*.{' . $exts . '}';
            $filesList = glob($filter, GLOB_BRACE);
        } else {
            $filesList = array_values(array_filter(glob($this->path->withTrailingSlash() . '*'), 'is_file'));
        }
        return $filesList;
    }

    public function countFiles(array $extensions=[]): int
    {
        return count($this->files($extensions));
    }

    public function hiddenFiles(): array
    {
        return array_values(array_filter(glob($this->path->withTrailingSlash() . '{.}*', GLOB_BRACE), 'is_file'));
    }

    public function countHidden(): int
    {
        return count($this->hiddenFiles());
    }

    public function directories(): array
    {
        return glob($this->path->withTrailingSlash() . '*', GLOB_ONLYDIR);
    }

    public function countDirectories(): int
    {
        return count($this->directories());
    }
}
