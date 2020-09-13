<?php

class Hash {
    public static function make($string){

        return password_hash($string,PASSWORD_BCRYPT);

    }

    public static function unique(){
        return self::make(uniqid());
    }


    public static function bit_32_unique(){                                        //for unique notification id which will refrence to the data of particular user challenge
        $random = mt_rand(25,32);
        return substr(md5(time()), 0, $random);
    }


    public static function imgname()
    {
        $str = 'abcd1e2f4g5h78i8j08k34l56mnopq45r3s8t9u43vwxy23zAB4C3D5E6F7G2H2I1J7K8L9M0N7O6P4Q3RTSUVWXYZ';

        $part1 = substr(str_shuffle($str), 0 , 25);
        $part2 = substr(md5(time()), 0 , 25);
        return $part1.'_'.$part2.'.png';

    }


    /* Static function to generate order ID */
    
    public static function generateOrderId()
    {
        $random = mt_rand(10,15);
        
        return "ORD_".substr(md5(time()), 0, $random);;
    }

    public static function generate_challenge3_token()
    {
        $str = 'abcd1e2f4g5h78i8j08k34l56mnopq45r3s8t9u43vwxy23zAB4C3D5E6F7G2H2I1J7K8L9M0N7O6P4Q3RTSUVWXYZ';
        $part1 = substr(md5(time()), 0 , 12);
        return $part1;
    }

}
