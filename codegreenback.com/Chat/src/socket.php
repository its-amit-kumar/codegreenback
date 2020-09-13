<?php
//namespace MyApp;
use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;
use Ratchet\Http\HttpServerInterface;
use Psr\Http\Message\RequestInterface;


require_once dirname(__DIR__).'/ClassesTemp/DB.php';
require_once dirname(__DIR__).'/ClassesTemp/RedisDB.php';
require_once dirname(__DIR__).'/ClassesTemp/StaticFunc.php';


class Socket implements HttpServerInterface {

    private $_db,
            $_results,
            $_presentUser,
            $_user,
            $_clientsStored,
            $_friendList,
            $_onlineInfo;

    protected $clients,$onlneUsers;

/*

///////////////////////////////////////////////////////////////////////////////////////////////////////////
////////DO NOT DELETE THE BELOW FUNCTION, IT WILL BE INPLEMENTED ONCE WE LAUNCH THE FRIEND CONCEPT////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////

*/

  /* private function getFriends($username){
        //$db = DB::getInstance();
        $result = $this->_db->get("friends",array("username","=",$username))->results();
        $friends = json_decode($result[0]->friendDetails,True)['friends'];
        if($friends == null){
            return array('friends'=>"false");
        }
        else{
            return array('friends'=>$friends);
        }
    }*/


    /*  THE FOLLOWING VARIABLES ARE INTIATED AS SOON AS THE SERVER IS LAUNCHED, NOT WHEN THE CONNECTION TAKES PLACE
        THE ARRAY AND OBJECTS IS COMMON FOR ALL THE USERS CONNECTED TO THE SERVER AND EACH CHANGE BY A SPECIFIC CONNECTION 
        IS REFLECTED TO ALL VARIABLES, ARRAY AND OBJECT   */


    public function __construct()
    {
        $this->clients = new \SplObjectStorage;
        $this->_clientsStored = array();
        $this->onlineUsers = array();
        $this->_online = array();
        $this->_onlineInfo = array();

        echo "socket created";
       
        
    }

    /*
    THE FOLLOWING FUNCTION IS AUTOMATICALLY LAUNCHED ON THE COMMON OBJECT AS SOON AS A NEW CONNECTION CONNECTS.
    */ 

    public function onOpen(ConnectionInterface $conn, RequestInterface $request = null) {
        array_push($this->_clientsStored, $conn->resourceId);
	$this->clients->attach($conn);
	$token = $conn->httpRequest->getUri()->getQuery();
//	$conn->send($token);
        $cookiesRaw = $conn->httpRequest->getHeader('Cookie');
            
            $cookiesArr = \GuzzleHttp\Psr7\parse_header($cookiesRaw)[0];
            //$sessionId = $conn->WebSocket->request->getCookies()['PHPSESSID']; 
            $sessionId = $cookiesArr['PHPSESSID'];
	    $json = file_get_contents('http://www.codegreenback.com:80/get_session_info.php?id='.$sessionId.'&token='.urlencode($token), false);
            $session = json_decode($json,true);
            if($session['success']==-1){
                $this->clients->detach($conn);
                $conn->send("ddisconnect");
                $conn->close();
            }
	    else{
		if(!isset($this->_onlineInfo[$session['user']])){
                $this->_onlineInfo[$session['user']] = $conn;
                $conn->Session->start();
                $conn->Session->set('user', $session['user']);
                $conn->Session->set('requests', 0);
                $conn->Session->set('endTime', time()+60);
                array_push($this->_online, $conn->Session->get('user'));
                $onlineU = array();
                foreach ($this->_online as $key => $value) {
                    array_push($onlineU, $value);
                }
                $sendMsg = array("cmd"=>"setUserOnline","user"=>$onlineU);
                $conn->send(json_encode($sendMsg));
                foreach ($this->clients as $client) {
			$client->send(json_encode(array("cmd"=>"onlineUser","user"=>$conn->Session->get('user'))));
		}
		}
		else{
			$this->clients->detach($this->_onlineInfo[$session['user']]);
        $this->_online=array_diff($this->_online,[$session['user']]);
        foreach($this->clients as $client){
            $client->send(json_encode(array("cmd"=>"setOfflineUser","user"=>$session['user'])));
        }
	$this->_onlineInfo[$session['user']]->close();
	$this->_onlineInfo[$session['user']]->Session->clear();
	unset($this->_onlineInfo[$session['user']]);

			$this->_onlineInfo[$session['user']] = $conn;
			$conn->Session->start();
                $conn->Session->set('user', $session['user']);
                $conn->Session->set('requests', 0);
                $conn->Session->set('endTime', time()+60);
                array_push($this->_online, $conn->Session->get('user'));
                $onlineU = array();
                foreach ($this->_online as $key => $value) {
                    array_push($onlineU, $value);
                }
                $sendMsg = array("cmd"=>"setUserOnline","user"=>$onlineU);
                $conn->send(json_encode($sendMsg));
                foreach ($this->clients as $client) {
                        $client->send(json_encode(array("cmd"=>"onlineUser","user"=>$conn->Session->get('user'))));
                }

		}
                }

                
        //$conn->send($conn->Session->get('user'));
    }

