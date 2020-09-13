

<?php

require_once $_SERVER['DOCUMENT_ROOT'].'/core/init.php';

/**
 * <!-- .............Made by amit .............. -->
 */
class Challenge2
{
	private $_user,
			$_isChallenger,
			$_challengeId,
			$_db,
			$_result,
			$_time,
			$_typeOfUser;
	
	function __construct($userName, $_challengeId)
	{
		
		$this->_user = $userName;
		$this->_challengeId = $_challengeId;
		$this->_db = DB::getInstance();
		$data = $this->_db->get("challenge2",array('id', '=', $_challengeId));
		if($data)
		{
			$this->_result = $data->results();
		}
		
	
	}

	public function check()
	{
		if(!empty($this->_result) && Session::exists(Config::get('session/session_name'))){
			if($this->_user == Session::get(Config::get('session/session_name'))){
				return true;
			}
			return false;
		}else{
			return false;
		}
	}

	public function printt(){
		return $this->_result[0];
	}

	private function checkChallenger(){
		//print_r($result);
		$challenger = $this->_result[0]->challenger;
		if ($this->_user == $challenger){
			$this->_isChallenger = true;
			$this->_typeOfUser = "challenger";
		}
		else{
			$this->_isChallenger = false;
			$this->_typeOfUser = "opponent";
		}
	}

	public function isChallenger(){
		$this->checkChallenger();
		return $this->_isChallenger;
	}

	public function checkStarted(){
		if($this->_isChallenger){
			if($this->_result[0]->challenger_end_time == ""){
				//echo "CY";
				return false;
				
			}
			else{
				//echo "CN";
				return true;
				
			}
		}
		else{
			if($this->_result[0]->opponent_end_time == ""){
				return false;
			}
			else{
				return true;
		}
	}



}
	private function insertTime($typeOfUser,$time){
		//public function update($table,$id,$fields)
		$this->_db->updateNotification("challenge2",$this->_challengeId,array($typeOfUser."_end_time"=>$time)) or die("cannot insert1");
	}

	public function setTime($typeOfUser){
		$a = time()+3600;
		$this->insertTime($typeOfUser,$a); //or die("cannot inser2");
	}

	public function getTime($userType){
		//print_r($this->_db->getTime($userType,$id));
		if($userType == "challenger"){
			return strtotime($this->_db->get('challenge2',array("id","=",$this->_challengeId))->results()[0]->challenger_end_time);// or die("Unable to get time");
			// $this->_db->get("challenge2",array("id","=",$this->_challengeId))->results();
		}
		else{
			return strtotime($this->_db->get('challenge2',array("id","=",$this->_challengeId))->results()[0]->opponent_end_time);
		}
		//->$userType.'_end_time';
	}

	public function getQuestionsSolved(){
		$this->checkChallenger();
		if($this->_isChallenger){
			return $this->_result[0]->challenger_ques_solved;
		}
		else{
			return $this->_result[0]->opponent_ques_solved;
		}
		
	}
	public function updateSolvedTestCases($NewJson){
		$a = json_encode($NewJson);
		if($a != "null"){
			$this->_db->updateNotification("challenge2",$this->_challengeId,array($this->_typeOfUser.'_ques_solved' => $a)) or die(json_encode($NewJson));
		}

	}


	/*/ ....................................Functions by ayush ...........................
 ......................................................................................................*/

	public function delete($data)
    {
        $id = $data['id'];
        $accepter = $data['accepter'];
        $challenger = new User($data['challenger']);
        $challengerCC = $challenger->cc();                                       //return object of cc table
        $cc = $data['cc'];                                                    //rollback cc to challenger
		$updatedCC = $challengerCC->cc + $cc;
		
		//.....................................USE TRANSACTION HERE......................................//

        $challenger->update_user_cc($updatedCC,$data['challenger']);
        $this->_db->delete('challengeNotification',array('id','=',$id));
        $msg = $data['accepter']." has declined Your Challenge ".$data['challengeName'];
		$this->_db->insert('generalNotifications',array('username'=>$data['challenger'],'notification'=>$msg));
        return true;
	}
	

