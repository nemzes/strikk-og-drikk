<?php get_header(); ?>

<?php
$festival_parent_events_cats = sogd_get_all_festivals_parent_events_cats();
$paged = get_query_var('paged') ? get_query_var('paged') : 1;
$args = array(
  'tax_query' => array(
    array(
      'taxonomy' => 'event-category',
      'field'    => 'term_id',
      'terms'    => $festival_parent_events_cats,
      'operator' => 'NOT IN',
    ),
  ),
  'paged' => $paged,
  'post_type' => 'event',
);

query_posts($args);
?>

<main role="main" aria-label="Content" class="ssod-main ssod-listing">
  <div class="ssod-listing__content">

    <div class="ssod-listing__header">
      <h1>Arrangementer</h1>
    </div>

    <?php get_template_part('eo-loop-events'); ?>
    <?php get_template_part('pagination'); ?>

  </div>
</main>

<?php wp_reset_query(); ?>
<?php get_footer();
