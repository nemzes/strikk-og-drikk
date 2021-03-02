<?php
defined('ABSPATH') or die('No script kiddies please!');

add_action( 'init', 'sogd_post_speaker_create' );

function sogd_post_speaker_create() {
    register_taxonomy_for_object_type('category', 'sogd-speaker');

    register_post_type('sogd-speaker',
        array(
        'labels'       => array(
            'name'               => esc_html( 'Speakers', 'sogd' ),
            'singular_name'      => esc_html( 'Speaker', 'sogd' ),
            'add_new'            => esc_html( 'Add New', 'sogd' ),
            'add_new_item'       => esc_html( 'Add New speaker', 'sogd' ),
            'edit'               => esc_html( 'Edit', 'sogd' ),
            'edit_item'          => esc_html( 'Edit speaker', 'sogd' ),
            'new_item'           => esc_html( 'New speaker', 'sogd' ),
            'view'               => esc_html( 'View speaker', 'sogd' ),
            'view_item'          => esc_html( 'View speaker', 'sogd' ),
            'search_items'       => esc_html( 'Search speaker', 'sogd' ),
            'not_found'          => esc_html( 'No speakers found', 'sogd' ),
            'not_found_in_trash' => esc_html( 'No speakers found in Trash', 'sogd' ),
        ),
        'menu_icon'     => 'dashicons-welcome-learn-more',
        'menu_position' => 6,
        'public'        => true,
        'hierarchical'  => false,
        'has_archive'   => true,
        'show_in_rest'  => true,
        'rewrite'       => array(
            'slug'  => 'speakers'
        ),
        'supports'      => array(
            'title',
            'editor',
            'thumbnail'
        ),
        'can_export'    => true
    ) );
}

// ----------------------------------------------------------------------------

add_filter('pre_get_posts', 'sogd_post_speaker_show_in_category');

function sogd_post_speaker_show_in_category( $query ) {
    if ( (is_category() || is_tag()) && $query->is_archive() && empty( $query->query_vars['suppress_filters'] ) ) {
        $query->set( 'post_type', array( 'post', 'sogd-speaker' ));
    }
    return $query;
}

// ----------------------------------------------------------------------------

add_action( 'add_meta_boxes', 'sogd_post_speaker_config_meta_box' );

function sogd_post_speaker_config_meta_box() {
    add_meta_box(
        'sogd_post_speaker_config', // $id
        'Speaker configuration', // $title
        'sogd_post_speaker_configuration', // $callback
        'sogd-speaker', // $screen
        'normal', // $context
        'high' // $priority
    );
}

function sogd_post_speaker_configuration() {
    global $post;
    $sogd_speaker_fields = get_post_meta( $post->ID, 'sogd_speaker', true );
    ?>
        <input
            name="sogd_speaker_configuration_nonce"
            type="hidden"
            value="<?php echo wp_create_nonce( basename(__FILE__) ); ?>"
        >

        <h3>Festival</h3>

        <?php
            wp_dropdown_pages(array(

                'hide_empty'       => 0,
                'show_option_none' => ' — Please select — ',
                'post_type'        => 'sogd-festival',
                'id'               => 'sogd_speaker_festival',
                'name'             => 'sogd_speaker[festival]',
                'selected'         => is_array($sogd_speaker_fields) ? $sogd_speaker_fields['festival'] : -1
            ));
        ?>
    <?php
}

// ----------------------------------------------------------------------------

add_action('save_post', 'sogd_post_speaker_save');

function sogd_post_speaker_save( $post_id ) {
    // only run this for series
    if ('sogd-speaker' != get_post_type($post_id)) {
        return $post_id;
    }

    // verify nonce
    if (empty($_POST['sogd_speaker_configuration_nonce']) || !wp_verify_nonce($_POST['sogd_speaker_configuration_nonce'], basename(__FILE__))) {
        return $post_id;
    }

    // check autosave
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return $post_id;
    }

    // check permissions
    if (!current_user_can('edit_post', $post_id)) {
        return $post_id;
    }

    $old = get_post_meta( $post_id, 'sogd_speaker', true );
    $new = $_POST['sogd_speaker'];

    if ( $new && $new !== $old ) {
        update_post_meta( $post_id, 'sogd_speaker', $new );
    } elseif ( '' === $new && $old ) {
        delete_post_meta( $post_id, 'sogd_speaker', $old );
    }
}
