<?php
// require_once $_SERVER['DOCUMENT_ROOT'].'/core/init.php';
require_once dirname(__DIR__).'/core/init.php';

class Session {


    public static function put($name,$value)
    {                                                                             //formation of session[token] = "something"
        return $_SESSION[$name] = $value;
    }
 
   public static function exists($name)
   {                                                                               //checks if $name session variable exists
       if(isset($_SESSION[$name])){     
           return true;
       }
       return false;
   }

   public static function delete($name)
   {                                                                            //delete $name session variable
       if(self::exists($name)){
           unset($_SESSION[$name]);
       }
   }

   public static function get($name)
   {                                                                            //get $name session variable value
	if(isset($_SESSION[$name]))
	{
		return $_SESSION[$name];
	}
   }

   public static function flash($name,$string=''){                            //flashing message 
        if(self::exists($name)){
            $session = self::get($name);
            self::delete($name);
            return $session;

        }
        else if($string != ''){
            self::put($name,$string);
        }
   }

   public static function setOnline()
   {
       $db = DB::getInstance();
    
      

       if(Session::exists(Config::get('session/session_name')))
       {
            $user = Session::get(Config::get('session/session_name'));
            Session::setOfline();
           
            $id = session_id();
            $db->insert('onlineUsers',array("username"=>$user,"sessionID"=>$id));
       }
       
   }


   public static function setOfline()
   {
       $db = DB::getInstance();
       if(Session::exists(Config::get('session/session_name')))
       {
           $db->delete('onlineUsers',array('username','=',Session::get('user')));
       }
   }

   public static function Logout()
   {
       session_destroy();
   }

   /*.................... STATIC FUNCTION FOR PAYMENT SETUP..................... */

   public static function setOrder($r_order_id , $cc,$package ,$price , $order_id)
   {
        $_SESSION['razorpay_order_id'] = $r_order_id;
        $_SESSION['cc_to_transfer'] = $cc;
        $_SESSION['package_to_buy'] = $package;
        $_SESSION['amout_to_paid'] = $price;
        $_SESSION['order_id'] = $order_id;
   }

   public static function removeOrder()
   {
       if(isset($_SESSION['order_id']))
       {
            unset($_SESSION['razorpay_order_id']);
            unset($_SESSION['cc_to_transfer']);
            unset($_SESSION['package_to_buy']);
            unset($_SESSION['amout_to_paid']);
            unset($_SESSION['order_id']);
       }
   }


   /*  ...................................................................*/
}
