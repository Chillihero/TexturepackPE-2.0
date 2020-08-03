<?php

session_start();
require("MySQLProvider.php");
require("TexturePacksPE.php");
require("User.php");
require("TexturePack.php");
require("Rank.php");

$loader = new TexturePacksPE();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>QV Rechner</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"
          integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T"
          crossorigin="anonymous">
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
<div class="d-flex justify-content-center align-items-center login-container">
    <form method="post" class="login-form text-center" action="login.php">
        <h1 class="mb-5 font-weight-light text-uppercase noselect">Login</h1>

    <input placeholder="Nutzername" type="text" id="username" name="username" required="required" maxlength="64"
           minlength="4" class="form-control rounded-pill form-control-lg"
           placeholder="Email">


    <input placeholder="Passwort" type="password" id="password" name="password" required="required" maxlength="64"
               minlength="8" class="rounded-pill form-control-lg" placeholder="Password">

    <div class="forgot-link form-group d-flex justify-content-between align-items-center">
        <div class="form-check noselect">
            <input type="checkbox" class="form-check-input" id="remember">
            <label class="form-check-label" for="remember">Remember Password</label>
        </div>
        <a href="#">Forgot Password?</a>
    </div>

    <button type="submit" class="btn mt-5 rounded-pill btn-lg btn-custom btn-block text-uppercase">Log in</button>
    <p class="mt-3 font-weight-normal noselect">Don't have an account? <a href="#"><strong>Register Now</strong></a>
    </p>
</form>
</div>
</body>
</html>
