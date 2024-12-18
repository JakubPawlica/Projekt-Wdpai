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
            margin-bottom: 40px;
        }

        .input-box > input {
            margin-bottom: 1vw;
        }

        .buttons {
            display: flex;
            flex-direction: row;
            gap: 3em;
        }

        .bottom-btn {
            width: 160px;
            height: 60px;
            border-radius: 40px;
            margin-top: 50px;
            border: 3px solid rgb(110,0,255);
            font-size: 17px;
            background-color: rgb(110,0,255);
            color: white;
        }

        .bottom-btn:hover {
            background-color: white;
            color: rgb(110,0,255);
        }

        .input-radio > label > input {
            margin-bottom: 50px;
            width: 1em;
            height: 1em;
            margin-right: 5px;
        }

        .container {
            font-size: 1.2em;
            margin-right: 50px;
        }

        .switch {
            position: relative;
            display: inline-block;
            width: 58px;
            height: 32px;
            margin-bottom: 80px;
            z-index: 1;
        }

        .switch input { 
            opacity: 0;
            width: 0;
            height: 0;
        }

        .slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #ccc;
            -webkit-transition: .4s;
            transition: .4s;
        }

        .slider:before {
            position: absolute;
            content: "";
            height: 24px;
            width: 24px;
            left: 4px;
            bottom: 4px;
            background-color: white;
            -webkit-transition: .4s;
            transition: .4s;
        }

        input:checked + .slider {
            background-color: rgb(110,0,255);
        }

        input:focus + .slider {
            box-shadow: 0 0 1px rgb(110,0,255);
        }

        input:checked + .slider:before {
            -webkit-transform: translateX(24px);
            -ms-transform: translateX(24px);
            transform: translateX(24px);
        }

        .slider.round {
            border-radius: 32px;
        }

        .slider.round:before {
            border-radius: 50%;
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
            <a href="filter"><li><i class="fa-solid fa-database"></i></i>Zarządzaj</li></a>
            <a href="logout"><li><i class="fa-solid fa-door-open"></i></i>Wyloguj</li></a>
        </ul>
    </aside>
    <main>
        <h1>Zarządzaj bazą wpisów</h1>
        <div class="add-menu">
            <div class="fill-one">
                Zarządzaj bazą
            </div>
            <div class="gaps-one">
                <form action="/exportToExcel" method="GET">
                    <div class="buttons">
                        <button type="submit" class="bottom-btn">Eksport</button>
                    </div>
                </form>

                <form action="/importFromExcel" method="POST" enctype="multipart/form-data">
                    <div class="buttons">
                        <input type="file" name="importFile" accept=".xls,.csv" required>
                        <button type="submit" class="bottom-btn">Import</button>
                    </div>
                </form>
            </div>
        </div>     
    </main>
    <footer>
        Ostatnia aktualizacja 25.11.24
    </footer>
</body>
</html>