<?php
/*
Plugin Name: Duplicador de Postagens NextPress
Description: Esse plugin permite duplicar postagens de forma simples.
Author: Gustavo Modesto
Domain Path: /languages
Text Domain: dpn-plugin
Version: 1.0.0
*/

if(!defined('ABSPATH')){
    exit;
}

require_once plugin_dir_path(__FILE__) . 'includes/dpn-duplicate-functions.php';
require_once plugin_dir_path(__FILE__) . 'includes/dpn-bulk-functions.php';
require_once plugin_dir_path(__FILE__) . 'includes/dpn-notice-functions.php';

/** 
 * Adds new removable query args to the WordPress database
 * 
 * @param  array $dpn_removable_query_args The array containing the current removable query args
 *   
 * @return array The modified array of removable query args with the newly added args
 */
function dpn_removable_query_args($dpn_removable_query_args){
    
    if(!is_array($dpn_removable_query_args)){
        return $dpn_removable_query_args;
    }
    
    $dpn_removable_query_args[] = 'dpn_duplicated_objects';
    $dpn_removable_query_args[] = 'dpn_obj_id';
    $dpn_removable_query_args[] = 'dpn_duplicate_post';
    $dpn_removable_query_args[] = 'nonce';
    
    return $dpn_removable_query_args;
}

/**
 * Loading plugin translation files
 */
load_plugin_textdomain('dpn-plugin', FALSE, dirname(plugin_basename(__FILE__)) . '/languages/');
add_action('plugins_loaded', 'load_plugin_textdomain');

function my_plugin_load_my_own_textdomain( $mofile, $domain ) {
    if ( 'dpn-plugin' === $domain && false !== strpos( $mofile, WP_LANG_DIR . '/plugins/' ) ) {
        $locale = apply_filters( 'plugin_locale', determine_locale(), $domain );
        $mofile = WP_PLUGIN_DIR . '/' . dirname( plugin_basename( __FILE__ ) ) . '/languages/' . $domain . '-' . $locale . '.mo';
    }
    return $mofile;
}

add_filter( 'load_textdomain_mofile', 'my_plugin_load_my_own_textdomain', 10, 2 );

add_filter('page_row_actions', 'dpn_add_duplicate_link', 10, 2);
add_filter('post_row_actions', 'dpn_add_duplicate_link', 10, 2);

add_action('admin_action_dpn_duplicate_post', 'dpn_duplicate_post');

add_filter('bulk_actions-edit-post', 'dpn_bulk_actions');
add_filter('handle_bulk_actions-edit-post', 'dpn_bulk_action_handler', 10, 3);

add_filter('bulk_actions-edit-page', 'dpn_bulk_actions');
add_filter('handle_bulk_actions-edit-page', 'dpn_bulk_action_handler', 10, 3);

add_filter('removable_query_args', 'dpn_removable_query_args');

add_action('admin_notices', 'dpn_copy_action_notice', 10, 1);
