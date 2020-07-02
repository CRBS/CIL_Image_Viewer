<html>
<head>
        <link rel="stylesheet" href="/css/bootstrap.min.css"> 
        <script src="/js/jquery.min.3.3.1.js"></script> 

        <script src="/js/bootstrap.min.js"></script>
        <script src="/js/popper.min.js"></script>

        <link rel="stylesheet" href="/css/custom.css"> 
        <link rel="icon" href="/images/favicon.ico" type="image/x-icon" />  
</head>
<body>
<div class="container">
    <div class="row">
        <?php
            $image_count = 0;
            if(!is_null($cdeep3m_result) && isset($cdeep3m_result->Original_images))
                $image_count = count($cdeep3m_result->Original_images);
            
            $init_pos = 0;
            if($image_count >= 4)
                $init_pos = ceil ($image_count/2);
        ?>
        <div class="col-md-12">
            <img src="/images/CIL_logo_final_75H.jpg" height="50px"/>
        </div>
        <div class="col-md-12"><hr></div>
        <div class="col-md-2">Original:<input type="radio" id="original" name="original" value="original" onclick="toggle_radio_btn(this.value)"></div>
        <div class="col-md-2">Segmented:<input type="radio" id="segmented" name="segmented" value="segmented" onclick="toggle_radio_btn(this.value)"></div>
        <div class="col-md-2">Overlay:<input type="radio" id="overlay" name="overlay" value="overlay" onclick="toggle_radio_btn(this.value)"></div>
        <div id='z_label' class="col-md-1">Z:<?php echo $init_pos; ?></div>   
        <div class="col-md-5"><input autocomplete="off" id="z_index" type="range" min="0" max="<?php echo $data_size; ?>" value="<?php echo $init_pos; ?>" onchange="update_cdeep3m_image()">&nbsp;<a style="font-size:300%" id="backward_id" href="#" onclick="left_arrow()">↤</a><a style="font-size:300%" id="forward_id" href="#" onclick="right_arrow()">↦</a></div>
        <!-- <div class="col-md-2"></div> -->
        <!--<div class="col-md-12">
            <?php
                //echo $response;
            ?>
        </div> -->
        <div class="col-md-12"><br/></div>
        
        <div class="col-md-6">
            <?php 
               //var_dump($cdeep3m_result);
            
            ?>
            
            <img id="main_image" width="100%" name="main_image" src='<?php 
            
            /*if(isset($cdeep3m_result->Original_images) && count($cdeep3m_result->Original_images) > 3) 
                echo  $cdeep3m_result->Original_images[2];
            else
                echo  $cdeep3m_result->Original_images[0];*/
            
            //echo $cdeep3m_result->Original_images[$init_pos];
            echo $cdeep3m_result->Overlay_images[$init_pos];
            ?>'>
        </div>
        <div class="col-md-6">
            <div class="row">
                <?php
                    if(isset($cdeep3m_result->Tar_files))
                    {
                        
                ?>
                <div class="col-md-12"><br/></div>
                <div class="col-md-12">
                <span class='cil_title'>Download</span>
                </div>
                <?php
                        foreach($cdeep3m_result->Tar_files as $tfile)
                        {
                ?>           
                <div class="col-md-12"><a href='<?php echo $tfile; ?>' target="_self"><?php echo basename($tfile); ?></a></div>         
                <?php            
                        }   
                    }
                
                ?>
                
            </div>
            <div class="row">
                <?php
                    if(isset($cdeep3m_result->Enhanced_files))
                    {
                        
                ?>

                <div class="col-md-12">
                
                </div>
                <?php
                        foreach($cdeep3m_result->Enhanced_files as $tfile)
                        {
                ?>           
                <div class="col-md-12"><a href='<?php echo $tfile; ?>' target="_self"><?php echo basename($tfile); ?></a></div>         
                <?php            
                        }   
                    }
                
                ?>
                
            </div>
            <div class="row">
                <?php
                    if(isset($cdeep3m_result->Others_files))
                    {
                        
                ?>

                <div class="col-md-12">
                
                </div>
                <?php
                        foreach($cdeep3m_result->Others_files as $tfile)
                        {
                ?>           
                <div class="col-md-12"><a href='<?php echo $tfile; ?>' target="_self"><?php echo basename($tfile); ?></a></div>         
                <?php            
                        }   
                    }
                
                ?>
                
            </div>
            
            <div class="row"><div class="col-md-12"><br/></div></div>
            <div class="row">
                <div class="col-md-12">
                    <ul>
                     <?php
                        $original_file_location = $cropInfo->original_file_location;
                        $original_file_location = str_replace("/export2", "https://cildata.crbs.ucsd.edu", $original_file_location);
                        $image_source = $original_file_location;
                     
                     ?>
                     <li>Crop ID:<?php echo $cropInfo->id; ?></li>
                     <?php
                     if(strcmp($cropInfo->image_id, "CIL_0") != 0)
                     {
                     ?>
                     <li>Image source: <a href="http://www.cellimagelibrary.org/images/<?php echo $cropInfo->image_id; ?>" target="_blank" alt="<?php echo $cropInfo->image_id; ?>"><?php echo $cropInfo->image_id; ?></a></li>
                     <?php
                     }
                     ?>
                     
                    <?php
                    if(strcmp($original_file_location, "NA") != 0)
                    {
                    ?>
                        <li>X location: <?php echo $cropInfo->upper_left_x;  ?> pixels</li> 
                    <?php
                    }
                    ?>
                    
                    <?php
                    if(strcmp($original_file_location, "NA") != 0)
                    {
                    ?>
                     <li>Y location: <?php echo $cropInfo->upper_left_y;  ?> pixels</li>
                    <?php
                    }
                    ?>
                     
                    <?php
                    if(strcmp($original_file_location, "NA") != 0)
                    {
                    ?>
                     <li>Width: <?php echo $cropInfo->width;  ?> pixels</li>
                    <?php
                    }
                    ?>
                     
                     
                    <?php
                    if(strcmp($original_file_location, "NA") != 0)
                    {
                    ?> 
                     <li>Height: <?php echo $cropInfo->height;  ?> pixels</li>
                    <?php
                    }
                    ?> 
                     
                     <?php
                     
                        $modelID = str_replace("https://doi.org/10.7295/", "", $cropInfo->training_model_url);
                     ?>
                     <li>Training model: <?php if(isset($trained_model)) echo $trained_model->model_name;  ?> (<a href='<?php  echo $cropInfo->training_model_url;  ?>' ><?php  echo $modelID;  ?></a>) </li>
                     <li>Augspeed: <?php echo $cropInfo->augspeed;  ?> </li>
                     <li>Frame: <?php echo $cropInfo->frame;  ?> </li>
                     <li>Submit time: <?php echo $cropInfo->submit_time;  ?> </li>
                     <li>Finish time: <?php echo $cropInfo->finish_time;  ?> </li>
                     <?php
                     if(strcmp($original_file_location, "NA") != 0)
                     {
                     ?>
                     <li>Original file location: <a href="<?php echo $original_file_location; ?>" target="_blank" alt="<?php echo $original_file_location; ?>"><?php echo $original_file_location; ?></a></li>
                     <?php
                     }
                     ?>
                   </ul>
                </div>
                <div class="col-md-12">
                    <br/>
                    <a href="<?php echo $cdeep3m_website; ?>" target="_self" class="btn btn-primary">Go to CDeep3M homepage</a>
                </div>
            </div>
        </div>
        <div class="col-md-12"><?php //echo json_encode($cdeep3m_result, JSON_UNESCAPED_SLASHES); ?></div>
    </div>
    <div class="row">
        <div class="col-md-12"><br/></div>
    </div>
    
    
    <?php
                
        if(strcmp($original_file_location, "NA") != 0)
        {
    ?>
    
   
    
<div class="accordion" id="accordionExample">
  <div class="card">
    <div class="card-header" id="headingOne">
      <h5 class="mb-0">
        <button class="btn btn-link" collapsed type="button" data-toggle="collapse" data-target="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
            Launch CDeep3M using Docker <img src="/images/docker_logo.png" height="32px" />
        </button>
      </h5>
    </div>

    <div id="collapseOne" class="collapse" aria-labelledby="headingOne" data-parent="#accordionExample">
      <div class="card-body">
          <div class="row">
              <div class="col-md-12">
                    <span class='cil_title'>Launch CDeep3M using Docker</span>
                </div>
                 <div class="col-md-12">
                    <br/>
                </div>
                 <div class="col-md-12">
                     <a href="https://docs.docker.com/install/" target="_blank"><img src="/images/docker_logo.png" height="32px" /></a>
                 </div>
                 
                 <div class="col-md-12">
                    <br/>
                </div>
                 <div class="col-md-12">
                     <br/><b>Step 1) Install Docker</b><br/><a href="https://docs.docker.com/install/" target="_blank">https://docs.docker.com/install/</a>
                 </div>
                 <div class="col-md-12">
                    <br/>
                </div>
                 <div class="col-md-12">
                     <b>Step 2) Pull the CDeep3M image </b><br/>docker pull ncmir/cdeep3m
                 </div>
                 <div class="col-md-12">
                    <br/>
                </div>
                 <div class="col-md-12">
                     <b>Step 3) Launch the docker container </b><br/>docker run -it --network=host  --gpus all --entrypoint /bin/bash ncmir/cdeep3m
                 </div>
                        
                <div class="col-md-12">
                     <br/><b>Step 4) Download image:</b> <br/><span>wget <?php echo $original_file_location; ?></span>
                </div>
              
                <div class="col-md-12">
                    <br/><b>Step 5) Create the input image folder:</b> <br/> mkdir tifs
                </div>
              
              
              <div class="col-md-12">
                    <br/><b>Step 6) Convert the mrc file to TIFF images:</b> <br/> mrc2tif <?php echo basename($original_file_location); ?> tifs/image
              </div>
              
              
                <div class="col-md-12">
                    <br/><b>Step 7) Download the trained model file:</b> <br/> wget <?php echo $cropInfo->training_model_url; ?> 
                </div>
                <div class="col-md-12">
                    <br/><b>Step 8) Untar the model file:</b> <br/> tar -xvf  $MODEL_FILE
                </div>
                <div class="col-md-12">
                    <br/><b>Step 9) Run the prediction process</b><br/>runprediction.sh --augspeed <?php echo $cropInfo->augspeed;  ?> --models <?php echo $cropInfo->frame;  ?> $MODEL_FOLDER $IMAGE_FOLDER ~/predictions
                </div>
          </div>
      </div>
    </div>
  </div>
  <div class="card">
    <div class="card-header" id="headingTwo">
      <h5 class="mb-0">
        <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
            Launch CDeep3M using Singularity <img src="/images/singularity_logo.svg" height="32px" />
        </button>
      </h5>
    </div>
    <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionExample">
      <div class="card-body">
          <div class="row">
              
                <div class="col-md-12">
                    <span class='cil_title'>Launch CDeep3M using Singularity</span>
                </div>
              <div class="col-md-12">
                     <a href="https://sylabs.io/" target="_blank"><img src="/images/singularity_logo.svg" height="32px" /></a>
               </div>
              <div class="col-md-12">
                  <br/><b>Step 1) Install Singularity</b><br/><a href="https://sylabs.io/docs/" target="_blank">https://sylabs.io/docs/</a>
              </div>
              
              <div class="col-md-12">
                  <br/><b>Step 2) Download Singularity image file</b><br/>wget https://doi.org/10.7295/W9CDEEP3M_SINGULARITY
              </div>
              
              <div class="col-md-12">
                  <br/><b>Step 3) Launch the Singularity container</b><br/>singularity shell --nv cdeep3m-docker.simg
              </div>

              
              <div class="col-md-12">
                     <br/><b>Step 4) Download image:</b> <br/><span>wget <?php echo $original_file_location; ?></span>
                </div>
              
                <div class="col-md-12">
                    <br/><b>Step 5) Create the input image folder:</b> <br/> mkdir tifs
                </div>
              
              
              <div class="col-md-12">
                    <br/><b>Step 6) Convert the mrc file to TIFF images:</b> <br/> mrc2tif <?php echo basename($original_file_location); ?> tifs/image
              </div>
              
              
                <div class="col-md-12">
                    <br/><b>Step 7) Download the trained model file:</b> <br/> wget <?php echo $cropInfo->training_model_url; ?> 
                </div>
                <div class="col-md-12">
                    <br/><b>Step 8) Untar the model file:</b> <br/> tar -xvf  $MODEL_FILE
                </div>
                <div class="col-md-12">
                    <br/><b>Step 9) Run the prediction process</b><br/>runprediction.sh --augspeed <?php echo $cropInfo->augspeed;  ?> --models <?php echo $cropInfo->frame;  ?> $MODEL_FOLDER $IMAGE_FOLDER ~/predictions
                </div>
            </div>
      </div>
    </div>
  </div>
  
    
  <!--vcard-->
  <div class="card">
    <div class="card-header" id="headingThree">
      <h5 class="mb-0">
        <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
            Launch CDeep3M on the AWS cloud <span style="color:black"><b>Amazon Web Services</b></span>
        </button>
      </h5>
    </div>
    <div id="collapseThree" class="collapse" aria-labelledby="headingThree" data-parent="#accordionExample">
      <div class="card-body">
          <div class="row">
                <div class="col-md-12">
                    <span class='cil_title'>Launch CDeep3M on the AWS cloud</span>
                </div>
                <div class="col-md-12">

                    <a href="https://console.aws.amazon.com/cloudformation/home?region=us-west-2#/stacks/new?stackName=cdeep3m-stack-py3-docker&templateURL=https://cf-templates-1i8oypshb6jhq-us-west-2.s3-us-west-2.amazonaws.com/cloud_formation_cdeep3m_py3-docker.json" rel="nofollow" target="_blank"><img src="/images/launch_btn.png" alt="Launch Deep3m AWS CloudFormation link" data-canonical-src="/images/cloudformation-launch-stack.png" style="max-width:100%;"></a> 
                </div>
                <div class="col-md-12">
                    <br/><b>Step 1) Launch the docker container on AWS</b><br/>docker run -it --network=host  --gpus all --entrypoint /bin/bash ncmir/cdeep3m
                </div>
                
                
                <div class="col-md-12">
                    <br/><b>Step 2) Download the trained model file:</b> <br/> wget <?php echo $cropInfo->training_model_url; ?> 
                </div>
                <div class="col-md-12">
                    <br/><b>Step 3) Untar the model file:</b> <br/> tar -xvf  $MODEL_FILE
                </div>
              
              
              <div class="col-md-12">
                     <br/><b>Step 4) Download image:</b> <br/><span>wget <?php echo $original_file_location; ?></span>
                </div>
              
                <div class="col-md-12">
                    <br/><b>Step 5) Create the input image folder:</b> <br/> mkdir tifs
                </div>
              
              
              <div class="col-md-12">
                    <br/><b>Step 6) Convert the mrc file to TIFF images:</b> <br/> mrc2tif <?php echo basename($original_file_location); ?> tifs/image
              </div>
              
              
                <div class="col-md-12">
                    <br/><b>Step 7) Run the prediction process</b><br/>runprediction.sh --augspeed <?php echo $cropInfo->augspeed;  ?> --models <?php echo $cropInfo->frame;  ?> $MODEL_FOLDER $IMAGE_FOLDER ~/predictions
                </div>
            </div>
      </div>
    </div>
  </div>
  <!--End vcard-->
  
  <!--vcard-->
  <div class="card">
    <div class="card-header" id="headingFour">
      <h5 class="mb-0">
        <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
            Launch CDeep3M using Google Colab <span style="color:black"><b>Google</b></span>
        </button>
      </h5>
    </div>
    <div id="collapseFour" class="collapse" aria-labelledby="headingFour" data-parent="#accordionExample">
      <div class="card-body">
          <div class="row">
                <div class="col-md-12">
                    <span class='cil_title'>CDeep3M-Colab Prediction GUI</span>
                </div>
                <div class="col-md-12">
                    Run CDeep3M predictions with a graphical user interface (GUI) on Google Colab's free GPUs. 
                </div>
              <div class="col-md-12">
                  <a href="https://colab.research.google.com/github/haberlmatt/cdeep3m-colab/blob/master/CDeep3M_V2_RetrainingGUI.ipynb" rel="nofollow" target="_blank"><img src="https://camo.githubusercontent.com/52feade06f2fecbf006889a904d221e6a730c194/68747470733a2f2f636f6c61622e72657365617263682e676f6f676c652e636f6d2f6173736574732f636f6c61622d62616467652e737667" data-canonical-src="https://colab.research.google.com/assets/colab-badge.svg" style="max-width:100%;" align="middle"></a>
              </div>
              <div class="col-md-12">
                  <br/>
              </div>
              <div class="col-md-12">
                    <span class='cil_title'>CDeep3M-Colab CLI</span>
               </div>
              <div class="col-md-12">
                    If you are comfortable using a command line interface, this provides the most flexible way to use all functionality of CDeep3M while using Googles free GPUs. It performs the complete CDeep3M installation and sets you up with the CDeep3M CLI on colab.
               </div>
              <div class="col-md-12">
                  <a href="https://colab.research.google.com/github/haberlmatt/cdeep3m-colab/blob/master/CDeep3M_V2_installation_and_CLI.ipynb" rel="nofollow"><img src="https://camo.githubusercontent.com/52feade06f2fecbf006889a904d221e6a730c194/68747470733a2f2f636f6c61622e72657365617263682e676f6f676c652e636f6d2f6173736574732f636f6c61622d62616467652e737667" data-canonical-src="https://colab.research.google.com/assets/colab-badge.svg" style="max-width:100%;" align="middle"></a>
              </div>
              <div class="col-md-12">
                  <br/>
              </div>
            </div>
      </div>
    </div>
  </div>
  <!--End vcard-->
  
