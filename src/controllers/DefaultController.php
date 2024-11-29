<?php

require_once 'Appcontroller.php';

class DefaultController extends AppController {
    
    public function index(){
        //TODO display dashboard.html
        $this->render('dashboard');
    }

    public function dashboard(){
        //TODO display dashboard.html
        $this->render('dashboard');
    }

    public function error404(){
        //TODO display 404.html
        $this->render('404');
    }

    public function home(){
        //TODO display home.html
        $this->render('home');
    }

    public function loginpage(){
        //TODO display loginpage.html

        if($this->isGet()){
            return $this->render("loginpage");
        }

        $email = $_POST['email'];
        $password = $_POST['password'];

        $this->render("home", [
            'email'=>$email,
            'password'=>$password
        ]);
    }

    public function registerpage(){
        //TODO display registerpage.html
        $this->render('registerpage');
    }
}