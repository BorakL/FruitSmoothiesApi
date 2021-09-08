<?php
namespace App\Core;
class DatabaseConnection{
    private $connection;
    private $configuration;
    public function __construct(DatabaseConfiguration $configuration){
        $this->configuration=$configuration;
    }
    public function getConnection():\PDO{
        if($this->connection===null){
            try{
                $this->connection =  new \PDO($this->configuration->getSourceString(),
                                              $this->configuration->getUser(),
                                              $this->configuration->getPassword());
                $this->connection->setAttribute( \PDO::ATTR_ERRMODE, \PDO::ERRMODE_WARNING );
                echo "Connected successfully";
            }
            catch(\PDOException $e){
                echo "Connection failed: " . $e->getMessage();
            }
        }

        return $this->connection;   
    }
        
}

?>