<?php get_header(); ?>

  <?php if (have_posts()) : the_post(); ?>

    <?php
      $is_festival = sogd_is_festival_post(get_the_ID());
      $extra_class = $is_festival ? 'singular--festival' : '';
    ?>

    <main role="main" aria-label="Content" class="singular <?php echo $extra_class ?>">
      <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

        <?php if ($is_festival) : ?>
          <?php $sogd_festival_cat = sogd_get_festival_cat(get_the_ID()); ?>
          <?php sogd_output_festival_header($sogd_festival_cat) ?>
        <?php endif; ?>

        <div class="singular__content">
          <div class="singular__header">

            <h1><?php the_title(); ?></h1>

            <p class="singular__meta">
              <?php esc_html('Categorised in: ', 'html5blank'); the_category(', '); ?>
              —
              <span class="date">
                <time datetime="<?php the_time('Y-m-d'); ?> <?php the_time('H:i'); ?>">
                  <?php the_date(); ?> <?php the_time(); ?>
                </time>
              </span>
              —
              <span class="author">
                <?php the_author(); ?>
              </span>
            </p>

          </div>

          <?php if (has_post_thumbnail()) : // Check if Thumbnail exists.?>
            <div class="singular__banner">
              <?php the_post_thumbnail(); ?>
            </div>
          <?php endif; ?>

          <div class="singular__body">
            <div class="body-text">
              <?php the_content(); ?>
            </div>
          </div>

        </div>

      </article>
    </main>

  <?php else : ?>

    <main role="main" aria-label="Content" class="singular">
      <article>
        <h1>
          <?php esc_html('Sorry, nothing to display.', 'html5blank'); ?>
        </h1>
      </article>
    </main>

  <?php endif; ?>

<?php get_sidebar(); ?>

<?php get_footer();
