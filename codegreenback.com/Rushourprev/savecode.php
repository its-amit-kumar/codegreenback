<?php


$code = $_POST["code"] or die("did not receive");
$qid = strval($_POST["qid"]);
$user = $_POST["user"];

setcookie("code".$user.$qid,$code,time()+31536000);


echo "success";
//print_r($_SESSION);


?>