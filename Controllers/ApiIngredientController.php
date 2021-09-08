<?php
namespace App\Controllers;

class ApiIngredientController extends \App\Core\ApiController{
    public function getIngredients(int $categoryId){
        $ingredientModel = new \App\Models\IngredientModel($this->getDatabaseConnection());
        $ingredients = $ingredientModel->getAllByFieldName("ingredient_category_id",$categoryId);
        $this->set("ingredients",$ingredients);
    }
    public function getAllCategories(){
        $categoriesModel = new \App\Models\IngredientCategoryModel($this->getDatabaseConnection());
        $categories = $categoriesModel->getAll();
        $this->set("categories",$categories);
    }
    public function getIngredient(string $ingredientName){
        $ingredientModel = new \App\Models\IngredientModel($this->getDatabaseConnection());
        $ingredient = $ingredientModel->getByFieldName("name",$ingredientName);
        $this->set("ingredient", $ingredient);
    }
}


?>