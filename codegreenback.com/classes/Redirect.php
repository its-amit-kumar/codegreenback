<?php

require_once $_SERVER['DOCUMENT_ROOT'].'/core/init.php';

class Redirect
{
    public static function to($location = null){
        if(is_numeric($location)){
            switch($location) {
                case 404:
                    header('HTTP/1.1 404 Not Found');
                    include dirname(__DIR__).'/404.html';
                break;
                }
        }else if($location == '')
	{
		header('Location:https://www.codegreenback.com');
	}
        else if($location){
            header('Location: https://www.codegreenback.com/'. $location .'/');
        }
    }
}
