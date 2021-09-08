<?php
namespace App\Models;

class InfoModel extends \App\Core\Model{
    protected function getFields():array{
        return[
            "info_id"   =>  \App\Core\Field::readonlyInteger(10),
            "time"      =>  \App\Core\Field::editableInteger(10),
            "servings"  =>  \App\Core\Field::editableInteger(10),
            "dessert_id"=>  \App\Core\Field::editableInteger(10)
        ];
    }
}