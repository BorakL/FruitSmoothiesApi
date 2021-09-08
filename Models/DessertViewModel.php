<?php
namespace App\Models;

class DessertViewModel extends \App\Core\Model{
    protected function getFields():array{
        return[
            "dessert_view_id"   =>  \App\Core\Field::readonlyInteger(10),
            "dessert_id"        =>  \App\Core\Field::editableInteger(10),
            "ip_address"        =>  \App\Core\Field::editableIpAddress(),
            "date"              =>  \App\Core\Field::readonlyDateTime()
        ];
    }
}