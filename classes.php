<?php
/**
 * @name        Mac Doc Photogallery.
 * @version	2.2: classes.php 2011-08-15
 * @package	apptha
 * @subpackage  mac-doc-photogallery
 * @author      saranya
 * @copyright	Copyright (C) 2005 - 2011 Open Source Matters, Inc. All rights reserved.
 * @license	GNU General Public License version 2 or later; see LICENSE.txt
 * @abstract    The Class file of calling Mac Photo Gallery.
 * */

class contusMacgallery
{
function macEffectgallery($arguments= array(), $wid)
  {
      if($wid=='')
      $wid='pirobox_gall';
      global $wpdb;
      global $t;
      $site_url = get_bloginfo('url');
      $uploadDir = wp_upload_dir();
      $path = $uploadDir['baseurl'].'/mac-dock-gallery';
?>
                                          <!-- For the mac Effect and carousel -->

<link rel="stylesheet" href="<?php echo $site_url . '/wp-content/plugins/' . dirname(plugin_basename(__FILE__)) . '/css/images.css'; ?>" type="text/css" media="screen" />
<link rel="stylesheet" href="<?php echo $site_url . '/wp-content/plugins/' . dirname(plugin_basename(__FILE__)) . '/css/style.css'; ?>">

<script type="text/javascript" src="<?php echo $site_url . '/wp-content/plugins/' . dirname(plugin_basename(__FILE__)) . '/js/ajax.js'; ?>"></script>
<script type="text/javascript" src="<?php echo $site_url . '/wp-content/plugins/' . dirname(plugin_basename(__FILE__)) . '/js/lib/jquery-1.4.2.min.js';?>"></script>
<script type="text/javascript" src="<?php echo $site_url . '/wp-content/plugins/' . dirname(plugin_basename(__FILE__)) . '/js/interface.js';?>"></script>
<script type="text/javascript" src="<?php echo $site_url . '/wp-content/plugins/' . dirname(plugin_basename(__FILE__)) . '/js/lib/jquery.jcarousel.min.js';?>"></script>
<link rel="stylesheet" type="text/css" href="<?php echo $site_url . '/wp-content/plugins/' . dirname(plugin_basename(__FILE__)) . '/css/ie7/skin.css';?>" />

                                          <!-- For the Popup Pirobox -->
<link href="<?php echo $site_url . '/wp-content/plugins/' . dirname(plugin_basename(__FILE__)) . '/css_pirobox/style_1/style.css'; ?>" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="<?php echo $site_url . '/wp-content/plugins/' . dirname(plugin_basename(__FILE__)) . '/js/pirobox/pirobox_extended.js'?>"></script>
                                           <!-- End of the Popup Pirobox -->

                                             <!--Mac Gallery common js -->
<script type="text/javascript" src="<?php echo $site_url . '/wp-content/plugins/'.dirname(plugin_basename(__FILE__)).'/js/macGallery.js';?>"></script>
<script type="text/javascript" src="http://connect.facebook.net/en_US/all.js#xfbml=1"></script>

                     <style type="text/css">
                      #sample {
                     -ms-filter: "progid:DXImageTransform.Microsoft.Fade(duration=3)";
                     filter :progid:DXImageTransform.Microsoft.Fade(duration=3);
                     width: 175px;
                     height: 230px;
                     padding: 10px;
                     color: white;
                     }
                     #content img{

                     margin: 0;
                     max-width: 640px;
                     width: 100%;
                     }
                    </style>
 <?php
