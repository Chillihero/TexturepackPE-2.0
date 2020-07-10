<?php
session_start();

require("User.php");
require("TexturePacksPE.php");
require("MySQLProvider.php");
require("Tasks/AsyncMailSender.php");
require 'libs/PHPMailer/src/Exception.php';
require 'libs/PHPMailer/src/PHPMailer.php';
require 'libs/PHPMailer/src/SMTP.php';
require("Rank.php");

$loader = new TexturePacksPE();
?>

<html>
    <head>
        <link rel="stylesheet" type="text/css" href="stylesheet.css">
        <title> Registrieren </title>
    </head>
    <body>
        <?php
        if (isset($_SESSION["username"])) {
            echo "<p class='error'> Du bist bereits angemeldet! </p>";
        } else {
            if (isset($_POST["username"]) and isset($_POST["password"]) and isset($_POST["email"])) {
                $password = hash("sha512", $_POST["password"]);
                if ($loader->mysql->accountExists($_POST["username"]) or $loader->mysql->mailExists($_POST["email"])) {
                    echo "<p class='error'> Es gibt bereits einen gültigen Account mit dieser Kombination! </p>";
                } else {
                    $_SESSION["needsVerify"] = true;
                    $_SESSION["verify_username"] = $_POST["username"];
                    $_SESSION["verify_password"] = $password;
                    $_SESSION["verify_email"] = $_POST["email"];
                    $key = rand(1, 99999);
                    $_SESSION["verify_key"] = $key;
                    $mail = new AsyncMailSender("register@texturepackpe.de", $_POST["email"], "Hey " . $_POST["username"] . "!\r\n Your verification code is <bold>" . $key . "</bold>!\r\nBests, \r\n Your TexturePackPE Staff!", "TexturePackPE Verification");
                    $mail->run();
                    header("location:verifymailcode.php");

                }
            }
        }
        ?>
        <form method="post" action="register.php">
            <input placeholder="Nutzername" type="text" id="username" name="username" required="required" maxlength="64" minlength="4">
            <br><input placeholder="Passwort" type="password" id="password" name="password" required="required" maxlength="64" minlength="8">
            <br><input placeholder="Email" type="email" id="email" name="email" required="required" maxlength="64" minlength="6">
            <button type="submit" id="send"> Senden </button>
        </form>
    </body>
</html>
