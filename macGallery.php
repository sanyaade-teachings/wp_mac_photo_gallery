<?php
/*
Plugin Name: Mac Photo Gallery
Plugin URI: http://www.apptha.com/category/extension/Wordpress/Mac-Photo-Gallery
Description: Mac Photo Gallery for Wordpress. It gives you a stylish gallery effect with mac effect. Mac Photo Gallery is a simple and easy gallery for wordpress.        ***Alert: If you are upgrading the latest version of mac photo gallery means, Kindly take backup of your previous version data & then do upgrade.
Version:2.5
Author: Apptha
Author URI: http://www.apptha.com
License: GNU General Public License version 2 or later; see LICENSE.txt
*/

/* The first loading page of the Mac Photo Gallery these contain admin setting too */
require_once("classes.php"); // Front view of the Mac Photo Gallery

    global $t;
    global $e;
    global $d;

       $t=1;
       $e=1;
       $d=1;
         
function Sharemacgallery($content)
 {
    $content = preg_replace_callback('/\[macGallery ([^]]*)\y]/i', 'CONTUS_macRender', $content); //Mac Photo Gallery Page
    return $content;
 }

function CONTUS_macRender($content,$wid='')
 {
    global $wpdb;
    if($wid=='')
    $wid='pirobox_gall';
    $pageClass = new contusMacgallery();
    $returnGallery = $pageClass->macEffectgallery($content,$wid);
    return  $returnGallery;
 }
 function CONTUS_macWidget($content,$wid='')
 {
    global $wpdb;
    if($wid=='')
    $wid='pirobox_gall';
    $pageClass = new contusMacgallery();
    $returnGallery = $pageClass->macEffectgallery($content,$wid);
    echo  $returnGallery;
 }
function macDisplay()
 {
    global $wpdb;
    $pageClass = new contusMacgallery();
    $returnGallery = $pageClass->macEffectgallery($content,$wid);
    return $returnGallery;
 }

function fbmacpage()
 {
    global $wpdb;
    require_once("macfbreturn.php");
    $macpage      = new macfb();
    $returnfbmac  = $macpage->fbmacreturn();
    return $returnfbmac;
 }

function macPage()
 {
add_menu_page('Mac Photos', 'Mac Photos', '2', 'macAlbum', 'show_macMenu',get_bloginfo('url').'/wp-content/plugins/'.dirname(plugin_basename(__FILE__)).'/images/icon.png');

add_submenu_page('macAlbum', 'Albums', 'Albums',4, 'macAlbum','show_macMenu');
add_submenu_page( 'macAlbum', 'Image upload', 'Upload Images', 'manage_options', 'macPhotos', 'show_macMenu');
add_submenu_page( 'macAlbum', 'Mac Settings', 'Settings', 'manage_options', 'macSettings', 'show_macMenu');
 }