$site_url = get_bloginfo('url');
$aid = '';

 if ($_REQUEST['albid'] != '')
    {
    $aid = $_REQUEST['albid'];        //If  request the album from the gallery display page
    }
 else if ($arguments['albid'] != '')
   {
       $aid = $arguments['albid'];     //If  request in the admin page to display the mac images
   }
 else if($arguments['walbid'] != '')  //If  request in the widgets to display the mac images
   {
      $aid = $arguments['walbid'];
      $n = $arguments['cols'];
      $no_of_row = $arguments['row'];

   }
    if ($aid != '')    // If any albid is get then the mac images respective to the albums will be displayed
       {
          $macAlbid = $aid;
          $macSetting = $wpdb->get_row("SELECT * FROM " . $wpdb->prefix . "macsettings"); // Full settings get form the admin
                                     /* display randomly */
                                    if(($macSetting->macImg_dis == 'random'))
                                    {
                                            $where = 'ORDER BY RAND()';
                                    }
                                    else
                                    {
                                         $where = 'ORDER BY macPhoto_sorting ASC';
                                    }

                                    if ($arguments['row'] != '' && $arguments['cols'] != '')
                                    {
                                         $n = $arguments['cols'];
                                         $no_of_row = $arguments['row'];
                                         $albid = $arguments['albid'];
                                         $itemwidth = $macSetting->mouseWid;
                                         $phtDis = $wpdb->get_results("SELECT * FROM " . $wpdb->prefix . "macphotos
                                                                       WHERE macAlbum_id='$albid' and macPhoto_status='ON' $where");
                                    }
                                     else
                                    {
                                         $n = $macSetting->macrow;
                                         $no_of_row = $macSetting->macimg_page;
                                         $albid = $arguments['albid'];
                                         $itemwidth = $macSetting->mouseWid;
                                         $phtDis = $wpdb->get_results("SELECT * FROM " . $wpdb->prefix . "macphotos
                                                                       WHERE macAlbum_id='$albid' and macPhoto_status='ON' $where");
                                    }

                                    if(is_home())
                                    {
                                        $n =  $macSetting->summary_page;
                                        $no_of_row =$macSetting->summary_macrow;
                                        $itemwidth = $macSetting->mouseWid;
                                        $limit =$macSetting->summary_macrow * $macSetting->summary_page;
                                        $phtDis = $wpdb->get_results("SELECT * FROM " . $wpdb->prefix . "macphotos WHERE macAlbum_id='$macAlbid' and macPhoto_status='ON' $where limit 0,$limit");
                                    }
                                    if($arguments['walbid'] != '' )
                                    {
                                        $walbid = $arguments['walbid'];
                                        $n = $arguments['column'];
                                        $no_of_row = $arguments['rows'];
                                         $itemwidth = $arguments['width'];
                                        $phtDis = $wpdb->get_results("SELECT * FROM " . $wpdb->prefix . "macphotos
                                         WHERE macAlbum_id='$walbid' and macPhoto_status='ON' $where");
                                    }

                                     else if($_REQUEST['albid'] != '')
                                    {
                                          $itemwidth = $macSetting->mouseWid;
                                     //Pagination
                                        function listPagesNoTitle($args)
                                        { //Pagination
                                            if ($args) {
                                             $args .= '&echo=0';
                                            } else {
                                             $args = 'echo=0';
                                            }
                                            $pages = wp_list_pages($args);
                                            echo $pages;
                                        }

                                        function findStart($limit) { //Pagination
                                            if (!(isset($_REQUEST['pages'])) || ($_REQUEST['pages'] == "1")) {
                                                $start = 0;
                                                $_GET['pages'] = 1;
                                            } else {
                                                $start = ($_GET['pages'] - 1) * $limit;
                                            }
                                            return $start;
                                        }

                                        /*
                                         * int findPages (int count, int limit)
                                         * Returns the number of pages needed based on a count and a limit
                                         */

                                        function findPages($count, $limit) { //Pagination
                                            $pages = (($count % $limit) == 0) ? $count / $limit : floor($count / $limit) + 1;
                                            if ($pages == 1) {
                                                $pages = '';
                                            }
                                            return $pages;
                                        }
                                        /*
                                         * string pageList (int curpage, int pages)
                                         * Returns a list of pages in the format of "Â« < [pages] > Â»"
                                         * */
                                        function pageList($curpage, $pages, $albid) {
                                            //Pagination
                                              $site_url = get_bloginfo('url');
                                            $page_list = "";
                                            if ($search != '') {

                                                $self = '?page_id=' . get_query_var('page_id') . '&albid=' . $albid;
                                            } else {
                                                $self = '?page_id=' . get_query_var('page_id') . '&albid=' . $albid;
                                            }
                                            if (($curpage - 1) > 0) {
                                                $page_list .= "<a href=\"" . $self . "&pages=" . ($curpage - 1) . "\" title=\"Previous Page\" class='macpag_left'>
                                                    <img src=".$site_url."/wp-content/plugins/". dirname(plugin_basename(__FILE__)) . "/images/left.png></a> ";
                                            }
                                            /* Print the Next and Last page links if necessary */
                                            if (($curpage + 1) <= $pages) {
                                                $page_list .= "<a href=\"" . $self . "&pages=" . ($curpage + 1) . "\" title=\"Next Page\"  class='macpag_right'>
                                                    <img src=".$site_url."/wp-content/plugins/". dirname(plugin_basename(__FILE__)) . "/images/right.png></a> ";
                                            }
                                               $page_list .= "</td>\n";
                                               return $page_list;
                                        }

                                        /*
                                         * string nextPrev (int curpage, int pages)
                                         * Returns "Previous | Next" string for individual pagination (it's a word!)
                                         */

                                        function nextPrev($curpage, $pages) { //Pagination
                                            $next_prev = "";

                                            if (($curpage - 1) <= 0) {
                                                $next_prev .= "Previous";
                                            } else {
                                                $next_prev .= "<a href=\"" . $_SERVER['PHP_SELF'] . "&pages=" . ($curpage - 1) . "\">Previous</a>";
                                            }

                                            $next_prev .= " | ";

                                            if (($curpage + 1) > $pages) {
                                                $next_prev .= "Next";
                                            } else {
                                                $next_prev .= "<a href=\"" . $_SERVER['PHP_SELF'] . "&pages=" . ($curpage + 1) . "\">Next</a>";
                                            }
                                            return $next_prev;
                                        }

                                                //End of Pagination
                                            $sqlphts = mysql_query("SELECT * FROM " . $wpdb->prefix . "macphotos where macAlbum_id='$macAlbid'
                                                                         and macPhoto_status='ON'");
                                            $limit =  $n * $no_of_row ;
                                            $start = findStart($limit);
                                            $w = "LIMIT " . $start . ", " . $limit;
                                            $count = mysql_num_rows($sqlphts);
                                            /* Find the number of pages based on $count and $limit */
                                            $pages = findPages($count, $limit);
                                            /* Now we use the LIMIT clause to grab a range of rows */

                                            /* display in order */
                                             $phtDis = $wpdb->get_results("SELECT * FROM " . $wpdb->prefix . "macphotos where macAlbum_id='$macAlbid'
                                                                         and macPhoto_status='ON' $where $w");
                                     }
                                    //Parameters for mac Effect

                                    $maxwidth = $macSetting->mouseHei;
                                    $prox = $macSetting->macProximity;
                                    $largewidth = 0;
                                    $largeheight = 0;
                                    $theight = $macSetting->mouseHei;
                                    $twidth = $macSetting->mouseHei;
                                    $direction = $macSetting->macDir;
                                    $imgwidth = $macSetting->mouseWid;
                                    $total = count($phtDis);
                                    $totalimages = count($phtDis);
                                    if (($total / $n) < $no_of_row) {
                                     $no_of_row = ceil($total / $n);
                                    }
                                    $totalh = $twidth + $imgwidth;
                                    $preheight = (($totalh + 5) * $no_of_row);
                                    $width_total = ($macSetting->macrow * $macSetting->mouseWid)+(50).'px';
                                    $page_count = ($no_of_row*($macSetting->mouseWid)/2-10).'px';
                                                                //Enf of parameters


                                    $div = '<style type="text/css">';

 /* Normal image display style */
        if($macSetting->mac_imgdispstyle == 0)
        {
             $div .= '.imgcorner{
            border-radius: 0px;
            -moz-border-radius :0px;
            -webkit-border-radius: 0px;
            }';
        }
        /* Rounded corner image display style */
        else if($macSetting->mac_imgdispstyle == 1)
        {
        $div .= '.imgcorner{
            border-radius: 10px;
            -moz-border-radius :10px;
            -webkit-border-radius: 10px;
            }';
        }
         /* Winged display style */
        else if($macSetting->mac_imgdispstyle == 2)
        {
        $div .= '.imgcorner{

            -moz-border-radius: 1em 4em 1em 4em;
            border-radius: 1em 4em 1em 4em;
             -webkit-border-top-left-radius: 2em 0.5em;
            -webkit-border-top-right-radius: 1em 3em;
            -webkit-border-bottom-right-radius: 2em 0.5em;
            -webkit-border-bottom-left-radius: 1em 3em;


            }';
        }
         /* Rounded  image display  */
        else if($macSetting->mac_imgdispstyle == 3)
        {
         $div .= '.imgcorner{
            border-top-left-radius:4em;
            border-top-right-radius:4em;
            border-bottom-right-radius:4em;
            border-bottom-left-radius:4em;

            -moz-border-radius-topleft: 4em;
            -moz-border-radius-topright: 4em;
            -moz-border-radius-bottomright: 4em;
            -moz-border-radius-bottomleft: 4em;

            -webkit-border-top-left-radius:4em;
            -webkit-border-top-right-radius:4em;
            -webkit-border-bottom-right-radius:4em;
            -webkit-border-bottom-left-radius:4em;
            }';
        }
       // The black bg color for the mac effect images from the gallery
        if($_REQUEST['albid'] != '' && $arguments['walbid'] == '' && $arguments['albid'] == '')
        {
        $div .= '#content #imgwrapper{
                height:'.(($no_of_row*$itemwidth)).'px;

                }
                #imgmain
                {
                 width: '.$width_total.';
                 margin: 0px auto;
                }

                .page_list a
                {
                top:'. $page_count.';
                left: -25px;
                }


.jcarousel-skin-ie7 .jcarousel-item
{
   width:100px;

   background-color:black;
   text-decoration: none;

}';
}

           /* if direction is top */
        if($direction == 0)
        {
            $position = "top:";
            $positionvalue = 0;
        }
        /* if direction is bottom */
        else
        {
            $position = "bottom:";
            $positionvalue = ($itemwidth * $no_of_row);
        }
        for($l = 1;$l <= $no_of_row; $l++)
        {
        $div .= '#dock'.$t.' {
                width: 100%;
                left: 0px;
                position: relative;
                top:'.$positionvalue.'px;
       }

            .dock-container'.$t.' {
                position: absolute;
            }

            a.dock-item'.$t.' {
                display: block;
                font: bold 12px Arial, Helvetica, sans-serif;
                width: 40px;

                color: #000;
                '.$position.' 0px;
                position: absolute;
                text-align: center;
                text-decoration: none;
            }

            .dock-item'.$t.' span {
                display: none;
            }
            .dock-item'.$t.' img {
                border: none;
                margin: 5px 0px 0px ;
                width: 100%;
            }';

        if($direction == 0)
        {
        $positionvalue = $positionvalue + $itemwidth;
        }
        else
        {
        $positionvalue = $positionvalue - $itemwidth;
        }
         $t++;
        }
        $div .= '</style>';
                                    $y = 0;
                                    foreach ($phtDis as $phtDisplay) {  // Getting all the values and stored in array
                                        $imgsrc[$y]['macPhoto_image'] = $phtDisplay->macPhoto_image;
                                        $imgsrc[$y]['macPhoto_id']    = $phtDisplay->macPhoto_id;
                                        $imgsrc[$y]['macPhoto_name']  = $phtDisplay->macPhoto_name;
                                        $imgsrc[$y]['macPhoto_desc']  = $phtDisplay->macPhoto_desc;
                                        $imgsrc[$y]['macPhoto_date']  = $phtDisplay->macPhoto_date;
                                        $y++;
                                    }
                                       if($arguments['walbid'] == '' && $arguments['albid'] == '')
                                        {
                                           $div .= '<div id="mac_all"><div id="mac_id">';
                                        }
                                       else if($arguments['walbid'] != '' )
                                       {
                                           $div .= '<div class="lfloat"><div>';
                                       }
                                       else
                                       {
                                            $div .= '<div><div>';
                                       }
                                    $mac_album = $wpdb->get_row("SELECT macAlbum_name,macAlbum_description FROM " . $wpdb->prefix . "macalbum WHERE macAlbum_id ='$macAlbid'");
                                    $height = $no_of_row * $itemwidth ;
                                      if($arguments['walbid'] == '' && $arguments['albid'] == '')
                                    {
                                     $div .= '<div style="margin:0px;padding:0px;color:#0D3573;font-weight:bold;font-family:arial;"> '.$mac_album->macAlbum_name.'</div>';
                                    }
                                    $div .= '<div id="imgwrapper">';
                                      if (count($phtDis) == '0')
                                    {
                                    $div .= '<h4> No images in this album</h4>';
                                    }

                                    $div .= '<div id="imgmain" style="height:'.$height.'px;">';

                                    $div .= '<div class="clearfix" style="position:relative;z-index:9999;float:left;">';

                                    $m = $n - 1;
                                    $e = $t - 1;
                                    for ($j = $no_of_row; $j >= 1; $j--) {
                                        $k = 1;
                                        $s = $m;
                                        if ($s >= $totalimages

                                            )$s = $totalimages - 1;
                                        if ($direction == 0) {
                                            if ($total % $n != 0) {
                                                $o = $total % $n;
                                                // echo 'o='.$o;
                                                if ($o == 0) {
                                                    $o = $n;
                                                } else {
                                                    $s = $o - 1;
                                                }
                                                $m = $s;
                                            }
                                            else
                                                $o=$n;
                                        }
                                        else {
                                            if ($total % $n != 0) {
                                                $o = $n;
                                            } else {

                                                $o = $total % $n;
                                                if ($o == 0) {
                                                    $o = $n;
                                                } else {
                                                    $s = $o - 1;
                                                }
                                                $m = $s;
                                            }
                                        }
                                        if ($direction != 0) {
                                          //  $u = $s - $n;
                                            $u= $s;
                                            if ($u <= 0) {

                                                $s = 0;
                                            } else {
                                                $s = ($m - $n) + 1;
                                            }
                                        }
                                        $div .='<div class="dock" id="dock' . $e . '">';
                                        $div .='<div class="dock-container' . $e . '">';

                                        for ($i = $k; $i <= $total; $i++) {
                                            $l= $totalimages - 1 - $s;
                                            if ($k <= $o) {
                                                $extense = explode('.', $imgsrc[$s]['macPhoto_image']);
                                                $bigImg[$s] = $imgsrc[$s]['macPhoto_id'] . '.' . $extense[1];  //Getting the big image path
                                                //Dock Effect Images
                  $div .='<a class="'.$wid.' lightbox dock-item' . $e . '" rel="gallery" title="' . $imgsrc[$s]['macPhoto_name'] . '"
                  name="'.$imgsrc[$s]['macPhoto_desc'].'" date="'. $imgsrc[$s]['macPhoto_date'].'" albname="'.$mac_album->macAlbum_name.'"
                  macapi_id="'.$macSetting->mac_facebook_api.'"
                  href="' . $path . '/' . $bigImg[$s] . '"
                  onclick=javascript:fbcomments("' . $imgsrc[$s]['macPhoto_id'] . '","' . $imgsrc[$s]['macPhoto_name'] . '","' . $site_url . '") />

                   <div class="dock_img_space"><img class="imgcorner" title="' . $imgsrc[$s]['macPhoto_name'] . '"
                   src="' . $path . '/' . $imgsrc[$s]['macPhoto_image'] . '"
                   alt="" width="' . $twidth . '"> </div>
                   <span></span></a>';
                                                if ($direction == 0)
                                                    {
                                                        $s--;
                                                    } else
                                                    {
                                                        $s++;
                                                    }
                                                }
                                                else
                                               {
                                                $total = $total - $k + 1;
                                                 break;
                                               }
                                            $k++;
                                        }
                                        $div .= '</div>';
                                        $div .=' </div>';
                                        $m = $m + $n;
                                         $e--;
                                    }
                                    $div .=' </div>';
                                       if($_REQUEST['albid'] != '' && $arguments['walbid'] == '' && $arguments['albid'] == '') // mac effect pagination
                                    {
                                     $pagelist = pageList($_GET['pages'], $pages, $_GET['albid']);
                                    $div .= '<span class="page_list">' . $pagelist . '</span>';
                                    }
                                    $div .= '</div>';

                                    $div .= '</div>';


                                      if($arguments['walbid'] == '' && $arguments['albid'] == '')
                                    {

                                      if($mac_album->macAlbum_description == '')
                                      {
                                        $div .= '<div id="macshow"></div>';
                                      }
                                      else
                                      {
                                        $div .= '<div id="macshow"><span>Description:</span>'.$mac_album->macAlbum_description.'</div>';
                                      }

// Horizontal Carosoule
 $macGallid =$wpdb->get_var("SELECT ID FROM " . $wpdb->prefix . "posts WHERE post_content= '[macGallery]'");
 $div .='<div class="album_carosole"><h4 style="margin:0px">ALBUM</h4></div>';
 $div .= '<div id="mac_slider" >';
 $div .= '<ul id="second-carousel" class="first-and-second-carousel jcarousel-skin-ie7">';

 // Default selected first album
   $get_albid     = $_GET['albid'];
   $get_album_row = $wpdb->get_row("SELECT * FROM " . $wpdb->prefix ."macalbum WHERE macAlbum_id='$get_albid'");
   $photoCount    = $wpdb->get_var("SELECT count(*) FROM " . $wpdb->prefix . "macphotos WHERE macAlbum_id='$get_album_row->macAlbum_id' and macPhoto_status='ON'");
             if($get_album_row->macAlbum_image == '')
             {
                $div .='<li><a href="' . $site_url . '?page_id='.$macGallid.'&albid='.$get_album_row->macAlbum_id.'"><img title="' . $get_album_row->albumname . '" src="' . $site_url . '/wp-content/plugins/' . dirname(plugin_basename(__FILE__)) . '/uploads/star.jpg"
                              alt="" style="height:100px;"/>
                              <span class="carousel_lefttxt">'.substr($get_album_row->macAlbum_name,0,15).'</span></li></a>';

             }
             else
             {
               $div .='<li><a href="' . $site_url . '?page_id='.$macGallid.'&albid='.$get_album_row->macAlbum_id.'"><img title="' . $get_album_row->albumname . '" src="' . $path . '/'.$get_album_row->macAlbum_image.'"
                              alt="" style="height:100px;"/>
                              <span class="carousel_lefttxt">'.substr($get_album_row->macAlbum_name,0,15).'</span></li></a>';

             }


 // All other  albums
 $album_results = $wpdb->get_results("SELECT * FROM  " . $wpdb->prefix . "macalbum WHERE macAlbum_id !='$get_albid'");
          foreach($album_results as $dis_results)
          {
              $photoCount = $wpdb->get_var("SELECT count(*) FROM " . $wpdb->prefix . "macphotos WHERE macAlbum_id='$dis_results->macAlbum_id' and macPhoto_status='ON'");
             if($dis_results->macAlbum_image == '')
             {
             $div .='<li><a href="' . $site_url . '?page_id='.$macGallid.'&albid='.$dis_results->macAlbum_id.'">
                        <img title="' . $dis_results->albumname . '" src="' . $site_url . '/wp-content/plugins/' . dirname(plugin_basename(__FILE__)) . '/uploads/star.jpg"
                         alt=""  style="height:100px;filter:alpha(opacity=30);-moz-opacity:0.3;-khtml-opacity: 0.3;opacity: 0.3;" />
                         <span class="carousel_lefttxt">'.substr($dis_results->macAlbum_name,0,15).'</span></li></a>';


             }
             else
             {
              $div .='<li><a href="' . $site_url . '?page_id='.$macGallid.'&albid='.$dis_results->macAlbum_id.'">
                        <img title="' . $dis_results->albumname . '" src="' . $path . '/'.$dis_results->macAlbum_image.'"
                         alt=""  style="height:100px;filter:alpha(opacity=30);-moz-opacity:0.3;-khtml-opacity: 0.3;opacity: 0.3;" />
                         <span class="carousel_lefttxt">'.substr($dis_results->macAlbum_name,0,15).'</span></li></a>';
             }
          }
  $div .= '</ul>';

  $div .= '</div>';
    }
 $div .= '</div></div>';
                               // else end for no images in album
                                    //End of carosoule
                                    $albRows = 1;
                                    $alignment = 'left';
                                    $valign = 'top';
                                    $halign = 'left';
                                    $folder = dirname(plugin_basename(__FILE__));
                                    global $d;
                                    ?>
                                       <script type="text/javascript">
                                        var mac = jQuery.noConflict();
                                        var site_url,mac_folder,numfiles;
                                        site_url = '<?php echo $site_url; ?>';
                                        mac_folder  = '<?php echo $folder; ?>';
                                        appId = '<?php echo $macSetting->mac_facebook_api;?>';

                                        var docinarr<?php echo $t; ?> = <?php echo $t-1; ?>;
                                        var totalrec<?php echo $t; ?> = <?php echo $no_of_row;?>;

                                        function maceffect<?php echo $t; ?>()
                                        {

                                            for(k=docinarr<?php echo $t; ?>;k>(docinarr<?php echo $t; ?>-totalrec<?php echo $t; ?>);k--){

                                                mac('#dock'+k).Fisheye({
                                                    maxWidth: <?php echo $maxwidth; ?>,
                                                    items: 'a',
                                                    itemsText: 'span',
                                                    container: '.dock-container'+k,
                                                    itemWidth: <?php echo $itemwidth; ?>,
                                                    proximity: <?php echo $prox; ?>,
                                                    alignment : '<?php echo $alignment; ?>',
                                                    valign: 'top',
                                                    halign : '<?php echo $halign; ?>'
                                                });

                                            }


//
//                                            mac(document).ready(function() {
//                                                mac().piroBox_ext({
//                                                    piro_speed : 700,
//                                                    bg_alpha : 0.5,
//                                                    piro_scroll : true // pirobox always positioned at the center of the page
//                                                    //open_all  :'.piro_loader'
//                                                });
//                                                //piro_capt.animate_html();
//                                             });


                                        }
