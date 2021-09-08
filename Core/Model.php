<?php
namespace App\Core;

abstract class Model{
    private $dbc;

    final public function __construct(\App\Core\DatabaseConnection $dbc){
        $this->dbc = $dbc;
    }

    final protected function getConnection(){
        return $this->dbc->getConnection();
    }
    
    protected function getFields():array{
        return [];
    }
 

    final private function getTableName(): string{
        $fullName = static::class;
        $matches = [];
        preg_match('|^.*\\\((?:[A-Z][a-z]+)+)Model$|', $fullName, $matches);
        $className = $matches[1] ?? '';
        # UserProfile
        $underscoredClassName = preg_replace('|[A-Z]|', '_$0', $className);
        #_User_Profile
        $lowerCaseUnderscoredClassName = strtolower($underscoredClassName);
        # _user_profile
        return substr($lowerCaseUnderscoredClassName, 1);
        # user_profle
    }

    final private function getPrimaryKeyName(): string{
        return $this->getTableName() . '_id';
    }


    final private function isFieldValueValid(string $fieldName, $fieldValue):bool{
        $fields = $this->getFields();
        $supportedFieldNames = array_keys($fields);
        if(!in_array($fieldName,$supportedFieldNames)){
            echo $fieldName." - pogrešan naziv polja";
            return false;
        }
        return $fields[$fieldName]->isValid($fieldValue);
        echo $fieldValue." - pogrešna vrednost polja";
    }

    final private function checkFieldList(array $data){
        $fields = $this->getFields();
        $supportedFieldNames = array_keys($fields);
        $requestedFieldNames = array_keys($data);

        foreach($requestedFieldNames as $requestedFieldName){
            if(!in_array($requestedFieldName, $supportedFieldNames)){
                throw new \Exception('Field '. $requestedFieldName . ' is not supported!');
            }
            if(!$fields[$requestedFieldName]->isEditable()){
                throw new \Exception('Field '. $requestedFieldName . ' is not editable!');
            }
            if(!$fields[$requestedFieldName]->isValid($data[$requestedFieldName])){
                throw new \Exception('The value for the field '. $requestedFieldName . ' is not valid!');
            }
        }
    }

    protected function sortingSQL(array $sortOptions=[], string $sort="", string $order="", string $limit=""):string{
        $sorting="";
        if(in_array($sort,$sortOptions)){
            $sorting.=" ORDER BY ".$sort." ";
            if(in_array(strtoupper($order),["ASC","DESC"])) $sorting.=$order;
        }
        if(preg_match('/^[0-9]+$/',$limit) && intval($limit)>0) $sorting.=" LIMIT $limit";
        return $sorting;
    }

    



/////////////////////////////////////// SELECT ///////////////////////////////////////////////////////


    public function getById(int $id){
        $tableName = $this->getTableName();
        $sql = 'SELECT * FROM ' . $tableName . ' WHERE ' . $tableName .'_id = ?;'; 
        $prep = $this->dbc->getConnection()->prepare($sql);
        $result = $prep->execute([$id]);
        $item=NULL;
        if($result){
            $item = $prep->fetch(\PDO::FETCH_OBJ);
        }
        return $item;
    }

    public function getAll(){
        $tableName = $this->getTableName();
        $sql = 'SELECT * FROM ' . $tableName . ';'; 
        $prep = $this->dbc->getConnection()->prepare($sql);
        $result = $prep->execute();
        $items=[];
        if($result){
            $items = $prep->fetchAll(\PDO::FETCH_OBJ);
        }
        return $items;
    }

    // public function getAll(string $sortBy=null, string $sortOrder=null, int $limit=null){ 
    //     $tableName = $this->getTableName();

    //     $ordering="";
    //     if($sortBy){
                //isFieldNamevalid je sada promenjen!
    //         if(!$this->isFieldNameValid($sortBy)) throw new \Exception('Invalid sorting criteria: ',$sortBy);
    //         $ordering.=" ORDER BY ".$sortBy;
    //     }
    //     if($sortOrder){
    //         if(!in_array($sortOrder,['asc','desc','ASC','DESC'])) throw new \Exception('Invalid order criteria: ',$sortOrder);
    //         $ordering.=" ".strtoupper($sortOrder)." ";
    //     }
    //     if($limit>0){
    //         $ordering.=" LIMIT ".$limit;
    //     }

