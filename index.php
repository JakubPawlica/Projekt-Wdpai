<?php

require 'Routing.php';

$path = trim($_SERVER['REQUEST_URI'], '/');
$path = parse_url($path, PHP_URL_PATH);

Routing::get('index', 'DefaultController');
Routing::get('dashboard', 'DefaultController');
Routing::get('404', 'DefaultController');
Routing::get('home', 'DefaultController');
Routing::get('manage', 'DefaultController');
Routing::get('exportToExcel', 'EntryController');
Routing::post('importFromExcel', 'EntryController');
Routing::get('logout', 'SecurityController');
Routing::get('deleteEntry', 'EntryController');
Routing::post('addEntry', 'EntryController');
Routing::post('loginpage', 'SecurityController');
Routing::post('registerpage', 'SecurityController');
Routing::post('search', 'EntryController');
Routing::get('profile', 'ProfileController');
Routing::post('updateUserName', 'EntryController');
Routing::get('adminpage', 'AdminController'); // Wyświetlenie strony admina
Routing::post('blockUser', 'AdminController'); // Blokowanie użytkownika
Routing::post('deleteUser', 'AdminController'); // Usuwanie użytkownika
Routing::post('grantAdmin', 'AdminController'); // Nadawanie uprawnień administratora
Routing::post('unblockUser', 'AdminController'); // Odblokowywanie użytkownika
Routing::post('removeAdmin', 'AdminController'); // Odbieranie praw administratora
Routing::run($path);