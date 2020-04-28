// ===========================
// Suggestions Form Validation

function suggestions(){
    var text = document.getElementById('suggestionText').value;
    var check = document.getElementById('suggestionCheck').checked;
    var sugAlert = document.getElementById('suggestionAlert');

    if(check == true && text.length>5){
        sugAlert.className = "alert alert-success";
        sugAlert.innerHTML = "Suggestion Recorded!!";
    }
    else{
        sugAlert.className = "alert alert-warning";
        if(check == false){
            sugAlert.innerHTML = "Please Check to Submit";
        }
        else{
            sugAlert.innerHTML = "Must be at least 5 Characters!!";
        }
    }
    sugAlert.style.display = 'block';
    setTimeout(function(){
        sugAlert.style.display = 'none';
    },2000);
    document.getElementById('suggestionForm').reset();
}

// =========================
// Toggle AlertBox Container
function showAlert(tempAlert,tempClass,tempText){
    tempAlert.className = tempClass;
    tempAlert.innerHTML = tempText;
    tempAlert.style.display = 'block';
    setTimeout(function(){
        tempAlert.style.display = 'none';
    },2000);
}

// Index Page AJAX requests
$(document).ready(function(){
    // Signup form Request
    $('#register-form button').click(function(e){
        e.preventDefault();
        
        $('#register-form input').removeClass('is-invalid');
        $('.invalid-feedback').remove();
        
        $.ajax({
            type : 'POST',
            url  : './includes/signup.inc.php',
            data : $('#register-form').serialize(),
            dataType : 'json',
            success : function(data){
                $('#register-form button').html('Creating Account...').attr('disabled', 'disabled');
                if ( data.status === 'error' )
                {
                    // alert('error');
                    $.each( data.errors, function(key, value){
                        if( key == 'sql' )
                        {
                            $("#register-form button").before('<div class="alert alert-danger">'+value+'</div>');
                        }
                        else
                        {
                            if( key == 'agreeTerms' )
                            {
                                $("#register-form input[name="+key+"]").addClass('is-invalid')
                                .next().after('<div class="invalid-feedback">'+value+'</div>');
                            }
                            else
                            {
                                $("#register-form input[name="+key+"]").addClass('is-invalid')
                                .after('<div class="invalid-feedback">'+value+'</div>');
                            }
                        }
                    });
                    $("#register-form input[name='signupPassword'], #register-form input[name='repeatPassword']").val('');
                }
                else
                {
                    // alert('success');
                    $("#register-form")[0].reset();
                    $("#register-form button").before('<div class="alert alert-success">You have registered successfully.</div>');
                    setTimeout(function(){location.reload();},1500);
                }
                $('#register-form button').html('Create Account').removeAttr('disabled');
            },
            error: function(){$("#register-form button").before('<div class="alert alert-danger">Something went Wrong! Try Again</div>');}
        });
    });

    // Login Form Request
    $('#login-form button').click(function(e){
        e.preventDefault();
        
        $('#login-form input').removeClass('is-invalid');
        $('.invalid-feedback').remove();
        
        $.ajax({
            type : 'POST',
            url  : './includes/login.inc.php',
            data : $('#login-form').serialize(),
            dataType : 'json',
            success : function(data){
                $('#login-form button').html('Signing in...').attr('disabled', 'disabled');
                if ( data.status === 'error' )
                {
                    // alert('error');
                    $.each( data.errors, function(key, value){
                        if( key == 'sql')
                        {
                            $("#login-form button").before('<div class="alert alert-danger">'+value+'</div>');
                        }
                        else
                        {
                            $("#login-form input[name="+key+"]").addClass('is-invalid')
                            .after('<div class="invalid-feedback">'+value+'</div>');
                        }
                    });
                    $("#login-form input[name='loginPassword']").val('');
                }
                else
                {
                    // alert('success');
                    $("#login-form")[0].reset();
                    $("#login-form button").before('<div class="alert alert-success">You have logged in successfully.</div>');
                    setTimeout(function(){location.reload();},1500);
                }
                $('#login-form button').html('Sign in').removeAttr('disabled');
            },
            error: function(){$("#login-form button").before('<div class="alert alert-danger">Something went Wrong! Try Again</div>');}
        });
    });

    // Reset Password send Email Form Request
    $('#mailResetPass-form button').click(function(e){
        e.preventDefault();
        
        $('#mailResetPass-form input').removeClass('is-invalid');
        $('.invalid-feedback').remove();
        
        $.ajax({
            type : 'POST',
            url  : './includes/reset-request-mail.inc.php',
            data : $('#mailResetPass-form').serialize(),
            dataType : 'json',
            success : function(data){
                $('#mailResetPass-form button').html('Sending Mail...').attr('disabled', 'disabled');
                if ( data.status === 'error' )
                {
                    // alert('error');
                    $.each( data.errors, function(key, value){
                        if( key == 'sql')
                        {
                            $("#mailResetPass-form button").before('<div class="alert alert-danger">'+value+'</div>');
                        }
                        else
                        {
                            $("#mailResetPass-form input[name="+key+"]").addClass('is-invalid')
                            .after('<div class="invalid-feedback">'+value+'</div>');
                        }
                    });
                    $("#mailResetPass-form input[name='resetPassEmail']").val('');
                }
                else
                {
                    // alert('success');
                    $("#mailResetPass-form")[0].reset();
                    $("#mailResetPass-form button").before('<div class="alert alert-success">Mail sent successfully.</div>');
                    setTimeout(function(){location.reload();},1500);
                }
                $('#mailResetPass-form button').html('Send mail to reset password').removeAttr('disabled');
            },
            error: function(){$("#mailResetPass-form button").before('<div class="alert alert-danger">Something went Wrong! Try Again</div>');}
        });
    });

    // Reset Password Form Request
    $('#resetPass-form button').click(function(e){
        e.preventDefault();
        
        $('#resetPass-form input').removeClass('is-invalid');
        $('.invalid-feedback').remove();
        
        $.ajax({
            type : 'POST',
            url  : './includes/reset-pass.inc.php',
            data : $('#resetPass-form').serialize(),
            dataType : 'json',
            success : function(data){
                $('#resetPass-form button').html('Resetting Password...').attr('disabled', 'disabled');
                if ( data.status === 'error' )
                {
                    // alert('error');
                    $.each( data.errors, function(key, value){
                        if( key == 'sql')
                        {
                            $("#resetPass-form button").before('<div class="alert alert-danger">'+value+'</div>');
                        }
                        else
                        {
                            $("#resetPass-form input[name="+key+"]").addClass('is-invalid')
                            .after('<div class="invalid-feedback">'+value+'</div>');
                        }
                    });
                    $("#register-form input[name='resetNewPass'], #register-form input[name='resetRepeatPass']").val('');
                }
                else
                {
                    // alert('success');
                    $("#resetPass-form")[0].reset();
                    $("#resetPass-form button").before('<div class="alert alert-success">Password reset successful. Login to continue.</div>');
                    setTimeout(function(){window.location.replace("/PHP Projects/New Project/");},1500);
                }
                $('#resetPass-form button').html('Reset Password').removeAttr('disabled');
            },
            error: function(){$("#resetPass-form button").before('<div class="alert alert-danger">Something went Wrong! Try Again</div>');}
        });
    });
});