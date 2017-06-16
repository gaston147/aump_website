<?php

const ROOT_FOLDER   = ".";
const DATA_FOLDER   = ROOT_FOLDER . "/data";
const ACCOUNTS_FILE = DATA_FOLDER . "/accounts.txt";

function init() {
    if (!is_dir(ROOT_FOLDER)) {
        if (!mkdir(ROOT_FOLDER)) {
            echo "Error: Can't create directory `" . ROOT_FOLDER . "'\n";
        }
    }
    if (!is_dir(DATA_FOLDER)) {
        if (!mkdir(DATA_FOLDER)) {
            echo "Error: Can't create directory `" . DATA_FOLDER . "'\n";
        }
    }
    if (!is_file(ACCOUNTS_FILE)) {
        touch(ACCOUNTS_FILE);
        account_create("gaston", "temp2017");
    }
}

function account_load($user, $attrs) {
    $arr = array_fill(0, 1 + count($attrs), NULL);
    $arr[0] = false;
    $null_val = 0;
    $fd = fopen(ACCOUNTS_FILE, "r");
    while (($line = fgets($fd)) !== false) {
        $ind = 0;
        if (($next_ind = strpos($line, ";", $ind)) !== false && substr($line, $ind, $next_ind - $ind) == $user) {
            $ind = $next_ind + 1;
            for ($i = 0; $i < count($attrs); $i++) { $null_val |= 1 << $i; }
            while (($next_ind = strpos($line, ':', $ind)) !== false) {
                $attr = substr($line, $ind, $next_ind - $ind);
                $ind = $next_ind + 1;
                $next_ind = strpos($line, ';', $ind);
                $val = account_decode(substr($line, $ind, $next_ind - $ind));
                $ind = $next_ind + 1;
                for ($i = 0; $i < count($attrs); $i++) {
                    if ($attrs[$i] == $attr) {
                        $arr[1 + $i] = $val;
                        $null_val &= ~(1 << $i);
                    }
                }
            }
        }
    }
    fclose($fd);
    if ($null_val == 0) { $arr[0] = true; }
    return $arr;
}

function account_gen_salt() {
    $salt = "";
    $chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
    for ($i = 0; $i < 32; $i++) { $salt .= $chars[rand(0, strlen($chars) - 1)]; }
    return $salt;
}

function account_hash_pass($pass, $salt) {
    return hash("sha256", $pass . $salt);
}

function account_is_pass($user, $pass) {
    list($ok, $hashed_pass, $salt) = account_load($user, array( "hashed_pass", "salt" ));
    $hashed_pass_2 = account_hash_pass($pass, $salt);
    return $ok && $hashed_pass_2 == $hashed_pass;
}

function account_is_correct_pass($pass) {
    $pass_len = strlen($pass);
    if ($pass_len < 8) { return false; }

    $chars_letters = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz";

    # each bits of "state" is a flag which says if a character of it's category
    # has been found (1 means not found, 0 means found). Each flags, from
    # weakest bit to strongest bit, are:
    #  - letters (lower and upper cases)
    #  - other
    # a "state" of 0 means that all categories have been found at least once
    $state = 0x3;
    for ($i = 0; $i < $pass_len; $i++) {
        if (strpos($chars_letters, $pass[$i]) !== false) {
            $state &= ~0x1;
        } else {
            $state &= ~0x2;
        }
        if ($state == 0) { break; }
    }
    return $state == 0;
}

function account_encode($data) {
    $d = $data;
    $d = preg_replace("/%/",   "%(0)", $d);
    $d = preg_replace("/\\r/", "%(1)", $d);
    $d = preg_replace("/\\n/", "%(2)", $d);
    $d = preg_replace("/:/",   "%(3)", $d);
    $d = preg_replace("/;/",   "%(4)", $d);
    return $d;
}

function account_decode($data) {
    $d = $data;
    $d = preg_replace("/%\\(4\\)/", ";",  $d);
    $d = preg_replace("/%\\(3\\)/", ":",  $d);
    $d = preg_replace("/%\\(2\\)/", "\n", $d);
    $d = preg_replace("/%\\(1\\)/", "\r", $d);
    $d = preg_replace("/%\\(0\\)/", "%",  $d);
    return $d;
}

function account_create($user, $pass) {
    list($ok) = account_load($user);
    if ($ok || !account_is_correct_pass($pass)) { return false; }
    $salt = account_gen_salt();
    $fd = fopen(ACCOUNTS_FILE, "a");
    fwrite($fd, account_encode_data($user) . ";"
        . "hashed_pass" . ":" . account_hash_pass($pass, $salt) . ";"
        . "salt" . ":" . $salt . ";"
        . "\n"
    );
    fclose($fd);
    return true;
}

function test_eq($s1, $s2) {
    if ($s1 != $s2) { echo "Error: s1 = " . $s1 . ", s2 = " . $s2 . "\n"; }
}

function test_enc_dec() {
    test_eq(account_encode("abc"), "abc");
    test_eq(account_encode("%\r\n:;"), "%(0)%(1)%(2)%(3)%(4)");
    test_eq(account_encode("test; de long\nmessage qui marche : tres bien !"), "test%(4) de long%(2)message qui marche %(3) tres bien !");

    test_eq(account_decode("abc"), "abc");
    test_eq(account_decode("%(0)%(1)%(2)%(3)%(4)"), "%\r\n:;");
    test_eq(account_decode("test%(4) de long%(2)message qui marche %(3) tres bien !"), "test; de long\nmessage qui marche : tres bien !");

    test_eq(account_decode(account_encode("message\nlong message:;:; fdfsf fe \r::: uu!;")), "message\nlong message:;:; fdfsf fe \r::: uu!;"); 
}

session_start();

if ($_GET["action"] == "disconnect") {
    unset($_SESSION["user"]);
    header("Location: /admin");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $loc = "/admin";
    if (isset($_POST["user"]) && isset($_POST["pass"])) {
        if (!account_is_pass($_POST["user"], $_POST["pass"])) {
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

init();

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
