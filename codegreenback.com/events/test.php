<?php

require_once $_SERVER['DOCUMENT_ROOT'].'/core/init.php';

$mdb = new mongo('Events');
$arr = array("user" => "ayush123", "startTime" => "", "endTime" => "","solvedQuestions" => array());
if($mdb->insert('5ee1de694fba6',$arr)){
	echo "string";
}
else{
	echo "f";
}

?>