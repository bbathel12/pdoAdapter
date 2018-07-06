<?php

namespace PDO\Mysqli;

use PDO\Mysqli\Mysqli;

require __DIR__."/vendor/autoload.php";

$mysql = new Mysqli( "localhost","homestead","homestead","secret" );

$mysql->query("select * from users");
echo $mysql->affectedRows;
