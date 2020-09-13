<?php

require_once $_SERVER['DOCUMENT_ROOT'].'/core/init.php';

if(isset($_POST['qNo'])){
        $qNo = $_POST['qNo'];
}
else{
        $qNo = "";
}

$mng = new mongo("CodeSaver");

$challengeId = $_POST['cid'];

$code = $_POST['code'];

$username = $_SESSION['user'];

$mng->update('challenge' ,array("codeid" => $challengeId.$username.$qNo),array("code"=>$code));

 ?>
