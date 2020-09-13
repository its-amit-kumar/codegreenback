<?php

try{
    $conn = new PDO("mysql:host=localhost;dbname=email",'root','Ayush170@');
    $conn->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
}
catch(PDOException $e){
    echo "connection failed". $e->getMessage();
}



?>