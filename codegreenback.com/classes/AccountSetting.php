<?php
/*.......................User account Settings..................................
............................Made by ayush ........................................   */

require_once $_SERVER['DOCUMENT_ROOT'].'/core/init.php';

class AccountSetting {
    private $_db,
            $_user;

    public function __construct()
    {
        $this->_db = DB::getInstance();
        if(Session::exists(Config::get('session/session_name')))
        {
            $this->_user = Session::get('user');
        }
        
    }
    public function getUserProfileSettings()
    {
        if(Session::exists(Config::get('session/session_name')))
        {
            if($this->_user == Session::get('user'))
            {
                $data = $this->_db->getUserProfileSettings($this->_user);
                $data = $data->results()[0];
                $username = $data->username;
                $name = $data->name;
                $email = $data->userEmail;
                if(empty($data->user_image))
                {
                    $img = 'public/img/avatar.png';
                }else{
                    $img = $data->user_image;
                }
                

                if(empty($data->userdata))
                {
                    $about = '';
                    $website = '';
                    $gender = '';
                }
                else{
                    $details = json_decode($data->userdata);
                    $about = $details->about;
                    $website = $details->website;
                    $gender = $details->gender;
                }

                return json_encode(array(
                    'status'=>1,
                    'username'=>$username,
                    'name'=>$name,
                    'img'=>$img,
                    'email'=>$email,
                    'details'=>array(
                        'about'=>$about,
                        'website'=>$website,
                        'gender'=>$gender
                    )
                ));
                

            }
            else{
                return json_encode(array('status'=>0));
            }
        }
        else{
            
        }

    }



    public function updateProfileSettings($data)
    {
        if(Session::exists(Config::get('session/session_name')))
        {
            if($this->_user == Session::get('user'))
            {
                /* ......................... USER TRANSACTION HERE ...................  */
                $this->_db->update('users',$this->_user,array(
                    'name'=>$data['change-name'],
                ));

                $gender = '';
                if(isset($data['gender']))
                {
                    $gender = $data['gender'];
                }

                $array = array(
                    'website'=>$data['website'],
                    'about'=>$data['about'],
                    'gender'=>$gender
                );

                $this->_db->update('generalDetails',$this->_user, array('data'=>json_encode($array)));
                return true;
            }
            else{
                return false;
            }
        }
        else{
            return false;
        }
    }



    public function getUserNotificationSettings()
    {
        if(Session::exists(Config::get('session/session_name')))
        {
            if($this->_user == Session::get('user'))
            {
                $data = $this->_db->get('userNotificationSettings',array('username','=',$this->_user));
                if($data)
                {
                    $data = $data->results()[0];
                    $data->status = "1";
                    return json_encode($data);
                }
                else{
                    return json_encode(array('status'=>0));
                }
            }
            else{
                return json_encode(array('status'=>0));
            }   
        }
    }


    public function updateNotificationSettings($data = array())
    {
         if(Session::exists(Config::get('session/session_name')))
        {
            if($this->_user == Session::get('user'))
            {
                if($this->_db->update('userNotificationSettings',$this->_user,$data)){
                    return true;
                }   
                else{
                    return false;
                }
                
            }
        }

        return false;
    }

    public function uploadUserImage($data)
    {
        if(Session::exists(Config::get('session/session_name')))
        {
            if($this->_user == Session::get('user'))
            {
                $image_array_1 = explode(";", $data);
                $image_array_2 = explode(",", $image_array_1[1]);
                $base64 = $image_array_2[1];
                if(Validate::check_base64_image($base64)){
                    // echo "image";
                    $status = $this->storeuserimage($base64);
                    if($status['status'])
                    {
                        return json_encode(array('status'=>1,'msg'=>"Profile Picture Updated Successfully",'imguri'=>$status['img_upload_uri']));
                    }
                }
                else
                {
                    return json_encode(array('status'=>0, 'msg'=>'An error occurred  !! :('));
                }

            }
        }
    }


    private function storeuserimage($img_base64)
    {
        $arr = array();
        $img = imagecreatefromstring(base64_decode($img_base64));
        if (!$img) {
            $arr['status'] = false;
            return $arr;
        }

        $pathname = 'public/img/user_image/'.Hash::imgname();
        $fullpathname = $_SERVER['DOCUMENT_ROOT'].'/'.$pathname;

        imagepng($img, $fullpathname);

        if(is_file($fullpathname))
        {
            //process for db AND REMOVE PREVIOUS IMAGE

            $status = $this->_db->sql_019($this->_user, $pathname);
            if($status['status'])
            {
                $arr['status'] = true;
                $arr['img_upload'] = false;
                
                if($status['img_upload'])
                {
                    $arr['img_upload'] = true;
                    $arr['img_upload_uri'] = $pathname;
		    Session::put(Config::get('session/user_img'),$pathname);
                }

                if(!empty($status['img']))
                {
                    unlink(dirname(__DIR__).'/'.$status['img']);                        //delete image from directory
                }
                return $arr;
            }
            else
            {
                $arr['status'] = false;
                return $arr;
            }
        }
        $arr['status'] = false;
        return $arr;
    }






    public function getUserTransactions()
    {
        if(Session::exists(Config::get('session/session_name')))
        {
        
                $db = DB::getInstance();
                $data =$db->getUserTransactions($this->_user);
                if(!empty($data))
                {
                    $data['count'] = count($data);
                    return json_encode($data);
                }
                else{
                    return json_encode(array('count'=>0));
                }
            

        }

    }

    public function removeuserimage()
    {
        if(Session::exists(Config::get('session/session_name')))
        {
            $user = Session::get(Config::get('session/session_name'));
            $status = $this->_db->sql_019($this->_user,null);
            if($status['status'])
            {
                if(!empty($status['img']))
                {
                    unlink(dirname(__DIR__).'/'.$status['img']);
                }
		Session::put(Config::get('session/user_img'),"public/img/avatar.png");
                return json_encode(array('status' => true,'img_uri' => "/public/img/avatar.png"));
            }
        }
        return  json_encode(array('status' => false));;
    }


}





?>
