<?php

// database wrapper can be used anywhere
// using pdo php database object 

require_once dirname(__DIR__).'/ClassesTemp/Config.php';
require_once dirname(__DIR__).'/ClassesTemp/Hash.php';


class DB 
{
   
    private $_pdo,
            $_query,                                   //last query that is executed
            $_error=false,                             //for error 
            $_results,                                 //results from the querry
            $_count=0;                                 //count for the results
    

    public function __construct()                              //connection to database always
    {                            
        try{                                                       //try and catch for errors 

            $this->_pdo = new PDO('mysql:host='.Config::get('mysql/host').';dbname='.Config::get('mysql/db'),Config::get('mysql/username'),Config::get('mysql/password'));    //using PDO ;connection to db
            $this->_pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
        }catch(PDOException $e){                                       //pdo exception gives the error in $e through getMessage function 
            die($e->getMessage());
        }
    }

    public function close()
    {
        $this->_pdo = null;
    }
        
    public function query($sql,$params = array())                      //querry running function 
    {
        $this->_error = false;
        if($this->_query = $this->_pdo->prepare($sql)){                 //prepare the query and return true if success
            // print_r($this->_query);
            $x=1;
            if(count($params)){                                           //if parameters are present
                foreach($params as $param){
                    $this->_query->bindValue($x,$param);                      //binding the params
                    $x++;
                }
            }
            // print_r($this->_query);
            if($this->_query->execute()){                                               //executing the query successfully
            
                if(explode(" ",$sql)[0] == 'SELECT'){
                    $this->_results = $this->_query->fetchAll(PDO::FETCH_OBJ);       //FETCHING AND STORING RESULTS AS AN OBJECT
                    $this->_count = $this->_query->rowCount();                          //method of pdo to count the no. of rows
                }                                                                
                
                
            }
            else{
                $this->_error = true;
            }
        }
        return $this;                                                   //returns the object itself ni to error aata h bc
    }


    public function action($action,$table,$where=array()){
        if(count($where)===3){
            $operators = array('=','>','<','>=','<=');
            $field = $where[0];
            $operator = $where[1];
            $value = $where[2];
            if (in_array($operator,$operators)){
                $sql = "$action FROM `$table` WHERE $field $operator ?";
                if(!$this->query($sql,array($value))->error()){
                    return $this;
                }
            }
        }
        return false;
    }

    public function get($table,$where){                                //get data from the database
        return $this->action('SELECT *',$table,$where);

    }

    public function delete($table,$where){                          //delete function 
        return $this->action('DELETE',$table,$where);
        
    }

    public function insert($table,$fields){             //function to insert data into database
        $keys = array_keys($fields);
        $values = '';
        $x = 1;
        foreach($fields as $field){
            $values .= '?';
            if (count($fields)>$x){
                $values .= ', ';
            }
            $x++;
        }
        $sql = "INSERT INTO $table(`".implode('`, `',$keys)."`) VALUES ($values)";
        //echo $sql;
        if(!$this->query($sql,$fields)->error()){
            // echo " inserted ";
            return true;
        }
        //echo "not inserted";
        return false;
    }








    public function count(){                                //counts the no. of rows fetched
        return $this->_count;
    }

    public function results(){                           //return results from the querry run
        return $this->_results;
    }

 
    public function first(){                                //return the first results of the result array
        return ($this->results())[0];
    }


    public function error()                         //if error
    {
        return $this->_error;
    }






    public function getUserType($user)
    {
        $sql = "SELECT memberType FROM users WHERE username = ?";
        $this->query($sql , array("username"=>$user));
        if(!$this->error())
        {
            $data = $this->results();
            if(!empty($data))
            {
                if($data[0]->memberType == 1)
                {
                    return "elite";
                }
                else
                {
                    return "non-elite";
                }
            }
            else{
                return "";
            }
        
        }
        // return $this;
    }

