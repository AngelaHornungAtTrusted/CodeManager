<?php
/*
 * Plugin Name: CodeManager
 * Description: Code/lottery manager
 * Version: 1.0
 * Requires at least: 5.2
 * Requires PHP: 8.0.0
 * Author: Angela Hornung
 * Prefix: cm
 */

//load classes & configs
require_once(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'CodeManagerConfig.php');
require_once(CM_ROOT_DIR_PATH . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php');
require_once(CM_UTIL_DIR_PATH . DIRECTORY_SEPARATOR . 'cm-ajax.php');

/* variables & objects */

use Util\DbTableManager;

/* Plugin Activation & Installation Management Hooks */
register_activation_hook(__FILE__, 'cm_activate');

/* Actions */
add_action('admin_menu', 'cm_menu');
add_action('admin_enqueue_scripts', 'cm_enqueue_admin_scripts');

//todo clean implementation of this feature
/*add_action('admin_post_dp_export_action', 'cm_export_data');
add_action('admin_footer', 'cm_export_button');*/

/* Ajax Actions */
add_action('wp_ajax_cm_new_code', 'wp_ajax_cm_new_code');
add_action('wp_ajax_cm_update_code', 'wp_ajax_cm_update_code');
add_action('wp_ajax_cm_get_codes', 'wp_ajax_cm_get_codes');
add_action('wp_ajax_cm_delete_code', 'wp_ajax_cm_delete_code');
add_action('wp_ajax_cm_code_exists', 'wp_ajax_cm_code_exists');
add_action('wp_ajax_cm_get_settings', 'wp_ajax_cm_get_settings');
add_action('wp_ajax_cm_post_settings', 'wp_ajax_cm_post_settings');

function cm_activate(): void
{
    try {
        global $wpdb;
        $dbTableManager = new DbTableManager($wpdb);
        $dbTableManager->initTables();
        $dbTableManager->initSettings();
    } catch (\Exception $e) {
        //todo implement cleaner and more proper error reporting
        var_dump($e->getMessage());
    }
}

function cm_menu(): void
{
    add_menu_page(
        'Code Management', // Page title (for the admin panel)
        'Code Manager', // Menu title (what users see)
        'manage_options', // Required capability
        'code-manager-page', // Menu slug (unique identifier)
        'cm_page_content' // Callback function to display content
    );
}

function cm_page_content(): void
{
    ?>
    <div class="wrap">
        <?php include(plugin_dir_path(__FILE__) . 'Admin/admin.php'); ?>
        <?php wp_enqueue_script('admin-js', CM_ADMIN_URL . '/admin.js"', array('jquery')); ?>
    </div>
    <?php
}

function cm_enqueue_admin_scripts($hook): void
{
    wp_enqueue_style('bootstrap-css', CM_ASSETS_URL . '/bootstrap/css/bootstrap.css"');
    wp_enqueue_script('bootstrap-js', CM_ASSETS_URL . '/bootstrap/js/bootstrap.js"');

    wp_enqueue_script('toastr', plugin_dir_url(__FILE__) . 'Assets/toastr/toastr.js', array('jquery'));
    wp_enqueue_style('toastr', plugin_dir_url(__FILE__) . 'Assets/toastr/build/toastr.css');
}

add_shortcode('cmanager', 'cmanager_shortcode');
function cmanager_shortcode($atts = [], $content = null): void
{
    ?>
    <div class="wrap">
        <?php include(plugin_dir_path(__FILE__) . 'Shortcode/shortcode.php'); ?>
        <?php wp_enqueue_script('admin-js', CM_SHORTCODE_URL . '/shortcode.js"', array('jquery')); ?>
    </div>
    <?php
}
?>