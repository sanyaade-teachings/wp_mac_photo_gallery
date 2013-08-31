<?php
/**
 * @name        Mac Doc Photogallery.
 * @version	2.0: macalbum.php 2011-08-15
 * @package	apptha
 * @subpackage  mac-doc-photogallery
 * @author      saranya
 * @copyright	Copyright (C) 2005 - 2011 Open Source Matters, Inc. All rights reserved.
 * @license	GNU General Public License version 2 or later; see LICENSE.txt
 * @abstract    Add Album Page.
 * */
require_once( dirname(__FILE__) . '/macDirectory.php');

class macManage {


    function macManage() {

        if ($_REQUEST['action'] == 'editAlbum') {
            updateAlbum();
        } else {
            controller();
        }
    }
}
class simpleimage {

    var $image;
    var $image_type;

    function loads($filename) {

        $image_info = getimagesize($filename);
        $this->image_type = $image_info[2];
        if ($this->image_type == IMAGETYPE_JPEG) {
            $this->image = imagecreatefromjpeg($filename);
        } elseif ($this->image_type == IMAGETYPE_GIF) {
            $this->image = imagecreatefromgif($filename);
        } elseif ($this->image_type == IMAGETYPE_PNG) {
            $this->image = imagecreatefrompng($filename);
        }
    }

    function save($filename, $image_type=IMAGETYPE_JPEG, $compression=75, $permissions=null) {
        if ($image_type == IMAGETYPE_JPEG) {
            imagejpeg($this->image, $filename, $compression);
        } elseif ($image_type == IMAGETYPE_GIF) {
            imagegif($this->image, $filename);
        } elseif ($image_type == IMAGETYPE_PNG) {
            imagepng($this->image, $filename);
        }
        if ($permissions != null) {
            chmod($filename, $permissions);
        }
    }

    function output($image_type=IMAGETYPE_JPEG) {
        if ($image_type == IMAGETYPE_JPEG) {
            imagejpeg($this->image);
        } elseif ($image_type == IMAGETYPE_GIF) {
            imagegif($this->image);
        } elseif ($image_type == IMAGETYPE_PNG) {
            imagepng($this->image);
        }
    }

    function getWidth() {
        return imagesx($this->image);
    }

    function getHeight() {
        return imagesy($this->image);
    }

    function resizeToHeight($height) {
        $ratio = $height / $this->getHeight();
        $width = $this->getWidth() * $ratio;
        $this->resize($width, $height);
    }

    function resizeToWidth($width) {
        $ratio = $width / $this->getWidth();
        $height = $this->getheight() * $ratio;
        $this->resize($width, $height);
    }

    function scale($scale) {
        $width = $this->getWidth() * $scale / 100;
        $height = $this->getheight() * $scale / 100;
        $this->resize($width, $height);
    }

    /* resizing an image (crop an image) */

    function resize($width, $height) {
        $imgwidth = $this->getWidth();
        $imgheight = $this->getHeight();
        $source_aspect_ratio = $imgwidth / $imgheight;
        $desired_aspect_ratio = $width / $height;

        /* Triggered when source image is wider */
        if ($source_aspect_ratio > $desired_aspect_ratio) {
            $temp_height = $height;
            $temp_width = (int) ( $height * $source_aspect_ratio );
        } else { /* Triggered otherwise (i.e. source image is similar or taller) */
            $temp_width = $width;
            $temp_height = (int) ( $width / $source_aspect_ratio );
        }

        /* Resize the image into a temporary image */
        $temp_gdim = imagecreatetruecolor($temp_width, $temp_height);
        imagecopyresampled($temp_gdim, $this->image, 0, 0, 0, 0, $temp_width, $temp_height, $imgwidth, $imgheight);

        /* Copy cropped region from temporary image into the desired image */
        $x0 = ( $temp_width - $width ) / 2;
        $y0 = ( $temp_height - $height ) / 2;

        $desired_gdim = imagecreatetruecolor($width, $height);
        imagecopy($desired_gdim, $temp_gdim, 0, 0, $x0, $y0, $width, $height);
        $this->image = $desired_gdim;
    }

}

