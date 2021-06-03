<?php
    
    $uniqueList = array();
    
    
?>
<body>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-2">
                <img src="/images/CIL_logo_final_75H.jpg" height="50px"/>
            </div>
            <div class="col-md-10"></div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <?php //var_dump($pJson); ?>
                <br/>
            </div>
        </div>
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
                                    <td><?php echo ""; ?></td>
                                    <td><?php echo $item->description; ?></td>
                                    <td><?php echo $item->priority_name; ?></td>
                                    <td><?php echo $item->assign_time; ?></td>
                                    <td>
                                        <?php
                                            $viewUrl = $base_url."/internal_data/".$item->image_id."?username=".$username."&token=".$token."&zindex=".$item->zindex."&lat=".$item->lat."&lng=".$item->lng."&zoom=".$item->zoom;
                                        ?>
                                        <a href="<?php echo $viewUrl; ?>" target="_blank">View</a>
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
        
    </div>
 </body>
</html>
