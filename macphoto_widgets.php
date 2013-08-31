<?php
/*
 ***********************************************************
 * Plugin Name: Mac Photos Widgets
 * Component Name:  Mac Photo Gallery
 * Description: Mac Photo gallery widget, This is a photo displaying widget in this you can give row and column and the image width in Doc effect.
 * Version: 2.0
 * Edited By: Saranya
 * Author URI: http://www.apptha.com/
 * Date :May 19 2011

 **********************************************************

 @Copyright Copyright (C) 2010-2011 Contus Support
 @license GNU/GPL http://www.gnu.org/copyleft/gpl.html,

 **********************************************************/

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
                    CONTUS_macRender($content,'pirobox_gall1');
                ?>
            </ul></div>
            <?php
            // echo widget closing tag
            echo $after_widget;
               }
            function widget_Contusmacphotos_control()
            {
                 global $wpdb, $wp_version, $popular_posts_current_ID;
                 $widmac = $wpdb->get_results("select * from " . $wpdb->prefix . "macalbum");
                
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
                        update_option('widget_Contusmacphotos', $options);
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
            register_sidebar_widget(array(' Mac Photos Widgets ', 'widgets'), 'widget_Contusmacphotos');
            // Register settings for use, 300x100 pixel form
            register_widget_control(array('Mac Photos Widgets', 'widgets'), 'widget_Contusmacphotos_control', 300, 200);
            }
            // Run code and init
            add_action('widgets_init', 'widget_Contusmacphotos_init');
            ?>