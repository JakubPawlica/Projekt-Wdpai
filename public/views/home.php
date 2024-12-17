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

        .edit a p{
            color: blue !important;
        }

        .edit:hover {
            cursor: pointer !important;
            text-decoration: underline !important;
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
            <a href="home"><li><i class="fa-solid fa-list"></i>Lista wpisów</li></a>
            <a href="addEntry"><li><i class="fa-solid fa-plus"></i>Dodaj wpis</li></a>
            <a href="filter"><li><i class="fa-solid fa-magnifying-glass"></i>Filtruj wpisy</li></a>
            <a href="logout"><li><i class="fa-solid fa-door-open"></i></i>Wyloguj</li></a>
        </ul>
    </aside>
    <main>
        <h1>Wpisy z dzisiejszego dnia</h1>
        <div class="list-info">
            <div class="info-one">
                <p>Ilość dzisiejszych wpisów:</p>
                <p class="number"><?= htmlspecialchars($entriesCount); ?></p>
            </div>
            <div class="info-two">
                <p>Liczba użytkowników:</p>
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
                                <a href="deleteEntry?entryId=<?= urlencode($entry->getEntryId()); ?>"
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
        Ostatnia aktualizacja 16.12.24
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
            <a href="deleteEntry?entryId=<?= urlencode($entry->getEntryId()); ?>"
               onclick="return confirm('Czy na pewno chcesz usunąć ten wpis?');">
                <p>Usuń</p>
            </a>
        </td>
    </tr>
</template>