    //     $sql = 'SELECT * FROM ' . $tableName . ' ' . $ordering . ';'; 
    //     $prep = $this->dbc->getConnection()->prepare($sql);
    //     $result = $prep->execute();
    //     $items=[];
    //     if($result){
    //         $items = $prep->fetchAll(\PDO::FETCH_OBJ);
    //     }
    //     return $items;
    // }


    public function getByFieldName(string $fieldName, $value){
        if(!$this->isFieldValueValid($fieldName, $value)){
            throw new \Exception('Invalid field name or value: '. $fieldName);
        }
        $tableName = $this->getTableName();
        $sql = 'SELECT * FROM ' . $tableName . ' WHERE ' . $fieldName .' = ?;'; 
        $prep = $this->dbc->getConnection()->prepare($sql);
        $result = $prep->execute([$value]);
        $item=null;
        if($result){
            $item = $prep->fetch(\PDO::FETCH_OBJ);
        }
        return $item;
    }


    public function getAllByFieldName(string $fieldName, $value){
        if(!$this->isFieldValueValid($fieldName, $value)){
            throw new \Exception('Invalid field name or value: '. $fieldName);
        }
        $tableName = $this->getTableName();
        $sql = 'SELECT * FROM ' . $tableName . ' WHERE ' . $fieldName .' = ?;'; 
        $prep = $this->dbc->getConnection()->prepare($sql);
        $result = $prep->execute([$value]);
        $items=[];
        if($result){
            $items = $prep->fetchAll(\PDO::FETCH_OBJ);
        }
        return $items ? $items : [];
    }


    public function getByFieldsNames(array $data){
        $this->checkFieldList($data);
        $tableName = $this->getTableName();
        $sql = "SELECT * FROM ".$tableName." WHERE ";
        foreach($data as $k=>$v){
            $sql.= " {$k}=? AND ";
        }
        $sql = rtrim($sql," AND ");
        $prep = $this->dbc->getConnection()->prepare($sql);
        $res = $prep->execute(array_values($data));
        $item = null;
        if($res){
           $item = $prep->fetch(\PDO::FETCH_OBJ);
        }
        return $item;
    }



//////////////////////// ADD, EDIT, DELETE ////////////////////////////////////////////////////////////



    final public function add(array $data){
        $this->checkFieldList($data); 
        $tableName = $this->getTableName();
        $sqlFieldNames = implode(', ', array_keys($data));
        $questionMarks = str_repeat('?,', count($data));
        $questionMarks = substr($questionMarks,0,-1);

        $sql = "INSERT INTO {$tableName} ({$sqlFieldNames}) VALUES ({$questionMarks})";
        $prep = $this->dbc->getConnection()->prepare($sql);
        $res = $prep->execute(array_values($data));
        if(!$res){
            return false;
        }
        return $this->dbc->getConnection()->lastInsertId(); 
    }


    final public function editById(int $id, array $data){
        $this->checkFieldList($data);
        $tableName = $this->getTableName(); 
        $editList = [];
        $values = [];
        foreach($data as $fieldName=>$value){
            $editList[]="{$fieldName} = ?";
            $values[]=$value;
        }
        $editString = implode(', ', $editList);
        $values[] = $id;
        $sql="UPDATE {$tableName} SET {$editString} WHERE {$tableName}_id = ?;";
        $prep = $this->dbc->getConnection()->prepare($sql);
        return $prep->execute($values);
    }


    public function deleteById(int $id){
        $tableName = $this->getTableName();
        $sql = 'DELETE FROM ' . $tableName . ' WHERE ' . $tableName .'_id = ?;'; 
        $prep = $this->dbc->getConnection()->prepare($sql);
        return $prep->execute([$id]);
    }

    public function deleteByFieldName(string $fieldName, $value){
        // if(!$this->isFieldValueValid($fieldName, $value)){
        //     throw new \Exception('Invalid field name or value: '. $fieldName);
        // }
        $tableName = $this->getTableName();
        $sql = 'DELETE FROM ' . $tableName . ' WHERE ' . $fieldName .' = ?;'; 
        $prep = $this->dbc->getConnection()->prepare($sql);
        return $prep->execute([$value]);
    }

}
?> 