	public function accept($data)
    	{
		if(!Session::exists(Config::get('session/session_name')))
        {
            return false;
		}
		
		$id = $data['id'];
		$verify = $this->_db->get('challengeNotification',array('id','=',$id));
        if($verify != false)
        {
            $verifiedData = $verify->results();
            if(empty($verifiedData)){
                return false;
            }
            else{
                if($verifiedData[0]->accepter != $this->_user)
                {
                    return false;
                }
            }
        }
        else{
            return false;
		}
		

        if($verifiedData[0]->status == '-1')                                                   //accepting for the first time
        {
            
			//get both challenger and accepter cc
			$ccdata = $this->_db->sql_015($verifiedData[0]->challenger,$verifiedData[0]->accepter);
			
			if($ccdata != false)
            {
                //check whether accepter has enough cc to accept
                if($ccdata['accepter']['cc'] < $verifiedData[0]->cc)
                {
                    return false;
                }

                //get random question set

                $ques_set = $this->getRandomQuestion();

                //get data (array) for challenge1
                $data_c2 = $this->InsertChallenge2($verifiedData[0],$ques_set);

                //update opponent code coins

				$cc_accepter_update = $ccdata['accepter']['cc'] - $verifiedData[0]->cc;
				// print_r($data_c2);
                

                //process challenge acceptance




                if($this->_db->sql_017($verifiedData[0]->challenger,$verifiedData[0]->accepter,$verifiedData[0]->cc,$id,$ccdata['challenger']['cc'],$cc_accepter_update,$data_c2))
                {
                    Session::put(Config::get('session/user_cc'), $cc_accepter_update);
                                    //email notification send process for challenge acceptance
                    $obj = new ChallengeNotification($this->_user);
                    $obj->SendEmailNotification_accepted($verifiedData[0]->challenger, $verifiedData[0]->challengeName, $verifiedData[0]->cc);
                    return true;
                }
                else
                {
                    return false;
                }

            }
            else
            {
                return false;
            }
        }
        elseif($data['status'] == '1')                                                //challenge is running
        {
			return true;
        }
	}
	
	 private function updateNotification($id,$fields)
    {
        $this->_db->updateNotification('challengeNotification',$id,$fields);
	}
	

	private function InsertChallenge2($data,$common_questions)
    	{
        $id = $data->id;
        $challenger = $data->challenger;
        $opponent = $data->accepter;
        $cc_bet = (int)$data->cc;
        $opponent_end_time = date('Y-m-d H:i:s',strtotime(date("Y-m-d H:i:s"))+1800);                   //half an hour

        return array(
            $id,
            $challenger,
            $opponent,
			$cc_bet,
			date('Y-m-d H:i:s', time()),
			$opponent_end_time,
			json_encode($common_questions)
		);
	}


		/**
	 * function to set random question
	 * 
	 */

	public function getRandomQuestion()
	{
		$question_array = $this->_db->sql_004('challenge2_que', 3);
		$id = [];
		$i = 1;
        foreach($question_array as $key)
        {
			$id[$i] = $key;
			$i++;
        }
		return $id;
	}
	
	
	public function setQuession($challenger, $accepter)                                       //function to set a question set for this challenge
    {
        //$json = array(1,2);
        //$json = json_encode($json);
        //$this->_db->update('user_attempted_ques','ayush123',array('challenge1'=>$json));
        $attempted_ques = [];
        $challenger_ques_data = $this->_db->get('user_attempted_ques',array('username','=',$challenger))->results();
        $accepter_ques_data = $this->_db->get('user_attempted_ques',array('username','=',$accepter))->results();

        if(!empty($challenger_ques_data[0]->challenge2)){
            $attempted_ques[] = array_unique(json_decode($challenger_ques_data[0]->challenge1));
        }
        if(!empty($accepter_ques_data[0]->challenge2)){
            $attempted_ques[] = array_unique(json_decode($accepter_ques_data[0]->challenge1));
        }


        if(empty($attempted_ques)){
            $this->add_ques_set($this->_db->getQues('challenge2_que', 3));
        }
        elseif(count($attempted_ques) == 2)
        {
            $attempted_ques = array_unique(array_merge($attempted_ques[0],$attempted_ques[1]));
            $this->add_ques_set($this->_db->getQues('challenge2_que', 3,$attempted_ques));
        }
        else {
            $this->add_ques_set($this->_db->getQues('challenge2_que', 3,$attempted_ques[0]));
        }
        
       // print_r("hello");
       // print_r($attempted_ques);
        
        

	}
	

	private function add_ques_set($ques)
    {
        // print_r($ques);
		$id = [];
		$i = 1;
        foreach($ques as $key)
        {
			$id[$i] = $key->ques_id;
			$i++;
        }
        $id = json_encode($id);
        $this->_db->updateNotification('challenge2',$this->_challengeId,array('common_questions'=>$id));  
	}
	


	/* .............funtion to check for challenge status...............................*/ 

