<?php if ($wp_query->max_num_pages > 1) : ?>
  <div class="ssod-pagination">
    <div class="ssod-standout-text">
      <?php sogd_pagination(); ?>
    </div>
  </div>
<?php endif;
