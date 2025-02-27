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

        $stmt = $this->database->connect()->prepare('
        SELECT is_blocked FROM users WHERE email = :email
        ');
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($result && $result['is_blocked']) {
            return $this->render('loginpage', ['messages' => ['Konto zostało zablokowane']]);
        }

        if($user->getEmail() !== $email) {
            return $this->render('loginpage', ['messages' => ['Użytkownik o podanym adresie email nie istnieje!']]);
        }

        if(!password_verify($password, $user->getPassword())) {
            return $this->render('loginpage', ['messages' => ['Podano niepoprawne hasło!']]);
        }

        $new_session = $this->createUserToken($user->getId());
        $token = $new_session;
        $id = $user->getId();

        $stmt = $this->database->connect()->prepare('UPDATE users SET session_token = :token WHERE id = :id');
        $stmt->bindParam(':token', $token, PDO::PARAM_STR);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        setcookie("user_token", $new_session, time() + (356 * 24 * 60 * 60), '/');

        if (ob_get_level()) {
            ob_end_clean();
        }

        $url = "http://$_SERVER[HTTP_HOST]/home";
        header("Location: {$url}");
        exit;
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
        if ($workerId !== 'MK18PSWD') {
            return $this->render('registerpage', ['messages' => ['Nieprawidłowy identyfikator pracownika!']]);
        }

        $user = new User($email, $password, $name, $surname);

        $userRepository = new UserRepository();
        $res = $userRepository->addUser($user);
        if(is_null($res)) {
            return $this->render('registerpage', ['messages' => ['Adres e-mail jest już zajęty.']]);
        }

        header("Location: /loginpage");
        exit;
    }

    public function logout()
    {
        if (isset($_COOKIE['user_token'])) {
            $token = $_COOKIE['user_token'];

            $stmt = $this->database->connect()->prepare('UPDATE users SET session_token = NULL WHERE session_token = :token');
            $stmt->bindParam(':token', $token, PDO::PARAM_STR);
            $stmt->execute();

            setcookie("user_token", "", time() - 3600, '/');
        }

        if (isset($_SESSION)) {
            session_unset();
            session_destroy();
        }

        $url = "http://$_SERVER[HTTP_HOST]/loginpage";
        header("Location: {$url}");
        exit;
    }

    protected function createUserToken(int $userId): string
    {
        // Generowanie tokenu sesji na podstawie ID użytkownika
        // Używamy funkcji random_bytes do generowania tokenu
        $token = bin2hex(random_bytes(32));  // Generowanie unikalnego tokenu
        return $token;
    }
}