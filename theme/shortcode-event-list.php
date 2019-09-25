<?php
global $eo_event_loop,$eo_event_loop_args;

//The list ID / classes
$id = ( $eo_event_loop_args['id'] ? 'id="' . $eo_event_loop_args['id'] . '"' : '' );
$classes = $eo_event_loop_args['class'];

?>

<?php if ($eo_event_loop->have_posts()) : ?>

  <ul class="ssod-event-list">
    <?php while ($eo_event_loop->have_posts()) : ?>
      <?php $eo_event_loop->the_post(); ?>
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

<?php elseif ( ! empty( $eo_event_loop_args['no_events'] ) ) :  ?>

  <ul id="<?php echo esc_attr( $id );?>" class="<?php echo esc_attr( $classes );?>" > 
    <li class="eo-no-events" > <?php echo $eo_event_loop_args['no_events']; ?> </li>
  </ul>

<?php endif;
