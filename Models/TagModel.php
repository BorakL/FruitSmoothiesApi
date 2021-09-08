<?php
namespace App\Models;

class TagModel extends \App\Core\Model{
    protected function getFields():array{
        return[
            "tag_id"    =>  \App\Core\Field::readonlyInteger(10),
            "name"      =>  \App\Core\Field::editableString(64)
        ];
    }


    public function getAllByDessertId(int $dessert_id):array{
        $sql = "SELECT tag.tag_id, tag.name FROM tag
                INNER JOIN tag_dessert ON tag.tag_id = tag_dessert.tag_id 
                INNER JOIN dessert ON dessert.dessert_id = tag_dessert.dessert_id
                WHERE dessert.dessert_id=?;"; 
        $prep = $this->getConnection()->prepare($sql);
        $tags = [];
        $res = $prep->execute([$dessert_id]);
        if($res){
            $tags = $prep->fetchAll(\PDO::FETCH_OBJ);
        }
        return $tags;
    }
}