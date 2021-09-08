<?php
namespace App\Core\Role;

class AdminRoleController extends \App\Core\Controller{
    public function __pre(){
        if($this->getSession()->get('admin_id')===null){
            $this->redirect("/adminLogin12345");
        }
    }
}

?>