<?php
/**
 * @name        Mac Doc Photogallery.
 * @version	2.1: macphtajax.php 2011-08-15
 * @package	apptha
 * @subpackage  mac-doc-photogallery
 * @author      saranya
 * @copyright	Copyright (C) 2005 - 2011 Open Source Matters, Inc. All rights reserved.
 * @license	GNU General Public License version 2 or later; see LICENSE.txt
 * @abstract    Ajax Returning  photos page.
 * */

require_once( dirname(__FILE__) . '/macDirectory.php');
$maceditId = $_REQUEST['macEdit'];
$site_url = get_bloginfo('url');
?>
<?php
 if($_REQUEST['macdeleteId'] != '')
 {
    $macPhoto_id = $_REQUEST['macdeleteId'];
    $photoImg    = $wpdb->get_var("SELECT macPhoto_image FROM " . $wpdb->prefix . "macphotos WHERE macPhoto_id='$macPhoto_id' ");
    $deletePhoto  = $wpdb->get_results("DELETE FROM " . $wpdb->prefix . "macphotos WHERE macPhoto_id='$macPhoto_id'");
    $path = "./uploads/";
                unlink($path . $photoImg);
            $extense = explode('.', $photoImg);
            unlink($path . $macPhoto_id . '.' . $extense[1]);

 }
  else if(($_REQUEST['macPhoto_desc']) != '')
 {
     $macPhoto_desc = $_REQUEST['macPhoto_desc'] ;
     $macPhoto_id   = $_REQUEST['macPhoto_id'];
     $sql = $wpdb->query("UPDATE " . $wpdb->prefix . "macphotos SET `macPhoto_desc` = '$macPhoto_desc' WHERE `macPhoto_id` = '$macPhoto_id'");
 echo $macPhoto_desc;
 }
  else if($_REQUEST['macdelAlbum'] != '')
 {
        $macAlbum_id = $_REQUEST['macdelAlbum'];
        $alumImg = $wpdb->get_var("SELECT macAlbum_image FROM " . $wpdb->prefix . "macalbum WHERE macAlbum_id='$macAlbum_id' ");
        $delete = $wpdb->query("DELETE FROM " . $wpdb->prefix . "macalbum WHERE macAlbum_id='$macAlbum_id'");
        $path = './uploads/';
        unlink($path.$alumImg);
        $extense = explode('.', $alumImg);
        unlink($path.$macAlbum_id.'alb.'.$extense[1]);
        //Photos respect to album deleted
        $photos  =$wpdb->get_results("SELECT * FROM " . $wpdb->prefix . "macphotos WHERE macAlbum_id='$macAlbum_id' ");

        foreach ($photos as $albPhotos)
        {

        $macPhoto_id = $albPhotos->macPhoto_id;
        $photoImg    = $wpdb->get_var("SELECT macPhoto_image FROM " . $wpdb->prefix . "macphotos WHERE macPhoto_id='$macPhoto_id' ");
        $deletePhoto  = $wpdb->get_results("DELETE FROM " . $wpdb->prefix . "macphotos WHERE macPhoto_id='$macPhoto_id'");
        $path = "./uploads/";
            unlink($path . $photoImg);
            $extense = explode('.', $photoImg);
            unlink($path . $macPhoto_id . '.' . $extense[1]);
        }
 }
  else if($_REQUEST['macedit_phtid'] != '')
 {
      $macedit_name = $_REQUEST['macedit_name'];
      $macedit_desc = $_REQUEST['macedit_desc'];
      $macedit_id   = $_REQUEST['macedit_phtid'];
      $sql = $wpdb->get_results("UPDATE " . $wpdb->prefix . "macphotos SET `macPhoto_name` = '$macedit_name', `macPhoto_desc` = '$macedit_desc' WHERE `macPhoto_id` = '$macedit_id'");
      
 }
?>