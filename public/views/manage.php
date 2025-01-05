
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
    <script src="public/views/mobile_sidebar.js" defer></script>
    <title>Zarządzaj bazą!</title>
    
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
        }

        .input-box > input {
            margin-bottom: 1vw;
        }

        .buttons {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: start;
            gap: 3em;
        }

        .bottom-btn {
            width: 160px;
            height: 60px;
            border-radius: 40px;
            border: 3px solid rgb(110,0,255);
            font-size: 17px;
            background-color: rgb(110,0,255);
            color: white;
        }

        .bottom-btn:hover {
            background-color: white;
            color: rgb(110,0,255);
        }

        .danger-section {
            margin-top: 50px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            flex-wrap: wrap;
        }

        .danger {
            color: rgb(110,0,255);
            font-weight: bold;
            font-size: 17px;
        }

        .danger-sign {
            color: rgb(110,0,255);
            font-size: 30px;
            margin-bottom: 15px;
        }

        @media(max-width: 768px){

            main > h1 {
                font-size: 6.5vw;
            }

            .bottom-btn {
                width: 45vw;
                height: 12vw;
                border-radius: 40px;
                border: 3px solid rgb(110,0,255);
                font-size: 17px;
                background-color: rgb(110,0,255);
                color: white;
            }

            .add-menu {
                width: auto;
                height: auto;
                display: flex;
                flex-direction: row;
                flex-wrap: wrap;
                justify-content: center;
                align-items: center;
            }

            .fill-one {
                width: 100%;
                font-size: 4.5vw;
                margin-bottom: 3vw;
            }

            .buttons {
                display: flex;
                flex-direction: column;
                gap: 2em;
                justify-content: center;
                align-items: center;
            }

            .danger-section {
                margin-top: 50px;
                display: flex;
                flex-direction: column;
                justify-content: center;
                align-items: center;
                flex-wrap: wrap;
            }

            .danger {
                color: rgb(110,0,255);
                font-weight: bold;
                font-size: 3vw;
                text-align: center;
            }

            .danger-sign {
                color: rgb(110,0,255);
                font-size: 30px;
                margin-bottom: 20px;
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
        <h1>Zarządzaj bazą wpisów</h1>
        <div class="add-menu">
            <div class="fill-one">
                Zarządzaj wpisami
            </div>
            <div class="gaps-one">
                <form action="/exportToExcel" method="GET">
                    <div class="buttons">
                        <button type="submit" class="bottom-btn">Eksport</button>
                    </div>
                </form>
            </div>
            <div class="gaps-one">
                <form action="/importFromExcel" method="POST" enctype="multipart/form-data">
                    <div class="buttons">
                        <button type="submit" class="bottom-btn">Import</button>
                        <input type="file" class="input-file" name="importFile" accept=".xls,.xlsx" required>
                    </div>
                </form>
            </div>
            <div class="danger-section">
                <i class="fa-solid fa-circle-exclamation danger-sign"></i>
                <p class="danger">Dokonując importu zastępujesz obecną bazę wpisów bazą importowaną. Utracisz wszystkie dane obecnej bazy wpisów.</p>
            </div>
        </div>     
    </main>
    <footer>
        Ostatnia aktualizacja 19.12.24
    </footer>
</body>
</html>