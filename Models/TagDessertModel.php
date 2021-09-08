<?php
namespace App\Models;

class TagDessertModel extends \App\Core\Model{
    protected function getFields():array{
        return[
            "tag_dessert_id"    =>  \App\Core\Field::readonlyInteger(10),
            "tag_id"            =>  \App\Core\Field::editableInteger(10),
            "dessert_id"        =>  \App\Core\Field::editableInteger(10)
        ];
    }
}