
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
    <link rel="stylesheet" href="public/styles/style_4.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">  
    <script src="https://kit.fontawesome.com/820b3635bf.js" crossorigin="anonymous"></script>
    <script src="public/views/mobile_sidebar.js" defer></script>
    <script type="text/javascript" src="public/views/search.js" defer></script>
    <script src="public/views/updateUsername.js"></script>
    <title>Witaj w aktualizatorze MK18!</title>
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

        .edit a p{
            color: blue !important;
        }

        .edit:hover {
            cursor: pointer !important;
            text-decoration: underline !important;
        }

        .search {
            z-index: 1;
        }

        .searchTerm {
            width: 28%;
            border: 3px solid rgb(110,0,255);
            border-right: none;
            padding: 5px;
            height: 24px;
            border-radius: 5px 0 0 5px;
            outline: none;
            color: #9DBFAF;
        }

        .searchTerm:focus{
            color: rgb(0, 0, 0);
        }

        @media(max-width: 768px){
            .searchTerm {
                width: 100%;
                padding: 5px;
                height: 24px;
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
        <h1>Wpisy z dzisiejszego dnia</h1>
        <div class="list-info">
            <div class="info-one">
                <p class="number-info">Ilość dzisiejszych wpisów:</p>
                <p class="number"><?= htmlspecialchars($entriesCount); ?></p>
            </div>
            <div class="info-two">
                <p class="number-info">Liczba użytkowników:</p>
                <p class="number"><?= htmlspecialchars($usersCount); ?></p>
            </div>
        </div>
        <div class="search">
            <input class="searchTerm" placeholder="Szukaj wpisów po dowolnym z kryteriów">
            <button type="submit" class="searchButton">
                <i class="fa-solid fa-magnifying-glass"></i>
            </button>
        </div>
        <div class="list">
            <?php if (isset($entries) && count($entries) > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>Użytkownik</th>
                        <th>ID</th>
                        <th>Lokalizacja</th>
                        <th>Ilość</th>
                        <th>Akcja</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($entries as $entry): ?>
                        <tr>
                            <td><?= htmlspecialchars($entry->getUserName()); ?></td>
                            <td><?= htmlspecialchars($entry->getEntryId()); ?></td>
                            <td><?= htmlspecialchars($entry->getLocation()); ?></td>
                            <td><?= htmlspecialchars($entry->getAmount()); ?></td>
                            <td class="edit">
                                <!-- Link do usunięcia wpisu -->
                                <a href="deleteEntry?id=<?= urlencode($entry->getId()); ?>"
                                   onclick="return confirm('Czy na pewno chcesz usunąć ten wpis?');">
                                    <p>Usuń</p>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <?php else: ?>
                <p>Brak wpisów do wyświetlenia.</p>
            <?php endif; ?>
        </div>
    </main>
    <footer>
        Ostatnia aktualizacja 06.01.25
    </footer>
</body>
</html>

<template id="entry-template">
    <tr>
        <td class="user_name">user name</td>
        <td class="number_id">number_id</td>
        <td class="location">location</td>
        <td class="amount">amount</td>
        <td class="edit">
            <!-- Link do usunięcia wpisu -->
            <a href="deleteEntry?id=<?= urlencode($entry->getId()); ?>"
               onclick="return confirm('Czy na pewno chcesz usunąć ten wpis?');">
                <p>Usuń</p>
            </a>
        </td>
    </tr>
</template>