$(document).ready(function() {
    $('form').on('submit', function(e) {
        e.preventDefault();

        var username = $('#username').val();
        var password = $('#password').val();

        $.ajax({
            url: '/logedin',
            type: 'POST',
            dataType: 'json',
            data: {
                username: username,
                password: password,
                _token: '{{ csrf_token() }}' // Add CSRF token for Laravel
            },
            success: function(response) {
                // Login successful, redirect or perform desired actions
                window.location.href = '/admin/index';
            },
            error: function(response) {
                // Display validation errors
                var errors = response.responseJSON.errors;
                $.each(errors, function(key, value) {
                    $('#error_' + key).text(value[0]);
                });
            }
        });
    });
});
