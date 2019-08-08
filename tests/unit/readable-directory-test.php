<?php
declare(strict_types=1);

namespace J\Tests\Unit;

use J\FS\DirectoryPath;
use J\FS\ReadableDirectory;
use org\bovigo\vfs\vfsStream;
use org\bovigo\vfs\vfsStreamDirectory;
use org\bovigo\vfs\vfsStreamFile;
use PHPUnit\Framework\TestCase;

use \ArgumentCountError;
use \InvalidArgumentException;

class ReadableDirectoryTest extends TestCase
{
    private $rootDir;

    public function setUp(): void
    {
        parent::setUp();
        $structure = [
            'base-dir' => [
                'text-files' => [
                    'file0.txt' => 'file1',
                    'file1.txt' => 'file1',
                    'file2.txt' => 'file1',
                    'file3.txt' => 'file1',
                    'file4.txt' => 'file1',
                    'file5.txt' => 'file1',
                    'file6.txt' => 'file1',
                    'file7.txt' => 'file1',
                ],
                'mixed-files' => [
                    'file0.txt' => 'file1',
                    'file1.jpg' => 'file1',
                    'file2.png' => 'file1',
                    'file3.png' => 'file1',
                    'file4.jpg' => 'file1',
                    'file5.vb' => 'file1',
                    'file6.vb' => 'file1',
                    'file7.ini' => 'file1',
                ],
                'mixed-files-with-o-extensions' => [
                    'file0.txt' => 'file1',
                    'file1.jpg' => 'file1',
                    'file2.png' => 'file1',
                    'file3.png' => 'file1',
                    'file4.jpg' => 'file1',
                    'file5' => 'file1',
                    'file6' => 'file1',
                    'file64' => 'file1',
                    '.file6' => 'file1',
                    '.file7' => 'file1',
                    'file7.ini' => 'file1',
                ],
                'with-dirs' => [
                    'file0.txt' => 'file1',
                    'file1.jpg' => 'file1',
                    'file2.png' => 'file1',
                    'file3.png' => 'file1',
                    'file4.jpg' => 'file1',
                    'file5' => 'file1',
                    'file6' => 'file1',
                    'file7.ini' => 'file1',
                    'dir1' => [
                        'mixed-files-inside-dir' => [
                            'file0.txt' => 'file1',
                            'file1.jpg' => 'file1',
                            'file2.png' => 'file1',
                            'file3.png' => 'file1',
                            'file4.jpg' => 'file1',
                            'file5' => 'file1',
                            'file6' => 'file1',
                            'file7.ini' => 'file1',
                        ],
                    ],
                    'dir2' => [],
                    'dir3' => [],
                ],
            ]
        ];
        $this->rootDir = vfsStream::setup('root', 700, $structure);
        $this->assertTrue($this->rootDir->hasChild('base-dir/text-files/file0.txt'));
        $this->rootDir->chown(vfsStream::getCurrentUser());
        $this->rootDir->chgrp(vfsStream::getCurrentGroup());
    }

    public function test_a_valid_dir_path_as_input_parameter_must_be_supplied():void
    {
        $this->expectException(ArgumentCountError::class);
        $rFile = new ReadableDirectory();
    }

    public function test_non_readable_directories_must_not_be_accepted():void
    {
        $dir = new vfsStreamDirectory('no-permission-dir', 0400);
        $dir->chown(vfsStream::getCurrentUser() + 1);
        $dir->chgrp(vfsStream::getCurrentGroup() + 1);
        $this->rootDir->addChild($dir);

        $this->assertTrue($this->rootDir->hasChild('no-permission-dir'));

        $noPermissionDir = $this->rootDir->url() . '/no-permission-dir';
        $dirPath = new DirectoryPath($noPermissionDir);
        $this->expectException(InvalidArgumentException::class);
        $path = $dirPath->path();
        $this->expectExceptionMessage("The given directory path '$path' is not accessible. Please check permissions");
        $this->expectExceptionCode(200001);
        $rDir = new ReadableDirectory($dirPath);
    }

    public function test_must_work_with_a_valid_readable_dir_without_trailing_slash()
    {
        $testDir = $this->rootDir->url() . '/base-dir/text-files';
        $rDir = new ReadableDirectory(new DirectoryPath($testDir));
        $this->assertInstanceOf('J\FS\ReadableDirectory', $rDir);
        $this->assertEquals('J\FS\ReadableDirectory', get_class($rDir));
        $this->assertEquals($rDir->fullPath(), $testDir, 'Full directory path');

    }

