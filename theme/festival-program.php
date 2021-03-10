<?php get_header(); ?>
<?php
$festival = get_page_by_path(get_query_var('festival'), OBJECT, 'sogd-festival');
$event_cat = sogd_get_festival_parent_event_cat($festival);

$events = eo_get_events(array(
  'event-category' => $festival
));

$args = array(
  'cat' => array_map(function ($cat) {
    return "-$cat";
  }, $festival_parent_cats),
  'paged' => $paged,
);

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
    <?php $previous_event_day = '' ?>
    <?php $first = true ?>
    <?php while (have_posts()) : the_post(); ?>
      <?php if (eo_get_the_start('d') !== $previous_event_day) : ?>
        <?php if (!$first) : ?>
          </ul>
        <?php endif ?>
        <h2><?php echo eo_the_start('l') ?></h2>
        <ul class="ssod-event-list">
        <?php $first = false ?>
      <?php endif ?>
      <li>
        <?php eo_get_template_part('eo-loop-single-event-festival'); ?>
      </li>
      <?php $previous_event_day = eo_get_the_start('d') ?>
    <?php endwhile; ?>
    </ul>
  </div>
</main>

<?php wp_reset_query(); ?>
<?php get_footer(); ?>