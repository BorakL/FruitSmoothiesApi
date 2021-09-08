<?php
namespace App\Controllers;

class MainController extends \App\Core\Controller{
    public function home(){
        $categoryModel = new \App\Models\CategoryModel($this->getDatabaseConnection());
        $categories = $categoryModel->getAll();
        $this->set('categories',$categories);

    }



    public function getAdminLogin(){}



    public function postAdminLogin(){
        $email = filter_input(INPUT_POST, "email", FILTER_SANITIZE_EMAIL);
        $password = filter_input(INPUT_POST, "password", FILTER_SANITIZE_STRING);
        if(strlen($password)<7){
            $this->set("message","Greška: Lozinka nije ispravnog formata");
            return;
        }
        
        $adminModel = new \App\Models\AdminModel($this->getDatabaseConnection());
        
        $admin = $adminModel->getByFieldName("email",$email);
        if(!$admin){
            $this->set("message","Greška: Ne postoji administrator sa datom email adresom");
            return;
        }

        if($admin->password!==$password){
            sleep(1);
            $this->set("message","Greška: Lozinka nije ispravna");
            return;
        }
        //prijava administratora 
        $this->getSession()->put('admin_id',$admin->admin_id);
        $this->getSession()->save(); 
        header("Location: /adminProfile");

    }


}



  /*
    public function getRegister(){

    }



  
    public function postRegister(){
        $email     = \filter_input(INPUT_POST, 'reg_email', FILTER_SANITIZE_EMAIL);
        $forename  = \filter_input(INPUT_POST, 'reg_forename', FILTER_SANITIZE_STRING);
        $surname   = \filter_input(INPUT_POST, 'reg_surname', FILTER_SANITIZE_STRING);
        $username  = \filter_input(INPUT_POST, 'reg_username', FILTER_SANITIZE_STRING);
        $password1 = \filter_input(INPUT_POST, 'reg_password_1', FILTER_SANITIZE_STRING);
        $password2 = \filter_input(INPUT_POST, 'reg_password_2', FILTER_SANITIZE_STRING);
        
        if($password1 !== $password2){
            $this->set('message','Došlo je do greške: Niste uneli dva puta istu lozinku.');
            return;
        }

        if(strlen($password1)<7){
            $this->set('message', 'Došlo je do greške: Lozinka nije ispravnog formata.');
            return;
        }

        $userModel = new \App\Models\UserModel($this->getDatabaseConnection());

        $user = $userModel->getByFieldname('email',$email);
        if($user){
            $this->set('message', 'Došlo je do greške: Već postoji korisnik sa tom e-mail adresom.');
            return;
        }
        $user = $userModel->getByFieldname('username',$username);
        if($user){
            $this->set('message', 'Došlo je do greške: Već postoji korisnik sa tim korisničkim imenom.');
            return;
        }


        $passwordHash = \password_hash($password1, PASSWORD_DEFAULT);
        $userId = $userModel->add([
            "email"         => $email,
            "name"          => $forename,
            "surname"       => $surname,
            "username"      => $username,
            "password"      => $passwordHash
        ]); 

        if(!$userId){
            $this->set('message', 'Došlo je dovde bez greške: Nije bilo uspešno registrovanje naloga.');
            return;
        }

        $this->set('message','Napravljen je novi nalog. Sada možete da se prijavite.');        
    }






    public function getLogin(){

    }





    public function postLogin(){
        $username = \filter_input(INPUT_POST, 'login_username', FILTER_SANITIZE_STRING);
        $password = \filter_input(INPUT_POST, 'login_password', FILTER_SANITIZE_STRING);

        if(strlen($password)<7){
            $this->set('message', 'Došlo je do greške: Lozinka nije ispravnog formata.');
            return;
        }

        $userModel = new \App\Models\UserModel($this->getDatabaseConnection());
        $user = $userModel->getByFieldName('username',$username);
        if(!$user){
            $this->set('message','Došlo je do greške: Ne postoji korisnik sa tim korisničkim imenom! ');
            return;
        }

        if(!password_verify($password, $user->password)){
            print_r("Password: ".$password);
            print_r("PasswordHash: ".$user->password);
            sleep(1);
            $this->set('message','Došlo je do greške: lozinka nije ispravna!');
            return;
        }

        //Prijava korisnika
        $this->getSession()->put('user_id',$user->user_id);
        $this->getSession()->save();

        $this->redirect('/mc/app1/user/profile');
    }

}

*/

?>