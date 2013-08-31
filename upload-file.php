<?php
/**
 * @name        Mac Doc Photogallery.
 * @version	2.2: upload-file.php 2011-08-15
 * @package	apptha
 * @subpackage  mac-doc-photogallery
 * @author      saranya
 * @copyright	Copyright (C) 2005 - 2011 Open Source Matters, Inc. All rights reserved.
 * @license	GNU General Public License version 2 or later; see LICENSE.txt
 * @abstract    Upload the photos to the album.
 * */

/* Upload the photos to the album */

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

require_once( dirname(__FILE__) . '/macDirectory.php');
global $wpdb;
echo $albumId = $_REQUEST['albumId'];
$uploadDir = wp_upload_dir();
$path = $uploadDir['basedir'].'/mac-dock-gallery';
if($albumId !='')
{
$uploaddir = "$path/";
$file = $uploaddir . basename($_FILES['uploadfile']['name']);
$size=$_FILES['uploadfile']['size'];
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
     $macname = explode('.',$macimage);
     $uploadDb =  $wpdb->query("INSERT INTO ". $wpdb->prefix."macphotos (`macAlbum_id`,`macPhoto_name`, `macPhoto_desc`, `macPhoto_image`, `macPhoto_status`, `macPhoto_sorting`,`macPhoto_date`)
       VALUES ('$albumId','$macname[0]', '', '$macimage', 'ON', '',NOW())");
       $lastid = $wpdb->insert_id;
       $album_image = $wpdb->get_var("select macPhoto_image from " . $wpdb->prefix . "macphotos WHERE macPhoto_id='$lastid'");
       $filenameext = explode('.',$album_image);
       $filenameextcount = count($filenameext);
                $thumbfile = $lastid . "_thumb." . $filenameext[(int) $filenameextcount - 1];
                $bigfile = $lastid . "." . $filenameext[(int) $filenameextcount - 1];
                $path = $uploaddir.$album_image;
                define(contus, "$uploaddir/");
                $macSetting = $wpdb->get_row("SELECT * FROM ". $wpdb->prefix."macsettings");
                $twidth = $macSetting->resizeWid;
                $theight =$macSetting->resizeHei;
                /* create Big image and save */
                $imgwidth = $image->getWidth();
                $imgheight =$image->getHeight();
                if($imgwidth >= '700' && $imgheight >= '500')
                {
                    $image->resizeToWidth(700);
                    $image->resizeToHeight(500);
                    $image->resize(700,500);
                }
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