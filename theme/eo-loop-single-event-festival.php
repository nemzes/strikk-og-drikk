<article class="ssod-event ssod-event--festival">
  <time class="date">
    <span class="start"><?php eo_the_start('H:i'); ?></span>
    <span class="end"><?php eo_the_end('H:i'); ?></span>
  </time>
  <div class="overview">
    <div class="ssod-event__title">
      <h3>
        <a href="<?php the_permalink() ?>">
          <?php echo esc_html(get_the_title()); ?>
        </a>
      </h3>
      <?php get_template_part('post-categories'); ?>
    </div>
    <div class="excerpt">
      <?php ssod_excerpt(null, 'ssod_excerpt_more_none'); ?>
    </div>
  </div>
</article>