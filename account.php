<?php

session_start();
ini_set("max_file_uploads", 10);
ini_set("upload_max_filesize", 10000000);
ini_set("file_uploads", 1);
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
        <title> Benutzerinterface </title>
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
    if (isset($_POST["Packname"]) and isset($_POST["Packauthor"]) and isset($_POST["Packlink"]) and isset($_FILES["file"])) {

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
            $tp = new TexturePack($_POST["Packname"], $_SESSION["username"], $_POST["Packauthor"], $_POST["Packlink"], "img/" . $id . $ext, $id);
            $loader->mysql->createTexturePack($tp);
            echo "<p class='success'> You successfully created your texturepack " . $_POST["Packname"] . "</p>";
        }
    }

    ?>

    <h1> Your Account </h1><br>

    <h2> Profile </h2>
    <details>
        <summary> <img src="img/internal/arrow_left.png" width="100" height="100"> </summary>
        <details class="">
            <label for="avatar_upload" class="dropdown-item text-normal" style="cursor: pointer;" role="menuitem" tabindex="0">
                    Upload a photo…
                </label>
        </details>

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
                            <p class="card-text"> Uploader: <?php echo $pack->getAuthor(); ?> <br>
                                Creator: <?php echo $pack->getCreator(); ?> <br>
                                Download: <a
                                        href="<?php echo $pack->getLink(); ?>"
                                        target="_blank">Click here</a>
                            </p>
                        </div>
                    </div>
                </div>
                <?php
            }
            ?>
        </div>
        <!--<details>
            <summary>Create a Texturepack </summary>
            <form method="post" action="account.php" class="textureCreate" enctype="multipart/form-data" >
                <p><input placeholder="Packname" name="Packname" required="required"> </p>
                <p><input placeholder="Packcreator" name="Packauthor" required="required"> </p>
                <p><input placeholder="Link to download" name="Packlink" required="required"> </p>
                <p> <input type="file" name="file" value="Bild auswählen" id="file" required="required" accept="image/*"> </p>
                <button type="submit"> Create </button>
            </form>
        </details> !-->
    </body>
</html>
