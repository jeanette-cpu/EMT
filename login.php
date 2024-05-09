<?php
include('security.php');
// session_start();
include('includes/header.php');

?>
<div class="container">
    <!-- Outer Row -->
    <div class="row justify-content-center">
      <div class="col-xl-12 col-lg-12 col-md-9">
        <div class="card o-hidden border-0 shadow-lg my-5 mt-5">
          <div class="card-body p-0 mt-0 ">
            <!-- Nested Row within Card Body -->
            <div class="row">
              <!-- <span class="border-right border border-danger"> -->
              <div class="col-lg-6 d-none d-lg-block bg-login-image"></div>
              <!-- </span> -->
              <div class="col-lg-6 border-left">
                <div class="p-5 mt-2 mb-3">
                  <div class="text-center">
                    <!-- <h1 class="h4 text-gray-900 mb-4">Welcome Back!</h1> -->  
                  </div>
                  <form class="user" action="logincode.php" method="POST">
                    <div class="form-group mt-4">
                      <p class="font-weight-normal text-danger font-weight-bold">Username:</p>
                      <input type="text" name="USERNAME" class="form-control" id="" placeholder="Enter Username" required>
                    </div>
                    <p class="font-weight-normal text-danger font-weight-bold">Password:</p>
                    <div class="form-group">
                      <div class="input-group">
                          <input type="password" name="USER_PASSWORD" class="form-control" id="p" placeholder="Password" required>
                          <div class="input-group-append">  
                                <button class="btn btn-secondary" type="button" onclick="functionP()">
                                    <i class="fa fa-eye"></i>
                                </button>
                          </div>
                      </div>
                    </div>
                    <div class="mt-4"> 
                    <div class="pt-4">             
                    <button type="submit" name="login_btn" class="btn btn-primary btn-lg btn-block">
                      LOGIN
                    </button>
                  </form>
                  <div>
                    <div class="text-center pt-3">
                      <span>Don't have account yet? </span><a href="register.php"><span class="text-danger"> Register</span></a>
                    </div>
                  </div>        
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
<script>
    function functionP() 
    {
        var x = document.getElementById("p");
        if (x.type === "password") {
            x.type = "text";
        } else {
            x.type = "password";
        }
    }
</script>
<?php
include('includes/scripts.php');
?>