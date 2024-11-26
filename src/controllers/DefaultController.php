<?php

require_once 'Appcontroller.php';

class DefaultController extends AppController {
    
    public function index(){
        //TODO display dashboard.html
        $this->render('dashboard');
    }

    public function loginpage(){
        //TODO display loginpage.html
        $this->render('loginpage');
    }
}