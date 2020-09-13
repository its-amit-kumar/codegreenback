<?php

// database wrapper can be used anywhere
// using pdo php database object 

class DB 
{
    private static $_instance = null;               //the underscore is the notation for private members
    private $_pdo,
            $_query,                                   //last query that is executed
            $_error=false,                             //for error 
            $_results,                                 //results from the querry
            $_count=0;                                 //count for the results
    

    private function __construct()                              //connection to database always
    {                            
        try{                                                       //try and catch for errors 

            $this->_pdo = new PDO('mysql:host='.Config::get('mysql/host').';dbname='.Config::get('mysql/db'),Config::get('mysql/username'),Config::get('mysql/password'));    //using PDO ;connection to db
            $this->_pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
        }catch(PDOException $e){                                       //pdo exception gives the error in $e through getMessage function 
            die($e->getMessage());
        }
    }

    public static function getInstance()                                  //instantiating the class first 
        {
            if(!isset(self::$_instance))
            {
                self::$_instance = new DB();
            }
            return self::$_instance;
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


    public function update($table,$username,$fields)                //function to update a record 
    {
        $set ='';
        $x = 1;
        foreach($fields as $key=>$value)
        {
            $set .= $key.'=?';
            if(count($fields)>$x){
                $set .= ', ';
            }
            $x++;
        }
    
        $sql = "UPDATE $table SET $set WHERE username = '$username' ";
       // echo $sql;
       
        if(!$this->query($sql,$fields)->error()){
           //echo "updated";
            return true;
        }
        else {
       // echo "not updated ";
            return false;
        }
    }


    public function createNewUser($field){
        try{
            $this->_pdo->beginTransaction();

            $sql_users = 'INSERT INTO `users` (`username`, `email`, `password`, `name`, `joined`, `group`) VALUES (?, ?, ?, ?, ?, ?)';
            $query = $this->_pdo->prepare($sql_users);
            $query->execute(array($field['username'], $field['email'], $field['password'], $field['name'], $field['joined'], $field['group']));
            // $query->closeCursor();

            $sql_cc = 'INSERT INTO `cc` (`username`) VALUES (?)';
            $query = $this->_pdo->prepare($sql_cc);
            $query->execute(array($field['username']));
            // $query->closeCursor();

            $sql_not_setting = 'INSERT INTO `userNotificationSettings` (`username`) VALUES (?)';
            $query = $this->_pdo->prepare($sql_not_setting);
            $query->execute(array($field['username']));
            // $query->closeCursor();

            $sql_user_attempted_ques = 'INSERT INTO `user_attempted_ques` (`username`,`challenge1`,`challenge2`,`challenge3`) VALUES (?, ?, ?, ?)';
            $query = $this->_pdo->prepare($sql_user_attempted_ques);
            $query->execute(array($field['username'],'[]','[]','[]'));
            // $query->closeCursor();

            $sql_lastRank = 'SELECT MAX(rank) as lastrank FROM ranking';
            $query = $this->_pdo->prepare($sql_lastRank);
            $query->execute();
            $result = $query->fetchAll(PDO::FETCH_OBJ);
            // $query->closeCursor();

            $sql_ranking = 'INSERT INTO `ranking` (`username`, `rank`) VALUES (?, ?)';
            $query = $this->_pdo->prepare($sql_ranking);
            $query->execute(array($field['username'],($result[0]->lastrank + 1)));
            // $query->closeCursor();

            $sql_last_challenge_stats_rank = 'SELECT MAX(challenge1_rank) as c1_rank , MAX(challenge2_rank) as c2_rank, MAX(challenge3_rank) as c3_rank FROM user_challenge_stats';
            $query = $this->_pdo->prepare($sql_last_challenge_stats_rank);
            $query->execute();
            $result = $query->fetchAll(PDO::FETCH_OBJ);
            // $query->closeCursor();

            $sql_user_challenge_stats = 'INSERT INTO `user_challenge_stats`(`username`, `challenge1_rank`,  `challenge2_rank`, `challenge3_rank`) VALUES (?, ?, ?, ?)';
            $query = $this->_pdo->prepare($sql_user_challenge_stats);
            $query->execute(array($field['username'], ($result[0]->c1_rank + 1) , ($result[0]->c2_rank + 1), ($result[0]->c3_rank + 1) ));
            // $query->closeCursor();

            $sql_friends = 'INSERT INTO `friends` (`username`, `friendDetails`) VALUES (?, ?)';
            $query = $this->_pdo->prepare($sql_friends);
            $query->execute(array($field['username'], json_encode(array("friends"=>null,"blocked"=>null,"requests"=>null,"requested"=>null)) ));
          //  $query->closeCursor();
 	    
	    $sql_generalDetails = 'INSERT INTO `generalDetails` (`username`) VALUES (?)';
	    $query = $this->_pdo->prepare($sql_generalDetails);
	    $query->execute(array($field['username']));

	    $sql_ccStats = 'INSERT INTO `ccStats`(`username`, `ccstats`) VALUES (?, ?)';
            $query = $this->_pdo->prepare($sql_ccStats);
            $initial = array(
                            array('cc' => +50,
                            'referer' => "new_user",
                            'date' => date("Y-m-d H:i:s",time()),
                            'balance' => 50),
                        );
            $query->execute(array($field['username'], json_encode($initial)));

            $this->_pdo->commit();

        }catch(PDOException $e){
            print_r($e);
            $this->_pdo->rollBack();
            return false;
        }

        return true;
    }
    

    /* ...........function for buying code coins or elite membership ....................... */

    public function processPackageBuy($orderID, $pack, $ccTransfer, $r_order_id, $r_payment_id, $r_signature,$amount)
    {
        if(Session::exists(Config::get('session/session_name')))
        {
            $user = Session::get(Config::get('session/session_name'));
            $check = $this->get('ccTransactions', array('orderID','=',$orderID));
            if($check != false)
            {
                $check = $check->results();
                if(!empty($check))
                {
                    return false;
                } 
                $flag = true;
                $elite = false;
		/**
                 * get ccStats of the user 
                 * (updated)
                 */

                $ccStats = StaticFunc::update_cc_stats($ccTransfer, $orderID);

                try
                {
                    $this->_pdo->beginTransaction();

                    $sql_insert = 'INSERT INTO `ccTransactions` (`username`, `orderID`, `pack`, `ccTransfered`, `razor_order_id`, `razor_payment_id`, `razor_signature`, `amount`) VALUES (?, ?, ?, ?, ?, ?, ?, ?)';
                    $query = $this->_pdo->prepare($sql_insert);
                    $query->execute(array(
                        $user,
                        $orderID,
                        $pack,
                        $ccTransfer,
                        $r_order_id,
                        $r_payment_id,
                        $r_signature,
                        $amount
                    ));

                    if($ccTransfer > 0)
                    {
                         $sql = "UPDATE `cc` set `cc` = `cc` + ? WHERE username = ?";
                         $query = $this->_pdo->prepare($sql);
                         $query->execute(array($ccTransfer, $user));

                        /**
                         * update cc stats of the user
                         */
                        $sql = "UPDATE `ccStats` set `ccstats` = ? WHERE username = ?";
                        $query = $this->_pdo->prepare($sql);
                        $query->execute(array($ccStats, $user));

                    }

                    if(Session::get(Config::get('session/user_type')) == "non-elite")
                    {
                        $sql = "UPDATE `users` set `memberType` = 1 WHERE username = ?";
                        $query = $this->_pdo->prepare($sql);
                        $elite = true;
                        Session::put(Config::get('session/user_type') ,"elite");
                        $query->execute(array($user));

                    }

                    $sql = "INSERT INTO `generalNotifications` (`username`, `notification`) VALUES (?, ?)";
                    $query = $this->_pdo->prepare($sql);
                    $msg = "Order Successfull :".$pack; 
                    $query->execute(array($user, $msg ));

                    $this->_pdo->commit();

                }catch(PDOException $e)
                {
                    $this->_pdo->rollBack();
                    if($elite)
                    {
                        Session::put(Config::get('session/user_type') ,"non-elite");
                    }
                    $flag = false;
                }

                return $flag;
            }
           
        }
        else{
            return false;
        }
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

    /*   
            functions for question adding 
            cann be modified later ...


    */

     public function get_last_ques_id($table){
        if ($table=='challenge2' || $table=='challenge3' || $table == 'challenge1') {
            $sql = 'SELECT MAX(ques_id) as ques_id FROM '.$table.'_que';
        }
        else{
            $sql = 'SELECT MAX(ques_id) as ques_id FROM questions';
        }
        $this->query($sql);
        if($this->error()){
            echo $sql;
            return 0;
        }
        else {
            return $this->first()->ques_id;
        }
    }

    /*       for notification update                */


    public function updateNotification($table,$id,$fields)                //function to update Notification table
    {
        $set ='';
        $x = 1;
        foreach($fields as $key=>$value)
        {
            $set .= $key.'=?';
            if(count($fields)>$x){
                $set .= ', ';
            }
            $x++;
        }
    
        $sql = "UPDATE $table SET $set WHERE id = '$id'";
        //echo $sql;
       
        if(!$this->query($sql,$fields)->error()){
          // echo "updated";
            return true;
        }
        else {
        //echo "not updated ";
            return false;
        }
    }


    public function getQues($table,$num,$fields=null)                              //array like [1,2,3,4]                             
    {
        if(!$fields)
        {
            $sql = "SELECT * FROM $table ORDER BY RAND() LIMIT $num "; 
            return $this->query($sql)->results();
        }
        else{

            $not_in ='';
            $x = 1;
            foreach($fields as $key)
            {
                $not_in .='?';
                if(count($fields)>$x){
                    $not_in .= ', ';
                }
                $x++;
            }
    
            $sql = "SELECT * FROM $table WHERE ques_id NOT IN ( $not_in ) LIMIT $num "; 
            return $this->query($sql,$fields)->results();
           // echo $sql;

        }

    }



    public function getUserLike($str){
        $str = $str."%";
        $sql = "SELECT users.username,users.name, users.user_image , ranking.point, ranking.league, ranking.rank FROM users INNER JOIN ranking ON users.username = ranking.username WHERE users.username LIKE  '".$str. "' LIMIT 50" ;
        return $this->query($sql)->results();
    }

    /* function used for profile page friend info */

    public function getUserBasicInfo($user = array())
    {
        $tem = "";
        $len = count($user);
        // echo $len;
        for($i = 0 ; $i < $len ; $i++ )
        {
            $tem  = $tem."?";
            if($i != ($len-1))
            {
                $tem = $tem.", ";
            }
        }
        $sql = "SELECT users.username,users.name, users.user_image , ranking.point, ranking.league, ranking.rank FROM users INNER JOIN ranking ON users.username = ranking.username WHERE users.username in (" . $tem . ")";
        $query = $this->_pdo->prepare($sql);
        $query->execute($user);
        $result = $query->fetchAll(PDO::FETCH_OBJ);
        return $result;
    }
    // get Profile Page General Data
    
    public function getProfileGeneralData($user)
    {
        $sql = 'SELECT ranking.username as username,users.user_image as user_image, ranking.point as overAllPoint, ranking.league as league, ranking.rank as overAllRank, user_attempted_ques.challenge1 as challenge1_que, user_attempted_ques.challenge2 as challenge2_que, user_attempted_ques.challenge3 as challenge3_que, user_challenge_stats.totalChallenges as totalChallenges, user_challenge_stats.challengesWon as challengesWon FROM ranking, user_attempted_ques, user_challenge_stats, users WHERE ranking.username = user_attempted_ques.username AND ranking.username = users.username AND user_attempted_ques.username = user_challenge_stats.username AND ranking.username = ?';
        $query = $this->_pdo->prepare($sql);
        $query->execute(array($user));
        return $result = $query->fetchAll(PDO::FETCH_OBJ);
        
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

    public function getall(){
        $sql = 'SELECT username FROM users';
        $query = $this->_pdo->prepare($sql);
        $query->execute();
        $result = $query->fetchAll(PDO::FETCH_OBJ);
        return $result;

    }

    public function getUserProfileSettings($user){
        $sql = 'SELECT users.username AS username, users.name AS name , users.email AS userEmail, users.user_image AS user_image, generalDetails.data as userdata FROM users , generalDetails WHERE users.username = ? AND users.username = generalDetails.username';
        $this->query($sql , array('username'=>$user));
        return $this;
    }


    /*   function to lazy loading for question      */

    public function getChallenge1Ques_lazy($id)
    {
        $sql = "SELECT ques_id , ques_title ,tag FROM `challenge1_que` WHERE ques_id > ? LIMIT 10";
        $this->query($sql , array("id"=>$id));
        return $this;
    }



    /*   public function to get user Challenge details */

    public function getChallengedetails($table , $user)
    {
        if($table == 'challenge1')
        {
            $date = 'accepter_start_time';
            $opponent = 'accepter';
        }
        elseif($table == 'challenge2')
        {
            $date = 'date_time';
            $opponent = 'opponent';
        }
	elseif($table == 'challenge3')
        {
            $date = 'date_time';
            $opponent = 'opponent';
        }

        $sql = "SELECT * FROM `$table` WHERE challenger = ?  OR `$opponent` = ? ORDER BY  `$date` DESC ";
        $this->query($sql , array('username'=>$user, 'username1'=>$user));
        return $this;

    }


    /*   SQL TO UPDATE  (ADD INTEGERS ) LIKE UPDATE CODE COINS               */

    public function updateAppendInteger($table , $column , $value , $username)
    {
        $sql = "UPDATE `$table` set `$column` = `$column` + ? WHERE username = ?";
        $this->query($sql , array('value'=>$value, 'username'=>$username));
        return $this;

    }


    /* .......function to get user email and account notification settings............  */

    public function getEmail_AccountNotificationSet($user){
        $sql = "SELECT userNotificationSettings.username AS username ,userNotificationSettings.general AS general , userNotificationSettings.challengeRequests AS challengeRequests, userNotificationSettings.challengeAccept AS challengeAccept , userNotificationSettings.push AS push , users.email AS email FROM userNotificationSettings , users WHERE  userNotificationSettings.username = ? AND userNotificationSettings.username = users.username";

        $this->query($sql , array('username'=>$user));
        return $this;
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


    // FUNCTION TO GET USER USER TRANSACTIONS

    public function getUserTransactions($user)
    {

        $sql = "SELECT `orderID`, `pack`, `amount`, `created` FROM `ccTransactions` WHERE username = ? ORDER BY  `created` DESC";
        $this->query($sql , array('username'=>$user));
        if(!$this->error())
        {
            $data = $this->results();
            return $data;
        }
        return false;
    }



    /*................fUNCTION TO UPDATE USERS TOTAL CHALLENGES...................      */

    public function updateTotalChallenges($user1 , $user2)
    {
        $sql = "UPDATE `user_challenge_stats` SET `totalChallenges`= `totalChallenges` + 1 WHERE `username` IN (? , ?)";;
        $this->query($sql , array('username1'=>$user1, "username2"=>$user2));
        return $this;
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

            $this->_pdo->commit();

        } 
        catch (PDOException $e) {
            // print_r($e);
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
     * sql_008 : function to get user codecoins and ccStats
     * @param $user : username
     * @return integer|null
     */

    public function sql_008($user)
    {
        $sql = 'SELECT cc.cc as `cc`, ccStats.ccstats FROM `cc`, `ccStats` WHERE cc.username = ccStats.username and cc.username = ?';
        $query  = $this->_pdo->prepare($sql);
        $query->execute(array($user));
        $data = $query->fetchAll(PDO::FETCH_OBJ);
        if(!empty($data))
        {
            return $data[0];
        }
        return null;
    }


    /**
     * sql_009 : function to get current user withdraw request
     * @param $user : username
     * @return data|null 
     */

    public function sql_009($user)
    {
        $sql = 'SELECT * FROM `withdrawRequest` WHERE `username` = ? ';
        $query  = $this->_pdo->prepare($sql);
        $query->execute(array($user));
        $data = $query->fetchAll(PDO::FETCH_OBJ);
        if(!empty($data))
        {
            return $data[0];
        }
        return null;

    }


    /**
     * sql_010 : function to register a new cc redeem request
     * @param $user : username
     *        $processing_id : request id
     *        $data : JSON containing (status, account_no, cc_to_withdraw, contact, ifsc, processing_id, re_account_no)
     *        $ccStats : updates cc stats   JSON format
     *        $cc : cc to withdraw
     * 
     * @return true|false
     */       

    public function sql_010($user, $processing_id, $data, $ccStats, $cc)
    {
        try {
            $this->_pdo->beginTransaction();
            
            /**
             * insert new request in withdrawRequest
             */

            $sql = 'INSERT INTO `withdrawRequest` (`username`, `processID`, `orderData` ) VALUES (?, ?, ?)';
            $query  = $this->_pdo->prepare($sql);
            $query->execute(array($user, $processing_id, $data));

            /**
             * update user cc
             */

            $sql = "UPDATE `cc` set `cc` = `cc` - ? WHERE username = ?";
            $query = $this->_pdo->prepare($sql);
            $query->execute(array((int)$cc, $user));

            /**
             * update ccStats
             */

            $sql = "UPDATE `ccStats` set `ccstats` = ? WHERE username = ?";
            $query = $this->_pdo->prepare($sql);
            $query->execute(array($ccStats, $user));

            /**
             * send general msg to user
             */
            
            $sql = "INSERT INTO `generalNotifications` (`username`, `notification`) VALUES (?, ?)";
            $query = $this->_pdo->prepare($sql);
            $msg = 'Your withdrawal request is under review. Reference ('.$processing_id.') '; 
            $query->execute(array($user, $msg ));
            

            $this->_pdo->commit();
            

        } 
        catch (PDOException $e) {
            print_r($e);
            $this->_pdo->rollBack();
            return false;
        }

        return true;
    }


  /**
     * sql_011 : function to get recents for a user
     * @param : $user : username of the current user
     * @return  array|null
     */

    public function sql_011($user)
    {
        $arr = [];

        $sql = "SELECT `challenger`, `accepter` FROM `challenge1` WHERE challenger = ?  OR `accepter` = ? ORDER BY  `accepter_start_time` DESC LIMIT 1 ";
        $query  = $this->_pdo->prepare($sql);
        $query->execute(array($user,$user));
        $data = $query->fetchAll(PDO::FETCH_OBJ);
        if(!empty($data))
        {
            $data = $data[0];
            if($data->challenger == $user)
            {
                $arr[] = array(
                    'opponent' => $data->accepter,
                    'challenge' => 'TurnYourTurn'
                );
            }
            else
            {
                $arr[] = array(
                    'opponent' => $data->challenger,
                    'challenge' => 'TurnYourTurn'
                );
            }
        }

        $sql = "SELECT `challenger`, `opponent` FROM `challenge2` WHERE challenger = ?  OR `opponent` = ? ORDER BY  `date_time` DESC LIMIT 1 ";
        $query  = $this->_pdo->prepare($sql);
        $query->execute(array($user,$user));
        $data = $query->fetchAll(PDO::FETCH_OBJ);

        if(!empty($data))
        {
            $data = $data[0];
            if($data->challenger == $user)
            {
                $arr[] = array(
                    'opponent' => $data->opponent,
                    'challenge' => 'Rushour'
                );
            }
            else
            {
                $arr[] = array(
                    'opponent' => $data->challenger,
                    'challenge' => 'Rushour'
                );
            }
        }

        $sql = "SELECT `challenger`, `opponent` FROM `challenge3` WHERE challenger = ?  OR `opponent` = ? ORDER BY  `date_time` DESC LIMIT 1 ";
        $query  = $this->_pdo->prepare($sql);
        $query->execute(array($user,$user));
        $data = $query->fetchAll(PDO::FETCH_OBJ);

        if(!empty($data))
        {
            $data = $data[0];
            if($data->challenger == $user)
            {
                $arr[] = array(
                    'opponent' => $data->opponent,
                    'challenge' => 'Face/Off'
                );
            }
            else
            {
                $arr[] = array(
                    'opponent' => $data->challenger,
                    'challenge' => 'Face/Off'
                );
            }
        }

        return $arr;
        

    }

   /**
     * sql_012 : function is for getting user withdraw request data and cc buy data
     */

    public function sql_012($user)
    {
        $sql = "SELECT * FROM `withdrawRequest` WHERE `username` = ?";
        $query  = $this->_pdo->prepare($sql);
        $query->execute(array($user));

        $data = $query->fetchAll(PDO::FETCH_OBJ);
        return $data;

    }


    /**
     * sql_013 : function for challenge1 completion by a user
     *
     * set challenger_que to 1
     * update c_status to 2 i.e. completed
     * update user attempted question
     * set user end time of challenge
     * 
     * @param $username, $challengeid, $challenger = 1|0 $quesid
     * @return true|false
     */

    public function sql_013($username, $challengeid, $challenger, $quesid)
    {
        try 
        {
            $this->_pdo->beginTransaction();

            //update user solved status

            $current_time = date("Y-m-d H:i:s");
            if($challenger == 1)
            {
                $sql1 = 'UPDATE `challenge1` SET `challenger_ques` = 1 WHERE `id` = ?';
                $sql2 = 'UPDATE `challengeNotification` SET `c_status` = 2 WHERE `id` = ?';
                $sql3 = 'UPDATE `challenge1` SET `challenger_end_time` = ? WHERE `id` = ?';
            }else
            {
                $sql1 = 'UPDATE `challenge1` SET `accepter_ques` = 1 WHERE `id` = ?';
                $sql2 = 'UPDATE `challengeNotification` SET `a_status` = 2 WHERE `id` = ?';
                $sql3 = 'UPDATE `challenge1` SET `accepter_end_time` = ? WHERE `id` = ?';
            }
            $query  = $this->_pdo->prepare($sql1);
            $query->execute(array($challengeid));

            $query  = $this->_pdo->prepare($sql2);
            $query->execute(array($challengeid));

            $query  = $this->_pdo->prepare($sql3);
            $query->execute(array($current_time,$challengeid));

            $sql4 = 'UPDATE `user_attempted_ques` SET `challenge1` = JSON_ARRAY_APPEND(challenge1, "$", ? ) WHERE `username` = ?';
            $query  = $this->_pdo->prepare($sql4);
            $query->execute(array($quesid,$username));

            $this->_pdo->commit();

        }
        catch (PDOException $e) {
            $this->_pdo->rollBack();
            return false;
        }

        return true;

    }

    /**
     * sql_014 : function for challenge1 acceptance 
     * refer to challenge1 class for how the function is used
     */

    public function sql_014($challenger,$accepter,$cc,$id,$challenger_bal,$accepter_bal,$challenge1_data = array())
    {
        try {
            $this->_pdo->beginTransaction();

            /**
             * update challenge notificatation table
             */

            $sql = 'UPDATE `challengeNotification` SET `status` = 1,`a_status` = 1 WHERE `id` = ?';
            $query  = $this->_pdo->prepare($sql);
            $query->execute(array($id));

            /**
             * insert data into challenge1 table
             */

            $sql = 'INSERT INTO `challenge1` (`id`, `challenger`,`accepter`,`cc`,`default_endtime_accepter`,`accepter_start_time`,`default_endtime_challenger`,`ques_set`) VALUES (?, ?, ?, ?, ?, ?, ?, ?)';
            $query  = $this->_pdo->prepare($sql);
            $query->execute($challenge1_data);

            /**
             * deduct accepter codecoins
             */

            $sql = 'UPDATE `cc` SET `cc` = `cc` - ? WHERE `username` = ?';
            $query  = $this->_pdo->prepare($sql);
            $query->execute(array($cc, $accepter));
            
            /**
             * update cc stats data for both users
             */

            //for challenger
            $arr1 = json_encode(array(
                'cc' => -$cc,
                'date' => date('Y-m-d H:i:s'),
                'balance' => (int)$challenger_bal,
                'referer' => $id .' accepted'
            ));

            //for accepter

            $arr2 = json_encode(array(
                'cc' => -$cc,
                'date' => date('Y-m-d H:i:s'),
                'balance' => (int)$accepter_bal,
                'referer' => $id . 'accepted'
            ));

            $sql = 'UPDATE `ccStats` SET `ccstats` = JSON_ARRAY_APPEND(ccstats, "$", CAST(? as JSON)) WHERE `username` = ?';
            $query  = $this->_pdo->prepare($sql);
            $query->execute(array($arr1, $challenger));
            $query->execute(array($arr2, $accepter));

            //update total challenges

            $sql = 'UPDATE `user_challenge_stats` SET `totalChallenges` = `totalChallenges` + 1 WHERE `username` IN (?, ?)';
            $query  = $this->_pdo->prepare($sql);
            $query->execute(array($challenger,$accepter));
            $this->_pdo->commit();
            

        } catch (PDOException $e) {
            $this->_pdo->rollBack();
            return false;
        }
        return true;
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


  /**
     * sql_016: function to redeem codecoins of challenges 
     * @param $username, $cid, $challenge = challenge1,challenge2,challenge3
     * @param $cc_won $current_balance
     * @return true|false
     */

    public function sql_016($user,$cid,$challenge,$cc_won,$current_balance)
    {
        try {
            $this->_pdo->beginTransaction();

            /**
             * update challenge table for redeem status
             */

            switch ($challenge) {
                case 'challenge1':
                    $sql = 'UPDATE `challenge1` SET `cc_redeem` = 1 WHERE `id` = ?';
                    break;
                case 'challenge2':
                    $sql = 'UPDATE `challenge2` SET `cc_redeem` = 1 WHERE `id` = ?';
                    break;
                case 'challenge3':
                    $sql = 'UPDATE `challenge3` SET `cc_redeem` = 1 WHERE `id` = ?';
                    break;
            }

            $query  = $this->_pdo->prepare($sql);
            $query->execute(array($cid));

            /**
             * update user codecoins
             */

            $sql = 'UPDATE `cc` SET `cc` = `cc` + ? WHERE `username` = ?';
            $query  = $this->_pdo->prepare($sql);
            $query->execute(array($cc_won,$user));

            /**
              * update cc stats table
              */

            $arr1 = json_encode(array(
                'cc' => +$cc_won,
                'date' => date('Y-m-d H:i:s'),
                'balance' => (int)$current_balance + (int)$cc_won,
                'referer' => $cid .' redeem'
            ));

            $sql = 'UPDATE `ccStats` SET `ccstats` = JSON_ARRAY_APPEND(ccstats, "$", CAST(? as JSON)) WHERE `username` = ?';
            $query  = $this->_pdo->prepare($sql);
            $query->execute(array($arr1, $user));

            $this->_pdo->commit();
            
            
        } catch (PDOException $e) {
        
            $this->_pdo->rollBack();
            return false;
        }
        return true;
    }



 /**
     * sql_017 : function for challenge2 acceptance 
     * 
     */

    public function sql_017($challenger,$accepter,$cc,$id,$challenger_bal,$accepter_bal,$challenge2_data = array())
    {
        try {
            $this->_pdo->beginTransaction();

            /**
             * update challenge notificatation table
             */

            $sql = 'UPDATE `challengeNotification` SET `status` = 1,`a_status` = 1 WHERE `id` = ?';
            $query  = $this->_pdo->prepare($sql);
            $query->execute(array($id));

            /**
             * insert data into challenge1 table
             */

            $sql = 'INSERT INTO `challenge2` (`id`, `challenger`,`opponent`,`cc_bet`,`opponent_start_time`,`opponent_end_time`,`common_questions`) VALUES (?, ?, ?, ?, ?, ?, ?)';
            $query  = $this->_pdo->prepare($sql);
            $query->execute($challenge2_data);

            /**
             * deduct accepter codecoins
             */

            $sql = 'UPDATE `cc` SET `cc` = `cc` - ? WHERE `username` = ?';
            $query  = $this->_pdo->prepare($sql);
            $query->execute(array($cc, $accepter));
            
            /**
             * update cc stats data for both users
             */

            //for challenger
            $arr1 = json_encode(array(
                'cc' => -$cc,
                'date' => date('Y-m-d H:i:s'),
                'balance' => (int)$challenger_bal,
                'referer' => $id .' accepted'
            ));

            //for accepter

            $arr2 = json_encode(array(
                'cc' => -$cc,
                'date' => date('Y-m-d H:i:s'),
                'balance' => (int)$accepter_bal,
                'referer' => $id . 'accepted'
            ));

            $sql = 'UPDATE `ccStats` SET `ccstats` = JSON_ARRAY_APPEND(ccstats, "$", CAST(? as JSON)) WHERE `username` = ?';
            $query  = $this->_pdo->prepare($sql);
            $query->execute(array($arr1, $challenger));
            $query->execute(array($arr2, $accepter));

            //update total challenges

            $sql = 'UPDATE `user_challenge_stats` SET `totalChallenges` = `totalChallenges` + 1 WHERE `username` IN (?, ?)';
            $query  = $this->_pdo->prepare($sql);
            $query->execute(array($challenger,$accepter));
            $this->_pdo->commit();
            

        } catch (PDOException $e) {
            $this->_pdo->rollBack();
            return false;
        }
        return true;
    }

    /**
     * sql_018 function to process draw challenge
     */

    public function sql_018($challenger,$opponent,$cc_revert,$cid,$challenge,$challenger_bal,$accepter_bal)
    {
        try {
            $this->_pdo->beginTransaction();

            /**
             * update challenge table
             */
        
            switch ($challenge) {
                case 'c3':
                    $sql = 'UPDATE `challenge3` SET `winner` = "NA", `cc_redeem` = 1,`cc_won` = ? WHERE `id` = ?';
                    break;
		case 'c2':
                    $sql = 'UPDATE `challenge2` SET `winner` = "NA", `cc_redeem` = 1,`cc_won` = ? WHERE `id` = ?';
                    break;
            }
            $query  = $this->_pdo->prepare($sql);
            $query->execute(array((int)$cc_revert,$cid));

            /**
             * delete data from challenge notification table
             */

            $sql = 'DELETE FROM `challengeNotification` WHERE `id` = ?';
            $query  = $this->_pdo->prepare($sql);
            $query->execute(array($cid));

            /**
             * revert back codecoins to both players
             */

            $sql = 'UPDATE `cc` SET `cc` = `cc` + ? WHERE `username` IN (?, ?)';
            $query  = $this->_pdo->prepare($sql);
            $query->execute(array($cc_revert,$challenger,$opponent));

            /**
             * update ccstats table
             */

            //for challenger
            $arr1 = json_encode(array(
                'cc' => +$cc_revert,
                'date' => date('Y-m-d H:i:s'),
                'balance' => (int)$challenger_bal + (int)$cc_revert,
                'referer' => $cid .' DRAW'
            ));

            //for accepter

            $arr2 = json_encode(array(
                'cc' => +$cc_revert,
                'date' => date('Y-m-d H:i:s'),
                'balance' => (int)$accepter_bal + (int)$cc_revert,
                'referer' => $cid . ' Draw'
            ));

            $sql = 'UPDATE `ccStats` SET `ccstats` = JSON_ARRAY_APPEND(ccstats, "$", CAST(? as JSON)) WHERE `username` = ?';
            $query  = $this->_pdo->prepare($sql);
            $query->execute(array($arr1, $challenger));
            $query->execute(array($arr2, $opponent));

            /**
             * make general notification
             */

            $msg1 = "FACE/OFF With ".$challenger." is a draw ! Check Challenge Status  For CC Info.";
            $msg2 = "FACE/OFF With ".$opponent." is a draw ! Check Challenge Status For CC Info.";
            
            $sql = 'INSERT INTO `generalNotifications` (`username`, `notification`) VALUES (?, ?)';
            $query  = $this->_pdo->prepare($sql);
            $query->execute(array($challenger, $msg2));
            $query->execute(array($opponent, $msg1));

            $this->_pdo->commit();
            
        }
        catch (PDOException $e) {
            error_log($e);
            $this->_pdo->rollBack();
            return false;
        }
        return true;

    }



    /**
     * sql_019 : function to get and update user image
     * @param $username
     * @param $path = null (optional)
     * @return array(status,current_image_path)
     */

    public function sql_019($username, $path = 'NA')
    {
        $sql = 'SELECT `user_image` FROM `users` WHERE `username` = ?';
        $query  = $this->_pdo->prepare($sql);
        $query->execute(array($username));
        $data = $query->fetchAll(PDO::FETCH_OBJ);

        $arr = array();
        if($path != 'NA')
        {
            $sql = 'UPDATE `users` SET `user_image` = ? WHERE `username` = ?';
            $query  = $this->_pdo->prepare($sql);
            if($query->execute(array($path,$username)))
            {
                $arr['img_upload'] = true;
            }
            else
            {
                $arr['img_upload'] = false;
            }
        }

        
        if(empty($data))
        {
            $arr['status'] = false;
            return $arr;
        }

        $arr['status'] = true;
        $arr['img'] = $data[0]->user_image;
        return $arr;
    }


    /**
     * sql_020 : function to get challenge notification and the challenger image
     * @param $accepter
     */

    public function sql_020($accepter)
    {
        $sql = 'SELECT `serialNo`, `id`, `challenger`, `accepter`, `challengeName`, `cc`, `time`, `status`, `t_o_r`, `a_status`, `c_status`, users.user_image as `c_img` FROM `challengeNotification`, `users` WHERE challengeNotification.accepter = ? AND challengeNotification.challenger = users.username';
        $query  = $this->_pdo->prepare($sql);
        $query->execute(array($accepter));
        $data = $query->fetchAll(PDO::FETCH_OBJ);

        return $data;
    }


/**
     * sql_021 : function to get all user mails
     */

    public function sql_021()
    {
        $sql = 'SELECT `email` FROM users WHERE 1';
        $query  = $this->_pdo->prepare($sql);
        $query->execute();
        $data = $query->fetchAll(PDO::FETCH_OBJ);

        return $data;

    }


 }

