<?php
/**
 * @name        Mac Doc Photogallery.
 * @version	2.2: macfbcomment.php 2011-08-15
 * @package	apptha
 * @subpackage  mac-doc-photogallery
 * @author      saranya
 * @copyright	Copyright (C) 2005 - 2011 Open Source Matters, Inc. All rights reserved.
 * @license	GNU General Public License version 2 or later; see LICENSE.txt
 * @abstract    Facebook comment under each photo.
 * */

require_once( dirname(__FILE__) . '/macDirectory.php');
global $wpdb;
$pid        = $_REQUEST['pid'];
$phtName    = $_REQUEST['phtName'];
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