function validateEmail(email) {
    var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    return re.test(String(email).toLowerCase());
}


function isGoodNumber(value, name)
{
    if(isNaN(value))
    {
        $("#error_modal_id").modal('show');
        document.getElementById('error_message_id').innerHTML = name+" is not a number.";
        return false;
    }
    else
    {
       var x =  parseInt(value);
       if(x < 0)
       {
           $("#error_modal_id").modal('show');
           document.getElementById('error_message_id').innerHTML = name+" cannot be negative.";
           return false;
       }
    }
    
    return true;
}

function validateCropImage(form)
{
    var email = form.email.value;
    if(!validateEmail(email))
    {
        $("#error_modal_id").modal('show');
        return false;
    }
  
    var x_location = form.x_location.value;
    if(!isGoodNumber(x_location, 'X location'))
        return false;
    
    var y_location = form.y_location.value;
    if(!isGoodNumber(y_location, 'Y location'))
        return false;
    
    var width_in_pixel = form.width_in_pixel.value;
    if(!isGoodNumber(width_in_pixel, 'Width'))
        return false;
    
    var height_in_pixel = form.height_in_pixel.value;
    if(!isGoodNumber(height_in_pixel, 'Height'))
        return false;
    
    var starting_z_index = form.starting_z_index.value;
    if(!isGoodNumber(starting_z_index, 'Starting z index'))
        return false;
    
    var ending_z_index = form.ending_z_index.value;
    if(!isGoodNumber(ending_z_index, 'Ending z index'))
        return false;
    /*if(isNaN(x_location))
    {
        $("#error_modal_id").modal('show');
        document.getElementById('error_message_id').innerHTML = "X location is not a number.";
        return false;
    }
    else
    {
       var x =  parseInt(x_location);
       if(x < 0)
       {
           $("#error_modal_id").modal('show');
           document.getElementById('error_message_id').innerHTML = "X location cannot be negative.";
           return false;
       }
    }*/
    
    
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

function validateRunCdeep3m(form)
{
    var email = form.r_email.value;
    if(!validateEmail(email))
    {
        $("#error_modal_id").modal('show');
        document.getElementById('error_message_id').innerHTML = "Invalid email address.";
        return false;
    }
    else
        return true;
}