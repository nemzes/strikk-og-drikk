<?php get_header(); ?>

<main role="main" aria-label="Content">
  Program
  <?php
  $events = eo_get_events();
  sogd_debug($events)
  ?>
</main>

<?php get_footer(); ?>