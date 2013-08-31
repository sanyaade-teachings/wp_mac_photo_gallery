<?php
/**
 * @name        Mac Doc Photogallery.
 * @version	2.2: macdownload.php 2011-08-15
 * @package	apptha
 * @subpackage  mac-doc-photogallery
 * @author      saranya
 * @copyright	Copyright (C) 2005 - 2011 Open Source Matters, Inc. All rights reserved.
 * @license	GNU General Public License version 2 or later; see LICENSE.txt
 * @abstract    Downloading option for the images.
 * */

require_once( dirname(__FILE__) . '/macDirectory.php');

/* Getting the file path */
$site_url = get_bloginfo('url');
$folder = dirname(plugin_basename(__FILE__));

$file = "$site_url/wp-content/uploads/mac-dock-gallery/".$_GET['albid']."";

header("Pragma: public");
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");

header("Content-Type: application/force-download");
header( "Content-Disposition: attachment; filename=".basename($file));

header( "Content-Description: File Transfer");
@readfile($file);
?>