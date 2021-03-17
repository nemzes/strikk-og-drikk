<?php get_header(); ?>
<?php
$festival = get_page_by_path(get_query_var('festival'), OBJECT, 'sogd-festival');

$args = array(
  'post_type' => 'sogd-speaker',
  'suppress_filters' => false,
  'posts_per_page' => -1,
  'meta_key' => 'sogd_speaker',
  'meta_value' => $festival->ID,
  'orderby' => 'title'
);

query_posts($args);
?>

<main role="main" aria-label="Content" class="ssod-main ssod-listing ssod-listing--festival">
  <?php sogd_output_festival_header($festival) ?>
  <div class="ssod-listing__content">
    <div class="ssod-listing__header">
      <h1>Bidragsytere</h1>
    </div>
    <?php $previous_event_day = '' ?>
    <?php $first = true ?>
    <ul class="ssod-speaker-list">
      <?php while (have_posts()) : the_post(); ?>
        <li>
          <a href="<?php the_permalink() ?>">
            <div class="ssod-speaker-photo">
              <?php the_post_thumbnail(); ?>
            </div>
            <h3>
              <?php echo esc_html(get_the_title()); ?>
            </h3>
          </a>
        </li>
      <?php endwhile; ?>
    </ul>
  </div>
</main>

<?php wp_reset_query(); ?>
<?php get_footer(); ?>