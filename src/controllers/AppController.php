<?php

require_once __DIR__ . '/../repository/Repository.php';

class AppController {

    private $request;
    protected $database;

    public function __construct()
    {
        $this->database = new Repository();
        $this->request = $_SERVER['REQUEST_METHOD'];
    }

    protected function isGet(): bool
    {
        return $this->request === 'GET';
    }

    protected function isPost(): bool
    {
        return $this->request === 'POST';
    }
    
    protected function render(string $template = null, array $variables = []) {
        $templatePath = 'public/views/'.$template.'.php';
        //$output = 'File not found';

        if(!file_exists($templatePath)) {
            // Przekierowanie na stronę błędu 404
            $this->render('404');
            return;
        }

        extract($variables);
        ob_start();
        include $templatePath;
        $output = ob_get_clean();

        if (ob_get_length()) {
            ob_end_clean();
        }

        print $output;
        exit;
    }

    protected function redirectIfNotAuthenticated()
    {
        if (!$this->isAuthenticated()) {
            $url = "http://$_SERVER[HTTP_HOST]/loginpage";
            header("Location: {$url}");
            exit;
        }
    }

    protected function isAuthenticated(): bool
    {
        if (!isset($_COOKIE['user_token'])) {
            return false;
        }

        $token = $_COOKIE['user_token'];
        // Weryfikacja tokenu z bazą danych
        $stmt = $this->database->connect()->prepare('SELECT id FROM users WHERE session_token = :token');
        $stmt->bindParam(':token', $token, PDO::PARAM_STR);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC) !== false;
    }

}