<?php
defined('ABSPATH') or die('No script kiddies please!');

add_action('add_meta_boxes', function () {
  add_meta_box(
    'sogd_event_config', // $id
    'Festival', // $title
    'sogd_event_configuration', // $callback
    'event', // $screen
    'normal', // $context
    'high' // $priority
  );
});

function sogd_event_configuration()
{
  global $post;
  $sogd_speaker = get_post_meta($post->ID, 'sogd_speaker', true);
?>

  <input name="sogd_event_configuration_nonce" type="hidden" value="<?php echo wp_create_nonce(basename(__FILE__)); ?>">

  <p>This is only relevant if the event belongs to a festival!</p>

  <h3>Bidragsyter</h3>

<?php
  wp_dropdown_pages(array(
    'hide_empty'       => 0,
    'show_option_none' => ' — Please select — ',
    'post_type'        => 'sogd-speaker',
    'id'               => 'sogd_speaker',
    'name'             => 'sogd_speaker',
    'selected'         => $sogd_speaker
  ));
}

// ----------------------------------------------------------------------------

add_action('save_post', function ($post_id) {
  // only run this for events
  if ('event' != get_post_type($post_id)) {
    return $post_id;
  }

  // verify nonce
  if (!wp_verify_nonce($_POST['sogd_event_configuration_nonce'], basename(__FILE__))) {
    return $post_id;
  }

  // check autosave
  if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
    return $post_id;
  }

  //var_dump(current_user_can('edit_page', $post_id));
  //exit;
  // check permissions
  if (!current_user_can('edit_event', $post_id)) {
    return $post_id;
  }

  $old = get_post_meta($post_id, 'sogd_speaker', true);
  $new = $_POST['sogd_speaker'];

  if ($new && $new !== $old) {
    update_post_meta($post_id, 'sogd_speaker', $new);
  } elseif ('' === $new && $old) {
    delete_post_meta($post_id, 'sogd_speaker', $old);
  }
});
