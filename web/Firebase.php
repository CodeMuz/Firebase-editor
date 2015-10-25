<?php

class Firebase
{

    private $DEFAULT_URL;
    private $DEFAULT_TOKEN;
    private $DEFAULT_PATH;

    private $data;
    private $firebase;
    private $fields;
    private $editfields;

    public function __construct()
    {



        $this->fields = json_decode(file_get_contents('data/fields.json'), TRUE);
        $this->editfields = json_decode(file_get_contents('data/editfields.json'), TRUE);

        $this->DEFAULT_URL = getenv('DEFAULT_URL');
        $this->DEFAULT_TOKEN = getenv('DEFAULT_TOKEN');
        $this->DEFAULT_PATH = getenv('DEFAULT_PATH');

        $this->firebase = new \Firebase\FirebaseLib($this->DEFAULT_URL, $this->DEFAULT_TOKEN);

        $this->loadJSONFile();

    }

    private function loadJSONFile(){

        $fireBaseData = $this->get();
        //$this->data = json_decode(file_get_contents('data/fest.json'), TRUE);

        $this->data = json_decode($fireBaseData, TRUE);

//        foreach($this->data as $i => $data){
//            $this->data[$i]['id'] = $i;
//        }

    }

    public function updateJSONFile(){

        $data = $this->get($this->DEFAULT_PATH . '/');

        file_put_contents('data/fest.json', $data);

        $this->loadJSONFile();

    }

    public function set($path,$data){

        $this->firebase->set($this->DEFAULT_PATH . $path, $data);

    }
    public function push($path,$data){

        $this->firebase->push($this->DEFAULT_PATH . $path, $data);

    }

    public function get($path = ''){

        return $this->firebase->get($this->DEFAULT_PATH . $path);

    }

    public function getEntries(){
        return $this->data;
    }

    public function getEntry($id){

        foreach($this->data as $key => $entry){
            if($key == $id){
                return $entry;
            }
        }

    }

    public function getFields(){

        return $this->fields;

    }

    public function getEditFields(){

        return $this->editfields;

    }
}
