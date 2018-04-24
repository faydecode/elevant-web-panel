<?php
	$host = "localhost";
	$user = "root";
	$pass = "";

	$mysqli = new mysqli($host, $user, $pass, 'db_shopping');

	/*
	 * This is the "official" OO way to do it,
	 * BUT $connect_error was broken until PHP 5.2.9 and 5.3.0.
	 */
	//if ($mysqli->connect_error) {
	//	die('Connect Error (' . $mysqli->connect_errno . ') '
	//			. $mysqli->connect_error);
	//}
	//else{
	//	echo("Success");
	//	die;
	//}

	/*
	 * Use this instead of $connect_error if you need to ensure
	 * compatibility with PHP versions prior to 5.2.9 and 5.3.0.
	 */
	 
	//if (mysqli_connect_error()) {
	//	die('Connect Error (' . mysqli_connect_errno() . ') '
	//			. mysqli_connect_error());
	//}

?>

	