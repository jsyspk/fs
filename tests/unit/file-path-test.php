<?php
declare(strict_types=1);

namespace J\Tests\Unit;

use J\FS\FilePath;
use org\bovigo\vfs\vfsStream;
use PHPUnit\Framework\TestCase;

use \ArgumentCountError;
use \InvalidArgumentException;

class FilePathTest extends TestCase
{
    private $rootDir;

    public function setUp(): void
    {
        parent::setUp();
        $structure = [
            'base-dir' => [
                'sub-dir0' => ['test.file.txt' => 'This is a source file that has some sample content. This is a test of readable and not readable.'],
                'sub-dir1' => [],
                'sub-dir2' => [
                    'sub-dir3' => [],
                ],
            ]
        ];
        $this->rootDir = vfsStream::setup('root', 700, $structure);
        $this->assertTrue($this->rootDir->hasChild('base-dir/sub-dir0/test.file.txt'));
        $this->rootDir->chown(vfsStream::getCurrentUser());
        $this->rootDir->chgrp(vfsStream::getCurrentGroup());
    }

    public function test_a_valid_file_path_as_input_parameter_must_be_supplied():void
    {
        $this->expectException(ArgumentCountError::class);
        $rFile = new FilePath();
    }

    public function test_empty_staring_as_file_parameter_must_not_be_accepted():void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("Given path '' is empty and can not be used as a valid path");
        $this->expectExceptionCode(30000);
        $rFile = new FilePath("");
    }

    public function test_dir_path_must_not_be_accepted():void
    {
        $dir = $this->rootDir->url() . '/base-dir/sub-dir1/';
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("Given file path '$dir' is not a file but it is a directory");
        $this->expectExceptionCode(20001);
        $rFile = new FilePath($dir);
    }

    public function test_non_existent_files_must_not_be_accepted():void
    {
        $noFile = $this->rootDir->url() . '/base-dir/sub-dir1/some-non-existing-file.pdf';
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("Given file path '$noFile' doesn't map to a valid file");
        $this->expectExceptionCode(20005);
        $rFile = new FilePath($noFile);
    }

    public function tearDown(): void
    {
        parent::tearDown();
        unset($this->rootDir);
    }
}
