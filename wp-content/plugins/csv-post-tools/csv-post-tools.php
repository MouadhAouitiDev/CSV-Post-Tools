<?php
/**
 * Plugin Name: CSV Post Tools
 * Description: A plugin to import and export posts.
 * Version: 1.0
 * Author: Ardortech
 * Author URI: 
 */


require_once plugin_dir_path(__FILE__) . 'post-import.php';


add_filter('manage_posts_extra_tablenav', 'my_export_posts_button');
function my_export_posts_button($which) {
    if ('top' === $which) {
        ?>
        <div class="alignleft actions">
            <a href="<?php echo admin_url('admin.php?action=my_post_export_all'); ?>" class="button">Export CSV</a>
        </div>
        <?php
    } elseif ('bottom' === $which) {
        ?>
        <div class="alignleft actions">
            <form method="post" enctype="multipart/form-data">
                <input type="file" name="file" />
                <input type="submit" name="submit" class="button"  value="Import" />
            </form>
        </div>
        <?php
    }
}
require_once plugin_dir_path(__FILE__) . 'post-export.php';
