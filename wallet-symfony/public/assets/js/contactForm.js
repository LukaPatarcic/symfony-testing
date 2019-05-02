$('#contact-form').submit(function (e) {
    e.preventDefault();
    var name = $.trim($('#name').val());
    var email = $.trim($('#email').val());
    var subject = $.trim($('#subject').val());
    var text = $.trim($('#message').val());
    var message = $('#displayMessage');
    $.ajax({
        url: 'email/contact',
        type: 'POST',
        data: $(this).serialize(),
        beforeSend: function () {
            message.css('display','block');
            message.text('');
            if(name === '' || email === '' || subject === '' || text === '') {
                message.addClass('alert-danger');
                message.text('Please fill in all fields');
                return false;
            }
            message.removeClass('alert-danger').addClass('alert-warning');
            message.append('<i class="fa fa-spin fa-spinner"></i> Sending message');

        },
        success: function (response) {
            message.text('');
            if(response.successMessage) {

                message.append(response.successMessage);
                message.removeClass('alert-warning alert-danger').addClass('alert-success');
                $('#contact-form')[0].reset();
                setTimeout(function (){
                    message.fadeOut();
                },2000);
                return;
            } else if (response.errorMessage) {
                message.removeClass('alert-warning alert-danger').addClass('alert-danger');
                message.append('');
                return;
            }
            message.removeClass('alert-warning alert-danger').addClass('alert-danger');
            message.append('Oops. There was a problem, please try again later!');

        },
        error: function () {
            message.removeClass('alert-warning alert-danger').addClass('alert-danger');
            message.append('Oops. There was a problem, please try again later!');
        }
    })
})