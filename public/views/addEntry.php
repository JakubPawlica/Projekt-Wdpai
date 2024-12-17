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
    </style>
</head>
<body>
    <nav>
        <button onclick="toggleSidebar()"><i class="fa-solid fa-bars"></i></button>
        <p>Witaj! Aby dokonać zmian na wpisach lub dodać wpisy skorzystać z panelu akcji po lewej.</p>
        <div class="user-info">
            <p>Jan Kowalski</p>
            <i class="fa-regular fa-circle-user"></i>
        </div>
    </nav>
    <aside id="sidebar">
        <div class="mk18-logo">
            <img src="public/styles/mk18-icon.png" alt="mk18">
        </div>
        <button onclick="toggleSidebar()"><i class="fa-solid fa-arrow-rotate-left"></i></button>
        <ul>
            <li><i class="fa-regular fa-user"></i>Profil</li>
            <a href="home"><li><i class="fa-solid fa-list"></i>Lista wpisów</li></a>
            <a href="addEntry"><li><i class="fa-solid fa-plus"></i>Dodaj wpis</li></a>
            <a href="filter"><li><i class="fa-solid fa-magnifying-glass"></i>Filtruj wpisy</li></a>
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
                        <input type="text" name="entry_id" required placeholder="ID">
                    </div>
                    <div class="input-box">
                        <input type="text" name="location" required placeholder="Lokalizacja">
                    </div>
            </div>
            <div class="fill-two">
                Zaktualizuj ilość
            </div>
            <div class="gaps-one">
                    <div class="input-box">
                        <input type="text" name="amount" required placeholder="Ilość">
                    </div>
            </div>
            <button type="submit" class="add-btn">Dodaj wpis</button>
            </form>
        </div>     
    </main>
    <footer>
        Ostatnia aktualizacja 25.11.24
    </footer>
</body>
</html>