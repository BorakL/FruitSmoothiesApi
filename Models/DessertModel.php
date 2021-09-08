<?php
namespace App\Models;

class DessertModel extends \App\Core\Model{
    protected function getFields():array{
        return[
            "dessert_id"        =>     \App\Core\Field::readonlyInteger(10),
            "name"              =>     \App\Core\Field::editableString(64),
            "intro"             =>     \App\Core\Field::editableText(),
            "recipe"            =>     \App\Core\Field::editableText(),
            "category_id"       =>     \App\Core\Field::editableInteger(10),
            "user_id"           =>     \App\Core\Field::editableInteger(10), 
            "photo"             =>     \App\Core\Field::editableString(256),
            "video"             =>     \App\Core\Field::editableString(256)
        ];
    }

    
    
    public function getDesserts( $category_id, string $sort="", string $order="", string $limit="", string $user="", array $ingInc=[], array $ingExcl=[]){
        $sorts=["name","calories","rating"];
        $sql = "SELECT  
        dessert.dessert_id,
        dessert.name AS name, 
        dessert.intro, 
        dessert.recipe, 
        category.name AS category, 
        user.username AS username,
        nutrition_fact.calories AS calories, 
        AVG(assessment.assessment) AS rating, 
        dessert.photo AS photo, 
        dessert.video AS video 
        FROM dessert
        INNER JOIN category ON dessert.category_id = category.category_id
        INNER JOIN nutrition_fact ON dessert.dessert_id = nutrition_fact.dessert_id
        INNER JOIN user ON dessert.user_id = user.user_id  
        LEFT JOIN assessment ON dessert.dessert_id = assessment.dessert_id
        INNER JOIN making ON dessert.dessert_id = making.dessert_id
        INNER JOIN ingredient ON ingredient.ingredient_id = making.ingredient_id ".

        $this->whereClausules($category_id,$user) 

        ." GROUP BY dessert.dessert_id ".
        $this->getDessertsByIngredients($ingInc,$ingExcl)
        ." ".
        $this->sortingSQL($sorts,$sort,$order,$limit).
        ";";
        
        $prep = $this->getConnection()->prepare($sql);

        $bindNum=1;

        if($category_id!==null){
            $prep->bindValue($bindNum, $category_id, \PDO::PARAM_INT);
            $bindNum+=1;
        }

        if($user!==''){
            $prep->bindValue($bindNum, $user, \PDO::PARAM_STR);
            $bindNum+=1;
        }

        if(count($ingInc)>0 || count($ingExcl)>0){
            $ings = array_merge($ingInc,$ingExcl); 
            foreach ($ings as $key => $value) {
                $prep->bindValue($key+$bindNum, $value, \PDO::PARAM_STR);
            }
        } 

        $res = $prep->execute();
        $desserts = [];
        if($res){
            $desserts = $prep->fetchAll(\PDO::FETCH_OBJ);
        }
        return $desserts;
    }





    public function getFavoriteDessertsByUserName(string $username):array{
        $sql = "SELECT assessment.assessment, dessert.dessert_id, dessert.name, dessert.photo 
                FROM dessert INNER JOIN assessment ON dessert.dessert_id = assessment.dessert_id
                INNER JOIN user ON user.user_id = assessment.user_id 
                WHERE user.username = ? ORDER BY assessment.assessment DESC LIMIT 5";
        $prep = $this->getConnection()->prepare($sql);
        $favoriteDesserts=[];
        $result = $prep->execute([$username]);
        if($result){
            $favoriteDesserts = $prep->fetchAll(\PDO::FETCH_OBJ);
        }
        return $favoriteDesserts;
    }

 


    protected function whereClausules($category_id, string $user):string{
        if($category_id===null && $user==="")return "";
        $result=" WHERE ";
        $categoryStr = " dessert.category_id = ? ";
        $userStr = " user.username = ? ";
        if($category_id!==null && $user!=="") return $result.$categoryStr." AND ".$userStr;
        else if($category_id!==null) return $result.$categoryStr;
        else return $result.$userStr;
    }




    protected function getDessertsByIngredients(array $ingInc, array $ingExcl):string{
        if(count($ingInc)===0 && count($ingExcl)===0) return "";
        $sql = " HAVING ";
        $ingIncQuestionMarks = rtrim(str_repeat("?,",count($ingInc)),",");
        $ingExclQuestionMarks = rtrim(str_repeat("?,",count($ingExcl)),",");
        if(count($ingInc)>0){
            $sql.= " COUNT(DISTINCT CASE
                            WHEN ingredient.name IN (".$ingIncQuestionMarks.") THEN ingredient.name
                    END) = ".count($ingInc);
        }
        if(count($ingInc)>0 && count($ingExcl)>0){
            $sql.= " AND ";
        }
        if(count($ingExcl)>0){
            $sql.= " MAX(CASE
                        WHEN ingredient.name IN (".$ingExclQuestionMarks.") THEN 1 ELSE 0
                    END) = 0 ";
        }
        return $sql;
    }

   


    public function getDessertsBySearch(string $keywords){
        $sql = "SELECT * FROM `desserts` WHERE `name` LIKE ?;";
        $keywords = '%' . $keywords . '%';
        $prep = $this->getConnection()->prepare($sql);
        if(!$prep){
            return[];
        }
        $res = $prep->execute([$keywords]);
        if(!$res){
            return[];
        }
        return $prep->fetchAll(\PDO::FETCH_OBJ);
    }

}
