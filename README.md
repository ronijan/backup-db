### backup-db

Easily Backup your Database.


An example to use

> change `localhost`, `username`, `password` and `databaseName`

```php

<?php

use Ronijan\BackupDatabase\Backup;

require_once 'vendor/autoload.php';


$backup = new Backup('localhost', 'username', 'password', 'db', '*');

$backup->run();
```
