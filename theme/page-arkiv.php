<?php get_header(); ?>

<?php
  $festival_parent_cats = sogd_get_all_festivals_parent_cats();
  $paged = get_query_var('paged') ? get_query_var('paged') : 1;
  $args = array(
    'cat' => array_map(function ($cat) { return "-$cat"; }, $festival_parent_cats),
    'paged' => $paged,
  );

  query_posts($args);
?>

<main role="main" aria-label="Content" class="ssod-main ssod-listing">
  <div class="ssod-listing__content">
    
    <div class="ssod-listing__header">
      <h1>Arkiv</h1>
    </div>

    <?php get_template_part('loop'); ?>
    <?php get_template_part('pagination'); ?>

  </div>
</main>

<?php wp_reset_query(); ?>
<?php get_footer();
