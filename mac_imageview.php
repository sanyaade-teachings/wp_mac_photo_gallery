<?php
/*
 ***********************************************************/
/**
 * @name          : Mac Doc Photogallery.
 * @version	      : 2.6
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

global $wpdb;
  $uploadDir = wp_upload_dir();
  $site_url = get_bloginfo('url');
  $mac_phtid = $_REQUEST['mac_phtid'];
  $mac_albid = $_REQUEST['mac_albid'];
  $limit = $_REQUEST['limit'];
  $flag = $_REQUEST['flag']; // if the photos from picasa then it wil cal
  $mac_folder = dirname(plugin_basename(__FILE__));
  $path = $uploadDir['baseurl'] . '/mac-dock-gallery';
 $macapi =$wpdb->get_row("SELECT mac_facebook_api,mac_facebook_comment,show_share,show_download FROM " . $wpdb->prefix . "macsettings ");
  
$phtDis    = $wpdb->get_row("SELECT * FROM " . $wpdb->prefix . "macphotos where macPhoto_status='ON' and macAlbum_id='$mac_albid' ORDER BY macPhoto_sorting ASC  LIMIT $limit,1");
$mac_download = explode('_thumb',$phtDis->macPhoto_image);

$mac_count = $wpdb->get_row("SELECT count(*) as mac_count
                             FROM " . $wpdb->prefix . "macphotos WHERE macAlbum_id='$phtDis->macAlbum_id' and macPhoto_status='ON'");
								$get_macImage = explode('_thumb',$phtDis->macPhoto_image);
                                $file_image = $uploadDir['basedir'] . '/mac-dock-gallery/' . $get_macImage[0].$get_macImage[1];
								$downloadImg = $get_macImage[0].$get_macImage[1];
                            if (file_exists($file_image)) {
                                $file_image = $path . '/' . $get_macImage[0].$get_macImage[1];
                            }
                            else {
                            	
                            	 $get_macImage = explode('.',$phtDis->macPhoto_image);
                            	 $phtoExten = $get_macImage[1];  //get photo extension like .jpg , .png
                            	 $photoId  = $phtDis->macPhoto_id;
                            	$downloadImg = $bigImageName = $photoId.'.'.$phtoExten;
                            	 $file_image = $uploadDir['basedir'].'/mac-dock-gallery/'.$bigImageName;
                            	 if (file_exists($file_image))
                            	 {
                            	 	 $file_image = $path.'/'.$bigImageName;
                            	 }
                            	 else{
                            	 	$file_image = $site_url . '/wp-content/plugins/' . dirname(plugin_basename(__FILE__)) . '/uploads/no-photo.png'; 	
                            	 }
                                 
                            }
                              $p_link = $file_image;

                              $macPhoto_desc = $phtDis->macPhoto_desc;
                              if($phtDis->macPhoto_desc != '')
                              {
                                  $macPhoto_desc = $phtDis->macPhoto_desc;
                              }
                              else
                              {
                                  $macPhoto_desc = 'No Description is available';
                              }
                              $phName = $phtDis->macPhoto_name;
                              $total = $mac_count->mac_count;
  	
   	
$mac_redirect = urlencode($_SERVER['HTTP_REFERER']);  

$fbUrl  = 'http://www.facebook.com/dialog/feed?app_id='.$macapi->mac_facebook_api.'&description='.urlencode($macPhoto_desc).'&picture='.urlencode($p_link).'&name='.$phName.'&message=Comments&link='.$mac_redirect.'&redirect_uri='.$mac_redirect;



$next = $limit  + 1;

$prev = $limit - 1;

if($next >= $total){
	$next = $limit;
}

if($prev<0){
	$prev = -1;
}

?>
 
<script src="http://static.ak.fbcdn.net/connect.php/js/FB.Share" type="text/javascript"></script>


<style type="text/css">
.floatleft {float:left;}
.floatright{float:right;}
.clear{clear:both;}
.download{
width:60px;padding: 5px 5px 2px 0;

}.share-font a{color: #3B5998;font-family: tahoma;font-weight: normal;text-decoration: underline;font-size:11px;}
.fb-title{padding: 10px 10px 0 0px;font-size: 12px;color: #3B5998;font-family: lucida grande,tahoma,verdana,arial,sans-serif;font-weight: bold;}
.fb-title span{display:block;}
.border-bottom {border-bottom:1px solid #ccc;}
.fb-date{color: #666;font-family: tahoma;font-weight: normal;font-size: 11px;}
#facebox .footer {border:none;}
.fbcomment{text-align:center;padding:10px 0 0 5px;}
.fb-left{float:left; padding: 0px 0px 0px 10px;}
.fb-right{float:right;}
.fb-center{width:440px;float:left;}
.mac-whole-content{width:920px;}
.mac-gallery-image{width: 920px;margin: 0 auto;text-align: center;display:table-cell;vertical-align: middle;height:520px;}
.mac-gallery-image img{vertical-align: middle;max-width:920px;margin:10px 0;max-height: 500px;}
.fb-desc-head {padding: 10px 10px 0 10px;font-size: 12px;color: #3B5998;font-family: 'lucida grande',tahoma,verdana,arial,sans-serif;font-weight: bold;}
.fb-desc{padding: 0px 10px 10px 0px;font-size: 12px;color: #666;font-family: 'lucida grande',tahoma,verdana,arial,sans-serif;font-weight: normal;width:600px;}
.left-arrow,right-arrow{width:10%;vertical-align:middle;cursor:pointer;width:30px;}
.right-arrow a{cursor:pointer;}
.top-content{background:#000;visibility: visible;display:block;height:520px;}

.mac-close-image a{float:right;}
.mac-close-image{background:#000;position: relative;}
.macpopup_bottom{background:#fff;width: 920px;}


</style>

   
  <div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) {return;}
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/en_US/all.js#xfbml=1";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));


function loadNextImages(url){

      
	apptha.get(url,"",function(result){
		apptha("#appthaContent").html(result);
		});
              // alert(apptha('img').height()/2);
                
}
</script>
 <?php
 $height= 0;   if($flag != 3){
               list($width, $height) = getimagesize($file_image);
 				}
				$pluginDirPath = $site_url .'/wp-content/plugins/' . dirname(plugin_basename(__FILE__)) ;

                ?>
		<div class="mac-whole-content" style="position:relative;" >
<?php switch($flag) //for showing picasa photos slide show
{
			  //left arrow
	case 1 :  $sendingLUrl = $pluginDirPath.'/mac_imageview.php?mac_phtid='.$prev.'&mac_albid='.$macAlbId.'&limit='.$prev.'&flag=1&total='.$total.'&selfReload=1&date='.$photoDate;	
			  //right arrow
		 	  $sendingRUrl = $pluginDirPath.'/mac_imageview.php?mac_phtid='.$next.'&mac_albid='.$macAlbId.'&limit='.$next.'&flag=1&total='.$total.'&selfReload=1&date='.$photoDate;
	break;
	case 2 :  $sendingLUrl = $pluginDirPath.'/mac_imageview.php?mac_phtid='.$prev.'&mac_albid='.$macAlbId.'&limit='.$prev.'&flag=2&total='.$total;

			  $sendingRUrl = $pluginDirPath.'/mac_imageview.php?mac_phtid='.$next.'&mac_albid='.$macAlbId.'&limit='.$next.'&flag=2&total='.$total;
			  
	break;	
	case 3 :
			  $sendingLUrl = $pluginDirPath.'/mac_imageview.php?mac_phtid='.$prev.'&mac_albid='.$macAlbId.'&limit='.$prev.'&flag=3&total='.$total;

			  $sendingRUrl = $pluginDirPath.'/mac_imageview.php?mac_phtid='.$next.'&mac_albid='.$macAlbId.'&limit='.$next.'&flag=3&total='.$total;
		
		
	break;	
	default:   $sendingLUrl = $pluginDirPath.'/mac_imageview.php?mac_albid='.$mac_albid.'&limit='.$prev;
			   $sendingRUrl = $pluginDirPath.'/mac_imageview.php?mac_albid='.$mac_albid.'&limit='.$next;
			   $photoDate = date("d-m-Y",strtotime($phtDis->macPhoto_date));
			   $photoName = $phtDis->macPhoto_name;
			   	
}
	
?>	
	<div class="top-content clearfix" id="top-content">
		<div class="left-arrow floatleft" id="left-arrow"
			style="position: absolute; top: 244px;">

			<a <?php if($prev > -1  ):?>
				onclick="loadNextImages('<?php echo $sendingLUrl; ?>')"
				<?php endif;?>> <img
				<?php if($prev <= -1  ) echo ' style="opacity:0.3;filter: alpha(opacity=30); "'; ?>
				src="<?php echo $site_url . '/wp-content/plugins/' . dirname(plugin_basename(__FILE__)) . '/uploads/sprite_prev.png';?>" />
			</a>
		</div>

		<div class="mac-gallery-image" style="min-height: 520px;">

			<img src="<?php echo $file_image; ?>" />
		</div>


		<div class="right-arrow floatleft"
			style="position: absolute; top: 244px; right: -2px; width: 30px;">
			<a <?php if($limit < $total-1): ?>
				onclick="loadNextImages('<?php echo $sendingRUrl; ?>')"
				<?php endif;?>> <img
				<?php  if($limit >= $total-1) echo ' style="opacity:0.3;filter: alpha(opacity=30); "';  ?>
				src="<?php echo $site_url . '/wp-content/plugins/' . dirname(plugin_basename(__FILE__)) . '/uploads/sprite_next.png';?>" />
			</a>
		</div>
	</div>
<?php //else hend hear?>

	<div class="clear"></div>
    <div class="macpopup_bottom">
        <div class="fb-left">
    <div class="fb-title fb-left"><?php echo $photoName; ?></div><div class="fb-date fb-left"> on <?php 
    		if($photoDate != '')
                echo $photoDate;//
            ?></div>
 <div class="clear"></div>

    <!-- <div class="fb-center">
 <?php   //if($macapi->show_share == 'show') {?>
 <div class="floatleft fbcomment">
    
   <fb:comments href="<?php echo $site_url.'/?page_id='.$returnfbid.'&macphid='.$mac_phtid; ?>" width="445" height="" num_posts="<?php echo $macapi->mac_facebook_comment;?>" xid="<?php echo $mac_phtid; ?>"></fb:comments>
       </div> 
       <?php //} ?>
    </div> -->

   <div>
     <div class="fb-desc-head">Description :
          <div class="fb-desc" ><span><?php
         
        // echo $limit.'--limit--'.$prev.'--pre---'.'--next--'.$next.'---tot--'.$total; 
      //  echo '<pre>';print_r($phtDis);print_r($_REQUEST);
        echo $macPhoto_desc;?></span> </div></div>
    </div>
    </div>
<div class="fb-right">
 <?php  if($macapi->show_download == 'allow')  { ?>
    <div class="download share-font"><a href="<?php echo $site_url.'/wp-content/plugins/'.$mac_folder.'/macdownload.php?albid='.$downloadImg;?>">Download</a></div>
       <?php  } if($macapi->mac_facebook_api != '')  { ?>
     <div class="share-font"><a href="<?php echo $fbUrl; ?>" target="_blank">Share</a></div>
     <?php } ?>
    </div>
    <div class="clear"></div>
      
    </div>
  </div>
  <?php 
  
  function  getPicasaRequiredImagesSize($photo , $imgSiz)
	 {
		   	   $kkk = explode('/',$photo);
		   	   $totalCount =  count($kkk);
		   	   $imageSizeis =  $kkk[$totalCount - 2];
		   	   $imgXY = ereg_replace("[^0-9]", "",$imageSizeis);
		   	   $kkk[$totalCount - 2] = $newImageSige = str_replace($imgXY,$imgSiz, $imageSizeis);
			   $kkk = implode('/', $kkk);
			   return $kkk;
	}
  
  
  ?>