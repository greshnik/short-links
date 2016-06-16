$(document).ready(function() {
    $('.short-button').click(function() {
        $.ajax({
            method: "POST",
            url: $(this).attr('data-url'),
            data: {url: $('.short-input').val()}
        }).done(function(respond) {
            $('#result').html(respond);
        });
    });
});