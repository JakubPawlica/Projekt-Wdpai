<?php

require_once 'src/controllers/DefaultController.php';
require_once 'src/controllers/SecurityController.php';
require_once 'src/controllers/EntryController.php';
require_once 'src/controllers/ProfileController.php';
require_once 'src/controllers/AdminController.php';
require_once 'src/controllers/LocatorController.php';

class Routing {

    public static $routes;

    public static function get($url, $controller) {
        self::$routes[$url] = $controller;
    }

    public static function post($url, $controller) {
        self::$routes[$url] = $controller;
    }

    public static function run($url) {
        $action = explode("/",$url)[0];

        if ($action === '') {
            $controller = new DefaultController();
            return $controller->dashboard();
        }

        if(!array_key_exists($action, self::$routes)) {
            $controller = new DefaultController();
            return $controller->error404();
        }

        $controller = self::$routes[$action];
        $object = new $controller;

        $object->$action();
    }
}