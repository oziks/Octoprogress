$(document).ready(function() {
    var refresh = function() {
        $.post(refreshUri, function(html) {
            $('#content').html(html);
        });
    }

    refresh();

    var timer = setInterval(refresh, 300000);
});