</div>
 <!--    
 <div class="row">
        <div class="col-md-6" style="background-color: #E8E8E8">
             <div class="row">
                <div class="col-md-12">
                    <br/>
                </div>
                <div class="col-md-12">
                    <span class='cil_title'>Launch CDeep3M using Docker</span>
                </div>
                 <div class="col-md-12">
                    <br/>
                </div>
                 <div class="col-md-12">
                     <a href="https://docs.docker.com/install/" target="_blank"><img src="/images/docker_logo.png" height="32px" /></a>
                 </div>
                 
                 <div class="col-md-12">
                    <br/>
                </div>
                 <div class="col-md-12">
                     <br/><b>Step 1) Install Docker</b><br/><a href="https://docs.docker.com/install/" target="_blank">https://docs.docker.com/install/</a>
                 </div>
                 <div class="col-md-12">
                    <br/>
                </div>
                 <div class="col-md-12">
                     <br/><b>Step 2) Pull the CDeep3M image </b><br/>docker pull ncmir/cdeep3m
                 </div>
                 <div class="col-md-12">
                    <br/>
                </div>
                 <div class="col-md-12">
                     <br/><b>Step 3) Launch the docker container </b><br/>docker run -it --network=host  --gpus all --entrypoint /bin/bash ncmir/cdeep3m
                 </div>
                 
                 <div class="col-md-12">

                     <br/><b>Step 4) Download image:</b> <br/><span>wget <?php echo $original_file_location; ?></span>

                </div>

                <div class="col-md-12">
                    <br/><b>Step 5) Create the input image folder:</b> <br/> mkdir tifs
                </div>
                <div class="col-md-12">
                    <br/><b>Step 6) Convert the mrc file to TIFF images:</b> <br/> mrc2tif <?php echo basename($original_file_location); ?> tifs/image
                </div>
                <div class="col-md-12">
                    <br/><b>Step 7) Download the trained model file:</b> <br/> wget <?php echo $cropInfo->training_model_url; ?> 
                </div>
                <div class="col-md-12">
                    <br/><b>Step 8) Untar the model file:</b> <br/> tar -xvf  $MODEL_FILE
                </div>
                <div class="col-md-12">
                    <br/><b>Step 9) Run the prediction process</b><br/>runprediction.sh --augspeed <?php echo $cropInfo->augspeed;  ?> --models <?php echo $cropInfo->frame;  ?> $MODEL_FOLDER ~/tifs/ ~/predictions
                </div>
                 <div class="col-md-12"><br/><br/></div>
             </div>
         
        </div>
        <div class="col-md-6" >
            <div class="row">
                <div class="col-md-12">
                    <br/>
                </div>
                <div class="col-md-12">
                    <span class='cil_title'>Launch CDeep3M on the AWS cloud</span>
                </div>
                <div class="col-md-12">
                    <a href="https://console.aws.amazon.com/cloudformation/home?region=us-west-2#/stacks/new?stackName=cdeep3m-stack-py3-docker&templateURL=https://cf-templates-1i8oypshb6jhq-us-west-2.s3-us-west-2.amazonaws.com/cloud_formation_cdeep3m_py3-docker.json" rel="nofollow" target="_blank"><img src="https://camo.githubusercontent.com/210bb3bfeebe0dd2b4db57ef83837273e1a51891/68747470733a2f2f73332e616d617a6f6e6177732e636f6d2f636c6f7564666f726d6174696f6e2d6578616d706c65732f636c6f7564666f726d6174696f6e2d6c61756e63682d737461636b2e706e67" alt="Launch Deep3m AWS CloudFormation link" data-canonical-src="https://s3.amazonaws.com/cloudformation-examples/cloudformation-launch-stack.png" style="max-width:100%;"></a> 
                </div>
                <div class="col-md-12">
                    <br/><b>Step 1) Launch the docker container on AWS</b><br/>docker run -it --network=host  --gpus all --entrypoint /bin/bash ncmir/cdeep3m
                </div>
                <div class="col-md-12">

                    <br/><b>Step 2) Download image:</b> <br/>wget <?php echo $original_file_location; ?>

                </div>
                <div class="col-md-12">
                    <br/><b>Step 3) Create the input image folder:</b> <br/> mkdir tifs
                </div>
                <div class="col-md-12">
                    <br/><b>Step 4) Convert the mrc file to TIFF images:</b> <br/> mrc2tif <?php echo basename($original_file_location); ?> tifs/image
                </div>
                <div class="col-md-12">
                    <br/><b>Step 5) Download the trained model file:</b> <br/> wget <?php echo $cropInfo->training_model_url; ?> 
                </div>
                <div class="col-md-12">
                    <br/><b>Step 6) Untar the model file:</b> <br/> tar -xvf  $MODEL_FILE
                </div>
                <div class="col-md-12">
                    <br/><b>Step 7) Run the prediction process</b><br/>runprediction.sh --augspeed <?php echo $cropInfo->augspeed;  ?> --models <?php echo $cropInfo->frame;  ?> $MODEL_FOLDER ~/tifs/ ~/predictions
                    <br/>
                </div>
            </div>
        </div>
    </div> 
    <div class="row">
        <div class="col-md-12"><br/></div>
    </div> 
 -->

    <?php
        }
        else 
        {
    ?>
    
    
<div class="accordion" id="accordionExample">
  <div class="card">
    <div class="card-header" id="headingOne">
      <h5 class="mb-0">
        <button class="btn btn-link" collapsed type="button" data-toggle="collapse" data-target="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
            Launch CDeep3M using Docker <img src="/images/docker_logo.png" height="32px" />
        </button>
      </h5>
    </div>

    <div id="collapseOne" class="collapse" aria-labelledby="headingOne" data-parent="#accordionExample">
      <div class="card-body">
          <div class="row">
              <div class="col-md-12">
                    <span class='cil_title'>Launch CDeep3M using Docker</span>
                </div>
                 <div class="col-md-12">
                    <br/>
                </div>
                 <div class="col-md-12">
                     <a href="https://docs.docker.com/install/" target="_blank"><img src="/images/docker_logo.png" height="32px" /></a>
                 </div>
                 
                 <div class="col-md-12">
                    <br/>
                </div>
                 <div class="col-md-12">
                     <br/><b>Step 1) Install Docker</b><br/><a href="https://docs.docker.com/install/" target="_blank">https://docs.docker.com/install/</a>
                 </div>
                 <div class="col-md-12">
                    <br/>
                </div>
                 <div class="col-md-12">
                     <b>Step 2) Pull the CDeep3M image </b><br/>docker pull ncmir/cdeep3m
                 </div>
                 <div class="col-md-12">
                    <br/>
                </div>
                 <div class="col-md-12">
                     <b>Step 3) Launch the docker container </b><br/>docker run -it --network=host  --gpus all --entrypoint /bin/bash ncmir/cdeep3m
                 </div>
                 
                <div class="col-md-12">
                    <br/><b>Step 4) Download the trained model file:</b> <br/> wget <?php echo $cropInfo->training_model_url; ?> 
                </div>
                <div class="col-md-12">
                    <br/><b>Step 5) Untar the model file:</b> <br/> tar -xvf  $MODEL_FILE
                </div>
                <div class="col-md-12">
                    <br/><b>Step 6) Run the prediction process</b><br/>runprediction.sh --augspeed <?php echo $cropInfo->augspeed;  ?> --models <?php echo $cropInfo->frame;  ?> $MODEL_FOLDER $IMAGE_FOLDER ~/predictions
                </div>
          </div>
      </div>
    </div>
  </div>
  <div class="card">
    <div class="card-header" id="headingTwo">
      <h5 class="mb-0">
        <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
            Launch CDeep3M using Singularity <img src="/images/singularity_logo.svg" height="32px" />
        </button>
      </h5>
    </div>
    <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionExample">
      <div class="card-body">
          <div class="row">
              
                <div class="col-md-12">
                    <span class='cil_title'>Launch CDeep3M using Singularity</span>
                </div>
              <div class="col-md-12">
                     <a href="https://sylabs.io/" target="_blank"><img src="/images/singularity_logo.svg" height="32px" /></a>
               </div>
              <div class="col-md-12">
                  <br/><b>Step 1) Install Singularity</b><br/><a href="https://sylabs.io/docs/" target="_blank">https://sylabs.io/docs/</a>
              </div>
              
              <div class="col-md-12">
                  <br/><b>Step 2) Download Singularity image file</b><br/>wget https://doi.org/10.7295/W9CDEEP3M_SINGULARITY
              </div>
              
              <div class="col-md-12">
                  <br/><b>Step 3) Launch the Singularity container</b><br/>singularity shell --nv cdeep3m-docker.simg
              </div>

                <div class="col-md-12">
                    <br/><b>Step 4) Download the trained model file:</b> <br/> wget <?php echo $cropInfo->training_model_url; ?> 
                </div>
                <div class="col-md-12">
                    <br/><b>Step 5) Untar the model file:</b> <br/> tar -xvf  $MODEL_FILE
                </div>
                <div class="col-md-12">
                    <br/><b>Step 6) Run the prediction process</b><br/>runprediction.sh --augspeed <?php echo $cropInfo->augspeed;  ?> --models <?php echo $cropInfo->frame;  ?> $MODEL_FOLDER $IMAGE_FOLDER ~/predictions
                </div>
            </div>
      </div>
    </div>
  </div>
  
    
  <!--- vcard ----->  
  <div class="card">
    <div class="card-header" id="headingThree">
      <h5 class="mb-0">
        <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
            Launch CDeep3M on the AWS cloud <span style="color:black"><b>Amazon Web Services</b></span>
        </button>
      </h5>
    </div>
    <div id="collapseThree" class="collapse" aria-labelledby="headingThree" data-parent="#accordionExample">
      <div class="card-body">
          <div class="row">
                <div class="col-md-12">
                    <span class='cil_title'>Launch CDeep3M on the AWS cloud</span>
                </div>
                <div class="col-md-12">

                    <a href="https://console.aws.amazon.com/cloudformation/home?region=us-west-2#/stacks/new?stackName=cdeep3m-stack-py3-docker&templateURL=https://cf-templates-1i8oypshb6jhq-us-west-2.s3-us-west-2.amazonaws.com/cloud_formation_cdeep3m_py3-docker.json" rel="nofollow" target="_blank"><img src="https://camo.githubusercontent.com/210bb3bfeebe0dd2b4db57ef83837273e1a51891/68747470733a2f2f73332e616d617a6f6e6177732e636f6d2f636c6f7564666f726d6174696f6e2d6578616d706c65732f636c6f7564666f726d6174696f6e2d6c61756e63682d737461636b2e706e67" alt="Launch Deep3m AWS CloudFormation link" data-canonical-src="https://s3.amazonaws.com/cloudformation-examples/cloudformation-launch-stack.png" style="max-width:100%;"></a> 
                </div>
                <div class="col-md-12">
                    <br/><b>Step 1) Launch the docker container on AWS</b><br/>docker run -it --network=host  --gpus all --entrypoint /bin/bash ncmir/cdeep3m
                </div>
                
                
                <div class="col-md-12">
                    <br/><b>Step 2) Download the trained model file:</b> <br/> wget <?php echo $cropInfo->training_model_url; ?> 
                </div>
                <div class="col-md-12">
                    <br/><b>Step 3) Untar the model file:</b> <br/> tar -xvf  $MODEL_FILE
                </div>
                <div class="col-md-12">
                    <br/><b>Step 4) Run the prediction process</b><br/>runprediction.sh --augspeed <?php echo $cropInfo->augspeed;  ?> --models <?php echo $cropInfo->frame;  ?> $MODEL_FOLDER $IMAGE_FOLDER ~/predictions
                </div>
            </div>
      </div>
    </div>
  </div>
  <!--End vcard -->
  
  
  <!--vcard-->
  <div class="card">
    <div class="card-header" id="headingFour">
      <h5 class="mb-0">
        <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
            Launch CDeep3M using Google Colab <span style="color:black"><b>Google</b></span>
        </button>
      </h5>
    </div>
    <div id="collapseFour" class="collapse" aria-labelledby="headingFour" data-parent="#accordionExample">
      <div class="card-body">
          <div class="row">
                <div class="col-md-12">
                    <span class='cil_title'>CDeep3M-Colab Prediction GUI</span>
                </div>
                <div class="col-md-12">
                    Run CDeep3M predictions with a graphical user interface (GUI) on Google Colab's free GPUs. 
                </div>
              <div class="col-md-12">
                  <a href="https://colab.research.google.com/github/haberlmatt/cdeep3m-colab/blob/master/CDeep3M_V2_RetrainingGUI.ipynb" rel="nofollow" target="_blank"><img src="https://camo.githubusercontent.com/52feade06f2fecbf006889a904d221e6a730c194/68747470733a2f2f636f6c61622e72657365617263682e676f6f676c652e636f6d2f6173736574732f636f6c61622d62616467652e737667" data-canonical-src="https://colab.research.google.com/assets/colab-badge.svg" style="max-width:100%;" align="middle"></a>
              </div>
              <div class="col-md-12">
                  <br/>
              </div>
              <div class="col-md-12">
                    <span class='cil_title'>CDeep3M-Colab CLI</span>
               </div>
              <div class="col-md-12">
                    If you are comfortable using a command line interface, this provides the most flexible way to use all functionality of CDeep3M while using Googles free GPUs. It performs the complete CDeep3M installation and sets you up with the CDeep3M CLI on colab.
               </div>
              <div class="col-md-12">
                  <a href="https://colab.research.google.com/github/haberlmatt/cdeep3m-colab/blob/master/CDeep3M_V2_installation_and_CLI.ipynb" rel="nofollow" target="_blank"><img src="https://camo.githubusercontent.com/52feade06f2fecbf006889a904d221e6a730c194/68747470733a2f2f636f6c61622e72657365617263682e676f6f676c652e636f6d2f6173736574732f636f6c61622d62616467652e737667" data-canonical-src="https://colab.research.google.com/assets/colab-badge.svg" style="max-width:100%;" align="middle"></a>
              </div>
              <div class="col-md-12">
                  <br/>
              </div>
            </div>
      </div>
    </div>
  </div>
  <!--End vcard-->
  
  
  
