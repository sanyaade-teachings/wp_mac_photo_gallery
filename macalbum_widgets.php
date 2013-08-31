<?php
/*
 ***********************************************************
 * Plugin Name: Mac Widgets
 * Component Name:  Mac Photo Gallery
 * Description: Mac Photo gallery widget, This is a album displaying widget in this you can give number of albums to be display in widget.
 * Version: 2.1
 * Edited By: Saranya
 * Author URI: http://www.apptha.com/
 * Date :May 19 2011

 **********************************************************

 @Copyright Copyright (C) 2010-2011 Contus Support
 @license GNU/GPL http://www.gnu.org/copyleft/gpl.html,

 **********************************************************/

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
$macPageid = $wpdb->get_var("SELECT ID FROM " . $wpdb->prefix . "posts WHERE post_content='[macGallery]'");

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
          $photoCount = $wpdb->get_var("SELECT count(*) FROM " . $wpdb->prefix . "macphotos WHERE macAlbum_id='$albDisplay->macAlbum_id' and macPhoto_status='ON'");
          $default_first = $wpdb->get_var("SELECT macPhoto_image FROM " . $wpdb->prefix . "macphotos WHERE macAlbum_id='$albDisplay->macAlbum_id' and macPhoto_status='ON' ORDER BY macPhoto_id DESC LIMIT 0,1");
          $div .='<div  class="albumimg">';
                 if ($albDisplay->macAlbum_image == '' && $photoCount == '0')
                    {
                      $div .='<div class="widget_alb_img"><a class="thumbnail" href="' . $site_url .'?page_id='.$macPageid.'&albid=' . $albDisplay->macAlbum_id . '"><img src="' . $site_url . '/wp-content/plugins/' . dirname(plugin_basename(__FILE__)) . '/uploads/star.jpg" width="140" height="140"></a></div>';
                    }
                    else if($albDisplay->macAlbum_image == '' && $photoCount != '0')
                    {
                    $div .='<div class="widget_alb_img"><a class="thumbnail" href="' . $site_url .'?page_id='.$macPageid.'&albid=' . $albDisplay->macAlbum_id . '"><img src="' . $site_url . '/wp-content/plugins/' . dirname(plugin_basename(__FILE__)) . '/uploads/'.$default_first.'" width="140" height="140"></a></div>';
                    }
                    else
                    {
                      $div .='<div class="widget_alb_img"><a class="thumbnail" href="' . $site_url .'?page_id='.$macPageid.'&albid=' . $albDisplay->macAlbum_id . '"><img src="' . $site_url . '/wp-content/plugins/' . dirname(plugin_basename(__FILE__)) . '/uploads/' . $albDisplay->macAlbum_image . '"   width="140" height="140"></a></div>';
                    }
                      $div .='<div class="mac_title">' . $albDisplay->macAlbum_name . '</div>';
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
register_sidebar_widget(array('Mac Album Widgets', 'widgets'), 'widget_Contusmacalbum');

// Register settings for use, 300x100 pixel form
register_widget_control(array(' Mac Album Widgets ', 'widgets'), 'widget_Contusmacalbum_control', 300, 200);
}
// Run code and init
add_action('widgets_init', 'widget_Contusmacalbum_init');
?>