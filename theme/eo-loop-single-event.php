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