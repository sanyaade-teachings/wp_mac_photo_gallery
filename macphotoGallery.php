<?php
/**
 * @name        Mac Doc Photogallery.
 * @version	2.1: macphotoGallery.php 2011-08-15
 * @package	apptha
 * @subpackage  mac-doc-photogallery
 * @author      saranya
 * @copyright	Copyright (C) 2005 - 2011 Open Source Matters, Inc. All rights reserved.
 * @license	GNU General Public License version 2 or later; see LICENSE.txt
 * @abstract    Add and Viewing Photos page.
 * */

require_once( dirname(__FILE__) . '/macDirectory.php');
class macPhotos {
    var $base_page = '?page=macPage';
    function macPhotos() {
        maccontroller();
    }
}

function maccontroller() {
    global $wpdb, $site_url, $folder;
    $site_url = get_bloginfo('url');
    $folder = dirname(plugin_basename(__FILE__));

?>
   <link rel='stylesheet' href='<?php echo $site_url; ?>/wp-content/plugins/<?php echo $folder ?>/css/style.css' type='text/css' />
   <script type="text/javascript" src="<?php echo $site_url; ?>/wp-content/plugins/<?php echo $folder; ?>/js/jquery-1.3.2.js"></script>

    <script type="text/javascript" src="<?php echo $site_url; ?>/wp-content/plugins/<?php echo $folder; ?>/js/macGallery.js"></script>
    
    <script type="text/javascript" src="<?php echo $site_url; ?>/wp-content/plugins/<?php echo $folder; ?>/js/swfupload/swfupload.js"></script>
    <script type="text/javascript" src="<?php echo $site_url; ?>/wp-content/plugins/<?php echo $folder; ?>/js/jquery.swfupload.js"></script>
    <script type="text/javascript" src="<?php echo $site_url; ?>/wp-content/plugins/<?php echo $folder; ?>/js/jquery-ui-1.7.1.custom.min.js"></script>
    <script  type="text/javascript" src="<?php echo $site_url; ?>/wp-content/plugins/<?php echo $folder; ?>/js/main.js"></script>
    <script type="text/javascript">
        var site_url,mac_folder,numfiles;
        site_url = '<?php echo $site_url; ?>';
        var url = '<?php echo $site_url; ?>';
        mac_folder  = '<?php echo $folder; ?>';
        keyApps = '<?php echo $configXML->keyApps; ?>';
        videoPage = '<?php echo $meta; ?>';
                function GetSelectedItem() {
                len = document.frm1.macAlbum_name.length
                i = 0
                chosen = "none"
                for (i = 0; i < len; i++) {
                    if (document.frm1.macAlbum_name[i].selected) {
                        chosen = document.frm1.macAlbum_name[i].value
                    }
                }
                    window.location = site_url+"/wp-admin/admin.php?page=macPhotos&albid="+chosen;
            }
    </script>
    <script type="text/javascript">
    dragdr(document).ready(function(){
    if(document.getElementById('mac-test-list'))
                {
               dragdr("#mac-test-list").sortable({
                handle : '.handle',
                update : function () {
                    var order =dragdr('#mac-test-list').sortable('serialize');
                    dragdr("#info").load(site_url+"/wp-content/plugins/"+mac_folder+"/process-sortable.php?"+order);
                    location.reload();
                    }
                });
    }

           dragdr('#swfupload-control').swfupload({
                upload_url: site_url+"/wp-content/plugins/"+mac_folder+"/upload-file.php?albumId=<?php echo $_REQUEST['albid'] ?>",
                file_post_name: 'uploadfile',
                file_size_limit : 0,
                file_types : "*.jpg;*.png;*.jpeg;*.gif",
                file_types_description : "Image files",
                file_upload_limit : 1000,
                flash_url : site_url+"/wp-content/plugins/"+mac_folder+"/js/swfupload/swfupload.swf",
                button_image_url : site_url+'/wp-content/plugins/'+mac_folder+'/js/swfupload/wdp_buttons_upload_114x29.png',
                button_width : 114,
                button_height : 29,
                button_placeholder :dragdr('#button')[0],
                debug: false
            })
            .bind('fileQueued', function(event, file){
                var listitem='<li id="'+file.id+'" >'+
                    'File: <em>'+file.name+'</em> ('+Math.round(file.size/1024)+' KB) <span class="progressvalue" ></span>'+
                    '<div class="progressbar" ><div class="progress" ></div></div>'+
                    '<p class="status" >Pending</p>'+
                    '<span class="cancel" >&nbsp;</span>'+
                    '</li>';

                dragdr('#log').append(listitem);

               dragdr('li#'+file.id+' .cancel').bind('click', function(){
                    var swfu =dragdr.swfupload.getInstance('#swfupload-control');
                    swfu.cancelUpload(file.id);
                    dragdr('li#'+file.id).slideUp('fast');
                });
                // start the upload since it's queued
                dragdr(this).swfupload('startUpload');
            })
            .bind('fileQueueError', function(event, file, errorCode, message){
                alert('Size of the file '+file.name+' is greater than limit');

            })
            .bind('fileDialogComplete', function(event, numFilesSelected, numFilesQueued){
               dragdr('#queuestatus').text('Files Selected: '+numFilesSelected+' / Queued Files: '+numFilesQueued);
                numfiles = numFilesQueued;
                i=1;
                j=numfiles;
            })
            .bind('uploadStart', function(event, file){

                dragdr('#log li#'+file.id).find('p.status').text('Uploading...');
               dragdr('#log li#'+file.id).find('span.progressvalue').text('0%');
               dragdr('#log li#'+file.id).find('span.cancel').hide();
            })
            .bind('uploadProgress', function(event, file, bytesLoaded){
                //Show Progress

                var percentage=Math.round((bytesLoaded/file.size)*100);
               dragdr('#log li#'+file.id).find('div.progress').css('width', percentage+'%');
               dragdr('#log li#'+file.id).find('span.progressvalue').text(percentage+'%');
            })
            .bind('uploadSuccess', function(event, file, serverData){
                var item=dragdr('#log li#'+file.id);
                item.find('div.progress').css('width', '100%');
                item.find('span.progressvalue').text('100%');
                item.addClass('success').find('p.status').html('Done!!!');
               
            })
            .bind('uploadComplete', function(event, file){
                // upload has completed, try the next one in the queue
                dragdr(this).swfupload('startUpload');
                if(j == i)
                    {
                 macPhotos(numfiles,'<?php echo $_REQUEST['albid'] ?>');
                    }
                    i++
            })
           
        });
    </script>
    <script type="text/javascript">
        function checkallPhotos(frm,chkall)
        {
            var j=0;
            comfList123 = document.forms[frm].elements['checkList[]'];
            checkAll = (chkall.checked)?true:false; // what to do? Check all or uncheck all.

            // Is it an array
            if (comfList123.length) {
                if (checkAll) {
                    for (j = 0; j < comfList123.length; j++) {
                        comfList123[j].checked = true;
                    }
                }
                else {
                    for (j = 0; j < comfList123.length; j++) {
                        comfList123[j].checked = false;
                    }
                }
            }
            else {
                /* This will take care of the situation when your
    checkbox/dropdown list (checkList[] element here) is dependent on
    a condition and only a single check box came in a list.
                 */
                if (checkAll) {
                    comfList123.checked = true;
                }
                else {
                    comfList123.checked = false;
                }
            }

            return;
        }


    </script>

<script src="<?php echo $site_url . '/wp-content/plugins/'.$folder.'/js/jquery-pack.js'; ?>" type="text/javascript"></script>
<link href="<?php echo $site_url . '/wp-content/plugins/'.$folder.'/css/facebox.css';?>" media="screen" rel="stylesheet" type="text/css" />
<script src="<?php echo $site_url . '/wp-content/plugins/'.$folder.'/js/facebox.js';?>" type="text/javascript"></script>
<script type="text/javascript">
// starting the script on page load
dragdr(document).ready(function(){

	imagePreview();
});

 $(document).ready(function($) {
      $('a[rel*=facebox]').facebox()
    })
 </script>


    <style type="text/css" >
        #swfupload-control p{ margin:10px 5px; font-size:11px;width:100%; }
        #log{ margin:0; padding:0; width:100%;}
        #log li{ list-style-position:inside; margin:2px; border:1px solid #ccc; padding:10px; font-size:12px;
                 font-family:Arial, Helvetica, sans-serif; color:#333; background:#fff; position:relative;}
        #log li .progressbar{ border:1px solid #333; height:5px; background:#fff; }
        #log li .progress{ background:#999; width:0%; height:5px; }
        #log li p{ margin:0; line-height:18px; }
        #log li.success{ border:1px solid #339933; background:#ccf9b9; }
        #log li span.cancel{ position:absolute; top:5px; right:5px; width:20px; height:20px;
                             background:url('../cancel.png') no-repeat; cursor:pointer; }
        </style>
    </head>
    <body>
    <?php
    if ($_REQUEST['action'] == 'viewPhotos')
       {
        $albid = $_REQUEST['albid'];
        if ($_REQUEST['macPhotoid'] != '') {
            $macPhotoid = $_REQUEST['macPhotoid'];
            $photoImg = $wpdb->get_var("SELECT macPhoto_image FROM " . $wpdb->prefix . "macphotos WHERE macPhoto_id='$macPhotoid' ");
            $delete = $wpdb->query("DELETE FROM " . $wpdb->prefix . "macphotos WHERE macPhoto_id='$macPhotoid'");
            $path = '../wp-content/plugins/'.$folder.'/uploads/';
            unlink($path . $photoImg);
            $extense = explode('.', $photoImg);
            unlink($path . $macPhotoid . '.' . $extense[1]);
               
        }
        if (isset($_REQUEST['doaction_photos'])) {

            if (isset($_REQUEST['action_photos']) == 'Delete') {
                for ($k = 0; $k < count($_POST['checkList']); $k++) {
                    $macPhoto_id = $_POST['checkList'][$k];
                    $photoImg = $wpdb->get_var("SELECT macPhoto_image FROM " . $wpdb->prefix . "macphotos WHERE macPhoto_id='$macPhoto_id' ");
                    $delete = $wpdb->query("DELETE FROM " . $wpdb->prefix . "macphotos WHERE macPhoto_id='$macPhoto_id'");
                    $path = '../wp-content/plugins/'.$folder.'/uploads/';
                    unlink($path . $photoImg);
                    $extense = explode('.', $photoImg);
                    unlink($path . $macPhoto_id . '.' . $extense[1]);
                }
            }
        }

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
            $page_list = "";
            if ($search != '') {

                $self = '?page=' . macPhotos . '&action=viewPhotos' . '&albid=' . $albid;
            } else {
                $self = '?page=' . macPhotos . '&action=viewPhotos' . '&albid=' . $albid;
            }

            /* Print the first and previous page links if necessary */
            if (($curpage != 1) && ($curpage)) {
                $page_list .= "  <a href=\"" . $self . "&pages=1\" title=\"First Page\">«</a> ";
            }

            if (($curpage - 1) > 0) {
                $page_list .= "<a href=\"" . $self . "&pages=" . ($curpage - 1) . "\" title=\"Previous Page\"><</a> ";
            }

            /* Print the numeric page list; make the current page unlinked and bold */
            for ($i = 1; $i <= $pages; $i++) {
                if ($i == $curpage) {
                    $page_list .= "<b>" . $i . "</b>";
                } else {
                    $page_list .= "<a href=\"" . $self . "&pages=" . $i . "\" title=\"Page " . $i . "\">" . $i . "</a>";
                }
                $page_list .= " ";
            }

            /* Print the Next and Last page links if necessary */
            if (($curpage + 1) <= $pages) {
                $page_list .= "<a href=\"" . $self . "&pages=" . ($curpage + 1) . "\" title=\"Next Page\">></a> ";
            }

            if (($curpage != $pages) && ($pages != 0)) {
                $page_list .= "<a href=\"" . $self . "&pages=" . $pages . "\" title=\"Last Page\">»</a> ";
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
    ?>
            <link rel='stylesheet' href='<?php echo $site_url; ?>/wp-content/plugins/<?php echo $folder ?>/css/style.css' type='text/css' />
            <link rel='stylesheet' href='<?php echo $site_url; ?>/wp-content/plugins/<?php echo $folder ?>/css/styles.css' type='text/css' />
<div class="wrap nosubsub" style="width:98%;float:left;margin-right:15px;align:center"><div id="icon-upload" class="icon32"><br /></div>
        <h2 class="nav-tab-wrapper">
        <a href="?page=macAlbum" class="nav-tab">Albums</a>
        <a href="?page=macPhotos&action=macPhotos" class="nav-tab  nav-tab-active">Photos</a>
        <a href="?page=macSettings" class="nav-tab">Settings</a></h2>
    <div style="background-color:#ECECEC;padding: 10px;margin:10px 0px 10px 0px;border: #ccc 1px solid">
        <strong> Note : </strong>Mac Photo Gallery can be easily inserted to the Post / Page by adding the following code :<br><br>
                 (i)  [macGallery] - This will show the entire gallery<br>
                 (ii) [macGallery albid=1 row=3 cols=3] - This will show the particular album with the album id 1
          </div>
<div class="clear"></div>
    <?php
    if($_REQUEST['albid'] != '' && $_REQUEST['albid']!='0')
    {
        $macAlbum = $wpdb->get_row("SELECT * FROM " . $wpdb->prefix . "macalbum WHERE macAlbum_id='$albid'");
    ?>
<h4><div class="lfloat">Album Name : </div> <div style="color:#448abd;"> <?php echo $macAlbum->macAlbum_name; ?> </div></h4>
        <?php
        if($macAlbum->macAlbum_image != '')
        { ?>
         <img src="<?php echo $site_url; ?>/wp-content/plugins/<?php echo $folder; ?>/uploads/<?php echo $macAlbum->macAlbum_image; ?>" width="100" height="100"/>

        <?php
        }
        else
        {
        ?>
        <img src="<?php echo $site_url; ?>/wp-content/plugins/<?php echo $folder; ?>/images/default_star.gif" width="50px" height="50px" />
        <?php } ?>

        <div style="float:right;width:80%"><form name="macPhotos" id="macPhotos" method="POST">
                  
                <select name="action_photos" style="float: left">
                    <option value="" selected="selected"><?php _e('Bulk Actions'); ?></option>
                    <option value="Delete"><?php _e('Delete'); ?></option>
                </select>
                 <ul class="alignright actions">
                     <li><a href="<?php echo $site_url ?>/wp-admin/admin.php?page=macPhotos&albid=<?php echo $macAlbum->macAlbum_id; ?>" class="gallery_btn">
                        Add Images</a></li>
                     
                </ul>
              
 <input type="submit" value="<?php esc_attr_e('Apply'); ?>" name="doaction_photos" id="doaction_photos" class="button-secondary action" />
                <!--<div id="info">Waiting for update</div>-->

                <table cellspacing="0" cellpadding="0" border="1" class="mac_gallery">
                    <thead>
                        <tr>
                             <th style="width:5%">Sort</th>
                             <th class="maccheckPhotos_all" style="width:5%" >
                            <input type="checkbox"  name="maccheckPhotos"  id="maccheckPhotos" class="maccheckPhotos" onclick="checkallPhotos('macPhotos',this);" /></th>
                            <th class="macname" style='max-width:30%;text-align: left'>Name</th>
                            <th class="macimage" style='width:10%;text-align: left'>Image</th>
                            <th class="macdesc" style='width:30%;text-align: left'>Description</th>
                            <th class="macon" style='width:10%'>Album Cover</th>
                            <th class="macon" style='width:10%;text-align: center'>Sorting</th>
                            <th class="macon" style='width:10%;text-align: center'>Status</th>
                        </tr>
                    </thead>
                    <tbody id="mac-test-list" class="list:post">
                    <?php
                    $site_url = get_bloginfo('url');
                    /* Pagination */
                 
                    $limit = 20;
                    $sql = mysql_query("SELECT * FROM " . $wpdb->prefix . "macphotos WHERE macAlbum_id='$albid' ORDER BY macPhoto_sorting ASC");
                    $start = findStart($limit);

                        if($_REQUEST['pages']== 'viewAll')
                    {
                      $w= '';
                    }
                    else
                    {
                  
                    $w = "LIMIT " . $start . "," . $limit;
                    }
                    
                    $count = mysql_num_rows($sql);
                    /* Find the number of pages based on $count and $limit */
                   $pages = findPages($count, $limit);
                    /* Now we use the LIMIT clause to grab a range of rows */
                   $result = $wpdb->get_results("SELECT * FROM " . $wpdb->prefix . "macphotos WHERE macAlbum_id='$albid' ORDER BY macPhoto_sorting ASC $w");
                   $album = '';
               
                    foreach ($result as $results)
                    {
                     $album .= "<tr class='$j' id='listItem_$results->macPhoto_id'>
                             <td style='text-align: center'><img src='$site_url/wp-content/plugins/$folder/images/arrow.png' alt='move' width='16' height='16' class='handle' /></td>
                               <td class='checkPhotos_all' style='text-align: center'><input type=hidden id=macPhoto_id name=macPhoto_id value='$results->macPhoto_id' >
                               <input type='checkbox' class='checkSing' name='checkList[]' class='others' value='$results->macPhoto_id' ></td>

                               <td class='macName'style='text-align: left' ><div id='macPhotos_$results->macPhoto_id' onclick=photosNameform($results->macPhoto_id); style='cursor:pointer'>" . $results->macPhoto_name . "</div>
                               <span id='showPhotosedit_$results->macPhoto_id'></span>
                               <div class='delView'></div></td>";

                   if ($results->macPhoto_image == '')
                   {
                    $album .="<td  style='width:10%;align=center'>
                    <a id='$site_url/wp-content/plugins/$folder/images/default_star.gif' class='preview' alt='Edit'  href='javascript:void(0)'>
                     <img src='$site_url/wp-content/plugins/$folder/images/default_star.gif' width='40' height='20' /></a></td>";
                   } else
                   {
                       
                    $album .="<td  style='width:10%;align=center'>
                    <a id='$site_url/wp-content/plugins/$folder/uploads/$results->macPhoto_image' class='preview' alt='Edit' href='javascript:void(0)'>
                    <img src='$site_url/wp-content/plugins/$folder/uploads/$results->macPhoto_image' width='40' height='20' /></a></td>";
                   }

                   $album .="<td style='width:30%'><div id='display_txt_" . $results->macPhoto_id . "'>" . $results->macPhoto_desc . "</div>
                             <a id='displayText_" . $results->macPhoto_id . "' href='javascript:phototoggle($results->macPhoto_id);'>Edit</a>
                             <div id='toggleText" . $results->macPhoto_id . "' style='display: none'>
                             <textarea name='macPhoto_desc' id='macPhoto_desc_" . $results->macPhoto_id . "' rows='6' cols='30' >$results->macPhoto_desc</textarea><br />
                             <input type='button' onclick='javascript:macdesc_updt($results->macPhoto_id);' value='Update'>
                             </div></td>";
                   if ($results->macAlbum_cover == 'ON')
                        {
                            $album .= "<td align='center'><div id='albumCover_bind_$results->macPhoto_id' style='text-align:center'>
                            <img src='$site_url/wp-content/plugins/$folder/images/tick.png' width='16' height='16' style='cursor:pointer;text-align:center' onclick=macAlbcover_status('OFF',$albid,$results->macPhoto_id) /></div></td>";
                        } else
                        {
                            $album .= "<td align='center'><div id='albumCove_bind_$results->macPhoto_id' style='text-align:center'>
                            <img src='$site_url/wp-content/plugins/$folder/images/publish_x.png' width='16' height='16' style='cursor:pointer;text-align:center' onclick=macAlbcover_status('ON',$albid,$results->macPhoto_id) /></div></td>";
                        }
                   $album .="<td style='text-align:center'>$results->macPhoto_sorting</td>";
                        if ($results->macPhoto_status == 'ON')
                        {
                            $album .= "<td><div id='photoStatus_bind_$results->macPhoto_id' style='text-align:center'>
                            <img src='$site_url/wp-content/plugins/$folder/images/tick.png' width='16' height='16' style='cursor:pointer' onclick=macPhoto_status('OFF',$results->macPhoto_id) /></div></td>";
                        } else
                        {
                            $album .= "<td><div id='photoStatus_bind_$results->macPhoto_id' style='text-align:center'>
                            <img src='$site_url/wp-content/plugins/$folder/images/publish_x.png' width='16' height='16' style='cursor:pointer' onclick=macPhoto_status('ON',$results->macPhoto_id) /></div></td></tr>";
                        }
                       
                    }
                    $pagelist = pageList($_GET['pages'], $pages, $_GET['albid']);
                  
                    echo $album;
                    ?>
                </tbody>
            </table>
        </form>
             <div align="right"><?php echo $pagelist; ?>
                 <?php 
                 if($count > $limit )
                 { ?>
                  <a href="<?php echo $site_url?>/wp-admin/admin.php?page=macPhotos&action=viewPhotos&albid=<?php echo $albid;?>&pages=viewAll">See All</a></div>
               <?php
                  }
                 ?>
                
               <?php   ?>
             <?php }
           else
             {
              ?>
            <div style="padding-top:20px">No albums is selected. Please Go to back and select the respective album to view images</div>
             <?php 
             }
       ?>
    <div align="right" onClick="history.back();" style="cursor:pointer;color:#21759B;font-weight: bold">Back</div>
    </div>
  
</div>
 <?php
                } else {
 ?>
        <div class="wrap nosubsub clearfix"><div id="icon-upload" class="icon32"><br /></div>
        <h2 class="nav-tab-wrapper">
        <a href="?page=macAlbum" class="nav-tab">Albums</a>
        <a href="?page=macPhotos&action=macPhotos" class="nav-tab  nav-tab-active">Photos</a>
        <a href="?page=macSettings" class="nav-tab">Settings</a></h2>
         <div style="background-color: #ECECEC;padding: 10px;margin:10px 0px 30px 0px;border: #ccc 1px solid">
        <strong> Note : </strong>Mac Photo Gallery can be easily inserted to the Post / Page by adding the following code :<br><br>
                 (i)  [macGallery] - This will show the entire gallery<br>
                 (ii) [macGallery albid=1 row=3 cols=3] - This will show the particular album with the album id 1
          </div>
                        <div class="clear"><div style="width:30%;float:left;margin-right:15px;">
                            <h3>Select The Album To Upload Photos</h3>
                                <div class="clear"></div>
                            <form name="frm1">
                                <div><select name="macAlbum_name" id="macAlbum_name" onchange="GetSelectedItem()" >
                                        <option value="0"><-- Select/Add Album Here --></option>
                                        <option value="-1">Create New Album</option>
                    <?php
                    if (($_REQUEST['albid']) != '') {
                        $albid = $_REQUEST['albid'];
                    }
                    $albRst = $wpdb->get_results("SELECT * FROM  " . $wpdb->prefix . "macalbum");
                    foreach ($albRst as $albRsts) {
                        if ($albid == $albRsts->macAlbum_id) {
                            $selected = selected;
                        } else {
                            $selected = '';
                        }
                    ?>
                        <option value="<?php echo $albRsts->macAlbum_id; ?>" <?php echo $selected ?>><?php echo $albRsts->macAlbum_name; ?></option>
                    <?php
                    }
                    ?>
                </select></div></form>
                <?php
                if($_REQUEST['albid'] != '0' && $_REQUEST['albid'] != '-1')
                {
                    ?>
              
        <div id="swfupload-control" class="left_align">
            <p>Upload multiple image files(jpg, jpeg, png, gif)</p>
            choose files to upload <input type="button" id="button"  />
            <p id="queuestatus" ></p>
            <ol id="log"></ol>
        </div>
                <?php } else if($_REQUEST['albid'] == '-1') { ?>
                  <script type="text/javascript">

window.location = "<?php echo $site_url;?>/wp-admin/admin.php?page=macAlbum"

</script>


               <?php } ?>
</div>
  
    <div name="bind_macPhotos" id="bind_macPhotos" class="bind_macPhotos"></div></div>
                        
     <input type="hidden" name="bind_value" id="bind_value" value="0"/>
</div>
<?php
                }
            }
?>