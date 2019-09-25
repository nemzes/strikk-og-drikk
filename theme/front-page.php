<?php get_header(); ?>

<main role="main" aria-label="Content" class="ssod-main">
  <?php if (get_option('sogd-festival-enabled')) : ?>
    <?php $sogd_festival_id = get_option('sogd-festival-current'); ?>
    <?php $sogd_festival_current = get_post($sogd_festival_id); ?>
    <?php $sogd_festival_fields = get_post_meta($sogd_festival_id, 'sogd_festival', true); ?>
    <section class="festival-hero">
      <div class="festival-hero__back">
        <div class="ssod-layout-clamp">
          <div class="festival-hero__heading">
            <h1>
              <a href="<?php the_permalink($sogd_festival_id) ?>">
                <?php echo esc_html($sogd_festival_current->post_title); ?>
              </a>
            </h1>
          </div>
        </div>
      </div>
      <div class="ssod-layout-clamp">
        <div class="festival-hero__content">
          <div class="festival-hero__blurb">
            <?php echo wp_kses_post($sogd_festival_fields['front-blurb']) ?>
          </div>
          <div class="festival-hero__links">
            <?php sogd_output_festival_links($sogd_festival_current); ?>
          </div>
        </div>
      </div>
      <div class="festival-hero__segue">
        <div class="ssod-layout-clamp">
          <div class="festival-hero__segue-text">
            <?php echo esc_html($sogd_festival_current->post_title); ?>
            er arrangert av Stavanger Strikk og Drikk
          </div>
        </div>
      </div>
    </section>
  <?php endif; ?>

  <section class="hero">
    <div class="ssod-layout-clamp">
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

  <div class="ssod-layout-clamp">
    <div class="ssod-layout-sidebar">
      <section>
        <?php
          $festival_parent_cats = sogd_get_all_festivals_parent_cats();
          $paged = get_query_var('paged') ? get_query_var('paged') : 1;
          $args = array(
            'cat' => array_map(function ($cat) { return "-$cat"; }, $festival_parent_cats),
            'paged' => $paged,
          );

          query_posts($args);
          get_template_part('loop');
          wp_reset_query();
        ?>
        <p class="ssod-standout-text">
          <a href="/arkiv/">Vis hele arkivet</a>
        </p>
      </section>
      <section class="ssod-front-sidebar">
        <h2>Hva skjer?</h2>
        <?php get_template_part('events'); ?>

        <?php if (is_active_sidebar('ssod-widgets-front-page')) : ?>
          <?php dynamic_sidebar('ssod-widgets-front-page'); ?>
        <?php endif; ?>

      </section>
    </div
  </div>
</main>

<?php get_footer();
