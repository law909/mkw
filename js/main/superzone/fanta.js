$(document).ready(function() {

    $('button').on('click', function(e) {
        e.preventDefault();
        $.ajax({
            url: '/fanta/do',
            type: 'POST',
            data: {
                ev: $('input[name="ev"]').val(),
                tol: $('input[name="tol"]').val(),
                pw: $('input[name="pw"]').val()
            },
            success: function(d) {
                var data = JSON.parse(d);
                alert(data.em);
            }
        });
    });
});