<?php
namespace App\Models;

class AssessmentModel extends \App\Core\Model{
    protected function getFields():array{
        return[
            "assessment_id" =>  \App\Core\Field::readonlyInteger(10),
            "assessment"    =>  \App\Core\Field::editableInteger(10),
            "dessert_id"    =>  \App\Core\Field::editableInteger(10),
            "user_id"       =>  \App\Core\Field::editableInteger(10),
            "date"          =>  \App\Core\Field::readonlyDateTime()
        ];
    }

    public function getDessertRating(int $id){   
        $sql = "SELECT AVG(assessment.assessment) AS rating FROM assessment INNER JOIN dessert ON dessert.dessert_id = assessment.dessert_id 
        WHERE dessert.dessert_id = ? GROUP BY dessert.dessert_id;";
        $prep = $this->getConnection()->prepare($sql);
        $rating = null;
        $res = $prep->execute([$id]);
        if($res){
            $rating = $prep->fetch(\PDO::FETCH_OBJ);
        }
        return $rating;
    }
 
    
}