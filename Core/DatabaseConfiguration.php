<?php
namespace App\Core;
class DatabaseConfiguration {
    private $host;
    private $user;
    private $password;
    private $dbName;
    public function __construct($host,$user,$password,$dbName){
        $this->host=$host;
        $this->user=$user;
        $this->password=$password;
        $this->dbName=$dbName;
    }
    public function getSourceString():string{
        return "mysql:host={$this->host};dbname={$this->dbName}; charset=utf8";
    }
    public function getUser():string{
        return $this->user;
    }
    public function getPassword():string{
        return $this->password;
    }
}

?>