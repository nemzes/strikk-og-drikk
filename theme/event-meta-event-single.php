<div class="ssod-event-banner">

  <div class="ssod-layout-clamp ssod-layout-clamp--content">
    <div class="ssod-event-meta">

      <?php if (!eo_recurs()) : ?>

        <div><?php echo eo_format_event_occurrence();?></div>

      <?php else : ?>

        <div>
          <?php
            $next = eo_get_next_occurrence(eo_get_event_datetime_format());
          ?>
          <?php if ($next) : ?>
            <?php echo $next ?>
            <?php
              //Event recurs - display dates.
              $upcoming = new WP_Query(array(
                'post_type'         => 'event',
                'event_start_after' => 'today',
                'posts_per_page'    => -1,
                'event_series'      => get_the_ID(),
                'group_events_by'   => 'occurrence',
              ));
            ?>
            <?php if (sizeof($upcoming->posts) > 0) : ?>
              (<button class="ssod-button-link" id="ssod_event_dates_toggler">
              se flere datoer
              </button>)
            <?php endif ?>

          <?php else : ?>
            Event finished on
            <?php eo_get_schedule_last(eo_get_event_datetime_format()) ?>
          <?php endif ?>
        </div>

      <?php endif ?>

      <?php if (eo_get_venue()) : ?>
        <div class="ssod-event-meta__location"><?php eo_venue_name(); ?></div>
      <?php endif ?>

      <?php if (eo_recurs() && $upcoming->have_posts()) : ?>
  
        <ul id="ssod_event_dates" class="ssod-event-meta__upcoming">
          <?php
            while ( $upcoming->have_posts() ) {
              $upcoming->the_post();
              echo '<li>' . eo_format_event_occurrence() . '</li>';
            };
          ?>
        </ul>

        <?php
        wp_reset_postdata();
        ?>
      <?php endif ?>

    </div>
  </div>

  <!-- Does the event have a venue? -->
  <?php if ( eo_get_venue() && eo_venue_has_latlng( eo_get_venue() ) ) : ?>
    <!-- Display map -->
    <div class="ssod-event-map">
      <?php echo eo_get_venue_map( eo_get_venue(), array( 'width' => '100%' ) ); ?>
    </div>
  <?php endif ?>

</div>
