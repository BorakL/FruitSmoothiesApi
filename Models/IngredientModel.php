<?php
namespace App\Models;

class IngredientModel extends \App\Core\Model{
    protected function getFields():array{
        return[
            "ingredient_id"              =>      \App\Core\Field::readonlyInteger(10),
            "ingredient_category_id"     =>      \App\Core\Field::editableInteger(10),
            "name"                       =>      \App\Core\Field::editableString(64),
            "image"                      =>      \App\Core\Field::editableString(256),
            "measure"                    =>      \App\Core\Field::editableString(64)
        ];
    }

    public function getAllByDessertId(int $int):array{
        $sql = "SELECT ingredient.*,making.quantity, making.optional, making.description FROM ingredient 
                INNER JOIN making ON ingredient.ingredient_id = making.ingredient_id
                INNER JOIN dessert ON dessert.dessert_id = making.dessert_id
                WHERE dessert.dessert_id = ?";
        $prep = $this->getConnection()->prepare($sql);
        $ingredients = [];
        $result = $prep->execute([$int]);
        if($result){
            $ingredients = $prep->fetchAll(\PDO::FETCH_OBJ);
        }
        return $ingredients;
    }
}