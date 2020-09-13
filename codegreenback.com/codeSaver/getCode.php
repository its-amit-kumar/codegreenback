<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/init.php';

$mdb = new mongo("CodeSaver");
$username = $_SESSION['user'];
$cid = $_POST["cid"];
if(isset($_POST['qNo'])){
	$qNo = $_POST['qNo'];
}
else{
	$qNo = "";
}
$code = $mdb->get("challenge", array("codeid" => $cid.$username.$qNo));
if(count($code) == 0){

	$mdb->insert("challenge", array("codeid" => $cid.$username.$qNo, "code" => ""));
	echo json_encode(array("code" => "Some instructions before you code:\n\n 1. In case of java, keep the class name as main.\n 2. In case of erlang, keep the function name as main.\n 3. In case of Golang keep the module name as main.\n 4. In case of haskell, keep the variable name as main.\n\nHappy Coding!"));
}
else{
	if ($code[0]['code'] == ""){
		echo json_encode(array("code" => "Some instructions before you code:\n\n 1. In case of java, keep the class name as main.\n 2. In case of erlang, keep the function name as main.\n 3. In case of Golang keep the module name as main.\n 4. In case of haskell, keep the variable name as main.\n\nHappy Coding!"));
	}
	else{
		echo json_encode(array("code" => $code[0]['code']));

	}


}



/*
{
codeid : skldlsakds
code : ""
}
 */

 ?>

