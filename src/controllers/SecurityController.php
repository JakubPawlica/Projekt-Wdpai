<?php

require_once 'AppController.php';
require_once __DIR__.'/../models/User.php';
require_once __DIR__.'/../repository/UserRepository.php';

class SecurityController extends AppController
{
    public function loginpage(){

        $userRepository = new UserRepository();

        if (!$this->isPost()) {
            return $this->render('loginpage');
        }

        $email = $_POST["email"];
        $password = $_POST["password"];

        $user = $userRepository->getUser($email);

        if(!$user) {
            return $this->render('loginpage', ['messages' => ['Użytkownik o podanym adresie email nie istnieje!']]);
        }

        if($user->getEmail() !== $email) {
            return $this->render('loginpage', ['messages' => ['Użytkownik o podanym adresie email nie istnieje!']]);
        }

        if(!password_verify($password, $user->getPassword())) {
            return $this->render('loginpage', ['messages' => ['Podano niepoprawne hasło!']]);
        }

        $new_session = $this->createUserToken($user->getId());
        setcookie("user_token", $new_session, time() + (356 * 24 * 60 * 60), '/');

        $url = "http://$_SERVER[HTTP_HOST]";
        header("Location: {$url}/home");
    }

    public function registerpage(){

        if(!$this->isPost()) {
            return $this->render('registerpage');
        }

        $name = $_POST['name'];
        $surname = $_POST['surname'];
        $email = $_POST['email'];
        $options = [
            'cost' => 12,
        ];
        $password = password_hash($_POST['password'], PASSWORD_BCRYPT, $options);

        $workerId = $_POST['worker_id'];
        if ($workerId !== 'MK18') {
            return $this->render('registerpage', ['messages' => ['Nieprawidłowy identyfikator pracownika!']]);
        }

        $user = new User($email, $password, $name, $surname);

        $userRepository = new UserRepository();
        $res = $userRepository->addUser($user);
        if(is_null($res)) {
            $this->render('registerpage', ['messages' => 'Konto już istnieje']);
        }

        $url = "http://$_SERVER[HTTP_HOST]";
        header("Location: {$url}/loginpage");
        exit;
    }

    public function logout()
    {
        // Usuń ciasteczko 'user_token'
        setcookie("user_token", "", time() - 3600, '/');  // Ustawiamy datę wygaszenia ciasteczka na przeszłość

        // Możesz również zakończyć sesję, jeśli jej używasz
        if (isset($_SESSION)) {
            session_unset();  // Usuwa wszystkie zmienne sesyjne
            session_destroy(); // Niszczy całą sesję
        }

        // Przekierowanie użytkownika na stronę logowania po wylogowaniu
        $url = "http://$_SERVER[HTTP_HOST]";
        header("Location: {$url}/loginpage");
        exit; // Upewnij się, że nie ma dalszego kodu po przekierowaniu
    }

    protected function createUserToken(int $userId): string
    {
        // Generowanie tokenu sesji na podstawie ID użytkownika
        // Możesz użyć np. funkcji uniqid, random_bytes lub JWT do generowania tokenu
        $token = bin2hex(random_bytes(32));  // Generowanie unikalnego tokenu
        // Możesz tu dodać logikę, aby zapisać token w bazie danych lub w sesji, jeśli jest to wymagane
        return $token;
    }
}