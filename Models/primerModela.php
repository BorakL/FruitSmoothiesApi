<?php
// namespace App\Models;

// class AuctionModel extends \App\Core\Model{
    // private $dbc;
    // public function __construct(\App\Core\DatabaseConnection $dbc){
        // $this->dbc = $dbc;
    // }
    // public function getAll():array{
        // $conn=$this->dbc->getConnection();
        // $sql="SELECT * FROM auction;";
        // $prep = $conn->prepare($sql);
        // $result = $prep->execute();
        // $auctions=[];
        // if($result){
            // $auctions = $prep->fetchAll(\PDO::FETCH_OBJ);
        // }
        // return $auctions;
    // }

    // public function getById(int $id){
        // $conn=$this->dbc->getConnection();
        // $sql= 'SELECT * FROM auction WHERE auction_id = ?;';
        // $prep = $conn->prepare($sql);
        // $result = $prep->execute([$id]);
        // $auction=null;
        // if($result){
            // $auction = $prep->fetch(\PDO::FETCH_OBJ);
        // }
        // return $auction;
    // }

    // public function getByName(string $name){
        // $conn = $this->dbc->getConnection();
        // $sql = "SELECT * FROM auction WHERE name=?;";
        // $prep = $conn->prepare($sql);
        // $result = $prep->execute([$name]);
        // $name=null;
        // if($result){
            // $name=$prep->fetch(\PDO::FETCH_OBJ);
        // }
        // return $name;
    // }

    // public function getAllByCategoryId(int $categoryId):array{
        // $conn=$this->dbc->getConnection();
        // $sql="SELECT * FROM auction WHERE category_id = ?;";
        // $prep = $conn->prepare($sql);
        // $result = $prep->execute([$categoryId]);
        // $auctions=[];
        // if($result){
            // $auctions = $prep->fetchAll(\PDO::FETCH_OBJ);
        // }
        // return $auctions;
    // }
// }
?>