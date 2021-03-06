<?php

namespace PDO\Mysqli;

use PDOAdapter\Connector;

class Mysqli extends Connector{
    
    use ToCamelCase;

    protected $driver = "mysql";
    protected $info;// this contains all variables from the client
    protected $pdo;

    public $lastResponse;// gotta store this if I want some of the mysql properties to work ¯\_(ツ)_/¯

    /*
     * hook from pdoadapter to set the properties object
     * to replace all properties with functions
     */
    protected function setProperties(){
        $this->properties = new Properties();
    }

    /*
     * this will be used to intercept property requests
     * that have been replaced by methods. It takes snake_case
     * properties converts them to camelCase method names
     * @param string $propertyName, the name to be converted to method call
     * @return whatever the method returns
     * @throws \Exception "Property Does Not Exist" if there is no method
     * replacing that property or there never was a property.
     */
    public function __get($propertyName)
    {
       return $this->properties->get($propertyName,$this);
    }

    public function __call($functionName,$args)
    {
        $methodName = $this->convertToCamelCase($functionName);
        if (method_exists($this,$methodName)){
            return $this->$methodName(...$args);
        }
    }

    public function getPdo()
    {
        return $this->pdo;
    }


    /*
     * Run an sql query on current pdo connection.
     * It also unfortunatly sets the return value
     * to $this->lastResponse to be able to respond
     * to some of the adapted functions.
     *
     * @param String $query, the sql statement to be run
     * @return MysqliResult, the result of the query
     */
    public function query(String $query): Result
    {
        try{
            $result = $this->pdo->query($query);
        }catch(\Exception $e){
            $result = false;
        }
        $this->lastResponse = (!empty($result)) ? new Result( $result ) : new Result();
        return $this->lastResponse;
    }

    /*
     * set the charset of the db
     * @param string $charset
     * @return bool true on success false on failure
     */
    public function setCharset(string $charset="utf-8")
    {
        return $this->pdo->exec("set names $charset") === 0;
    }

    public function realEscapeString(string $sql)
    {
        return $this->pdo->quote($sql);
    }


    public function hasErrors():bool
    {
        return $this->pdo->errorCode() !== self::NO_ERROR_STATE;
    }

    public function getErrors()
    {
        return $this->pdo->errorInfo();
    }


}

