<?php get_header(); ?>

<?php if (have_posts()) : the_post(); ?>

  <?php
  $festival = sogd_get_event_festival(get_the_ID());
  $is_festival_post = !!$festival;
  $extra_class = $is_festival_post ? 'singular--festival' : '';
  ?>

  <main role="main" aria-label="Content" class="ssod-main singular <?php echo $extra_class ?>">
    <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

      <?php if ($is_festival_post) : ?>
        <?php sogd_output_festival_header($festival) ?>
      <?php endif; ?>

      <div class="singular__content">
        <div class="singular__header">
          <h1><?php the_title(); ?></h1>
        </div>
      </div>

      <?php eo_get_template_part('event-meta', 'event-single'); ?>

      <div class="singular__content">
        <div class="singular__header">
          <?php if (!$is_festival_post) : ?>
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
          <?php else : ?>
            <?php
            $sogd_event_fields = get_post_meta(get_the_ID(), 'sogd_event', true);
            $speaker_id = is_array($sogd_event_fields) ? $sogd_event_fields['speaker'] : -1;
            ?>
            <div class="ssod-post-meta">
              <a href="<?php echo get_the_permalink($speaker_id); ?>" class="ssod-speaker-photo ssod-speaker-photo--small">
                <?php echo get_the_post_thumbnail($speaker_id); ?>
              </a>
              <div class="meta">
                <span class="meta-title">Kurs/foredragsholder</span>
                <span class="meta-value">
                  <a href="<?php echo get_the_permalink($speaker_id); ?>">
                    <?php echo get_the_title($speaker_id); ?>
                  </a>
                </span>
              </div>
            </div>
          <?php endif ?>
        </div>
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


















<div id="primary">
  <div id="content" role="main">

    <?php while (have_posts()) : the_post(); ?>

      <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

        <header class="entry-header">

          <!-- Display event title -->
          <h1 class="entry-title"><?php the_title(); ?></h1>

        </header><!-- .entry-header -->

        <div class="entry-content">
          <!-- Get event information, see template: event-meta-event-single.php -->
          <?php eo_get_template_part('event-meta', 'event-single'); ?>

          <!-- The content or the description of the event-->
          <?php the_content(); ?>
        </div><!-- .entry-content -->

        <footer class="entry-meta">
          <?php
          //Events have their own 'event-category' taxonomy. Get list of categories this event is in.
          $categories_list = get_the_term_list(get_the_ID(), 'event-category', '', ', ', '');

          if ('' != $categories_list) {
            $utility_text = __('This event was posted in %1$s by <a href="%3$s">%2$s</a>.', 'eventorganiser');
          } else {
            $utility_text = __('This event was posted by <a href="%3$s">%2$s</a>.', 'eventorganiser');
          }
          printf(
            $utility_text,
            $categories_list,
            get_the_author(),
            esc_url(get_author_posts_url(get_the_author_meta('ID')))
          );
          ?>

          <?php edit_post_link(__('Edit'), '<span class="edit-link">', '</span>'); ?>
        </footer><!-- .entry-meta -->

      </article><!-- #post-<?php the_ID(); ?> -->

      <!-- If comments are enabled, show them -->
      <div class="comments-template">
        <?php comments_template(); ?>
      </div>

    <?php endwhile; // end of the loop. 
    ?>

  </div><!-- #content -->
</div><!-- #primary -->

<?php get_footer();
