<?php
namespace App\Controllers;

class ApiCategoryController extends \App\Core\ApiController{

    public function show(int $id=null){
        
        $params = $this->getParameters();

        $categoryId = empty($id) ? null : $id;
        $sort = empty($params["sort"]) ? "" : $params["sort"];
        $order = empty($params["order"]) ? "" : $params["order"];
        $limit = empty($params["items_num"]) ? "" : $params["items_num"];
        $author = empty($params["author"]) ? "" : $params["author"];
        $ingInc = (empty($params["ingInc"])||!is_array($params["ingInc"])) ? [] : $params["ingInc"];
        $ingExcl = (empty($params["ingExcl"])||!is_array($params["ingExcl"])) ? [] : $params["ingExcl"];

        $dessertModel = new \App\Models\DessertModel($this->getDatabaseConnection());
        $desserts = $dessertModel->getDesserts($categoryId,$sort,$order,$limit,$author,$ingInc,$ingExcl);
        $this->set("desserts",$desserts); 
    }

}