### backup-db

Easily Backup your Database.


An example to use

> change `localhost`, `username`, `password` and `databaseName`

```php

<?php
use Ronijan\BackupDatabase\Backup;

require_once 'vendor/autoload.php';

$backup = new Backup('localhost', 'ro', 'root', 'blog');

// default is empty. If the path is not given, then it'll be saved in the root of your Project
$backup->start('sql/');
```
