<?php get_header(); ?>

<main role="main" aria-label="Content">
  <?php if (get_option('sogd-festival-enabled')) : ?>
    <?php $sogd_festival_current_cat = get_category(get_option('sogd-festival-current-cat')); ?>
    <section class="festival-hero">
      <div class="festival-hero__back">
        <div class="layout-clamp">
          <div class="festival-hero__heading">
            <h1><?php echo esc_html($sogd_festival_current_cat->name); ?></h1>
          </div>
        </div>
      </div>
      <div class="layout-clamp">
        <div class="festival-hero__content">
          <div class="festival-hero__blurb">
            <?php sogd_output_page_content(get_option('sogd-festival-current-page')) ?>
          </div>
          <div class="festival-hero__links">
            <?php sogd_output_festival_links($sogd_festival_current_cat->term_id); ?>
          </div>
        </div>
      </div>
      <div class="festival-hero__segue">
        <div class="layout-clamp">
          <div class="festival-hero__segue-text">
            <?php echo esc_html($sogd_festival_current_cat->name); ?>
            er arrangert av Stavanger Strikk og Drikk
          </div>
        </div>
      </div>
    </section>
  <?php endif; ?>

  <section class="hero">
    <div class="layout-clamp">
      <div class="hero__layout">
        <?php include(get_template_directory() . '/img/logo.svg') ?>
        <div class="hero__text">
          <h1><?php echo esc_html(get_option('sogd-front-title')); ?></h1>
          <div class="hero__blurb">
            <?php echo wp_kses_post(get_option('sogd-front-blurb')); ?>
          </div>
        </div>
      </div>
    </div>
  </section>

  <section class="latest-posts">
    <?php get_template_part('loop'); ?>
    <?php get_template_part('pagination'); ?>
  </section>
</main>

<?php get_footer();
