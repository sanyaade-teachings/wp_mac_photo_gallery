<?php
/*
 ***********************************************************
 * Plugin Name:  Mac Photo Gallery
 * Description: Mac Photo Gallery component for Wordpress. In this you can Stylish gallery effect with mac effect. Mac Photo Gallery is a simple and easy gallery for wordpress.
 * Version: 1.0
 * Edited By: Saranya
 * Author URI: http://www.apptha.com/
 * Date :May 19 2011

 **********************************************************

 @license GNU/GPL http://www.gnu.org/copyleft/gpl.html,

 **********************************************************/

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
     $content = preg_replace_callback('/\[macGallery([^]]*)\y]/i', 'CONTUS_macRender', $content,''); //Mac Photo Gallery Page
    $content = preg_replace_callback('/\[macGallery display]/', 'macDisplay', $content); //Mac Photo Gallery from Widgets
    $content = preg_replace_callback('/\[fbmaccomments]/', 'fbmacpage', $content); // Mac Photo Gallery facebook return page
    return $content;
 }

function CONTUS_macRender($content,$wid='')
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
    add_media_page(__('Macgallery', 'Macgallery'), __('Mac Photo Gallery', 'Mac Photo Gallery'), 'edit_posts', 'macAlbum', 'show_macMenu');
    add_media_page(__('Macphotos', 'Macphotos'), __('', ''), 'edit_posts', 'macPhotos', 'show_macMenu');
    add_options_page('Mac Photo Gallery', 'Mac Photo Gallery', '8', 'macGallery.php', 'macSettings');
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