window.onload = function(){
 mac().piroBox_ext({
                                                    piro_speed : 700,
                                                    bg_alpha : 0.5,
                                                    piro_scroll : true // pirobox always positioned at the center of the page
                                                    //open_all  :'.piro_loader'
                                                });
}

                                    </script>
                                    <script>
                                        mac(document).ready(function(){
                                            maceffect<?php echo $t; ?>();

                                        });
                                    </script>

                                    <script> function getfacebook()
                                        {
                                            FB.init({appId:<?php echo $macSetting->mac_facebook_api;?> , status: true, cookie: true,
                                                xfbml: true});
                                        }
                                    </script>
                            <script>
                                    var mac = jQuery.noConflict();
  mac(document).ready(function() {
    // Initialise the first and second carousel by class selector.
	// Note that they use both the same configuration options (none in this case).
	mac('.first-and-second-carousel').jcarousel();

});</script>
<?PHP
                                } // if end for Mac dock images  display
                                //Album View page started
                                else {

                                    //Pagination
                                function listPagesNoTitle($args) { //Pagination
                                    if ($args) {
                                        $args .= '&echo=0';
                                    } else {
                                        $args = 'echo=0';
                                    }
                                    $pages = wp_list_pages($args);
                                    echo $pages;
                                }

                                function findStart($limit) { //Pagination
                                    if (!(isset($_REQUEST['pages'])) || ($_REQUEST['pages'] == "1")) {
                                        $start = 0;
                                        $_GET['pages'] = 1;
                                    } else {
                                        $start = ($_GET['pages'] - 1) * $limit;
                                    }
                                    return $start;
                                }

                                /*
                                 * int findPages (int count, int limit)
                                 * Returns the number of pages needed based on a count and a limit
                                 */
                                function findPages($count, $limit) { //Pagination
                                    $pages = (($count % $limit) == 0) ? $count / $limit : floor($count / $limit) + 1;
                                    if ($pages == 1) {
                                        $pages = '';
                                    }
                                    return $pages;
                                }
                                /*
                                 * string pageList (int curpage, int pages)
                                 * Returns a list of pages in the format of "Ã‚Â« < [pages] > Ã‚Â»"
                                 * */
                                function pageList($curpage, $pages, $albid) {
                                    //Pagination
                                      $site_url = get_bloginfo('url');
                                    $page_list = "";
                                    if ($search != '') {

                                        $self = '?page_id=' . get_query_var('page_id');
                                    } else {
                                        $self = '?page_id=' . get_query_var('page_id');
                                    }

                                    if (($curpage - 1) > 0) {
                                        $page_list .= "<a href=\"" . $self . "&pages=" . ($curpage - 1) . "\" title=\"Previous Page\" class='macpag_left'>
                                            <img src=".$site_url."/wp-content/plugins/". dirname(plugin_basename(__FILE__)) . "/images/circle.GIF></a> ";
                                    }
                                                       /* Print the Next and Last page links if necessary */
                                    if (($curpage + 1) <= $pages) {
                                        $page_list .= "<a href=\"" . $self . "&pages=" . ($curpage + 1) . "\" title=\"Next Page\" class='macpag_right'>
                                            <img src=".$site_url."/wp-content/plugins/". dirname(plugin_basename(__FILE__)) . "/images/circle.GIF></a> ";
                                    }
                                    $page_list .= "</td>\n";
                                    return $page_list;
                                }
                                /*
                                 * string nextPrev (int curpage, int pages)
                                 * Returns "Previous | Next" string for individual pagination (it's a word!)
                                 */
                                function nextPrev($curpage, $pages) { //Pagination
                                    $next_prev = "";

                                    if (($curpage - 1) <= 0) {
                                        $next_prev .= "Previous";
                                    } else {
                                        $next_prev .= "<a href=\"" . $_SERVER['PHP_SELF'] . "&pages=" . ($curpage - 1) . "\">Previous</a>";
                                    }

                                    $next_prev .= " | ";

                                    if (($curpage + 1) > $pages) {
                                        $next_prev .= "Next";
                                    } else {
                                        $next_prev .= "<a href=\"" . $_SERVER['PHP_SELF'] . "&pages=" . ($curpage + 1) . "\">Next</a>";
                                    }
                                    return $next_prev;
                                }
                                //End of Pagination
                                    $i = 0;
                                    $macSetting = $wpdb->get_row("SELECT * FROM " . $wpdb->prefix . "macsettings");
                                    $limit = $macSetting->macAlbum_limit;
                                    $sql = mysql_query("SELECT * FROM " . $wpdb->prefix . "macalbum");
                                    $macGallid =$wpdb->get_var("SELECT ID FROM " . $wpdb->prefix . "posts WHERE post_content= '[macGallery]'");
                                    $start = findStart($limit);
                                    if ($_REQUEST['pages'] == 'viewAll') {
                                        $w = '';
                                    } else {

                                        $w = "LIMIT " . $start . ", " . $limit;
                                    }
                                    $count = mysql_num_rows($sql);
                                    /* Find the number of pages based on $count and $limit */
                                    $pages = findPages($count, $limit);
                                    /* Now we use the LIMIT clause to grab a range of rows */
                                    $albDis = $wpdb->get_results("SELECT * FROM " . $wpdb->prefix . "macalbum WHERE macAlbum_status='ON' $w");
                                    $div = '<div id="albwrapper" >';
                                    foreach ($albDis as $albDisplay)
                                    {
                                        $photoCount    = $wpdb->get_var("SELECT count(*) FROM " . $wpdb->prefix . "macphotos WHERE macAlbum_id='$albDisplay->macAlbum_id' and macPhoto_status='ON'");
                                        $default_first = $wpdb->get_var("SELECT macPhoto_image FROM " . $wpdb->prefix . "macphotos WHERE macAlbum_id='$albDisplay->macAlbum_id' and macPhoto_status='ON' ORDER BY macPhoto_id DESC LIMIT 0,1");
                                        $div .='<div  class="albumimg lfloat">';

                                        if ($albDisplay->macAlbum_image == '' && $photoCount == '0') {
                                            $div .='<div><a class="thumbnail" href="' .$site_url. '/?page_id='.$macGallid.'&albid=' . $albDisplay->macAlbum_id . '"><img src="' . $site_url . '/wp-content/plugins/' . dirname(plugin_basename(__FILE__)) . '/uploads/star.jpg"></a></div>';
                                        }
                                        else if($albDisplay->macAlbum_image == '' && $photoCount != '0')
                                        {
                                            $div .='<div><a class="thumbnail" href="' .$site_url. '/?page_id='.$macGallid.'&albid=' . $albDisplay->macAlbum_id . '"><img src="' . $path. '/'.$default_first.'"></a></div>';
                                        }
                                        else
                                        {
                                            $div .='<div><a class="thumbnail" href="' .$site_url. '/?page_id='.$macGallid.'&albid=' . $albDisplay->macAlbum_id . '"><img src="' . $path . '/' . $albDisplay->macAlbum_image . '" ></a></div>';
                                        }

                                        $div .='<div class="mac_title">' . $albDisplay->macAlbum_name . '</div>';

                                        $macDate = explode(' ', $albDisplay->macAlbum_date);
                                        $exDate =  explode('-',$macDate[0]);
                                        $div .='<div class="mac_date">' .$exDate[2].'-'.$exDate[1].'-'.$exDate[0]. '</div>';
                                        $div .='<a href="' .$site_url. '/?page_id='.$macGallid.'&albid=' . $albDisplay->macAlbum_id . '" class="album_href">
                                        <span class="countimg">
                                        <img src="' . $site_url . '/wp-content/plugins/' . dirname(plugin_basename(__FILE__)) . '/images/photo.jpg" class="mac_count_img" />' . $photoCount . ' </span></a>';
                                        $div .='</div>';
                                        $i++;
                                        $album_row = $macSetting->albumRow;
                                        if ($i % $album_row == 0) {
                                            $div .= '<div class="clear"></div>';
                                        }
                                    }
                                    $div .= '<div class="clear"></div>';
                                    $div .= '</div>';

                                    $pagelist = pageList($_GET['pages'], $pages, $_GET['albid']);
                                    $div .= '<div align="right">' . $pagelist . '</div>';
                                }  // End of alubm view
                                return $div;
   }  // End of the function
}     // End of the class
?>