<?php

require_once 'AppController.php';
require_once __DIR__.'/../models/User.php';

class SecurityController extends AppController
{
    public function loginpage(){
        //TODO display loginpage.html

        $user = new User(email: 'jWick@pk.edu.pl', password: 'admin', name: 'John', surname: 'Wick');

        if (!$this->isPost()) {
            return $this->render('loginpage');
        }

        $email = $_POST["email"];
        $password = $_POST["password"];

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
        //TODO display registerpage.html
        $this->render('registerpage');
    }
}