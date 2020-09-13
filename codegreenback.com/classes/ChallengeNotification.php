<?php
// this class is for managing user notification
// note : this page will be made as a middleware 

require_once $_SERVER['DOCUMENT_ROOT'].'/core/init.php';


class ChallengeNotification {

    private $_user,
            $_notifications,
            $_db;

    public function __construct($user){
        $this->_user = $user;
        $this->_db = DB::getInstance();
	$this->_notifications = [];
    }

    

    public function getNotification(){
                                                                        // get user challenge request notification from database
//        $results  = $this->_db->get("challengeNotification",array('accepter','=',$this->_user))->results();
	$results = $this->_db->sql_020($this->_user);
        //$notifications = array();

        foreach($results as $notification){


            if($notification->status == -1 && $this->checktime($notification->time)){                               //Notification which are not accepted by this user
               // print_r($notification);                                      
                $this->_notifications[] = $notification;
            }
            elseif($notification->status == -1 && !$this->checktime($notification->time))
            {
                       $user = new User($notification->challenger);
                       $userCC = $user->cc();
                       $newCC = $userCC->cc + $notification->cc;
                       $user->update_user_cc($newCC);                                     //user cc updated
                       $this->deleteNotification($notification->id);                      //notification deleted
                       $msg = $notification->accepter." did not respond to your Challenge :".$notification->challengeName ;
                       $this->_db->insert('generalNotifications',array('username'=>$notification->challenger,'notification'=>$msg)); 
            }
            elseif ($notification->status == 1 && $this->checktime($notification->time)) {
                # code...
                // $this->_notifications[] = $notification;

                if($notification->challengeName == 'TurnYourTurn')
                {
                    //check challenge1
                      if(!Challenge1::getWinner($notification->id)){
                            $this->_notifications[] = $notification;
                       }
                }
                elseif($notification->challengeName == 'Rushour'){
                    //check challenge2
                    $obj = new Challenge2($this->_user, $notification->id);
                    if($obj->check())
                    {
                        if(!$obj->setwinner()){
                        $this->_notifications[] = $notification;
                        }
                    }
                }
		elseif($notification->challengeName == 'Face/Off')
                {
                    $this->_notifications[] = $notification;

                }
            }
            elseif ($notification->status == 0 && !($this->checktime($notification->time))){
                       $user = new User($notification->challenger);
                       $userCC = $user->cc();
                       $newCC = $userCC->cc + $notification->cc;
                       $user->update_user_cc($newCC);                                     //user cc updated
                       $this->deleteNotification($notification->id);                      //notification deleted
                       $msg = $notification->accepter." did not respond to your Challenge :".$notification->challengeName ;
                       $this->_db->insert('generalNotifications',array('username'=>$notification->challenger,'notification'=>$msg)); 
            }
            elseif($notification->status == 1 && !($this->checktime($notification->time))){
                 switch ($notification->challengeName) {
                            case 'TurnYourTurn':
                                Challenge1::getWinner($notification->id);
                                break;
                            case 'Rushour':
                                $obj = new Challenge2($this->_user , $notification->id);
                                $obj->setwinner();
                                break;
                      	    case 'Face/Off':
                                $obj = new Challenge3($notification->id,$this->_user);
                                $obj->checkOverall();
                                break;
                       }
            }
        }

        //print_r($this->_notifications);
        return $this->_notifications;

    }

    /*  status about the request which the user has made          */      

    public function your_request()                                                             //user challengeRequest Status  the requests which are made by the user
    {
        // get user notification from database
            $results  = $this->_db->get("challengeNotification",array('challenger','=',$this->_user))->results();
            $data = array();
            
            
            if(!empty($results))
            {

                foreach($results as $notification)
                {
                    if($notification->status == 0){                                        //user declined                              
                            // delete the notification and credit back the cc to this user
                        $user = new User();
                        $userCC = $user->cc();
                        $newCC = $userCC->cc + $notification->cc;
                        $user->update_user_cc($newCC);                                     //user cc updated
                        $this->deleteNotification($notification->id);                      //notification deleted
                        $msg = $notification->accepter." denied your Challenge in ".$notification->challengeName ;
                        $this->_db->insert('generalNotifications',array('username'=>$notification->challenger,'notification'=>$msg));           //updated general notification
                            
                   }
                   elseif ($notification->status == 1 && !($this->checktime($notification->time)))                                    
                   {         
                       switch ($notification->challengeName) {
                           case 'TurnYourTurn':
                                Challenge1::getWinner($notification->id);
                                break;
                           case 'Rushour':
                                $obj = new Challenge2($this->_user , $notification->id);
                                $obj->setwinner();
                                break;
			case 'Face/Off':
                                $obj = new Challenge3($notification->id,$this->_user);
                                $obj->checkOverall();
                                break;
                       }                                                           
                    
                        
                   }
                   elseif ($notification->status == 0 &&  !($this->checktime($notification->time)))
                   {
                        $user = new User();
                       $userCC = $user->cc();
                       $newCC = $userCC->cc + $notification->cc;
                       $user->update_user_cc($newCC);                                     //user cc updated
                       $this->deleteNotification($notification->id);                      //notification deleted
                       $msg = $notification->accepter." did not respond to your Challenge :".$notification->challengeName ;
                       $this->_db->insert('generalNotifications',array('username'=>$notification->challenger,'notification'=>$msg)); 
                   }

                   elseif($notification->status == -1)                                                           //challenge accepted by opponent
                   {
                        $data[] = $notification;
                   }

                   elseif($notification->status == 1 && $notification->challengeName == 'TurnYourTurn')
                   {
                       //check for challenge1 status
                       if(!Challenge1::getWinner($notification->id)){
                            $data[] = $notification;
                       }
                   }
                    elseif($notification->status == 1 && $notification->challengeName == 'Rushour')
                   {
                       //check for challenge2 status
                       $obj = new Challenge2($this->_user, $notification->id);
                       if($obj->check())
                       {
                           if(!$obj->setwinner()){
                            $data[] = $notification;
                           }
                       }
                   }
		   elseif($notification->status == 1 && $notification->challengeName == 'Face/Off')
                	{
                
                    		$data[] = $notification;
                	}
                }
                return $data;

            }
            else 
            {
                return array();
            }

           

            //print_r($this->_notifications);
            

    }


