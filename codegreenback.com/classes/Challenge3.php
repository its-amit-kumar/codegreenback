<?php



require_once dirname(__DIR__).'/core/init.php';

class Challenge3
{
	private $_user,
	$_challengeId,
	$_db,
	$_result;
	
	function __construct($challengeId,$user)
	{
		$this->_user = $user;
		$this->_challengeId = $challengeId;
		$this->_db = DB::getInstance();
	}

	////Checks if the question submitted for evalutaion has already been solved

	public function isSolved($questionId){
		$this->_result = $this->_db->get('challenge3',array('id','=',$this->_challengeId))->results()[0];

		$result1 = array();
		$result2 = array();

		
		if($this->_result->challenger_ques_solved != null)
		{
			$result1 = json_decode($this->_result->challenger_ques_solved)->questions;
		}

		if($this->_result->opponent_ques_solved != null)
		{
			$result2 = json_decode($this->_result->opponent_ques_solved)->questions;
		}
		
		$common = array_unique(array_merge($result1,$result2));

		foreach ($common as $key => $value) {
			if ($questionId==$value) {
				return true;
			}
		}

		return false;

		
	}

	////checks if the user is the challenger or opponent

	public function isChallenger(){
		if ($this->_user == $this->_result->challenger){
			return true;
		}
		else{
			return false;
		}
	}

	////calls the above 2 functions and on confirmation inserts the question to solved question

	public function insertSolvedQuestion($questionId){


		$quesSolvedArray = array();
		if($this->isChallenger()){
			
			if($this->_result->challenger_ques_solved != null)
			{
				$quesSolvedArray = json_decode($this->_result->challenger_ques_solved);
				array_push($quesSolvedArray->questions, $questionId);
			}
			else
			{
				$quesSolvedArray['questions'] = array($questionId);
			}

			
			$this->_db->updateNotification("challenge3",$this->_challengeId,array('challenger_ques_solved' => json_encode($quesSolvedArray)));
		}
		else{
			

			if($this->_result->opponent_ques_solved != null)
			{
				$quesSolvedArray = json_decode($this->_result->opponent_ques_solved);
				array_push($quesSolvedArray->questions, $questionId);
			}
			else
			{
				$quesSolvedArray['questions'] = array($questionId);
			}

			$this->_db->updateNotification("challenge3",$this->_challengeId,array('opponent_ques_solved' => json_encode($quesSolvedArray)));
		}


	}

	/////checks if the challenge has been completed or not based on weather all questions have been solved

	public function isComplete(){



		$this->_result = $this->_db->get('challenge3',array('id','=',$this->_challengeId));
		if($this->_result != false)
		{
			$this->_result = $this->_result->results()[0];

			/**
			 * get challenger question solved
			 */

			
		}

		
		$result1 = array();
		$result2 = array();

		if($this->_result->challenger_ques_solved != null)
		{
			$result1 = json_decode($this->_result->challenger_ques_solved)->questions;
		}

		if($this->_result->opponent_ques_solved != null)
		{
			$result2 = json_decode($this->_result->opponent_ques_solved)->questions;
		}

		
		$common = array_unique(array_merge($result1,$result2));

		$questions = json_decode($this->_result->common_questions);
		$question = array();
		foreach ($questions as $key => $value) {
				array_push($question, $value);
			}

		if(array_diff($question, $common)==[]){
			return true;
		}
		else{
			return false;
		}

	}

	public function getTime(){
		$time = $this->_db->get('challenge3',array('id','=',$this->_challengeId))->results()[0]->end_time;
		return $time;
	}



	/**
	 * Funtion to get questions which are not solved
	 * 
	 *
	 */

	public function getQuestions()
	{
		$data = $this->_db->get('challenge3',array('id', '=', $this->_challengeId));
		if($data != false)
		{
			$data = $data->results()[0];

			/**
			 * check if winner has been set
			 */

			if($data->winner == null)
			{
				/**
				 * check the default end time of the challenge
				 * if current time > than the default end time of the challenge
				 */

				if(time() > strtotime($data->end_time, time()))
				{
					/**
					 * declare winner 
					 */
				}
				else
				{
					/**
					 * check solved questions by both the players
					 */
					$challenger_ques_solved = $data->challenger_ques_solved;
					if($challenger_ques_solved != null)
					{
						$challenger_ques_solved = json_decode($challenger_ques_solved);
					}

					$opponent_ques_solved = $data->opponent_ques_solved;

					if($opponent_ques_solved != null)
					{
						$opponent_ques_solved = json_decode($opponent_ques_solved);
					}


				}
			}
			else
			{
				return false;
			}
		}
	}



