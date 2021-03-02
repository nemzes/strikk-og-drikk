<?php
defined('ABSPATH') or die('No script kiddies please!');

add_action( 'init', 'sogd_post_festival_create' );

function sogd_post_festival_create() {
    register_post_type( 'sogd-festival',
        array(
        'labels'       => array(
            'name'               => esc_html( 'Festivals', 'sogd' ),
            'singular_name'      => esc_html( 'Festival', 'sogd' ),
            'add_new'            => esc_html( 'Add New', 'sogd' ),
            'add_new_item'       => esc_html( 'Add New festival', 'sogd' ),
            'edit'               => esc_html( 'Edit', 'sogd' ),
            'edit_item'          => esc_html( 'Edit festival', 'sogd' ),
            'new_item'           => esc_html( 'New festival', 'sogd' ),
            'view'               => esc_html( 'View festival', 'sogd' ),
            'view_item'          => esc_html( 'View festival', 'sogd' ),
            'search_items'       => esc_html( 'Search festival', 'sogd' ),
            'not_found'          => esc_html( 'No festivals found', 'sogd' ),
            'not_found_in_trash' => esc_html( 'No festivals found in Trash', 'sogd' ),
        ),
        'menu_icon'     => 'dashicons-star-filled',
        'menu_position' => 5,
        'public'        => true,
        'hierarchical'  => true,
        'has_archive'   => true,
        'show_in_rest'  => true,
        'rewrite'       => array(
            'slug'  => 'festivals'
        ),
        'supports'      => array(
            'title',
            'editor',
            'thumbnail'
        ),
        'can_export'   => true
    ) );
}

// ----------------------------------------------------------------------------

add_action( 'add_meta_boxes', 'sogd_post_festival_config_meta_box' );

function sogd_post_festival_config_meta_box() {
    add_meta_box(
        'sogd_post_festival_config', // $id
        'Festival configuration', // $title
        'sogd_post_festival_configuration', // $callback
        'sogd-festival', // $screen
        'normal', // $context
        'high' // $priority
    );
}

function sogd_post_festival_configuration() {
    global $post;
    $sogd_festival_fields = get_post_meta( $post->ID, 'sogd_festival', true );
    ?>
        <input
            name="sogd_post_festival_configuration_nonce"
            type="hidden"
            value="<?php echo wp_create_nonce( basename(__FILE__) ); ?>"
        >

        <h3>Front page blurb</h3>

        <?php
            wp_editor(
                is_array($sogd_festival_fields) ? $sogd_festival_fields['front-blurb'] : '',
                'sogd_festival_front_blurb',
                $settings = array(
                    'media_buttons' => false,
                    'textarea_name' => 'sogd_festival[front-blurb]',
                    'textarea_rows' => 4,
                    'teeny' => true,
                    'quicktags' => false
                )
            );
        ?>

        <p class="description">
            The blurb will be on the front page of the site if this is the active festival
        </p>

        <h3>External link</h3>

        <input
            name="sogd_festival[external-link]"
            value="<?php echo is_array($sogd_festival_fields) ? $sogd_festival_fields['external-link'] : '' ?>"
        >

        <p class="description">
            If external link is defined, categories and posts are ignored and not output in front page. Leave blank to
            display the normal festival menu items instead.
        </p>

        <h3>Parent category for festival posts</h3>

        <?php
            wp_dropdown_categories( array(
                'hide_empty'         => 0,
                'show_option_none'   => ' — Please select — ',
                'selected'           => is_array($sogd_festival_fields) ? $sogd_festival_fields['posts-cat'] : -1,
                'hierarchical'       => 1,
                'id'                 => 'sogd_festival_posts_cat',
                'name'               => 'sogd_festival[posts-cat]',
            ) );
        ?>

        <p class="description">Subcategories of this become the menu of the festival</p>

        <h3>Parent category for festival events</h3>

        <?php
            wp_dropdown_categories( array(
                'hide_empty'         => 0,
                'show_option_none'   => ' — Please select — ',
                'selected'           => is_array($sogd_festival_fields) ? $sogd_festival_fields['events-cat'] : -1,
                'hierarchical'       => 1,
                'id'                 => 'sogd_festival_events_cat',
                'name'               => 'sogd_festival[events-cat]',
                'taxonomy'           => 'event-category',
            ) );
        ?>

        <p class="description">Events within this become part of the program</p>
    <?php
}

add_action( 'save_post', 'sogd_post_festival_configuration_save' );

function sogd_post_festival_configuration_save( $post_id ) {

    // only run this for festivals
    if ('sogd-festival' != get_post_type($post_id)) {
        return $post_id;
    }

    // verify nonce
    if ( !wp_verify_nonce( $_POST['sogd_post_festival_configuration_nonce'], basename(__FILE__) ) ) {
        return $post_id;
    }

    // check autosave
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
        return $post_id;
    }

    // check permissions
    if ( 'sogd-festival' === $_POST['post_type'] ) {
        if ( !current_user_can( 'edit_page', $post_id ) ) {
            return $post_id;
        } elseif ( !current_user_can( 'edit_post', $post_id ) ) {
            return $post_id;
        }
    }

    $old = get_post_meta( $post_id, 'sogd_festival', true );
    $new = $_POST['sogd_festival'];

    if ( $new && $new !== $old ) {
        update_post_meta( $post_id, 'sogd_festival', $new );
    } elseif ( '' === $new && $old ) {
        delete_post_meta( $post_id, 'sogd_festival', $old );
    }
}
