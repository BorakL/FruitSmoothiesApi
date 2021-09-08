<?php
namespace App\Models;

    class AuthorModel extends \App\Core\Model{
        protected function getFields():array{
            return[
                "author_id"     =>  \App\Core\readonlyInteger(10),
                "name"          =>  \App\Core\editableString(64),
                "photo"         =>  \App\Core\editableString(128)
            ];
        }
    }
?>