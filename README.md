# citeprocessor #
[![Build Status](https://travis-ci.org/mettio/citeprocessor.svg?branch=master)](https://travis-ci.org/mettio/citeprocessor)
[![License](https://img.shields.io/badge/license-GPLv3-blue.svg?style=flat-square)](https://bitbucket.org/bibsonomy/citeproc-php/raw/default/license.txt)
[![PHP](https://img.shields.io/badge/PHP-%3E=5.4-green.svg?style=flat-square)](http://docs.php.net/manual/en/migration53.new-features.php)

**Description**

This is an another effort to implement a standalone CSL processor in PHP. This program can be used to render bibliographies using [CSL](http://citationstyles.org/) Stylesheets. This repository is a fork of the [implementation of Sebastian Böttger](https://bitbucket.org/seboettg/citeproc-php/) which is a fork of the [implementation of rjerome](https://bitbucket.org/rjerome/citeproc-php) (apparently no longer maintained).

I renamed it to citeprocessor because of use it for personal purposes.

Some advantages:

* uses Composer
* each class is located in a separate file
* uses namespaces
* uses the autoloader of Composer
* uses PHPUnit for testing
* better integration

## Installing citeprocessorp using Composer ##

Use Composer to add citeprocessor to your app by editing your composer.json:

```
    "repositories": [
        {
            "type": "vcs",
            "url": "git@github.com:mettio/citeprocessor.git"
        }
    ],

...

    "require": {
        ...
        "mett/citeproc":"*"
    },
```
After that, run ``composer update`` in your project root directory.


## How to use it ##

```
<?php
include 'vendor/autoload.php';
use \Mett\CiteProc\CiteProc;

$bibliographyStyleName = 'apa';
$lang = "en-US";

$csl = CiteProc::loadStyleSheet($bibliographyStyleName); // xml code of your csl stylesheet

$citeProc = new CiteProc($csl, $lang);

// $data is a JSON encoded string
echo $citeProc->render(json_decode($data));
?>
```

## How to run the tests ##

Run phpunit in the project root and you will be fine.
