<?php
 /***********************************************************/
/**
 * @name          : Mac Doc Photogallery.
 * @version	      : 2.3
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

require_once( dirname(__FILE__) . '/macDirectory.php');
global $wpdb;
$queue    =  $_REQUEST['queue'];
$albid    = $_REQUEST['albid'];
$site_url = get_bloginfo('url');
$folder   = dirname(plugin_basename(__FILE__));
$album ='';
 $uploadDir = wp_upload_dir();
            $path = $uploadDir['baseurl'].'/mac-dock-gallery';
$res = $wpdb->get_results("SELECT * FROM  " . $wpdb->prefix . "macphotos ORDER BY macPhoto_id DESC LIMIT 0,$queue");
$p = 1;
                                    foreach($res as $results)
                                    {
                                        $phtsrc[$p]['macPhoto_image'] = $results->macPhoto_image;
                                        $phtsrc[$p]['macPhoto_id']    = $results->macPhoto_id;
                                        $phtsrc[$p]['macPhoto_name']  = $results->macPhoto_name;
                                        $phtsrc[$p]['macPhoto_desc']  = $results->macPhoto_desc;
                                        $p++;
                                    }

       $album .= "<div class='left_align' style='color: #21759B'>Following are the list of images that has been uploaded</div>";
       $album .='<ul class="actions"><li><a href="javascript:void(0)" onclick=" upd_disphoto(\''.$queue.'\',\''.$albid.'\');" class="gallery_btn" style="cursor:pointer">Update</a></li></ul>';
       for($i=1;$i<=$queue;$i++)
       {
       $delete_phtid = $phtsrc[$i]['macPhoto_id'];
       $album .= "<div class='left_align' id='photo_delete_$delete_phtid'>";
       $album .='<div style="float:left;margin:0 10px 0 0;display:block;">
                 <img src="'.$path.'/'.$phtsrc[$i]['macPhoto_image'].'" style="height:108px;"/></div><span onclick="macdeletePhoto('.$phtsrc[$i]['macPhoto_id'].')"><a style="cursor:pointer;text-decoration:underline;padding-left:6px;" >Delete</a></span>';
       $album .='<div class="mac_gallery_photos" style="float:left" id="macEdit_'.$i.'">';

       $album .= '<form name="macEdit_'.$phtsrc[$i]['macPhoto_id'].'" method="POST"  class="macEdit">';
       $album .= '<table cellpadding="0" cellspacing="0" width="100%"><tr><td style="margin:0 10px;">Name</td><td style="margin:0 10px;">';
       $album .= '<input type="text" name="macedit_name" id="macedit_name_'.$i.'" value="'.$phtsrc[$i]['macPhoto_name'].'" style="width:100%"></td></tr>';
       $album .= '<tr><td style="margin:0 10px;vertical-align:top">Caption</td><td style="margin:0 10px;">';
       $album .= '<textarea  name="macedit_desc_'.$i.'" id="macedit_desc_'.$i.'" row="10" column="10">'. $phtsrc[$i]['macPhoto_desc'].'</textarea></td></tr></table>';
       $album .= '<tr ><td colspan="2" align="right" style="padding-top:10px;">';
       $album .= '<input type="hidden" name="macedit_id_'.$i.'" id="macedit_id_'.$i.'" value="'.$phtsrc[$i]['macPhoto_id'].'">' ;
       $album .='</form></div>';

       $album .='<div class="clear"></div>';
       $album .='<div><h3 style="margin:0px;padding:3px 0" class="photoName">'.$phtsrc[$i]['macPhoto_name'].'</h3>';
       $album .='</div></div>';
       }
echo $album;
?>