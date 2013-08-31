<?php
/*
 * ********************************************************* */
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
 * ********************************************************* */
class contusMacgallery {
    function macEffectgallery($arguments= array(), $wid) {

        if ($wid == '')
        $wid = 'pirobox_gall';
        global $wpdb;
        global $t;
        $site_url = get_bloginfo('url');
        $uploadDir = wp_upload_dir();
        $path = $uploadDir['baseurl'] . '/mac-dock-gallery';
        $macSetting = $wpdb->get_row("SELECT * FROM " . $wpdb->prefix . "macsettings"); // Full settings get form the admin
        // Page id to display the mac effect
        $macGallid = $wpdb->get_var(("SELECT * FROM " . $wpdb->prefix . "posts WHERE post_content LIKE '%[macGallery]%' AND post_type = 'page' AND post_status = 'publish'"));
?>      <div id="fb-root"></div>
        <script type="text/javascript" src="http://connect.facebook.net/en_US/all.js#xfbml=1"></script>
        <link rel="stylesheet" href="<?php echo $site_url . '/wp-content/plugins/' . dirname(plugin_basename(__FILE__)) . '/css/style.css'; ?>">
        <link rel="stylesheet" href="<?php echo $site_url . '/wp-content/plugins/' . dirname(plugin_basename(__FILE__)) . '/css/fish-eye.css'; ?>">
        <link rel="stylesheet" href="<?php echo $site_url . '/wp-content/plugins/' . dirname(plugin_basename(__FILE__)) . '/css/images.css'; ?>" type="text/css" media="screen" />
        
<?php
        $aid = '';
        if ($_REQUEST['albid'] != '') {
            $aid = $_REQUEST['albid'];        //If  request the album from the gallery display page
        } else if ($arguments['albid'] != '') {
            $aid = $arguments['albid'];     //If  request in the admin page to display the mac images
        } else if ($arguments['walbid'] != '') {  //If  request in the widgets to display the mac images
            $aid = $arguments['walbid'];
            $n = $arguments['cols'];
            $no_of_row = $arguments['row'];
        }
        if ($aid != '') {    // If any albid is get then the mac images respective to the albums will be displayed
        $get_albcount = $wpdb->get_var("SELECT macAlbum_id FROM " . $wpdb->prefix . "macalbum WHERE macAlbum_id='$aid' AND macAlbum_status='ON'");
        // Only the album exist
        if($get_albcount > 0)
        {
        ?>
        <!-- For the mac Effect and carousel -->
         <link rel="stylesheet" type="text/css" href="<?php echo $site_url . '/wp-content/plugins/' . dirname(plugin_basename(__FILE__)) . '/css/ie7/skin.css'; ?>" />

        <!--Mac Gallery common js -->
        <script type="text/javascript" src="<?php echo $site_url . '/wp-content/plugins/' . dirname(plugin_basename(__FILE__)) . '/js/macGallery.js'; ?>"></script>

        <!-- single image pirobox script-->
       
 <?php
   $macAlbid = $aid;
            $macSetting = $wpdb->get_row("SELECT * FROM " . $wpdb->prefix . "macsettings"); // Full settings get form the admin
            /* display randomly */
            if (($macSetting->macImg_dis == 'random')) {
                $where = 'ORDER BY RAND()';
            } else {
                $where = 'ORDER BY macPhoto_sorting ASC';
            }

            if ($arguments['row'] != '' && $arguments['cols'] != '') {
                $n = $arguments['cols'];
                $no_of_row = $arguments['row'];
                $albid = $arguments['albid'];
                $itemwidth = $macSetting->mouseWid;
                $phtDis = $wpdb->get_results("SELECT a.*,b.* FROM " . $wpdb->prefix . "macphotos as a,
                                                                      " . $wpdb->prefix . "macalbum as b WHERE a.macAlbum_id='$macAlbid' and a.macPhoto_status='ON' and b.macAlbum_status ='ON' and b.macAlbum_id='$macAlbid' $where");
            } else if ($arguments['row'] == '' && $arguments['cols'] != '') {
                $n = $arguments['cols'];
                $no_of_row = $macSetting->macimg_page;
                $albid = $arguments['albid'];
                $itemwidth = $macSetting->mouseWid;
                $phtDis = $wpdb->get_results("SELECT a.*,b.* FROM " . $wpdb->prefix . "macphotos as a,
                                                                      " . $wpdb->prefix . "macalbum as b WHERE a.macAlbum_id='$macAlbid' and a.macPhoto_status='ON' and b.macAlbum_status ='ON' and b.macAlbum_id='$macAlbid' $where");
            } else if ($arguments['row'] != '' && $arguments['cols'] == '') {
                $n = $macSetting->macrow;
                $no_of_row = $arguments['row'];
                $albid = $arguments['albid'];
                $itemwidth = $macSetting->mouseWid;
                $phtDis = $wpdb->get_results("SELECT a.*,b.* FROM " . $wpdb->prefix . "macphotos as a,
                                                                      " . $wpdb->prefix . "macalbum as b WHERE a.macAlbum_id='$macAlbid' and a.macPhoto_status='ON' and b.macAlbum_status ='ON' and b.macAlbum_id='$macAlbid' $where");
            } else {
                $n = $macSetting->macrow;
                $no_of_row = $macSetting->macimg_page;
                $albid = $arguments['albid'];
                $itemwidth = $macSetting->mouseWid;
                $phtDis = $wpdb->get_results("SELECT a.*,b.* FROM " . $wpdb->prefix . "macphotos as a,
                                                                      " . $wpdb->prefix . "macalbum as b WHERE a.macAlbum_id='$albid' and a.macPhoto_status='ON' and b.macAlbum_status ='ON' and b.macAlbum_id='$albid' $where");
            }

            if (is_home ()) {
                $n = $macSetting->summary_page;
                $no_of_row = $macSetting->summary_macrow;
                $itemwidth = $macSetting->mouseWid;
                $limit = $macSetting->summary_macrow * $macSetting->summary_page;
                $phtDis = $wpdb->get_results("SELECT a.*,b.* FROM " . $wpdb->prefix . "macphotos as a,
                                                                      " . $wpdb->prefix . "macalbum as b WHERE a.macAlbum_id='$macAlbid' and a.macPhoto_status='ON' and b.macAlbum_status ='ON' and b.macAlbum_id='$macAlbid' $where limit 0,$limit");
            }
            if ($arguments['walbid'] != '') {
                $walbid = $arguments['walbid'];
                $n = $arguments['column'];
                $no_of_row = $arguments['rows'];
                $itemwidth = $arguments['width'];

                $phtDis = $wpdb->get_results("SELECT a.*,b.* FROM " . $wpdb->prefix . "macphotos as a,
                                                                      " . $wpdb->prefix . "macalbum as b WHERE a.macAlbum_id='$walbid' and a.macPhoto_status='ON' and b.macAlbum_status ='ON' and b.macAlbum_id='$walbid' $where");
            } else if ($_REQUEST['albid'] != '') {
                $itemwidth = $macSetting->mouseWid;

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
                                                    <img src='" . $site_url . "/wp-content/plugins/" . dirname(plugin_basename(__FILE__)) . "/images/left.png' class='mac-no-border'></a> ";
                    }
                    /* Print the Next and Last page links if necessary */
                    if (($curpage + 1) <= $pages) {
                        $page_list .= "<a href=\"" . $self . "&pages=" . ($curpage + 1) . "\" title=\"Next Page\"  class='macpag_right'>
                                                    <img src='" . $site_url . "/wp-content/plugins/" . dirname(plugin_basename(__FILE__)) . "/images/right.png' class='mac-no-border'></a> ";
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
                $limit = $n * $no_of_row;
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
            $current_page = $_REQUEST['pages'];
            $limitperpage = $macSetting->macrow * $macSetting->macimg_page ;
            $current_limit= 0;
            for($i=1;$i<$current_page;$i++)
            {
                $current_limit = $current_limit+$limitperpage;
            }
            if (count($phtDis) > 0) {
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
                $width_total = ($macSetting->macrow * $macSetting->mouseWid) + (50) . 'px';
                $page_count = ($no_of_row * ($macSetting->mouseWid) / 2 - 10) . 'px';
                //Enf of parameters


                $div = '<style type="text/css">';
           
                /* Normal image display style */
                if ($macSetting->mac_imgdispstyle == 0) {
                    $div .= '.imgcorner{
            border-radius: 0px;
            -moz-border-radius :0px;
            -webkit-border-radius: 0px;
            }';
                }
                /* Rounded corner image display style */ else if ($macSetting->mac_imgdispstyle == 1) {
                    $div .= '.imgcorner{
            border-radius: 10px;
            -moz-border-radius :10px;
            -webkit-border-radius: 10px;
            }';
                }
                /* Winged display style */ else if ($macSetting->mac_imgdispstyle == 2) {
                    $div .= '.imgcorner{

            -moz-border-radius: 1em 4em 1em 4em;
            border-radius: 1em 4em 1em 4em;
             -webkit-border-top-left-radius: 2em 0.5em;
            -webkit-border-top-right-radius: 1em 3em;
            -webkit-border-bottom-right-radius: 2em 0.5em;
            -webkit-border-bottom-left-radius: 1em 3em;


            }';
                }
                /* Rounded  image display  */ else if ($macSetting->mac_imgdispstyle == 3) {
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
                if ($_REQUEST['albid'] != '' && $arguments['walbid'] == '' && $arguments['albid'] == '') {
                    $div .= '
                #imgmain
                {
                 width: ' . $width_total . ';
                 margin: 0px auto;
                } #imgwrapper
                {
width: ' . $width_total . ';
}';
                
                }

                /* if direction is top */
                if ($direction == 0) {
                    $position = "top:0px";
                    $positionvalue = 0;
                }
                /* if direction is bottom */ else {
                    $position = "bottom:0px";
                    $positionvalue = ($itemwidth);
                }
                for ($l = 1; $l <= $no_of_row; $l++) {
                    $div .= '#dock' . $t . ' { width: 100%;left: 0px;position: relative;  top:' . $positionvalue . 'px; height:'.$itemwidth.'px  }
                  
                    #imgmain a.dock-item { '.$position.'; 
    } ';
                  /* if direction is top */
                if ($direction != 0) {
                     $div .= ' .dock-container' . $t . ' {  position: absolute;padding-left: 20px;}';
                }
                /* if direction is bottom */ else {
                     $div .= ' .dock-container' . $t . ' {  position: absolute;padding-left: 20px;z-index:'.$t.';}';
                }

                    $t++;
                }
                $div .= '</style>';
                $y = 0;




                foreach ($phtDis as $phtDisplay) {  // Getting all the values and stored in array
                    $imgsrc[$y]['macPhoto_image'] = $phtDisplay->macPhoto_image;
                    $imgsrc[$y]['macAlbum_id'] = $phtDisplay->macAlbum_id;
                    $imgsrc[$y]['macPhoto_id'] = $phtDisplay->macPhoto_id;
                    $imgsrc[$y]['macPhoto_name'] = $phtDisplay->macPhoto_name;
                    $imgsrc[$y]['macPhoto_desc'] = $phtDisplay->macPhoto_desc;
                    $imgsrc[$y]['macPhoto_date'] = $phtDisplay->macPhoto_date;
                    $y++;
                }


                $mac_album = $wpdb->get_row("SELECT macAlbum_name,macAlbum_description,macAlbum_id FROM " . $wpdb->prefix . "macalbum WHERE macAlbum_id ='$macAlbid'");
                $height = $no_of_row * $itemwidth;
               
                $div .= '<div id="imgwrapper" style="height:'.$no_of_row * $itemwidth.'px">';
                 if($arguments['walbid'] != '')  {  $maclimit = 0;  $div .= '<div id="imgmain" style="position:absolute;">'; }
                else {  $maclimit = $current_limit;  $div .= '<div id="imgmain">'; }
              
                $div .= '<div class="clearfix" style="width: 100%;position:relative;z-index:9999;">';
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
                        $u = $s;
                        if ($u <= 0) {

                            $s = 0;
                        } else {
                            $s = ($m - $n) + 1;
                        }
                    }
                    $div .='<div class="dock" id="dock' . $e . '">';
                    $div .='<div class="dock-container' . $e . '">';

                    for ($i = $k; $i <= $total; $i++) {
                        $l = $totalimages - 1 - $s;
                        if ($k <= $o) {
                            $extense = explode('.', $imgsrc[$s]['macPhoto_image']);
                            $bigImg[$s] = $imgsrc[$s]['macPhoto_id'] . '.' . $extense[1];  //Getting the big image path
                            //Dock Effect Images
                            $file_image = $uploadDir['basedir'] . '/mac-dock-gallery/' . $imgsrc[$s]['macPhoto_image'];

                            if (file_exists($file_image)) {
                                $file_image = $path . '/' . $imgsrc[$s]['macPhoto_image'];
                            } else {
                                $file_image = $site_url . '/wp-content/plugins/' . dirname(plugin_basename(__FILE__)) . '/uploads/no-photo.png';
                            }

                  $div .='<a class="dock-item" rel="facebox" href="'.$site_url . '/wp-content/plugins/' . dirname(plugin_basename(__FILE__)) . '/mac_imageview.php?mac_phtid='.$imgsrc[$s]['macPhoto_id'].'&mac_albid='.$imgsrc[$s]['macAlbum_id'].'&limit='.$maclimit.'">
                   <div class="dock_img_space"><img class="imgcorner mac-no-border" title="' . $imgsrc[$s]['macPhoto_name'] . '"
                   src="' . $file_image . '"
                   alt="" width="' . $twidth . '"> </div>
                   <span></span></a>';
                            if ($direction == 0) {
                                $s--;
                            } else {
                                $s++;
                            }
                        } else {
                            $total = $total - $k + 1;
                            break;
                        }
                        $k++;
                        $maclimit++;
                    }
                    $div .= '</div>';
                    $div .=' </div>';
                    $m = $m + $n;
                    $e--;
                }
 
                $div .=' </div>';
                $div .= '</div>';
                $div .= '</div>';

} // End of photos count
           else {
                $div .= '<div><h4> No Images in this album</h4></div>';
                 }
                if ($_REQUEST['albid'] != '' && $arguments['walbid'] == '' && $arguments['albid'] == '') { // mac effect pagination
                    $pagelist = pageList($_GET['pages'], $pages, $_GET['albid']);
                    $div .= '<div class="page_list">' . $pagelist . '</div>';
                }
                if ($arguments['walbid'] == '' && $arguments['albid'] == '') {

                    if ($mac_album->macAlbum_description == '') {
                        $div .= '<div id="macshow"></div>';
                    } else {
                        $div .= '<div id="macshow"><h2 class="mac_album_des">' . $mac_album->macAlbum_name . ':'.'  </h2><p>' . $mac_album->macAlbum_description . '</p></div>';
                    }

// Horizontal Carosoule
                    $macGallid = $wpdb->get_var(("SELECT * FROM " . $wpdb->prefix . "posts WHERE post_content LIKE '%[macGallery]%' AND post_type = 'page' AND post_status = 'publish'"));
                    $div .='<div class="album_carosole"><h2 style="margin:0px">ALBUM</h2></div>';
                    $div .= '<div id="mac_slider" >';
                    $div .= '<ul id="second-carousel" class="first-and-second-carousel jcarousel-skin-ie7">';
// Default selected first album
                    $get_albid = $_GET['albid'];
                    $get_album_row = $wpdb->get_row("SELECT * FROM " . $wpdb->prefix . "macalbum WHERE macAlbum_id='$get_albid' and macAlbum_status='ON'");
                    $photoCount = $wpdb->get_var("SELECT count(*) FROM " . $wpdb->prefix . "macphotos WHERE macAlbum_id='$get_albid' and macPhoto_status='ON'");
                    $default_first = $wpdb->get_var("SELECT macPhoto_image FROM " . $wpdb->prefix . "macphotos WHERE macAlbum_id='$get_albid' and macPhoto_status='ON' ORDER BY macPhoto_id DESC LIMIT 0,1");
                    $uploadDir = wp_upload_dir();
                    $file_image = $uploadDir['basedir'] . '/mac-dock-gallery/' . $get_album_row->macAlbum_image;

                    if ((file_exists($file_image)) && ($get_album_row->macAlbum_image != '')) {
                        $div .='<li><a href="' . $site_url . '?page_id=' . $macGallid . '&albid=' . $get_album_row->macAlbum_id . '"><img class="mac-no-border" title="' . $get_album_row->albumname . '" src="' . $path . '/' . $get_album_row->macAlbum_image . '"
                              alt="" style="height:112px;filter:alpha(opacity=30);  opacity:1.0;"/>
                              <span class="carousel_lefttxt">' . substr(trim($get_album_row->macAlbum_name,' '), 0, 15) . '</span></a></li>';
                    } else if ($get_album_row->macAlbum_image == '' && $photoCount != '0') {
                        $div .='<li><a href="' . $site_url . '?page_id=' . $macGallid . '&albid=' . $get_album_row->macAlbum_id . '"><img class="mac-no-border" title="' . $get_album_row->albumname . '" src="' . $path . '/' . $default_first . '"
                              alt="" style="height:112px;filter:alpha(opacity=30);  opacity:1.0;"/>
                              <span class="carousel_lefttxt">' . substr(trim(trim($get_album_row->macAlbum_name,' '),''), 0, 15) . '</span></a></li>';
                    } else if (!file_exists($file_image)) {
                        $div .='<li><a href="' . $site_url . '?page_id=' . $macGallid . '&albid=' . $get_album_row->macAlbum_id . '"><img class="mac-no-border" title="' . $get_album_row->albumname . '" src="' . $site_url . '/wp-content/plugins/' . dirname(plugin_basename(__FILE__)) . '/uploads/star.jpg"
                              alt="" style="height:112px;filter:alpha(opacity=30);  opacity:1.0;"/>
                              <span class="carousel_lefttxt">' . substr(trim($get_album_row->macAlbum_name,' '), 0, 15) . '</span></a></li>';
                    } else {
                        $div .='<li><a href="' . $site_url . '?page_id=' . $macGallid . '&albid=' . $get_album_row->macAlbum_id . '"><img class="mac-no-border" title="' . $get_album_row->albumname . '" src="' . $site_url . '/wp-content/plugins/' . dirname(plugin_basename(__FILE__)) . '/uploads/star.jpg"
                              alt="" style="height:112px;filter:alpha(opacity=30);  opacity:1.0;"/>
                              <span class="carousel_lefttxt">' . substr(trim($get_album_row->macAlbum_name,' '), 0, 15) . '</span></a></li>';
                    }

                    // All other  albums
                    $album_results = $wpdb->get_results("SELECT * FROM  " . $wpdb->prefix . "macalbum WHERE macAlbum_id !='$get_albid' and macAlbum_status='ON'");
                    
                    foreach ($album_results as $dis_results) {
                        $uploadDir = wp_upload_dir();
                        $default_first = $wpdb->get_var("SELECT macPhoto_image FROM " . $wpdb->prefix . "macphotos WHERE macAlbum_id='$dis_results->macAlbum_id' and macPhoto_status='ON' ORDER BY macPhoto_id DESC LIMIT 0,1");
                        $file_image = $uploadDir['basedir'] . '/mac-dock-gallery/' . $dis_results->macAlbum_image;
                        $photoCount = $wpdb->get_var("SELECT count(*) FROM " . $wpdb->prefix . "macphotos WHERE macAlbum_id='$dis_results->macAlbum_id' and macPhoto_status='ON'");


                        if ((file_exists($file_image)) && ($dis_results->macAlbum_image != '')) {
                            $div .='<li><a href="' . $site_url . '?page_id=' . $macGallid . '&albid=' . $dis_results->macAlbum_id . '">
                        <img class="mac-no-border" title="' . $dis_results->albumname . '" src="' . $path . '/' . $dis_results->macAlbum_image . '"
                         alt=""  style="height:112px;filter:alpha(opacity=30);" />
                         <span class="carousel_lefttxt">' . substr(trim($dis_results->macAlbum_name,' '), 0, 11) . '</span></a></li>';
                        } else if ($dis_results->macAlbum_image == '' && $photoCount != '0') {
                            $div .='<li><a href="' . $site_url . '?page_id=' . $macGallid . '&albid=' . $dis_results->macAlbum_id . '"><img class="mac-no-border" title="' . $dis_results->albumname . '" src="' . $path . '/' . $default_first . '"
                              alt="" style="height:112px;filter:alpha(opacity=30);"/>
                              <span class="carousel_lefttxt">' . substr(trim($dis_results->macAlbum_name,' '), 0, 11) . '</span></a></li>';
                        } else if (!file_exists($file_image)) {
                            $div .='<li><a href="' . $site_url . '?page_id=' . $macGallid . '&albid=' . $dis_results->macAlbum_id . '">
                        <img class="mac-no-border" title="' . $dis_results->albumname . '" src="' . $site_url . '/wp-content/plugins/' . dirname(plugin_basename(__FILE__)) . '/uploads/star.jpg"
                         alt=""  style="height:112px;filter:alpha(opacity=30);" />
                         <span class="carousel_lefttxt">' . substr(trim($dis_results->macAlbum_name,' '), 0, 11) . '</span></a></li>';
                        } else {
                            $div .='<li><a href="' . $site_url . '?page_id=' . $macGallid . '&albid=' . $dis_results->macAlbum_id . '">
                        <img class="mac-no-border" title="' . $dis_results->albumname . '" src="' . $site_url . '/wp-content/plugins/' . dirname(plugin_basename(__FILE__)) . '/uploads/star.jpg"
                         alt=""  style="height:112px;filter:alpha(opacity=30);" />
                         <span class="carousel_lefttxt">' . substr(trim($dis_results->macAlbum_name,' '), 0, 11) . '</span></a></li>';
                        }
                    }
                    $div .= '</ul>';

                    $div .= '</div>';

                    ?>
         <script type="text/javascript">
            var mac = jQuery.noConflict();
          // alert(document.body.offsetWidth);
            mac(document).ready(function() {
              mac('.first-and-second-carousel').jcarousel();

            });
            </script>
        <?php
                }
          }  // If only that album exixt
        else
        {
             $div .= '<div>Album does not exist </div>';
        }
            if (count($phtDis) > 0) {
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
                    appId = '<?php echo $macSetting->mac_facebook_api; ?>';

                    var docinarr<?php echo $t; ?> = <?php echo $t - 1; ?>;
                    var totalrec<?php echo $t; ?> = <?php echo $no_of_row; ?>;

                    function maceffect<?php echo $t; ?>()
                    {

                        for(k=docinarr<?php echo $t; ?>;k>(docinarr<?php echo $t; ?>-totalrec<?php echo $t; ?>);k--){

                            mac('#dock'+k).Fisheye({
                                maxWidth: <?php echo $macSetting->mouseHei;?>,
                                items: 'a',
                                itemsText: 'span',
                                valign: 'top',
                                container: '.dock-container'+k,
                                itemWidth: <?php echo $itemwidth; ?>,
                                proximity: <?php echo $macSetting->macProximity?>,
                                halign : '<?php echo 'center'; ?>'
                            });

                        }

                    }

                </script>
                <script>
                    mac(document).ready(function(){
                        maceffect<?php echo $t; ?>();

                    });
                </script>
 <script type="text/javascript">
          
             function getfacebook()
            {
                FB.init({appId:'<?php echo $macSetting->mac_facebook_api; ?>', status: true, cookie: true,
                    xfbml: true});
            }</script>


<?PHP
            } // Second End of photos count


     }   // Photos of the respective alubm
        else
        {
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
                                                    <img src='" . $site_url . "/wp-content/plugins/" . dirname(plugin_basename(__FILE__)) . "/images/left.png' class='mac-no-border'></a> ";
                    }
                    /* Print the Next and Last page links if necessary */
                    if (($curpage + 1) <= $pages) {
                        $page_list .= "<a href=\"" . $self . "&pages=" . ($curpage + 1) . "\" title=\"Next Page\"  class='macpag_right'>
                                                    <img src='" . $site_url . "/wp-content/plugins/" . dirname(plugin_basename(__FILE__)) . "/images/right.png' class='mac-no-border'></a> ";
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

            $limit = $macSetting->macAlbum_limit;
            $sql = mysql_query("SELECT * FROM " . $wpdb->prefix . "macalbum WHERE macAlbum_status='ON'");
            $start = findStart($limit);
            $w = "LIMIT " . $start . ", " . $limit;
            $count = mysql_num_rows($sql);
            /* Find the number of pages based on $count and $limit */
            $pages = findPages($count, $limit);
            /* Now we use the LIMIT clause to grab a range of rows */
            $albDis = $wpdb->get_results("SELECT * FROM " . $wpdb->prefix . "macalbum WHERE macAlbum_status='ON' $w");

            // Album div starts
            $div = '<div id="albwrapper" >';
            foreach ($albDis as $albDisplay) {
                $uploadDir = wp_upload_dir();
                $file_image = $uploadDir['basedir'] . '/mac-dock-gallery/' . $albDisplay->macAlbum_image;
                $path = $uploadDir['baseurl'] . '/mac-dock-gallery';
                $site_url = get_bloginfo('url');
                $photoCount = $wpdb->get_var("SELECT count(*) FROM " . $wpdb->prefix . "macphotos WHERE macAlbum_id='$albDisplay->macAlbum_id' and macPhoto_status='ON'");
                $default_first = $wpdb->get_var("SELECT macPhoto_image FROM " . $wpdb->prefix . "macphotos WHERE macAlbum_id='$albDisplay->macAlbum_id' and macPhoto_status='ON' ORDER BY macPhoto_id DESC LIMIT 0,1");
                $div .='<div  class="albumimg lfloat">';

                if ($albDisplay->macAlbum_image == '' && $photoCount == '0') {
                    $div .='<div class="inner_albim_image"><a class="thumbnail" href="' . $site_url . '/?page_id=' . $macGallid . '&albid=' . $albDisplay->macAlbum_id . '"><img title="' . $albDisplay->macAlbum_name . '" src="' . $site_url . '/wp-content/plugins/' . dirname(plugin_basename(__FILE__)) . '/uploads/star.jpg"></a></div>';
                } else if ($albDisplay->macAlbum_image == '' && $photoCount != '0') {
                    $div .='<div class="inner_albim_image"><a class="thumbnail" href="' . $site_url . '/?page_id=' . $macGallid . '&albid=' . $albDisplay->macAlbum_id . '"><img title="' . $albDisplay->macAlbum_name . '" src="' . $path . '/' . $default_first . '"></a></div>';
                } else if ((file_exists($file_image))) {
                    $div .='<div class="inner_albim_image"><a class="thumbnail" href="' . $site_url . '/?page_id=' . $macGallid . '&albid=' . $albDisplay->macAlbum_id . '"><img title="' . $albDisplay->macAlbum_name . '" src="' . $path . '/' . $albDisplay->macAlbum_image . '" ></a></div>';
                } else {
                    $div .='<div class="inner_albim_image"><a class="thumbnail" href="' . $site_url . '/?page_id=' . $macGallid . '&albid=' . $albDisplay->macAlbum_id . '"><img title="' . $albDisplay->macAlbum_name . '" src="' . $site_url . '/wp-content/plugins/' . dirname(plugin_basename(__FILE__)) . '/uploads/star.jpg"></a></div>';
                }
                $div .='<div class="mac_title"><a class="thumbnail" href="' . $site_url . '/?page_id=' . $macGallid . '&albid=' . $albDisplay->macAlbum_id . '">' . substr($albDisplay->macAlbum_name, 0, 15) . '</a></div>';

                $macDate = explode(' ', $albDisplay->macAlbum_date);
                $exDate = explode('-', $macDate[0]);
                $div .='<div class="mac_date">' . $exDate[2] . '-' . $exDate[1] . '-' . $exDate[0] . '</div>';
                $div .='<a href="' . $site_url . '/?page_id=' . $macGallid . '&albid=' . $albDisplay->macAlbum_id . '" class="album_href">
                                        <span class="countimg">
                                        <img src="' . $site_url . '/wp-content/plugins/' . dirname(plugin_basename(__FILE__)) . '/images/photo.jpg" class="mac_count_img" />' . $photoCount . ' </span></a>';
                $div .='</div>';
                $i++;
                $album_row = $macSetting->albumRow;
            }
            $div .= '<div class="clear"></div>';
            $div .= '</div>';

            $pagelist = pageList($_GET['pages'], $pages, $_GET['albid']);
            $div .= '<div align="center">' . $pagelist . '</div>';
            
        }   // End of the Album list show
//$div.='';
        return $div;
    }
// End of the function
}
// End of the class
?>