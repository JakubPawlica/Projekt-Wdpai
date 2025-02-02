
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
    <meta name="current-user" content="<?= htmlspecialchars($name . ' ' . $surname); ?>">
    <link rel="stylesheet" href="public/styles/style_4.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">  
    <script src="https://kit.fontawesome.com/820b3635bf.js" crossorigin="anonymous"></script>
    <script src="public/js/mobile_sidebar.js" defer></script>
    <script type="text/javascript" src="public/js/searchLocator.js" defer></script>
    <script src="public/js/updateUsername.js"></script>
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

        .export_xls {
            margin-left: 30px;
            background-color: #c2fbd7;
            border-radius: 100px;
            box-shadow: rgba(44, 187, 99, .2) 0 -25px 18px -14px inset,rgba(44, 187, 99, .15) 0 1px 2px,rgba(44, 187, 99, .15) 0 2px 4px,rgba(44, 187, 99, .15) 0 4px 8px,rgba(44, 187, 99, .15) 0 8px 16px,rgba(44, 187, 99, .15) 0 16px 32px;
            color: green;
            cursor: pointer;
            display: inline-block;
            font-family: CerebriSans-Regular,-apple-system,system-ui,Roboto,sans-serif;
            padding: 7px 20px;
            text-align: center;
            text-decoration: none;
            transition: all 250ms;
            border: 0;
            font-size: 16px;
            user-select: none;
            -webkit-user-select: none;
            touch-action: manipulation;
        }


        .export_xls:hover {
            box-shadow: rgba(44,187,99,.35) 0 -25px 18px -14px inset,rgba(44,187,99,.25) 0 1px 2px,rgba(44,187,99,.25) 0 2px 4px,rgba(44,187,99,.25) 0 4px 8px,rgba(44,187,99,.25) 0 8px 16px,rgba(44,187,99,.25) 0 16px 32px;
            transform: scale(1.05) rotate(-1deg);
        }


        @media(max-width: 768px){
            .searchTerm {
                width: 100%;
                padding: 5px;
                height: 24px;
            }

            .export_xls{
                display: none;
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
            <a href="locator"><li><i class="fa-solid fa-magnifying-glass"></i>Lokalizator</li></a>
            <a href="manage"><li><i class="fa-solid fa-database"></i></i>Zarządzaj</li></a>
            <?php if ($isAdmin): ?>
                <a href="adminpage"><li><i class="fa-solid fa-lock-open"></i>Admin</li></a>
            <?php endif; ?>
            <a href="logout"><li><i class="fa-solid fa-door-open"></i></i>Wyloguj</li></a>
        </ul>
    </aside>
    <main>
        <h1>Rozmieszczenie produktów</h1>
        <div class="search">
            <input class="searchTerm" placeholder="Wpisz ID lub Lokalizację">
            <button type="submit" class="searchButton">
                <i class="fa-solid fa-magnifying-glass"></i>
            </button>
            <form action="/exportToExcel_locator" method="GET">
                <button class="export_xls">
                    Eksportuj do pliku xls
                </button>
            </form>
        </div>
        <div class="list">
            <?php if (isset($entries) && count($entries) > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>ID Produktu</th>
                        <th>Lokalizacje</th>
                        <th>Ilość na lokalizacji</th>
                        <th>Łączna ilość</th>
                    </tr>
                </thead>
                <tbody class="locator-list">
                    <?php foreach ($locators as $locator): ?>
                        <tr>
                            <td><?= htmlspecialchars($locator['entry_id']) ?></td>
                            <td><?= nl2br(htmlspecialchars($locator['all_locations'])) ?></td>
                            <td><?= nl2br(htmlspecialchars($locator['all_amounts'])) ?></td>
                            <td><?= htmlspecialchars($locator['total_amount']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <?php else: ?>
                <p>Dodaj wpisy aby utworzyć podsumowanie.</p>
            <?php endif; ?>
        </div>
    </main>
    <footer>
        Ostatnia aktualizacja 12.01.25
    </footer>
</body>
</html>

<template id="locator-template">
    <tr>
        <td class="entry-id">entry_id</td>
        <td class="locations">locations</td>
        <td class="amounts">amounts</td>
        <td class="total-amount">total_amount</td>
    </tr>
</template>