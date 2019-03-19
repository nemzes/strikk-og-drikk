<?php
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
    register_setting('sogd-settings', 'sogd-festivals-parent-cat');

    register_setting('sogd-settings', 'sogd-festival-enabled');
    register_setting('sogd-settings', 'sogd-festival-current-cat');
    register_setting('sogd-settings', 'sogd-festival-current-page');

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

        <h2>Festivals</h2>

        <p>
          <label>
            Parent category for all festivals
            <?php wp_dropdown_categories(array(
                'name' => 'sogd-festivals-parent-cat',
                'selected' => get_option('sogd-festivals-parent-cat'),
                'hierarchical' => true,
                'hide_empty' => false
              ));
            ?>
          </label>
        </p>

        <?php submit_button('Save', 'primary'); ?>

        <h2>Current festival</h2>

        <p>
          <label>
            <input type="checkbox" name="sogd-festival-enabled" <?php echo get_option('sogd-festival-enabled') ? 'checked' : ''; ?> />
            Enable festival
          </label>
        </p>
        <p>
          <label>
            Category for current festival
            <?php wp_dropdown_categories(array(
                'name' => 'sogd-festival-current-cat',
                'child_of' => get_option('sogd-festivals-parent-cat'),
                'selected' => get_option('sogd-festival-current-cat'),
                'hierarchical' => true,
                'depth' => 1,
                'hide_empty' => false
              ));
            ?>
          </label>
        </p>
        <p>
          NB: The events category tree must have a category with the <strong>exact same</strong> slug name.
        </p>
        <p>
          <label>
            Page for current festival
            <?php wp_dropdown_pages(array(
                'name' => 'sogd-festival-current-page',
                'selected' => get_option('sogd-festival-current-page'),
                'show_option_none' => '— none —',
              ));
            ?>
          </label>
        </p>

        <?php submit_button('Save', 'primary'); ?>

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