     public function checkForRunningChallenge($user1,$user2)
    {
        $sql = 'SELECT * FROM challengeNotification WHERE (challenger = ? AND accepter = ?) OR (challenger = ? AND accepter = ?)';
        $query = $this->_pdo->prepare($sql);
        if($query->execute(array($user1,$user2,$user2,$user1)))
        {
            if($query->rowCount() == 1){
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






    /**
     * .........................sql naming convention is sql_XXX ........................................... 
     */


    /* 
        *   sql_001 : sql to get user codecoins, league, usertype  and total challenges alsos
    */

    public function sql_001($user)
    {
        $sql = "SELECT users.username as `username`, users.memberType as `user_type`, cc.cc as `cc`, ranking.league as `league`, user_challenge_stats.totalChallenges as `totalChallenges` FROM users, cc, ranking, user_challenge_stats WHERE users.username = cc.username AND cc.username = ranking.username AND ranking.username = user_challenge_stats.username AND users.username = ?";
        $this->query($sql , array('username'=>$user));
        if(!$this->error())
        {
            if(!empty($data = $this->results()[0]))
            {
                return $data;
            }
            return false;
        }
        return false;
    }


    /**
     * sql_002 : sql for making a challenge request
     * i.e. 1. insert a new challenge in challenge notification table
     *      2. Update challenge code coins
     */

    public function sql_002($id, $challenger, $accepter, $challenge_name, $cc_bet)
    {
        if(Session::exists(Config::get('session/session_name')))
        {
            if($challenger != Session::get(Config::get('session/session_name')))
            {
                return false;
            }
        }

        try {
            $this->_pdo->beginTransaction();

            $sql = "INSERT INTO `challengeNotification` (`id`, `challenger`, `accepter`, `challengeName`, `cc`) VALUES (?, ?, ?, ?, ?)";
            $query = $this->_pdo->prepare($sql);
            $query->execute(array($id, $challenger, $accepter, $challenge_name, $cc_bet));


            $sql = "UPDATE `cc` set `cc` = `cc` - ? WHERE username = ?";
            $query = $this->_pdo->prepare($sql);
            $query->execute(array((int)$cc_bet, $challenger));

            $this->_pdo->commit();
            
        }catch(PDOException $e){
            // print_r($e);
            $this->_pdo->rollBack();
            return false;
        }

        return true;

    }

    /**
     * sql_003 is only for challenge3 insertion
     * reuired params : challenger , accepter , cc
     * on success : it will return challengeid else return false
     * 
     */

    public function sql_003($challenger, $accepter, $cc)
    {
        if($this->checkForRunningChallenge($challenger,$accepter))
        {
            return false;
        }
        $id = 'c3_'.Hash::bit_32_unique();

        $ccdata = $this->sql_015($challenger,$accepter);
        if($ccdata == false)
        {
            return false;
        }

        try {
            $this->_pdo->beginTransaction();

            /**
             * deduct cc from challenger and accepter account
             */

            $sql = "UPDATE `cc` set `cc` = `cc` - ? WHERE username = ?";
            $query = $this->_pdo->prepare($sql);
            $query->execute(array((int)$cc, $challenger));

            $sql = "UPDATE `cc` set `cc` = `cc` - ? WHERE username = ?";
            $query = $this->_pdo->prepare($sql);
            $query->execute(array((int)$cc, $accepter));

            /**
             * insert challenge details in challenge Notification details
             * and put challenger and accepter status as 1
             */

            $sql = "INSERT INTO `challengeNotification` (`id`, `challenger`, `accepter`, `challengeName`, `cc`, `status`,`a_status`,`c_status`) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
            $query = $this->_pdo->prepare($sql);
            $query->execute(array($id, $challenger, $accepter, "Face/Off", $cc, 1, 1, 1));

            /**
             * prepare question set
             * insert data in challenge3 table 
             */

            $sql = "INSERT INTO `challenge3`(`id`, `challenger`, `opponent`, `cc_bet`, `common_questions`, `end_time`) VALUES (?, ?, ?, ?, ?, ?)";
            $question = $this->sql_004('challenge3_que',1);
            $count = 1;
            $common_que = [];
            foreach($question as $key) {
                $common_que[$count] = $key;
                $count = $count + 1;
            }
            $common_que = json_encode($common_que);
            $query = $this->_pdo->prepare($sql);
            $end_time =  date("Y-m-d H:i:s",time() + 86400);
            $query->execute(array($id, $challenger, $accepter, $cc, $common_que, $end_time));

		/**
             * update cc stats data for both users
             */

            //for challenger
            $arr1 = json_encode(array(
                'cc' => -$cc,
                'date' => date('Y-m-d H:i:s'),
                'balance' => $ccdata['challenger']['cc'],
                'referer' => $id .' accepted'
            ));

            //for accepter

            $arr2 = json_encode(array(
                'cc' => -$cc,
                'date' => date('Y-m-d H:i:s'),
                'balance' => $ccdata['accepter']['cc']-$cc,
                'referer' => $id . 'accepted'
            ));

            $sql = 'UPDATE `ccStats` SET `ccstats` = JSON_ARRAY_APPEND(ccstats, "$", CAST(? as JSON)) WHERE `username` = ?';
            $query  = $this->_pdo->prepare($sql);
            $query->execute(array($arr1, $challenger));
            $query->execute(array($arr2, $accepter));

            $this->_pdo->commit();

        } 
        catch (PDOException $e) {
            $this->_pdo->rollBack();
            return false;
        }
        return $id;
    }


    /**
     * sql_004 : to get random question set
     * required params : table name and no. of questions
     * @param tablename $table 
     * @param $num must be an int type
     * 
     */


    public function sql_004($table, $num)
    {
        if($table == 'challenge3_que' || $table == 'challenge2_que' || $table == 'challenge1_que')
        {
            if(is_int($num))
            {
                $sql    = 'SELECT `ques_id` FROM '. $table .' ORDER BY RAND() LIMIT '.$num;
                $query  = $this->_pdo->prepare($sql);
                $query->execute();
                $data = $query->fetchAll(PDO::FETCH_OBJ);
            
                $que_set = [];
                foreach ($data as $key => $value) {
                    
                    $que_set[] = $value->ques_id;
                }
                // print_r($que_set);
                return $que_set;
            }
        }
    }

    /**
     * sql_005 : to get recommended users for a
     * particular league
     * @param $league : league for which to get recommended users
     * @param $limit : no of records
     * 
     * details : username, name, league, img
     */

    public function sql_005($league, $limit = 10)
    {
        $sql = "SELECT users.username as `username`, users.name as `name`, users.user_image `img`, ranking.league as `league` FROM `users`, `ranking` WHERE users.username = ranking.username AND ranking.league = ? ORDER BY RAND() LIMIT 10";
        $query  = $this->_pdo->prepare($sql);
        $query->execute(array($league));
        $data = $query->fetchAll(PDO::FETCH_OBJ);
        // print_r($data);
        return $data;
    }



    /**
     * sql_006 : to set winner for challenger 3
     * 
     * @param $id : challengeid
     * @param $winner   : winner of the challenge
     * @param $loser    : loser of the challenge
     * @param $cc       : cc won by the winner
     * @param $winner_ranking : array()  ['ranking', 'rd', 'league']
     * @param $loser_ranking  : array()  ['ranking', 'rd', 'league']
     * return true|false
     */

    public function sql_006($id, $winner, $loser, $cc, $winner_ranking, $loser_ranking)
    {
        

        try {
            $this->_pdo->beginTransaction();

            /**
             * updating challenge3 table
             */

            $sql = "UPDATE `challenge3` set `winner` = ?, `cc_won` = ? WHERE `id` = ? ";
            $query  = $this->_pdo->prepare($sql);
            $query->execute(array($winner,$cc,$id));


            /**
             * update user ratings : winner
             */

            $sql = "UPDATE `ranking` set `point`=?, `rd` = ?, `league` = ? WHERE `username` = ?";
            $query  = $this->_pdo->prepare($sql);
            $query->execute(array($winner_ranking['point'], $winner_ranking['rd'],$winner_ranking['league'], $winner));

            /**
             * update user rating : loser
             */
            $sql = "UPDATE `ranking` set `point`=?, `rd` = ?, `league` = ? WHERE `username` = ?";
            $query  = $this->_pdo->prepare($sql);
            $query->execute(array($loser_ranking['point'], $loser_ranking['rd'],$loser_ranking['league'], $loser));

            /**
             * deleting notification
             */

            $sql = "DELETE FROM `challengeNotification` WHERE `id` = ?";
            $query  = $this->_pdo->prepare($sql);
            $query->execute(array($id));

            /**
             * send msg to both the users
             */

            $msg1 = "You Have Won The Challenge With ".$loser.". Check Challenge Status To Redeem CC";
            $msg2 = "You Have Lost The Challenge With ".$winner." Check Challenge Status...";

            $sql = "INSERT INTO `generalNotifications`(`username`, `notification`) VALUES (?, ?)";
            $query  = $this->_pdo->prepare($sql);
            $query->execute(array($winner, $msg1));

            $sql = "INSERT INTO `generalNotifications`(`username`, `notification`) VALUES (?, ?)";
            $query  = $this->_pdo->prepare($sql);
            $query->execute(array($loser, $msg2));

            $this->_pdo->commit();
        } 
        catch (PDOException $e) {
            // print_r($e);
            $this->_pdo->rollBack();
            return false;
        }

        return true;
    }


    /**
     * sql_007 :  function to verify if email 
     * exist or not   @author : amit
     * @param $email : email to verify
     * @return username|false 
     */


    public function sql_007($email){

        $res = $this->action('SELECT username','users',array("email","=",$email))->results();
        if(count($res)>0){
            return $res[0]->username;
        }
        else{
            return false;
        }

    }
     /**
     * sql_015 : function to get challenger and accepter codecoins
     * @param $challenger $accepter
     * @return array()
     */
    
    public function sql_015($challenger,$accepter)
    {
        $sql = "SELECT `username`, `cc` FROM cc WHERE `username` IN (?, ?)";
        $query  = $this->_pdo->prepare($sql);
        $query->execute(array($challenger,$accepter));
        $data = $query->fetchAll(PDO::FETCH_OBJ);
        // print_r($data);
        if(count($data) == 2)
        {
            $arr = array('status'=>true);
            if($data[0]->username == $challenger)
            {
                $arr['challenger']['username'] = $challenger;
                $arr['challenger']['cc'] = (int)$data[0]->cc;

                $arr['accepter']['username'] = $accepter;
                $arr['accepter']['cc'] = (int)$data[1]->cc;
                return $arr;
            }
            elseif ($data[0]->username == $accepter) {
                $arr['challenger']['username'] = $challenger;
                $arr['challenger']['cc'] = (int)$data[1]->cc;

                $arr['accepter']['username'] = $accepter;
                $arr['accepter']['cc'] = (int)$data[0]->cc;
                return $arr;
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
 }