    /*
    THE FOLLOWING FUNCTION IS AUTOMATICALLY LAUNCHED ON THE COMMON OBJECT AS SOON AS A MESSAGE IS RECEIVED.
    A TYPICAL MSG HAS A CMD(COMMAND) ALONG WITH THE NAME OF THE USER THAT HAS TRANSMITTED THE MSG
    */ 

    public function onMessage(ConnectionInterface $from, $msg) {
        if($from->Session->get('endTime')<time()){
            $from->Session->set('requests', 1);
            $from->Session->set('endTime', time()+60);
        }
        //if($from->Session->get('endTime')>time()){
           // if($from->Session->get('requests')<100){
              //  $from->Session->set('requests', $from->Session->get('requests')+1);
            //}
           // else{
            //    $from->close();
          //  }
        //}

        $sendMsg = array();
        $online = array();

        $msgReceived = json_decode($msg,True);
        if($msgReceived['cmd']=='s'){
            foreach ($this->clients as $client1) {
                $from->send($client1->Session->get('user'));
            }
        }

        if($msgReceived['cmd']=="challenge3request"){

            $opponent = $msgReceived['opponent'];
            
            // $from->send($msgReceived['_vid']);
            // $from->send($from->Session->get('user'));

            if(isset($this->_onlineInfo[$opponent]))
            {
                $opponent_to_be_emitted = $this->_onlineInfo[$opponent];
                $opponent_to_be_emitted->send(json_encode(array("cmd"=>"challenge3request","from"=>$from->Session->get('user'),"_vid"=>$msgReceived["_vid"],"cc"=>$msgReceived["cc"])));

            }
            else
            {
                $from->send(json_encode(array("cmd"=>"challenge3requesterror","_vid"=>$msgReceived['_vid'],"from"=>$opponent)));
            }

        }

        if($msgReceived["cmd"]=="challenge3response"){

            $opponent = $msgReceived['to'];
            if($msgReceived['response'] == "accepted")
            {
                /**
                 * PERFORM THIS USER VERIFICATION 
                 * i.e. Elibile for challenge or not
                 */

                $verification = StaticFunc::verify_challenge_accepter($from->Session->get('user'), $msgReceived['cc']);
                if($verification['status'] == 1)
                {
                    $challenger = $opponent;
                    $accepter  = $from->Session->get('user');
                    $cc = (int)$msgReceived['cc'];
                    $token = $msgReceived['_vid'];

                    /**
                     * verify from redis database now
                     */
                    $redis_obj = new RedisDB();
                    if($redis_obj->verify_c3($accepter, $challenger, $cc, $token))
                    {
                        /**
                         * challenge verified !! 
                         * process further sql db insertions
                         * and get the challengeid
			 */

			
			$this->_onlineInfo[$msgReceived['to']]->send(json_encode(array('cmd'=>"_r_a")));        //sending request accepted to other client _r_a
                        $cid = StaticFunc::make_new_faceoff($challenger, $accepter, $cc);
                        if( $cid != false)
                        {
                            $toBeSend = array(
                                "cmd"=>"challenge3RedirectUrl",
                                "url"=>"/Faceoff/challenge.php?cid=".$cid
                                );
                            $this->_onlineInfo[$msgReceived['to']]->send(json_encode($toBeSend));
                            $from->send(json_encode($toBeSend));
                        }
			else
                        {
                            $this->_onlineInfo[$msgReceived['to']]->send(json_encode(array('msg'=>"an error occurred")));
                            $from->send(json_encode(array("msg"=> "an error occured")));
                        }

		    }
		    else
		    {
			    $this->_onlineInfo[$msgReceived['to']]->send(json_encode(array('msg'=>"redis not verified")));
			    $from->send(json_encode(array("msg"=>"redis not verified")));
		    }
                }
                else
                {
                    $msg = json_encode(array(
                        'cmd'           => "v_error",                          //verification error
                        'error_code'    => $verification['errorcode'] 
                    ));
                    $from->send($msg);
                }
            }
            else if($msgReceived["response"]=="rejected"){

                    // $this->_db->updateNotification("challengeNotification",$msgReceived["challengeid"],array("status"=>"0"));
                    $this->_onlineInfo[$msgReceived['to']]->send(json_encode(array("cmd"=>"challenge3response","response"=>"rejected","from"=>$from->Session->get('user'))));

                }

        //     if($this->performChecks($msgReceived['challengeid'],$from->Session->get('user'))==20){
        //         if($msgReceived["response"]=="accepted"){
        //             $this->_db->insert("challenge3",array(
        //                 "id"=>$msgReceived["challengeid"],
        //                 "challenger"=>$msgReceived["to"],
        //                 "opponent"=>$from->Session->get('user'),
        //                 "cc_bet"=>$msgReceived["cc"],
        //                 "common_questions"=>json_encode(array("1"=>"3","2"=>"4","3"=>"5"),True),
        //                 "challenger_ques_solved"=>json_encode(array("questions"=>array()),True),
        //                 "opponent_ques_solved"=>json_encode(array("questions"=>array()),True),
        //                 "end_time" => time()+86400
        //             ));
        //             $this->_db->updateNotification("challengeNotification",$msgReceived["challengeid"],array("status"=>"1"));
        //             $toBeSend = array("cmd"=>"challenge3RedirectUrl","url"=>"/challenge3.php?cid=".$msgReceived["challengeid"]);
        //             $this->_onlineInfo[$msgReceived['to']]->send(json_encode($toBeSend));
        //             $from->send(json_encode($toBeSend));
        //         }
        //         if($msgReceived["response"]=="rejected"){

        //             $this->_db->updateNotification("challengeNotification",$msgReceived["challengeid"],array("status"=>"0"));
        //             $this->_onlineInfo[$msgReceived['to']]->send(json_encode(array("cmd"=>"challenge3response","response"=>"rejected","from"=>$from->Session->get('user'),"challengeid"=>$msgReceived["challengeid"])));

        //         }
        //     }
        //    else{
        //         $this->_db->delete("challengeNotification",array("id","=",$msgReceived["challengeid"]));
        //         $from->send(json_encode(array("cmd"=>"challenge3requesterror","challengeid"=>$msgReceived['challengeid'],"from"=>$opponent)));
        //     }
        }
    }

