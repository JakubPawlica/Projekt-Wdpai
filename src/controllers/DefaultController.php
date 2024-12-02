<?php

require_once 'AppController.php';

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
}