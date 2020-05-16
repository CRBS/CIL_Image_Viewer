<html>
<head>
    <title><?php if(isset($title)) echo $title; ?></title>
    <meta charset="utf-8" />
    
        <link rel="stylesheet" href="/css/bootstrap.min.css"> 
    <script src="/js/jquery.min.3.3.1.js"></script> 
    
    <script src="/js/bootstrap.min.js"></script>
    <script src="/js/popper.min.js"></script>
    
    <link rel="stylesheet" href="/css/custom.css"> 
    <link rel="icon" href="/images/favicon.ico" type="image/x-icon" />  
</head>
<body
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <img src="/images/CIL_logo_final_75H.jpg" height="50px"/><br/><br/>
            </div>
            <div class="col-md-12 cil_title">
                Viewer FAQ
            </div>
            <div class="col-md-12">
                <br/>
            </div>
        </div>    
        <div class="row">
            <div class="col-md-12">
                <div id="accordion">
                    <div class="card">
                      <div class="card-header" id="headingOne">
                        <h5 class="mb-0">
                          <button class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
                            Add description to the annotation
                          </button>
                        </h5>
                      </div>

                      <div id="collapseOne" class="collapse" aria-labelledby="headingOne" data-parent="#accordion">
                        <div class="card-body">
                            <ul>
                                <li>
                                    Click on the annotation (Polygon, Rectangle or Map Pin)
                                </li>
                                <li>
                                    Input your description in the text box
                                </li>
                                <li>
                                    Click the submit button
                                </li>
                            </ul>
                        </div>
                      </div>
                    </div>
                    <div class="card">
                      <div class="card-header" id="headingTwo">
                        <h5 class="mb-0">
                          <button class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                            Delete the annotation
                          </button>
                        </h5>
                      </div>
                      <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordion">
                        <div class="card-body">
                            <ul>
                                <li>
                                Click on the annotation (Polygon, Rectangle or Map Pin)
                                </li>
                                <li>
                                Click the Remove button
                                </li>
                            </ul>
                        </div>
                      </div>
                    </div>
                    <!------- Section --------------------->
                    <div class="card">
                      <div class="card-header" id="headingThree">
                        <h5 class="mb-0">
                          <button class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                            Share the region of interest (ROI)
                          </button>
                        </h5>
                      </div>
                      <div id="collapseThree" class="collapse" aria-labelledby="headingThree" data-parent="#accordion">
                        <div class="card-body">
                            <ul>
                                <li>
                                    Click the "Share" button at the upper right
                                </li>
                                <li>
                                   Copy the sharable URL
                                </li>
                                <li>
                                    Email the URL to your collaborators
                                </li>
                            </ul>
                        </div>
                      </div>
                    </div>
                    <!----------End Section--------------------->
                    
                    <!------- Section --------------------->
                    <div class="card">
                      <div class="card-header" id="heading4">
                        <h5 class="mb-0">
                          <button class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapse4" aria-expanded="false" aria-controls="collapse4">
                            Hide all annotations
                          </button>
                        </h5>
                      </div>
                      <div id="collapse4" class="collapse" aria-labelledby="heading4" data-parent="#accordion">
                        <div class="card-body">
                            <ul>
                                <li>
                                    Uncheck the "Annotation" checkbox at the upper right 
                                </li>
                                
                            </ul>
                        </div>
                      </div>
                    </div>
                    <!----------End Section--------------------->
                    
                    
                    <!------- Section --------------------->
                    <div class="card">
                      <div class="card-header" id="heading4">
                        <h5 class="mb-0">
                          <button class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapse5" aria-expanded="false" aria-controls="collapse5">
                           Zooming
                          </button>
                        </h5>
                      </div>
                      <div id="collapse5" class="collapse" aria-labelledby="heading5" data-parent="#accordion">
                        <div class="card-body">
                            <ul>
                                <li>
                                    Use the scroll wheel on the computer mouse
                                </li>
                                <li>
                                    Alternatively, use the "+" button at the upper left
                                </li>
                            </ul>
                        </div>
                      </div>
                    </div>
                    <!----------End Section--------------------->
                    
                    
                    <!------- Section --------------------->
                    <div class="card">
                      <div class="card-header" id="heading4">
                        <h5 class="mb-0">
                          <button class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapse6" aria-expanded="false" aria-controls="collapse6">
                           Submit bugs
                          </button>
                        </h5>
                      </div>
                      <div id="collapse6" class="collapse" aria-labelledby="heading6" data-parent="#accordion">
                        <div class="card-body">
                            <ul>
                                <li>
                                    Go to <a href="https://github.com/CRBS/CIL_Image_Viewer/issues/new" target="_blank">the bug report on Github</a>
                                </li>
                            </ul>
                        </div>
                      </div>
                    </div>
                    <!----------End Section--------------------->
                    
                </div>
            </div>
                 
                
        </div>
    </div>
</body>
</html>