	public function checkChallengeStatus()
	{
		$data = $this->_result[0];//$this->_db->get('challenge2',array('id','=',$this->_challengeId));
		if($data)
		{
			if(!empty($data)){
				// print_r($data);

				if(Session::exists(Config::get('session/session_name'))){
					$user = Session::get(Config::get('session/session_name'));
				}
				else{
					return false;
				}

				if($user == $data->challenger)
				{
					if(empty($data->challenger_end_time) && $this->checkTime(date("Y-m-d H:i:s" ,(strtotime($data->date_time) + 86400))) )
					{
						//challenger did not completed his/her challenger so make winner
						$this->setwinner();
						return false;
					}
					elseif(!empty($data->challenger_end_time)  && $this->checkTime($data->challenger_end_time)){
						$this->_db->updateNotification('challengeNotification',$this->_challengeId,array('c_status'=>"2"));
						$this->setwinner();
						return false;
					}
					elseif(!empty($data->challenger_end_time) && !($this->checkTime($data->challenger_end_time))){
						return true;
					}
					elseif(empty($data->challenger_end_time) && !($this->checkTime(date("Y-m-d H:i:s" ,(strtotime($data->date_time) + 86400))))){
						return true;
					}
				}
				elseif($user == $data->opponent)
				{
					if(empty($data->opponent_end_time) && $this->checkTime(date("Y-m-d H:i:s" ,(strtotime($data->date_time) + 86400))) )
					{
						//challenger did not completed his/her challenger so make winner
						$this->setwinner();
						return false;
					}
					elseif(!empty($data->opponent_end_time)  && $this->checkTime($data->opponent_end_time)){
						$this->_db->updateNotification('challengeNotification',$this->_challengeId,array('a_status'=>"2"));
						$this->setwinner();
						// echo "hello";
						return false;
					}
					elseif(!empty($data->opponent_end_time)  && !($this->checkTime($data->opponent_end_time))){
						return true;
					}
					elseif(empty($data->opponent_end_time) && !($this->checkTime(date("Y-m-d H:i:s" ,(strtotime($data->date_time) + 86400))))){
						
						return true;
					}
				}
				else{
				
					return false;
				}

			}
			else{
			return false;
			}
			
		}
		
	}

	private function checkTime($time)                                               //CHallenge time over
	{
		if(strtotime(date("Y-m-d H:i:s", time())) > strtotime($time))
		{
			return true;
		}
		else{
			return false;
		}
	}


