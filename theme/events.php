<?php

$args = array(
	'post_status' => array('publish'),
  'event_start_after' => date('Y-m-d'),
  'no_found_rows'=> true,
  'post_type' => array('event'),
  'posts_per_page' => 10,
  'showpastevents' => true,
  'suppress_filters' => false,
);

$eventloop = new WP_Query($args);

if ($eventloop->have_posts()) : ?>
  <ul class="ssod-event-list">
    <?php while ($eventloop->have_posts()) : ?>
      <?php $eventloop->the_post(); ?>
      <?php if (! sogd_get_event_festival($post->ID)) : ?>
        <li>
          <article class="ssod-event">
            <time class="date">
              <span class="month"><?php eo_the_start('M'); ?></span>
              <span class="day"><?php eo_the_start('d'); ?></span>
            </time>
            <div class="overview">
              <h3>
                <a href="<?php the_permalink() ?>">
                  <?php echo esc_html(get_the_title()); ?>
                </a>
              </h3>
              <div class="excerpt">
                <?php ssod_excerpt(null, 'ssod_excerpt_more_none'); ?>
              </div>
            </div>
          </article>
        </li>
      <?php endif; ?>
    <?php endwhile; ?>
  </ul>
<?php endif;
