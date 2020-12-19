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



<script>
    document.getElementById("add2notes_btn_id").addEventListener ("click",  add2notes_click_func, false);
    
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
       var item_str ='';
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
        document.getElementById('notes_area_row_id').innerHTML = item_str;
    }
    
    
    function deleteMessage(index)
    {
        //console.log('deleting:'+index);
        notes_json.splice(index, 1);
        
        var notes_json_str = JSON.stringify(notes_json);
        saveImageNotesJson(notes_json_str);
        
        var item_str ='';
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
        document.getElementById('notes_area_row_id').innerHTML = item_str;
        
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