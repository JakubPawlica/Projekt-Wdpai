<?php

class AppController {

    protected function isGet(): bool
    {
        return $_SERVER['REQUEST_METHOD'] === 'GET';
    }
 
    protected function isPost(): bool
    {
        return $_SERVER['REQUEST_METHOD'] === 'POST';
    }
    
    protected function render(string $template = null, array $variables = []) {
        $templatePath = 'public/views/'.$template.'.html.php';
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

        print $output;
    }
}