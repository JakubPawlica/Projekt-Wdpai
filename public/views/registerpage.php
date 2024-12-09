<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="public/styles/style_3.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">  
    <script src="https://kit.fontawesome.com/820b3635bf.js" crossorigin="anonymous"></script>
    <script type="text/javascript" src="./public/js/script.js" defer></script>
    <title>Zarejestruj się</title>
    <style>
        .no-valid {
            border: 0.3vw solid red !important;
            border-radius: 0.4vw !important;
        }
    </style>
</head>
<body>
    <main>
        <div class="login-section">
            <div class="mk18-logo">
                <img src="public/styles/mk18-icon.png" alt="mk18">
            </div>
            <div class="choice-section">
                <a href="loginpage"><button class="login">Zaloguj</button></a>
                <a href="registerpage"><button class="register">Zarejestruj</button></a>
            </div>
            <div class="login-form">
                <form action="registerpage" method="POST">
                    <div class="messages" style="margin-top: 15px; color:red; font-weight: bold">
                        <?php if(isset($messages) && is_array($messages)): ?>
                            <?php foreach($messages as $message): ?>
                                <?php echo $message; ?>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                    <div class="input-box">
                        <i class="fa-solid fa-user-pen"></i>
                        <input type="name" name="name" required placeholder="Imię">
                    </div>
                    <div class="input-box">
                        <i class="fa-solid fa-user-group"></i>
                        <input type="surname" name="surname" required placeholder="Nazwisko">
                    </div>
                    <div class="input-box">
                        <i class="fa-solid fa-envelope"></i>
                        <input type="email" name="email" required placeholder="Email">
                    </div>
                    <div class="input-box">
                        <i class="fa-solid fa-key"></i>
                        <input type="password" name="password" required placeholder="Hasło">
                    </div>
                    <div class="input-box">
                        <i class="fa-solid fa-user-shield"></i>
                        <input type="worker_id" name="worker_id" required placeholder="Identyfikator pracownika">
                    </div>
                    <div class="id-info">
                        Poproś rekrutera o identyfikator aby potwierdzić, że jesteś pracownikiem.
                    </div>
                    <button type="sumbit" class="login-btn">Zarejestruj się</button>
                </form>
            </div>
        </div>
    </main>
</body>
</html>