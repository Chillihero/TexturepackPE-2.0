<?php

session_start();

require("User.php");
require("TexturePacksPE.php");
require("MySQLProvider.php");
require 'libs/PHPMailer/src/Exception.php';
require 'libs/PHPMailer/src/PHPMailer.php';
require 'libs/PHPMailer/src/SMTP.php';
require 'Tasks/AsyncMailSender.php';
require("Rank.php");

?>
<html>
<head>
    <link rel="stylesheet" type="text/css" href="stylesheet.css">
    <title> Email Verification </title>
</head>
<body>
<?php
if (!isset($_SESSION["needsVerify"]) or $_SESSION["needsVerify"] !== true) return;
if (isset($_SESSION["failed_verify"]) and $_SESSION["failed_verify"] >= 3) {
    echo "<p class='error'> You entered the wrong key more than 3 times! Account registration failed!";
    return;
}
if (isset($_POST["code"])) {
    if ((int)$_POST["code"] === (int)$_SESSION["verify_key"]) {
        $loader = new TexturePacksPE();
        $loader->mysql->createAccount(new User($_SESSION["verify_username"], $_SESSION["verify_password"], $_SESSION["verify_email"], time(), $loader->mysql->getRank("User")));
        $_SESSION["status_data"] = 0;
        $_SESSION["username"] = $_SESSION["verify_username"];
        unset($_SESSION["verify_username"]);
        unset($_SESSION["verify_password"]);
        unset($_SESSION["sent_verify_mail"]);
        $_SESSION["needsVerify"] = false;
        unset($_SESSION["failed_verify"]);
        header("location:account.php");
    } else {
        if (isset($_SESSION["failed_verify"])) {
            if ($_SESSION["failed_verify"] >= 2) {
                $msg = "This was your last try! Account registration failed!";
            } else {
                $_SESSION["failed_verify"] = $_SESSION["failed_verify"] + 1;
                $msg = "You have <bold>" . (3 - $_SESSION["failed_verify"]) . "</bold> tries left!";
            }
        } else {
            $_SESSION["failed_verify"] = 0;
            $msg = "You have <bold>" . (3 - $_SESSION["failed_verify"]) . "</bold> tries left!";
        }
        echo "<p class='error'> You entered a wrong key! $msg";
    }
}
?>
    <form method="post" action="verifymailcode.php">
        <input type="number" maxlength="6" minlength="5" name="code">
        <button type="submit"> Verify </button>
    </form>
</body>
</html>