<?php



require_once "vendor/autoload.php";

require_once $_SERVER['DOCUMENT_ROOT'].'/core/init.php';




class mongo
{

	private $_connection,
      $_client;
	
	public function __construct($db)
	{

            $this->_client = new MongoDB\Client('mongodb+srv://'.Config::get('mongodb/username').':'.Config::get('mongodb/password').'@cluster0-sxg9y.mongodb.net/?ssl=true&authSource=admin&serverSelectionTryOnce=false&serverSelectionTimeoutMS=15000&w=majority');
            
            $this->_connection = $this->_client->selectDatabase($db);

	}

	public function getAllCollection(){                            //returns an array of the collections in the db passed in the construct
            $arr = array();
            foreach ($this->_connection->listCollections() as $doc) {
                      array_unshift($arr, $doc['name']);
                  }
            return $arr;

	}

      //creates a new collection, returns true and false depending on weather the collection was build or not


      public function makeCollection($coll){                               
             try {
                  $this->_connection->createCollection($coll);
                  return true;
                  
            } catch (Exception $e) {
                  return false;
                  
            }
      }

      public function count($collection,$filter = array()){             //returns the number of documents in a collection
            $coll = $this->_connection->selectCollection($collection);
            return $coll->count($filter);
      }

      public function deleteEntry($collection,$filter){                   //deletes a document, returns true on success

            $coll = $this->_connection->selectCollection($collection);
            $res = $coll->deleteOne($filter);
            if($res->$deleteResult->getDeletedCount()==1){
                  return true;
            }
            else{
                  return false;
            }
                  
                
       }

      public function get($collection,$filter = array()){                   // gets all the documents with the given filters in a collection in an array
            $coll = $this->_connection->selectCollection($collection) or die("error3");
            $res = $coll->find($filter) or die("error4");
            $arrMain = array();
            foreach ($res as $value) {
                  $arr=array();
                  foreach ($value as $key => $val) {
                        $arr[$key] = $val;
                  }
                  array_push($arrMain, $arr);
            }
            return $arrMain;
      }

      public function insert($collection,$document){                        //inserts a document in a collection, return ture on sucesss or false otherwise
                  $coll = $this->_connection->selectCollection($collection);
                  $res = $coll->insertOne($document);
                  if($res->getInsertedCount()==1){
                        return true;
                  }
                  else{
                        return false;
                  }

      }

      public function update($collection,$filter,$updates){                      // updates a document in a collection, returns true on sucess, false otherwise

            $coll = $this->_connection->selectCollection($collection);
            $ress = $coll->updateOne($filter,["\$set" => $updates]);
            if($ress->getModifiedCount()>=1){
                  return true;
            }
            else{
                  return false;
            }
             
                  

      }

}

