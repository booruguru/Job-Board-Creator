<?php

/**

 * Plugin Name: Job Board Creator
 * Description: Job Board Platform for WordPress
 * Plugin URI: http://jbcreator.com
 * Version: 1.0
 * License: GPL
 * Text Domain: jbc

 */
 
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit; 

define ('JBC_DIR', plugin_dir_path( __FILE__ ));


require_once('inc/relative-timestamp.php');
require_once('inc/jobs-tax.php');
require_once('inc/jobs-cpt.php');
require_once('inc/tax-metadata.php');
require_once('inc/transactions-cpt.php');
require_once('inc/wp-advanced-search/wpas.php');
require_once('inc/post-counter.php');
require_once('inc/read-unread.php');
require_once('inc/applications.php');
require_once('inc/widgets.php');
require_once('inc/settings/options-framework.php');
require_once('inc/options.php');

require_once('inc/shortcodes.php');
require_once('inc/profile-fields.php');
require_once('inc/restrictions.php');

register_activation_hook( __FILE__, 'jbc_activate' );


/* hook updater to init */
add_action( 'init', 'jbc_updater_init' );

/**
 * Load and Activate Plugin Updater Class.
 */
function jbc_updater_init() {

    /* Load Plugin Updater */
    require_once( trailingslashit( plugin_dir_path( __FILE__ ) ) . 'inc/update/autohosted.php' );

    /* Updater Config */
    $config = array(
        'base'      => plugin_basename( __FILE__ ), //required
        'dashboard' => false,
        'username'  => false,
        'key'       => '',
        'repo_uri'  => 'http://jbcreator.userhost.net/',
        'repo_slug' => 'jbc',
    );

    /* Load Updater Class */
    new UserPress_Updater( $config );
}
  