</div>
    
 <!--   
<div class="row">
        <div class="col-md-6" style="background-color: #E8E8E8">
             <div class="row">
                <div class="col-md-12">
                    <br/>
                </div>
                <div class="col-md-12">
                    <span class='cil_title'>Launch CDeep3M using Docker</span>
                </div>
                 <div class="col-md-12">
                    <br/>
                </div>
                 <div class="col-md-12">
                     <a href="https://docs.docker.com/install/" target="_blank"><img src="/images/docker_logo.png" height="32px" /></a>
                 </div>
                 
                 <div class="col-md-12">
                    <br/>
                </div>
                 <div class="col-md-12">
                     <br/><b>Step 1) Install Docker</b><br/><a href="https://docs.docker.com/install/" target="_blank">https://docs.docker.com/install/</a>
                 </div>
                 <div class="col-md-12">
                    <br/>
                </div>
                 <div class="col-md-12">
                     <br/><b>Step 2) Pull the CDeep3M image </b><br/>docker pull ncmir/cdeep3m
                 </div>
                 <div class="col-md-12">
                    <br/>
                </div>
                 <div class="col-md-12">
                     <br/><b>Step 3) Launch the docker container </b><br/>docker run -it --network=host  --gpus all --entrypoint /bin/bash ncmir/cdeep3m
                 </div>
                 
                <div class="col-md-12">
                    <br/><b>Step 4) Download the trained model file:</b> <br/> wget <?php echo $cropInfo->training_model_url; ?> 
                </div>
                <div class="col-md-12">
                    <br/><b>Step 5) Untar the model file:</b> <br/> tar -xvf  $MODEL_FILE
                </div>
                <div class="col-md-12">
                    <br/><b>Step 6) Run the prediction process</b><br/>runprediction.sh --augspeed <?php echo $cropInfo->augspeed;  ?> --models <?php echo $cropInfo->frame;  ?> $MODEL_FOLDER $IMAGE_FOLDER ~/predictions
                </div>
                 <div class="col-md-12"><br/><br/></div>
             </div>
         
        </div>
        <div class="col-md-6">
            <div class="row">
                <div class="col-md-12">
                    <br/>
                </div>
                <div class="col-md-12">
                    <span class='cil_title'>Launch CDeep3M on the AWS cloud</span>
                </div>
                <div class="col-md-12">

                    <a href="https://console.aws.amazon.com/cloudformation/home?region=us-west-2#/stacks/new?stackName=cdeep3m-stack-py3-docker&templateURL=https://cf-templates-1i8oypshb6jhq-us-west-2.s3-us-west-2.amazonaws.com/cloud_formation_cdeep3m_py3-docker.json" rel="nofollow" target="_blank"><img src="https://camo.githubusercontent.com/210bb3bfeebe0dd2b4db57ef83837273e1a51891/68747470733a2f2f73332e616d617a6f6e6177732e636f6d2f636c6f7564666f726d6174696f6e2d6578616d706c65732f636c6f7564666f726d6174696f6e2d6c61756e63682d737461636b2e706e67" alt="Launch Deep3m AWS CloudFormation link" data-canonical-src="https://s3.amazonaws.com/cloudformation-examples/cloudformation-launch-stack.png" style="max-width:100%;"></a> 
                </div>
                <div class="col-md-12">
                    <br/><b>Step 1) Launch the docker container on AWS</b><br/>docker run -it --network=host  --gpus all --entrypoint /bin/bash ncmir/cdeep3m
                </div>
                
                
                <div class="col-md-12">
                    <br/><b>Step 5) Download the trained model file:</b> <br/> wget <?php echo $cropInfo->training_model_url; ?> 
                </div>
                <div class="col-md-12">
                    <br/><b>Step 6) Untar the model file:</b> <br/> tar -xvf  $MODEL_FILE
                </div>
                <div class="col-md-12">
                    <br/><b>Step 7) Run the prediction process</b><br/>runprediction.sh --augspeed <?php echo $cropInfo->augspeed;  ?> --models <?php echo $cropInfo->frame;  ?> $MODEL_FOLDER $IMAGE_FOLDER ~/predictions
                </div>
            </div>
        </div>
    </div>    
    -->
    
    
    <?php
        }
        
    ?>
        
