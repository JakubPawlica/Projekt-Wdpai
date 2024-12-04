<?php

require_once 'AppController.php';
require_once __DIR__.'/../models/User.php';
require_once __DIR__.'/../repository/UserRepository.php';

class SecurityController extends AppController
{
    public function loginpage(){

        $userRepository = new UserRepository();

        if (!$this->isPost()) {
            return $this->render('loginpage');
        }

        $email = $_POST["email"];
        $password = $_POST["password"];

        $user = $userRepository->getUser($email);

        if(!$user) {
            return $this->render('loginpage', ['messages' => ['Użytkownik o podanym adresie email nie istnieje!']]);
        }

        if($user->getEmail() !== $email) {
            return $this->render('loginpage', ['messages' => ['Użytkownik o podanym adresie email nie istnieje!']]);
        }

        if($user->getPassword() !== $password) {
            return $this->render('loginpage', ['messages' => ['Podano niepoprawne hasło!']]);
        }

        $url = "http://$_SERVER[HTTP_HOST]";
        header("Location: {$url}/home");
    }

    public function registerpage(){
        $this->render('registerpage');
    }
}