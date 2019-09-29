<?php
/*
Plugin Name: Strikk og Drikk
Description: Sets up site for Strikk og Drikk
Version: 1.0.0
Author: Nelson Menezes
Author URI: https://fittopage.org
License: MIT
*/

defined('ABSPATH') or die('No script kiddies please!');

require_once('post-festival.php');
require_once('post-speaker.php');

define('SOGD_ARCHIVE_PAGE_SLUG', 'arkiv');
define('SOGD_EVENTS_PAGE_SLUG', 'arrangementer');

// ----------------------------------------------------------------------------

add_filter('allowed_block_types', 'sogd_allowed_blocks', 10, 2);

function sogd_allowed_blocks($allowed_block_types, $post) {
    if (!in_array($post->post_type, array(
      'sogd-festival',
      'sogd-speaker',
      'post',
      'page',
    ))) {
        return $allowed_block_types;
    }
    
    return array(
        'core/gallery',
        'core/heading',
        'core/image',
        'core/list',
        'core/paragraph',
        'core/separator',
        'core/shortcode',
        'core-embed/facebook',
        'core-embed/instagram',
        'core-embed/twitter',
        'core-embed/youtube',
    );
}

// ----------------------------------------------------------------------------

add_action('init', 'sogd_change_permalinks');
add_action('init', 'sogd_remove_featured_images');
add_action('init', 'sogd_create_archive_page');
add_action('init', 'sogd_create_events_page');

function sogd_remove_featured_images() {
  remove_post_type_support('event', 'thumbnail');
  remove_post_type_support('page', 'thumbnail');
  remove_post_type_support('post', 'thumbnail');
}

function sogd_change_permalinks() {
  global $wp_rewrite;
  $wp_rewrite->set_permalink_structure('/%year%/%monthnum%/%day%/%postname%/');
  $wp_rewrite->flush_rules();
}

function sogd_create_archive_page() {
  $args = array(
    'name' => SOGD_ARCHIVE_PAGE_SLUG,
    'numberposts' => 1,
    'post_type' => 'page',
  );

  $posts = get_posts($args);

  if (count($posts) === 0) {
    $post_details = array(
      'post_title' => 'Arkiv',
      'post_name' => SOGD_ARCHIVE_PAGE_SLUG,
      'post_content' => '',
      'post_status' => 'publish',
      'post_type' => 'page'
    );
  
     wp_insert_post($post_details);
  }
}

function sogd_create_events_page() {
  $args = array(
    'name' => SOGD_EVENTS_PAGE_SLUG,
    'numberposts' => 1,
    'post_type' => 'page',
  );

  $posts = get_posts($args);

  if (count($posts) === 0) {
    $post_details = array(
      'post_title' => 'Arrangementer',
      'post_name' => SOGD_EVENTS_PAGE_SLUG,
      'post_content' => '',
      'post_status' => 'publish',
      'post_type' => 'page'
    );
  
     wp_insert_post($post_details);
  }
}

// ----------------------------------------------------------------------------

add_filter('pre_get_posts', 'sogd_search_filter');

function sogd_search_filter($query) {
  if ($query->is_search) {
    $archive_id = get_page_by_path(SOGD_ARCHIVE_PAGE_SLUG)->ID;
    $events_id = get_page_by_path(SOGD_EVENTS_PAGE_SLUG)->ID;
    $query->set('post__not_in', array($archive_id, $events_id));
  }
  return $query;
}

// ----------------------------------------------------------------------------

add_action('admin_init', 'sogd_register_settings');
add_action('admin_init', 'sogd_check_dependencies');

function sogd_register_settings() {
  register_setting('sogd-settings', 'sogd-festival-enabled');
  register_setting('sogd-settings', 'sogd-festival-current');

  register_setting('sogd-settings', 'sogd-front-title');
  register_setting('sogd-settings', 'sogd-front-blurb');
}

function sogd_check_dependencies() {
  if (!is_plugin_active('event-organiser/event-organiser.php')) {
    add_action( 'admin_notices', 'sogd_add_notice_dep_event_organiser' );
  }
}

function sogd_add_notice_dep_event_organiser() {
  ?>
  <div class="notice notice-error">
    <p>S&D: The Event Organiser plugin is not active!</p>
  </div>
  <?php
}

// ----------------------------------------------------------------------------

add_action('admin_menu', 'sogd_create_menu');

function sogd_create_menu() {
  add_menu_page('Strikk og Drikk settings', 'S&D settings', 'administrator', 'sogd', 'sogd_settings_page');
}

function sogd_settings_page() {
  ?>
    <div class="wrap">
      <h1>Strikk og Drikk settings</h1>

      <form method="post" action="options.php">
        <?php settings_fields('sogd-settings'); ?>
        <?php do_settings_sections('sogd-settings'); ?>

        <h2>Festival</h2>

        <p>
          <label>
            <input
              type="checkbox"
              name="sogd-festival-enabled"
              <?php echo get_option('sogd-festival-enabled') ? 'checked' : ''; ?>
            />
            Enable festival
          </label>
        </p>
        <p>
          <label>
            Current festival
            <?php
              wp_dropdown_pages(array(
                'name' => 'sogd-festival-current',
                'selected' => get_option('sogd-festival-current'),
                'show_option_none' => '— none —',
                'hierarchical' => false,
                'post_type' => 'sogd-festival'
              ));
            ?>
          </label>
        </p>

        <?php submit_button('Save', 'primary'); ?>

        <h2>Front page</h2>

        <p>
          <label>
            Front page title
            <input type="text" name="sogd-front-title"
              value="<?php echo esc_attr(get_option('sogd-front-title')); ?>" />
          </label>
        </p>
        <p>Front page blurb</p>
        <?php
          $content = get_option('sogd-front-blurb');
          wp_editor(
            $content,
            'sogd-front-blurb',
            $settings = array(
              'textarea_rows' => '10',
              'teeny' => true,
              'media_buttons' => false,
              'quicktags' => false,
            )
          );
        ?>
        <?php submit_button('Save', 'primary'); ?>
      </form>
    </div>
  <?php
}

// ----------------------------------------------------------------------------

function sogd_update_all_events() {
  // Update all events in case they were migrated from another site
  // @see https://wordpress.org/support/topic/fatal-error-uncaught-exception-15/

  $args = array(
    'post_type' => array('event'),
    'showpastevents' => true,
    'suppress_filters' => true,
    'no_found_rows'=> true,
    'posts_per_page' => 10000,
  );
  
  $query = new WP_Query($args);
  $events = $query->posts;

  foreach ($events as $event) {
    eo_update_event($event->ID);
  }
}

register_activation_hook(__FILE__, 'sogd_update_all_events');
