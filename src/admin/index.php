<?php

const DATA_FOLDER   = "data";
const ACCOUNTS_FILE = DATA_FOLDER . "/accounts.txt";

include "lib/accounts.php";

function test_eq($s1, $s2) {
    if ($s1 != $s2) { echo "Error: s1 = " . $s1 . ", s2 = " . $s2 . "\n"; }
}

function test_enc_dec($am) {
    test_eq($this->encode("abc"), "abc");
    test_eq($this->encode("%\r\n:;"), "%(0)%(1)%(2)%(3)%(4)");
    test_eq($this->encode("test; de long\nmessage qui marche : tres bien !"), "test%(4) de long%(2)message qui marche %(3) tres bien !");

    test_eq($this->decode("abc"), "abc");
    test_eq($this->decode("%(0)%(1)%(2)%(3)%(4)"), "%\r\n:;");
    test_eq($this->decode("test%(4) de long%(2)message qui marche %(3) tres bien !"), "test; de long\nmessage qui marche : tres bien !");

    test_eq($this->decode($this->encode("message\nlong message:;:; fdfsf fe \r::: uu!;")), "message\nlong message:;:; fdfsf fe \r::: uu!;"); 
}

$first_launch = false;

if (!is_dir(DATA_FOLDER)) {
    $first_launch = true;
    mkdir(DATA_FOLDER);
    touch(ACCOUNTS_FILE);
}

$am = new AccountManager(ACCOUNTS_FILE);
if ($first_launch) { $am->create("root", "temp1234"); }
session_start();

if ($_GET["action"] == "disconnect") {
    unset($_SESSION["user"]);
    header("Location: /admin");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $loc = "/admin";
    if (isset($_POST["user"]) && isset($_POST["pass"])) {
        if (!$this->is_pass($_POST["user"], $_POST["pass"])) {
            $_SESSION["msg"] = "Utilisateur ou mot de passe incorrect";
        } else {
            $_SESSION["user"] = $_POST["user"];
        }
    }
    header("Location: " . $loc);
    exit();
}

if (!isset($_SESSION["user"])) {
    $user = NULL;
} else {
    $user = $_SESSION["user"];
}

?>
<!DOCTYPE html>
<html lang="fr">
<head>
<title>AUMP - Administration</title>
<meta charset="UTF-8" />
<link rel="stylesheet" href="../style.css" />
<link rel="stylesheet" href="index.css" />
<script src="index.js"></script>
</head>
<body onload="init();">
<?php

if ($user == NULL) {
    ?>
    <div id="div-connect" class="autocenter">
    <div class="title">Se Connecter</div>
    <div class="content">
    <form method="POST" action="">
    <table>
    <tbody>
    <tr><td>Utilisateur: </td> <td class="table-spacer-x"></td><td><input type="text" id="user" name="user" /></td></tr>
    <tr><td class="table-spacer-y" colspan="3"></td></tr>
    <tr><td>Mot de Passe: </td><td class="table-spacer-x"></td><td><input type="password" id="pass" name="pass" /></td></tr>
    </tbody>
    </table>
    <div class="div-button"><input type="submit" value="connexion" /></div>
    </form>
    </div>
    </div>
    <?php
} else {
    ?>
    Connecté
    <?php
}
?>
</body>
</html>
