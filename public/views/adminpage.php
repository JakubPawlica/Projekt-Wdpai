
<?php
require_once __DIR__.'/../../src/repository/UserRepository.php';

$userRepository = new UserRepository();
$isAdmin = false;

// Sprawdź, czy użytkownik jest zalogowany
if (isset($_COOKIE['user_token'])) {
    $userToken = $_COOKIE['user_token'];
    $isAdmin = $userRepository->isAdmin($userToken); // Sprawdza, czy użytkownik jest adminem
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
    <script src="mobile_sidebar.js" defer></script>
    <title>Witaj w aktulizatorze MK18!</title>
    
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
            margin-top: 20px;
            margin-bottom: 20px;
            display: flex;
            flex-direction: column;
            gap: 1em;
        }

        .gaps-one {
            margin-bottom: 20px;
        }

        .gaps-two {
            display: flex;
            flex-direction: row;
        }

        .gaps-two > select {
            margin-right: 30px;
        }

        .gaps-two > button {
            margin-right: 5px;
        }

        .input-box > input {
            margin-bottom: 1vw;
        }

        .admin-btn {
            width: 140px;
            height: 45px;
            border-radius: 40px;
            border: 3px solid rgb(110,0,255);
            font-size: 17px;
            background-color: rgb(110,0,255);
            color: white;
        }

        .admin-btn:hover {
            background-color: white;
            color: rgb(110,0,255);
        }

        .tooltip {
            position: relative;
            display: inline-block;
        }

        .tooltip .tooltiptext {
            visibility: hidden;
            width: 300px; /* Zwiększona szerokość */
            background-color: #555;
            color: #fff;
            text-align: center;
            border-radius: 6px;
            padding: 5px 0;
            position: absolute;
            z-index: 1;
            bottom: 125%;
            left: 50%;
            margin-left: -150px; /* Zwiększony margines na szerokość */
            opacity: 0;
            transition: opacity 0.3s;
        }

        .tooltip .tooltiptext::after {
            content: "";
            position: absolute;
            top: 100%;
            left: 50%;
            margin-left: -5px;
            border-width: 5px;
            border-style: solid;
            border-color: #555 transparent transparent transparent;
        }

        .tooltip:hover .tooltiptext {
            visibility: visible;
            opacity: 1;
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
        <h1>Panel admina</h1>

        <?php if (isset($_GET['success'])): ?>
            <?php if ($_GET['success'] === 'blocked'): ?>
                <p style="color: green;">Użytkownik został zablokowany!</p>
            <?php elseif ($_GET['success'] === 'unblocked'): ?>
                <p style="color: green;">Użytkownik został odblokowany!</p>
            <?php elseif ($_GET['success'] === 'admin_granted'): ?>
                <p style="color: green;">Rola Administratora została nadana!</p>
            <?php elseif ($_GET['success'] === 'admin_removed'): ?>
                <p style="color: green;">Rola Administratora została odebrana!</p>
            <?php elseif ($_GET['success'] === 'deleted'): ?>
                <p style="color: green;">Użytkownik został usunięty!</p>
            <?php endif; ?>
        <?php endif; ?>

        <?php if (isset($_GET['error'])): ?>
            <p style="color: red;">Wystąpił błąd! Upewnij się, że wybrałeś użytkownika.</p>
        <?php endif; ?>

        <div class="add-menu">
            <div class="fill-one">
                Zarządzaj użytkownikami
            </div>

            <!-- Blokowanie użytkownika -->
            <div class="gaps-one">
                <form action="/blockUser" method="POST">
                    <label for="block-user">Zablokuj użytkownika:</label>
                    <div class="gaps-two">
                        <select name="user_id" id="block-user">
                            <option value="" disabled selected>Wybierz</option>
                            <?php foreach ($users as $user): ?>
                                <?php if ($user['id'] != $userId): ?> <!-- Exclude current user -->
                                    <option value="<?= $user['id'] ?>"><?= htmlspecialchars($user['email']) ?></option>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </select>
                        <button type="submit" class="admin-btn">Zablokuj</button>
                        <div class="tooltip"><i class="fa-regular fa-circle-question"></i>
                            <span class="tooltiptext">Pozwala zablokować konto użytkownika. Wpisy użytkownika pozostaną w liście wpisów.</span>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Odblokowywanie użytkownika -->
            <div class="gaps-one">
                <form action="/unblockUser" method="POST">
                    <label for="unblock-user">Odblokuj użytkownika:</label>
                    <div class="gaps-two">
                        <select name="user_id" id="unblock-user">
                            <option value="" disabled selected>Wybierz</option>
                            <?php foreach ($users as $user): ?>
                                <?php if ($user['id'] != $userId): ?> <!-- Exclude current user -->
                                    <option value="<?= $user['id'] ?>"><?= htmlspecialchars($user['email']) ?></option>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </select>
                        <button type="submit" class="admin-btn">Odblokuj</button>
                        <div class="tooltip"><i class="fa-regular fa-circle-question"></i>
                            <span class="tooltiptext">Pozwala odblokować konto zablokowanego wcześniej użytkownika.</span>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Przyznawanie roli admina -->
            <div class="gaps-one">
                <form action="/grantAdmin" method="POST">
                    <label for="grant-admin">Nadaj prawa administratora:</label>
                    <div class="gaps-two">
                        <select name="user_id" id="grant-admin">
                            <option value="" disabled selected>Wybierz</option>
                            <?php foreach ($users as $user): ?>
                                <?php if ($user['id'] != $userId): ?> <!-- Exclude current user -->
                                    <option value="<?= $user['id'] ?>"><?= htmlspecialchars($user['email']) ?></option>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </select>
                        <button type="submit" class="admin-btn">Nadaj</button>
                        <div class="tooltip"><i class="fa-regular fa-circle-question"></i>
                            <span class="tooltiptext">Pozwala nadać prawa administratora wybranemu użytkownikowi.</span>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Odbieranie praw administratora -->
            <div class="gaps-one">
                <form action="/removeAdmin" method="POST">
                    <label for="remove-admin">Odbierz prawa administratora:</label>
                    <div class="gaps-two">
                        <select name="user_id" id="remove-admin">
                            <option value="" disabled selected>Wybierz</option>
                            <?php foreach ($users as $user): ?>
                                <?php if ($user['id'] != $userId): ?> <!-- Exclude current user -->
                                    <option value="<?= $user['id'] ?>"><?= htmlspecialchars($user['email']) ?></option>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </select>
                        <button type="submit" class="admin-btn">Odbierz</button>
                        <div class="tooltip"><i class="fa-regular fa-circle-question"></i>
                            <span class="tooltiptext">Pozwala odebrać prawa administratora wybranemu użytkownikowi.</span>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Usuwanie użytkownika -->
            <div class="gaps-one">
                <form action="/deleteUser" method="POST">
                    <label for="delete-user">Usuń użytkownika:</label>
                    <div class="gaps-two">
                        <select name="user_id" id="delete-user">
                            <option value="" disabled selected>Wybierz</option>
                            <?php foreach ($users as $user): ?>
                                <?php if ($user['id'] != $userId): ?> <!-- Exclude current user -->
                                    <option value="<?= $user['id'] ?>"><?= htmlspecialchars($user['email']) ?></option>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </select>
                        <button type="submit" class="admin-btn">Usuń</button>
                        <div class="tooltip"><i class="fa-regular fa-circle-question"></i>
                            <span class="tooltiptext">Pozwala usunąć konto wybranego użytkownika.<p style="color:red; font-weight: bold"><br>UWAGA!</p><p>Usunięcie użytkownika wiąże się z usunięciem jego obecnych wpisów</p></span>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </main>
    <footer>
        Ostatnia aktualizacja 19.12.24
    </footer>
</body>
</html>