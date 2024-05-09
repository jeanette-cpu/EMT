  <?php
include('security.php');
include('includes/header.php');
?>
<style>
    /* .fa-handshake-o {
  font-size: 15em;
} */
</style>
<div class="container">
    <!-- Outer Row -->
    <div class="row justify-content-center">
      <div class="col-xl-12 col-lg-12 col-md-9">
        <div class="card o-hidden border-0 shadow-lg my-5 mt-5">
          <div class="card-body p-0 mt-0 ">
            <!-- Nested Row within Card Body -->
            <div class="row">
            <div class="col-lg-6 d-none d-lg-block bg-login-image"></div>
              <!-- <div class="col-6 d-flex justify-content-center" >
                  <div class="d-flex align-items-center">
                    <i class="fa fa-handshake-o text-dark align-center" aria-hidden="true"></i>
                  </div>
              </div> -->
              <!-- <span class="border-right border border-danger"> -->
              <!-- </span> -->
              <div class="col-lg-6 center">
                  
                <div class="p-5 mt-2 mb-3">
                  <div class="text-center">
                    <h1 class="h4 text-gray-900 mb-4 font-weight-bold">REGISTER <i class="fa fa-handshake align-center" aria-hidden="true"></i></h1>  
                  </div>
                  <form class="user" action="reg_code.php" method="POST">
                    <div class="form-group mt-4">
                      <p class="font-weight-normal text-danger font-weight-bold">Email:</p>
                      <input type="Email" name="email" maxlength="35" class="form-control" id="" placeholder="Enter Email" required>
                    </div>
                    <p class="font-weight-normal text-danger font-weight-bold">Password:</p>
                    <div class="form-group">
                      <div class="input-group">
                          <input type="password" name="password" class="form-control" id="p" placeholder="Password" minlength="6" maxlength="25" required>
                          <div class="input-group-append">  
                                <button class="btn btn-secondary" type="button" onclick="functionP()">
                                    <i class="fa fa-eye"></i>
                                </button>
                          </div>
                      </div>
                    </div>
                    <p class="font-weight-normal text-danger font-weight-bold">Confirm Password:</p>
                    <div class="form-group">
                      <div class="input-group">
                          <input type="password" name="confirmpassword" class="form-control" id="input" placeholder="Confirm Password" minlength="6" maxlength="25" required>
                          <div class="input-group-append">  
                                <button class="btn btn-secondary" type="button" onclick="functionF()">
                                    <i class="fa fa-eye"></i>
                                </button>
                          </div>
                      </div>
                    </div>
                    <div class="mt-4"> 
                    <div class="pt-4">             
                    <button type="submit" name="login_btn" class="btn btn-primary btn-lg btn-block">
                      REGISTER
                    </button>
                  </form>
                  <div>
                    <div class="text-center pt-3">
                      <span>Already have account? </span><a href="login.php"><span class="text-danger">Login</span></a>
                    </div>
                  </div>        
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- <div class="row d-none">
      <div class="col-xl-12 col-lg-12 col-md-9">
        <h1 class="text-danger display-2 my-5"> Let's grow together!</h1><br>
        <h5><span class="text-dark"> Do you want to grow together with EMT? We are looking for new partners to collaborate with.</span></h5><br>
        <h5><span class="text-danger font-weight-bold"> Become our partner </span><br></h5>
        <span class="text-dark font-weight-bold ">Already Registered? Login here</span><br>
      </div>
    </div> -->
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
    function functionF() 
    {
      var y = document.getElementById("input");
        if (y.type === "password") {
            y.type = "text";
        } else {
            y.type = "password";
        }
    }
</script>
<?php
include('includes/scripts.php');
include('includes/footer.php');
?>