    /*
    THE FOLLOWING FUNCTION IS LAUNCHED AS SOON AS A CONNECTION DISCONNECTS AND IS ABSOLUTELY LAUNCHED NO MATTER WHAT
    */


    public function onClose(ConnectionInterface $conn, $isInitial = null) {    
        $this->clients->detach($conn);
        unset($this->_onlineInfo[$conn->Session->get('user')]);
        
        $this->_online=array_diff($this->_online,[$conn->Session->get('user')]);
        foreach($this->clients as $client){
            $client->send(json_encode(array("cmd"=>"setOfflineUser","user"=>$conn->Session->get('user'))));
	}
        $conn->Session->clear();
    }

    public function onError(ConnectionInterface $conn, \Exception $e) {
	    $this->clients->detach($conn);
        unset($this->_onlineInfo[$conn->Session->get('user')]);

        $this->_online=array_diff($this->_online,[$conn->Session->get('user')]);
        foreach($this->clients as $client){
            $client->send(json_encode(array("cmd"=>"setOfflineUser_error","user"=>$e->getMessage())));
        }
        $conn->Session->clear();

    }

    /////////TYPES OF ERROR///////////
    /* 
    10 - the time to respond has ended
    11 - emitted when the users arev not in the same league, this is malicious
    
    12 - invalid cid, malicious
    13 - already running, finished or rejected challenge, might be a bug, possibility of malicious activity
    14 - the user emitting this is not a part of this challenge, maliocious activity
    15 - the cid is not for challenge3
    20 - perfectly passed all checks
    */

    public function performChecks($cid,$from){

        //$dbb = DB::getInstance();
        $checks = $this->_db->get("challengeNotification",array("id","=",$cid));
        //return json_encode($checks->results());
        //return json_encode($checks->results()[0]->status);
        if($checks->count()==0){
            return 12;
        }
        if($checks->results()[0]->challengeName!="challenge3"){
            return 15;
        }
        if($checks->results()[0]->status != "1" && $checks->results()[0]->status != "-1"){
            return 13;
        }
        if($from!=$checks->results()[0]->challenger && $from!=$checks->results()[0]->accepter){
            return 14;
        }
        return 20;
        
    }
}

?>

