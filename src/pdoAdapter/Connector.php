<?php

namespace PDOAdapter;

use \PDO;

abstract class Connector {

    protected $host,$db,$user,$pass,$dsn = "";
    protected $driver; // this must be set in the subclass to the driver string
    protected $properties;
    protected $pdo;

    const NO_ERROR_STATE = "00000";

    public function __construct($host,$db,$user,$pass)
    {
        $this->host = $host;
        $this->db   = $db;
        $this->user = $user;
        $this->pass = $pass;
        $this->buildDsn($host,$db);
        $opt =[
             PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_BOTH,
             PDO::ATTR_ERRMODE            => PDO::ERRMODE_SILENT,
		];
        $this->pdo = new PDO($this->dsn,$this->user,$this->pass);

        if (method_exists($this,"setProperties")){
            $this->setProperties();
        }


    }

    /*
     * Sets pdo property to null which is the suggested
     * way to close a pdo connections
     */
    public function close():void{
        $this->pdo = null;
    }


    // PRIVATE METHODS

    /*
     * builds and sets pdo dsn for various drivers
     */
    private function buildDsn($host,$db){
        $this->dsn = "{$this->driver}:host={$this->host};dbname={$this->db};";
    }

}

