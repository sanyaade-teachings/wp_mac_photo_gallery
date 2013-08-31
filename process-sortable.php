<?php
/**
 * @name        Mac Doc Photogallery.
 * @version	1.0: process-sortable.php 2011-08-15
 * @package	apptha
 * @subpackage  mac-doc-photogallery
 * @author      saranya
 * @copyright	Copyright (C) 2005 - 2011 Open Source Matters, Inc. All rights reserved.
 * @license	GNU General Public License version 2 or later; see LICENSE.txt
 * @abstract    Sorting the photos in admin.
 * */

require_once( dirname(__FILE__) . '/macDirectory.php'); 
/* This is where you would inject your sql into the database
   but we're just going to format it and send it back
*/
$macPhoto_id = $_REQUEST['macPhoto_id'];
foreach ($_GET['listItem'] as $position => $item) :
	$sql[] =$wpdb->query("UPDATE " . $wpdb->prefix . "macphotos SET `macPhoto_sorting` = '$position'  WHERE `macPhoto_id` = $item");
endforeach;

?>