<?php

namespace PDO\Mysqli;

use PDO\Mysqli\Mysqli;


require __DIR__. '/../../vendor/autoload.php';

$mysql       = new Mysqli( "localhost","homestead","homestead","secret" );
$mysqlResult = $mysql->query("select id from users");

if($mysql->hasErrors()){
  print_r($mysql->getErrors());
}


echo "users: ".$mysql->affected_rows;
print_r($mysqlResult->fetch_fields());
print_r($mysqlResult->fetch_array());
print_r($mysqlResult->fetch_assoc());
print_r($mysqlResult->fetch_object());

while($row = $mysqlResult->fetch_assoc()){
    print_r($row);
}
print_r($mysqlResult->fetch_all());

try{
    $mysql->error_list;
}catch(\Exception $e){
    echo "\nmethod not found\n";
}

$mysql->close();
print_r($mysql);
