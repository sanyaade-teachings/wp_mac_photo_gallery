<?php
 /***********************************************************/
/**
 * @name          : Mac Doc Photogallery.
 * @version	      : 3.0
 * @package       : apptha
 * @subpackage    : mac-doc-photogallery
 * @author        : Apptha - http://www.apptha.com
 * @copyright     : Copyright (C) 2011 Powered by Apptha
 * @license	      : GNU General Public License version 2 or later; see LICENSE.txt
 * @abstract      : The core file of calling Mac Photo Gallery.
 * @Creation Date : June 20 2011
 * Edited by 	  : kranthi kumar
 * Email          : kranthikumar@contus.in
 * @Modified Date : Jan 05 2012
 * */
/*
 ***********************************************************/

/* Upload the photos to the album */

require_once('../../../wp-load.php');

$dbtoken = md5(DB_NAME);
$token = trim($_REQUEST["token"]);

if($dbtoken != $token ){
    die("You are not authorized to access this file");
}


class SimpleImage {
   var $image;
   var $image_type;

   function load($filename) {
      $image_info = getimagesize($filename);
      $this->image_type = $image_info[2];
      if( $this->image_type == IMAGETYPE_JPEG ) {
         $this->image = imagecreatefromjpeg($filename);
      } elseif( $this->image_type == IMAGETYPE_GIF ) {
         $this->image = imagecreatefromgif($filename);
      } elseif( $this->image_type == IMAGETYPE_PNG ) {
         $this->image = imagecreatefrompng($filename);
      }
   }
   function save($filename, $image_type=IMAGETYPE_JPEG, $compression=75, $permissions=null) {
      if( $image_type == IMAGETYPE_JPEG ) {
         imagejpeg($this->image,$filename,$compression);
      } elseif( $image_type == IMAGETYPE_GIF ) {
         imagegif($this->image,$filename);
      } elseif( $image_type == IMAGETYPE_PNG ) {
         imagepng($this->image,$filename);
      }
      if( $permissions != null) {
         chmod($filename,$permissions);
      }
   }
   function output($image_type=IMAGETYPE_JPEG) {
      if( $image_type == IMAGETYPE_JPEG ) {
         imagejpeg($this->image);
      } elseif( $image_type == IMAGETYPE_GIF ) {
         imagegif($this->image);
      } elseif( $image_type == IMAGETYPE_PNG ) {
         imagepng($this->image);
      }
   }
   function getWidth() {
      return imagesx($this->image);
   }
   function getHeight() {
      return imagesy($this->image);
   }
   function resizeToHeight($height) {
      $ratio = $height / $this->getHeight();
      $width = $this->getWidth() * $ratio;
      $this->resize($width,$height);
   }
   function resizeToWidth($width) {
      $ratio = $width / $this->getWidth();
      $height = $this->getheight() * $ratio;
      $this->resize($width,$height);
   }
   function scale($scale) {
      $width = $this->getWidth() * $scale/100;
      $height = $this->getheight() * $scale/100;
      $this->resize($width,$height);
   }
   function resize($width,$height) {
      $new_image = imagecreatetruecolor($width, $height);
      imagecopyresampled($new_image, $this->image, 0, 0, 0, 0, $width, $height, $this->getWidth(), $this->getHeight());
      $this->image = $new_image;
   }
}


  

// let's load WordPress



require_once( dirname(__FILE__) . '/macDirectory.php');
global $wpdb;


 $albumId = $_REQUEST['albumId'];
$uploadDir = wp_upload_dir();
$path = $uploadDir['basedir'].'/mac-dock-gallery';
if($albumId !='')
{
$uploaddir = "$path/";
$file = $uploaddir . basename($_FILES['uploadfile']['name']);
$size=$_FILES['uploadfile']['size'];
$typeinfo = explode(".",$_FILES['uploadfile']['name']);
$type =  strtolower($typeinfo[count($typeinfo)-1]);
$allowExt  = array("jpg","jpeg","png","gif");
if(!in_array($type,$allowExt)){
    echo "not an allowed extension";
    unlink($_FILES['uploadfile']['tmp_name']);
    exit;
}
if($size>10485760)
{
	echo "error file size > 1 MB";
	unlink($_FILES['uploadfile']['tmp_name']);
	exit;
}
  $image = new SimpleImage();
   $image->load($_FILES['uploadfile']['tmp_name']);

if (move_uploaded_file($_FILES['uploadfile']['tmp_name'], $file) && $albumId !='0')
{
     $macimage = $_FILES['uploadfile']['name'];
     $revers = strrev($macimage);  //for get image extension 
     $macname123 = explode('.',$revers);
      $imgExteType = strrev($macname123[0]);
      $macname = explode('.',$macimage);
      
     $random_digit=rand(0000,9999);
     $storing_macname = addslashes($macname[0]);
     $uploadDb =  $wpdb->query("INSERT INTO ". $wpdb->prefix."macphotos (`macAlbum_id`,`macPhoto_name`, `macPhoto_desc`, `macPhoto_image`, `macPhoto_status`, `macPhoto_sorting`,`macPhoto_date`)
       VALUES ('$albumId','$storing_macname', '', '$macimage', 'ON', '',NOW())");
       $lastid = $wpdb->insert_id;
       $album_image = $wpdb->get_var("select macPhoto_image from " . $wpdb->prefix . "macphotos WHERE macPhoto_id='$lastid'");
       $filenameext = explode('.',$album_image);
       $filenameextcount = count($filenameext);
       $_thumb_random_digit=rand(0000,9999);
                $thumbfile = $lastid . "_thumb_" .$_thumb_random_digit.'.'.$imgExteType;
                $bigfile = $lastid.'.'.$imgExteType;
                $path = $uploaddir.$album_image;
                define(contus, "$uploaddir/");
                $macSetting = $wpdb->get_row("SELECT * FROM ". $wpdb->prefix."macsettings");
//                $twidth = $macSetting->resizeWid;
//                $theight =$macSetting->resizeHei;

                 $twidth  = $macSetting->mouseWid+50;
                 $theight = $macSetting->mouseWid+50;
                /* create Big image and save */
                $imgwidth = $image->getWidth();
                $imgheight =$image->getHeight();
               
                  $image->save($uploaddir . $bigfile);
                /* create thumb image and save */
                  $image->resize($twidth,$theight);
                  $image->save($uploaddir . $thumbfile);
               /* reload for resizing image for our slideshow*/

                $upd = $wpdb->query("UPDATE " . $wpdb->prefix . "macphotos SET macPhoto_image='$thumbfile',`macPhoto_sorting`='$lastid' WHERE macPhoto_id=$lastid");
}
else
{
        echo "error ".$_FILES['uploadfile']['error']." --- ".$_FILES['uploadfile']['tmp_name']." %%% ".$file."($size)";
}

}
?>