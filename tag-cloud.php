<?php
/*
 * Plugin Name: Tag Cloud Widget
 * Plugin URI: http://wordpress.org/plugins/tag-cloud-widget/
 * Author: Waterloo Plugins
 * Description: Add a tag cloud widget with links to the tag pages
 * Version: 1.0.0
 * License: GPL2+
 */
 
if (!defined('WPINC'))
    die;

class Tag_Cloud extends WP_Widget {
    function __construct() {
        parent::__construct('tag_cloud', 'Tag Cloud',
            array('description' => 'Add links to your tag pages in a tag cloud'));
    }
    
    public function widget($args, $instance) {
        echo $args['before_widget'];
        if (!empty($instance['title'])) {
            echo $args['before_title'].apply_filters('widget_title', $instance['title']).$args['after_title'];
        }
        
        echo '<div class="tag-cloud__widget"></div>';
        
        echo $args['after_widget'];
    }
    
    public function form($instance) {
        $title = !empty($instance['title']) ? $instance['title'] : 'Tag Cloud';
        ?>
        <p>
        <label for="<?php echo $this->get_field_id('title'); ?>">Title:</label> 
        <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>">
        </p>
        <?php 
    }
}

function print_tag_cloud_tags() {
    $tags = get_tags();
    $arr = array();
    foreach ($tags as $tag) {
        $arr[] = (object) array(
            'text' => $tag->name,
            'weight' => $tag->count,
            'link' => get_tag_link($tag->term_id)
        );
    }
    
?>
<script>
window.TAGCLOUDTAGS = <?php echo json_encode($arr) ?>;
</script>
<?php
}
add_action('wp_footer', 'print_tag_cloud_tags');

function register_tag_cloud_widget() {
    register_widget('Tag_Cloud');
}
add_action('widgets_init', 'register_tag_cloud_widget');

function enqueue_tag_cloud_assets() {
    wp_enqueue_style('tag-cloud-styles', plugin_dir_url(__FILE__).'tag-cloud.css');
    wp_enqueue_script('tag-cloud-scripts', plugin_dir_url(__FILE__).'tag-cloud.js', array('jquery'));
}
add_action('init', 'enqueue_tag_cloud_assets');
