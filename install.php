<?php
/**
 * @name        Mac Doc Photogallery.
 * @version	2.1: install.php 2011-08-15
 * @package	apptha
 * @subpackage  mac-doc-photogallery
 * @author      saranya
 * @copyright	Copyright (C) 2005 - 2011 Open Source Matters, Inc. All rights reserved.
 * @license	GNU General Public License version 2 or later; see LICENSE.txt
 * @abstract    The Page is for Installing the tables in the Database.
 * */

function macGallery_install()
{
  global $wpdb;
    // set tablename settings, albums, photos
    $table_settings		= $wpdb->prefix . 'macsettings';
    $table_macAlbum		= $wpdb->prefix . 'macalbum';
    $table_macPhotos		= $wpdb->prefix . 'macphotos';
   
    $sfound = false;
    $afound = false;
    $pfound = false;
    $found = true;
    foreach ($wpdb->get_results("SHOW TABLES;", ARRAY_N) as $row)
    {
        if ($row[0] == $table_settings) $sfound = true;
        if ($row[0] == $table_macAlbum) $afound = true;
        if ($row[0] == $table_macPhotos) $pfound = true;
    }

    // add charset & collate like wp core
    $charset_collate = '';

    if ( version_compare(mysql_get_server_info(), '4.1.0', '>=') )
    {
        if ( ! empty($wpdb->charset) )
        $charset_collate = "DEFAULT CHARACTER SET $wpdb->charset";
        if ( ! empty($wpdb->collate) )
        $charset_collate .= " COLLATE $wpdb->collate";
    }
    
          if (!$sfound)
            {
          $sql = "CREATE TABLE ".$table_settings." (
          `macSet_id` int(10) NOT NULL AUTO_INCREMENT PRIMARY KEY,
          `macrow` int(10) NOT NULL,
          `macimg_page` int(10) NOT NULL,
          `summary_macrow` int(5) NOT NULL,
          `summary_page` int(5) NOT NULL,
          `albumRow` bigint(10) NOT NULL,
          `mouseHei` bigint(10) NOT NULL,
          `mouseWid` bigint(10) NOT NULL,
          `resizeHei` bigint(3) NOT NULL,
          `resizeWid` bigint(3) NOT NULL,
          `macProximity` double NOT NULL,
          `macDir` int(10) NOT NULL,
          `macImg_dis` varchar(10) NOT NULL,
          `macAlbum_limit` int(11) NOT NULL,
          `mac_albumdisplay` varchar(5) NOT NULL,
          `mac_imgdispstyle` int(11) NOT NULL,
          `mac_facebook_api` varchar(50) NOT NULL,
          `mac_facebook_comment` int(5) NOT NULL
              ) $charset_collate;";
          $res = $wpdb->get_results($sql);
            }

          if (!$afound)
            {
         $sql = "CREATE TABLE ".$table_macAlbum."  (
          `macAlbum_id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
          `macAlbum_name` varchar(100) NOT NULL,
          `macAlbum_description` text NOT NULL,
          `macAlbum_image` varchar(50) NOT NULL,
          `macAlbum_status` varchar(100) NOT NULL,
          `macAlbum_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
          ) $charset_collate;";
         $res = $wpdb->get_results($sql);
            }

            if (!$pfound)
            {
        $sql = "CREATE TABLE ".$table_macPhotos." (
          `macPhoto_id` int(5) NOT NULL AUTO_INCREMENT PRIMARY KEY,
          `macAlbum_id` int(5) NOT NULL,
          `macAlbum_cover` varchar(10) NOT NULL,
          `macPhoto_name` varchar(50) NOT NULL,
          `macPhoto_desc` text NOT NULL,
          `macPhoto_image` varchar(50) NOT NULL,
          `macPhoto_status` varchar(10) NOT NULL,
          `macPhoto_sorting` int(4) NOT NULL,
          `macPhoto_date` date NOT NULL
           ) $charset_collate;";
         $res = $wpdb->get_results($sql);
            }
        $site_url = get_option('siteurl');  //Getting the site domain path
        
           
 $page_found  = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."posts where post_content='[macGallery]'");
 if (empty($page_found)) {
$mac_gallery_page    =  "INSERT INTO ".$wpdb->prefix."posts(`post_author`, `post_date`, `post_date_gmt`, `post_content`, `post_title`, `post_excerpt`, `post_status`, `comment_status`, `ping_status`, `post_password`, `post_name`, `to_ping`, `pinged`, `post_modified`, `post_modified_gmt`, `post_content_filtered`, `post_parent`, `guid`, `menu_order`, `post_type`, `post_mime_type`, `comment_count`)
        VALUES
                    (1, NOW(), NOW(), '[macGallery]', 'Photos', '', 'publish', 'open', 'open', '', 'mac-gallery', '', '', '2011-01-10 10:42:43',
                    'NOW()', '','', '$site_url/?page_id=',0, 'page', '', 0)";

$res_macpage       =  $wpdb->get_results($mac_gallery_page );
$res_macpage_id    =  $wpdb->get_var("select ID from ".$wpdb->prefix."posts ORDER BY ID DESC LIMIT 0,1");
$upd_macPage       =  "UPDATE ".$wpdb->prefix."posts SET post_parent='$videoId',guid='$site_url/?page_id=$res_macpage_id' WHERE ID='$res_macpage_id'";
$rst_updated       =  $wpdb->get_results($upd_macPage);
 }
}
?>