function macSettings() {
    global $wpdb;
     $folder   = dirname(plugin_basename(__FILE__));
     $site_url = get_bloginfo('url');
        $split_title = $wpdb->get_var("SELECT option_value FROM ".$wpdb->prefix."options WHERE option_name='get_title_key'");
         $get_title = unserialize($split_title);

            $strDomainName = $site_url;
            preg_match("/(?P<domain>[a-z0-9][a-z0-9\-]{1,63}\.[a-z\.]{2,6})$/i", $strDomainName, $matches);
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
<script src="<?php echo $site_url . '/wp-content/plugins/'.$folder.'/js/jquery-pack.js'; ?>" type="text/javascript"></script>
<link href="<?php echo $site_url . '/wp-content/plugins/'.$folder.'/css/facebox.css';?>" media="screen" rel="stylesheet" type="text/css" />
<script src="<?php echo $site_url . '/wp-content/plugins/'.$folder.'/js/facebox.js';?>" type="text/javascript"></script>
<script type="text/javascript">

    $(document).ready(function($) {
      $('a[rel*=facebox]').facebox()
    })
</script>
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
<?php } ?>
<?php
    if (isset($_REQUEST['macSet_upt'])) {
        $macrow         = $_REQUEST['macrow'];
        $macimg_page    = $_REQUEST['macimg_page'];
        $summary_macrow = $_REQUEST['summary_macrow'];
        $summary_page   = $_REQUEST['summary_page'];

        $albumRow       = $_REQUEST['albumRow'];
        $mouseHei       = $_REQUEST['mouseHei'];
        $mouseWid       = $_REQUEST['mouseWid'];
        $macProximity          = $_REQUEST['macProximity'];
        $macDir                = $_REQUEST['macDir'];
        $macImg_dis            = $_REQUEST['macImg_dis'];
        $mac_Limit             = $_REQUEST['mac_Limit'];
        $mac_facebook_api      = $_REQUEST['mac_facebook_api'];
        $mac_facebook_comment  = $_REQUEST['mac_facebook_comment'];

        $mac_imgdispstyle = $_REQUEST['mac_imgdispstyle'];
        $resizeHei        = $_REQUEST['resizeHei'];
        $resizeWid        = $_REQUEST['resizeWid'];
        $macAlbum_limit   = $_REQUEST['macAlbum_limit'];

        if($get_title['title'] == $get_key)
        {
        $updSet = $wpdb->query("UPDATE " . $wpdb->prefix . "macsettings SET  `macrow` = '$macrow',
         `macimg_page` = '$macimg_page',`summary_macrow` = '$summary_macrow', `summary_page`='$summary_page',
         `albumRow` = '$albumRow', `mouseHei` = '$mouseHei' , `mouseWid` = '$mouseWid',`resizeHei`='$resizeHei',`resizeWid` = '$resizeWid',
         `macProximity` = '$macProximity', `macDir` = '$macDir',`macImg_dis` = '$macImg_dis',`macAlbum_limit`= '$macAlbum_limit',`mac_limit`= '$mac_Limit',
        `mac_imgdispstyle` = '$mac_imgdispstyle',
         `mac_facebook_api` = '$mac_facebook_api', `mac_facebook_comment` = '$mac_facebook_comment' WHERE `macSet_id` = 1");
        echo 'Updated Successfully';
    }
    else
        {
           echo "<script>alert('Get licence Key');</script>";
        }
        }

    $viewSetting = $wpdb->get_row("SELECT * FROM " . $wpdb->prefix . "macsettings");
?>

    <script>
        var mac = jQuery.noConflict();
        jQuery(function(){
            mac("#macSet_upt").click(function() {
                // Made it a local variable by using "var"
                var macrow = document.getElementById("macrow").value;
                var macimg_page = document.getElementById("macimg_page").value;
                var albumRow = document.getElementById("albumRow").value;
                var mouseHei = document.getElementById("mouseHei").value;
                var mouseWid = document.getElementById("mouseWid").value;
                var resizeHei = document.getElementById("resizeHei").value;
                var resizeWid = document.getElementById("resizeWid").value;
                var macProximity = document.getElementById("macProximity").value;
                var mac_facebook_api = document.getElementById("mac_facebook_api").value;

                if(macrow == ""  || macrow == "0" || macimg_page =="" || macimg_page =="0" || albumRow == "" || albumRow == "0" ||
                    resizeHei == "" || resizeHei == "0" || resizeWid =="" || resizeWid == "0" || mouseWid == ""  || mouseWid == "0"
                   ||mouseHei == "" || mouseHei == "0" ||  macProximity == "0" || mac_facebook_api == "" ){
                    document.getElementById("error_msg").innerHTML = 'Please Enter Values For All The Fields ';
                    return false;
                }

            });
        });
    </script>
    <link rel="stylesheet" href="<?php echo $site_url . '/wp-content/plugins/' . dirname(plugin_basename(__FILE__)) . '/css/style.css'; ?>">
    <div class="wrap">
        <div class="icon32" id="icon-options-general"><br></div>
        <h2>Mac Photo Gallery Settings</h2><br />
        <div>Welcome to the Mac Photo Gallery Settings plugin options menu!
            <a href="<?php echo $site_url; ?>/wp-admin/upload.php?page=macAlbum"  style="text-decoration: none"> Create Album </a> &nbsp&nbsp
            <a href="<?php echo $site_url; ?>/wp-admin/upload.php?page=macPhotos&albid=0"  style="text-decoration: none" > Add Photos </a>
        </div>
        <div id="error_msg" style="color:red"></div>
        <form name="macSet" method="POST">
            <div class="macSettings">
                <table>
                    <tr>
                        <td>Columns / Page</td>
                        <td><input type="text" name="macrow" id="macrow" value="<?php echo $viewSetting->macrow; ?>"></td>
                    </tr>
                    <tr>
                        <td>Rows / page</td>
                        <td><input type="text" name="macimg_page" id="macimg_page" value="<?php echo $viewSetting->macimg_page; ?>"></td>
                    </tr>
                    <tr>
                        <td>Multiple Post View Page / Columns</td>
                        <td><input type="text" name="summary_page" id="summary_page" value="<?php echo $viewSetting->summary_page; ?>"></td>
                    </tr>
                     <tr>
                        <td>Multiple Post View Page / Rows</td>
                        <td><input type="text" name="summary_macrow" id="summary_macrow" value="<?php echo $viewSetting->summary_macrow; ?>"></td>
                    </tr>
                    <tr>
                        <td>Album List / Row (Px)</td>
                        <td><input type="text" name="albumRow" id="albumRow" value="<?php echo $viewSetting->albumRow; ?>"></td>
                    </tr>


                    <tr>
                        <td>  Mac Dock  Image Width(Px)</td>
                        <td><input type="text" name="mouseWid" id="mouseWid" value="<?php echo $viewSetting->mouseWid; ?>"></td>
                    </tr>
                     <tr>
                        <td>Mouseover Width/ Height / Row (Px)</td>
                        <td><input type="text" name="mouseHei" id="mouseHei" value="<?php echo $viewSetting->mouseHei; ?>"></td>
                    </tr>


                    <tr>
                        <td> Resizing Height(Px)</td>
                        <td><input type="text" name="resizeHei" id="resizeHei" value="<?php echo $viewSetting->resizeHei; ?>"></td>
                    </tr>


                    <tr>
                        <td>  Resizing Width(Px)</td>
                        <td><input type="text" name="resizeWid" id="resizeWid" value="<?php echo $viewSetting->resizeWid; ?>"></td>
                    </tr>

                    <tr>
                        <td>Proximity</td>
                        <td><input type="text"  name="macProximity" id="macProximity" value="<?php echo $viewSetting->macProximity; ?>"></td>
                    </tr>
                    <tr>
                        <td>Mac_Effect Direction</td>
                        <td><select name="macDir">
                                <option <?php  if ($viewSetting->macDir == '1') { echo 'selected="selected"'; } ?> value="1" >Top</option>
                                <option <?php if ($viewSetting->macDir == '0') { echo 'selected="selected"';  } ?>value="0">Bottom</option>
                        </select></td>
                    </tr>
                    <tr>
                        <td>Image display:</td>
                        <td>
                            <input type="radio" name="macImg_dis" <?php if ($viewSetting->macImg_dis == 'random') { echo 'checked'; } ?> value="random" >Random
                            <input type="radio" name="macImg_dis" <?php if ($viewSetting->macImg_dis == 'order') { echo 'checked'; } ?> value="order">Order
                        </td>
                   </tr>
                    <tr>
                        <td>Number of Albums / Page</td>
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
                        <td> Facebook API Id</td>
                        <td><input type="text" name="mac_facebook_api" id="mac_facebook_api" value="<?php echo $viewSetting->mac_facebook_api; ?>"><br />
                            <div style="font-size:8pt">Enter Facebook URI , For example: http://www.facebook.com/profile.php?id=<strong>100001747038774</strong><br /></div>

                        </td>
                     </tr>
                     <tr>
                        <td>Number of Fb comments / Page</td>
                        <td>
                            <select name="mac_facebook_comment">
                            <option  <?php if ($viewSetting->mac_facebook_comment == '5')  { echo 'selected="selected"'; } ?>value="5">5</option>
                            <option  <?php if ($viewSetting->mac_facebook_comment == '10')  { echo 'selected="selected"'; } ?>value="10">10</option>
                            <option  <?php if ($viewSetting->mac_facebook_comment == '15') { echo 'selected="selected"'; } ?>value="15">15</option>
                            <option  <?php if ($viewSetting->mac_facebook_comment == '20') { echo 'selected="selected"'; } ?>value="20">20</option>
                            <option  <?php if ($viewSetting->mac_facebook_comment == '25') { echo 'selected="selected"'; } ?>value="25">25</option>
                            <option  <?php if ($viewSetting->mac_facebook_comment == '30') { echo 'selected="selected"'; } ?>value="30">30</option>
                            </select>
                       </td>
                   </tr>
                     <tr>
                        <td>Image Display Style [Works only for Chrome]:</td>
                        <td>
                           <input type="radio" name="mac_imgdispstyle" <?php if ($viewSetting->mac_imgdispstyle == '0') { echo 'checked';}?> value="0" >Normal
                           <input type="radio" name="mac_imgdispstyle" <?php if ($viewSetting->mac_imgdispstyle == '1') { echo 'checked';}?> value="1">Rounded corner
                           <input type="radio" name="mac_imgdispstyle" <?php if ($viewSetting->mac_imgdispstyle == '2') { echo 'checked';} ?> value="2">Winged display
                           <input type="radio" name="mac_imgdispstyle" <?php if ($viewSetting->mac_imgdispstyle == '3') { echo 'checked';}?> value="3">Rounded  image
                        </td>
                    </tr>
               <tr>
                    <td>Limit of Image Display[Only For Admin]</td>
                    <td>
                            <select name="mac_Limit">
                            <option <?php if ($viewSetting->mac_limit == '5')  { echo 'selected="selected"'; } ?>value="5">5</option>
                            <option <?php if ($viewSetting->mac_limit == '10') { echo 'selected="selected"'; } ?>value="10">10</option>
                            <option <?php if ($viewSetting->mac_limit == '15') { echo 'selected="selected"'; } ?>value="15">15</option>
                            <option <?php if ($viewSetting->mac_limit == '20') { echo 'selected="selected"'; } ?>value="20">20</option>
                            <option <?php if ($viewSetting->mac_limit == '25') { echo 'selected="selected"'; } ?>value="25">25</option>
                            <option <?php if ($viewSetting->mac_limit == '30') { echo 'selected="selected"'; } ?>value="30">30</option>
                        </select>
                   </td>
                </tr>
                </table>
                <div id="error_msg" style="color:red"></div>
                <div> <p class='submit' style="float:left;"><input class='button-primary' name='macSet_upt' id='macSet_upt' type='submit' value='Update Options'></p></div>
            </div>
        </form>
    </div>
<?php
                        }
                        //End of Album Setting page
 function loadsettings() {
 global $wpdb;
 $insertSettings = $wpdb->query("INSERT INTO " . $wpdb->prefix . "macsettings (`macSet_id`, `macrow`, `macimg_page`, `summary_macrow`, `summary_page`, `albumRow`, `mouseHei`, `mouseWid`, `resizeHei`, `resizeWid`, `macProximity`, `macDir`, `macImg_dis`, `mac_limit`, `macAlbum_limit`, `mac_albumdisplay`, `mac_imgdispstyle`, `mac_facebook_api`, `mac_facebook_comment`) VALUES
(1, 4, 3, 3, 3, 4, 20, 90, 120, 120, 90, 1, 'order', 15, 8, 'no', 1, '', 10);");
 $insertDefault = $wpdb->query("INSERT INTO " . $wpdb->prefix . "macalbum (`macAlbum_id`, `macAlbum_name`, `macAlbum_description`, `macAlbum_image`, `macAlbum_status`, `macAlbum_date`) VALUES
 (1, 'Default', 'Default album', 'star.jpg', 'ON', '2011-07-27 17:11:53')");
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
                            //update_option('HDFLVSettings', HdflvloadSharedefaults());
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
?>
