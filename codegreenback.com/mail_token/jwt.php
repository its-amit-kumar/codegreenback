<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/init.php';

$jwt = Token::jwtGenerate('ayush');
//echo $jwt;
Token::jwtVerify($jwt);

?>