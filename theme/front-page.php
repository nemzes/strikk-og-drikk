<?php get_header(); ?>

<main role="main" aria-label="Content">
  <?php if (get_option('sogd-festival-enabled')) : ?>
    <section class="festival-hero">
      <div class="festival-hero__back">
        <div class="layout-clamp">
          <div class="festival-hero__heading">
            <h1><?php echo esc_html(get_option('sogd-festival-title')); ?></h1>
          </div>
        </div>
      </div>
      <div class="layout-clamp">
        <div class="festival-hero__content">
          <div class="festival-hero__blurb">
            <?php echo wp_kses_post(get_option('sogd-festival-front-blurb')); ?>
          </div>
          <div class="festival-hero__links">
            <ul>
              <?php
                $sogd_festival_posts_base_cat = get_option('sogd-festival-posts-base-cat');
                wp_list_categories(array(
                  'child_of' => $sogd_festival_posts_base_cat,
                  'hide_empty' => false,
                  'hierarchical' => false,
                  'title_li' => null,
                ));
                ?>
            </ul>
          </div>
        </div>
      </div>
      <div class="festival-hero__segue">
        <div class="layout-clamp">
          <div class="festival-hero__segue-text">
            Arrangert av…
            <?php include(get_template_directory() . '/img/arrow-down.svg') ?>
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
