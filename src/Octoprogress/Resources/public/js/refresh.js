$(document).ready(function() {
    $.get(refreshUri, function() {

         setTimeout(function () {
            location.reload();
          }, 5000);

    });
});
