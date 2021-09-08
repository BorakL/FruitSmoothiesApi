<?php
namespace App\Models;

    class CategoryModel extends \App\Core\Model{
        protected function getFields():array{
            return[
                "category_id"   =>  \App\Core\Field::readonlyInteger(10),
                "name"   =>  \App\Core\Field::editableString(64),
                "photo"   =>  \App\Core\Field::editableString(128),
            ];
        }
    }
?>