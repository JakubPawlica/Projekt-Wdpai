<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="public/styles/style_4.css">
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
            <li><i class="fa-regular fa-user"></i>Profil</li>
            <li><i class="fa-solid fa-list"></i>Lista wpisów</li>
            <li><i class="fa-solid fa-plus"></i>Dodaj wpis</li>
            <li><i class="fa-solid fa-magnifying-glass"></i>Filtruj wpisy</li>
            <a href="logout"><li><i class="fa-solid fa-door-open"></i></i>Wyloguj</li></a>
        </ul>
    </aside>
    <main>
        <h1>Wpisy z dzisiejszego dnia</h1>
        <div class="list-info">
            <div class="info-one">
                <p>Ilość dzisiejszych wpisów:</p>
                <p class="number">142</p>
            </div>
            <div class="info-two">
                <p>Liczba użytkowników:</p>
                <p class="number">7</p>
            </div>
        </div>
        <div class="list">
            <table>
                <tr>
                    <th>Użytkownik</th>
                    <th>ID</th>
                    <th>Lokalizacja</th>
                    <th>Ilość</th>
                    <th>Akcja</th>
                </tr>
                <tr>
                    <td>Adam Nowak</td>
                    <td>25436788</td>
                    <td>DB-1902</td>
                    <td>-9</td>
                    <td class="edit">Edytuj</td>
                </tr>
                <tr>
                    <td>Jan Nowak</td>
                    <td>25436788</td>
                    <td>DB-1902</td>
                    <td>+4</td>
                    <td class="edit">Edytuj</td>
                </tr>
                <tr>
                    <td>Ola Kowalska</td>
                    <td>25436788</td>
                    <td>DB-1902</td>
                    <td>-3</td>
                    <td class="edit">Edytuj</td>
                </tr>
                <tr>
                    <td>Katarzyna Nowak</td>
                    <td>25436788</td>
                    <td>DB-1902</td>
                    <td>+12</td>
                    <td class="edit">Edytuj</td>
                </tr>
                <tr>
                    <td>Adam Nowak</td>
                    <td>25436788</td>
                    <td>DB-1902</td>
                    <td>-9</td>
                    <td class="edit">Edytuj</td>
                </tr>
                <tr>
                    <td>Jan Nowak</td>
                    <td>25436788</td>
                    <td>DB-1902</td>
                    <td>+4</td>
                    <td class="edit">Edytuj</td>
                </tr>
                <tr>
                    <td>Ola Kowalska</td>
                    <td>25436788</td>
                    <td>DB-1902</td>
                    <td>-3</td>
                    <td class="edit">Edytuj</td>
                </tr>
                <tr>
                    <td>Katarzyna Nowak</td>
                    <td>25436788</td>
                    <td>DB-1902</td>
                    <td>+12</td>
                    <td class="edit">Edytuj</td>
                </tr>
                <tr>
                    <td>Adam Nowak</td>
                    <td>25436788</td>
                    <td>DB-1902</td>
                    <td>-9</td>
                    <td class="edit">Edytuj</td>
                </tr>
                <tr>
                    <td>Jan Nowak</td>
                    <td>25436788</td>
                    <td>DB-1902</td>
                    <td>+4</td>
                    <td class="edit">Edytuj</td>
                </tr>
                <tr>
                    <td>Ola Kowalska</td>
                    <td>25436788</td>
                    <td>DB-1902</td>
                    <td>-3</td>
                    <td class="edit">Edytuj</td>
                </tr>
                <tr>
                    <td>Katarzyna Nowak</td>
                    <td>25436788</td>
                    <td>DB-1902</td>
                    <td>+12</td>
                    <td class="edit">Edytuj</td>
                </tr>
                <tr>
                    <td>Adam Nowak</td>
                    <td>25436788</td>
                    <td>DB-1902</td>
                    <td>-9</td>
                    <td class="edit">Edytuj</td>
                </tr>
                <tr>
                    <td>Jan Nowak</td>
                    <td>25436788</td>
                    <td>DB-1902</td>
                    <td>+4</td>
                    <td class="edit">Edytuj</td>
                </tr>
                <tr>
                    <td>Ola Kowalska</td>
                    <td>25436788</td>
                    <td>DB-1902</td>
                    <td>-3</td>
                    <td class="edit">Edytuj</td>
                </tr>
                <tr>
                    <td>Katarzyna Nowak</td>
                    <td>25436788</td>
                    <td>DB-1902</td>
                    <td>+12</td>
                    <td class="edit">Edytuj</td>
                </tr>
                <tr>
                    <td>Adam Nowak</td>
                    <td>25436788</td>
                    <td>DB-1902</td>
                    <td>-9</td>
                    <td class="edit">Edytuj</td>
                </tr>
                <tr>
                    <td>Jan Nowak</td>
                    <td>25436788</td>
                    <td>DB-1902</td>
                    <td>+4</td>
                    <td class="edit">Edytuj</td>
                </tr>
                <tr>
                    <td>Ola Kowalska</td>
                    <td>25436788</td>
                    <td>DB-1902</td>
                    <td>-3</td>
                    <td class="edit">Edytuj</td>
                </tr>
                <tr>
                    <td>Katarzyna Nowak</td>
                    <td>25436788</td>
                    <td>DB-1902</td>
                    <td>+12</td>
                    <td class="edit">Edytuj</td>
                </tr>
                <tr>
                    <td>Adam Nowak</td>
                    <td>25436788</td>
                    <td>DB-1902</td>
                    <td>-9</td>
                    <td class="edit">Edytuj</td>
                </tr>
                <tr>
                    <td>Jan Nowak</td>
                    <td>25436788</td>
                    <td>DB-1902</td>
                    <td>+4</td>
                    <td class="edit">Edytuj</td>
                </tr>
                <tr>
                    <td>Ola Kowalska</td>
                    <td>25436788</td>
                    <td>DB-1902</td>
                    <td>-3</td>
                    <td class="edit">Edytuj</td>
                </tr>
                <tr>
                    <td>Katarzyna Nowak</td>
                    <td>25436788</td>
                    <td>DB-1902</td>
                    <td>+12</td>
                    <td class="edit">Edytuj</td>
                </tr>
                <tr>
                    <td>Adam Nowak</td>
                    <td>25436788</td>
                    <td>DB-1902</td>
                    <td>-9</td>
                    <td class="edit">Edytuj</td>
                </tr>
                <tr>
                    <td>Jan Nowak</td>
                    <td>25436788</td>
                    <td>DB-1902</td>
                    <td>+4</td>
                    <td class="edit">Edytuj</td>
                </tr>
                <tr>
                    <td>Ola Kowalska</td>
                    <td>25436788</td>
                    <td>DB-1902</td>
                    <td>-3</td>
                    <td class="edit">Edytuj</td>
                </tr>
                <tr>
                    <td>Katarzyna Nowak</td>
                    <td>25436788</td>
                    <td>DB-1902</td>
                    <td>+12</td>
                    <td class="edit">Edytuj</td>
                </tr>
                <tr>
                    <td>Adam Nowak</td>
                    <td>25436788</td>
                    <td>DB-1902</td>
                    <td>-9</td>
                    <td class="edit">Edytuj</td>
                </tr>
                <tr>
                    <td>Jan Nowak</td>
                    <td>25436788</td>
                    <td>DB-1902</td>
                    <td>+4</td>
                    <td class="edit">Edytuj</td>
                </tr>
                <tr>
                    <td>Ola Kowalska</td>
                    <td>25436788</td>
                    <td>DB-1902</td>
                    <td>-3</td>
                    <td class="edit">Edytuj</td>
                </tr>
                <tr>
                    <td>Katarzyna Nowak</td>
                    <td>25436788</td>
                    <td>DB-1902</td>
                    <td>+12</td>
                    <td class="edit">Edytuj</td>
                </tr>
            </table>
        </div>
    </main>
    <footer>
        Ostatnia aktualizacja 25.11.24
    </footer>
</body>
</html>