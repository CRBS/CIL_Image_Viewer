<div class="row">
    <div class="col-md-12">
        <!---------- Notes Model --------------------->    
        <div class="modal fade" id="notes_modal_id" role="dialog">
            <div class="modal-dialog" role="document" id="notes_dialog_modal_id" style="max-width: 70%;">
                <div class="modal-content" >
                    <div class="modal-header" style="background-color: #4582EC; color: white">
                      <h5 class="modal-title">Notes</h5>
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                      </button>
                    </div>
                <div class="modal-body" id="notes-modal-body-id">
                    <div class="row"> 
                        <div class="col-md-12">
                            <div id="notes_area_id" style="width: 100%;height: 400px;overflow: scroll;">
                                <div id="notes_area_row_id" class="row"></div>
                            </div> 
                        </div>
                        <div class="col-md-12">
                            <hr>
                        </div>
                        <div class="col-md-12"> 
                            <textarea id="notes_textarea_id" name="notes_textarea_id" rows="3" class="form-control" ></textarea>
                        
                        </div>        
                             
                        
                </div>
                </div>
            <div class="modal-footer">
                <button id="add2notes_btn_id" type="button" class="btn btn-primary">Add to Notes</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
             </div>
            </div>
        </div>
        <!----------End Search Model----------------->  
    </div>
</div>


<div class="row">
    <div class="col-md-12">
        <!----------Edit Notes Model --------------------->    
        <div class="modal fade" id="notes_edit_modal_id" role="dialog">
            <div class="modal-dialog" role="document" id="notes_edit_dialog_modal_id" style="max-width: 70%;">
                <div class="modal-content" >
                    <div class="modal-header" style="background-color: #4582EC; color: white">
                      <h5 class="modal-title">Edit Notes</h5>
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                      </button>
                    </div>
                <div class="modal-body" id="notes-edit-modal-body-id">
                    <div class="row">                       
                        <div class="col-md-12"> 
                            <input type="hidden" id="notes_edit_index_id" name="notes_edit_index_id" value="0">
                            <textarea id="notes_edit_textarea_id" name="notes_edit_textarea_id" rows="3" class="form-control" ></textarea>
                        </div>                   
                    </div>
                </div>
            <div class="modal-footer">
                <button id="submit_notes_btn_id" type="button" class="btn btn-primary" onclick="submitMessage()">Submit</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
             </div>
            </div>
        </div>
        <!----------End Edit Notes Model----------------->  
    </div>
</div>



<script>
    document.getElementById("add2notes_btn_id").addEventListener ("click",  add2notes_click_func, false);
    
    function display_notes_func()
    {
        var item_str ='';
        
        var nusername = '<?php echo $username; ?>';
        
        for(i=0;i<notes_json.length;i++)
        {
            var deleteBtn = '';
            var editBtn = '';
            if(notes_json[i].username == nusername)
            {
                deleteBtn = '<button type="button" class="close" data-dismiss="alert" onclick="deleteMessage('+i+')">×</button>';
                editBtn = '<button type="button" class="btn btn-info btn-sm" onclick="editMessage('+i+')">Edit</button>';
            }
            item_str = item_str+'<div class="col-md-12">';
            item_str = item_str+'<div class="bs-component">'+
                       '<div class="alert alert-dismissible alert-secondary">'+
                       //'<button type="button" class="close" data-dismiss="alert" onclick="deleteMessage('+i+')">×</button>'+
                       deleteBtn+
                       notes_json[i].message+'<br/>(By '+notes_json[i].full_name+" - "+notes_json[i].create_time+") "+editBtn+
                       '</div>';
                       
            item_str = item_str+'</div></div>';
            
        }
        document.getElementById('notes_area_row_id').innerHTML = item_str;
    }
    
    function submitMessage()
    {
        var index = document.getElementById('notes_edit_index_id').value;
        console.log('Edit submit index:'+index);
        var newMessage = document.getElementById('notes_edit_textarea_id').value;
        console.log(newMessage);
        notes_json[index].message = newMessage;
        
        var notes_json_str = JSON.stringify(notes_json);
        saveImageNotesJson(notes_json_str);
        
        $("#notes_edit_modal_id").modal('hide');
        display_notes_func();
        $('#notes_modal_id').modal('show');
    }
    
    function editMessage(i)
    {
        document.getElementById('notes_edit_textarea_id').value = "";
        $('#notes_modal_id').modal('hide');
        document.getElementById('notes_edit_textarea_id').value = notes_json[i].message;
        document.getElementById('notes_edit_index_id').value = i;
        $("#notes_edit_modal_id").modal('show');
    }
    
    
    function add2notes_click_func()
    {
        console.log('add2notes_click_func');
        var notes_value = document.getElementById('notes_textarea_id').value;
        document.getElementById('notes_textarea_id').value = "";
        console.log(notes_value);
        
        if(notes_value.length == 0)
            return;
        
        
        var notes_item = { "message":  notes_value, "username" : "", "full_name": "", "create_time": "" };
        
        notes_item.username = '<?php echo $username; ?>';
        notes_item.full_name = '<?php if(!is_null($user_json) && isset($user_json->full_name)) echo $user_json->full_name;   ?>';
        notes_item.create_time = getCurrentTimeString();
        
        notes_json.push(notes_item);
        console.log(notes_json);
        
        var notes_json_str = JSON.stringify(notes_json);
        saveImageNotesJson(notes_json_str);
        //document.getElementById('notes_area_id').innerHTML = JSON.stringify(notes_json);
       
        /*var item_str ='';
        for(i=0;i<notes_json.length;i++)
        {
            item_str = item_str+'<div class="col-md-12">';
            item_str = item_str+'<div class="bs-component">'+
                       '<div class="alert alert-dismissible alert-secondary">'+
                       '<button type="button" class="close" data-dismiss="alert" onclick="deleteMessage('+i+')">×</button>'+
                       notes_json[i].message+'<br/>(By '+notes_json[i].full_name+" - "+notes_json[i].create_time+")"+
                       '</div>';
                       
            item_str = item_str+'</div></div>';
            
        }
        document.getElementById('notes_area_row_id').innerHTML = item_str;*/
    
        display_notes_func();
    }
    
    
    function deleteMessage(index)
    {
        //console.log('deleting:'+index);
        notes_json.splice(index, 1);
        
        var notes_json_str = JSON.stringify(notes_json);
        saveImageNotesJson(notes_json_str);
        
        /* var item_str ='';
        for(i=0;i<notes_json.length;i++)
        {
            item_str = item_str+'<div class="col-md-12">';
            item_str = item_str+'<div class="bs-component">'+
                       '<div class="alert alert-dismissible alert-secondary">'+
                       '<button type="button" class="close" data-dismiss="alert" onclick="deleteMessage('+i+')">×</button>'+
                       notes_json[i].message+
                       '</div>';
                       
            item_str = item_str+'</div></div>';
            
        }
        document.getElementById('notes_area_row_id').innerHTML = item_str; */
        
        display_notes_func();
        
    }
    
    
    function saveImageNotesJson(notes_json_str)
    {
        var notesUrl = '<?php echo $serverName; ?>/image_annotation_service/image_notes/'+cil_id;
        console.log(notesUrl);
        $.post(notesUrl, notes_json_str, function(returnedData) {
            // do something here with the returnedData
            console.log(returnedData);
        });
        //.error(function() { //alert("error"); }
        //);
 
    }
    
</script>