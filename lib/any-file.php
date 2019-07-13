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
        if(!is_file($file))
        {
            throw new InvalidArgumentException("Given name $file is not a valid file", 20001);
        }
        $this->getInfo();
        $this->subjectFile = $file;
    }

    public function dir(): string
    {
        return $this->dirName;
    }

    public function extension(): string
    {
        return $this->extension;
    }

    public function size(): int
    {
        // TODO: Implement size() method.
    }

    public function name(): string
    {
        return $this->fullName;
    }

    public function coreName(): string
    {
        return $this->coreName;
    }

    private function getInfo()
    {
        $fileInfo = pathinfo($this->subjectFile);   //     /www/htdocs/inc/lib.inc.php
        $this->dirName = $fileInfo['dirname'];      //     /www/htdocs/inc
        $this->fullName = $fileInfo['basename'];    //     lib.inc.php
        $this->extension = $fileInfo['extension'];  //     php
        $this->coreName = $fileInfo['filename'];    //     lib.inc
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