    public function test_must_work_with_a_valid_readable_dir_with_trailing_slash()
    {
        $testDir = $this->rootDir->url() . '/base-dir/text-files/';
        $rDir = new ReadableDirectory(new DirectoryPath($testDir));
        $this->assertInstanceOf('J\FS\ReadableDirectory', $rDir);
        $this->assertEquals('J\FS\ReadableDirectory', get_class($rDir));
        $this->assertEquals($rDir->fullPath(), $testDir, 'Full directory path');

    }

    public function test_can_get_list_of_all_files_from_a_valid_readable_dir()
    {
        $testDir = __DIR__ . '/test-files/txt-files';
        $sampleFiles = [
            '/Volumes/data/projects/open-source/fs/tests/unit/test-files/txt-files/file0.txt',
            '/Volumes/data/projects/open-source/fs/tests/unit/test-files/txt-files/file1.txt',
            '/Volumes/data/projects/open-source/fs/tests/unit/test-files/txt-files/file2.txt',
            '/Volumes/data/projects/open-source/fs/tests/unit/test-files/txt-files/file3.txt',
            '/Volumes/data/projects/open-source/fs/tests/unit/test-files/txt-files/file4.txt',
            '/Volumes/data/projects/open-source/fs/tests/unit/test-files/txt-files/file5.txt',
            '/Volumes/data/projects/open-source/fs/tests/unit/test-files/txt-files/file6.txt',
            '/Volumes/data/projects/open-source/fs/tests/unit/test-files/txt-files/file7.txt',
            '/Volumes/data/projects/open-source/fs/tests/unit/test-files/txt-files/file8.txt',
            '/Volumes/data/projects/open-source/fs/tests/unit/test-files/txt-files/file9.txt'
        ];
        $rDir = new ReadableDirectory(new DirectoryPath($testDir));
        $this->assertInstanceOf('J\FS\ReadableDirectory', $rDir);
        $this->assertEquals('J\FS\ReadableDirectory', get_class($rDir));
        $this->assertEquals($rDir->fullPath(), $testDir, 'Full directory path');
        $this->assertEquals(10, $rDir->countFiles());
        foreach ($rDir->files() as $file)
        {
            $this->assertIsString($file);
            $this->assertNotFalse(strpos($file, '/fs/tests/unit/test-files/txt-files/file'));
        }
        $this->assertEquals($sampleFiles, $rDir->files());

    }

    public function test_can_get_list_of_all_files_filtered_by_extension()
    {
        $testDir = __DIR__ . '/test-files/mixed-files';
        $jpgFiles = [
            '/Volumes/data/projects/open-source/fs/tests/unit/test-files/mixed-files/file0.jpg',
            '/Volumes/data/projects/open-source/fs/tests/unit/test-files/mixed-files/file1.jpg',
            '/Volumes/data/projects/open-source/fs/tests/unit/test-files/mixed-files/file2.jpg'
        ];
        $txtFiles = [
            '/Volumes/data/projects/open-source/fs/tests/unit/test-files/mixed-files/file2.txt',
            '/Volumes/data/projects/open-source/fs/tests/unit/test-files/mixed-files/file5.txt'
        ];
        $rDir = new ReadableDirectory(new DirectoryPath($testDir));
        $this->assertInstanceOf('J\FS\ReadableDirectory', $rDir);
        $this->assertEquals('J\FS\ReadableDirectory', get_class($rDir));
        $this->assertEquals($rDir->fullPath(), $testDir, 'Full directory path');
        $this->assertEquals($jpgFiles, $rDir->files(['jpg']));
        $this->assertEquals(3, $rDir->countFiles(['jpg']));
        $this->assertEquals($txtFiles, $rDir->files(['txt']));
        $this->assertEquals(2, $rDir->countFiles(['txt']));

    }

    public function test_can_get_list_of_all_files_filtered_by_multiple_extensions()
    {
        $testDir = __DIR__ . '/test-files/mixed-files';
        $rDir = new ReadableDirectory(new DirectoryPath($testDir));
        $this->assertInstanceOf('J\FS\ReadableDirectory', $rDir);
        $this->assertEquals('J\FS\ReadableDirectory', get_class($rDir));
        $this->assertEquals($rDir->fullPath(), $testDir, 'Full directory path');

        $jpgTxtFiles = [
            '/Volumes/data/projects/open-source/fs/tests/unit/test-files/mixed-files/file0.jpg',
            '/Volumes/data/projects/open-source/fs/tests/unit/test-files/mixed-files/file1.jpg',
            '/Volumes/data/projects/open-source/fs/tests/unit/test-files/mixed-files/file2.jpg',
            '/Volumes/data/projects/open-source/fs/tests/unit/test-files/mixed-files/file2.txt',
            '/Volumes/data/projects/open-source/fs/tests/unit/test-files/mixed-files/file5.txt'
        ];

        $this->assertEquals($jpgTxtFiles, $rDir->files(['jpg', 'txt']));
        $this->assertEquals(5, $rDir->countFiles(['jpg', 'txt']));

    }