function controller() {
    global $wpdb, $site_url, $folder;
    $site_url = get_bloginfo('url');
    $folder   = dirname(plugin_basename(__FILE__));
    $pageURL  = $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"];
         $split_title = $wpdb->get_var("SELECT option_value FROM ".$wpdb->prefix."options WHERE option_name='get_title_key'");
         $get_title = unserialize($split_title);
            $strDomainName = $site_url;
            preg_match("/(?P<domain>[a-z0-9][a-z0-9\-]{1,63}\.[a-z\.]{2,6})$/i", $strDomainName, $matches);
            $customerurl = $matches['domain'];
            $customerurl = str_replace("www.", "", $customerurl);
            $customerurl = str_replace(".", "D", $customerurl);
            $customerurl = strtoupper($customerurl);
            $get_url     = macgal_generate($customerurl);
    $macSet   = $wpdb->get_row("SELECT * FROM " . $wpdb->prefix . "macsettings");
    if (isset($_REQUEST['macAlbum_submit']))
        {

          if($get_title['title'] == $get_url)
          {
            $macAlbum_name        = $_REQUEST['macAlbum_name'];
        $macAlbum_description = $_REQUEST['macAlbum_description'];
        $macAlbum_pageid      = $_REQUEST['macAlbum_pageid'];
        $current_image        = $_FILES['macAlbum_image']['name'];
        if ($current_image == '')
        {
            $sql = $wpdb->query("INSERT INTO " . $wpdb->prefix . "macalbum
                    (`macAlbum_name`, `macAlbum_description`,`macAlbum_image`,`macAlbum_status`,`macAlbum_date`) VALUES
                    ('$macAlbum_name', '$macAlbum_description', '','ON',NOW())");
        } else {
           echo $extension = substr(strrchr($current_image, '.'), 1);
            if (($extension != "jpg") && ($extension != "JPEG") && ($extension != 'JPG') && ($extension != "png") && ($extension != 'gif')&& ($extension != 'jpeg')) {
                die('Unknown extension');
            }
            $destination = '../wp-content/plugins/'.$folder.'/uploads/' . $current_image;
            $action = move_uploaded_file($_FILES['macAlbum_image']['tmp_name'], $destination);
            if (!$action) {
                die('File copy failed');
            } else {
                echo "File copy successful";
            }

            $sql = $wpdb->query("INSERT INTO " . $wpdb->prefix . "macalbum
           (`macAlbum_name`, `macAlbum_description`,`macAlbum_image`, `macAlbum_status`,`macAlbum_date`)
           VALUES ('$macAlbum_name', '$macAlbum_description', '$current_image','ON',NOW())");
            $lastid = $wpdb->insert_id;
            $album_image = $wpdb->get_var("select macAlbum_image from " . $wpdb->prefix . "macalbum WHERE macAlbum_id='$lastid'");
            $filenameext = explode('.', $album_image);
            $filenameextcount = count($filenameext);
            $thumbfile = $lastid . "_thumbalb." . $filenameext[(int) $filenameextcount - 1];
            $bigfile = $lastid . "alb." . $filenameext[(int) $filenameextcount - 1];
            $path = "../wp-content/plugins/$folder/uploads/" . $album_image;
            define(contus, "../wp-content/plugins/$folder/uploads/");
            /* create an object to call the required image to resize */
            $image = new simpleimage();
            $image->loads($path);
            $twidth = $macSet->resizeWid;
            $theight = $macSet->resizeHei;
            /* create thumb image and save */
            $image->resize($twidth + $theight, $twidth + $theight);
            $image->save(contus . $thumbfile);
            /* reload for resizing image for our slideshow */
            $image->loads($path);
            if (file_exists(contus . $bigfile))
                unlink(contus . $bigfile);
            rename($path, contus . $bigfile);
            $upd = $wpdb->query("UPDATE " . $wpdb->prefix . "macalbum SET macAlbum_image='$thumbfile' WHERE macAlbum_id=$lastid");
        }
      }
    }

    if (isset($_REQUEST['doaction_album']))
     {
        if (isset($_REQUEST['action_album']) == 'delete')
         {
            for ($i = 0; $i < count($_POST['checkList']); $i++)
            {
                $macAlbum_id = $_POST['checkList'][$i];
                $alumImg = $wpdb->get_var("SELECT macAlbum_image FROM " . $wpdb->prefix . "macalbum WHERE macAlbum_id='$macAlbum_id' ");
                $delete = $wpdb->query("DELETE FROM " . $wpdb->prefix . "macalbum WHERE macAlbum_id='$macAlbum_id'");
                define(upload, "../wp-content/plugins/$folder/uploads/");

                //unlink(upload . $macAlbum_id . 'alb.' . $extense[1]);

                $phtImg = $wpdb->get_results("SELECT macPhoto_image FROM " . $wpdb->prefix . "macphotos WHERE macAlbum_id='$macAlbum_id'");

                foreach($phtImg as $phtImgs)
                {
                unlink(upload.$phtImgs->macPhoto_image);
                $extense  = explode('.', $phtImgs->macPhoto_image);
                $phtalbid = explode('_',$extense[0]);
                unlink(upload.$phtalbid[0]. '.' . $extense[1]);
                }

                $deletePht = $wpdb->query("DELETE FROM " . $wpdb->prefix . "macphotos WHERE macAlbum_id='$macAlbum_id'");

            }
        }
    }
?>
    <link rel='stylesheet' href='<?php echo $site_url; ?>/wp-content/plugins/<?php echo $folder ?>/css/style.css' type='text/css' />
    <script type="text/javascript" src="<?php echo $site_url; ?>/wp-content/plugins/<?php echo $folder; ?>/js/jquery.js"></script>
    <script type="text/javascript" src="<?php echo $site_url; ?>/wp-content/plugins/<?php echo $folder; ?>/js/macGallery.js"></script>

    <script type="text/javascript" src="<?php echo $site_url; ?>/wp-content/plugins/<?php echo $folder; ?>/js/main.js" ></script>
    <script type="text/javascript">

        var site_url = '<?php echo $site_url; ?>';
        var url = '<?php echo $site_url; ?>';
        var mac_folder = '<?php echo $folder; ?>';
        var pages  = '<?php echo $_REQUEST['pages']; ?>';
        var get_title = '<?php echo $get_title['title'];?>';
        var title_value = '<?php echo $get_url ?>';
        macAlbum(pages)

    </script>
    <script src="<?php echo $site_url . '/wp-content/plugins/'.$folder.'/js/jquery-pack.js'; ?>" type="text/javascript"></script>
<link href="<?php echo $site_url . '/wp-content/plugins/'.$folder.'/css/facebox.css';?>" media="screen" rel="stylesheet" type="text/css" />
<script src="<?php echo $site_url . '/wp-content/plugins/'.$folder.'/js/facebox.js';?>" type="text/javascript"></script>
<script type="text/javascript">

    $(document).ready(function($) {
      $('a[rel*=facebox]').facebox()
    })
        function check_all(frm, chAll)
        {
            var i=0;
            comfList = document.forms[frm].elements['checkList[]'];
            checkAll = (chAll.checked)?true:false; // what to do? Check all or uncheck all.
            // Is it an array
            if (comfList.length) {
                if (checkAll) {
                    for (i = 0; i < comfList.length; i++) {
                        comfList[i].checked = true;
                    }
                }
                else {
                    for (i = 0; i < comfList.length; i++) {
                        comfList[i].checked = false;
                    }
                }
            }
            else {
                /* This will take care of the situation when your
    checkbox/dropdown list (checkList[] element here) is dependent on
                a condition and only a single check box came in a list.
                 */
                if (checkAll) {
                    comfList.checked = true;
                }
                else {
                    comfList.checked = false;
                }
            }

            return;
        }
        var dragdr = jQuery.noConflict();
        jQuery(function(){
            dragdr("#macAlbum_submit").click(function() {
                // Made it a local variable by using "var"
                var macAlbum_name = document.getElementById("macAlbum_name").value;
                if(macAlbum_name == ""){
                    document.getElementById("error_alb").innerHTML = 'Please Enter the Album ';
                    return false;
                }
                else if(get_title != title_value)
                    {
                        alert('This is a free version so, you will not be able to add new albums. Please click on Buy now button to purchase license key for your domain.');
                    }

            });
        });
    </script>
<?php
$options = get_option('get_title_key');
if ( !is_array($options) )
{
  $options = array('title'=>'', 'show'=>'', 'excerpt'=>'','exclude'=>'');
}
if(isset($_POST['submit_license']))
    {
       $options['title'] = strip_tags(stripslashes($_POST['get_license']));

       update_option('get_title_key', $options);
    }
?>
    <div class="wrap nosubsub"><div id="icon-upload" class="icon32"><br /></div>
        <h2 class="nav-tab-wrapper">
        <a href="?page=macAlbum" class="nav-tab nav-tab-active">Albums</a>
        <a href="?page=macPhotos&albid=0" class="nav-tab">Photos</a>
        <a href="?page=macSettings" class="nav-tab">Settings</a></h2>
        <div style="background-color: #ffffff;padding: 10px;margin-top:10px;border: #ccc 1px solid">
        <strong> Note : </strong> Adding macGallery Photos of a particular album in your post/page, or displaying all
    the albums can be easily done by adding a simple code there. <br/><br />
       (i)In case you want to insert a particular photo album into your post or page,
       you can do it easily by following the example:<br />
        <strong>[macGallery albid=1 row=3 cols=3]</strong>.
        (ii) Else you want the full gallery in Post/ Page put this code <strong>[macGallery]</strong>
          </div>
         <h3 style="float:left;width:200px">Add New Album</h3>

         <?php
         if($get_title['title'] != $get_url)
        {
        ?>
         <p><a href="#mydiv" rel="facebox"><img src="<?php echo $site_url . '/wp-content/plugins/'.$folder.'/images/licence.png'?>" align="right"></a>
    <a href="http://www.apptha.com/category/extension/Wordpress/Mac-Photo-Gallery" target="_blank"><img src="<?php echo $site_url . '/wp-content/plugins/'.$folder.'/images/buynow.png'?>" align="right"></a>
</p>
<div id="mydiv" style="display:none">
<form method="POST" action="">
    <h2 align="center"> Apply Your License Key Here</h2>
   <div align="right"><input type="text" name="get_license" id="get_license" size="58" />
   <input type="submit" name="submit_license" id="submit_license" value="Save"/></div>
</form>
</div>
<?php } //else { ?>
 <div class="clear"></div>
 <div name="form_album" name="left_content" class="left_column">
            <form name="macAlbum" method="POST" id="macAlbum" enctype="multipart/form-data"  ><div class="form-wrap">

                    <div class="form-macAlbum">
                        <label for="macAlbum_name">Album Name</label>
                        <input name="macAlbum_name" id="macAlbum_name" type="text" value="" size="40" aria-required="true" />
                        <div id="error_alb" style="color:red"></div>
                        <p><?php _e('The album name is how it appears on your site.'); ?></p>
                    </div>

                    <div class="form-macAlbum">
                        <label for="macAlbum_description">Album Description</label>
                        <textarea name="macAlbum_description" id="macAlbum_description" rows="5" cols="30"></textarea>
                        <p><?php _e('The description is for the album.'); ?></p>
                    </div>


                <div class="form-macAlbum">
                    <label for="macAlbum_image">Album Image</label>
                    <input type="file" name="macAlbum_image" id="macAlbum_image">
                    <p><?php _e('Upload Image for the album.'); ?></p>
                </div>

                <p class="submit"><input type="submit" class="button" name="macAlbum_submit" id="macAlbum_submit" value="<?php echo 'Add new Album'; ?>" /></p>
            </div></form>
    </div>
<?php //} ?>
    <div name="right_content" class="right_column">
                        <form name="all_action"  action="" method="POST"><div class="alignleft actions">
                        <select name="action_album">
                            <option value="" selected="selected"><?php _e('Bulk Actions'); ?></option>
                            <option value="delete"><?php _e('Delete'); ?></option>
                        </select>
                        <input type="submit" value="<?php esc_attr_e('Apply'); ?>" name="doaction_album" id="doaction_album" class="button-secondary action" />
                <?php wp_nonce_field('bulk-tags'); ?>
            </div>

            <div id="bind_macAlbum" name="right_content" ></div></form>
    </div>

</div>
<?php
   }
?>
