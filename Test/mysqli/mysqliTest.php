<?php

namespace PDO\Tests\Mysqli;

// library classes actually being tested
use PDO\Mysqli\Mysqli;

// PHPUnit classes that run tests
use PHPUnit\Framework\TestCase;
use PHPUnit\DbUnit\TestCaseTrait;
use PHPUnit\DbUnit\DataSet\QueryDataSet;

class mysqliTest extends TestCase{

    use TestCaseTrait;

    private $mysql = null;

    public function getConnection()
    {
        if (is_null($this->mysql)){
            $this->mysql = new Mysqli( "localhost","homestead","homestead","secret" );
            $this->conn = $this->createDefaultDbConnection($this->mysql->getPDO(),"zd_test");
        }
        return $this->conn;
    }

    public function getDataSet()
    {
        return $this->createMySQLXMLDataSet(__DIR__.'/../Fixtures/testDb.xml');
    }

    public function testQuery()
    {
        $conn = $this->getConnection();
        $this->assertsame(19, $this->getconnection()->getrowcount('users'), "pre-condition");
        $result = $this->mysql->query("insert into users (name,email) values( 'testuser','testuser@gmail.com')");
        $this->assertsame(20, $this->getconnection()->getrowcount('users'), "post-condition");

    }

    public function testHasAndGetErrorsWithError()
    {
        $conn = $this->getConnection();
        $result = $this->mysql->query("select from users");
        // test hasErrors
        $this->assertTrue($this->mysql->hasErrors());
        // test getErrors();
        $this->assertContains(1064,$this->mysql->getErrors());
    }

    public function testHasAndGetErrorsWithoutError()
    {
        $conn = $this->getConnection();
        $result = $this->mysql->query("select * from users");
        // test hasErrors
        $this->assertFalse($this->mysql->hasErrors());
        // test getErrors();
        $this->assertNotContains(1064,$this->mysql->getErrors());
    }
}
