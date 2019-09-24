(function() {
  function $(id) {
    return document.getElementById(id);
  }
  document.addEventListener("DOMContentLoaded", function() {
    $("ssod_event_dates_toggler").addEventListener("click", function() {
      $("ssod_event_dates").classList.toggle("ssod-event-meta__upcoming--visible");
    });
  });
})();
