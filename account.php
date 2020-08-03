<?php

session_start();

require("TexturePacksPE.php");
require("User.php");
require("MySQLProvider.php");
require("TexturePack.php");
require("Rank.php");

$loader = new TexturePacksPE();

$statusData = [
    0 => "<p class='success'> Du hast erfolgreich einen Account erstellt! </p>",
    1 => "<p class='success'> Du hast dich erfolgreich eingeloggt! </p>"
];
$critialData = [
    0 => false,
    1 => false
];
?>
<html>
    <head>
        <title> Userinterface </title>
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
        <link rel="stylesheet" href="./stylesheet/stylesheet.css" type="text/css">
        <link rel="stylesheet" href="stylesheet.css" type="text/css">
    </head>
    <body>
    <?php

    if (!isset($_SESSION["username"])) {
        echo "<p class='error'> You are not logged in to your account! <a href='login.php'> Login now</a>";
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

    if (isset($_POST["Packname"]) and isset($_POST["Packauthor"]) and isset($_POST["Packlink"]) and isset($_FILES["file"]) and isset($_POST["tags"])) {

        $id = rand(0, 999999999999999999);
        $check = getimagesize($_FILES["file"]["tmp_name"]);
        var_dump($check);
        var_dump($check[0] . ":" . $check[1]);
        if ($_FILES["file"]["sizee"] > 2097152) {
            echo "<p class='error'> Your image size must be smaller than 2MB! </p>";
        } else if (($check[0] - $check[1]) < 0) {
            echo "<p class='error'> Please upload an image in landscape format!";
        } else if (!$check["mime"] or ($check["mime"] !== "image/png" and $check["mime"] !== "image/jpg")) {
            echo "<p class='error'> Please upload a file with .png or .jpg extension!</p>";
        } else {
            $ext = ($check["mime"] === "image/png" ? ".png" : ".jpg");
            copy($_FILES["file"]["tmp_name"], "img/" . $id . $ext);
            $tags = [];
            $tp = new TexturePack($_POST["Packname"], $_SESSION["username"], $_POST["Packauthor"], $_POST["Packlink"], "img/" . $id . $ext, $tags, $id);
            $loader->mysql->createTexturePack($tp);
            echo "<p class='success'> You successfully created your texturepack " . $_POST["Packname"] . "</p>";
        }
    }
    ?>
    <h1> Your Account </h1><br>

    <div class="accountCard">
        <div class="header">
            <h2>Profile</h2>
            <hr>
        </div>
        <div class="leftSide">

            <form method="post" action="account.php">
                <div class="rightSide">
                    <p><img src="img/internal/default_icon.jpg"> <span class="right"> Welcome to your profile!<br>Change your profile - image<br> by clicking on the button below<br> your latest image! <br>If you want to change / set a url<br> to e.g your YouTube channel,<br> change the value of the input below.<br>Have fun!</span> </p>
                    <input id="input_field" type="url" name="profile_url_input" placeholder="Enter a url...">
                </div>
                <a href="#"> Upload image </a>
            </form>
        </div>

    </div>
    <h2>Your uploaded TexturePacks: </h2><br>

    <ul>
        <div class="row">
            <?php
            $username = $_SESSION["username"];
            foreach ($loader->getTexturePacksByPlayer($username) as $pack) {
                ?>
                <div class="col-lg-4 col-sm-6 mb-4 grow 1">
                    <div class="card h-100">
                        <a href="<?php echo $pack->getImage(); ?>" target="_blank"><img width="500.2" height="281.9" class="card-img-top" src="<?php echo $pack->getImage(); ?>" alt="<?php echo $pack->getImage(); ?>"></a>
                        <div class="card-body">
                            <h4 class="card-title">
                                <li><a href="<?php echo $pack->getLink(); ?>"
                                       target="_blank"><?php echo $pack->getName(); ?></a></li>
                            </h4>
                            <p class="card-text"> Creator: <?php echo $pack->getCreator()();  ?> <br>
                            </p>
                        </div>
                    </div>
                </div>
                <?php
            }
            ?>
        </div>
    </ul>
    </body>
</html>
