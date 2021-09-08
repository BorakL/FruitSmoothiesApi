<?php
namespace App\Models;

class MakingModel extends \App\Core\Model{
    protected function getFields():array{
        return[
            "making_id"     =>  \App\Core\Field::readonlyInteger(10),
            "dessert_id"    =>  \App\Core\Field::editableInteger(10),
            "ingredient_id" =>  \App\Core\Field::editableInteger(10),
            "quantity"      =>  \App\Core\Field::editableString(64),
            "optional"      =>  \App\Core\Field::editableString(64),
            "description"   =>  \App\Core\Field::editableString(256)
        ];
    }
}