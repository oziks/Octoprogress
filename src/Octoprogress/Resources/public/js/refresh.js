$(document).ready(function() {
    $.get(refreshUri, function() {
         setTimeout(function () {
            location.reload();
          }, 60 * 3 * 1000);
    });
});
