<?php


class Redirect
{
    public static function to($location = null){
        if(is_numeric($location)){
            switch($location) {
                case 404:
                    header('HTTP/1.0 404 Not Found');
                    include 'includes/errors/404.php';
                break;
                }
        }

        else if($location){
            header('Location:'. $location .'.php');
        }
    }
}