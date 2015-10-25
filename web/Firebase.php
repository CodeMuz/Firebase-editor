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

        $this->loadJSONFile();

        $this->fields = json_decode(file_get_contents('data/fields.json'), TRUE);
        $this->editfields = json_decode(file_get_contents('data/editfields.json'), TRUE);

        $this->DEFAULT_URL = getenv('DEFAULT_URL');
        $this->DEFAULT_TOKEN = getenv('DEFAULT_TOKEN');
        $this->DEFAULT_PATH = getenv('DEFAULT_PATH');

        $this->firebase = new \Firebase\FirebaseLib($this->DEFAULT_URL, $this->DEFAULT_TOKEN);

    }

    private function loadJSONFile(){

        $this->data = json_decode(file_get_contents('data/fest.json'), TRUE);

        foreach($this->data as $i => $data){
            $this->data[$i]['id'] = $i;
        }

    }

    public function updateJSONFile(){

        $data = $this->get($this->DEFAULT_PATH . '/');

        file_put_contents('data/fest.json', $data);

        $this->loadJSONFile();

    }

    public function set($path,$data){


        // --- storing an array ---
        $test = array(
            "foo" => "bar",
            "i_love" => "lamp",
            "id" => 42
        );
        $dateTime = new DateTime();
        //$firebase->set(self::DEFAULT_PATH . '/' . $dateTime->format('c'), $test);

        // --- storing a string ---
        //$firebase->set(self::DEFAULT_PATH . '/messages/contact001', "John Doe");

        // --- reading the stored string ---
        //$name = $firebase->get(self::DEFAULT_PATH . '/messages/contact001');

        //var_dump($name);
        //die("done");

        $this->firebase->set($this->DEFAULT_PATH . $path, $data);

    }

    public function get($path){

        return $this->firebase->get($this->DEFAULT_PATH . $path);

    }

    public function getEntries(){
        return $this->data;
    }

    public function getEntry($id){

        foreach($this->data as $entry){
            if($entry['id'] == $id){
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
