<?php
namespace App\Models;

class CommentModel extends \App\Core\Model{
    protected function getFields():array{
        return [
            "comment_id"    =>  \App\Core\Field::readonlyInteger(10),
            "content"       =>  \App\Core\Field::editableString(512),
            "user_id"       =>  \App\Core\Field::editableInteger(10),
            "dessert_id"    => \App\Core\Field::editableInteger(10),
            "date"          =>  \App\Core\Field::readonlyDateTime()
        ];
    }

    public function getAllByDessertId(int $dessertId):array{
        $sql = "SELECT comment.*, user.username FROM comment INNER JOIN user ON comment.user_id=user.user_id WHERE comment.dessert_id = ?";
        $prep = $this->getConnection()->prepare($sql);
        $comments=[];
        $result = $prep->execute([$dessertId]);
        if($result){
            $comments = $prep->fetchAll(\PDO::FETCH_OBJ);
        }
        return $comments;
    }
}

?>