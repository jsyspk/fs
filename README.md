# FileSystem for PHP 7.4+

This is a simple oop library for php 7.4 or above

## Why?

A lot is happening in the php world right now but there are some fundamental issues that forced the creation of this lib and the ones that are going to follow.

- capitalization of file names and directories
- adding directories just for the sake of composer
- adding namespaces and title casing just for the composer
- interfaces just for the sake of interface

Composer has done a great job but it has also killed the namesapcing feature the way it should be used.

This lib is just a small step towards a better and cleaner php-world.  


## Key features
- Simplicity
- OOP
- DDD
- DRY

## How to install

`composer require j/fs`

## How to use

Readable file

```
use J\FS;

$rf = new ReadableFile("/some/readable/file.sample.txt");

// do whatever you want to do with this file 

$rf->extesnion(); // will get txt

$rf->fullName(); // will get the file.sample.txt

$rf->mime(); // will get the mime, like text/plain


```

## Join us
Please give us a hand in creating a better php community
