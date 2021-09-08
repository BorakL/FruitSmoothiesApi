<?php
namespace App\Models;

class AdminModel extends \App\Core\Model{
    protected function getFields():array{
        return[
            "admin_id"  =>  \App\Core\Field::readonlyInteger(10),
            "name"      =>  \App\Core\Field::editableString(64),
            "surname"   =>  \App\Core\Field::editableString(64),
            "email"     =>  \App\Core\Field::editableString(64),
            "password"  =>  \App\Core\Field::editableString(256)
        ];
    }
}