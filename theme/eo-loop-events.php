<?php if (have_posts()) : ?>

  <ul class="ssod-event-list">
    <?php while ( have_posts() ) : the_post(); ?>
      <li>
        <?php eo_get_template_part('eo-loop-single-event'); ?>
      </li>
    <?php endwhile; ?>
  </ul>

<?php else : ?>

  <article class="ssod-no-results">
    <h2>Ingen arrangementer</h2>

    <p>Beklager; det er ingenting å se her</p>
  </article>

<?php endif;
