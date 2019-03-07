function validateEmail(email) {
    var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    return re.test(String(email).toLowerCase());
}


function validateCropImage(email)
{
   
    //console.log(email.value);
    if(!validateEmail(email.value))
    {
        $("#error_modal_id").modal('show');
        return false;
    }
    else
        return true;
}


function validatePreviewImage(form)
{
    var email = form.email.value;
    if(!validateEmail(email))
    {
        $("#error_modal_id").modal('show');
        document.getElementById('error_message_id').innerHTML = "Invalid email address.";
        return false;
    }
    else
        return true;
}

