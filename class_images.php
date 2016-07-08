<?php
require_once('class_session.php');
require_once('class_mysqli.php');
require_once('class_file.php');

$file_manager = new File;

new Session;

if(isset($_SESSION)){  var_dump($_SESSION); }
if(isset($_POST)){  var_dump($_POST); }
if(isset($_FILES["up_file"])){
  $_FILES["up_file"] = $file_manager->array_files($_FILES["up_file"]);

  var_dump($_FILES["up_file"]);
  for($i = 0, $len = count($_FILES["up_file"]); $i < $len; $i++){
    $file = $_FILES["up_file"][$i];

    $resized_img = New Image($file);
    $resized_img->resize($file,400,400);
  }
}

class Image
{
  var $file;

  function __construct($file)
  {
    $this->db = new Database;

    $this->file = $file;
    $this->user = $_SESSION["user_id"];

  }
  private function db_insert_new(){
    $sql = "INSERT INTO `elephant`.`el_images` (`id`, `user_id`, `filename`, `nicename`, `description`)
    VALUES (NULL, ?, ?, '', '')";
    $params = array('is', $this->user, $this->filename);
    if($this->db->query($sql, $params)){
      echo "saved to db";
    };
  }
  public function resize($file,$width,$height){
    $this->file = $file;
    /* Read original image size */
    list($w,$h) = getimagesize($this->file["tmp_name"]);
    /* Calculate new image isze with ratio */
    $ratio = max($width/$w, $height/$h);
    $h = ceil($height / $ratio);
    $x = ($w - $width / $ratio) / 2;
    $w = ceil($width / $ratio);

    if($this->file['type'] == ''){
      $this->file['type'] = exif_imagetype ( $this->file['tmp_name'] );
    }

    $finfo = new finfo(FILEINFO_MIME_TYPE);
    $ext = array_search(
        $finfo->file($this->file['tmp_name']),
        array(
            'jpg' => 'image/jpeg',
            'png' => 'image/png',
            'gif' => 'image/gif',
        ),
        true
    );

    /* only filename */
    $this->filename  = sprintf('%s_%s.%s',
        $width.'x'.$height,
        sha1_file($this->file['tmp_name']),
        $ext
    );
    /* new path */
    $path = sprintf('%s/../uploads/%s',
        dirname(__FILE__),
        $this->filename
    );
    if(file_exists($path)){
      echo 'file already existed';
    }
    else{
    $img_string = file_get_contents($this->file['tmp_name']);
    /* Create image from string */
    $image = imagecreatefromstring($img_string);
    $tmp = imagecreatetruecolor($width, $height);
    imagealphablending($tmp, false);
    imagesavealpha($tmp,true);

    $transparent = imagecolorallocatealpha($tmp, 255, 255, 255, 127);
    imagefilledrectangle($tmp, 0, 0, $width, $height, $transparent);
    imagecopyresampled($tmp, $image,0,0,$x,0,$width,$height,$w,$h);



    /* Save Image */
    switch ($this->file['type']) {
      case 'image/jpeg':
        imagejpeg($tmp, $path, 100);
        break;
      case 'image/png':
        imagepng($tmp, $path, 0);
        break;
      case 'image/gif':
        imagegif($tmp, $path);
        break;
      default:
        exit;
        break;
    }
    var_dump($this->filename);


    if($this->db_insert_new()){
      echo 'new file uploaded';
    };
    }

        echo time();
    return $path;

    /* cleanup memory */
    imagedestroy($image);
    imagedestroy($tmp);
  }
}
?>
