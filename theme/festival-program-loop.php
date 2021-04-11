<div class="ssod-festival-program" id="sogdFestivalProgram">
  <?php $previous_event_day = '' ?>
  <?php $first = true ?>
  <?php while (have_posts()) : the_post(); ?>
    <?php
      $cats = get_the_terms($post->ID, 'event-category');
      $cats = array_map('sogd_get_category_classname', $cats);
    ?>
    <?php if (eo_get_the_start('d') !== $previous_event_day) : ?>
      <?php if (!$first) : ?>
        </ul>
      <?php endif ?>
      <h2 class="ssod-festival-program__day-header">
        <span class="ssod-festival-program__day"><?php echo eo_the_start('l') ?></span>
        <span class="ssod-festival-program__date"><?php echo eo_the_start('d.M') ?></span>
      </h2>
      <ul class="ssod-event-list ssod-event-list--festival">
        <?php $first = false ?>
      <?php endif ?>
      <li class="ssod-event-list-item <?php echo implode(" ", $cats); ?>">
        <?php eo_get_template_part('eo-loop-single-event-festival'); ?>
      </li>
      <?php $previous_event_day = eo_get_the_start('d') ?>
    <?php endwhile; ?>
      </ul>
</div>