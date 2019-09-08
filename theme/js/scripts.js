(function( root, $, undefined ) {
  'use strict';

  $(function () {
    $('#ssod_event_dates_toggler').on('click', function(ev) {
      $('#ssod_event_dates').toggle();
    });
  });

} ( this, jQuery ));