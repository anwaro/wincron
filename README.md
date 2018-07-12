WinCron
===============
Run cron job on Windows platform

Installation
------------

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
php composer.phar require --prefer-dist anwaro/wincron "*"
```
or

```
composer require --prefer-dist anwaro/wincron "*"
```

or add

```
"anwaro/wincron": "*"
```

to the require section of your `composer.json` file.


Usage
-----



```php
<?php
use anwaro\wincron\Cron;

$config = [
    'path' => __DIR__, // path to file with cron job list
    'file' => 'crontab.txt' // file with cron job list
];

$cron = new Cron($config);
$cron->run();

```

