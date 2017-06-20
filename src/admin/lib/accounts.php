<?php

class AccountManager {
    private $data_file;

    public function __construct($accounts_file) {
        $this->accounts_file = $accounts_file;
    }

    public function load($user, $attrs) {
        $arr = array_fill(0, 1 + count($attrs), NULL);
        $arr[0] = false;
        $null_val = 0;
        $fd = fopen($this->data_file, "r");
        while (($line = fgets($fd)) !== false) {
            $ind = 0;
            if (($next_ind = strpos($line, ";", $ind)) !== false && substr($line, $ind, $next_ind - $ind) == $user) {
                $ind = $next_ind + 1;
                # set all flags to 1
                for ($i = 0; $i < count($attrs); $i++) { $null_val |= 1 << $i; }
                while (($next_ind = strpos($line, ':', $ind)) !== false) {
                    $attr = substr($line, $ind, $next_ind - $ind);
                    $ind = $next_ind + 1;
                    $next_ind = strpos($line, ';', $ind);
                    $val = $this->decode(substr($line, $ind, $next_ind - $ind));
                    $ind = $next_ind + 1;
                    for ($i = 0; $i < count($attrs); $i++) {
                        if ($attrs[$i] == $attr) {
                            $arr[1 + $i] = $val;
                            # remove flag "i"
                            $null_val &= ~(1 << $i);
                        }
                    }
                }
            }
        }
        fclose($fd);
        # check if all flags are 0
        if ($null_val == 0) { $arr[0] = true; }
        return $arr;
    }

    public function gen_salt() {
        $salt = "";
        $chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
        for ($i = 0; $i < 32; $i++) { $salt .= $chars[rand(0, strlen($chars) - 1)]; }
        return $salt;
    }

    public function hash_pass($pass, $salt) {
        return hash("sha256", $pass . $salt);
    }

    public function is_pass($user, $pass) {
        list($ok, $hashed_pass, $salt) = account_load($user, array( "hashed_pass", "salt" ));
        return $ok && $this->hash_pass($pass, $salt) == $hashed_pass;
    }

    public function is_correct_pass($pass) {
        $pass_len = strlen($pass);
        if ($pass_len < 8) { return false; }

        $chars_letters = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz";

        # each bit of "state" is a flag which says if a character of it's category
        # has been found (1 means not found, 0 means found). Each flag, from
        # weakest bit to strongest bit, is:
        #  - letters (lower and upper cases)
        #  - other
        # a "state" of 0 means that all categories have been found at least once
        $state = 0x3;
        for ($i = 0; $i < $pass_len; $i++) {
            if (strpos($chars_letters, $pass[$i]) !== false) {
                # remove flag 1
                $state &= ~0x1;
            } else {
                # remove flag 2
                $state &= ~0x2;
            }
            if ($state == 0) { break; }
        }
        # check if all flags are 0
        return $state == 0;
    }

    public function encode($data) {
        $d = $data;
        $d = preg_replace("/%/",   "%(0)", $d);
        $d = preg_replace("/\\r/", "%(1)", $d);
        $d = preg_replace("/\\n/", "%(2)", $d);
        $d = preg_replace("/:/",   "%(3)", $d);
        $d = preg_replace("/;/",   "%(4)", $d);
        return $d;
    }

    public function decode($data) {
        $d = $data;
        $d = preg_replace("/%\\(4\\)/", ";",  $d);
        $d = preg_replace("/%\\(3\\)/", ":",  $d);
        $d = preg_replace("/%\\(2\\)/", "\n", $d);
        $d = preg_replace("/%\\(1\\)/", "\r", $d);
        $d = preg_replace("/%\\(0\\)/", "%",  $d);
        return $d;
    }

    public function create($user, $pass) {
        list($ok) = $this->load($user, array( ));
        if ($ok || !$this->is_correct_pass($pass)) { return false; }
        $salt = $this->gen_salt();
        $fd = fopen($this->data_file, "a");
        fwrite($fd, $this->encode($user) . ";"
            . "hashed_pass" . ":" . $this->hash_pass($pass, $salt) . ";"
            . "salt" . ":" . $salt . ";"
            . "\n"
        );
        fclose($fd);
        return true;
    }
}
