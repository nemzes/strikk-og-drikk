<?php get_header(); ?>

<?php if (have_posts()) : the_post(); ?>

  <?php
  $id = get_the_ID();
  $post_type = get_post_type($id);
  $festival = sogd_get_post_festival($id);
  $is_festival_post = !!$festival;
  $extra_class = $is_festival_post ? 'singular--festival' : '';
  $show_post_meta =
    (!in_array($post_type, array('sogd-festival', 'sogd-speaker'))) &&
    (is_null($festival));
  ?>

  <main role="main" aria-label="Content" class="ssod-main singular <?php echo $extra_class ?>">
    <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

      <?php if ($is_festival_post) : ?>
        <?php sogd_output_festival_header($festival) ?>
      <?php endif; ?>

      <div class="singular__content">
        <div class="singular__header">
          <h1><?php the_title(); ?></h1>

          <?php if ($show_post_meta) : ?>
            <div class="ssod-post-meta">
              <span class="avatar-wrap">
                <?php echo get_avatar(get_the_author_meta('ID'), 128); ?>
              </span>
              <div class="meta">
                <span class="meta-title">Skrevet av</span>
                <span class="meta-value"><?php the_author(); ?></span>
              </div>
              <div class="meta">
                <span class="meta-title">Publisert</span>
                <time class="meta-value" datetime="<?php the_time('Y-m-d'); ?> <?php the_time('H:i'); ?>">
                  <?php the_date(); ?>
                </time>
              </div>
            </div>
          <?php endif; ?>

          <?php if ($post_type === 'sogd-speaker') : ?>
            <div class="ssod-speaker-photo">
              <?php the_post_thumbnail(); ?>
            </div>
          <?php endif; ?>

        </div>
        <div class="singular__body">
          <div class="body-text">
            <?php the_content(); ?>
          </div>

          <?php if ($post_type === 'sogd-speaker') : ?>
            <h2 class="singular__subheader">
              Arrangementer av <?php the_title(); ?>
            </h2>
            <?php
            $args = array(
              'post_type' => 'event',
              'posts_per_page' => -1,
              'showpastevents' => true,
              'meta_key' => 'sogd_speaker',
              'meta_value' => $id,
            );

            query_posts($args);
            ?>
            <?php get_template_part('festival-program-loop') ?>
          <?php endif; ?>
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

<?php get_footer();
