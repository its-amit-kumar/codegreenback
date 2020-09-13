<?php
// require_once $_SERVER['DOCUMENT_ROOT'].'/core/init.php';
require_once dirname(__DIR__).'/core/init.php';

class User {
    private $_db,
            $_data,
            $_sessionName,
            $_cookieName,
            $_isLoggedIn,
            $_userType,
            $_userEmail,
            $_userLeague,
            $_userCC,
            $_statsData,
            $_cc,
	    $_img,
	    $_name;

    public function __construct($user = null)
    {
        $this->_db = DB::getInstance();
        $this->_sessionName = Config::get('session/session_name');
        $this->_cookieName = Config::get('remember/cookie_name');
        $this->_userType = Config::get('session/user_type');
        $this->_userEmail = Config::get('session/user_email');
        $this->_userLeague = Config::get('session/user_league');
        $this->_userCC = Config::get('session/user_cc');
        $this->_img = Config::get('session/user_img');                                      //image url
        $this->_name = Config::get('session/user_name');                                    //fullname

        if(!$user){
            if(Session::exists($this->_sessionName)){
                $user = Session::get($this->_sessionName);
                if($this->find($user)){
                    $this->_isLoggedIn = true;
                }else{
                    //process logout
                }
            }
        }else{
            $this->find($user);
        }
    }

    public function GetPresentUser(){
        return Session::get($this->_sessionName);
    }
        
    
    



    public function find($input){                                                  //checks the username in the db   
        $data = $this->_db->get('users',array('username','=',$input));
        if($this->_db->count()===1){
            $this->_data = $data->first();

            $data_stats = $this->_db->get('ranking',array('username','=',$input));
            $this->_statsData = $data_stats->first();

            $cc = $this->_db->get('cc',array('username','=',$input));
            $this->_cc = $cc->first();

            return true;                                                                       
        }
        else{
            return false;
        }
       
    }
    


    public function login($username=null,$password=null,$remember=false){                          //login logic
        if(!$username && !$password && $this->exists()){
            Session::put($this->_sessionName,$this->data()->username);
            if($this->data()->memberType == 1)
            {
                Session::put($this->_userType, "elite");
            }
            else{
                Session::put($this->_userType, "non-elite");
            }

            Session::put($this->_userEmail, $this->data()->email);
            Session::put($this->_userLeague , $this->_statsData->league);
            Session::put($this->_userCC , $this->_cc->cc);
            Session::put($this->_img, $this->data()->user_image);
            Session::put($this->_name, $this->data()->name);
            // Session::setOnline();

        }else 
        {
            $user = $this->find($username);
            if($user){
                if(password_verify($password,$this->_data->password)){
    
                    Session::put($this->_sessionName,$username);
                    if($this->data()->memberType == 1)
                    {
                        Session::put($this->_userType, "elite");
                    }
                    else{
                        Session::put($this->_userType, "non-elite");
                    }
                    Session::put($this->_userEmail, $this->data()->email);
                    Session::put($this->_userLeague , $this->_statsData->league);
                    Session::put($this->_userCC , $this->_cc->cc);
                    Session::put($this->_img, $this->data()->user_image);
                    Session::put($this->_name, $this->data()->name);

                    if($remember){
                        
                        $hash = Hash::unique();
                        $hashcheck = $this->_db->get('users_session',array('user_id','=',$username));
                        if($hashcheck->count()===0){
                        
                            $this->_db->insert('users_session',array(
                                'user_id' => $username,
                                'hash' => $hash
                            ));
                        }else {
                            $hash =$hashcheck->first()->hash;
                        }
    
                        Cookie::put($this->_cookieName,$hash,Config::get('remember/cookie_expiry'));
                    }
                    return true;
                }
                
            }

        }
     
        return false;
    }

    public function create_newuser($source){
        $fields = array(
            'username' => $source['username'],
            'password' => Hash::make($source['password']),
            'email' => $source['email'],
            'name' => $source['name'],
            'joined' => date('Y-m-d H:i:s' , time()),
            'group' => 1 
        );
        if($this->_db->createNewUser($fields)){
            return true;
        }
        else{
            return false;
        }
       
       // Redirect::to('home');
    }

// function to update user password
    public function update_user_pass($pass)
    {
        $hash_pass = Hash::make($pass);

        if($this->_db->update('users',Session::get($this->_sessionName),array('password'=>$hash_pass))){
            return true;
        }
        return false;
    }

//function to update user code coins                      $user=null is for :when we want to update cc for other user
    public function update_user_cc($cc,$user=''){
        if($user == ''){
            if($this->_db->update("cc",Session::get($this->_sessionName),array('cc'=>$cc)))
            {
                return true;
            }
        }                   
        elseif($user != '')
        {
            if($this->_db->update("cc",$user,array('cc'=>$cc)))
            {
                return true;
            }   
        }
        else{
            return false;
        }
    }


    public function exists(){
        return (!empty($this->_data))?true:false;
    }

    public function logout(){
        $this->_db->delete('users_session',array('user_id','=',$this->data()->username));
        Session::setOfline();
        // Session::delete($this->_sessionName);
        Session::Logout();
        Cookie::delete($this->_cookieName);                                

        // note : delete all the cookies not implemented yet 
    }

    public function ProfileData($username)
    {
        return $this->_db->getProfileGeneralData($username);
    }

    public function data(){                                               //returns data from user table
        return $this->_data;
    }


    public function statsData(){                                         //return users stats data from ranking table
        return $this->_statsData;
    } 

    public function cc(){                                               //return user code coins from cc table
        return $this->_cc;
    }

    public function isLoggedIn(){                                          //return boolean
        return $this->_isLoggedIn;
    }



}
