function validateUsername() {
    'use strict';
    var $username = $(this).val();
    var divError = $('#username-validate-status');
    var posting = $.post('/admin/users/validate/username', { username: $username});
    posting.done(function(data) {
        if (data.payload.isValid === true) {
          divError.html('<ul class="list-unstyled success"><li>'+data.message+'</li></ul>');
        } else {
          divError.html('<ul class="list-unstyled danger"><li>'+data.message+'</li></ul>');
        }
    });
    
    posting.fail(function() {
        divError.html('<ul class="list-unstyled danger"><li>Este serviço parou de funcionar</li></ul>');
    });
    setTimeout(function(){ divError.find('ul').fadeOut(); }, 6000);
}

function validateUserEmailAddress() {
    'use strict';
    var $email = $(this).val();
    var divError = $('#user-email-validate-status');
    var posting = $.post('/admin/users/validate/user-email', { email: $email});    
    posting.done(function(data) {
        if (data.payload.isValid === true) {
          divError.html('<ul class="list-unstyled success"><li class="text-success">'+data.message+'</li></ul>');
        } else {
          divError.html('<ul class="list-unstyled danger"><li class="text-danger">'+data.message+'</li></ul>');
        }
    });
    posting.fail(function() {
        divError.html('<ul class="list-unstyled danger"><li class="text-danger">Este serviço parou de funcionar</li></ul>');
    });
    setTimeout(function(){ divError.find('ul').fadeOut(); }, 6000);
}

$(document).on('keyup keypress blur change', '#username', validateUsername);
$(document).on('keyup keypress blur change', '#email', validateUserEmailAddress);