</div>
</body>
</html>


<script>
    /*document.getElementById('original').checked = true;
    document.getElementById('segmented').checked = false;
    document.getElementById('overlay').checked = false;*/
    
    document.getElementById('original').checked = false;
    document.getElementById('segmented').checked = false;
    document.getElementById('overlay').checked = true;
    
    var max_z = <?php echo $data_size;?>;
    var cjson = JSON.parse('<?php echo json_encode($cdeep3m_result, JSON_UNESCAPED_SLASHES);  ?>');
    function toggle_radio_btn(val)
    {
        if(val == 'original')
        {
            document.getElementById('original').checked = true;
            document.getElementById('segmented').checked = false;
            document.getElementById('overlay').checked = false;
            update_cdeep3m_image();
        }
        else if(val == 'segmented')
        {
            document.getElementById('original').checked = false;
            document.getElementById('segmented').checked = true;
            document.getElementById('overlay').checked = false;
            update_cdeep3m_image();
        }
        else if(val == 'overlay')
        {
            document.getElementById('original').checked = false;
            document.getElementById('segmented').checked = false;
            document.getElementById('overlay').checked = true;
            update_cdeep3m_image();
        }
            
    }
    
    function update_cdeep3m_image()
    {
        console.log('update_cdeep3m_image');
        
        var z_index = document.getElementById('z_index').value;
        z_index = parseInt(z_index);
        var origninal = document.getElementById('original').checked;
        var segmented = document.getElementById('segmented').checked;
        var overlay = document.getElementById('overlay').checked;
        console.log('z_index:'+z_index);
        document.getElementById('z_label').innerHTML = 'Z:'+z_index;
        if(origninal)
        {
           document.getElementById('main_image').src = cjson.Original_images[z_index];
        }
        else if(segmented)
        {
           document.getElementById('main_image').src = cjson.Result_images[z_index];
        }
        else if(overlay)
        {
           document.getElementById('main_image').src = cjson.Overlay_images[z_index];
        }
    }
    
    function right_arrow()
    {
        var z_index = document.getElementById('z_index').value;
        z_index = parseInt(z_index);
        if(z_index+1 <= max_z)
        {
            z_index = z_index+1;
            document.getElementById('z_index').value = z_index;
            document.getElementById('z_label').innerHTML = 'Z:'+z_index;
            update_cdeep3m_image();
        }
    }
    
    function left_arrow()
    {
        var z_index = document.getElementById('z_index').value;
        z_index = parseInt(z_index);
        if(z_index-1 >= 0)
        {
            z_index = z_index-1;
            document.getElementById('z_index').value = z_index;
            document.getElementById('z_label').innerHTML = 'Z:'+z_index;
            update_cdeep3m_image();
        }
    }
    
</script>


