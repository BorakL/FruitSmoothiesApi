<?php
namespace App\Controllers;

class ApiMainController extends \App\Core\ApiController{
    public function show(){
        $categoryModel = new \App\Models\CategoryModel($this->getDatabaseConnection());
        $categories = $categoryModel->getAll();
        $this->set("categories",$categories);
    }


    public function error(int $errorCode=404){
        http_response_code($errorCode);
        $this->set("message", "Route not found");
    }




    ///////////////////////////////////////////// postRegister() ////////////////////////////////////////

    public function postRegister(){

        $data = json_decode(file_get_contents('php://input'),true);

        $email = filter_var($data['email'], FILTER_SANITIZE_EMAIL);
        $forename = filter_var($data['forename'], FILTER_SANITIZE_STRING);
        $surname = filter_var($data['surname'], FILTER_SANITIZE_STRING);
        $username = filter_var($data['username'], FILTER_SANITIZE_STRING);
        $password1 = filter_var($data['password_1'], FILTER_SANITIZE_STRING);
        $password2 = filter_var($data['password_2'], FILTER_SANITIZE_STRING);
        $age = filter_var($data['age'], FILTER_SANITIZE_NUMBER_INT); 
 
        $this->set("success",false);

        if(!isset($email) || empty($email) || !filter_var($email,FILTER_VALIDATE_EMAIL)){$this->set("message","Email is not defined"); return;}
        if(!isset($forename) || empty($forename)){$this->set("message","Forename is not defined"); return;}
        if(!isset($surname) || empty($surname)){$this->set("message","Surname is not defined"); return;}
        if(!isset($username) || empty($username)){$this->set("message","Username is not defined"); return;}
        if(!isset($age) || $age<=0){$this->set("message","Age is not defined"); return;}
        if(!isset($password1) || empty($password1)){$this->set("message","Password1 is not defined"); return;}
        if(!isset($password2) || empty($password2)){$this->set("message","Password2 is not defined"); return;}

        if($password1!==$password2){
            $this->set("message","Password and confirm password does not match");
            return;
        }

        $userModel = new \App\Models\UserModel($this->getDatabaseConnection());

        $userEmail = $userModel->getByFieldName('email',$email);
        if($userEmail){
            $this->set("message", "This email address is already registered");
            return;
        }
        $userUsername = $userModel->getByFieldName("username",$username);
        if($userUsername){
            $this->set("message","There is already a user with that username");
            return;
        }

        $passwordHash = \password_hash($password1, PASSWORD_DEFAULT);

        $userId = $userModel->add([
            "forename"  =>  $forename, 
            "surname"   =>  $surname,
            "username"  =>  $username, 
            "birthYear" =>  date("Y") - $age,
            "email"     =>  $email, 
            "password"  =>  $passwordHash
        ]);


        if(!$userId){
            $this->set("message", "Registration failed");
            return;
        }
        $this->set("success",true);
        $this->set("message", "Registration Successful");

    }


 

    ///////////////////////////////////// postLogin() //////////////////////////////////////////

    public function postLogin(){
        header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

        include_once './jwt_configuration.php';

        $data = json_decode(file_get_contents('php://input'),true);

        $username = filter_var($data['username'], FILTER_SANITIZE_STRING);
        $password = filter_var($data['password'], FILTER_SANITIZE_STRING);

        $this->set("success",false);

        if(!isset($username) || !isset($password) || empty($username ) || empty($password)){
            $this->set("message","Inccorect username or password");
            return;
        }

        $userModel = new \App\Models\UserModel($this->getDatabaseConnection());
        $user = $userModel->getByFieldName('username',$username);
        if(!$user){
            $this->set("message","Incorect username");
            return;
        }

        if(!password_verify($password, $user->password)){
            sleep(1);
            $this->set('message','Incorect password');
            return;
        }

        $token = array(
            "iat"   =>  $issued_at,
            //"exp"   =>  $expiration_time,
            "iss"   =>  $issuer,
            "data"  =>  array(
                "user_id"    =>  $user->user_id,
                "firstrname" =>  $user->forename,
                "lastname"   =>  $user->surname,
                "username"   =>  $user->username,
                "email"      =>  $user->email
            )
        );

        //set response code
        http_response_code(200);

        //generate jwt
        $jwt = \App\Libs\PhpJwtMaster\Src\JWT::encode($token, $key);
        $this->set("message","successful login");
        $this->set("success",true);
        $this->set("jwt",$jwt);
    }
}
?>