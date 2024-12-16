<?php

require 'Routing.php';

$path = trim($_SERVER['REQUEST_URI'], '/');
$path = parse_url($path, PHP_URL_PATH);

Routing::get('index', 'DefaultController');
Routing::get('dashboard', 'DefaultController');
Routing::get('404', 'DefaultController');
Routing::get('home', 'DefaultController');
Routing::get('logout', 'SecurityController');
Routing::post('addEntry', 'EntryController');
Routing::post('loginpage', 'SecurityController');
Routing::post('registerpage', 'SecurityController');
Routing::run($path);