	public function setwinner()
	{
		$data 			= 	$this->_result[0];
		$challenger 	= 	$data->challenger;
		$accepter 		= 	$data->opponent;
		$c_e_t 			= 	$data->challenger_end_time;
		$a_e_t 			= 	$data->opponent_end_time;
		$cc 			= 	$data->cc_bet;

		if(!empty($data->challenger_ques_solved) && $data->challenger_ques_solved != "null")
		{
			$c_ques = count(json_decode($data->challenger_ques_solved, TRUE));
		}
		else{
			$c_ques = 0;
		}


		if(!empty($data->opponent_ques_solved) && $data->opponent_ques_solved != null)
		{
			$a_ques = count(json_decode($data->opponent_ques_solved , TRUE));
		}
		else{
			$a_ques = 0;
		}


		/**
		 * 	FIRST CHECK FOR IF THE CHALLENGER HAS NOT STARTED THE CHALLENGE 
		 * 	AND THE CHALLENGE TIME HAS COMPLETED
		 */

		if($c_e_t == '' && $this->checkTime(date("Y-m-d H:i:s" ,(strtotime($data->date_time) + 86400))) )
		{
			$this->makeWinnerChanges(1, $accepter, $challenger, $cc);
			return true;
		}

		if($c_e_t != ''  && $a_e_t != '')
		{
			if($this->checkTime($c_e_t) && $this->checkTime($a_e_t))
			{
				//process winner
				if($c_ques == 0 && $a_ques == 0)
				{
					/**
					 *  take our commision and draw the challenge
					 */
					$this->makeWinnerChanges(0, $challenger, $accepter, $cc);
					return true;
				}
				elseif ($c_ques > $a_ques) {
					$this->makeWinnerChanges(1, $challenger, $accepter, $cc);
					return true;
		
				}
				elseif ($c_ques < $a_ques) {
					# code...
					$this->makeWinnerChanges(1, $accepter, $challenger, $cc);
					return true;
				
				}
				elseif($c_ques == $a_ques){
					$this->makeWinnerChanges(0, $challenger, $accepter, $cc);
					return true;
					
				}
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

	private function makeWinnerChanges($status,$winner,$loser, $cc)
	{
		// match draw
		
		if($status == 0)
		{
			/**
			 * Withdraw process
			 */
			$cc_to_return = CodeCoins::setChallengeDrawCodeCoins($cc);                                   //REFER CODECOIN CLASS TO KNOW MORE
			$ccdata = $this->_db->sql_015($winner,$loser);

			if($ccdata != false)
			{
				$this->_db->sql_018($winner,$loser,$cc_to_return,$this->_challengeId,'c2',$ccdata['challenger']['cc'],$ccdata['accepter']['cc']);
			}
		}
		elseif($status == 1)
		{
			/**
			 * NOTE : USER TRANSACTION HERE
			 */
			$winnCC = CodeCoins::setWinningCodeCoins($cc); 												//REFER CODECOIN CLASS TO KNOW MORE

			$this->_db->updateNotification('challenge2',$this->_challengeId,array('winner'=>$winner, 'cc_won'=>$winnCC));                           
			$this->_db->delete('challengeNotification',array('id','=',$this->_challengeId));
			$msg1 = "You Have Won The Challenge With ".$loser.". Check Challenge Status To Redeem CC";
        	$msg2 = "You Have Lost The Challenge With ".$winner."Check Challenge Status...";
        	$this->_db->insert('generalNotifications',array('username'=>$winner,'notification'=>$msg1));
			$this->_db->insert('generalNotifications',array('username'=>$loser,'notification'=>$msg2));
			

			/*.............updating points .................... */
			$winnerStats    =   $this->_db->get('ranking',array('username','=',$winner))->results();
			$winnerPoints   =   $winnerStats[0]->point;
			$winnerRD       =   $winnerStats[0]->rd;
			$winnerLeague   =   $winnerStats[0]->league;

			$loserStats     =   $this->_db->get('ranking',array('username','=',$loser))->results();
			$loserPoints    =   $loserStats[0]->point;
			$loserRD        =   $loserStats[0]->rd;
			$loserLeague    =   $loserStats[0]->league;

			$winner_obj     =   new Glicko2Player($winnerPoints, $winnerRD);
			$loser_obj      =   new Glicko2Player($loserPoints, $loserRD);

			$winner_obj->AddWin($loser_obj);
			$loser_obj->AddLoss($winner_obj);

			$winner_new_rating  =   $winner_obj->update();
			$loser_new_rating   =   $loser_obj->update();

			$winner_new_rating['league']      =   StaticFunc::League($winner_new_rating['point']);
			$loser_new_rating['league']       =   StaticFunc::League($loser_new_rating['point']);

			/* ..............optimise this for single query.................. */
			$this->_db->update('ranking',$winner,$winner_new_rating);
			$this->_db->update('ranking',$loser, $loser_new_rating);
			}
	}




	public function setChallengerStartTime()
	{
		$data = $this->_result[0];
		$id = $this->_challengeId;
		$c_e_t = $data->challenger_end_time;
		if(empty($c_e_t) && !($this->checkTime(date('Y-m-d H:i:s'), strtotime($data->date_time)+86400)))
		{
			if($this->_db->updateNotification('challenge2',$id,array('challenger_start_time'=>date("Y-m-d H:i:s", time()), 'challenger_end_time'=>date("Y-m-d H:i:s", time()+1800))))
			{
				 	$this->_db->updateNotification('challengeNotification',$id,array('c_status'=>1));
            		return true;
			}
			return true;
		
		
		}
		elseif(!empty($c_e_t) && !$this->checkTime($c_e_t) )
		{
			return true;
		}
		else{
			return false;
		}
	}


	public function getSolvedQuestions(){
					$solvedSend = array();
					$questionSet = json_decode($this->_result[0]->common_questions, true);
					$this->checkChallenger();
					if($this->_typeOfUser == "challenger"){
						$solved = json_decode($this->_result[0]->challenger_ques_solved, true);
					}
					else{
						$solved = json_decode($this->_result[0]->opponent_ques_solved, true);
					}
					if(empty($solved)){
						return array();
					}
					foreach($solved as $quesId => $testCases){
						$a = $this->_db->get("challenge2_que", array("ques_id", "=",  $quesId))->results()[0];
						$point = json_decode($a->points_distribution, true);
						$testCaseCount = count($point);
						if($testCaseCount == count($testCases)){
							$quesNum = array_search($quesId, $questionSet);
							array_push($solvedSend, $quesNum);
						}
					}
					return $solvedSend;
				}





}



























?>
