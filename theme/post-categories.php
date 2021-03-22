<?php
$cats = get_the_terms($post->ID, 'event-category');
?>
<ul class="ssod-categories">
  <?php for ($i = 0; $cat = $cats[$i]; $i++) : ?>
    <li style="background-color: <?php echo $cat->color ?>">
      <?php echo $cat->name ?>
    </li>
  <?php endfor; ?>
</ul>