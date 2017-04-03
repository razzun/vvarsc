<?php
    function hash_password(
        $userPassword
    ) {
        return password_hash($userPassword, PASSWORD_DEFAULT, ['cost' => 12]);
    }
?>