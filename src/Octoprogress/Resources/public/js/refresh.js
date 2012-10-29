$(document).ready(function() {
    var refresh = function() {
        $.post(refreshUri, function(html) {
            $('#content').html(html);

            heighter();
        });
    }

    var heighter = function() {
        var maxHeight = 0;
        $('.project').each(function() {
            if ($(this).height() > maxHeight) {
                maxHeight = $(this).height();
            }
        });

        $('.project').height(maxHeight);
    }

    refresh();

    var timer = setInterval(refresh, 300000 );
});
