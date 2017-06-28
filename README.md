# shmcache

> A lightweight, abstract, scalable, out-of-the-box shared memory operation, makes use of the PHP Shared Memory Functions([shmop](https://secure.php.net/manual/en/ref.shmop.php)).

[![Latest Stable Version](https://img.shields.io/packagist/v/windomz/shmcache.svg?style=flat-square)](https://packagist.org/packages/windomz/shmcache)
[![Build Status](https://img.shields.io/travis/WindomZ/shmcache/master.svg?style=flat-square)](https://travis-ci.org/WindomZ/shmcache)
[![Minimum PHP Version](https://img.shields.io/badge/php-%3E%3D%205.3-8892BF.svg?style=flat-square)](https://php.net/)

## Features

- [x] Class [shmop](https://github.com/WindomZ/shmcache/blob/master/src/shmop.php) - _Abstract_ and _scalable_ make use of the [shmop](https://secure.php.net/manual/en/ref.shmop.php).
- [x] Class [Block](https://github.com/WindomZ/shmcache/blob/master/src/Block.php) - **Key-value** pairs and **timeout** implement
- [x] Class [Cache](https://github.com/WindomZ/shmcache/blob/master/src/Cache.php) - Extends `Block`, _lightweight_ and _Out-of-the-box_

## Usage

The idea behind **shmcache** is to keep _easy_ to use and _flexibility_.

Provide key-value pairs of functions, more extensibility and convenience.

```php
<?php
use SHMCache\Block;
use SHMCache\Cache;

// Use by Block, extends \SHMCache\shmop
$memory = new Block;
$memory->save('key1', 'value1');
$memory->save('key2', 'value2');
echo $memory->get('key1');
echo $memory->get('key2');

// Or use by Cache, same as Block, can not need to new it.
Cache::saveCache('key1', 'value1');
Cache::saveCache('key2', 'value2');
echo Cache::getCache('key1');
echo Cache::getCache('key2');
```

## Development

Welcome your **Star**, make pull requests, report bugs, suggest ideas and discuss **shmcache**.

I would love to hear what you think about **shmcache** on [issues page](https://github.com/WindomZ/shmcache/issues).

## License

[MIT](https://github.com/WindomZ/shmcache/blob/master/LICENSE)
