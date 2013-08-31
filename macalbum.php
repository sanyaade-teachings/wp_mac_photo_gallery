<?php
/*
 ***********************************************************/
/**
 * @name          : Mac Doc Photogallery.
 * @version	      : 2.8
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
        preg_match("/^(http:\/\/)?([^\/]+)/i", $strDomainName, $subfolder);
        preg_match("/(?P<domain>[a-z0-9][a-z0-9\-]{1,63}\.[a-z\.]{2,6})$/i", $subfolder[2], $matches);
        $customerurl = $matches['domain'];
        $customerurl = str_replace("www.", "", $customerurl);
        $customerurl = str_replace(".", "D", $customerurl);
        $customerurl = strtoupper($customerurl);
        $get_key     = macgal_generate($customerurl);
    $macSet   = $wpdb->get_row("SELECT * FROM " . $wpdb->prefix . "macsettings");
    $mac_album_count = $wpdb->get_var("SELECT count(*) FROM " . $wpdb->prefix . "macalbum");
   // echo "<pre>";print_r($_REQUEST);echo "</pre>";

    if (isset($_REQUEST['doaction_album']))
     {
        if (isset($_REQUEST['action_album']) == 'delete')
         {
            for ($i = 0; $i < count($_POST['checkList']); $i++)
            {
            	$albIdVal = is_numeric($_POST['checkList'][$i]);
            	
            	if($albIdVal)
            	{
                $macAlbum_id = $_POST['checkList'][$i];
                $alumImg = $wpdb->get_var("SELECT macAlbum_image FROM " . $wpdb->prefix . "macalbum WHERE macAlbum_id='$macAlbum_id' ");
                $delete = $wpdb->query("DELETE FROM " . $wpdb->prefix . "macalbum WHERE macAlbum_id='$macAlbum_id'");
                //define(upload, "$path/");

                //unlink(upload . $macAlbum_id . 'alb.' . $extense[1]);

                $phtImg = $wpdb->get_results("SELECT macPhoto_id , macPhoto_image FROM " . $wpdb->prefix . "macphotos WHERE macAlbum_id='$macAlbum_id'");
                $uploadDir = wp_upload_dir();
                $path = $uploadDir['basedir'].'/mac-dock-gallery';
            
                foreach($phtImg as $phtImgs)
                {
                	$photois = $path.'/'.$phtImgs->macPhoto_image;
                if(file_exists($photois))
                {	
                	unlink($photois);
                }	
                $imgName = $phtImgs->macPhoto_image;
                $bigImgName = explode('.', $imgName);
                $bigImg = $path.'/'.$phtImgs->macPhoto_id.'.'.$bigImgName[1];
               
	               if(file_exists($filename))
	               {
	               		 unlink($bigImg); 
	               }
                }//for loop end hear

                $deletePht = $wpdb->query("DELETE FROM " . $wpdb->prefix . "macphotos WHERE macAlbum_id='$macAlbum_id'");
            	}//if end hear
            	else{
            		 echo $albIdVal = (string)$_POST['checkList'][$i];
            		 
            		 $albTypeAndId = explode('-', $albIdVal);
            		
            		 $albType = $albTypeAndId[0];
            		  $albId = $albTypeAndId[1];
            		 switch($albType)
            		 {
            		 	case 333 : //facebook
            		 				$macFacebookAlbums = get_option('macFacebookAlbums');
            		 				unset($macFacebookAlbums[$albId]);
            		 				update_option('macFacebookAlbums',$macFacebookAlbums);
            		 				
            		 				break;
            		 				
            		 	case 222 :  //picasa
            		 				  $picaalbphotos = get_option('macalbumPhotosList');
            		 				 unset($picaalbphotos[$albId]);
            		 				  update_option('macalbumPhotosList',$picaalbphotos);
            		 				 //echo "<pre>";print_r($picaalbphotos);echo "</pre>";exit;
            		 				
            		 				
            		 				break;
            		 	case 111 :   //flickr  
            		 				$macflickrAlbDetailList = get_option('macflickrAlbDetailList');
            		 				unset($macflickrAlbDetailList[$albId]);
            		 				update_option('macflickrAlbDetailList',$macflickrAlbDetailList);
            		 				break;
            		 					
            		 	
            		 } 
            		
            	}
            }
            $msg = 'Album/s Deleted Successfully';
        }
    }
?>
    <link rel='stylesheet' href='<?php echo $site_url; ?>/wp-content/plugins/<?php echo $folder ?>/css/style.css' type='text/css' />
    <script type="text/javascript" src="<?php echo $site_url; ?>/wp-content/plugins/<?php echo $folder; ?>/js/jquery.js"></script>
    <script type="text/javascript" src="<?php echo $site_url; ?>/wp-content/plugins/<?php echo $folder; ?>/js/macGallery.js"></script>
    <script type="text/javascript" src="<?php echo $site_url; ?>/wp-content/plugins/<?php echo $folder; ?>/js/mac_preview.js" ></script>
    <script type="text/javascript">

        var site_url = '<?php echo $site_url; ?>';
        var url = '<?php echo $site_url; ?>';
        var mac_folder = '<?php echo $folder; ?>';
        var pages  = '<?php echo $_REQUEST['pages']; ?>';
        var get_title = '<?php echo $get_title['title'];?>';
        var title_value = '<?php echo $get_key ?>';
        var dragdr = jQuery.noConflict();
         dragdr(document).ready(function(dragdr) {
        macAlbum(pages)
         });
    </script>
    <script src="<?php echo $site_url . '/wp-content/plugins/'.$folder.'/js/jquery-pack.js'; ?>" type="text/javascript"></script>
<link href="<?php echo $site_url . '/wp-content/plugins/'.$folder.'/css/facebox_admin.css';?>" media="screen" rel="stylesheet" type="text/css" />
<script src="<?php echo $site_url . '/wp-content/plugins/'.$folder.'/js/facebox_admin.js'; ?>" type="text/javascript"></script>
<script src="<?php echo $site_url . '/wp-content/plugins/'.$folder.'/js/jquery.colorbox.js';?>"></script>
<script type="text/javascript">

    dragdr(document).ready(function($) {
      dragdr('a[rel*=facebox]').facebox()
    })
</script>
<script type="text/javascript">

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
                    document.getElementById("error_alb").innerHTML = 'Please enter the album name';
                    return false;
                }
                else if(get_title != title_value && <?php echo $mac_album_count?> != 0 )
                    {
                        dragdr(document).ready(function($) {
                        dragdr('a[rel*=oops]').facebox();
                         });
                    }

            });
        });
    </script>

    <div class="wrap nosubsub"><div id="icon-upload" class="icon32"><br /></div>
        <h2 class="nav-tab-wrapper">
        <a href="?page=macAlbum" class="nav-tab nav-tab-active">Albums</a>
        <a href="?page=macPhotos&albid=0" class="nav-tab">Upload Images</a>
        <a href="?page=macSettings" class="nav-tab">Settings</a>
        <a href="?page=ImportAlbums" class="nav-tab">Import Albums</a></h2>
        <div style="background-color:#ECECEC;padding: 10px;margin-top:10px;border: #ccc 1px solid">
        <strong> Note : </strong>Mac Photo Gallery can be easily inserted to the Post / Page by adding the following code :<br><br>
                 (i)  [macGallery] - This will show the entire gallery [Only for Page]<br>
                 (ii) [macGallery albid=1 row=3 cols=3] - This will show the particular album images with the album id 1
          </div>
         <h3 style="float:left;width:200px;padding-top: 10px">Add New Album</h3>
         <?php
if (isset($_REQUEST['macAlbum_submit']))
        {
            $uploadDir = wp_upload_dir();
            $path = $uploadDir['basedir'].'/mac-dock-gallery';
          
          if($get_title['title'] == $get_key || $mac_album_count <= 1)
          {
            $macAlbum_name        = filter_input(INPUT_POST, 'macAlbum_name');
            $macAlbum_description = filter_input(INPUT_POST, 'macAlbum_description');
            $current_image        = $_FILES['macAlbum_image']['name'];
           
            $get_albname =  $wpdb->get_var("SELECT macAlbum_name FROM " . $wpdb->prefix . "macalbum WHERE macAlbum_name like '%$macAlbum_name%'");
            if(!$get_albname)
            {
        
            $sql = $wpdb->query("INSERT INTO " . $wpdb->prefix . "macalbum
                    (`macAlbum_name`, `macAlbum_description`,`macAlbum_image`,`macAlbum_status`,`macAlbum_date`) VALUES
                    ('$macAlbum_name', '$macAlbum_description', '','ON',NOW())");
    
            }
            else
            {
                echo "<script> alert('Album name already exist');</script>";
            }
         
      }
      else
      {
       echo '<div class="mac-error_msg">Album Created successfully</div>';
      }
    }
   
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

         if($get_title['title'] != $get_key)
        {
        ?>
    <p><a href="#mydiv" rel="facebox"><img src="<?php echo $site_url . '/wp-content/plugins/'.$folder.'/images/licence.png'?>" align="right"></a>
 <a href="http://www.apptha.com/shop/checkout/cart/add/product/23/" target="_new"><img src="<?php echo $site_url . '/wp-content/plugins/'.$folder.'/images/buynow.png'?>" align="right" style="padding-right:5px;"></a>
</p>

<div id="mydiv" style="display:none;width:500px;background:#fff;">
<form method="POST" action=""  onSubmit="return validateKey()">
    <h2 align="center">License Key</h2>
   <div align="center"><input type="text" name="get_license" id="get_license" size="58" value="" />
   <input type="submit" name="submit_license" id="submit_license" value="Save"/></div>
</form>
</div>

<script>
   
    function validateKey()
           {
        	   var Licencevalue = document.getElementById("get_license").value;
                   if(Licencevalue == "" || Licencevalue !="<?php echo $get_key ?>"){
            	   alert('Please enter valid license key');
            	   return false;
        	   }
                   else
                       {
                            alert('Valid License key is entered successfully');
            	           return true;
                       }

           }
</script>
<div id="oops" style="display:none">
<p><strong>Oops! you will not be able to create more than one album with the free version.</strong></p>
<p>However you can play with the default album</p>
<ul>
    <li> - You can add n number of photos to the default album</li>
    <li> - You can rename the default photo album</li>
    <li> - You can use widgets to show the photos from the default album</li>
</ul>
<p>Please purchase the <a href="http://www.apptha.com/category/extension/Wordpress/Mac-Photo-Gallery" target="_blank">license key</a> to use complete features of this plugin.</p>
</div>
<?php } //else { ?>
 <div class="clear"></div>
 <?php if ($msg) {
 ?>
            <div  class="updated below-h2">
                <p><?php echo $msg; ?></p>
            </div>
<?php } ?>
 <div name="form_album" name="left_content" class="left_column">
            <form name="macAlbum" method="POST" id="macAlbum" enctype="multipart/form-data"  ><div class="form-wrap">

                    <div class="form-macAlbum">
                        <label for="macAlbum_name">Album Name *</label>
                        <input name="macAlbum_name" id="macAlbum_name" type="text" value="" size="40" aria-required="true" />
                        <div id="error_alb" style="color:red"></div>
                        <p><?php _e('The album name is how it appears on your site.'); ?></p>
                    </div>

                    <div class="form-macAlbum">
                        <label for="macAlbum_description">Album Description</label>
                        <textarea name="macAlbum_description" id="macAlbum_description" rows="5" cols="30"></textarea>
                        <p><?php _e('The description is for the album.'); ?></p>
                    </div>


           
                <p class="submit"><a href="#oops" rel="oops">
<input type="submit" class="button" name="macAlbum_submit" id="macAlbum_submit" value="<?php echo 'Add new Album'; ?>" /></a></p>
            </div></form>
    </div>
<?php //} ?>
    <div name="right_content" class="right_column">
                     
            <form name="all_action"  action="" method="POST" onSubmit="return deleteAlbums();" >
	            <div class="alignleft actions">
	                           <?php // if($get_title['title'] == $get_key) {?>
	                        <select name="action_album" id="action_album">
	                            <option value="" selected="selected"><?php _e('Bulk Actions'); ?></option>
	                            <option value="delete"><?php _e('Delete'); ?></option>
	                        </select>
	                        <input type="submit" value="<?php esc_attr_e('Apply'); ?>" name="doaction_album" id="doaction_album" class="button-secondary action" />
	                        <?php //} wp_nonce_field('bulk-tags'); ?>
	            </div>

            	<div id="bind_macAlbum"></div>
		            <script type="text/javascript">
						function deleteAlbums(){
							if(document.getElementById('action_album').selectedIndex == 1)
							{
								var album_delete= confirm('Are you sure to delete album/s ?');
								if (album_delete){
									return true;
								}
								else{
									return false;
								}
							}
							else if(document.getElementById('action_album').selectedIndex == 0)
							{
							return false;
							}
		
						}
						</script>
			</form>
    </div>

</div>

<?php
   }
?>