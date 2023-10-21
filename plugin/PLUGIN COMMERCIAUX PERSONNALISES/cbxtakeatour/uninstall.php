<?php
/**
 * Fired when the plugin is uninstalled.
 *
 * When populating this file, consider the following flow
 * of control:
 *
 * - This method should be static
 * - Check if the $_REQUEST content actually is the plugin name
 * - Run an admin referrer check to make sure it goes through authentication
 * - Verify the output of $_GET makes sense
 * - Repeat with other user roles. Best directly by using the links/query string parameters.
 * - Repeat things for multisite. Once for a single site in the network, once sitewide.
 *
 * This file may be updated more in future version of the Boilerplate; however, this is the
 * general skeleton and outline for how the file should work.
 *
 * For more information, see the following discussion:
 * https://github.com/tommcfarlin/WordPress-Plugin-Boilerplate/pull/123#issuecomment-28541913
 *
 * @link       https://codeboxr.com
 * @since      1.0.0
 *
 * @package    CBXTakeaTour
 */

// If uninstall not called from WordPress, then exit.
if ( ! defined('WP_UNINSTALL_PLUGIN')) {
    exit;
}

global $wpdb;


//load the setting and helper classes
require_once dirname(__FILE__).'/includes/class-cbxtakeatour-settings.php';
require_once dirname(__FILE__).'/includes/class-cbxtakeatour-helper.php';

$settings = new CBXTakeaTour_Settings();

$delete_global_config = $settings->get_option('delete_global_config', 'cbxtakeatour_tools', 'no');

if ($delete_global_config == 'yes') {
    $option_prefix = 'cbxtakeatour_';

    //delete setting options created by this plugin
    $option_values = CBXTakeaTourHelper::getAllOptionNames();

    foreach ($option_values as $option_value) {
        delete_option($option_value['option_name']);
    }

    //delete tables created by this plugin
    $table_names = CBXTakeaTourHelper::getAllDBTablesList();
    if (sizeof($table_names) > 0) {
        $sql          = "DROP TABLE IF EXISTS ".implode(', ', array_values($table_names));
        $query_result = $wpdb->query($sql);
    }


    do_action('cbxtakeatour_plugin_uninstall');

}//if enabled delete