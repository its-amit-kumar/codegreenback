<?php
require_once dirname(__DIR__).'/ClassesTemp/DB.php';
class Validate
{                                            
    private $_passed = false,                                  //if data is valid, turns true
            $_errors = array(),                                  //for storing errors
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
                $item = escape($item);                                      //sanitizing data

                if($rule==='required' && empty($value))
                {
                    $this->addError($item." cannot be empty!");
                }
                else if (!empty($value))
                {
                    switch($rule){
                        case 'min':
                            if(strlen($value)<$rule_value){
                                $this->addError($item." should be atleast ".$rule_value." characters long ");
                            }
                        break;
                        case 'max':
                            if(strlen($value)>$rule_value){
                                $this->addError($item." should be less than ".$rule_value);
                            }
                        break;
                        case 'matches':
                            if($value != $source[$rule_value]){
                                $this->addError('password did not mathch');
                            }
                        break;
                        case 'unique':
                            $check = $this->_db->get('users',array($item,'=',$value));
                            if($check->count()){
                                $this->addError($item." already exists! choose another ");
                            }
                        break;
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


    public function signup_check($source){
        $fields = array(
            'username' => array(
                'required' => true,
                'min' => 2,
                'max' => 20,
                'unique' => 'users'
            ),
            'email' => array(
                'required' => true,
                'unique' => 'users'
            ),

            'password' => array(
                'required' => true,
                'min' => 6,
            ),
            'password_again' => array(
                'required' => true,
                'matches' => 'password'
            ),
            'name' => array(
                'required' => true,
                'min'=>2,
                'max'=>50
            )
        );

        $this->check($source,$fields);
        return $this;
    }

    private function addError($error){                                          //function to add error
        $this->_errors[] = $error; 
    }


    public function errors(){                                                        //get the errors array
        return $this->_errors;
    }

    public function passed(){                                                      //if true form validated
        return $this->_passed;                                                      //return boolean
    }
}