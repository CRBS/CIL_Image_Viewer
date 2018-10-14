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
    <form action="/login/auth/<?php echo $image_id; ?>" method="POST">
    <div class="row">
        <div class="col-md-12">
            <img src="/images/CIL_logo_final_75H.jpg" height="50px"/>
        </div>
    </div>
    <br/>
    <div class="row">
        <div class="col-md-6">
            <div class="card border-light mb-3">
                <div class="card-header">Login</div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">User name</div>
                        <div class="col-md-8">
                            <input type="text" name="username" class="form-control">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">Password</div>
                        <div class="col-md-8">
                            <input type="password" name="password" class="form-control">
                        </div>
                    </div>
                    <br/>
                    <div class="row">
                        <div class="col-md-12 ">
                            <center><button type="submit" class="btn btn-info">Submit</button></center>
                        </div>
                    </div>
                </div>
              </div>
        </div>
        <div class="col-md-6">
        </div>
    </div>
    </form>
</div>

</body>
</html>