	/**
	 * function to check user can continue to challenge or not
	 */

	public function check()
	{
		$data = $this->_db->get('challenge3', array('id','=', $this->_challengeId));
		if($data!= false)
		{
			if(!empty($data = $data->results()))
			{
				$data = $data[0];

				if($data->challenger == $this->_user || $data->opponent == $this->_user)
				{
			
					if($data->winner != null)
					{
						return array("status" => false);
					
					}

					if($data->challenger_ques_solved != null || $data->opponent_ques_solved != null)
					{
						return array('status' => false);
					}

					if(time() > strtotime($data->end_time, time()))
					{
						$this->setDraw();
						return array('status' => false);
					}

					/**
					 * process to send the appropraite quetion and time to start
					 */

					$start_time = strtotime($data->start_time, time()) + 10;
					$challenge_obj = array(
						'status'   	 	=> 		true,
						'start_time'	=> 		$start_time,	
					);

					return $challenge_obj;
				}

			}
		}

		return array("status" => false);
	}


	/**
	 * funtion to send question details
	 */

	public function sendData()
	{
		$data = $this->_db->get('challenge3', array('id','=', $this->_challengeId));
		if($data!= false)
		{
			if(!empty($data = $data->results()))
			{
				$data = $data[0];
				if($data->challenger == $this->_user || $data->opponent == $this->_user)
				{
					$allotted_que_id = json_decode($data->common_questions)->{1};
					$ques = new Ques('challenge3',$allotted_que_id);
					$quesSend = $ques->get_question();


					if($data->challenger == $this->_user)
					{
						$opponent = $data->opponent;
					}
					else
					{
						$opponent = $data->challenger;
					}

					$challenge_obj = array(
						'status'   	 	=> 		true,
						'problem' 	 	=>		$quesSend,
						'opponent'		=> 		$opponent,
						'username'		=>		$this->_user,
						'cc'			=>		$data->cc_bet
						
					);

					return json_encode($challenge_obj);
				}
			}

		}

		return json_encode(array('status' => false));

	}


	/**
	 * funtion to set winner when a user has completed the challenge
	 */

	public function setWinner()
	{
		/**
		 * write an sql to update a winner
		 */

		$data = $this->_db->get('challenge3', array('id','=',$this->_challengeId));
		if($data != false)
		{
			$data = $data->results();
			if(!empty($data))
			{
				$cc = $data[0]->cc_bet;
				$winCC = CodeCoins::setWinningCodeCoins($cc);

				$winner = $this->_user;
				if($data[0]->challenger == $this->_user)
				{
					$loser = $data[0]->opponent;
				}
				else
				{
					$loser = $data[0]->challenger;
				}


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

				if($this->_db->sql_006($this->_challengeId, $winner, $loser, $winCC, $winner_new_rating, $loser_new_rating))
				{

					return true;
				}
				else
				{
					return false;
				}

			
			}
		}

	}


	/**
	 * public function to check overall challenge status when the challenge notification time is over
	 * and then take necessary action like : set winner or draw challenge
	 */

	public function checkOverall()
	{
		$data = $this->_db->get('challenge3', array('id','=',$this->_challengeId));

		if($data != false)
		{
			$data = $data->results();
			if(!empty($data))
			{
				if($data[0]->winner == null)
				{
					if($data[0]->challenger_ques_solved == null && $data[0]->opponent_ques_solved == null)
					{
						/**
						 * process draw match
						 */

						$cc_to_return = CodeCoins::setChallengeDrawCodeCoins($data[0]->cc_bet);

						$ccdata = $this->_db->sql_015($data[0]->challenger,$data[0]->opponent);

						if($ccdata != false)
						{
							return $this->_db->sql_018($data[0]->challenger,$data[0]->opponent,$cc_to_return,$this->_challengeId,'c3',$ccdata['challenger']['cc'],$ccdata['accepter']['cc']);
						}
						
					}
				}
			}

		}
		return false;
	}



	
}

























?>
