<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">  
    <script src="https://kit.fontawesome.com/820b3635bf.js" crossorigin="anonymous"></script>
    <title>Wystąpił błąd!</title>
    <style>
        *  {
            font-family: Poppins;
        }

        body{
            display: flex;
            justify-content: center;
            background-image: url(public/styles/back_login.png);
            background-repeat: no-repeat;
            background-position: center;
            background-attachment: fixed;
            background-size: cover;
        }

        .error-info {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-items: center;
            font-size: 2vw;
        }
    </style>
</head>
<body>
    <div class="error-info">
        <h1>Coś poszło nie tak...</h1>
        <i class="fa-solid fa-hammer"></i>
    </div>
</body>
</html>