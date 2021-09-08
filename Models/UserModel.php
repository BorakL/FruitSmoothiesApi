<?php
namespace App\Models;

class UserModel extends \App\Core\Model{
    protected function getFields():array{
        return[
            "user_id"   =>  \App\Core\Field::readonlyInteger(10),
            "forename"  =>  \App\Core\Field::editableString(64),
            "surname"   =>  \App\Core\Field::editableString(64),
            "username"  =>  \App\Core\Field::editableString(64),
            "birthYear" =>  \App\Core\Field::editableDateYear(),
            "email"     =>  \App\Core\Field::editableString(256),
            "password"  =>  \App\Core\Field::editableString(256)
        ];
    }
}