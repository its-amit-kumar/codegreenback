<?php
require_once  dirname(__DIR__).'/ClassesTemp/DB.php';
require_once  dirname(__DIR__).'/ClassesTemp/Session.php';
require_once  dirname(__DIR__).'/ClassesTemp/Cookie.php';
require_once  dirname(__DIR__).'/ClassesTemp/Hash.php';
require_once  dirname(__DIR__).'/ClassesTemp/Validate.php';
class User {
    private $_db,
            $_data,
            $_sessionName,
            $_cookieName,
            $_isLoggedIn,
            $_statsData;

    public function __construct($user = null)
    {
        $this->_db = DB::getInstance();
        $this->_sessionName = 'user';
        $this->_cookieName = 'hash';
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

    public function create($field=array())
    {
        if(!$this->_db->insert('users',$field)){
            throw new Exception('user cannot be created ');
        }
    }

    public function find($input){                                                  //checks the username in the db
        $data = $this->_db->get('users',array('username','=',$input));
        if($this->_db->count()===1){
            $this->_data = $data->first();
            //return true;
        }
        else{
            return false;
        }
        $data_stats = $this->_db->get('ranking',array('username','=',$input));
            if($this->_db->count()===1){
                $this->_statsData = $data_stats->first();
                return true;
            }
    }
    


    public function login($username=null,$password=null,$remember=false){                          //login logic
        
        if(!$username && !$password && $this->exists()){
            Session::put($this->_sessionName,$this->data()->username);
            Session::setOnline();

        }else 
        {
            $user = $this->find($username);
            if($user){
                if(password_verify($password,$this->_data->password)){
    
                    Session::put($this->_sessionName,$username);
                    Session::setOnline();
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
            'joined' => date('Y-m-d H:i:s'),
            'group' => 1 
        );
        $this->create($fields);
       
       // Redirect::to('home');
    }


    public function exists(){
        return (!empty($this->_data))?true:false;
    }

    public function logout(){
        Session::setOfline();
        $this->_db->delete('users_session',array('user_id','=',$this->data()->username));
        Session::delete($this->_sessionName);
        Cookie::delete($this->_cookieName);
    }

    public function data(){
        return $this->_data;
    }


    public function statsData(){
        return $this->_statsData;
    }

    public function isLoggedIn(){
        return $this->_isLoggedIn;
    }



}
