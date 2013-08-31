<?php
 /***********************************************************/
/**
 * @name          : Mac Doc Photogallery.
 * @version	      : 2.7
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
?>
<script type="text/javascript" src="<?php echo $site_url; ?>/wp-content/plugins/<?php echo $folder; ?>/js/macGallery.js"></script>
<?php require_once( dirname(__FILE__) . '/macDirectory.php');
$site_url = get_bloginfo('url');
$folder   = dirname(plugin_basename(__FILE__));
// Album Status Change
if($_REQUEST['albid'] != '')
{
    $mac_albId   = $_REQUEST['albid'];
    $mac_albStat = $_REQUEST['status'];
    if($_REQUEST['status'] == 'ON')
    {
       $alumImg = $wpdb->query("UPDATE " . $wpdb->prefix . "macalbum SET macAlbum_status='ON' WHERE macAlbum_id='$mac_albId'");
       echo "<img src='$site_url/wp-content/plugins/$folder/images/tick.png' style='cursor:pointer' width='16' height='16' onclick=macAlbum_status('OFF',$mac_albId)  />";
    }
    else
    {
        $alumImg = $wpdb->query("UPDATE " . $wpdb->prefix . "macalbum SET macAlbum_status='OFF' WHERE macAlbum_id='$mac_albId'");
        echo "<img src='$site_url/wp-content/plugins/$folder/images/publish_x.png' style='cursor:pointer' width='16' height='16' onclick=macAlbum_status('ON',$mac_albId)  />";
    }
   
exit;
}
// Photos status change respect to album
else if($_REQUEST['macPhoto_id'] != '')
{
    $macPhoto_id  = $_REQUEST['macPhoto_id'];
    $mac_photoStat = $_REQUEST['status'];
    if($_REQUEST['status'] == 'ON')
    {
      $photoImg = $wpdb->query("UPDATE " . $wpdb->prefix . "macphotos SET macPhoto_status='ON' WHERE macPhoto_id='$macPhoto_id'");
      echo "<img src='$site_url/wp-content/plugins/$folder/images/tick.png' style='cursor:pointer' width='16' height='16' onclick=macPhoto_status('OFF',$macPhoto_id)  />";
    }
    else
    {
        $photoImg = $wpdb->query("UPDATE " . $wpdb->prefix . "macphotos SET macPhoto_status='OFF' WHERE macPhoto_id='$macPhoto_id'");
        echo "<img src='$site_url/wp-content/plugins/$folder/images/publish_x.png' style='cursor:pointer' width='16' height='16' onclick=macPhoto_status('ON',$macPhoto_id)  />";
    }

}
else if($_REQUEST['macDelid'] != '')
{
    $macPhoto_id = $_REQUEST['macDelid'];
    $photoImg    = $wpdb->get_var("SELECT macPhoto_image FROM " . $wpdb->prefix . "macphotos WHERE macPhoto_id='$macPhoto_id' ");
    $uploadDir = wp_upload_dir();
    $path = $uploadDir['baseurl'];
    $path = "$path/";
                unlink($path . $photoImg);
            $extense = explode('.', $photoImg);
            unlink($path . $macPhoto_id . '.' .$extense[1]);
             $deletePhoto = $wpdb->get_results("DELETE FROM " . $wpdb->prefix . "macphotos WHERE macPhoto_id='$macPhoto_id'");
            echo '';

}
//   For photo edit form
else if($_REQUEST['macPhotoname_id'] != '')
{
    $macPhoto_id = $_REQUEST['macPhotoname_id'];
    $div = '<form name="macPhotoform" method="POST"><td style="margin:0 10px;border:none"><input type="text" name="macPhoto_name_'.$macPhoto_id.'" id="macPhoto_name_'.$macPhoto_id.'" ></td>';
    $div .= '<td colspan="2" style="padding-top:10px;text-align:center;border:none"><input type="submit" name="updatePhoto_name" value="Update" onclick="updPhotoname('.$macPhoto_id.')";></td></form/>' ;
    echo $div;
}

// Add as album cover from the photos
else if ($_REQUEST['macCovered_id'] != '')
{
$macPhotoid  = $_REQUEST['macCovered_id'];
$albumCover  = $_REQUEST['albumCover'];
$albumId     = $_REQUEST['albumId'];
if($albumCover == 'ON')
{
     $albumCover    = $wpdb->query("UPDATE " . $wpdb->prefix . "macphotos SET macAlbum_cover='ON' WHERE macPhoto_id='$macPhotoid' and macAlbum_id='$albumId'");
     $albumCoveroff = $wpdb->query("UPDATE " . $wpdb->prefix . "macphotos SET macAlbum_cover='OFF' WHERE macPhoto_id !='$macPhotoid' and macAlbum_id='$albumId'");
     $photoImg      = $wpdb->get_var("SELECT macPhoto_image FROM " . $wpdb->prefix . "macphotos WHERE macPhoto_id='$macPhotoid' ");
     $addtoAlbum    = $wpdb->query("UPDATE " . $wpdb->prefix . "macalbum SET macAlbum_image='$photoImg' WHERE macAlbum_id='$albumId'");
     echo "<img src='$site_url/wp-content/plugins/$folder/images/tick.png' style='cursor:pointer' width='16' height='16' onclick=macPhoto_status('OFF',$macPhoto_id)  />";
}
}

// update photo name
else if($_REQUEST['macPhoto_name'] != '')
{
     $macPhoto_id =$_REQUEST['macPhotos_id'];
     $macPhoto_name =  addslashes($_REQUEST['macPhoto_name']);
     $sql = $wpdb->get_results("UPDATE " . $wpdb->prefix . "macphotos SET `macPhoto_name` = '$macPhoto_name' WHERE `macPhoto_id` = '$macPhoto_id'");
     echo $macPhoto_name;
}

//Album name edit form
else if($_REQUEST['macAlbumname_id'] != '')
{
    $macAlbum_id = $_REQUEST['macAlbumname_id'];
    $fet_res = $wpdb->get_row("SELECT * FROM  " . $wpdb->prefix . "macalbum WHERE macAlbum_id='$macAlbum_id'");
    $div = '<form name="macUptform" method="POST">
    <div style="margin:0;padding:0;border:none"><input type="text"
           name="macedit_name_'.$macAlbum_id.'" id="macedit_name_'.$macAlbum_id.'" size="15" value="'.$fet_res->macAlbum_name.'" ></div>';

    $div .= '<div><textarea name="macAlbum_desc_'.$macAlbum_id.'"  id="macAlbum_desc_'.$macAlbum_id.'" rows="6" cols="27" >'.$fet_res->macAlbum_description.'</textarea></div>';
//    $div .= '<div style="margin:0;padding:0;border:none"><input type="text"  name="macedit_pageid_'.$macAlbum_id.'"   value="'.$fet_res->macAlbum_pageid.'" id="macedit_pageid_'.$macAlbum_id.'" size="5">';
    $div .='<input type="button"  name="updateMac_name" value="Update" onclick="updAlbname('.$macAlbum_id.')";>
             <input type="button" onclick="CancelAlbum('.$macAlbum_id.')"   value="Cancel">
            </div>';
    $div .= '</form/>' ;
    echo $div;
}


 else if($_REQUEST['macAlbum_id'] != '' )
{
      $macAlbum_id =   $_GET['macAlbum_id'];
      $macAlbum_name = $_GET['macAlbum_name'];
      $macAlbum_desc = $_GET['macAlbum_desc'];
          $sql = $wpdb->get_results("UPDATE " . $wpdb->prefix . "macalbum SET `macAlbum_name`='$macAlbum_name',`macAlbum_description` ='$macAlbum_desc'
    WHERE `macAlbum_id` = '$macAlbum_id'");
            

}
//  Album description update
 else
{
     $macAlbum_desc =  addslashes($_REQUEST['macAlbum_desc']) ;
     $macAlbum_id   = $_REQUEST['macAlbum_id'];
     $sql = $wpdb->query("UPDATE " . $wpdb->prefix . "macalbum SET `macAlbum_description` = '$macAlbum_desc' WHERE `macAlbum_id` = '$macAlbum_id'");
     echo $macAlbum_desc;
}

?>