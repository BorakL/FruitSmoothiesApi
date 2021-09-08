<?php
namespace App\Models;

class IngredientCategoryModel extends \App\Core\Model{
    protected function getFields():array{
        return[
            "ingredient_category_id"    =>  \App\Core\Field::readonlyInteger(10),
            "name"                      =>  \App\Core\Field::editableString(64)
        ];
    }
}