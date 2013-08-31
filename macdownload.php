<?php
/*
 ***********************************************************/
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
 * Edited by 	  : kranthi kumar
 * Email          : kranthikumar@contus.in
 * @Modified Date : Jan 05 2012
 * */

/*
 ***********************************************************/

require_once( dirname(__FILE__) . '/macDirectory.php');

$folder = dirname(plugin_basename(__FILE__));
$filepart = explode(".",$_GET['albid']);
$file = dirname(dirname(dirname(__FILE__)))."/uploads/mac-dock-gallery/".$_GET['albid'];

$fileExt = '';
$allowedExtensions = array("jpg", "jpeg", "png", "gif");
 if(preg_grep( "/$filepart[1]/i" , $allowedExtensions )){
 	$fileExt = true;
 }

if(file_exists($file) && (count($filepart) === 2) && (is_int( (int)$filepart[0]) ) && ((int)$filepart[0] > 0) && $fileExt == '1'){

    header('Content-Description: File Transfer');
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename='.basename($file));
    header('Content-Transfer-Encoding: binary');
    header('Expires: 0');
    header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
    header('Pragma: public');
    header('Content-Length: ' . filesize($file));
    ob_clean();
    flush();
    readfile($file);
}
else{
    die("No direct access");
}
?>