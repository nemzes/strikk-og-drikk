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
  <section class="ssod-event-list-upcoming">
    <h2>Hva skjer?</h2>
    <ul>
      <?php while ($eventloop->have_posts()) : ?>
        <?php
          $eventloop->the_post();
          //Check if all day, set format accordingly
          $format = (eo_is_all_day() ? get_option('date_format') : get_option('date_format').' '.get_option('time_format'));
        ?>
        <li class="ssod-event">
          <h3>
            <a href="<?php the_permalink() ?>">
              <?php echo esc_html(get_the_title()); ?>
            </a>
          </h3>
          <div class="date">
            <?php eo_the_start($format); ?>
          </div>
          <div class="excerpt">
            <?php ssod_excerpt(); ?>
          </div>
        </li>
      <?php endwhile; ?>
    </ul>
  </section>
<?php endif;
