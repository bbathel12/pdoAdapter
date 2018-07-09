<?php

namespace PDO\Mysqli;

use PDOAdapter\Properties as AbstractProperties;


class Properties extends AbstractProperties{

    use ToCamelCase;

    public function get($propertyName,$object)
    {
        $methodName = $this->convertToCamelCase($propertyName);
        if (method_exists($this,$methodName)){
            return $this->$methodName($object);
        }else{
            throw new \Exception("Property Does Not Exist $methodName");
        }
    }

    /*
     * gets the mysql client variables and sets
     * them to the info property. This uses the the pdo
     * query method directly to avoid setting the lastresponse
     * property, because that would break many of the getter
     * functions that replace mysqli properties. This will only
     * make the query once, if the info property is already set
     * it does nothing.
     * @return void
     */
    private function getMysqlInfo($object):void
    {
        if (empty($this->info)){
            $result = new MysqliResult($object->pdo->query("Show Variables"));
            $object->info = $result->fetchArray();
        }
    }


    // properties that need to be rewritten as functions
    private function affectedRows($object):int
    {
        return $object->lastResponse->count();
    }

    private function numRows($object):int
    {
        return $object->lastResponse->count();
    }

    private function connectErrno($object):string
    {
        return $object->pdo->errorCode();
    }

    private function connectError($object):string
    {
        return $object->pdo->errorInfo();
    }

    private function errno($object):string
    {
        return $object->lastResponse->errorCode();
    }

    private function error($object):string
    {
        return $object->lastResponse->errorInfo()[1];
    }

    /*
     * throws an exception I don't believe
     * there is a PDO method for this
     */
    private function errorList($object):void
    {
        throw new \Exception("Property Not Found On Adapter");
    }

    /*
     * @return int, number of columns in the response
     */
    private function fieldCount($object):int
    {
        return $object->lastResponse->columnCount();
    }

    /*
     * @return string, version number of the database
     */
    private function clientInfo($object):string
    {
        $object->getClientInfo();
        return $object->info['Version'];
    }

    /*
     * A number that represents the MySQL client library version in format:
     * main_version*10000 + minor_version *100 + sub_version.
     * For example, 4.1.0 is returned as 40100.
     * @return int, mysql version number as an integer
     */
    private function clientVersion($object):int
    {
        $object->getClientInfo();
        $versionParts = explode('.',$object->info['version']);
        $versionPartInts = array_map(
            function($versionPart){
                return intval($versionPart);
            },
            $versionParts
        );

        $versionInt = $versionPartInts[0] * 10000;
        $versionInt += $versionPartInts[1] * 100;
        $versionInt += $versionPartInts[2];

        return $versionInt;
    }

    /*
     * gets info about the host
     * @return string host info
     */
    private function hostInfo($object):string
    {
        return "Host Info: {$object->host} via Unix socket";
    }


    /*
     * get the protocol version
     * @return int, the mysql variable called protocol version
     */
    private function protocolVersion($object):int
    {
        $object->getClientInfo();
        return $object->info['protocol_version'];
    }

    private function serverInfo($object):string
    {
    }

    private function info($object):string
    {
    }

    private function insertId($object):string
    {
        $object->lastInsertId();
    }

    private function sqlstate($object)
    {
    }

    private function threadId($object)
    {
        $result = new MysqliResult($object->pdo->query("SELECT CONNECTION_ID() as id"));
        $threadId = $result->fetchArray()['id'];
        return $threadId;
    }

    private function warningCount($object)
    {
    }

}
