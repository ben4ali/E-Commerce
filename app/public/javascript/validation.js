$(document).ready(function() {
    $('#form').submit(function(event) {
        var valid = true;
        var firstName = $('#firstName').val();
        if (firstName === '') {
            valid = false;
            $('#firstName').addClass('error');
        } else {
            $('#firstName').removeClass('error');
        }
        var lastName = $('#lastName').val();
        if (lastName === '') {
            valid = false
            $('#lastName').addClass('error');
        } else {
            $('#lastName').removeClass('error');
        }
        
        
        if (!valid) {
            event.preventDefault();
        }
    });
});

$(document).ready(function() {
    $('#formLogin').submit(function(event) {
        var valid = true;

        var email = $('#email').val();
        if (email === '') {
            valid = false;
            $('#email').addClass('error');
        } else {
            $('#email').removeClass('error');
        }

        var password = $('#password').val();
        if (password === '') {
            valid = false;
            $('#password').addClass('error');
        } else {
            $('#password').removeClass('error');
        }

        if (!valid) {
            event.preventDefault();
        }
    });
});
$(document).ready(function() {
    $('#formReset').submit(function(event) {
        var valid = true;
        var password = $('#password').val();
        if (password === '') {
            valid = false;
            $('#password').addClass('error');
        } else {
            $('#password').removeClass('error');
        }

        var passwordConfirmation = $('#password-confirmation').val();
        if (passwordConfirmation === '') {
            valid = false;
            $('#password-confirmation').addClass('error');
        } else {
            $('#password-confirmation').removeClass('error');
        }
        if (password !== passwordConfirmation) {
            valid = false;
            $('#password').addClass('error');
            $('#password-confirmation').addClass('error');
        }

        if (!valid) {
            event.preventDefault();
        }
    });
});

$(document).ready(function() {
    $('#formEmail').submit(function(event) {
        var valid = true;

        var email = $('#email').val();
        if (email === '' || !isValidEmail(email)) {
            valid = false;
            $('#email').addClass('error');
        } else {
            $('#email').removeClass('error');
        }

        if (!valid) {
            event.preventDefault(); 
        }
    });

    function isValidEmail(email) {
        var emailRegex = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/;
        return emailRegex.test(email);
    }
});