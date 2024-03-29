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
 * @Modified Date : September 30 2011
 * */

/*
 ***********************************************************/
require_once('../../../wp-load.php');
$dbtoken = md5(DB_NAME);
$token = trim($_REQUEST["token"]);

if($dbtoken != $token ){
    die("You are not authorized to access this file");
}
require_once( dirname(__FILE__) . '/macDirectory.php');
$maceditId = $_REQUEST['macEdit'];
$site_url = get_bloginfo('url');
 $uploadDir = wp_upload_dir();
 $path = $uploadDir['basedir'].'/mac-dock-gallery';
 global $wpdb;

if(isset($_REQUEST['importalubmsdelete'])) 
{
	$ok = $_REQUEST['importalubmsdelete'];
	update_option('allowImportOfCurrentAlbs',$ok);
}
 
if(isset($_REQUEST['importalubms']))
{
	$site = $_REQUEST['site'];
	$uIds = $_REQUEST['importalubms'];
	$table = $wpdb->prefix.'macimportalbums';
	switch($site){
		
		case 'picasa' :						
				 $sql = "SELECT accountids , importid FROM  $table  WHERE  importsite = 'picasa' ";
				$picasaAlbums = $wpdb->get_results($sql);
				
				$flag = 1;
				foreach($picasaAlbums as $k => $userIds)
				{
					
					$cmp = strcmp($userIds->accountids ,$uIds );
					if(!$cmp)
					{
						echo  '11'; //success
						update_option('importedTalbleId',$userIds->importid);
						$flag = 0;
						break;
					}
				}
				if($flag)
				echo '22'; // fail
				break;
		case 'flickr' :
			$sql = "SELECT accountids FROM  $table  WHERE  importsite = 'flickr' ";
				$picasaAlbums = $wpdb->get_results($sql);
				$flag = 1;
				$givenids = explode(',', $uIds);
				$one = $givenids[0];
				$two = $givenids[1];
				foreach($picasaAlbums as $k => $userIds)
				{
					$dbids = explode(',' ,$userIds->accountids);
					 if( in_array($one , $dbids) || in_array($two , $dbids))
					{
						echo  '11'; //success
						$flag = 0;
						break;
					}
				}
				if($flag)
				echo '22'; // fail		
			
			
			break; //flickr end hear
		case 'facebook' :
			$sql = "SELECT accountids FROM  $table  WHERE  importsite = 'facebook' ";
				$picasaAlbums = $wpdb->get_results($sql);
				$flag = 1;
				$givenids = explode(',', $uIds);
				$one = $givenids[0];
				$two = $givenids[1];
				foreach($picasaAlbums as $k => $userIds)
				{
					$dbids = explode(',' ,$userIds->accountids);
					 if( in_array($one , $dbids) || in_array($two , $dbids))
					{
						echo  '11'; //success
						$flag = 0;
						break;
					}
				}
				if($flag)
				echo '22'; // fail		
			
			
			break; //facebook	
			
	}
	exit;
}

 else if($_REQUEST['macdeleteId'] != '')
 {
    $macPhoto_id = $_REQUEST['macdeleteId'];
    $photoImg    = $wpdb->get_var("SELECT macPhoto_image FROM " . $wpdb->prefix . "macphotos WHERE macPhoto_id='$macPhoto_id' ");
    $deletePhoto  = $wpdb->get_results("DELETE FROM " . $wpdb->prefix . "macphotos WHERE macPhoto_id='$macPhoto_id'");
    $path = "$path/";
            unlink($path . $photoImg);
            $extense = explode('.', $photoImg);
            unlink($path . $macPhoto_id . '.' . $extense[1]);

 }
  else if(($_REQUEST['macPhoto_desc']) != '')
 {
     $macPhoto_desc = strip_tags($_REQUEST['macPhoto_desc']);          
     $macPhoto_id   = $_REQUEST['macPhoto_id'];
     $sql = $wpdb->query("UPDATE " . $wpdb->prefix . "macphotos SET `macPhoto_desc` = '$macPhoto_desc' WHERE `macPhoto_id` = '$macPhoto_id'");
 echo $macPhoto_desc;
 }
  else if($_REQUEST['macdelAlbum'] != '')
 {
        $macAlbum_id = $_REQUEST['macdelAlbum'];
        $alumImg = $wpdb->get_var("SELECT macAlbum_image FROM " . $wpdb->prefix . "macalbum WHERE macAlbum_id='$macAlbum_id' ");
        $delete = $wpdb->query("DELETE FROM " . $wpdb->prefix . "macalbum WHERE macAlbum_id='$macAlbum_id'");
        $path1 = "$path/";
        unlink($path1.$alumImg);
        $extense = explode('.', $alumImg);
        unlink($path1.$macAlbum_id.'alb.'.$extense[1]);
        //Photos respect to album deleted
        $photos  =$wpdb->get_results("SELECT * FROM " . $wpdb->prefix . "macphotos WHERE macAlbum_id='$macAlbum_id' ");

        foreach ($photos as $albPhotos)
        {

        $macPhoto_id = $albPhotos->macPhoto_id;
        $photoImg    = $wpdb->get_var("SELECT macPhoto_image FROM " . $wpdb->prefix . "macphotos WHERE macPhoto_id='$macPhoto_id' ");
        $deletePhoto  = $wpdb->get_results("DELETE FROM " . $wpdb->prefix . "macphotos WHERE macPhoto_id='$macPhoto_id'");
        $path1 = "$path/";
            unlink($path1 . $photoImg);
            $extense = explode('.', $photoImg);
            unlink($path1 . $macPhoto_id . '.' . $extense[1]);
        }
 }
  else if($_REQUEST['macedit_phtid'] != '')
 {
      $macedit_name = strip_tags($_REQUEST['macedit_name']);
      $macedit_name = preg_replace("/[^a-zA-Z0-9\/_-\s]/", ' ', $macedit_name);
      $macedit_desc = strip_tags($_REQUEST['macedit_desc']);      
      $macedit_id   = $_REQUEST['macedit_phtid'];
      $sql = $wpdb->get_results("UPDATE " . $wpdb->prefix . "macphotos SET `macPhoto_name` = '$macedit_name', `macPhoto_desc` = '$macedit_desc' WHERE `macPhoto_id` = '$macedit_id'");
      echo "success";
 }
?>