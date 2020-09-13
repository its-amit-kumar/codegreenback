<?php


	$id = $_POST["quesid"];
	$user = $_POST["user"];


	if($_COOKIE["code".$user.strval($id)] == "NOCODE" or !isset($_COOKIE["code".$user.strval($id)])){
		echo "//##Type your code here##//";
	}
	else{
		echo $_COOKIE["code".$user.strval($id)];
	}


?>