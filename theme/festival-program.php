<?php get_header(); ?>
<?php
$festival = get_page_by_path(get_query_var('festival'), OBJECT, 'sogd-festival');
$event_cat = sogd_get_festival_parent_event_cat($festival);

$args = array(
  'post_type' => 'event',
  'suppress_filters' => false,
  'posts_per_page' => -1,
  'tax_query' => array(
    array(
      'taxonomy' => 'event-category',
      'field'    => 'term_id',
      'terms'    => $event_cat,
    ),
  ),
  'showpastevents' => true
);

query_posts($args);
?>

<main role="main" aria-label="Content" class="ssod-main ssod-listing ssod-listing--festival">
  <?php sogd_output_festival_header($festival) ?>
  <div class="ssod-listing__content">
    <div class="ssod-listing__header">
      <h1>Program</h1>
    </div>
    <?php get_template_part('festival-program-loop') ?>
  </div>
</main>

<?php wp_reset_query(); ?>
<?php get_footer(); ?>