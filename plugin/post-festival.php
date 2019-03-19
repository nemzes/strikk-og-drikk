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
        'public'       => true,
        'hierarchical' => false,
        'has_archive'  => true,
        'show_in_rest' => true,
        'rewrite'      => array(
            'slug'  => 'festivals'
        ),
        'supports'     => array(
            'title',
            'editor',
            'thumbnail'
        ),
        'can_export'   => true
    ) );
}

// ----------------------------------------------------------------------------

add_filter( 'allowed_block_types', 'sogd_post_festival_allowed_blocks', 10, 2 );

function sogd_post_festival_allowed_blocks( $allowed_block_types, $post ) {
    if ( $post->post_type !== 'sogd-festival' ) {
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
