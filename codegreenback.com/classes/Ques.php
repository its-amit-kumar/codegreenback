<?php 

require_once $_SERVER['DOCUMENT_ROOT'].'/core/init.php';

 class Ques {

    private $_path,
            $_question_id,
            $_db,
            $_id,
            $_type_of_question,
            $_QuestionInfoArray;


    public function __construct($type_of_queston,$id = null)
    {
    	if (!isset($id))
    	{
	        $this->_db = DB::getInstance();
            if($type_of_queston=='challenge2' || $type_of_queston=='challenge3'){
	           $last_id = $this->_db->get_last_ques_id($type_of_queston);
            }
            else{
                $last_id = $this->_db->get_last_ques_id('questions');
                $last_id = $this->_db->get_last_ques_id('questions');
            }
	        $last_id += 1;
            $this->_type_of_question = $type_of_queston;
	        $this->_question_id = $last_id;
	        $this->_path = "data/questions/".$this->_type_of_question."/question".$last_id;
	        mkdir($this->_path) or die("Unable to make dir");
    	}
    	else
    	{
            $this->_type_of_question = $type_of_queston;
    		$this->_question_id = $id;
    		$this->_db  = DB::getInstance();

            if($type_of_queston=='challenge2' || $type_of_queston=='challenge3'){
               $this->_QuestionInfoArray = $this->_db->get($type_of_queston.'_que',array(
                'ques_id',
                '=',
                $this->_question_id
                ))->results();
            }
            else{
                $this->_QuestionInfoArray = $this->_db->get('questions',array(
                'ques_id',
                '=',
                $this->_question_id
                ))->results();
            }
   // 		echo '<pre>';
   // print_r($this->_QuestionInfoArray);
    //echo '</pre>';
    		$this->_path = $this->_QuestionInfoArray[0]->path;


    	}
    }

    public function get_question(){
        //$noOfPublicTestCases = $this->_QuestionInfoArray[0]->total_test_cases - $this->_QuestionInfoArray[0]->private_test_cases
        //$explanationArray = array(); 
        //return $this->_question_id;
        //for ($i=1; $i <= $noOfPublicTestCases; $i++) { 
             
        
    	return array("qid" => $this->_question_id,
            "title" => $this->_QuestionInfoArray[0]->ques_title,
            "question" => file_get_contents('/var/www/codegreenback.com/'.$this->_path."/question".$this->_question_id.".txt"),
            "if" => file_get_contents('/var/www/codegreenback.com/'.$this->_path."/question".$this->_question_id."if.txt"),
            "of" => file_get_contents('/var/www/codegreenback.com/'.$this->_path."/question".$this->_question_id."of.txt"),
            "constrain" => file_get_contents('/var/www/codegreenback.com/'.$this->_path."/question".$this->_question_id."constrain.txt"),
            "sampleI" => file_get_contents('/var/www/codegreenback.com/data/questions/'.$this->_type_of_question.'/question'.$this->_question_id.'/input-'.$this->_question_id.'-1.txt'),
            "sampleO" =>file_get_contents('/var/www/codegreenback.com/data/questions/'.$this->_type_of_question.'/question'.$this->_question_id.'/output-'.$this->_question_id.'-1.txt'));

    }

    public function make_question($source){

        $filename = $this->_path."/question".$this->_question_id.".txt";                     //question path and name
        $file = fopen($filename,'w') or die("error opening the file !");
        fwrite($file,$source['ques']);
        fclose($file);
    }

    public function make_minors($source){
        $filename = $this->_path."/question".$this->_question_id."if.txt";
        $file = fopen($filename,'w') or die("error opening the file !");
        fwrite($file,$source['if']);
        fclose($file);
        $filename = $this->_path."/question".$this->_question_id."of.txt";
        $file = fopen($filename,'w') or die("error opening the file !");
        fwrite($file,$source['if']);
        fclose($file);
        $filename = $this->_path."/question".$this->_question_id."constrain.txt";
        $file = fopen($filename,'w') or die("error opening the file !");
        fwrite($file,$source['constrain']);
        fclose($file);
        $filename = $this->_path."/question".$this->_question_id."solution.txt";
        $file = fopen($filename,'w') or die("error opening the file !");
        fwrite($file,$source['solution']);
        fclose($file);

    }

    public function makeTestCase($source){

        $num_of_test = $source['no_of_test'];

        for ($i=0; $i < $num_of_test; $i++){ 
            $j = $i + 1;
            $input_file = $this->_path."/input-".$this->_question_id."-".$j.".txt";          //input file full path and name
            $output_file = $this->_path."/output-".$this->_question_id."-".$j.".txt";        //output file full path and name

            /*$file = fopen($input_file,'w') or die("error making a file");
            $var = "input".$i+1;
            fwrite($file,$source[$var]);
            fclose($file);

            $file = fopen($output_file,'w') or die("error making a file");
            $var = "output".$i+1;
            fwrite($file,$source[$var]);
            fclose($file);*/
            $file = fopen($input_file,'w') or die("error making a file");
            fwrite($file,$source['testcases'][$i]["inputgiven"]);
            fclose($file);

            $file = fopen($output_file,'w') or die("error making a file");
            fwrite($file,$source['testcases'][$i]["expectedOutput"]);
            fclose($file);



        }

        /*$str = array('lang'=>$source['lang'],'level'=>$source['level'],'domain'=>$source['domain']);
        $str = json_encode($str);

        $arr = array('ques_id'=>$this->_question_id,'ques_title'=>$source['ques_title'],'path'=>$this->_path,'tags'=>$str,'test_case'=>$source['no_of_test']);
        if($this->_db->insert('questions',$arr)){
            echo "Question added successfully !";
        }
        else {
            echo "error !!";
        }*/

    }

    public function insertInfo($questionTitle,$jsonOfLanguages,$total_test_cases,$private_test_cases,$level,$points_distribution){
        if($this->_type_of_question!='challenge2' && $this->_type_of_question!='challenge3'){
            $feild  = array(
            'ques_id' => $this->_question_id,
            'ques_title' => strval($questionTitle),
            'path' => strval($this->_path),
            'tags' => $jsonOfLanguages,
            'total_test_cases' => intval($total_test_cases),
            'type_of_question' => strval($this->_type_of_question),
            'private_test_cases' => intval($private_test_cases),
            'level' => strval($level),
            'points_distribution' => $points_distribution
            );
             if($this->_db->insert("questions",$feild)){
                echo "done";
             }
             else{
                print_r($feild);
                //echo $this->_db->insert("questions",$feild);
             }
        }
        else{
            $feild  = array(
            'ques_id' => $this->_question_id,
            'ques_title' => strval($questionTitle),
            'path' => strval($this->_path),
            'tags' => $jsonOfLanguages,
            'total_test_cases' => intval($total_test_cases),
            'private_test_cases' => intval($private_test_cases),
            'level' => strval($level),
            'points_distribution' => $points_distribution
            );
             if($this->_db->insert($this->_type_of_question.'_que',$feild)){
                echo "done";
             }
             else{
                echo $this->_type_of_question;
                print_r($feild);
                //echo $this->_db->insert("questions",$feild);
             }
        }
    }
   

 }


?>
