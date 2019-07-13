<?php
declare(strict_types=1);

namespace J\FS;

use \InvalidArgumentException;
use phpDocumentor\Reflection\Types\This;

abstract class AnyFile implements File
{
    protected $subjectFile;

    protected $fullName;   // full full name with extension myfile.text.php, yourfile.best.3.php
    protected $dirName;    // dir name without trailing slash
    protected $extension;  // only last extension from a multi-extension file
    protected $coreName;   // name of the file excluding extension


    public function __construct(string $file)
    {
        if(empty($file))
        {
            throw new InvalidArgumentException("Given file name '$file'  is empty", 20000);
        }
        if(is_dir($file))
        {
            throw new InvalidArgumentException("Given file path '$file' is not a file but it is a directory", 20001);
        }
        if(!is_file($file))
        {
            throw new InvalidArgumentException("Given file '$file' doesn't exist", 20002);
        }

        $this->subjectFile = $file;
    }

    public function dir(): string
    {
        $this->getInfo();
        return $this->dirName;
    }

    public function extension(): string
    {
        $this->getInfo();
        return $this->extension;
    }

    public function size(): int
    {
        $this->getInfo();
        // TODO: Implement size() method.
    }

    public function name(): string
    {
        $this->getInfo();
        return $this->fullName;
    }

    public function coreName(): string
    {
        $this->getInfo();
        return $this->coreName;
    }

    private function getInfo(): bool
    {
        if(!$this->fullName){
            $fileInfo = pathinfo($this->subjectFile);   //     /www/htdocs/inc/lib.inc.php
            $this->dirName = $fileInfo['dirname'];      //     /www/htdocs/inc
            $this->fullName = $fileInfo['basename'];    //     lib.inc.php
            $this->extension = $fileInfo['extension'];  //     php
            $this->coreName = $fileInfo['filename'];    //     lib.inc
        }
        return true;
    }

    public function mime(): string
    {
        return mime_content_type($this->subjectFile);
    }

    public function fullPath(): string
    {
        return $this->subjectFile;
    }
}