<?php
session_start();
/*
require("./TexturePacksPE.php");
require("./User.php");
require("./MySQLProvider.php");
require("./TexturePack.php");
require("./Rank.php");

$loader = new TexturePacksPE();

$statusData = [
    0 => "<p class='success'> Du hast erfolgreich einen Account erstellt! </p>",
    1 => "<p class='success'> Du hast dich erfolgreich eingeloggt! </p>"
];
$critialData = [
    0 => false,
    1 => false
];*/
?>
<html>
    <head>
        <title> Account | TexturePackPE </title>
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
        <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"
                integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous">
        </script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"
                integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous">
        </script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"
                integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous">
        </script>
        <meta name="google-site-verification" content="JiifFMlVYmcBLi7EhHlI-6QRfP70kFNcNlkGxklbnB0" />

        <script async src="https://www.googletagmanager.com/gtag/js?id=UA-153642339-1"></script>

        <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro&display=swap" rel="stylesheet">

        <link rel="stylesheet" href="stylesheet.css" type="text/css">

        <link rel="icon" href="/img/internal/icon.png">

        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <meta property="og:locale" content="en">
        <meta property="og:site_name" content="TexturePackPE.de">
        <meta property="og:image" content="https://test.texturepackpe.de/img/internal/icon.png">
        <meta property="og:url" content="https://test.texturepackpe.de/account">
        <meta property="og:title" content="TexturePackPE | Account">
        <meta property="og:description" content="Manage your profile and uploaded texturepacks on TexturePackPE.">
    </head>
    <body>
            <div id="background_slider">
                <?php
                    if (!isset($_SESSION["username"])) {
                        echo "<p class='error'> You are not logged in to your account! <a href='/login.php'> Login now</a>";
                        return;
                    }
                    if (isset($_SESSION["status_data"])) {
                        $data = $_SESSION["status_data"];
                        if (isset($statusData[$data])) {
                            echo $statusData[$data];
                            unset($_SESSION["status_data"]);
                            if (isset($critialData[$data]) and $critialData[$data] === true) return;
                        }
                    }
                ?>
                <div class="header">
                    <img src="/img/internal/default_icon.jpg">
                    <h1> My account </h1>
                </div>
                <form method="post" enctype="multipart/form-data">
                    <div class="accountCard">
                        <h2>test</h2><br>
                        <img src="/img/internal/default_icon.jpg">
                        <input type="file" value=""  name="profilepic">
                        <br>
                        <input type="url" class="accountCard urlSelect" placeholder="Enter a social media url...">
                        <button> Update </button>
                    </div>
                </form>
            </div>
    </body>
</html>