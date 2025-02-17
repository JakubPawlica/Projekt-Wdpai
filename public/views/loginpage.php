<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="public/styles/style_2.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">  
    <script src="https://kit.fontawesome.com/820b3635bf.js" crossorigin="anonymous"></script>
    <title>Zaloguj się</title>
    <style>

        .messages{
            margin-right: 60px;
            margin-left: 60px;
            font-size: 0.6vw;
            text-align: center;
        }

        @media (max-width: 768px) {
            .messages{
                margin-right: 60px;
                margin-left: 60px;
                font-size: 2.8vw;
                text-align: center;
            }
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
                <form action="loginpage" method="POST">
                    <div class="messages" style="margin-top: 15px; color:red; font-weight: bold">
                        <?php if(isset($messages)){
                            foreach($messages as $message){
                                echo $message;
                            }
                        }
                        ?>
                    </div>
                    <div class="input-box">
                        <i class="fa-solid fa-envelope"></i>
                        <input type="email" name="email" required placeholder="Email">
                    </div>
                    <div class="input-box">
                        <i class="fa-solid fa-key"></i>
                        <input type="password" name="password" required placeholder="Hasło">
                    </div>
                    <button type="submit" class="login-btn">Zaloguj się</button>
                </form>
            </div>
        </div>
    </main>
</body>
</html>