function show_macMenu()
 {
    switch ($_GET['page'])
    {
        case 'macAlbum' :
            include_once (dirname(__FILE__) . '/macalbum.php'); // admin functions
            $macManage = new macManage();
            break;
        case 'macPhotos' :
            include_once (dirname(__FILE__) . '/macphotoGallery.php'); // admin functions
            $macPhotos = new macPhotos();
            break;
           case 'macSettings' :

            include_once (dirname(__FILE__) . '/macGallery.php'); // admin functions
               macSettings();
            break;
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


// Admin Setting For Mac Photo Gallery

function get_domain($url)
    {
      $pieces = parse_url($url);
      $domain = isset($pieces['host']) ? $pieces['host'] : '';
      if (preg_match('/(?P<domain>[a-z0-9][a-z0-9\-]{1,63}\.[a-z\.]{2,6})$/i', $domain, $regs)) {
        return $regs['domain'];
      }
      return false;
    }
function macSettings() {
            global $wpdb;
            $folder   = dirname(plugin_basename(__FILE__));
            $site_url = get_bloginfo('url');
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

        if($get_title['title'] != $get_key)
        {
        ?>
<script>
var url = '<?php echo $site_url; ?>';
</script>
<link href="<?php echo $site_url . '/wp-content/plugins/'.$folder.'/css/facebox_admin.css';?>" media="screen" rel="stylesheet" type="text/css" />
<script src="<?php echo $site_url . '/wp-content/plugins/'.$folder.'/js/facebox_admin.js'; ?>" type="text/javascript"></script>

<script type="text/javascript">
 var apptha = jQuery.noConflict();
    apptha(document).ready(function(apptha) {
      apptha('a[rel*=facebox]').facebox()
    })
</script>
<p><a href="#mydiv" rel="facebox"><img src="<?php echo $site_url . '/wp-content/plugins/'.$folder.'/images/licence.png'?>" align="right"></a>
 <a href="http://www.apptha.com/category/extension/Wordpress/Mac-Photo-Gallery" target="_blank"><img src="<?php echo $site_url . '/wp-content/plugins/'.$folder.'/images/buynow.png'?>" align="right" style="padding-right:5px;"></a>
</p>
<div id="mydiv" style="display:none">
<form method="POST" action="" onSubmit="return validateKey()">
    <h2 align="center">License Key</h2>
   <div align="right"><input type="text" name="get_license" id="get_license" size="58" />
   <input type="submit" name="submit_license" id="submit_license" value="Save" /></div>
</form>
</div>
<?php } ?>

    <script>
       function mac_settings_validation()
       {
                // Made it a local variable by using "var"
                var macrow = document.getElementById("macrow").value;
                var macimg_page = document.getElementById("macimg_page").value;
               
                var mouseHei = document.getElementById("mouseHei").value;
                var mouseWid = document.getElementById("mouseWid").value;
                var resizeHei = document.getElementById("resizeHei").value;
                var resizeWid = document.getElementById("resizeWid").value;
                var macProximity = document.getElementById("macProximity").value;

                if(macrow == ""  || macrow == "0" || macimg_page =="" || macimg_page =="0" || 
                    resizeHei == "" || resizeHei == "0" || resizeWid =="" || resizeWid == "0" || mouseWid == ""  || mouseWid == "0"
                   ||mouseHei == "" || mouseHei == "0" ||  macProximity == "0"){
                    document.getElementById("error_msg").innerHTML = 'Please Enter Values For All The Fields ';
                    return false;
                }

       }
           function validateKey()
           {
        	   var Licencevalue = document.getElementById("get_license").value;
        	   if(Licencevalue == ""||Licencevalue !="<?php echo $get_key ?>"){
            	   alert('Please enter valid license key');
            	   return false;
        	   }
                    else
                       {
                           alert('Valid License key is entered successfully');
            	           return false;
                       }

           }
    </script>
    <link rel="stylesheet" href="<?php echo $site_url . '/wp-content/plugins/' . dirname(plugin_basename(__FILE__)) . '/css/style.css'; ?>">
    <div class="wrap">
        <div id="icon-upload" class="icon32"><br /></div>
        <h2 class="nav-tab-wrapper">
        <a href="?page=macAlbum" class="nav-tab">Albums</a>
        <a href="?page=macPhotos&albid=0" class="nav-tab">Upload Images</a>
        <a href="?page=macSettings" class="nav-tab nav-tab-active">Settings</a></h2>
          <div style="background-color: #ECECEC;padding: 10px;margin-top:10px;border: #ccc 1px solid">
        <strong> Note : </strong>Mac Photo Gallery can be easily inserted to the Post / Page by adding the following code :<br><br>
                 (i)  [macGallery] - This will show the entire gallery [Only for Page]<br>
                 (ii) [macGallery albid=1 row=3 cols=3] - This will show the particular album images with the album id 1
          </div>
        <div id="error_msg" style="color:red"></div>
<?php
    if (isset($_REQUEST['macSet_upt'])) {

        $macrow         = $_REQUEST['macrow'];
        $macimg_page    = $_REQUEST['macimg_page'];
        $summary_macrow = $_REQUEST['summary_macrow'];
        $summary_page   = $_REQUEST['summary_page'];
        $mouseHei       = $_REQUEST['mouseHei'];
        $mouseWid       = $_REQUEST['mouseWid'];
        $macProximity          = $_REQUEST['macProximity'];
        $macDir                = $_REQUEST['macDir'];
        $macImg_dis            = $_REQUEST['macImg_dis'];
        $mac_facebook_api      = $_REQUEST['mac_facebook_api'];
        $mac_facebook_comment  = $_REQUEST['mac_facebook_comment'];

        $mac_imgdispstyle = $_REQUEST['mac_imgdispstyle'];
        $resizeHei        = $_REQUEST['resizeHei'];
        $resizeWid        = $_REQUEST['resizeWid'];
        $macAlbum_limit   = $_REQUEST['macAlbum_limit'];
        
         $show_share  = $_REQUEST['show_share'];
        $show_download  = $_REQUEST['show_download'];
        
        if($macrow == '' || $macrow == '0' || $macimg_page == '' || $macimg_page == '0' ||
           $summary_macrow == '' || $summary_macrow == '0' ||$summary_page == '' || $summary_page == '0' ||
           $mouseHei == '' || $mouseHei == '0' ||
           $mouseWid == '' || $mouseWid == '0' ||$macProximity == '' || $macProximity == '0' ||
          /* $mac_facebook_api == '' || $mac_facebook_api == '0' ||$mac_facebook_comment == '' || $mac_facebook_comment == '0' ||*/
           $resizeHei == '' || $resizeHei == '0' ||$resizeWid == '' || $resizeWid == '0'
                )
        {
         
        }
        else
        {
         $updSet = $wpdb->query("UPDATE " . $wpdb->prefix . "macsettings SET  `macrow` = '$macrow',
         `macimg_page` = '$macimg_page',`summary_macrow` = '$summary_macrow', `summary_page`='$summary_page',
         `mouseHei` = '$mouseHei' , `mouseWid` = '$mouseWid',`resizeHei`='$resizeHei',`resizeWid` = '$resizeWid',
         `macProximity` = '$macProximity', `macImg_dis` = '$macImg_dis',`macAlbum_limit`= '$macAlbum_limit',
         `mac_imgdispstyle` = '$mac_imgdispstyle',
         `mac_facebook_api` = '$mac_facebook_api',show_download='$show_download' WHERE `macSet_id` = 1");
         echo '<div class="mac-error_msg"">Settings updated successfully</div>';
        }
         }
       $viewSetting = $wpdb->get_row("SELECT * FROM " . $wpdb->prefix . "macsettings");
    ?>

        <form name="macSet" method="POST" onsubmit="return mac_settings_validation();" action="">
            <div class="macSettings">
                <div align="right"><input class='button-primary' name='macSet_upt' id='macSet_upt' type='submit' value='Update Options'></p></div>
                <table style="margin-right: 10px;">

                        <caption class="header">Display Settings</caption>

                    <tr>
                        <td><span>Columns</span></td>
                        <td><input type="text" name="macrow" id="macrow" value="<?php echo $viewSetting->macrow; ?>"></td>
                    </tr>
                    <tr>
                        <td><span>Rows</span></td>
                        <td><input type="text" name="macimg_page" id="macimg_page" value="<?php echo $viewSetting->macimg_page; ?>"></td>
                    </tr>
                    <tr>
                        <td><span>Home Page Columns</span></td>
                        <td><input type="text" name="summary_page" id="summary_page" value="<?php echo $viewSetting->summary_page; ?>"></td>
                    </tr>
                     <tr>
                        <td><span>Home Page Rows</span></td>
                        <td><input type="text" name="summary_macrow" id="summary_macrow" value="<?php echo $viewSetting->summary_macrow; ?>"></td>
                    </tr>
                 
                     <tr style="display:none">
                        <td><span>Image display:</span></td>
                        <td>
                            <input type="radio" name="macImg_dis" <?php if ($viewSetting->macImg_dis == 'random') { echo 'checked'; } ?> value="random" >Random
                     <input type="radio" name="macImg_dis" <?php if ($viewSetting->macImg_dis == 'order') { echo 'checked'; } ?> value="order">Order
                        </td>
                   </tr>
                        <tr>
                        <td><span>Number of Albums / Page</span></td>
                        <td>
                            <select name="macAlbum_limit">
                            <option  <?php if ($viewSetting->macAlbum_limit == '4')  { echo 'selected="selected"'; } ?>value="4">4</option>
                            <option  <?php if ($viewSetting->macAlbum_limit == '8')  { echo 'selected="selected"'; } ?>value="8">8</option>
                            <option  <?php if ($viewSetting->macAlbum_limit == '12') { echo 'selected="selected"'; } ?>value="12">12</option>
                            <option  <?php if ($viewSetting->macAlbum_limit == '16') { echo 'selected="selected"'; } ?>value="16">16</option>
                            <option  <?php if ($viewSetting->macAlbum_limit == '20') { echo 'selected="selected"'; } ?>value="20">20</option>
                            </select>
                       </td>
                   </tr>
                   
                   <tr>
                        <td><span>Download settings:</span></td>
                        <td>
                            <input type="radio" name="show_download" <?php if ($viewSetting->show_download == 'allow') { echo 'checked'; } ?> value="allow" >Allow
                     <input type="radio" name="show_download" <?php if ($viewSetting->show_download == 'restrict') { echo 'checked'; } ?> value="restrict">Restrict
                        </td>
                   </tr>
                     
                  <!-- <tr>
                        <td><span>Facebook Comments:</span></td>
                        <td>
                            <input type="radio" name="show_share" <?php //if ($viewSetting->show_share == 'show') { echo 'checked'; } ?> value="show" >Show
                     <input type="radio" name="show_share" <?php //if ($viewSetting->show_share == 'hide') { echo 'checked'; } ?> value="hide">Hide
                        </td>
                   </tr>  
                    <tr>
                        <td><span>Number of FB comments / Page</span></td>
                        <td>
                            <select name="mac_facebook_comment">
                            <option  <?php //if ($viewSetting->mac_facebook_comment == '5')  { echo 'selected="selected"'; } ?>value="5">5</option>
                            <option  <?php //if ($viewSetting->mac_facebook_comment == '10')  { echo 'selected="selected"'; } ?>value="10">10</option>
                            <option  <?php //if ($viewSetting->mac_facebook_comment == '15') { echo 'selected="selected"'; } ?>value="15">15</option>
                            <option  <?php //if ($viewSetting->mac_facebook_comment == '20') { echo 'selected="selected"'; } ?>value="20">20</option>
                            <option  <?php //if ($viewSetting->mac_facebook_comment == '25') { echo 'selected="selected"'; } ?>value="25">25</option>
                            <option  <?php //if ($viewSetting->mac_facebook_comment == '30') { echo 'selected="selected"'; } ?>value="30">30</option>
                            </select>
                       </td>
                   </tr>-->
              </table>
                <table>
              
                        <caption>Image Settings</caption>
                   <tr>
                        <td><span>  Mac Dock  Image Width/Height(Px)</span></td>
                        <td><input type="text" name="mouseWid" id="mouseWid" value="<?php echo $viewSetting->mouseWid; ?>"></td>
                    </tr>
                     <tr>
                        <td><span>Mouseover Width/ Height / Row (Px)</span></td>
                        <td><input type="text" name="mouseHei" id="mouseHei" value="<?php echo $viewSetting->mouseHei; ?>"></td>
                    </tr>
                        <tr style="display:none">
                        <td><span> Resizing Height(Px)</span></td>
                        <td><input type="text" name="resizeHei" id="resizeHei" value="<?php echo $viewSetting->resizeHei; ?>"></td>
                    </tr>
                    <tr style="display:none">
                        <td><span>  Resizing Width(Px)</span></td>
                        <td><input type="text" name="resizeWid" id="resizeWid" value="<?php echo $viewSetting->resizeWid; ?>"></td>
                    </tr>
                
                     <tr>
                        <td><span>Image Display Style <br> [Works only for Chrome]:</span></td>
                        <td>
                           <input type="radio" name="mac_imgdispstyle" <?php if ($viewSetting->mac_imgdispstyle == '0') { echo 'checked';}?> value="0" >Normal
                           <input type="radio" name="mac_imgdispstyle" <?php if ($viewSetting->mac_imgdispstyle == '1') { echo 'checked';}?> value="1">Rounded corner<br>
                           <input type="radio" name="mac_imgdispstyle" <?php if ($viewSetting->mac_imgdispstyle == '2') { echo 'checked';} ?> value="2">Winged display
                           <input type="radio" name="mac_imgdispstyle" <?php if ($viewSetting->mac_imgdispstyle == '3') { echo 'checked';}?> value="3">Rounded  image
                        </td>
                    </tr>
                    <tr>
                        <td><span>Proximity</span></td>
                        <td><input type="text"  name="macProximity" id="macProximity" value="<?php echo $viewSetting->macProximity; ?>"></td>
                    </tr>
                    <tr>
                        <td><span> Facebook API Id    [Facebook Share]</span></td>
                        <td><input type="text" name="mac_facebook_api" id="mac_facebook_api" value="<?php echo $viewSetting->mac_facebook_api; ?>"><br />
                            <div style="font-size:8pt">Enter Facebook API ID , For example: https://developers.facebook.com/apps#!/apps/<strong>126989914043022</strong><br /></div>
                        </td>
                     </tr>
                  <!--  <tr>
                        <td><span>Mac Effect Direction</span></td>
                        <td><select name="macDir">
                                <option <?php  //if ($viewSetting->macDir == '1') { echo 'selected="selected"'; } ?> value="1" >Top</option>
                                <option <?php //if ($viewSetting->macDir == '0') { echo 'selected="selected"';  } ?>value="0">Bottom</option>
                        </select></td>
                    </tr> -->
                   
                </table>
                <div id="error_msg" style="color:red"></div>
                <div align="right"> <p class='submit'><input class='button-primary' name='macSet_upt' id='macSet_upt' type='submit' value='Update Options'></p></div>
            </div>
        </form>
    </div>
<?php
                        }
                        //End of Album Setting page
 function loadsettings() {
 global $wpdb;
 $insertSettings = $wpdb->query("INSERT INTO " . $wpdb->prefix . "macsettings
(`macSet_id`, `macrow`, `macimg_page`, `summary_macrow`, `summary_page`, `mouseHei`, `mouseWid`, `resizeHei`, `resizeWid`, `macProximity`, `macDir`, `macImg_dis`, `macAlbum_limit`, `mac_albumdisplay`, `mac_imgdispstyle`, `mac_facebook_api`, `mac_facebook_comment`,`show_share`,`show_download`) VALUES
(1, 4, 3, 3, 3, 50, 77, 120, 120, 90, 1, 'order', 8, 'no', 1, '', 10 ,'show' , 'restrict');");
 $insertDefault = $wpdb->query("INSERT INTO " . $wpdb->prefix . "macalbum (`macAlbum_id`, `macAlbum_name`, `macAlbum_description`, `macAlbum_image`, `macAlbum_status`, `macAlbum_date`) VALUES
 (1, 'First Album', 'This is my first album ', '', 'ON', '2011-07-27 17:11:53')");
                        }

 $lookupObj = array();
 $chars_str;
 $chars_array = array();

function macgal_generate($domain)
{
$code=macgal_encrypt($domain);
$code = substr($code,0,25)."CONTUS";
return $code;
}

function macgal_encrypt($tkey) {

$message =  "EW-MPGMP0EFIL9XEV8YZAL7KCIUQ6NI5OREH4TSEB3TSRIF2SI1ROTAIDALG-JW";

	for($i=0;$i<strlen($tkey);$i++){
$key_array[]=$tkey[$i];
}
	$enc_message = "";
	$kPos = 0;
        $chars_str =  "WJ-GLADIATOR1IS2FIRST3BEST4HERO5IN6QUICK7LAZY8VEX9LIFEMP0";
	for($i=0;$i<strlen($chars_str);$i++){
$chars_array[]=$chars_str[$i];
}
	for ($i = 0; $i<strlen($message); $i++) {
		$char=substr($message, $i, 1);

		$offset = macgal_getOffset($key_array[$kPos], $char);
		$enc_message .= $chars_array[$offset];
		$kPos++;
		if ($kPos>=count($key_array)) {
			$kPos = 0;
		}
	}

	return $enc_message;
}
function macgal_getOffset($start, $end) {

    $chars_str =  "WJ-GLADIATOR1IS2FIRST3BEST4HERO5IN6QUICK7LAZY8VEX9LIFEMP0";
	for($i=0;$i<strlen($chars_str);$i++){
$chars_array[]=$chars_str[$i];
}

	for ($i=count($chars_array)-1;$i>=0;$i--) {
		$lookupObj[ord($chars_array[$i])] = $i;

	}

	$sNum = $lookupObj[ord($start)];
	$eNum = $lookupObj[ord($end)];

	$offset = $eNum-$sNum;

	if ($offset<0) {
		$offset = count($chars_array)+($offset);
	}

	return $offset;
}
  /* Function to invoke install player plugin */

                        function macGallery_installFile()
                        {

                            require_once(dirname(__FILE__) . '/install.php');
                            macGallery_install();
                        }

                        /* Function to uninstall player plugin */

                        function macGallery_deinstall()
                        {
                            global $wpdb, $wp_version;
                            $table_settings = $wpdb->prefix . 'macsettings';
                            $table_macAlbum = $wpdb->prefix . 'macphotos';
                            $table_macPhotos = $wpdb->prefix . 'macalbum';

                              $wpdb->query("DROP TABLE IF EXISTS `" . $table_settings . "`");
                              $wpdb->query("DROP TABLE IF EXISTS `" . $table_macAlbum . "`");
                              $wpdb->query("DROP TABLE IF EXISTS `" . $table_macPhotos . "`");
                              $wpdb->query("DELETE FROM " . $wpdb->prefix . "posts WHERE post_content='[macGallery]'");
                        }

                        /* Function to activate player plugin */

                        function macGallery_activate() {
                            loadsettings();
                             create_mac_folder();
                        }

                        register_activation_hook(plugin_basename(dirname(__FILE__)) . '/macGallery.php', 'macGallery_installFile');
                        register_activation_hook(__FILE__, 'macGallery_activate');
                        register_uninstall_hook(__FILE__, 'macGallery_deinstall');

                        /* Function to deactivate player plugin */

                        function macGallery_deactivate()
                        {
                             global $wpdb;
                             $wpdb->query("DELETE FROM " . $wpdb->prefix . "posts WHERE post_content='[macGallery]'");
                        }

                        register_deactivation_hook(__FILE__, 'macGallery_deactivate');
                        add_shortcode('macGallery', 'CONTUS_macRender');
// CONTENT FILTER
                        add_filter('the_content', 'Sharemacgallery');
// OPTIONS MENU
                        add_action('admin_menu', 'macPage');
                        
                        
/* Album Listing Widgets */

$site_url = get_bloginfo('url');
function widget_Contusmacalbum_init()
{
    if (!function_exists('register_sidebar_widget') )
    return;
    function widget_Contusmacalbum($args)
    {
        extract($args);
        global $wpdb, $wp_version, $popular_posts_current_ID;
        // These are our own options
        $options = get_option('widget_Contusmacalbum');
        $title = $options['title'];  // Title in sidebar for widget
        $show = $options['show'];  // # of Posts we are showing
        $excerpt = $options['excerpt'];  // Showing the excerpt or not
        $exclude = $options['exclude'];  // Categories to exclude
        $site_url = get_bloginfo('url');
        $dir = dirname(plugin_basename(__FILE__));
        $dirExp = explode('/', $dir);
        $dirPage = $dirExp[0];
        $uploadDir = wp_upload_dir();
        $path = $uploadDir['baseurl'].'/mac-dock-gallery';
        ?>
        <link rel="stylesheet" type="text/css" href="<?php echo $site_url; ?>/wp-content/plugins/<?php echo dirname(plugin_basename(__FILE__))?>/css/style.css" />
        <link rel="stylesheet" href="<?php echo $site_url . '/wp-content/plugins/' . dirname(plugin_basename(__FILE__)) . '/css/images.css'; ?>">
        <script type="text/javascript">
            var baseurl;
            baseurl = '<?php echo $site_url; ?>';
            folder  = '<?php echo $dirPage ;?>';
        </script>
                                                    <!-- For Contus macalbum -->
<?php
echo $before_widget;
$macPageid = $wpdb->get_var(("SELECT * FROM " . $wpdb->prefix . "posts WHERE post_content LIKE '%[macGallery]%' AND post_type = 'page' AND post_status = 'publish'"));
$div    = '<div id="contusMac" class="sidebar-wrap clearfix">
           <div><h3 class="widget-title">'.$title.'</h3></div>';
$show   = $options['show']; //Number of shows
$sql    = "SELECT * FROM " . $wpdb->prefix . "macalbum WHERE macAlbum_status = 'ON' ORDER BY RAND() LIMIT 0,$show";
$albDis = $wpdb->get_results($sql);
$div .='<ul class="ulwidget">';
// were there any posts found?
if (!empty($albDis))
    {
           //output to screen
      $div .='<li>';
      foreach ($albDis as $albDisplay)
      {		
      	  $uploadDir = wp_upload_dir();
          $file_image =  $uploadDir['basedir'] . '/mac-dock-gallery/' .$albDisplay->macAlbum_image;
          $photoCount = $wpdb->get_var("SELECT count(*) FROM " . $wpdb->prefix . "macphotos WHERE macAlbum_id='$albDisplay->macAlbum_id' and macPhoto_status='ON'");
          $default_first = $wpdb->get_var("SELECT macPhoto_image FROM " . $wpdb->prefix . "macphotos WHERE macAlbum_id='$albDisplay->macAlbum_id' and macPhoto_status='ON' ORDER BY macPhoto_id DESC LIMIT 0,1");
          $div .='<div  class="albumimg">';
                 if ($albDisplay->macAlbum_image == '' && $photoCount == '0')
                    {
                      $div .='<div class="widget_alb_img"><a class="thumbnail" href="' . $site_url .'?page_id='.$macPageid.'&albid=' . $albDisplay->macAlbum_id . '"><img src="' . $site_url . '/wp-content/plugins/' . dirname(plugin_basename(__FILE__)) . '/uploads/star.jpg" width="140" height="140"></a></div>';
                    }
                    else  if((file_exists($file_image))&&($albDisplay->macAlbum_image != ''))
                    {
                      $div .='<div class="widget_alb_img"><a class="thumbnail" href="' . $site_url .'?page_id='.$macPageid.'&albid=' . $albDisplay->macAlbum_id . '"><img src="' . $path . '/' . $albDisplay->macAlbum_image . '"   width="140" height="140"></a></div>';
                    }
                    else  if(!file_exists($file_image))
                    {
                    $div .='<div class="widget_alb_img"><a class="thumbnail" href="' . $site_url .'?page_id='.$macPageid.'&albid=' . $albDisplay->macAlbum_id . '"><img src="' . $site_url . '/wp-content/plugins/' . dirname(plugin_basename(__FILE__)) . '/uploads/no-photo.png" width="140" height="140"></a></div>';	
                    }
                    else if($albDisplay->macAlbum_image == '' && $photoCount != '0')
                    {
                    $div .='<div class="widget_alb_img"><a class="thumbnail" href="' . $site_url .'?page_id='.$macPageid.'&albid=' . $albDisplay->macAlbum_id . '"><img src="' . $path . '/'.$default_first.'" width="140" height="140"></a></div>';
                    }
                      $div .='<div class="mac_title">' . substr($albDisplay->macAlbum_name,0,23) . '</div>';
                                        $macDate = explode(' ', $albDisplay->macAlbum_date);
                                        $exDate =  explode('-',$macDate[0]);
                     $div .='<div class="mac_date">' .$exDate[2].'-'.$exDate[1].'-'.$exDate[0]. '</div>';
                     $div .='<a href="' . $site_url .'?page_id='.$macPageid.'&albid=' . $albDisplay->macAlbum_id . '" class="album_href">
                             <span class="countimg">
                             <img src="' . $site_url . '/wp-content/plugins/' . dirname(plugin_basename(__FILE__)) . '/images/photo.jpg" />' . $photoCount . ' </span></a>';
                     $div .='</div>';
       }

                     $div .= '</li>';
   }
else
    $div .="<li>No Contus macalbum</li>";
    $div .='</ul></div>';
    echo $div;
// echo widget closing tag
echo $after_widget;
   }
// Settings form
      ?>
<?php
function widget_Contusmacalbum_control()
{
// Get options
$options = get_option('widget_Contusmacalbum');
// options exist? if not set defaults
if ( !is_array($options) )
{
$options = array('title'=>'Photo Album', 'show'=>'3', 'excerpt'=>'1','exclude'=>'');
}
// form posted?
if ( $_POST['Contusmacalbum-submit'] )
{
    // Remember to sanitize and format use input appropriately.
    $options['title'] = strip_tags(stripslashes($_POST['Contusmacalbum-title']));
    $options['show'] = strip_tags(stripslashes($_POST['Contusmacalbum-show']));
    $options['excerpt'] = strip_tags(stripslashes($_POST['Contusmacalbum-excerpt']));
    $options['exclude'] = strip_tags(stripslashes($_POST['Contusmacalbum-exclude']));
    update_option('widget_Contusmacalbum', $options);
}

// Get options for form fields to show
$title = htmlspecialchars($options['title'], ENT_QUOTES);
$show = htmlspecialchars($options['show'], ENT_QUOTES);
$excerpt = htmlspecialchars($options['excerpt'], ENT_QUOTES);
$exclude = htmlspecialchars($options['exclude'], ENT_QUOTES);

// The form fields
echo '<p>
<label for="Contusmacalbum-title">' . __('Title:') . '
<input id="Contusmacalbum-title" name="Contusmacalbum-title" type="text" value="'.$title.'" />
</label></p>';
echo '<p>
<label for="Contusmacalbum-show">' . __('Show:') . '
<input id="Contusmacalbum-show" name="Contusmacalbum-show" type="text" value="'.$show.'" />
</label></p>';
echo '<input type="hidden" id="Contusmacalbum-submit" name="Contusmacalbum-submit" value="1" />';
}

// Register widget for use
register_sidebar_widget(array('Mac Album', 'widgets'), 'widget_Contusmacalbum');

// Register settings for use, 300x100 pixel form
register_widget_control(array(' Mac Album ', 'widgets'), 'widget_Contusmacalbum_control', 300, 200);
}
// Run code and init
add_action('widgets_init', 'widget_Contusmacalbum_init');

/* Album Listing Page */
require_once("macGallery.php");
function widget_Contusmacphotos_init()
{
    if (!function_exists('register_sidebar_widget') )
    return;
    function widget_Contusmacphotos($args)
    {
        extract($args);
        global $wpdb, $wp_version, $popular_posts_current_ID;
        $options   = get_option('widget_Contusmacphotos');
        $macAlbid  = $options['title'];  // Title in sidebar for widget
        $mac_row   = $options['mac_row'];  // # of Posts we are showing
        $mac_cols  = $options['mac_cols'];  // # of Posts we are showing
        $mac_width = $options['mac_width'];  // # of Posts we are showing
        $excerpt   = $options['excerpt'];  // Showing the excerpt or not
        $exclude   = $options['exclude'];  // Categories to exclude
        $site_url  = get_bloginfo('url');


        ?>
        <link rel="stylesheet" type="text/css" href="<?php echo $site_url; ?>/wp-content/plugins/<?php echo dirname(plugin_basename(__FILE__))?>/css/style.css" />
        <script type="text/javascript">
            var baseurl;
            baseurl = '<?php echo $site_url; ?>';
            folder  = '<?php echo $dirPage ;?>';
        </script>
                                                 <!-- For Contus Mac Album photo list -->
            <?php
            echo $before_widget;
            $mac_row        = $options['mac_row']; //Number of shows
            $sql         = "SELECT * FROM " . $wpdb->prefix . "photos WHERE `macAlbum_id`='$macAlbid' LIMIT 0,$mac_row";
            $macPhotos   = $wpdb->get_results($sql);
            $albName_wid = $wpdb->get_var("SELECT macAlbum_name FROM " . $wpdb->prefix . "macalbum WHERE `macAlbum_id`='$macAlbid'");
            ?>
            <div><h3 class="widget-title" style="padding-bottom:5px"><?php echo $albName_wid ?></h3></div>
            <div id="contusmacwid" class="sidebar-wrap clearfix">
            <ul class="ulwidget">
               <?php
                    $content = array("walbid"=>$macAlbid, "rows"=>$mac_row ,"column"=>$mac_cols,"width"=>$mac_width);
                     CONTUS_macWidget($content,'pirobox_gall1');
                ?>
            </ul></div>
            <?php
            // echo widget closing tag
            echo $after_widget;
               }
            function widget_Contusmacphotos_control()
            {
                 global $wpdb, $wp_version, $popular_posts_current_ID;
                 $widmac = $wpdb->get_results("select * from " . $wpdb->prefix . "macalbum where macAlbum_status='ON'");

                    // Get options
                    $options = get_option('widget_Contusmacphotos');
                    // options exist? if not set defaults
                    if ( !is_array($options) )
                    {
                    $options = array('title'=>'1', 'mac_row'=>'3','mac_cols'=>'3','mac_width'=>'50', 'excerpt'=>'1','exclude'=>'');
                    }
                    // form posted?
                    if ( $_POST['Contusmacphotos-submit'] )
                    {
                        // Remember to sanitize and format use input appropriately.
                        $options['title'] = strip_tags(stripslashes($_REQUEST['Contusmacalbum-id']));
                        $options['mac_row'] = strip_tags(stripslashes($_POST['Contusmacphotos-macrow']));
                        $options['mac_cols'] = strip_tags(stripslashes($_POST['Contusmacphotos-maccols']));
                        $options['mac_width'] = strip_tags(stripslashes($_POST['Contusmacphotos-macwidth']));
                        $options['excerpt'] = strip_tags(stripslashes($_POST['Contusmacphotos-excerpt']));
                        $options['exclude'] = strip_tags(stripslashes($_POST['Contusmacphotos-exclude']));

                        if($options['mac_row'] == '0' || $options['mac_cols'] == '0' || $options['mac_width'] == '0')
                        {
                            echo "<div class='mac-red-error'>Please enter values</div>";
                        }
                        else
                        {
                          update_option('widget_Contusmacphotos', $options);
                        }
                        
                    }
                    // Get options for form fields to show
                    $title = htmlspecialchars($options['title'], ENT_QUOTES);
                    $mac_row = htmlspecialchars($options['mac_row'], ENT_QUOTES);
                    $mac_cols = htmlspecialchars($options['mac_cols'], ENT_QUOTES);
                    $mac_width = htmlspecialchars($options['mac_width'], ENT_QUOTES);
                    $excerpt = htmlspecialchars($options['excerpt'], ENT_QUOTES);
                    $exclude = htmlspecialchars($options['exclude'], ENT_QUOTES);

                    echo ' <label for="Contusmacalbum-id">' . __('Album Id:').'
                   <select style="width: 200px;" id="Contusmacalbum-id" name="Contusmacalbum-id">';
                    foreach($widmac as $widmacs)
                    {
                        if($title == $widmacs->macAlbum_id )
                        {
                           $sele = 'selected=selected';
                        }
                             else
                             {
                             $sele = '';
                             }
                     echo '<option value="'.$widmacs->macAlbum_id.'" '.$sele.'>'.$widmacs->macAlbum_name.'</option>';
                   }
                    echo '</select>';
                    echo '<p style="text-align:right;">
                    <label for="Contusmacphotos-macrow">' . __('Rows:') . '
                    <input id="Contusmacphotos-macrow" name="Contusmacphotos-macrow" type="text" value="'.$mac_row.'" />
                    </label></p>';
                      echo '<p style="text-align:right;">
                    <label for="Contusmacphotos-macrow">' . __('Cols:') . '
                    <input id="Contusmacphotos-maccols" name="Contusmacphotos-maccols" type="text" value="'.$mac_cols.'" />
                    </label></p>';
                        echo '<p style="text-align:right;">
                    <label for="Contusmacphotos-macrow">' . __('Width:') . '
                    <input id="Contusmacphotos-macwidth" name="Contusmacphotos-macwidth" type="text" value="'.$mac_width.'" />
                    </label></p>';
                    echo '<input type="hidden" id="Contusmacphotos-submit" name="Contusmacphotos-submit" value="1" />';
            }

            // Register widget for use
            register_sidebar_widget(array(' Mac Photos', 'widgets'), 'widget_Contusmacphotos');
            // Register settings for use, 300x100 pixel form
            register_widget_control(array('Mac Photos', 'widgets'), 'widget_Contusmacphotos_control', 300, 200);
            }
         function setplayerscripts() {
             $site_url = get_bloginfo('url');
             ?>
         
        <script type="text/javascript" src="<?php echo $site_url . '/wp-content/plugins/' . dirname(plugin_basename(__FILE__)) . '/js/ajax.js'; ?>"></script>
        <script type="text/javascript" src="<?php echo $site_url . '/wp-content/plugins/' . dirname(plugin_basename(__FILE__)) . '/js/jquery164.js'; ?>"></script>
        <script type="text/javascript" src="<?php echo $site_url . '/wp-content/plugins/' . dirname(plugin_basename(__FILE__)) . '/js/interface.js'; ?>"></script>
        <script type="text/javascript" src="<?php echo $site_url . '/wp-content/plugins/' . dirname(plugin_basename(__FILE__)) . '/js/jquery.jcarousel.js'; ?>"></script>
        <script type="text/javascript" src="<?php echo $site_url . '/wp-content/plugins/' . dirname(plugin_basename(__FILE__)) . '/js/facebox.js'; ?>"></script>
        <script type="text/javascript">
var mac = jQuery.noConflict();
mac(document).ready(function() {
	mac('a[rel*=facebox]').facebox();
    });
</script>
        <link rel="stylesheet" type="text/css" href="<?php echo $site_url . '/wp-content/plugins/' . dirname(plugin_basename(__FILE__)) . '/css/facebox.css'; ?>" />
    <?php }

add_action('wp_head', 'setplayerscripts');
            // Run code and init
            add_action('widgets_init', 'widget_Contusmacphotos_init');

?>
