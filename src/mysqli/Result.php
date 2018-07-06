<?php

namespace PDO\Mysqli;

use \PDO;
use \PDOStatement;

class Result{

    use ToCamelCase;

    private $result;
    const FETCH_NUM    = PDO::FETCH_NUM; //returns enumerated array
    const FETCH_ASSOC  = PDO::FETCH_ASSOC; //returns associative array
    const FETCH_BOTH   = PDO::FETCH_BOTH;// - both of the above
    const FETCH_OBJ    = PDO::FETCH_OBJ; //returns object
    const FETCH_LAZY   = PDO::FETCH_LAZY;
    const FETCH_COLUMN = PDO::FETCH_COLUMN; // gets column names

    public function __construct(?PDOStatement $stmt=null)
    {
        $this->result = $stmt;
    }

    public function __call(string $methodName, array $args)
    {
        $correctedMethodName = $this->convertToCamelCase($methodName);
        if (method_exists($this,$correctedMethodName)){
            return $this->$correctedMethodName();
        }else {
            throw new Exception("Method does not exist $methodName");
        }
    }

    /*
     * the number of rows returned by the query
     * @return int
     */
    public function count():int
    {
        return $this->result->rowCount();
    }

    public function map(callable $func)
    {
        $toReturn = [];
        if( $this->result != null){
            foreach( $this->fetchArray() as $item ){
                $toReturn = $func($item);
            }
        }
        return $toReturn;
    }

    /*
     * generator that returns the result of the pdo statement
     * so you can use foreach instead of stupid while syntax
     */
    public function fetchIterable(int $fetchMode = self::FETCH_ASSOC)
    {
        if( $this->result != null){
            while( $item = $this->result->fetch( $fetchMode )){
                yield $item;
            }
        }else{
            return [];
        }
    }

    /*
     * fetch all rows as an associative array, can set fetchmode to
     * return other types of arrays
     * @param int $fetchmode, one of the fetchmodes set as consts on this class
     * @return array, by default an associative array
     */
    public function fetchArray(int $fetchMode = self::FETCH_BOTH)
    {
        if ($row = $this->result->fetch($fetchMode)){
            return (array) $row;
        }else{
            return [];
        }
    }

    /*
     * fetch all rows as an associative array, can set fetchmode to
     * return other types of arrays
     * @param int $fetchmode, one of the fetchmodes set as consts on this class
     * @return array, by default an associative array
     */
    public function fetchAssoc()
    {
        if ($row = $this->result->fetch(self::FETCH_ASSOC)){
            return $row;
        }else{
            return false;
        }
    }

    /*
     * fetches all rows default as both numeric and associative
     * @param int $fetchMode, the type to fetch i.e. associative,numerical, and both
     * @return array
     */
    public function fetchAll($fetchMode = self::FETCH_BOTH)
    {
        if (!is_null($this->result)){
            return $this->result->fetchAll($fetchMode);
        }else{
            return [];
        }
    }

    /*
     * fetches all rows as an object
     * @param $class_name stdClass to return
     * @param array $params, arguments to be passed to class constructor
     * @return array
     */
    public function fetchObject($className = 'stdClass',array $params= [])
    {
        if ($row = $this->result->fetch(self::FETCH_ASSOC)){
            return (object) $row;
        }else{
            return false;
        }
    }

    /*
     * returns all the column names in an array
     * @return array of the column names
     */
    public function fetchFields(){
        if(!is_null($this->result)){
            return array_keys($this->result->fetch(self::FETCH_ASSOC));
        }else{
            return [];
        }
    }

}


