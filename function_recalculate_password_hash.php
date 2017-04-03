<?php
    function recalculate_password_hash(
        $password_hash
    ) {
        if (password_needs_rehash($hash, PASSWORD_DEFAULT, ['cost' => 12]))
		{
			//do something here
		}
    }
?>