<?php

/*         this class is for challenge1            */


require_once $_SERVER['DOCUMENT_ROOT'].'/core/init.php';

class Challenge1 
{
    private $_db,
            $_id,
            $_challenger,
            $_accepter;

    public function __construct($data)
    {
        $this->_db = DB::getInstance();
        $this->_id = $data['id'];
        $this->_challenger = $data['challenger'];
        $this->_accepter = $data['accepter'];
    }
    

    /*                   user declines the request function      
                             delete the notification and inform 
                                                the challenger                               */

    
    public function delete($data)
    {
        $id = $data['id'];
        $accepter = $data['accepter'];
        $challenger = new User($data['challenger']);
        $challengerCC = $challenger->cc();                                       //return object of cc table
        $cc = $data['cc'];                                                    //rollback cc to challenger
        $updatedCC = $challengerCC->cc + $cc;
        $challenger->update_user_cc($updatedCC,$data['challenger']);

        $this->_db->delete('challengeNotification',array('id','=',$id));

        $msg = $data['accepter']." has declined Your Challenge ".$data['challengeName'];
        $this->_db->insert('generalNotifications',array('username'=>$data['challenger'],'notification'=>$msg));
        return true;
    }


    /*    user accepts the challenge request : 
              1. change notification status  and time   
              2. update challenge1 table with all the field  
              3. Fetch questions based on the challenger settings   
              4. Redirect the user to the editor page                         */

              

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
                if($verifiedData[0]->accepter != $this->_accepter)
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
                $data_c1 = $this->InsertChallenge1($data,$ques_set);

                //update accepter code coins

                $cc_accepter_update = $ccdata['accepter']['cc'] - $verifiedData[0]->cc;
                Session::put(Config::get('session/user_cc'), $cc_accepter_update);

                //process challenge acceptance




