$(document).ready(function() {
    $.post(refreshUri, function(html) {
        $('#content').html(html);
    });
});
