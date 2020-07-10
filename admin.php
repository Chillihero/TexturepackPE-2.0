<?php

session_start();

require("TexturePacksPE.php");
require("MySQLProvider.php");
require("User.php");
require("TexturePack.php");
require("Rank.php");
require("PermissionIds.php");

$loader = new TexturePacksPE();
?>

<html>
    <head>
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
        <title> AdminPanel </title>
    </head>
    <body>
        <?php
            if (!isset($_SESSION["username"]) or (!$loader->mysql->getAccountData($_SESSION["username"])->getRank()->canEnterAdminPanel() and !$loader->mysql->isAdmin($_SESSION["username"]))) {
                echo "<p class='error'> You do not have the permission to enter TexturePackPE's AdminPanel. If you think this is a bug, please contact the staff on our Discord. </p>";
                return;
            }
            $username = $_SESSION["username"];

        $rank = $loader->mysql->getAccountData($username)->getRank();
        if (isset($_POST["deleteAccount"]) and isset($_POST["group_delete_select"])) {
            
        }
        if (isset($_GET["deleteAccount"])) {
            if ($_GET["deleteAccount"] === $username) {
                echo "<p class='error'> You cant delete your own account via AdminPanel! </p>";
            } else {
                if (!$loader->mysql->accountExists($_GET["deleteAccount"])) {
                    echo "<p class='error'> The given username is invalid! </p>";

                } else {
                    $loader->mysql->deleteAccount($_GET["deleteAccount"]);
                    echo "<p class='success'> You successfully deleted the account " . $_GET["deleteAccount"];
                }
            }
        }

        // DELETE GROUPS
        if (isset($_POST["submit_group_delete"]) and isset($_POST["group_delete_select"])) {
            if ($_POST["group_delete_select"] === "Administrator" or $_POST["group_delete_select"] === "User") {
                echo "<p class='error'> The given Group is not deletable! </p>";
            } else {
                if ($loader->mysql->getRank($_POST["group_delete_select"])->getName() === "User") {
                    echo "<p class='error'> The given Group is invalid or there was an error in database source! </p>";
                } else {
                    $loader->mysql->deleteRank($_POST["group_delete_select"]);
                    echo "<p class='success'> You successfully deleted group " . $_POST["group_delete_select"] . "</p>";
                }
            }
        }

        // CREATE GROUPS
        if (isset($_POST["create_submit_button"]) and isset($_POST["create_rankname"]) and isset($_POST["create_color"]) and isset($_POST["create_permissions"])) {
            if ($_POST["create_rankname"] === "User") {
                echo "<p class='error'> The given Group does already exist!</p>";
            } else {
                if ($loader->mysql->getRank($_POST["create_rankname"])->getName() !== "User") {
                    echo "<p class='error'> The given Group does already exist!</p>";
                } else {
                    $create_rank = new Rank($_POST["create_rankname"], explode(',', $_POST["create_permissions"]), $_POST["create_color"]);
                    $loader->mysql->createRank($create_rank);
                    echo "<p class='success'> You successfully created group " . $rank->getName() . "</p>";
                }
            }
        }

        // CHANGE USERGROUPS
        if (isset($_POST["change_group_username"]) and isset($_POST["change_group_groupname"]) and isset($_POST["change_group_submit"])) {
            if (!$loader->mysql->accountExists($_POST["change_group_username"])) {
                echo "<p class='error'> There is no User with the given username!</p>";
            } else if ($loader->mysql->isAdmin($_POST["change_group_username"]) and $_SESSION["username"] !== $_POST["change_group_username"]) {
                echo "<p class='error'> You cannot change the group of a Network Administrator! Please go to PHPMyAdmin Interface and change it manually if you are allowed to do this</p>";
            } else {
                $user = $loader->mysql->getAccountData($_POST["change_group_username"]);
                $user = new User($user->getName(), $user->getPassword(), $user->getMailAddress(), $user->getCreateTime(), $loader->mysql->getRank($_POST["change_group_groupname"]));
                $loader->mysql->setAccountData($user);
                echo "<p class='success'> You successfully changed the group of " . $user->getName() . " to " . $user->getRank()->getName() . "</p>";
            }
        }

        if (isset($_POST["submit_group_edit_choose"]) and isset($_POST["group_edit_select"])) header("location:admin.php?editGroup=" . $_POST["group_edit_select"]);

        ?>
        <h1> AdminPanel </h1>
        <?php
            if ($rank->hasPermission(PermissionIds::DELETE_ACCOUNTS) or $loader->mysql->isAdmin($username)) {
            ?>
            <h2> Usermanagment </h2>
            <?php

                $site = 0;
                if (isset($_GET["userSite"])) $site = (int) $_GET["userSite"] - 1;
                ?>
                <table style="margin-left: 31.5vw">
                    <tr><th class="userCard"> Username </th> <th class="userCard"> Group </th> <th class="userCard"> Email </th> <th class="userCard"> Delete Account </th> </tr>
                    <?php
                    foreach ($loader->getUsersSite($site) as $user) {
                        ?>
                        <tr><td  class="userCard"> <?php echo $user->getName(); ?> </td> <td class="userCard"><?php echo "<p class='rankformat' style='color: " . $user->getRank()->getColor() . "; border: 2px solid' " . $user->getRank()->getColor() . ">" . $user->getRank()->getName() . "</p>"; ?> </td> <td class="userCard"> <?php echo $user->getMailAddress(); ?> </td> <td class="userCard"> <a href="admin.php?deleteAccount=<?php echo $user->getName(); ?>"> Click here </a></td> </tr>
                        <?php
                    }
                    ?>
                </table>
                <p class="arrows"> <a href="admin.php?userSite=<?php echo ($site); ?>"> <img src="img/internal/arrow_left.png" width="25" height="25"> </a> <?php echo ($site + 1); ?> <a href="admin.php?userSite=<?php echo ($site + 2); ?>"> <img width="25" height="25" src="img/internal/arrow_right.png"> </a>  </p>
            <?php
            }
            ?>
    <br>
        <?php
            if ($rank->hasPermission(PermissionIds::EDIT_TEXTUREPACK_NAME) or $rank->hasPermission(PermissionIds::EDIT_TEXTUREPACKS) or $rank->hasPermission(PermissionIds::DELETE_TEXTUREPACKS) or $loader->mysql->isAdmin($username)) {
            ?>
            <h2> Texturepacks </h2>
            <div class="row">
                <?php
                $site = (isset($_GET["textureSite"]) ? $_GET["textureSite"] - 1 : 0);
                foreach ($loader->getUsersSite($site, $loader->mysql->getTexturePacks()) as $pack) {
                    ?>
                    <div class="col-lg-4 col-sm-6 mb-4 grow 1">
                        <div class="card h-100">
                            <a href="<?php echo $pack->getImage(); ?>" target="_blank"><img width="500.2" height="281.9" class="card-img-top" src="<?php echo $pack->getImage(); ?>" alt="<?php echo $pack->getImage(); ?>"></a>
                            <div class="card-body">
                                <h4 class="card-title">
                                    <li><a href="<?php echo $pack->getLink(); ?>"
                                           target="_blank"><?php echo $pack->getName(); ?></a></li>
                                </h4>
                                <p class="card-text"> Uploader: <?php echo $pack->getAuthor(); ?> <br></p>
                            </div>
                        </div>
                    </div>
                    <?php
                }
            ?>
            </div>
            <p class="arrows"> <a href="admin.php?textureSite=<?php echo ($site); ?>"> <img src="img/internal/arrow_left.png" width="25" height="25"> </a> <?php echo ($site + 1); ?> <a href="admin.php?textureSite=<?php echo ($site + 2); ?>"> <img width="25" height="25" src="img/internal/arrow_right.png"> </a>  </p>

                <?php
            }
            ?>
        <br><br>
        <?php
        $rank = $loader->mysql->getAccountData($username)->getRank();
        if ($rank->hasPermission(PermissionIds::GROUP_CHANGE) or $loader->mysql->isAdmin($username)) {
            ?>
            <details>
                <summary> Change user's group </summary>
                <div class="cart" style="height: 300px">
                    <form method="post" action="admin.php">
                        <h4> Enter an username </h4>
                        <input name="change_group_username" placeholder="Enter an username">
                        <br><br>
                        <h4> Pick the target Group</h4>
                        <select class="border_black" name="change_group_groupname">
                            <?php
                            foreach ($loader->mysql->getRanks() as $rank) {
                            ?>
                            <option value="<?php echo $rank->getName(); ?>" style="text-align: center;"> <?php echo $rank->getName(); ?>
                                <?php
                                }
                                ?>
                        </select>
                        <br> <br>
                        <button class="submitButton" name="change_group_submit" type="submit"> Push Changes</button>
                    </form>
                </div>
            </details>
            <?php
        }
        ?>
        <br><br>
        <?php
        $rank = $loader->mysql->getAccountData($username)->getRank();
        if ($rank->hasPermission(PermissionIds::GROUP_CREATE) or $loader->mysql->isAdmin($username)) {
        ?>
        <details>
            <summary> Create a new Group </summary>
            <div class="cart" style="height: 550px">
                <h3> Group creator </h3>
                <hr>
                <p>Choose a name</p>
                <form action="admin.php" method="post">
                    <input name="create_rankname" placeholder="Select a name">
                    <hr>
                    <p> Choose a color </p>
                    <input type="color" name="create_color" height="30" width="200" class="input">
                    <hr>
                    <p> Select permissions (Split them with ',') </p>
                    <input name="create_permissions" placeholder="Choose permissions" value="default.texturepacks.delete,default.texturepacks.manage" class="field_permissions">
                    <br><br>
                    <p><button class="submitButton" name="create_submit_button" type="submit"> Create </button><p>
                </form>
            </div>
        </details>
        <br><br>
        <?php
        }
        $rank = $loader->mysql->getAccountData($username)->getRank();
        if ($rank->hasPermission(PermissionIds::GROUP_EDIT) or $loader->mysql->isAdmin($username)) {
        if (isset($_GET["editGroup"])) {
            echo "<details open='open'>";
        } else {
            echo "<details>";
        }
        ?>
            <summary> Edit an existing Group </summary>
            <div id="editor">
                <div class="cart" style="height: 600px">
                    <h3> Edit a Group </h3>
                    <hr>
                    <p> Select your group to edit </p>
                    <form action="admin.php" method="post">
                        <select class="border_black" name="group_edit_select">
                            <?php
                            foreach ($loader->mysql->getRanks() as $rank) {
                            ?>
                            <option value="<?php echo $rank->getName(); ?>"> <?php echo $rank->getName(); ?>
                                <?php
                                }
                                ?>
                        </select>
                        <br><br>
                        <p> <button type="submit" class="submitButton" name="submit_group_edit_choose"> Edit </button> </p>
                        <?php
                            if (isset($_GET["editGroup"])) {
                        ?>
                            <hr>
                            <p> Choose a color </p>
                            <input type="color" name="edit_color" height="30" width="200" class="input">
                            <hr>
                            <p> Select permissions (Split them with ',') </p>
                            <input name="create_permissions" placeholder="Choose permissions" value="defaults.texturepacks.delete,defaults.texturepacks.manage" class="field_permissions">
                            <br>
                            <button type="submit" class="submitButton" name="submit_group_edit_save"> Save Changes </button>
                        <?php
                            }
                        ?>
                    </form>
                </div>
            </div>
        </details>
        <?php
        }
        $rank = $loader->mysql->getAccountData($username)->getRank();
        if ($rank->hasPermission(PermissionIds::GROUP_DELETE) or $rank->hasPermission(PermissionIds::ADMIN) or $loader->mysql->isAdmin($username)) {
        ?>
        <br><br><details >
            <summary> Delete an existing Group </summary>
            <div class="cart" style="height: 300px; margin-bottom: 100px">
                <h3> Delete a Group </h3>
                <hr>
                <p> Select a group to delete </p>
                <br>
                <form method="post" action="admin.php">
                    <select class="border_black" name="group_delete_select">
                       <?php
                            foreach ($loader->mysql->getRanks() as $rank) {
                               ?>
                                <option value="<?php echo $rank->getName(); ?>" style="text-align: center;"> <?php echo $rank->getName(); ?>
                                <?php
                            }
                        ?>
                    </select>
                    <br>
                    <button class="submitButton" name="submit_group_delete" type="submit" style="margin-top: 20px"> Delete</button>
                </form>
            </div>
        </details>
        <?php
        }
        ?>
        <br>
        <br>
        <hr>
    </body>
</html>
