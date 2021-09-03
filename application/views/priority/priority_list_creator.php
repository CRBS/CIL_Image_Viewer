<?php
    
    $uniqueList = array();
    
    
?>
<body>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12"><br/></div>
        </div>
        <div class="row">
            <div class="col-md-2">
                <img src="/images/CIL_logo_final_75H.jpg" height="50px"/>
            </div>
            <div class="col-md-10"></div>
        </div>
        <div class="row">
            <div class="col-md-12"><br/></div>
            <div class="col-md-12"><h4>Annotation priorities created by me (<?php echo $username; ?>)</h4></div>
            <div class="col-md-12"><hr></div>
        </div>
        
        
        
        <?php
        if(is_null($pJson) || count($pJson) == 0)
        {
            echo "\n<div class='row'>";
            echo "\n<div class='col-md-12'>";
            echo "\nYou have not created any annotation priority yet.";
            echo "\n</div>";
            echo "\n</div>";
        }
        else 
        { 
            
        ?>
        <!----------------------Annotation priorities table ------------------------------------>
        <div class="row">
            <div class="col-md-12">
                <table class="table table-striped">
                    <thead class="thead-dark">
                      <tr>
                        <th scope="col">Annotation ID</th>
                        <th scope="col">Image ID</th>
                        <th scope="col">Z Index</th>
                        <th scope="col">Assignees</th>
                        <th scope="col">Description</th>
                        <th scope="col">Priority level</th>
                        <th scope="col">Timestamp</th>
                        <th scope="col">Action</th>
                      </tr>
                    </thead>
                    <tbody>
                        <?php
                            foreach($pJson as $item)
                            {
                                if(!array_key_exists($item->annotation_id, $uniqueList))
                                {
                                    $uniqueList[$item->annotation_id] = $item->annotation_id;
                        ?>
                                <tr>
                                    <td><?php echo $item->annotation_id; ?></td>
                                    <td><?php echo $item->image_id; ?></td>
                                    <td><?php echo $item->zindex; ?></td>
                                    <td><?php echo $assignee_map[$item->annotation_id]; ?></td>
                                    <td><?php echo $item->description; ?></td>
                                    <td><?php echo $item->priority_name; ?></td>
                                    <td><?php 
                            
                            $assign_time = $item->assign_time;
                            
                            $sep = strpos($assign_time, ".");
                            if($sep === false)
                                echo $item->assign_time;
                            else
                            {
                                echo substr($assign_time, 0, $sep);
                            }
                            
                            
                            ?></td>
                                    <td>
                                        <?php
                                            $viewUrl = $base_url."/internal_data/".$item->image_id."?username=".$username."&token=".$token."&zindex=".$item->zindex."&lat=".$item->lat."&lng=".$item->lng."&zoom=".$item->zoom;
                                            
                                            $deleteUrl = $base_url."/Annotation_priority/delete_annotation/".$item->image_id."/".$item->annotation_id."?username=".$username."&token=".$token;
                                        ?>
                                        <a href="<?php echo $viewUrl; ?>" target="_blank" class="btn btn-primary">View</a>  <a href="<?php echo $deleteUrl; ?>" target="_self" class="btn btn-danger"  onclick="return confirm('Are you sure to delete this annotation (<?php echo $item->annotation_id;  ?>)?')">Delete</a>
                                    </td>
                                </tr>
                        <?php
                                }
                            }
                        ?>
                    </tbody>
                    
                </table>
            </div>
        </div>
        <!----------------------End Annotation priorities table ------------------------------------>
        <?php
        }
        
        ?>
    </div>
 </body>
</html>
