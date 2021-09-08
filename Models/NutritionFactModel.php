<?php
namespace App\Models;

class NutritionFactModel extends \App\Core\Model{
    protected function getFields():array{
        return[
            "nutrition_fact_id"     =>  \App\Core\Field::readonlyInteger(10),
            "calories"               =>  \App\Core\Field::editableInteger(10),
            "protein"                =>  \App\Core\Field::editableDecimal(10,1),
            "carbotydrates"          =>  \App\Core\Field::editableDecimal(10,1),
            "fat"                    =>  \App\Core\Field::editableDecimal(10,1),
            "sugars"                 =>  \App\Core\Field::editableDecimal(10,1),
            "dessert_id"             =>  \App\Core\Field::editableInteger(10)
        ];
    }
}