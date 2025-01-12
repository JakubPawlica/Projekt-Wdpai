
<?php
require_once __DIR__.'/../../src/repository/UserRepository.php';

$userRepository = new UserRepository();
$isAdmin = false;

if (isset($_COOKIE['user_token'])) {
    $userToken = $_COOKIE['user_token'];
    $isAdmin = $userRepository->isAdmin($userToken);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="public/styles/style_5.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">  
    <script src="https://kit.fontawesome.com/820b3635bf.js" crossorigin="anonymous"></script>
    <script src="public/js/mobile_sidebar.js" defer></script>
    <title>Profil użytkownika</title>
    <style>
        a {
            text-decoration: none;
            color: white;
        }

        a > li {
            display: flex;
            align-items: center;
            padding-left: 15px;
            padding-right: 15px;
            width: 180px;
            height: 50px;
        }

        a > li > i {
            margin-right: 2em;
        }

        a:hover {
            color: rgb(110,0,255);
            background-color: white;
        }

        .gaps-one > form {
            display: flex;
            flex-direction: column;
        }

        .input-box > input {
            border: none;
            border-radius: 0.4vw;
            background-color: rgb(212, 212, 212, 0.4);
            height: 60px;
            width: 400px;
            padding-left: 0.5vw;
            font-size: 17px;
            margin-right: 50px;
            margin-bottom: 30px;
            margin-top: 10px;
        }

        .input-box {
            display: flex;
            flex-direction: column;
        }

        .input-box > label {
            margin-right: 10px;
        }

        @media(max-width: 768px) {

            .add-menu {
                width: auto;
                height: auto;
                border-radius: 30px;
                background-color: rgb(255, 255, 255);
                display: flex;
                flex-direction: column;
            }

            .fill-one {
                width: 54vw;
                font-size: 4.5vw;
                margin-bottom: 5vw;
            }

            .input-box > input {
                height: 13vw;
                width: 80%;
                font-size: 3vw;
                padding-left: 3vw;
                margin-right: 0;
            }

        }
    </style>
</head>
<body>
    <nav>
        <button onclick="toggleSidebar()"><i class="fa-solid fa-bars"></i></button>
        <p>Witaj! Aby dokonać zmian na wpisach lub dodać wpisy skorzystaj z panelu akcji po lewej.</p>
        <div class="user-info">
            <p><?= htmlspecialchars($name) . ' ' . htmlspecialchars($surname); ?></p>
            <i class="fa-regular fa-circle-user"></i>
        </div>
    </nav>
    <aside id="sidebar">
        <div class="mk18-logo">
            <img src="public/styles/mk18-icon.png" alt="mk18">
        </div>
        <button onclick="toggleSidebar()"><i class="fa-solid fa-arrow-rotate-left"></i></button>
        <ul>
            <a href="profile"><li><i class="fa-regular fa-user"></i>Profil</li></a>
            <a href="home"><li><i class="fa-solid fa-list"></i>Lista wpisów</li></a>
            <a href="addEntry"><li><i class="fa-solid fa-plus"></i>Dodaj wpis</li></a>
            <a href="manage"><li><i class="fa-solid fa-database"></i></i>Zarządzaj</li></a>
            <?php if ($isAdmin): ?>
                <a href="adminpage"><li><i class="fa-solid fa-lock-open"></i>Admin</li></a>
            <?php endif; ?>
            <a href="logout"><li><i class="fa-solid fa-door-open"></i></i>Wyloguj</li></a>
        </ul>
    </aside>
    <main>
        <h1>Profil użytkownika</h1>
        <div class="add-menu">
            <div class="fill-one">
                Zmień swoje dane
            </div>
            <div class="gaps-one">
                <?php if (isset($messages)): ?>
                    <ul>
                        <?php foreach ($messages as $message): ?>
                            <li><?= htmlspecialchars($message) ?></li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>

                <?php if (isset($_GET['success']) && $_GET['success'] === 'true'): ?>
                    <p style="color: green;">Dane zostały pomyślnie zaktualizowane!</p>
                <?php endif; ?>

                <form action="/updateUserName" method="POST">

                    <input type="hidden" name="id" value="<?= htmlspecialchars($user['id']) ?>">

                    <div class="input-box">
                        <label for="name">Imię:</label>
                        <input type="text" id="name" name="name" value="<?= htmlspecialchars($user['name']) ?>" required>
                    </div>

                    <div class="input-box">
                        <label for="surname">Nazwisko:</label>
                        <input type="text" id="surname" name="surname" value="<?= htmlspecialchars($user['surname']) ?>" required>
                    </div>

                    <button type="submit" class="add-btn">Zatwierdź</button>
                </form>
            </div>
        </div>     
    </main>
    <footer>
        Ostatnia aktualizacja 12.01.25
    </footer>
</body>
</html>