<?php
$args = array(
  'event_start_after' => '2000-01-01',
  'numberposts' => 3,
  'post_type' => 'event',
  'showpastevents' => true,
  'suppress_filters' => false,
);

$eventloop = new WP_Query($args);

if ($eventloop->have_posts()) : ?>
  <ul class="ssod-event-list">
    <?php while ($eventloop->have_posts()) : ?>
      <?php
        $eventloop->the_post();
        //Check if all day, set format accordingly
        //$format = (eo_is_all_day() ? get_option('date_format') : get_option('date_format').' '.get_option('time_format'));
      ?>
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
    <?php endwhile; ?>
  </ul>
<?php endif;
