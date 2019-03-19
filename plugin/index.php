<?php
/*
Plugin Name: Strikk og Drikk
Description: Sets up site for Strikk og Drikk
Version: 0.1.0
Author: Nelson Menezes
Author URI: https://fittopage.org
License: MIT
*/

defined('ABSPATH') or die('No script kiddies please!');

include_once('admin.php');
include_once('post-festival.php');
include_once('post-speaker.php');

// plugin init
add_action('init', 'sogd_change_permalinks');

function sogd_change_permalinks() {
  global $wp_rewrite;
  $wp_rewrite->set_permalink_structure('/%year%/%monthnum%/%day%/%postname%/');
  $wp_rewrite->flush_rules();
}
