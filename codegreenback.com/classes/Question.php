<?php
//new questions class slight changes 

require_once $_SERVER['DOCUMENT_ROOT'].'/core/init.php';

 class Question {

    private 
            $_question_id,
            $_db;


    public function __construct()
    {
        $this->_db = DB::getInstance();
        $last_id = $this->_db->get_last_ques_id('challenge1');
        $last_id += 1;
        $this->_question_id = $last_id;
        
    }

    public function make_question($source)
    {

      
        $num_of_public_test = $source['no_of_pub_test'];
        $num_of_private_test = $source['no_of_pri_test'];
        $inputPublic = array();                                             //testcase
        $outputPublic = array();                                            //expected Public output
        $inputPrivate = array();
        $outputPrivate = array();
        for($i=0;$i<$num_of_public_test;$i++)
        {
            $inputPublic[$i] = $source['public-input-'.$i];
            $outputPublic[$i] = $source['public-output-'.$i];
        }

        for($i = 0 ; $i< $num_of_private_test ; $i++)
        {
            $inputPrivate[$i] = $source['private-input-'.$i];
            $outputPrivate[$i] = $source['private-output-'.$i];
        }


        $str = array(
            'ques'=>$source['ques'],
            'input_format'=>$source['input-format'],
            'output_format'=>$source['output-format'],
            'sample_input'=>$source['sample-input'],
            'sample_output'=>$source['sample-output'],
            'lang'=>$source['lang'],
            'level'=>$source['level'],
            'domain'=>$source['domain'],
            'No_of_public_testcase'=>$source['no_of_pub_test'],
            'No_of_private_testcase'=>$source['no_of_pri_test'],
            'Public_input'=>$inputPublic,
            'Public_output'=>$outputPublic,
            'Private_input'=>$inputPrivate,
            'Private_output' => $outputPrivate
        );


        $str = json_encode($str);
       // print_r($str);
    

       $arr = array('ques_id'=>$this->_question_id,'ques_title'=>$source['ques_title'],'tag'=>$source['level'],'data'=>$str);
        if($this->_db->insert('challenge1_que',$arr))
        {

            // echo "Question added successfully !";
            return true;
        }
        else {
            // echo "error !!";
            return false;
        }
        
    }

 }



?>