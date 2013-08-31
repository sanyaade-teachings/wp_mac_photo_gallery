<?php
/*
 ***********************************************************/
/**
 * @name          : Mac Doc Photogallery.
 * @version	      : 2.5
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
$pid        = $_REQUEST['pid'];
$phtName    = stripslashes($_REQUEST['phtName']);
$site_url   = $_REQUEST['site_url'];
$dirPage    = $_REQUEST['folder'];
$returnfbid =$wpdb->get_var("SELECT ID FROM " . $wpdb->prefix . "posts WHERE post_content= '[fbmaccomments]' AND post_status='publish'");
$mac_facebook_comment = $wpdb->get_var("SELECT mac_facebook_comment FROM " . $wpdb->prefix . "macsettings WHERE macSet_id=1");
if($pid != '')
{
        $site_url = $_REQUEST['site_url'];
        $div .= '<div id="fbcomments">
                 <fb:comments canpost="true" candelete="false" numposts="'.$mac_facebook_comment.'"  xid="'.$pid.'"
                     href="'.$site_url.'/?page_id='.$returnfbid.'&macphid='.$pid.'"  title="'.$phtName.'"  publish_feed="true">
                 </fb:comments></div>';
        echo  $div;
}
?>