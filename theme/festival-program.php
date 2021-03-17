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
    <div class="ssod-festival-program">
      <?php $previous_event_day = '' ?>
      <?php $first = true ?>
      <?php while (have_posts()) : the_post(); ?>
        <?php if (eo_get_the_start('d') !== $previous_event_day) : ?>
          <?php if (!$first) : ?>
            </ul>
          <?php endif ?>
          <h2 class="ssod-festival-program__day-header">
            <span class="ssod-festival-program__day"><?php echo eo_the_start('l') ?></span>
            <span class="ssod-festival-program__date"><?php echo eo_the_start('d.M') ?></span>
          </h2>
          <ul class="ssod-event-list ssod-event-list--festival">
          <?php $first = false ?>
        <?php endif ?>
        <li>
          <?php eo_get_template_part('eo-loop-single-event-festival'); ?>
        </li>
        <?php $previous_event_day = eo_get_the_start('d') ?>
      <?php endwhile; ?>
      </ul>
    </div>
  </div>
</main>

<?php wp_reset_query(); ?>
<?php get_footer(); ?>