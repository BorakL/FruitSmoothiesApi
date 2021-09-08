<?php
namespace App\Core\Role;

class UserRoleController extends \App\Core\Controller{
   public function __pre(){
       //Ako pokušajem izvlačenja iz sesije vrednosti pod ključem id mi dobijemo null znamo da korisnik nije ulogovan.
        if($this->getSession()->get('user_id')===null){
            //Ako korisnik nije ulogovan redirektovati korisnika na login
            $this->redirect('/mc/app1/user/login');
        }
   }
}

