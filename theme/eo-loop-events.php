<?php if (have_posts()) : ?>

  <ul class="ssod-event-list">
    <?php while ( have_posts() ) : the_post(); ?>
      <li>
        <?php eo_get_template_part('eo-loop-single-event'); ?>
      </li>
    <?php endwhile; ?>
  </ul>

<?php else : ?>

  <article id="post-0" class="post no-results not-found">
    <header class="entry-header">
      <h1 class="entry-title"><?php _e( 'Nothing Found', 'eventorganiser' ); ?></h1>
    </header>

    <div class="entry-content">
      <p><?php _e( 'Apologies, but no results were found for the requested archive. ', 'eventorganiser' ); ?></p>
    </div>
  </article>

<?php endif;
