(function () {
  function $(id) {
    return document.getElementById(id);
  }

  document.addEventListener("DOMContentLoaded", function () {
    document.documentElement.classList.remove("no-js");

    $("sogdHandleCategoryFilter")?.addEventListener("change", function (ev) {
      var selectedCats = [];

      $("sogdHandleCategoryFilter")
        .querySelectorAll("input")
        .forEach((input) => {
          if (input.checked) {
            selectedCats.push(input.value);
          }
        });

      $("sogdFestivalProgram")
        .querySelectorAll(".ssod-event-list-item")
        .forEach((li) => {
          var display = "none";
          selectedCats.forEach((cat) => {
            if (li.classList.contains(cat)) {
              display = "";
            }
          });

          li.style.display = display;
        });
    });

    $("ssod_event_dates_toggler")?.addEventListener("click", function () {
      $("ssod_event_dates").classList.toggle(
        "ssod-event-meta__upcoming--visible"
      );
    });
  });
})();
