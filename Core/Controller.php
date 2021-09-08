<?php
namespace App\Core;

class Controller{
    private $dbc;
    private $session;
    private $data=[];

    public function __pre(){
        
    }

    final public function __construct(\App\Core\DatabaseConnection &$dbc){
        $this->dbc=$dbc;
    }

    final public function &getSession():\App\Core\Session\Session{
        return $this->session;
    }

    final public function setSession(\App\Core\Session\Session &$session){
        $this->session = $session;
    }

    final public function &getDatabaseConnection():\App\Core\DatabaseConnection{
        return $this->dbc;
    }

    final protected function set(string $name, $value):bool{
        $result=false;
        if(preg_match('/^[a-z][a-z0-9]+(?:[A-Z][a-z0-9]+)*$/', $name)){
            $this->data[$name]=$value;
            $result=true;
        }
        return $result;
    }

    final public function getData(): array{
        return $this->data;
    }

    final protected function redirect(string $path, int $code=307){
        ob_clean();
        header('Location: '.$path, true, $code);
        exit;
    }


    //////////////////////////////////////// Input Filters ///////////////////////////////////////

    private function myFilterInput($input){
        $result = trim($input);
        $result = stripslashes($input);
        $result = htmlspecialchars($input); 
        return $result;
    }
 
    private function filterArray(array $arr):array{
        $arr=[];
        foreach($arr as $key => $value) {
            $arr[$key]=filter_var($value,FILTER_SANITIZE_STRING);
        }
        return $arr;
    } 

    protected function getParameters(){
       $pattern = "|(?<=\?)([\[\]\w\-\s]+\=[\w\-\s]+)?(\&[\[\]\w\-\s]+\=[\w\-\s]+)*|"; 
        preg_match($pattern, urldecode($_SERVER['REQUEST_URI']), $matches);
        parse_str($matches[0], $get_array);
        return $get_array;
    }
}