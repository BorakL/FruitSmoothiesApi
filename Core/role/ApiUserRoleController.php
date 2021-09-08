<?php
namespace App\Core\Role;
include_once './jwt_configuration.php';

class ApiUserRoleController extends \App\Core\ApiController{
    protected $decoded;
    public function __pre(){
        //get posted tada
        //$data = json_decode(file_get_contents("php://input"));

        //$jwt = isset($data["jwt"]) ? (stripslashes($data["jwt"])) : false;

        $jwt = $this->getBearerToken(); 
        
        

        if($jwt){ 
            //if decode succeed, show user details
            try{
                //decode jwt
                $this->decoded = \App\Libs\PhpJwtMaster\Src\JWT::decode($jwt,"lb12345",array('HS256'));

                //set response code
                http_response_code(200); 
            } 
            //show error if decoding failed
            // if decode fails, it means jwt is invalid
            catch (\Exception $e){
                header('HTTP/1.1 401 Unauthorized'); 
                $this->set("message","Error: Access denied");
                $this->set("error",$e->getMessage());
                exit; 
            }
        }
        else{
             
            header('HTTP/1.1 401 Unauthorized');
            $this->set("message","Error: Access denied");
            exit; 
        } 
    }
    
    
}
 