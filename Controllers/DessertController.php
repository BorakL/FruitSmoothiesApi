<?php
namespace App\Controllers;

class DessertController extends \App\Core\Controller{
    public function show($name){
        $dessertModel = new \App\Models\DessertModel($this->getDatabaseConnection());
        $dessert = $dessertModel->getByFieldName("name",$name);
        $this->set("dessert",$dessert);
    }
}