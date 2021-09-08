<?php

namespace App\Controllers;

class AdminDashboardController extends \App\Core\Role\AdminRoleController{
    public function addDessert(){
        $categoryModel = new \App\Models\CategoryModel($this->getDatabaseConnection());
        $categories = $categoryModel->getAll();
        $this->set("categories",$categories);

        $authorModel = new \App\Models\AuthorModel($this->getDatabaseConnection());
        $authors = $authorModel->getAll();
        $this->set("authors",$authors);

        $tagModel = new \App\Models\TagModel($this->getDatabaseConnection());
        $tags = $tagModel->getAll();
        $this->set("tags",$tags);

        $ingredientCategoryModel = new \App\Models\IngredientCategoryModel($this->getDatabaseConnection());
        $ingredientCategories = $ingredientCategoryModel->getAll();
        $this->set("ingredientCategories", $ingredientCategories);

        $ingredientModel = new \App\Models\IngredientModel($this->getDatabaseConnection());
        $ingredients = $ingredientModel->getAll();
        $this->set("ingredients",$ingredients);
    }





    
    public function postAddDessert(){ 
        //Dessert
        $name = $this->myFilterInput(filter_input(INPUT_POST,"name",FILTER_SANITIZE_STRING));
        $intro = $this->myFilterInput(filter_input(INPUT_POST,"intro",FILTER_SANITIZE_STRING));
        $recipe = $this->myFilterInput(filter_input(INPUT_POST,"recipe",FILTER_SANITIZE_STRING));
        
        //Category
        $category = $this->myFilterInput(filter_input(INPUT_POST,"category_id",FILTER_SANITIZE_NUMBER_INT));

        //Author
        $author = $this->myFilterInput(filter_input(INPUT_POST,"author_id",FILTER_SANITIZE_NUMBER_INT));

        //Info
        $info_time = $this->myFilterInput(filter_input(INPUT_POST,"info_time",FILTER_SANITIZE_NUMBER_INT));
        $info_servings = $this->myFilterInput(filter_input(INPUT_POST,"info_servings",FILTER_SANITIZE_NUMBER_INT));

        //Nutrition facts
        $nutrition_fact_calories = $this->myFilterInput(filter_input(INPUT_POST,"nutrition_fact_calories",FILTER_SANITIZE_NUMBER_INT));
        $nutrition_fact_protein = $this->myFilterInput(filter_input(INPUT_POST,"nutrition_fact_protein",FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION));
        $nutrition_fact_carbohydrates = $this->myFilterInput(filter_input(INPUT_POST,"nutrition_fact_carbohydrates",FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION));
        $nutrition_fact_fats = $this->myFilterInput(filter_input(INPUT_POST,"nutrition_fact_fat",FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION));
        $nutrition_fact_sugars = $this->myFilterInput(filter_input(INPUT_POST,"nutrition_fact_sugars",FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION));
        
        //Tags  
        $tags = $this->filterArray("tags");

        //Ingredients
        $ingredients = $this->filterArray("ingredients");
        

        $strings = [$name,$intro,$recipe];
        $integers = [$category, $author, $info_time, $info_servings, $nutrition_fact_calories];
        $decimals = [$nutrition_fact_protein, $nutrition_fact_carbohydrates, $nutrition_fact_fats, $nutrition_fact_sugars];

        if($this->checkFieldsValues($strings,$integers,$decimals,$tags,$ingredients) ){
            header("Location: /adminProfile");
        }
        

 

        ///////////////////////////////////////////// INSERTS /////////////////////////////////////////////
        
        $dessertModel = new \App\Models\DessertModel($this->getDatabaseConnection());
        $lastDessertId = $dessertModel->add([
                        "name"=>$name,
                        "intro"=>$intro,
                        "recipe"=>$recipe,
                        "category_id"=>$category,
                        "author_id"=>$author
                    ]);
        if(!$lastDessertId){header("Location: /adminProfile");};

        $infoModel = new \App\Models\InfoModel($this->getDatabaseConnection());
        $infoModel->add([
                        "dessert_id"=>$lastDessertId, 
                        "time"=>$info_time, 
                        "servings"=>$info_servings
                    ]);

        $nutritionFactsModel = new \App\Models\NutritionFactsModel($this->getDatabaseConnection());
        $nutritionFactsModel->add([
                        "dessert_id"=>$lastDessertId,
                        "calories"=>$nutrition_fact_calories,
                        "protein"=>$nutrition_fact_protein,
                        "carbotydrates"=>$nutrition_fact_carbohydrates,
                        "fat"=>$nutrition_fact_fats,
                        "sugars"=>$nutrition_fact_sugars
                    ]);
        
        $tagDessertModel = new \App\Models\TagDessertModel($this->getDatabaseConnection());
        for($i=0; $i<count($tags); $i++){
            $tagDessertModel->add([
                    "tag_id"=>$tags[$i],
                    "dessert_id"=>$lastDessertId,
                ]);
        }

        $makingModel = new \App\Models\MakingModel($this->getDatabaseConnection());
        foreach($ingredients as $k=>$v){
            $makingModel->add([
                    "dessert_id"=>$lastDessertId,
                    "ingredient_id"=>$k,
                    "quantity"=>$v
                ]);
        } 

    }


    //////////////////////////////////////////////////////////////////////////////////////////////////////


    

    private function checkFieldsValues(array $integers, array $strings, array $decimals, array $tags, array $ingredients):bool{
        foreach($integers as $key => $value) {
            if(!is_int($value) || $value<=0) return true;
        }
        foreach($strings as $key=>$value){
            if(!is_string($value) || $value==="") return true;
        }
        foreach ($decimals as $key => $value) {
            if(!is_numeric($value) || $value<0) return true;
        }
        foreach($tags as $key=>$value){
            if(!is_int($value) || $value<0) return true;
        }
        foreach($ingredients as $key=>$value){
            if((!is_string($key) || $key==="") || ($value==="")) return true;
        }
        return false;
    }

}