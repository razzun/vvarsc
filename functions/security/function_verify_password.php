<?php
	include_once('function_recalculate_password_hash.php');
    function verify_password(
        $password_hash,
        $userPassword
    ) {
        if (password_verify($userPassword, $password_hash) == true) {
            recalculate_password_hash($password_hash);
            return true; // Login successful
        }
        else {
            return false;
        }
    }
?>