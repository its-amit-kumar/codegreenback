<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/init.php';

class Validate
{                                            
    private $_passed = false,                                  //if data is valid, turns true
            $_errors,                                //for storing errors
            $_db = null;                                         //db object to be created

    public function __construct()                       //connection to database and all its member functions 
    {                                                   //when object is create for Validation class
        $this->_db = DB::getInstance();
    }


    public function check($source,$items=array())
    {                                                                         //logic for validation
        foreach($items as $item=>$rules){
            foreach($rules as $rule=>$rule_value)
            {
                
                $value = trim($source[$item]);                             //removing whitespaces and storing the value
                $value = stripslashes($value);
                $value = htmlspecialchars($value);
            
                $item = $item;                                      //sanitizing data
               // echo $item."   ";

                if($rule==='required' && empty($value))
                {
                    $this->addError(array($item => "cannot be empty!"));
                }
                else if (!empty($value))
                {
                    switch($rule){
                        case 'min':
                            if(strlen($value)<$rule_value){
                                $this->addError(array($item => " should be atleast ".$rule_value." characters long "));
                            }
                        break;
                        case 'max':
                            if(strlen($value)>$rule_value){
                                $this->addError(array($item => " should be less than ".$rule_value));
                            }
                        break;
                        case 'matches':
                            if($value != $source[$rule_value]){
                                $this->addError(array($item=> 'password did not mathch'));
                            }
                        break;
                        case 'unique':
                            $check = $this->_db->get('users',array($item,'=',$value));
                            if($check->count()){
                                $this->addError(array($item => "already exists"));
                            }
                        break;
                        case 'regex':
                            if ( !preg_match($rule_value, $value) ){
                                $this->addError(array($item => "Invalid"));
                            }
                    }
                }
            }
        }

        if(empty($this->_errors)){                                        //setting _passed to true if no errors
            $this->_passed = true;
        }
        return $this;                                                        //to chain on other members
    }


    public function login_check($source){
        $fields = array(
            'login_username' => array('required'=>true),
            'login_password' => array('required'=>true)
        );

        $this->check($source,$fields);
        return $this;
    }

    public  function  update_pass_check($source){                             //update password validation
            $error = null;
            $current_pass = $source['current-pass'];
            $new_pass = $source['new-pass'];
            $repass = $source['password-again'];
            $hash = $this->_db->get('users',array('username','=',Session::get('user')))->first()->password;
            // print_r($hash);
           // echo "hello from func";
            if(!($new_pass === $repass)){
                $error = "Password did not match";
                return array("status"=>0, 'error'=>$error);
            }
            elseif(!password_verify($current_pass,$hash)){
                $error = "Wrong Password Entered !";
                return array('status'=>0, 'error'=>$error);
            }
            else {
                return array('status'=>1);
            }
            
    }


    public function signup_check($source){
        $fields = array(
            'username' => array(
                'required' => true,
                'min' => 5,
                'max' => 20,
                'regex' => '/^[A-Za-z][A-Za-z0-9]{5,21}$/',
                'unique' => 'users'
            ),
            'email' => array(
                'required' => true,
                'unique' => 'users'
            ),

            'password' => array(
                'required' => true,
                'min' => 8,
            ),
            'password_again' => array(
                'required' => true,
                'matches' => 'password'
            ),
            'name' => array(
                'required' => true,
                'min'=>2,
                'max'=>50,
                'regex'=>'/^[A-Za-z ]{5,40}$/'
            )
        );

        $this->check($source,$fields);
        return $this;
    }

    public static function photo_validate($source)
    {       $error = null;
            $allowedExts = array("jpeg", "jpg", "png");
            $extension = end(explode(".", $source["file"]["name"]));
            $check = getimagesize($source['file']['tmp_name']);

            if(empty($check)){
                $error = 'file is not an image !' ;
                return $error;
            }
            elseif ($source['file']['size'] > 5000000) {
                $error = 'file exceeds limit !';
                return $error;
            }
            elseif(!in_array($extension,$allowedExts,true)){
                $error = 'Only jpg,jpeg,png images are allowed ';
                return $error;
            }
            return $error;
    }

    private function addError($error){                                          //function to add error
        $this->_errors[] = $error; 
    }


    public function errors(){    
        $this->_errors[] = array("status"=>"-1");                                                   //get the errors array
        return $this->_errors;
    }

    public function passed(){                                                      //if true form validated
        return $this->_passed;                                                      //return boolean
    }



    public static function check_base64_image($base64) {



    $img = imagecreatefromstring(base64_decode($base64));
    if (!$img) {
        return false;
    }
    $temname = $_SERVER['DOCUMENT_ROOT'].'/data/tem/'.uniqid(rand()).'.png';

    imagepng($img, $temname);
    $info = getimagesize($temname);
    unlink($temname);

    if ($info[0] > 0 && $info[1] > 0 && $info['mime']) {
        return true;
    }

    return false;
}

/**
 * public function to validate withdraw request data
 * referer : WithdrawCC class
 * @param $data = Containing (status, account_no, cc_to_withdraw, contact, ifsc, processing_id, re_account_no)
 */

    public static function validate_001($data)
    {
        $data = json_decode($data);
        if($data->status)
        {
            if(!preg_match('/[0-9]{9,18}$/', $data->account_no))
            {
                return array('status' => -1, 'msg' => "Invalid Account Number !! ");
            }

            if($data->account_no != $data->re_account_no)
            {
                return array('status' => -1, 'msg' => "Account Numbers Do Not Match !! ");
            }

            if(!preg_match('/[A-Z|a-z]{4}[0][a-zA-Z0-9]{6}$/', $data->ifsc))
            {
                return array('status' => -1, 'msg' => "Invalid IFSC Code !! ");
            }

            if(!preg_match('/[0-9]{3,5}$/', $data->cc_to_withdraw))
            {
                return array('status' => -1, 'msg' => "Invalid CodeCoins Selected !! ");
            }

            if(!preg_match('/[0-9]{10}$/', $data->contact))
            {
                return array('status' => -1, 'msg' => "Invalid Phone Number !! ");
            }
            return array('status' => 1);
        }

        return array('status' => -1, 'msg' => "An Error Occurred !! ");
    }



}
