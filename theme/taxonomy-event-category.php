<?php get_header(); ?>

<?php

$pageTitle = single_cat_title('', false);
$category_festival = sogd_get_category_festival(get_queried_object()->term_id);
$extra_class = $category_festival ? 'ssod-listing--festival' : '';

?>

<main role="main" aria-label="Content" class="ssod-main ssod-listing <?php echo $extra_class ?>">

  <?php if ($category_festival) : ?>
    <?php sogd_output_festival_header($category_festival) ?>
  <?php endif; ?>

  <div class="ssod-listing__content">

    <div class="ssod-listing__header">
      <h1>Arrangementer: <?php echo esc_html($pageTitle) ?></h1>
    </div>

    <?php eo_get_template_part('eo-loop-events'); ?>

    <?php get_template_part('pagination'); ?>

  </div>
</main>

<?php get_footer(); ?>
