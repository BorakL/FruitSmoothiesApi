<?php

namespace App\Controllers;

class CategoryController extends \App\Core\Controller{
    public function show($id){
        $dessertModel = new \App\Models\DessertModel($this->getDatabaseConnection());
        $desserts = $dessertModel->getAllByFieldName("category_id",$id);
        $this->set("desserts",$desserts);
    }

    
}