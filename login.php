<?php

session_start();
require("MySQLProvider.php");
require("TexturePacksPE.php");
require ("User.php");
require("TexturePack.php");
require("Rank.php");

$loader = new TexturePacksPE();
?>
<html>
    <head>
        <link rel="stylesheet" type="text/css" href="stylesheet.css">
    </head>
    <body>
        <?php
        if (isset($_SESSION["username"])) {
            echo "<p class='error'> Du bist bereits angemeldet! </p>";
        } else {
            if (isset($_POST["username"]) and isset($_POST["password"])) {
                $username = $_POST["username"];
                $password = hash("sha512", $_POST["password"]);
                if (!$loader->mysql->accountExists($username)) {
                    echo "<p class='error'> Es gibt keinen gültigen Account mit dieser Kombination! </p>";
                } else {
                    $data = $loader->mysql->getAccountData($username);
                    if ($data === null) {
                        echo "<p class='error'> Es gibt keinen gültigen Account mit dieser Kombination! </p>";
                    } else {
                        if ($data->getPassword() !== $password) {
                            echo "<p class='error'> Es gibt keinen gültigen Account mit dieser Kombination! </p>";
                        } else {
                            $_SESSION["status_data"] = 1;
                            $_SESSION["username"] = $username;
                            header("location:account.php");
                        }
                    }
                }
            }
        }
        ?>
        <form method="post" action="login.php">
            <input placeholder="Nutzername" type="text" id="username" name="username" required="required" maxlength="64" minlength="4">
            <br><input placeholder="Passwort" type="password" id="password" name="password" required="required" maxlength="64" minlength="8">
            <button type="submit" id="send"> Senden </button>
        </form>
    </body>
</html>
