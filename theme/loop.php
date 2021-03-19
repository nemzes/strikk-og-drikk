<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

    <?php
    $show_post_meta =
      (get_post_type() !== 'sogd-speaker') &&
      (is_null(sogd_get_post_festival(get_the_ID())));
    ?>

    <div class="ssod-post-summary">
      <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
        <h2>
          <a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>">
            <span><?php the_title(); ?></span>
          </a>
        </h2>

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
        <?php elseif (get_post_type() === 'sogd-speaker') : ?>
          <div class="ssod-speaker-summary ssod-speaker-summary--list">
            <div class="ssod-speaker-photo">
              <?php the_post_thumbnail(); ?>
            </div>
          </div>
        <?php endif; ?>

        <div class="ssod-excerpt">
          <?php ssod_excerpt('ssod_excerpt_len_index'); ?>
        </div>

        <?php edit_post_link(); ?>

      </article>
    </div>

  <?php endwhile; ?>

<?php else : ?>

  <article>
    <h2><?php esc_html('Sorry, nothing to display.', 'html5blank'); ?></h2>
  </article>

<?php endif; ?>