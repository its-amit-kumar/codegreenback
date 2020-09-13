<?php

require_once $_SERVER['DOCUMENT_ROOT'].'/core/init.php';
require_once  $_SERVER['DOCUMENT_ROOT'].'/functions/getHeader.php';


$header = getBearerToken();

if($header)
{
    if(Token::jwtVerify($header) == -1)
    {
        echo -1;
        exit;
    }
}
else{
    echo -1;
    exit;
}

if(Input::exists())
{
    if($_POST['service'] == 'profile')
    {
        // get user profile details
        $obj = new AccountSetting();
        echo $obj->getUserProfileSettings();
    }
    elseif ($_POST['service'] == 'Notification') {
        // get user notification settings
        $obj = new AccountSetting();
        echo $obj->getUserNotificationSettings();
        
    }
    elseif($_POST['service'] == 'getTransactions')
    {
        // get user code coins transaction details
        $obj = new AccountSetting();
        echo $obj->getUserTransactions();
    }
    elseif($_POST['service'] == 'updateProfile')
    {
        print_r($_POST);
        $validate = new Validate();
        $field = array('change-name'=>array(
            'required'=>true,
            'min'=>2,
            'max'=>40,
            'regex'=>'/^[A-Za-z ]{5,40}$/'
        ));

        if($validate->check($_POST,$field)->passed())
        {
            $obj = new AccountSetting();
            if($obj->updateProfileSettings($_POST))
            {
                echo json_encode(array('status'=>1));
            }
            else{
                echo json_encode(array('status'=>0));
            }
        }
        else{
            echo json_encode(array('status'=>0));
        }
    }
    elseif($_POST['service'] == 'updateNotificationSetting')
    {
        // print_r($_POST)
        $arr = array();
        if(isset($_POST['not-set-general']))
        {
            if($_POST['not-set-general'] == 'on')
            {
                $arr['general'] = '1';
            }
        }else{
            $arr['general'] = '0';
        }

        if(isset($_POST['not-set-challengeRequest']))
        {
            if($_POST['not-set-challengeRequest'] == 'on')
            {
                $arr['challengeRequests'] = '1';
            }
        }else{
            $arr['challengeRequests'] = '0';
        }

        if(isset($_POST['not-set-challengeAccept']))
        {
            if($_POST['not-set-challengeAccept'] == 'on')
            {
                $arr['challengeAccept'] = '1';
            }
        }else{
            $arr['challengeAccept'] = '0';
        }

        if(isset($_POST['not-set-push']))
        {
            if($_POST['not-set-push'] == 'on')
            {
                $arr['push'] = '1';
            }
        }else{
            $arr['push'] = '0';
        }

        $obj = new AccountSetting();
        if($obj->updateNotificationSettings($arr))
        {
            echo json_encode(array('status'=>"1",'msg'=>"Changes Saved Successfully !!"));
        }
        else{
            echo json_encode(array('status'=>"0", 'msg'=>"An Error Ocurred ! :("));
        }
    }
    elseif($_POST['service'] == 'uploadNewImg')
    {
        if(isset($_POST['image']))
        {
            $obj = new AccountSetting();
            echo $obj->uploadUserImage($_POST['image']);
        }
    }
    elseif ($_POST['service'] == 'rmi') {
        $obj = new AccountSetting();
        echo $obj->removeuserimage();
    }
}

?>
