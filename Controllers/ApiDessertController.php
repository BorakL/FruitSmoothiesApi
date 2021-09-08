<?php
namespace App\Controllers;

class ApiDessertController extends \App\Core\ApiController{
    public function show(int $id){
        $dessertModel = new \App\Models\DessertModel($this->getDatabaseConnection());
        $dessert = $dessertModel->getById($id);

        if(!$dessert){
            $this->set("message","Dessert not found");
            return;
        }

        $this->set("name",$dessert->name);
        $this->set("category",$dessert->category_id);
        $this->set("intro",$dessert->intro);
        $this->set("recipe",$dessert->recipe);
        $this->set("photo",$dessert->photo);
        $this->set("video",$dessert->photo); 

        $assessmentModel = new \App\Models\AssessmentModel($this->getDatabaseConnection());
        $rating = $assessmentModel->getDessertRating($id);
        $this->set("rating",$rating->rating);

        $authorModel = new \App\Models\UserModel($this->getDatabaseConnection());
        $author = $authorModel->getById($dessert->user_id);
        $this->set("author",$author);

        $infoModel = new \App\Models\InfoModel($this->getDatabaseConnection());
        $info = $infoModel->getByFieldName("dessert_id",$id);
        $this->set("info",$info);

        $nutritionFactsModel = new \App\Models\NutritionFactModel($this->getDatabaseConnection());
        $nutritionFacts = $nutritionFactsModel->getByFieldName("dessert_id",$id);
        $this->set("nutritionFacts",$nutritionFacts);

        $tagModel = new \App\Models\TagModel($this->getDatabaseConnection());
        $tags = $tagModel->getAllByDessertId($id);
        $this->set("tags",$tags);

        $ingredientModel = new \App\Models\IngredientModel($this->getDatabaseConnection());
        $ingredients = $ingredientModel->getAllByDessertId($id);
        $this->set("ingredients",$ingredients);

        $commentModel = new \App\Models\CommentModel($this->getDatabaseConnection());
        $comments = $commentModel->getAllByDessertId($id);
        $this->set("comments",$comments);

        $dessertViewModel = new \App\Models\dessertViewModel($this->getDatabaseConnection());
        $dessertView_id = $dessertViewModel->add([
            "dessert_id" => $id,
            "ip_address" => $_SERVER["REMOTE_ADDR"]
        ]);
        $dessertViews = count($dessertViewModel->getAllByFieldName("dessert_id",$id));
        $this->set("views", $dessertViews);
    }

}



?>