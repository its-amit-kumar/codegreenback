<?php
require_once  dirname(__DIR__).'/ClassesTemp/DB.php';

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
       return $_SESSION[$name];
   }

   public static function flash($name,$string=''){                            //flashing message 
        if(self::exists($name)){
            $session = self::get($name);
            self::delete($name);
            return $session;

        }
        else{
            self::put($name,$string);
        }
   }

    public static function setOnline()
   {
       $db = DB::getInstance();
    
      

       if(Session::exists('user'))
       {
            $user = Session::get('user');
            Session::setOfline();
           
            $id = session_id();
            $db->insert('onlineUsers',array("username"=>$user,"sessionID"=>$id));
       }
       
   }


   public static function setOfline()
   {
       $db = DB::getInstance();
       if(Session::exists('user'))
       {
           $db->delete('onlineUsers',array('username','=',Session::get('user')));
       }
   }


}