    public function test_can_ignore_nested_directories()
    {
        $testDir = __DIR__ . '/test-files/mixed-files';
        $rDir = new ReadableDirectory(new DirectoryPath($testDir));
        $this->assertInstanceOf('J\FS\ReadableDirectory', $rDir);
        $this->assertEquals('J\FS\ReadableDirectory', get_class($rDir));
        $this->assertEquals($rDir->fullPath(), $testDir, 'Full directory path');


        $allFiles = [
            '/Volumes/data/projects/open-source/fs/tests/unit/test-files/mixed-files/file0.jpg',
            '/Volumes/data/projects/open-source/fs/tests/unit/test-files/mixed-files/file0.png',
            '/Volumes/data/projects/open-source/fs/tests/unit/test-files/mixed-files/file1.jpg',
            '/Volumes/data/projects/open-source/fs/tests/unit/test-files/mixed-files/file1.png',
            '/Volumes/data/projects/open-source/fs/tests/unit/test-files/mixed-files/file2.jpg',
            '/Volumes/data/projects/open-source/fs/tests/unit/test-files/mixed-files/file2.txt',
            '/Volumes/data/projects/open-source/fs/tests/unit/test-files/mixed-files/file3.env',
            '/Volumes/data/projects/open-source/fs/tests/unit/test-files/mixed-files/file5.txt'
        ];

        $jpgTxtFiles = [
            '/Volumes/data/projects/open-source/fs/tests/unit/test-files/mixed-files/file0.jpg',
            '/Volumes/data/projects/open-source/fs/tests/unit/test-files/mixed-files/file1.jpg',
            '/Volumes/data/projects/open-source/fs/tests/unit/test-files/mixed-files/file2.jpg',
            '/Volumes/data/projects/open-source/fs/tests/unit/test-files/mixed-files/file2.txt',
            '/Volumes/data/projects/open-source/fs/tests/unit/test-files/mixed-files/file5.txt'
        ];

        $this->assertEquals($allFiles, $rDir->files());
        $this->assertEquals(8, $rDir->countFiles());

        $this->assertEquals($jpgTxtFiles, $rDir->files(['jpg', 'txt']));
        $this->assertEquals(5, $rDir->countFiles(['txt', 'jpg']));

    }


    public function test_can_get_hidden_files()
    {
        $testDir = __DIR__ . '/test-files/mixed-files';
        $rDir = new ReadableDirectory(new DirectoryPath($testDir));
        $this->assertInstanceOf('J\FS\ReadableDirectory', $rDir);
        $this->assertEquals('J\FS\ReadableDirectory', get_class($rDir));
        $this->assertEquals($rDir->fullPath(), $testDir, 'Full directory path');

        $hiddenFiles = [
            '/Volumes/data/projects/open-source/fs/tests/unit/test-files/mixed-files/.env',
            '/Volumes/data/projects/open-source/fs/tests/unit/test-files/mixed-files/.test',
            '/Volumes/data/projects/open-source/fs/tests/unit/test-files/mixed-files/.txt'
        ];

        $this->assertEquals($hiddenFiles, $rDir->hiddenFiles());
        $this->assertEquals(3, $rDir->countHidden());

    }

    public function test_can_get_directories()
    {
        $testDir = __DIR__ . '/test-files/mixed-files';
        $rDir = new ReadableDirectory(new DirectoryPath($testDir));
        $this->assertInstanceOf('J\FS\ReadableDirectory', $rDir);
        $this->assertEquals('J\FS\ReadableDirectory', get_class($rDir));
        $this->assertEquals($rDir->fullPath(), $testDir, 'Full directory path');

        $dirs = [
            '/Volumes/data/projects/open-source/fs/tests/unit/test-files/mixed-files/empty-dir',
            '/Volumes/data/projects/open-source/fs/tests/unit/test-files/mixed-files/nested-files',
            '/Volumes/data/projects/open-source/fs/tests/unit/test-files/mixed-files/test-dir'
        ];

        $this->assertEquals($dirs, $rDir->directories());
        $this->assertEquals(3, $rDir->countDirectories());

    }
    public function tearDown(): void
    {
        parent::tearDown();
        unset($this->rootDir);
    }
}
