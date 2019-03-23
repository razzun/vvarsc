<?
    $servername = "localhost";
    $username = "projectx_vvarsc";
    $password = "SRuEMBd-QUDif,*z9r";
    $dbname = "projectx_vvarsc2";

	$connection = new mysqli ($servername, $username, $password, $dbname);
	
	if ($connection->connect_error) {
	    die("Connection failed: " . $connection->connect_error);
	}
?>
