<?php get_header(); ?>

<?php

if (is_category()) {
  $pageTitle = esc_html(single_term_title("", false));

  $category_festival = sogd_get_category_festival(get_queried_object()->term_id);
  $extra_class = $category_festival ? 'ssod-listing--festival' : '';
} elseif (is_search()) {
  $pageTitle = 'Søk på <q>' . get_search_query(false) . '</q>';
}

?>

<main role="main" aria-label="Content" class="ssod-main ssod-listing <?php echo $extra_class ?>">

  <?php if ($category_festival) : ?>
    <?php sogd_output_festival_header($category_festival) ?>
  <?php endif; ?>

  <div class="ssod-listing__content">

    <div class="ssod-listing__header">
      <h1><?php echo $pageTitle ?></h1>
    </div>

    <?php get_template_part('loop'); ?>
    <?php get_template_part('pagination'); ?>

  </div>
</main>

<?php get_footer(); ?>