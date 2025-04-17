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

/* variables & objects */
use Util\DbTableManager;
require( __DIR__ . '/Util/DbTableManager.php');

/* Plugin Activation & Installation Management Hooks */
register_activation_hook(__FILE__, 'cm_activate');

/* Actions */
//todo fix script loading, loads in when not supposed too, particularly admin.js
add_action('admin_menu', 'cm_menu');
add_action('admin_post_dp_export_action', 'cm_export_data');
add_action('admin_enqueue_scripts', 'cm_enqueue_admin_scripts');
add_action('admin_footer', 'cm_export_button');

function cm_activate(): void {
	try{
        global $wpdb;
		$dpTableManager = new DbTableManager($wpdb);
		$dpTableManager->initTables();
	} catch (\Exception $e) {
		//todo implement cleaner and more proper error reporting
		var_dump($e->getMessage());
	}
}

function cm_menu(): void {
	add_menu_page(
		'Code Management', // Page title (for the admin panel)
		'Code Manager', // Menu title (what users see)
		'manage_options', // Required capability
		'code-manager-page', // Menu slug (unique identifier)
		'cm_page_content' // Callback function to display content
	);
}

function cm_page_content(): void {
    ?>
    <div class="wrap">
		<?php include( plugin_dir_path( __FILE__ ) . 'Admin/admin.php' ); ?>
    </div>
	<?php
}

//exports table data, called by export button
function cm_export_action(): void {
	try{
		global $wpdb;

		$dpTableManager = new DbTableManager($wpdb);
		$dpTableManager->exportTables();
		exit;
	} catch (\Exception $e) {
		//todo implement cleaner and more proper error reporting
		var_dump($e->getMessage());
	}
}

//sets up export url for export button and admin.js
function cm_enqueue_admin_scripts($hook): void {
	wp_enqueue_script('your-plugin-admin-script', plugin_dir_url( __FILE__ ) . 'Admin/admin.js', array( 'jquery' ), '1.0', false);

    wp_enqueue_script('jquery.validate', '//ajax.aspnetcdn.com/ajax/jquery.validate/1.14.0/jquery.validate.min.js"');
    wp_enqueue_script('jquery.validate', '//ajax.aspnetcdn.com/ajax/jquery.validate/1.14.0/additional-methods.min.js"');
	wp_enqueue_style('bootstrap', 'https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css');
	wp_enqueue_script('toastr', plugin_dir_url( __FILE__ ) . 'Assets/toastr/toastr.js', array('jquery'));
    wp_enqueue_style('toastr', plugin_dir_url( __FILE__ ) . 'Assets/toastr/build/toastr.css');

	//plugins page specific javascript
    if ('plugins.php' === $hook) {
	    //wp_enqueue_style('my-plugin-admin-style', plugin_dir_url(__FILE__) . 'css/admin.css');
	    wp_enqueue_script('my-plugin-admin-script', plugin_dir_url(__FILE__) . '', array('jquery'), '1.0', true);
	    wp_localize_script('my-plugin-admin-script', 'my_plugin_vars', array(
		    'nonce' => wp_create_nonce('my_plugin_custom_action'),
		    'action' => 'my_plugin_custom_action',
		    'export_url' => admin_url('admin-post.php?action=cm_export_action') // Add the URL here
	    ));
	}
}

//export button
function cm_export_button(): void {
	$screen = get_current_screen();
	if ($screen->id !== 'plugins') {
		return;
	}
	?>
	<script type="text/javascript">
        jQuery(document).ready(function($) {
            $('#deactivate-docport').each(function() {
                //Find deactivate button
                var targetButton = $('#deactivate-docport');

                if (targetButton) {
                    var pluginRow = $(this).closest('tr');
                    var pluginSlug = pluginRow.find('.plugin-title strong').text().toLowerCase().replace(/ /g, '-'); // Get plugin slug

                    var buttonHTML = '<a class="" style="margin-left: 5px;" data-plugin-slug="' + pluginSlug + '" href="' + my_plugin_vars.export_url + '">Export Tables</a>';
                    targetButton.after(buttonHTML);
                }
            });
        });
	</script>
	<?php
}

add_shortcode('cmanager', 'cmanager_shortcode');
function cmanager_shortcode( $atts = [], $content = null): void {
	global $wpdb;
	$dbTableManager = new DbTableManager($wpdb);

	wp_enqueue_script('jquery.validate', '//ajax.aspnetcdn.com/ajax/jquery.validate/1.14.0/jquery.validate.min.js"');
	wp_enqueue_script('jquery.validate', '//ajax.aspnetcdn.com/ajax/jquery.validate/1.14.0/additional-methods.min.js"');
	wp_enqueue_script('toastr', plugin_dir_url( __FILE__ ) . 'Assets/toastr/toastr.js', array('jquery'));
	wp_enqueue_style('toastr', plugin_dir_url( __FILE__ ) . 'Assets/toastr/build/toastr.css');
	wp_enqueue_script(
		'your-plugin-shortcode-script', // Unique handle for the script
		plugin_dir_url( __FILE__ ) . 'Shortcode/shortcode.js', // Path to your script file
		array( 'jquery' ), // Dependencies (e.g., jQuery)
		'1.0', // Version number (optional, but recommended for cache busting)
		false // Load in the footer (true) or header (false)
	);
	?>
    <div class="wrap">
        <?php include(plugin_dir_path( __FILE__ ) . 'Shortcode/shortcode.php'); ?>
    </div>
	<?php
}
?>