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
        
        <div class="row">
            <div class="col-md-12">
                <ul class="nav nav-tabs">
                    <li class="nav-item">
                      <a class="nav-link active" aria-current="page" href="#home"  data-toggle="tab">Pending</a>
                    </li>
                    <li class="nav-item">
                      <a class="nav-link" href="#closed"  data-toggle="tab">Closed</a>
                    </li>

                </ul>
                
                <div id="myTabContent" class="tab-content">
                <div class="tab-pane fade show active" id="home">
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
                        <th scope="col">Start time</th>
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
                                            
                                            $closeUrl = $base_url."/Annotation_priority/close_annotation/".$item->image_id."/".$item->annotation_id."?username=".$username."&token=".$token;
                                            
                                            $deleteUrl = $base_url."/Annotation_priority/delete_annotation/".$item->image_id."/".$item->annotation_id."?username=".$username."&token=".$token;
                                        ?>
                                        <a href="<?php echo $viewUrl; ?>" target="_blank" class="btn btn-primary">View</a>  <a href="<?php echo $closeUrl; ?>" target="_self" class="btn btn-primary">Close</a> <a href="<?php echo $deleteUrl; ?>" target="_self" class="btn btn-danger"  onclick="return confirm('Are you sure to delete this annotation (<?php echo $item->annotation_id;  ?>)?')">Delete</a>
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
                <div class="tab-pane fade" id="closed">
                  <!---------Closed tab----------------->
                   <?php
        if(is_null($cpJson) || count($cpJson) == 0)
        {
            echo "\n<div class='row'>";
            echo "\n<div class='col-md-12'>";
            echo "\nYou don't have any closed annotation priority yet.";
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
                        <th scope="col">Start time</th>
                        <th scope="col">End time</th>
                        <th scope="col">Closed by</th>
                        <th scope="col">Action</th>
                      </tr>
                    </thead>
                    <tbody>
                        <?php
                            foreach($cpJson as $item)
                            {
                                if(!array_key_exists($item->annotation_id, $uniqueList))
                                {
                                    $uniqueList[$item->annotation_id] = $item->annotation_id;
                        ?>
                                <tr>
                                    <td><?php echo $item->annotation_id; ?></td>
                                    <td><?php echo $item->image_id; ?></td>
                                    <td><?php echo $item->zindex; ?></td>
                                    <td><?php echo $c_assignee_map[$item->annotation_id]; ?></td>
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
                                    <td><?php
                                        $close_time = $item->close_time;
                                        if(!is_null($close_time))
                                        {
                                            $sep = strpos($close_time, ".");
                                            if($sep === false)
                                                echo $item->close_time;
                                            else
                                            {
                                                echo substr($close_time, 0, $sep);
                                            }
                                        }
                                    ?></td>
                                    <td><?php
                                        if(!is_null($item->close_username))
                                        {
                                            echo $item->close_username;
                                        }
                                    ?></td>
                                    <td>
                                        <?php
                                            $viewUrl = $base_url."/internal_data/".$item->image_id."?username=".$username."&token=".$token."&zindex=".$item->zindex."&lat=".$item->lat."&lng=".$item->lng."&zoom=".$item->zoom;
                                            
                                            //$deleteUrl = $base_url."/Annotation_priority/delete_annotation/".$item->image_id."/".$item->annotation_id."?username=".$username."&token=".$token;
                                            $reopenUrl = $base_url."/Annotation_priority/reopen_annotation/".$item->image_id."/".$item->annotation_id."?username=".$username."&token=".$token;
                                        
                                        ?>
                                        <a href="<?php echo $viewUrl; ?>" target="_blank" class="btn btn-primary">View</a> <a href="<?php echo $reopenUrl; ?>" target="_self" class="btn btn-primary">Reopen</a>
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
                  <!---------End closed tab------------->
                </div>
                
                
              </div>
            </div>
            
        </div>
        
       
    </div>
 </body>
</html>
