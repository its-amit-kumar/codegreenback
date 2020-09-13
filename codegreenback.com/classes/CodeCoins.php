<?php

require_once $_SERVER['DOCUMENT_ROOT'].'/core/init.php';

// class to perform codecoins operations

class CodeCoins 
{

    private $_db;

    public function __construct()
    {
        $this->_db = DB::getInstance();
    }


    public function getOffers()
    {
        $user = Session::get(Config::get('session/session_name'));
        $userType = $this->_db->getUserType($user);

        if($userType === "elite")
        {
            $data = $this->_db->get('buyCodeCoins',array('sno','=',1))->results();
            $currentSale = $data[0]->currentSale;
            $offers = json_decode($data[0]->$currentSale, true);
            $array = array(
                'userType'=>"elite",
                'starter'=>$offers['elite']['starter'],
                'basic' => $offers['elite']['basic'],
                'standard' => $offers['elite']['standard'],
                'plus' => $offers['elite']['plus'],
                'solid' => $offers['elite']['solid']
            );
            // print_r($array);
            return json_encode($array);
        }
        else if($userType === "non-elite")
        {
            $data = $this->_db->get('buyCodeCoins',array('sno','=',1))->results();
            $currentSale = $data[0]->currentSale;
            $offers = json_decode($data[0]->$currentSale, true);
            $array = array(
                'userType'=>"non-elite",
                'starter'=>$offers['non-elite']['starter'],
                'basic' => $offers['non-elite']['basic'],
                'standard' => $offers['non-elite']['standard'],
                'plus' => $offers['non-elite']['plus'],
                'solid' => $offers['non-elite']['solid'],
                'eliteMemberShip' => 59 
            );
            return json_encode($array);
        }
        else{
            echo "sorry";
        }
    
    }


    public function getPack($name)
    {
        $user = Session::get(Config::get('session/session_name'));
        $userType = $this->_db->getUserType($user);

        if($userType === "elite")
        {
            $data = $this->_db->get('buyCodeCoins',array('sno','=',1))->results();
            $currentSale = $data[0]->currentSale;
            $offers = json_decode($data[0]->$currentSale, true);
            $offer = $offers['elite'][$name];
            // print_r($offer);
            return $this->makeorder($userType , $name , $offer);

        }
        elseif ($userType === 'non-elite') {
            if($name === 'eliteMemberShip')
            {
                return $this->makeorder($userType , $name);
            }
            $data = $this->_db->get('buyCodeCoins',array('sno','=',1))->results();
            $currentSale = $data[0]->currentSale;
            $offers = json_decode($data[0]->$currentSale, true);
            $offer = $offers['non-elite'][$name];
            // print_r($offer);
            return $this->makeorder($userType , $name , $offer);
        }

    }

    private function makeorder($type , $pack , $data='')
    {
    
        if($type === "elite")
        {
            $order = array(
                'orders' => 1,
                'package' =>  $pack." Package( ".$data['offer1']." + ".$data['offer2']." ) CodeCoins",
                'order-1' => array(
                    'order-name' => $pack." Package( ".$data['offer1']." + ".$data['offer2']." ) CodeCoins",
                    
                    'price' => $data['totalPrice']
                ),
                'totalPrice' => $data['totalPrice'],
                'total-cc' => $data['offer1'] + $data['offer2']
            );
        }
        elseif ($type === 'non-elite') {

            if($pack === 'eliteMemberShip')
            {
                $order = array(
                    'orders' => 1,
                    'package' => "Elite Membership ",
                    'order-1' => array(
                        'order-name' => "Elite Membership",
                        'price' => 59
                    ),
                    'totalPrice' => 59,
                    'total-cc' => 0
                );
            }
            else
            {
                $order = array(
                    'orders' => 2,
                    'package' =>  $pack." Pack With Elite Membership ",
                    'order-1' => array(
                        'order-name' => $pack." Package( ".$data['offer1']." + ".$data['offer2']." ) CodeCoins",
                        'price' => $data['price_INR']
                    ),
                    'order-2' => array(
                        'order-name' => "Elite Membership",
                        'price' => $data['elitemembershipPrice']
                    ),
                    'totalPrice' => $data['totalPrice'],
                    'total-cc' => $data['offer1'] + $data['offer2']
                );
            }
        }

        return $order;
    }



    public static function setWinningCodeCoins($cc)
    {
        /**
         *  8% commision from each challenge
         */

        $winnCC = $cc*2*0.92;
        return floor($winnCC);
    }

    public static function setChallengeDrawCodeCoins($cc)
    {
         /**
         *  6% commision from each challenge if drawn
         */
        $winnCC = floor($cc*2*0.94);
        if($winnCC%2 == 0)
        {
            return $winnCC/2;
        }
        else{
            return ($winnCC-1)/2;
        }
    }

}



?>
