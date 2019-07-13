<?php
declare(strict_types=1);

namespace J\Tests\Unit;

use J\FS\WritableFile;
use org\bovigo\vfs\vfsStream;
use org\bovigo\vfs\vfsStreamFile;
use PHPUnit\Framework\TestCase;

use \ArgumentCountError;
use \InvalidArgumentException;

class WritableFileTest extends TestCase
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
        $rFile = new WritableFile();
    }

    public function test_empty_staring_as_file_parameter_must_not_be_accepted():void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("Given file name ''  is empty");
        $this->expectExceptionCode(20000);
        $rFile = new WritableFile("");
    }

    public function test_dir_path_must_not_be_accepted():void
    {
        $dir = $this->rootDir->url() . '/base-dir/sub-dir1/';
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("Given file path '$dir' is not a file but it is a directory");
        $this->expectExceptionCode(20001);
        $rFile = new WritableFile($dir);
    }

    public function test_non_existent_files_must_not_be_accepted():void
    {
        $noFile = $this->rootDir->url() . '/base-dir/sub-dir1/some-non-existing-file.pdf';
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage("Given file '$noFile' doesn't exist");
        $this->expectExceptionCode(20002);
        $rFile = new WritableFile($noFile);
    }

    public function test_non_writable_files_must_not_be_accepted():void
    {
        $file = new vfsStreamFile('no-permission-file.txt', 0400);
        $file->chown(vfsStream::getCurrentUser() + 1);
        $file->chgrp(vfsStream::getCurrentGroup() + 1);
        $file->setContent('some sample text');
        $this->rootDir->addChild($file);

        $this->assertTrue($this->rootDir->hasChild('no-permission-file.txt'));

        $noPermissionFile = $this->rootDir->url() . '/no-permission-file.txt';
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage("Given file '$noPermissionFile' is not writable. Please check permissions");
        $this->expectExceptionCode(20004);
        $rFile = new WritableFile($noPermissionFile);
    }

    public function test_must_work_with_a_valid_writable_file()
    {
        $testFile = $this->rootDir->url() . '/base-dir/sub-dir0/test.file.txt';
        $rFile = new WritableFile($testFile);
        $this->assertInstanceOf('J\FS\WritableFile', $rFile);
        $this->assertEquals('J\FS\WritableFile', get_class($rFile));
        $this->assertEquals($rFile->fullPath(), $testFile, 'Full file path');
        $this->assertEquals($rFile->name(), 'test.file.txt', 'Full file name only');
        $this->assertEquals($rFile->dir(), $this->rootDir->url() . '/base-dir/sub-dir0', 'File containing dir');
        $this->assertEquals($rFile->extension(), 'txt', 'File extension');
        $this->assertEquals($rFile->mime(), 'text/plain', 'File mime type');
        $this->assertEquals($rFile->coreName(), 'test.file', 'File mime type');
    }

    public function tearDown(): void
    {
        parent::tearDown();
        unset($this->rootDir);
    }
}
