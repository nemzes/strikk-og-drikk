<?php
/*
Plugin Name: Strikk og Drikk
Description: Sets up site for Strikk og Drikk
Version: 0.1.0
Author: Nelson Menezes
Author URI: https://fittopage.org
License: MIT
*/

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

// create custom plugin settings menu
add_action('admin_menu', 'sogd_create_menu');

function sogd_create_menu() {
    add_menu_page('Strikk og Drikk settings', 'S&D settings', 'administrator', 'sogd', 'sogd_settings_page');
    add_action( 'admin_init', 'sogd_register_settings' );
}

function sogd_register_settings() {
    //register our settings
    register_setting( 'sogd-settings', 'sogd-festival-front-blurb' );
    register_setting( 'sogd-settings', 'sogd-festival-enabled' );
}

function sogd_settings_page() {
?>
<div class="wrap">
<h1>Your Plugin Name</h1>

<form method="post" action="options.php">
    <?php settings_fields( 'sogd-settings' ); ?>
    <?php do_settings_sections( 'sogd-settings' ); ?>

    <label>
        <input type="checkbox" name="sogd-festival-enabled" value="<?php echo esc_attr( get_option('sogd-festival-enabled') ); ?>" />
        Enable festival
    </label>
    <h2>Festival blurb</h2>
    <?php
        $content = get_option('sogd-festival-front-blurb');
        wp_editor( $content, 'sogd-festival-front-blurb', $settings = array('textarea_rows'=> '10') );
        submit_button('Save', 'primary');
    ?>    
</form>
</div>
<?php
}