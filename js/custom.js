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
    

    
    //$('#crop_modal_id').modal('hide');
    //document.getElementById('success_email_col_id').innerHTML = '<center>An email will be sent to '+email+' when the result is ready.</center>';
    //$("#success_email_modal_id").modal('show');
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
    
    
    
    if(!form.fm1.checked && !form.fm3.checked && !form.fm5.checked)
    {
        $("#error_modal_id").modal('show');
        document.getElementById('error_message_id').innerHTML = "You have to choose one of options in Neural net.";
        return false;
    }
    
    
    var x_location = form.ct_x_location.value;
    if(!isGoodNumber(x_location, 'X location'))
        return false;
    
    var y_location = form.ct_y_location.value;
    if(!isGoodNumber(y_location, 'Y location'))
        return false;
    
    var width_in_pixel = form.ct_width_in_pixel.value;
    if(!isGoodNumber(width_in_pixel, 'Width'))
        return false;
    
    var height_in_pixel = form.ct_height_in_pixel.value;
    if(!isGoodNumber(height_in_pixel, 'Height'))
        return false;
    
    var starting_z_index = form.ct_starting_z_index.value;
    if(!isGoodNumber(starting_z_index, 'Starting Z index'))
        return false;
    
    var ending_z_index = form.ct_ending_z_index.value;
    if(!isGoodNumber(ending_z_index, 'Ending Z index'))
        return false;
    
    var center = map.getCenter();
    var zoom = map.getZoom();
    //alert("lat"+center.lat+"----lng:"+center.lng);
    form.current_lat.value = center.lat;
    form.current_lng.value = center.lng;
    form.current_zoom.value = zoom;

    //alert("lat"+form.current_lat.value+"----lng:"+form.current_lng.value);
    //return false;
    
    //$("#spin_modal_id").modal('show');
    
    document.getElementById('prp_submit').disabled = true;
    document.getElementById('after_submit').innerHTML = "<br/><center><span style='color:#608000'>Waiting.....</span></center>";
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

/*
function frame_change(option)
{
    var fm1 = document.getElementById('fm1');
    var fm3 = document.getElementById('fm3');
    var fm5 = document.getElementById('fm5');
    if(option == 'fm1')
    {
        fm1.checked = true;
        fm3.checked = false;
        fm5.checked = false;
    }
    else if(option == 'fm3')
    {
        fm1.checked = false;
        fm3.checked = true;
        fm5.checked = false;
    }
    else if(option == 'fm5')
    {
        fm1.checked = false;
        fm3.checked = false;
        fm5.checked = true;
    }

} */