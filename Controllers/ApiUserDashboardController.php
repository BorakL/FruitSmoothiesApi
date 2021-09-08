<?php
    namespace App\Controllers;
    class ApiUserDashboardController extends \App\Core\Role\ApiUserRoleController{
        public function show(){
            if(!isset($this->decoded->data->username)){
                $this->set("message","Error: access denied");
                return;
            }
            $username = $this->decoded->data->username;
            
            $userModel = new \App\Models\UserModel($this->getDatabaseConnection());
            $user = $userModel->getByFieldName("username",$username);
            $this->set("forename",$user->forename);
            $this->set("surname",$user->surname);
            $this->set("username",$user->username);
            $this->set("birthYear",$user->birthYear);

            $dessertModel = new \App\Models\DessertModel($this->getDatabaseConnection());
            $favoriteDesserts = $dessertModel->getFavoriteDessertsByUserName($username);
            $this->set("favoriteDesserts",$favoriteDesserts); 
        }



///////////////////////////////////////////////////////////////////////////////////////////////


        public function postComment(){
            if(!isset($this->decoded->data->user_id)){
                $this->set("message","Error: incorect user_id");
                return;
            }
            $user_id = $this->decoded->data->user_id;
            $data = json_decode(file_get_contents('php://input'),true);
            
            $comment = filter_var($data["comment"],FILTER_SANITIZE_STRING);
            $dessert_id = filter_var($data["dessert_id"],FILTER_SANITIZE_NUMBER_INT);

            if(!$comment || !$user_id || !$dessert_id){
                $this->set("message","comment: $comment, user: $user_id, dessert: $dessert_id");
                return;
            }
            $userModel = new \App\Models\UserModel($this->getDatabaseConnection());
            $user = $userModel->getById($user_id);
            $dessertModel = new \App\Models\DessertModel($this->getDatabaseConnection());
            $dessert = $dessertModel->getbyId($dessert_id);
            if(!$user || !$dessert){
                $this->set("message","Error: incorect user or dessert");
                return;
            }
            $commentModel = new \App\Models\CommentModel($this->getDatabaseConnection());
            $comment_id = $commentModel->add([
                "content"=>$comment,
                "user_id"=>$user_id,
                "dessert_id"=>$dessert_id
            ]);
            if(!$comment_id){
                $this->set("message","Error: add comment failed");
                return;
            }
            else{
                $this->set("message","Comment added successfully");
            }
        }



    ///////////////////////////////////////////////////////////////////////////////////////////////////



        public function postAssessment(){
            if(!isset($this->decoded->data->user_id)){
                $this->set("message","Error: incorect user_id");
                $this->set("success",false);
                return;
            }
            $user_id = $this->decoded->data->user_id;

            $data = json_decode(file_get_contents('php://input'), true);

            $assessmentValue = filter_var($data["assessment"], FILTER_SANITIZE_NUMBER_INT);
            $dessert_id = filter_var($data["dessert_id"], FILTER_SANITIZE_NUMBER_INT);

            if(!$assessmentValue || !$user_id || !$dessert_id){
                $this->set("message","input error");
                $this->set("success",false);
                return;
            }
            if($assessmentValue>5 || $assessmentValue<1){
                $this->set("message","Error: Incorrect rating value");
                $this->set("success",false);
                return;
            }
            
            $assessmentData = [
                "assessment"=>$assessmentValue,
                "dessert_id"=>$dessert_id,
                "user_id"=>$user_id
            ];

            $assessmentModel = new \App\Models\AssessmentModel($this->getDatabaseConnection());
            $assessment = $assessmentModel->getByFieldsNames(["user_id"=>$user_id, "dessert_id"=>$dessert_id]);
            if(!$assessment){
                $last_assessment = $assessmentModel->add($assessmentData);
                if(!$last_assessment){
                    $this->set("message","Error: add rating failed");
                    $this->set("success",false);
                    return;
                }
            }
            else{
                $assessment_result = $assessmentModel->editById($assessment->assessment_id, $assessmentData);
                if(!$assessment_result){
                    $this->set("message","Error: add rating failed");
                    $this->set("success",false);
                    return;
                }
            }
            $this->set("message", "Rating assignment successfully"); 
            $this->set("success",true);
        }




//////////////////////////////////////////////////////////////////////////////////////////////////////////



        public function addDessert(){
            $categoryModel = new \App\Models\CategoryModel($this->getDatabaseConnection());
            $categories = $categoryModel->getAll();
            $this->set("categories",$categories);
    
            $authorModel = new \App\Models\AuthorModel($this->getDatabaseConnection());
            $authors = $authorModel->getAll();
            $this->set("authors",$authors); 
    
            $ingredientCategoryModel = new \App\Models\IngredientCategoryModel($this->getDatabaseConnection());
            $ingredientCategories = $ingredientCategoryModel->getAll();
            $this->set("ingredientCategories", $ingredientCategories);
    
            $ingredientModel = new \App\Models\IngredientModel($this->getDatabaseConnection());
            $ingredients = $ingredientModel->getAll();
            $this->set("ingredients",$ingredients);
        }


///////////////////////////////////////////////////////////////////////////////////////////////////


        public function postDessertPhoto(){
        $imageName = $_POST["imageName"];
             $uploadStatus = $this->doImageUpload("image",$imageName);
             if(!$uploadStatus){
                return;
             }
        }
    
    
    
///////////////////////////////////////////////////////////////////////////////////////////////////



        public function postAddDessert(){  

            $data = json_decode(file_get_contents('php://input'),true); 
            
            //Dessert
            $name = filter_var($data['name'], FILTER_SANITIZE_STRING);
            $intro = filter_var($data['intro'], FILTER_SANITIZE_STRING);
            $recipe = filter_var($data['recipe'], FILTER_SANITIZE_STRING);
            
            //Category
            $category = filter_var($data['category_id'], FILTER_SANITIZE_NUMBER_INT);
                        
            //Author
            $author = filter_var($data['author_id'], FILTER_SANITIZE_NUMBER_INT);
    
            //Info
            $info_time = filter_var($data['info_time'], FILTER_SANITIZE_NUMBER_INT);            
            $info_servings = filter_var($data['info_servings'], FILTER_SANITIZE_NUMBER_INT);
            
            //Nutrition facts
            $nutrition_fact_calories = filter_var($data['nutrition_fact_calories'], FILTER_SANITIZE_NUMBER_INT);            
            $nutrition_fact_protein = filter_var($data['nutrition_fact_protein'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);                      
            $nutrition_fact_carbohydrates = filter_var($data['nutrition_fact_carbohydrates'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
            $nutrition_fact_fats = filter_var($data['nutrition_fact_fat'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);           
            $nutrition_fact_sugars = filter_var($data['nutrition_fact_sugars'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);           
             
            //Tags  
            //$tags = $this->filterArray($data["tags"]);
            //["tag1","tag2","tag3"...]
    
            //Ingredients
            $ingredients = $data["ingredients"]; 
            //[{ingredient_id:1, quantity:300, optional:"optional", description:"fresh"}...] 
            //ODRADI VALIDACIJU ZA INGREDIENTS!!!

            $strings = [$name,$intro,$recipe];
            $nums  = [$category, $author, $info_time, $info_servings, $nutrition_fact_calories, $nutrition_fact_protein, $nutrition_fact_carbohydrates, $nutrition_fact_fats, $nutrition_fact_sugars];
    
    
            if($this->checkFieldsValues($strings,$nums,$ingredients) ){
                $this->set("success",false);
                $this->set("message","input error");
                return;
            } 

            
            /////// INSERTS ///////////


            $dessertModel = new \App\Models\DessertModel($this->getDatabaseConnection());
            $lastDessertId = $dessertModel->add([
                            "name"=>$name,
                            "intro"=>$intro,
                            "recipe"=>$recipe,
                            "category_id"=>$category,
                            "user_id"=>$author
                        ]);  
            if(!$lastDessertId){
                $this->set("success",false); 
                $this->set("message","Sory, name $name is already used"); 
                return;
            };
      
            
            $makingModel = new \App\Models\MakingModel($this->getDatabaseConnection());
            for($i=0; $i<count($ingredients); $i++){
                $ingredient = $ingredients[$i];
                $lastMakingId = $makingModel->add([
                            "dessert_id"=>$lastDessertId,
                            "ingredient_id"=>$ingredient["ingredient_id"],
                            "quantity"=>$ingredient["quantity"],
                            "optional"=>$ingredient["optional"],
                            "description"=>$ingredient["description"]
                        ]); 
                if(!$lastMakingId){
                    $this->set("success",false); 
                    $this->set("message","Making Error"); 
                    return;
                };
            }


            $infoModel = new \App\Models\InfoModel($this->getDatabaseConnection());
            $lastInfoId = $infoModel->add([ 
                            "time"=>$info_time, 
                            "servings"=>$info_servings,
                            "dessert_id"=>$lastDessertId
                        ]);
            if(!$lastInfoId){
                $this->set("success",false); 
                $this->set("message","error"); 
                return;
            };


            $nutritionFactModel = new \App\Models\NutritionFactModel($this->getDatabaseConnection());
            $lastNutritionFactId = $nutritionFactModel->add([ 
                            "calories"=>$nutrition_fact_calories,
                            "protein"=>$nutrition_fact_protein,
                            "carbotydrates"=>$nutrition_fact_carbohydrates,
                            "fat"=>$nutrition_fact_fats,
                            "sugars"=>$nutrition_fact_sugars,
                            "dessert_id"=>$lastDessertId
                        ]);
            if(!$lastNutritionFactId){
                $this->set("success",false); 
                $this->set("message","error"); 
                return;
            };


            $this->set("success",true);
            $this->set("message","New smoothie has been successfully created");
        }
    
    
///////////////////////////////////////////////////////////////////////////////////////////////////////////
    


        public function deleteDessert(){
            $data = json_decode(file_get_contents('php://input'),true); 
            $id = filter_var(intval($data["id"]), FILTER_SANITIZE_NUMBER_INT); 

            $dessertViewModel = new \App\Models\DessertViewModel($this->getDatabaseConnection());
            $lastDesserViewId = null;
            $lastDesserViewId = $dessertViewModel->deleteByFieldName("dessert_id",$id);

            $commentModel = new \App\Models\CommentModel($this->getDatabaseConnection());
            $lastCommentId = null;
            $lastCommentId = $commentModel->deleteByFieldName("dessert_id",$id);

            $assessmentModel = new \App\Models\AssessmentModel($this->getDatabaseConnection());
            $lastAssessmentId = null;
            $lastAssessmentId = $assessmentModel->deleteByFieldName("dessert_id",$id);

            $makingModel = new \App\Models\MakingModel($this->getDatabaseConnection());
            $lastMakingId = null;
            $lastMakingId = $makingModel->deleteByFieldName("dessert_id",$id);

            $infoModel = new \App\Models\InfoModel($this->getDatabaseConnection());
            $lastInfoId = null;
            $lastInfoId = $infoModel->deleteByFieldName("dessert_id",$id);

            $nutritionFactModel = new \App\Models\NutritionFactModel($this->getDatabaseConnection());
            $lastNutritionFactId = null;
            $lastNutritionFactId = $nutritionFactModel->deleteByFieldName("dessert_id",$id);

            $dessertModel = new \App\Models\DessertModel($this->getDatabaseConnection());
            $lastDeletedId = null;
            $lastDeletedId = $dessertModel->deleteByFieldName("dessert_id",$id);

            if($lastDeletedId){
                $this->set("success",true);
                $this->set("message",$lastDeletedId); 
            }
            else{
                $this->set("success",false);
                $this->set("message","error"); 
            } 
        }


    
////////////////////////////////////////////////////////////////////////////////////////////////////

    
        private function checkFieldsValues(array $strings=[], array $nums=[], array $ingredients=[]):bool{
            if(count($strings)===0 || count($nums)===0 || count($ingredients)===0) return true;
            foreach($strings as $key=>$value){
                if(!is_string($value) || $value==="") return true;
            }
            foreach ($nums as $key=>$value) {
                if(!is_numeric($value) || $value<0) return true; 
            }
            // foreach($ingredients as $key=>$value){
            //     if(!isset($value->quantity, $value->ingredient_id, $value->description)) return true;
            // }
            return false;
        }




        private function doImageUpload(string $filedName, string $fileName):bool{ 

            $uploadPath = new \Upload\Storage\FileSystem(\Configuration::UPLOAD_DIR);
            $file = new \Upload\File($filedName, $uploadPath);
            $file->setName($fileName); 
            
            // Access data about the file that has been uploaded
            $data = array(
                'name'       => $file->getNameWithExtension(),
                'extension'  => $file->getExtension(),
                'mime'       => $file->getMimetype(),
                'size'       => $file->getSize(),
                'md5'        => $file->getMd5(),
                'dimensions' => $file->getDimensions()
            );

            if(!in_array($data["extension"],["jpg","jpeg","png","gif"])){
                $this->set("message","Sorry, only JPG, JPEG, PNG & GIF files are allowed.");
                $this->set("success",false);
                return false;
            }
            if($data["size"]>500000){
                $this->set("message","Sorry, your file is too large");
                $this->set("success",false);
                return false;
            }
            if($data["dimensions"]["width"]!==596 || $data["dimensions"]["height"]!==596){
                $this->set("message","Sorry, image dimensions must be 596px x 596px");
                $this->set("success",false);
                return false;
            } 
            if(file_exists(\Configuration::UPLOAD_DIR.$fileName.".".$data["extension"])){
                $this->set("message","Sory, name $fileName is already used");
                $this->set("success",false);
                return false;
            }
            
            try{
                $file->upload();   
                $this->set("success",true);
                return true;
            }catch(Exception $e){
                $this->set("success", false);
                $this->set('message', "Error: ".implode(', ',$e->getMessage()));
                return false;
            }
 
            
        }


    }
?>