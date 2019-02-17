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

// create custom plugin settings menu
add_action('admin_menu', 'sogd_create_menu');

function sogd_create_menu()
{
    add_menu_page('Strikk og Drikk settings', 'S&D settings', 'administrator', 'sogd', 'sogd_settings_page');
    add_action('admin_init', 'sogd_register_settings');
}

function sogd_register_settings()
{
    //register our settings
    register_setting('sogd-settings', 'sogd-festival-enabled');
    register_setting('sogd-settings', 'sogd-festival-title');
    register_setting('sogd-settings', 'sogd-festival-posts-base-cat');
    register_setting('sogd-settings', 'sogd-festival-events-cat');
    register_setting('sogd-settings', 'sogd-festival-front-blurb');

    register_setting('sogd-settings', 'sogd-front-title');
    register_setting('sogd-settings', 'sogd-front-blurb');
}

function sogd_settings_page()
{
    ?>
    <div class="wrap">
      <h1>Strikk og Drikk settings</h1>

      <form method="post" action="options.php">
        <?php settings_fields('sogd-settings'); ?>
        <?php do_settings_sections('sogd-settings'); ?>

        <h2>Festival</h2>

        <p>
          <label>
            <input type="checkbox" name="sogd-festival-enabled" <?php echo get_option('sogd-festival-enabled') ? 'checked' : ''; ?> />
            Enable festival
          </label>
        </p>
        <p>
          <label>
            Festival title
            <input type="text" name="sogd-festival-title" value="<?php echo esc_attr(get_option('sogd-festival-title')); ?>" />
          </label>
        </p>
        <p>
          <label>
            Festival posts base category
            <?php wp_dropdown_categories(array(
                'name' => 'sogd-festival-posts-base-cat',
                'selected' => get_option('sogd-festival-posts-base-cat'),
                'hierarchical' => true,
                'hide_empty' => false
              ));
            ?>
          </label>
        </p>
        <p>
          <label>
            Festival events category
            <?php wp_dropdown_categories(array(
                'name' => 'sogd-festival-events-cat',
                'selected' => get_option('sogd-festival-events-cat'),
                'hide_empty' => false,
                'taxonomy' => 'event-category'
              ));
            ?>
          </label>
        </p>
        <p>Festival front page blurb</p>
        <?php
          $content = get_option('sogd-festival-front-blurb');
          wp_editor(
            $content,
            'sogd-festival-front-blurb',
            $settings = array(
              'textarea_rows' => '10',
              'teeny' => true,
              'media_buttons' => false,
              'quicktags' => false,
            ));
        ?>

        <h2>Front page</h2>

        <p>
          <label>
            Front page title
            <input type="text" name="sogd-front-title" value="<?php echo esc_attr(get_option('sogd-front-title')); ?>" />
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
            ));
        ?>
        <?php submit_button('Save', 'primary'); ?>
      </form>
    </div>
  <?php
}
