
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
    <script src="public/views/adding_entry.js" defer></script>
    <script src="public/views/cursor_focus.js" defer></script>
    <title>Dodaj wpis!</title>
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

        .input-box > input {
            margin-bottom: 25px;
        }

        .error-message {
            color: red;
            font-weight: bold;
            margin-top: 10px;
        }

        .calc-box {
            display: flex !important;
            align-items: center !important;
        }

        .amount_input {
            margin: 0 !important;
            border-radius: 0 !important;
            height: 64px !important;
            width: 65px !important;
            text-align: center !important;
            padding-left: 0.5vw !important;
            padding-right: 0.5vw !important;
            border-bottom: 3px solid rgb(110,0,255) !important;
            border-top: 3px solid rgb(110,0,255) !important;
        }

        .last_button_minus {
            height: 70px;
            width: 50px;
            border: 2px solid rgb(110,0,255);
            border-radius: 50px 0px 0px 50px;
            font-size: 17px;
            background-color: rgb(110,0,255);
            color: white;
        }

        .last_button_plus {
            height: 70px;
            width: 50px;
            border: 2px solid rgb(110,0,255);
            border-radius: 0px 50px 50px 0px;
            font-size: 17px;
            background-color: rgb(110,0,255);
            color: white;
        }

        .mid_button_minus {
            height: 70px;
            width: 50px;
            border-top: 2px solid rgb(110,0,255);
            border-bottom: 2px solid rgb(110,0,255);
            border-left: 3px solid rgb(166, 158, 158);
            border-right: 3px solid rgb(166, 158, 158);
            font-size: 17px;
            background-color: rgb(110,0,255);
            color: white;
        }

        .mid_button_plus {
            height: 70px;
            width: 50px;
            border-top: 2px solid rgb(110,0,255);
            border-bottom: 2px solid rgb(110,0,255);
            border-left: 3px solid rgb(166, 158, 158);
            border-right: 3px solid rgb(166, 158, 158);
            font-size: 17px;
            background-color: rgb(110,0,255);
            color: white;
        }

        .minus_button {
            height: 70px;
            width: 55px;
            border: 2px solid rgb(110,0,255);
            font-size: 25px;
            background-color: rgb(110,0,255);
            color: white;
        }

        .plus_button {
            height: 70px;
            width: 55px;
            border: 2px solid rgb(110,0,255);
            font-size: 25px;
            background-color: rgb(110,0,255);
            color: white;
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

            .fill-two {
                width: 54vw;
                font-size: 4.5vw;
                margin-bottom: 5vw;
            }

            .gaps-one > .input-box > input {
                height: 13vw;
                width: 80%;
                font-size: 3vw;
                padding-left: 3vw;
                margin-right: 0;
            }

            .amount_input {
                height: 64px !important;
                width: 45px !important;
                text-align: center !important;
                padding-left: 0.1vw !important;
                padding-right: 0.1vw !important;
                border-bottom: 3px solid rgb(110,0,255) !important;
                border-top: 3px solid rgb(110,0,255) !important;
            }

            .last_button_minus {
                height: 70px;
                width: 48px;
                border: 2px solid rgb(110,0,255);
                border-radius: 30px 0px 0px 30px;
                font-size: 17px;
                background-color: rgb(110,0,255);
                color: white;
            }

            .last_button_plus {
                height: 70px;
                width: 48px;
                border: 2px solid rgb(110,0,255);
                border-radius: 0px 30px 30px 0px;
                font-size: 17px;
                background-color: rgb(110,0,255);
                color: white;
            }

            .mid_button_minus {
                height: 70px;
                width: 48px;
                border-top: 2px solid rgb(110,0,255);
                border-bottom: 2px solid rgb(110,0,255);
                border-left: 3px solid rgb(166, 158, 158);
                border-right: 3px solid rgb(166, 158, 158);
                font-size: 17px;
                background-color: rgb(110,0,255);
                color: white;
            }

            .mid_button_plus {
                height: 70px;
                width: 48px;
                border-top: 2px solid rgb(110,0,255);
                border-bottom: 2px solid rgb(110,0,255);
                border-left: 3px solid rgb(166, 158, 158);
                border-right: 3px solid rgb(166, 158, 158);
                font-size: 17px;
                background-color: rgb(110,0,255);
                color: white;
            }

            .minus_button {
                height: 70px;
                width: 50px;
                border: 2px solid rgb(110,0,255);
                font-size: 25px;
                background-color: rgb(110,0,255);
                color: white;
            }

            .plus_button {
                height: 70px;
                width: 50px;
                border: 2px solid rgb(110,0,255);
                font-size: 25px;
                background-color: rgb(110,0,255);
                color: white;
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
        <h1>Dodaj wpis</h1>
        <div class="add-menu">
            <form action="addEntry" method="POST">
                    <?php
                    if(isset($messages)){
                        foreach($messages as $message){
                            echo $message;
                        }
                    }
                    ?>
                <div class="fill-one">
                    Uzupełnij pola
                </div>
                <div class="gaps-one">
                        <div class="input-box">
                            <input type="text" id="entry_id" name="entry_id" required placeholder="ID">
                        </div>
                        <div class="input-box">
                            <input type="text" name="location" required placeholder="Lokalizacja">
                        </div>
                </div>
                <div class="fill-two">
                    Zaktualizuj ilość
                </div>
                <div class="gaps-one">
                        <div class="input-box calc-box">
                            <button type="button" class="last_button_minus">10</button>
                            <button type="button" class="mid_button_minus">5</button>
                            <button type="button" class="minus_button">-</button>
                            <input type="text" class="amount_input" name="amount" required placeholder="Ilość" value="0">
                            <button type="button" class="plus_button">+</button>
                            <button type="button" class="mid_button_plus">5</button>
                            <button type="button" class="last_button_plus">10</button>
                        </div>
                </div>
                <button type="submit" class="add-btn">Dodaj wpis</button>
            </form>
        </div>     
    </main>
    <footer>
        Ostatnia aktualizacja 06.01.25
    </footer>
</body>
</html>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        var entryInput = document.getElementById('entry_id');
        if (entryInput) {
            entryInput.focus();
        }
    });
</script>
