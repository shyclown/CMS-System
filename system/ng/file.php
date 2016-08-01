<?php
/**
 * Create folder
 */
class File
{

  private $db;
  public $errors;

  function __construct()
  {
    $this->db = new Database;
    $this->errors = [];
  }

  public function create_dir($path,$mode,$recursive){
    if(!file_exist($path)){
      mkdir($path,$mode,$recursive);
    }
  }
  public function create_root(){
      $root = $_SERVER['DOCUMENT_ROOT'];
      $this->create_dir();
  }

  public function upload_files($data){

    if(
    isset($data)
    && isset($data['folder'])
    && isset($data['files'])
    && is_array($data['files'])
    && isset($data['user'])
    ){
      $this->folder = $data['folder'];
      $this->files  = $this->arrayFiles($data['files']);
      $this->user   = $data['user'];
    }

    $this->folder = (isset($data['folder'])) ? $data['folder'] : 0;
    $this->file  = (isset($data['files']) && is_array($data['files'])) ? $data['file'] : 'file';
  }

  public function is_type_of($file){
    
  }


  private function arrayFiles($files){

      $file_ary = array();
      $file_count = count($file_post['name']);
      $file_keys = array_keys($file_post);

      for ($i=0; $i<$file_count; $i++) {
      foreach ($file_keys as $key) {
          $file_ary[$i][$key] = $file_post[$key][$i];
        }
      }

      return $file_ary;
  }


}/*
$fileData = file_get_contents("php://input");
$ng_data = json_decode($fileData);

*/
 ?>
