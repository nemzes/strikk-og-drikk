<?php
$cats = get_the_terms($post->ID, 'event-category');
$festival_parent_events_cats = sogd_get_all_festivals_parent_events_cats();
?>
<ul class="ssod-categories">
  <?php for ($i = 0; $cat = $cats[$i]; $i++) : ?>
    <?php if (!in_array($cat->term_id, $festival_parent_events_cats)) : ?>
      <li style="background-color: <?php echo $cat->color ?>">
        <?php echo $cat->name ?>
      </li>
    <?php endif; ?>
  <?php endfor; ?>
</ul>