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

require_once('post-festival.php');
require_once('post-speaker.php');

// ----------------------------------------------------------------------------

add_action('init', 'sogd_change_permalinks');

function sogd_change_permalinks() {
  global $wp_rewrite;
  $wp_rewrite->set_permalink_structure('/%year%/%monthnum%/%day%/%postname%/');
  $wp_rewrite->flush_rules();
}

// ----------------------------------------------------------------------------

add_action('admin_init', 'sogd_register_settings');

function sogd_register_settings()
{
    register_setting('sogd-settings', 'sogd-festival-enabled');
    register_setting('sogd-settings', 'sogd-festival-current');

    register_setting('sogd-settings', 'sogd-front-title');
    register_setting('sogd-settings', 'sogd-front-blurb');
}

// ----------------------------------------------------------------------------

add_action('admin_menu', 'sogd_create_menu');

function sogd_create_menu()
{
    add_menu_page('Strikk og Drikk settings', 'S&D settings', 'administrator', 'sogd', 'sogd_settings_page');
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
