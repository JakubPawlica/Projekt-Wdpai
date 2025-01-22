<?php

require 'Routing.php';

$path = trim($_SERVER['REQUEST_URI'], '/');
$path = parse_url($path, PHP_URL_PATH);

Routing::get('index', 'DefaultController');
Routing::get('dashboard', 'DefaultController');
Routing::get('404', 'DefaultController');
Routing::get('home', 'DefaultController');
Routing::get('locator', 'LocatorController');
Routing::get('manage', 'DefaultController');
Routing::get('exportToExcel', 'EntryController');
Routing::post('importFromExcel', 'EntryController');
Routing::get('logout', 'SecurityController');
Routing::get('deleteEntry', 'EntryController');
Routing::post('addEntry', 'EntryController');
Routing::post('loginpage', 'SecurityController');
Routing::post('registerpage', 'SecurityController');
Routing::post('search', 'EntryController');
Routing::post('searchLocator', 'EntryController');
Routing::get('profile', 'ProfileController');
Routing::post('updateUserName', 'EntryController');
Routing::get('adminpage', 'AdminController');
Routing::post('blockUser', 'AdminController');
Routing::post('deleteUser', 'AdminController');
Routing::post('grantAdmin', 'AdminController');
Routing::post('unblockUser', 'AdminController');
Routing::post('removeAdmin', 'AdminController');
Routing::run($path);