    public function deleteNotification($id){
                                                                // function to delete the notification on the bases of time or user request
        //echo "delete function called";
       // echo $time;
        $this->_db->delete("challengeNotification",array("id","=",$id));
    }


    private function checktime($time){                                  //function to check notification validity
        $timeInSec = strtotime($time) + 90900;                               // )challenged time + 1 day
        $currentTime = strtotime(date("Y-m-d h:i:sa", time()));                // in seconds
        if($timeInSec >  $currentTime){
            return true;
        }
        else {
           // $this->deleteNotification($time);                      //storing outdated notification for deletion
            return false;
        }
    }



    public function sendNotification($sender,$reciever,$challengeName,$cc)
    {
        // function to make challenge notification 
        $pre = '';
        switch ($challengeName) {
            case 'TurnYourTurn':
                $pre = 'c1';
                break;
             case 'Rushour':
                $pre = 'c2';
                break;
             case 'Face/Off':
                $pre = 'c3';
                break;
            default:
                    return false;
        }
        
        //check is id already exists in the notification table to avoid conflicts

        if($this->_db->checkForRunningChallenge($sender,$reciever))
        {
            return false;
        }
            $id = $pre.'_'.Hash::bit_32_unique();

            /**
             * refer sql_002 for the transaction happening 
             * A new Challenge is Inserted And CodeCoin is Deducted from The $sender
             */

            if($this->_db->sql_002($id, $sender, $reciever, $challengeName, $cc))
            {

                /**
                 * if notification is enabled by the reciever 
                 * then send notification about the new challenge 
                 * request
                 */

                $this->SendEmailNotification($sender , $reciever ,$challengeName , $cc);
                return true;
            }
            else{
                return false;
            }

    }


    public function processChallengeNotification($challenger, $accepter, $cc, $challengename)
    {

    }



    
    /* ..........................  Send Email Notification to opponent that you have challenged ...............................*/

    public function SendEmailNotification($Challenger , $opponent , $challenge , $cc)
    {
        $data = $this->_db->getEmail_AccountNotificationSet($opponent);
        if(!$data->error())
        {
            $data = $data->results();
            if(!empty($data))
            {
                if($data[0]->challengeRequests == 1)
                {
                    $email = $data[0]->email;
                    Email::SendChallengeRequestEmail($Challenger, $challenge, $cc,  $email, $opponent);
                        
                }
            }
          

        }
    }

    /* ..........................  Send Email Notification to opponent that you have accepted the challenge ...............................*/

    public function SendEmailNotification_accepted($opponent , $challenge , $cc)
    {
        $data = $this->_db->getEmail_AccountNotificationSet($opponent);
        if(!$data->error())
        {
            $data = $data->results();
            if(!empty($data))
            {
                if($data[0]->challengeAccept == 1)
                {
                    $email = $data[0]->email;
                    Email::sendChallengeAcceptEmail($this->_user,$opponent,$email,$challenge,$cc);
                    
                        
                }
            }
          

        }
    }



    /* ............. INsert into challenge 3 table by:- amit  ...............       */

    public function sendNotification3($sender,$reciever,$challengeName,$cc)
    {
        // function to make challenge notification 
        $id = Hash::bit_32_unique();
        //check is id already exists in the notification table to avoid conflicts

            if($this->_db->insert('challengeNotification',array("id"=>$id,"challenger"=>$sender,"accepter"=>$reciever,"challengeName"=>$challengeName,"cc"=>$cc))){
                return $id;
            }
            else{
                return false;
            }

    }
 
    

}



?>
