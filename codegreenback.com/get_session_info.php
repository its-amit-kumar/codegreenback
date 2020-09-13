<?php

use Firebase\JWT\ExpiredException;
use \Firebase\JWT\JWT;
require_once $_SERVER['DOCUMENT_ROOT'].'/mail_token/vendor/autoload.php';

$id = $_GET['id'];
$token = urldecode($_GET['token']);

session_id($id);
session_start();
try{
            $data = JWT::decode($token ,"codegreenback", array('HS256'));
            $data = (array) $data;
            if($data['user'] == $_SESSION['user']){
            	echo json_encode(array("success" => "1", "user" => $_SESSION['user']));
            }
            else{
            	echo json_encode(array("success" => "-1"));
            }
        }catch(Exception $e){
            echo json_encode(array("success" => "-1"));
        }


?>