                if($this->_db->sql_014($verifiedData[0]->challenger,$verifiedData[0]->accepter,$verifiedData[0]->cc,$id,$ccdata['challenger']['cc'],$cc_accepter_update,$data_c1))
                {
                                    //email notification send process for challenge acceptance
                    $obj = new ChallengeNotification($this->_accepter);
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

    private function InsertChallenge1($data,$ques_set)
    {
        $id = $data['id'];
        $challenger = $data['challenger'];
        $accepter = $data['accepter'];
        $cc = $data['cc'];
        $default_endtime_accepter = date('Y-m-d H:i:s' , strtotime(date("Y-m-d H:i:s"))+86400);
        $default_endtime_challenger = date('Y-m-d H:i:s',strtotime(date("Y-m-d H:i:s"))+86400);
        $accepter_start_time = date('Y-m-d H-i-s');

        return array(
            $id,
            $challenger,
            $accepter,
            $cc,
            $default_endtime_accepter,
            $accepter_start_time,
            $default_endtime_challenger,
            $ques_set
        );

    }

 
    /**
     * setQuession()
     * sets unique quetion for both challenger and accepter
     */


    public function setQuession()                                       //function to set a question set for this challenge
    {
        //$json = array(1,2);
        //$json = json_encode($json);
        //$this->_db->update('user_attempted_ques','ayush123',array('challenge1'=>$json));
        $attempted_ques = [];
        $challenger_ques_data = $this->_db->get('user_attempted_ques',array('username','=',$this->_challenger))->results();
        $accepter_ques_data = $this->_db->get('user_attempted_ques',array('username','=',$this->_accepter))->results();

        if(!empty($challenger_ques_data[0]->challenge1)){
            $attempted_ques[] = array_unique(json_decode($challenger_ques_data[0]->challenge1));
        }
        if(!empty($accepter_ques_data[0]->challenge1)){
            $attempted_ques[] = array_unique(json_decode($accepter_ques_data[0]->challenge1));
        }


        if(empty($attempted_ques)){
            $this->add_ques_set($this->_db->getQues('challenge1_que', 2));
        }
        elseif(count($attempted_ques) == 2)
        {
            $attempted_ques = array_unique(array_merge($attempted_ques[0],$attempted_ques[1]));
            $this->add_ques_set($this->_db->getQues('challenge1_que', 2,$attempted_ques));
        }
        else {
            $this->add_ques_set($this->_db->getQues('challenge1_que',2,$attempted_ques[0]));
        }
    }


    /**
     *  setRandomQuestion sets question at random
     */
    public function getRandomQuestion()
    {
        $ques = $this->_db->sql_004('challenge1_que',1);
        return json_encode($ques);
    }

    private function add_ques_set($ques)
    {
       // print_r($ques);
        $id = [];
        foreach($ques as $key)
        {
            $id[] = $key->ques_id;
        }
        $id = json_encode($id);
        $this->_db->updateNotification('challenge1',$this->_id,array('ques_set'=>$id));  
    }


/*    ....................................Static Helper functions for challenge1 ...................................*/



     /*  this function is to update the question completed or skipped
           in the challenge so that next question can be given         */

           public static function update_user_que($id,$flag)                                         //function to update challenge1 attempted question by the user
           {
               $solved_tag = array();
               $user = Session::get('user');
               $db = DB::getInstance();
               $data = $db->get('challenge1',array('id','=',$id))->results();
       
               if($user == $data[0]->challenger)
               {
                   if($data[0]->challenger_ques != NULL)
                   {
                       $solved_tag = json_decode($data[0]->challenger_ques);
                   }
                   
                   $solved_tag[] = $flag;
                   $solved_tag = json_encode($solved_tag);
       
                   if($db->updateNotification('challenge1',$id,array('challenger_ques'=>$solved_tag))){
                       return true;
                   }
               }
       
               elseif($user == $data[0]->accepter)
               {
                   if($data[0]->accepter_ques != NULL)
                   {
                       $solved_tag = json_decode($data[0]->accepter_ques);
                   }
                   
                   $solved_tag[] = $flag;
                   $solved_tag = json_encode($solved_tag);
                  // print_r($solved_tag);
                  if($db->updateNotification('challenge1',$id,array('accepter_ques'=>$solved_tag))){
                      return true;
                  }
               
               
               }
               
           }
           


        public static function challengecompleted($id)                                         //function to update challenge1 attempted question by the user
        {
                
                $user = Session::get('user');
                $db = DB::getInstance();
                $data = $db->get('challenge1',array('id','=',$id))->results();
                if(!empty($data))
                {
                    if($user == $data[0]->challenger)
                    {
                        /**
                         * set challenger_que to 1
                         * update c_status to 2 i.e. completed
                         * update user attempted question
                         */

                        $ques = json_decode($data[0]->ques_set,true);
                        if($db->sql_013($user,$id,1,$ques[0]))
                        {
                            return true;
                        }
                        else{
                            return false;
                        }

                    }
                    elseif ($user == $data[0]->accepter) {
                        /**
                         * set challenger_que to 1
                         * update c_status to 2 i.e. completed
                         * update user attempted question
                         */
                        $ques = json_decode($data[0]->ques_set,true);
                        if($db->sql_013($user,$id,0,$ques[0]))
                        {
                            return true;
                        }
                        else{
                            return false;
                        }
                    }
                    Challenge1::getWinner($id);
                }
            }



           public static function check_challenge_1_id($id)  
           {
               $response = array();
               $db = DB::getInstance();
               $user = Session::get('user');
       
               $challengeData = $db->get('challenge1',array('id','=',$id))->results();
               if(!empty($challengeData))
               {
                 //  print_r($challengeData);
                    $response['cc'] = $challengeData[0]->cc;
                    $response['challenger'] = $challengeData[0]->challenger;
                    $response['opponent'] = $challengeData[0]->accepter;

                   if($user == $challengeData[0]->challenger)
                   {
                        if($challengeData[0]->challenger_ques == 1)
                        {
                        
                            $response['status'] = 0;
                            $response['msg'] = 'Completed the question';
                            return $response;
                            
                        }

                       if(($challengeData[0]->challenger_end_time) == NULL && Challenge1::checktime($challengeData[0]->default_endtime_challenger))
                       {
                           $response['status'] = 1;
                           $response['ques'] = json_decode($challengeData[0]->ques_set);
                           $response['endtime'] = $challengeData[0]->default_endtime_challenger;
                           return $response;
                       }
                   }
                   elseif($user == $challengeData[0]->accepter)
                   {
                        if($challengeData[0]->accepter_ques == 1)
                        {
                            $response['status'] = 0;
                            $response['msg'] = 'Completed all questions';
                            return $response;
                            
                        }
                       if(($challengeData[0]->accepter_end_time) == NULL && Challenge1::checktime($challengeData[0]->default_endtime_accepter))
                       {
                            $response['status'] = 1;
                            $response['ques'] = json_decode($challengeData[0]->ques_set);
                            $response['endtime'] = $challengeData[0]->default_endtime_accepter;
                            return $response;
                       }
                       
                   }
                   else
                   {
                        $response['status'] = 0;
                        $response['msg'] = 'Not found';
                        return $response;
                   }
               }
               else
               {
                    $response['status'] = 0;
                    $response['msg'] = 'Not found';
                    return $response;
               }
           }
       
           public static function checktime($time)                                //$time is greater than current time => return 1
           {
               if(strtotime(date("Y-m-d H:i:s" , time())) >= strtotime($time))
               {
                   return false;
               }
               else{
                   return true;
               }
           }




    public static function FetchQues($id)
    {   
        $db = DB::getInstance();
        $user = Session::get('user');
        $quesid = '';
        $quesData = '';
        $ques = array();

        $challengeData = $db->get('challenge1',array('id','=',$id))->results();
        //print_r($challengeData);
        if($user == $challengeData[0]->challenger || $user == $challengeData[0]->accepter)
        {

         ///  echo $len;
            $quesid = (json_decode($challengeData[0]->ques_set))[0];
            
            if($quesData = $db->get('challenge1_que',array('ques_id','=',$quesid))->results())
            {
                $ques['id'] = $quesData[0]->ques_id;

                $quesData = json_decode($quesData[0]->data,true);

                $ques['ques']['constrain'] = $quesData['constrain'];
                $ques['ques']['inputformat'] = $quesData['inputformat'];
                $ques['ques']['outputformat'] = $quesData['outputformat'];
                $ques['ques']['problem'] = $quesData['problem'];
                $ques['ques']['sampleinput'] = $quesData['public_testcase'][0]['inputgiven'];
                $ques['ques']['sampleoutput'] = $quesData['public_testcase'][0]['expectedOutput'];
                return(json_encode($ques));
                

            }
            else
            {
                return -1;
            }
        }
        else
        {
            return 0;
        }
    }


    public static function setChallengerStartTime($id,$challengeTable)                                       
    {
        $db = DB::getInstance();
        $user = Session::get('user');
       
        $challengeData = $db->get('challenge1',array('id','=',$id))->results();
        if($challengeData)
            {
                 //  print_r($challengeData);
                if($user == $challengeData[0]->challenger)
                   {
                        if($challengeData[0]->challenger_ques != 0)
                        {
                            
                            return 0;
                            
                        }

                        if(($challengeData[0]->challenger_start_time) == NULL && Challenge1::checktime($challengeData[0]->default_endtime_challenger))
                        {
                            if($db->updateNotification($challengeTable,$id,array('challenger_start_time'=>date("Y-m-d H:i:s"))))
                            {
                                $db->updateNotification('challengeNotification',$id,array('c_status'=>1));
                                return true;
                            }
                            else{
                                return false;
                            }
                        }
                        elseif($challengeData[0]->challenger_start_time != NULL && Challenge1::checktime($challengeData[0]->default_endtime_challenger))
                        {
                            return true;
                        }
                   }
            }
        return false;
    }







    public static function endChallenge($id)
    {
        $db = DB::getInstance();
        $user = Session::get('user');
        $challengeData = $db->get('challenge1',array('id','=',$id))->results();

        //  .................. USE TRANSACTION  ..................................//
        if($user == $challengeData[0]->challenger)
        {
            $db->updateNotification('challenge1',$id,array('challenger_end_time'=>date("Y-m-d H:i:s",time())));
            $db->updateNotification('challengeNotification',$id,array('c_status'=>2));
        }
        elseif($user == $challengeData[0]->accepter)
        {
            $db->updateNotification('challenge1',$id,array('accepter_end_time'=>date("Y-m-d H:i:s",time())));
            $db->updateNotification('challengeNotification',$id,array('a_status'=>2));
        }

        Challenge1::getWinner($id);

    }

   

/*   static functions for Finding the winner of challenge 1             */
    public static function getWinner($id)
    {
    
        $db = DB::getInstance();
        $data = $db->get('challenge1',array('id','=',$id));
        if($data)
        {
            $data = ($data->results())[0];
            if(empty($data->winner))
            {
                $challenger     =   $data->challenger;
                $accepter       =   $data->accepter;
                $cc             =   $data->cc;
                $c_s_t          =   $data->challenger_start_time;
                $a_s_t          =   $data->accepter_start_time;
                $c_e_t          =   $data->challenger_end_time;
                $a_e_t          =   $data->accepter_end_time;
                $d_e_c          =   $data->default_endtime_challenger;
                $d_e_a          =   $data->default_endtime_accepter;
                $a_ques         =   $data->accepter_ques;
                $c_ques         =   $data->challenger_ques;
                // echo $c_ques;
                
                if($c_s_t == null && Challenge1::checktime($d_e_c))
                {
                    // this means that challenger didnt started the challenge but it can challenge till d_e_c
                    
                }

                if($c_e_t == null && !(Challenge1::checktime($d_e_c)))
                {
                    //  set challenger end time
                    $db->updateNotification('challenge1',$id,array('challenger_end_time'=>$d_e_c));
                }
                
                if($a_e_t == null &&  !(Challenge1::checktime($d_e_a)))
                {
                    $db->updateNotification('challenge1',$id,array('accepter_end_time'=>$d_e_a));
                }

                if($a_e_t != null && $c_e_t != null)
                {
                    if($a_ques ==  $c_ques )                   // 0 for bot or 1 for both
                    {
                        //check time difference
                        $a_time = strtotime($a_e_t) - strtotime($a_s_t);
                        $c_time = strtotime($c_e_t) - strtotime($c_s_t);

                        if($a_time > $c_time)
                        {
                            //challenger wins
                            Challenge1::setWinner($challenger,$accepter,$id, $cc);
                            return true;
                            
                        }
                        else
                        {
                            //accepter wins
                            Challenge1::setWinner($accepter,$challenger,$id, $cc);
                            return true;
                        }
                    }
                    elseif($a_ques == 1 &&  $c_ques == 0)
                    {
                        //accepter is the winner
                        Challenge1::setWinner($accepter,$challenger,$id, $cc);
                        return true;
                    }
                    elseif($a_ques == 0 &&  $c_ques == 1){
                        //challenger wins is the winner
                        Challenge1::setWinner($challenger,$accepter,$id, $cc);
                        return true;
                    }
                    elseif($c_e_t != 0 && $c_s_t == null){
                        Challenge1::setWinner($accepter,$challenger,$id, $cc);
                        return true;
                    }
                    else
                    {
                        //challenger is the winner
                        Challenge1::setWinner($challenger,$accepter,$id, $cc);
                        return true;
                    }
                }
                return false;
            }
            return false;
          
           
        }
        return false;

    }


    public static function setWinner($winner,$loser,$id, $cc)
    {
        $db = DB::getInstance();
        $winnCC = CodeCoins::setWinningCodeCoins($cc);
        $db->updateNotification('challenge1',$id,array('winner'=>$winner, 'cc_won'=>$winnCC));
        $msg1 = "You Have Won The Challenge With ".$loser.". Check Challenge Status To Redeem CC";
        $msg2 = "You Have Lost The Challenge With ".$winner."Check Challenge Status...";
        $db->insert('generalNotifications',array('username'=>$winner,'notification'=>$msg1));
        $db->insert('generalNotifications',array('username'=>$loser,'notification'=>$msg2));
        $db->delete('challengeNotification',array('id','=',$id));


        $winnerStats    =   $db->get('ranking',array('username','=',$winner))->results();
        $winnerPoints   =   $winnerStats[0]->point;
        $winnerRD       =   $winnerStats[0]->rd;
        $winnerLeague   =   $winnerStats[0]->league;

        $loserStats     =   $db->get('ranking',array('username','=',$loser))->results();
        $loserPoints    =    $loserStats[0]->point;
        $loserRD        =    $loserStats[0]->rd;
        $loserLeague    =    $loserStats[0]->league;

        $winner_obj     =   new Glicko2Player($winnerPoints, $winnerRD);
        $loser_obj      =   new Glicko2Player($loserPoints, $loserRD);

        $winner_obj->AddWin($loser_obj);
        $loser_obj->AddLoss($winner_obj);

        $winner_new_rating  =   $winner_obj->update();
        $loser_new_rating   =   $loser_obj->update();

        $winner_new_rating['league']      =   StaticFunc::League($winner_new_rating['point']);
        $loser_new_rating['league']       =   StaticFunc::League($loser_new_rating['point']);

        /* ..............optimise this for single query.................. */
        $db->update('ranking',$winner,$winner_new_rating);
        $db->update('ranking',$loser, $loser_new_rating);


    }

    
    public static function getChallengeTimeDetails($id){
        $db = DB::getInstance();
        $user = Session::get('user');
        $challengeData = $db->get('challenge1',array('id','=',$id))->results();

        if($user == $challengeData[0]->challenger)
        {
            $data = array('startTime'=> $challengeData[0]->challenger_start_time , 'd_e_t'=>$challengeData[0]->default_endtime_challenger);
            return json_encode($data);
        }
        elseif($user == $challengeData[0]->accepter)
        {
            $data = array('startTime'=> $challengeData[0]->accepter_start_time , 'd_e_t'=>$challengeData[0]->default_endtime_accepter);
            return json_encode($data);
        }
        else
        {
            return 0;
        }
    }

   

  
    
/*     ........................static functions for Code runnning and Test case Matching............................*/

    public static function runCode($source)
    {
        $id = $source['id'];
        $quesid = $source['quesid'][0];
        $code = $source['code'];
        $lang = $source['lang'];
        $filename = Challenge1::getFileName($lang);
        if(isset($source['custominput']))
        {
            $custominput = $source['custominput'];
            $dataJSON = Challenge1::Makejson($lang,$code,$custominput,$filename);
            $output  = Challenge1::getCodeOutput($dataJSON);
            
            return Challenge1::makeCustomOutput($output,$custominput);
        }
        else
        {
            $flagpublic = false;

            $ques = Challenge1::getQuestion($quesid);
            if($ques == 0)
            {
                return 0;
            }

            $ques = json_decode($ques->data, true);

            $publictc = $ques['public_testcase'];                          //public test case data
            $privatetc = $ques['private_testcase'];                         //private test case data

            for ($i=0; $i < count($publictc); $i++)
            { 
                $data = array();
                $input = $publictc[$i]['inputgiven'];
                $expectedOutput = $publictc[$i]['expectedOutput'];
                $dataJSON = Challenge1::Makejson($lang,$code,$input,$filename);
                $output  = json_decode(Challenge1::getCodeOutput($dataJSON));
                $flag = Challenge1::checkTestCases($output,$expectedOutput);
                if($flag == -1)
                {
                    $data['error'] = $output->stderr;
                    return json_encode($data);
                }
                elseif($flag == 0)
                {
                    $data['input'] = $input;
                    $data['expectedOutput'] = $expectedOutput;
                    $data['output'] = $output->stdout;
                    return json_encode($data);
                }
            }

            for ($i=0; $i < count($privatetc); $i++)
            { 
                $data = array();
                $input = $privatetc[$i]['inputgiven'];
                $expectedOutput = $privatetc[$i]['expectedOutput'];
                $dataJSON = Challenge1::Makejson($lang,$code,$input,$filename);
                $output  = json_decode(Challenge1::getCodeOutput($dataJSON));
                $flag = Challenge1::checkTestCases($output,$expectedOutput);
                if($flag == -1)
                {
                    $data['error'] = $output->stderr;
                    return json_encode($data);
                }
                elseif($flag == 0)
                {
                    $data['input'] = "Private TestCase";
                    $data['expectedOutput'] = "Private";
                    $data['output'] = $output->stdout;
                    return json_encode($data);
                }
            }
          
            return 1;
        }
        
    
    }

    public static function getFileName($lang)
    {
        switch ($lang) {
            case 'python':
                return "main.py";
                break;
            
            case 'cpp':
                return "main.cpp";
            break;

            case 'java':
                return "main.java";
            break;
            case 'c':
                return "main.c";
            break;
	case 'ruby':
                return "main.rb";
            break;
	case 'javascript':
                return "main.js";
            break;
	case 'swift':
                return "main.swift";
            break;
	case 'bash':
                return "main.sh";
            break;
	case 'erlang':
                return "main.erl";
            break;
	case 'haskell':
                return "main.hs";
            break;

        }
    }

    public static function Makejson($lang,$code,$custominput,$filename)
    {
            //
            $dataJSON = array(
                "language" => $lang,
                "files" => [array(
                    "name"=>$filename,
                    "content"=>$code
                )],
                "stdin"=>$custominput,
                "command"=> ""
            );
            $data = json_encode($dataJSON);
            return $data;
    }

    public static function getCodeOutput($data)
    {
        $curl = curl_init();
        curl_setopt($curl , CURLOPT_URL , "http://13.233.153.141/APIbuilddocker.php");
        curl_setopt($curl , CURLOPT_POST,TRUE);
        curl_setopt($curl , CURLOPT_RETURNTRANSFER , TRUE);
        curl_setopt($curl , CURLOPT_POSTFIELDS , $data);

        $result = curl_exec($curl);
        curl_close($curl);
        return $result;
    }

    public static function makeCustomOutput($output,$custominput)               //making json data for displaying at the front end
    {
        $data = array();
        $output = json_decode($output);
        if($output->stderr != '')
        {
            $data['error'] = $output->stderr;
            return json_encode($data);
        }
        else
        {
            $data['input'] = $custominput;
            $data['output'] = chop($output->stdout);
            return json_encode($data); 
        }
        
    }


    public static function checkTestCases($output,$expectedOutput)
    {
        
        if($output->stderr != '')
        {
            return -1;
        }
        elseif(chop($output->stdout) == chop($expectedOutput))
        {
            return 1;
        }
        else{
            return 0;
        }

    }


    public static function getQuestion($quesid)
    {
        $db = DB::getInstance();
        $data = $db->get('challenge1_que',array("ques_id",'=',$quesid))->results();
        if($data)
        {
            return $data[0];
        }
        else{
            return 0;
        }
    }





/* Funtion for code compile in compile page  */

public static function codeCompile($source)
{
//
        
        $code = $source['code'];
        $lang = $source['lang'];
        $filename = Challenge1::getFileName($lang);
        if(isset($source['custominput']))
        {
            $custominput = $source['custominput'];
            $dataJSON = Challenge1::Makejson($lang,$code,$custominput,$filename);
            $output  = Challenge1::getCodeOutput($dataJSON);
            
            return Challenge1::makeCustomOutput($output,$custominput);
        }
        else
        {
            $dataJSON = Challenge1::Makejson($lang , $code , null , $filename);
            $output = Challenge1::getCodeOutput($dataJSON);
            return Challenge1::makeCustomOutput($output,'');
        }
    }
}


?>
