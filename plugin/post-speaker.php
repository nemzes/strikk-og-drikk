<?php
defined('ABSPATH') or die('No script kiddies please!');

add_action( 'init', 'sogd_post_speaker_create' );

function sogd_post_speaker_create() {
    register_taxonomy_for_object_type( 'category', 'sogd-speaker' );
    register_post_type( 'sogd-speaker',
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
        'public'       => true,
        'hierarchical' => false,
        'has_archive'  => true,
        'show_in_rest' => true,
        'rewrite'      => array(
            'slug'  => 'speakers'
        ),
        'supports'     => array(
            'title',
            'editor',
            'thumbnail'
        ),
        'can_export'   => true,
        'taxonomies'   => array(
            'post_tag',
            'category'
        )
    ) );
}

// ----------------------------------------------------------------------------

add_filter( 'pre_get_posts', 'sogd_post_speaker_show_in_category' );

function sogd_post_speaker_show_in_category( $query ) {
    if ( (is_category() || is_tag()) && $query->is_archive() && empty( $query->query_vars['suppress_filters'] ) ) {
        $query->set( 'post_type', array( 'post', 'sogd-speaker' ));
    }
    return $query;
}

// ----------------------------------------------------------------------------

add_action( 'admin_init', 'sogd_post_speaker_add_festival_meta_box' );

function sogd_post_speaker_add_festival_meta_box() {
    add_meta_box( 'sogd_post_speaker_festival', 'Festivals', 'sogd_post_speaker_festival_field', 'sogd-speaker' );
}

function sogd_post_speaker_festival_field() {
    global $post;

    $all_festivals = get_posts( array(
        'post_type' => 'sogd-festival',
        'numberposts' => -1,
        'orderby' => 'post_title',
        'order' => 'ASC'
    ) );
    ?>
        <input
            type="hidden"
            name="sogd_post_speaker_festival_nonce"
            value="<?php echo wp_create_nonce( basename( __FILE__ ) ); ?>"
        />

        <label for="sogd_post_speaker_festival">Festival</label>
        <select name="sogd_post_speaker_festival">
            <option value="">— None —</option>
            <?php foreach ( $all_festivals as $festival ) : ?>
                <option
                    value="<?php echo $festival->ID; ?>"
                    <?php echo ( $festival->ID == $post->sogd_linked_festival ) ? 'selected="selected"' : ''; ?>
                >
                    <?php echo htmlspecialchars($festival->post_title); ?>
                </option>
            <?php endforeach; ?>
        </select>
    <?php
}

add_action( 'save_post', 'sogd_post_speaker_save' );

function sogd_post_speaker_save( $post_id ) {

    // only run this for series
    if ('sogd-speaker' != get_post_type($post_id)) {
        return $post_id;
    }

    // verify nonce
    if (empty($_POST['sogd_post_speaker_festival_nonce']) || !wp_verify_nonce($_POST['sogd_post_speaker_festival_nonce'], basename(__FILE__))) {
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

    // save
    update_post_meta(
        $post_id,
        'sogd_linked_festival',
        $_POST['sogd_post_speaker_festival']
    );
}
