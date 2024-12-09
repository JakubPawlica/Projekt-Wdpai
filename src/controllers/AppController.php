<?php

class AppController {

    private $request;

    public function __construct()
    {
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
}