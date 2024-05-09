<?php
include('../../security.php');
include('../../includes/header.php'); 
include('../../includes/company_navbar.php'); 
?>

<div class="container-fluid">
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h5 class="m-0 font-weight-bold text-primary">Change Password</h5>
        </div>
        <div class="card-body">
            <form action="code.php" method="POST">

            <input type="hidden" name="username" pattern="{5,15}" value="<?php echo $_SESSION['USERNAME']; ?>">

                <div class="form-row">
                    <div class="form-group col-md-2">
                        <label>Old Password:</label>
                    </div>
                    <div class="form-group col-md-4">
                        <div class="input-group">
                            <input type="password" name="oldp" pattern="{,25}" class="form-control" id="oldp" required>
                            <div class="input-group-append">
                                <button class="btn btn-secondary" type="button" onclick="functionOldp()">
                                    <i class="fa fa-eye"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-2">
                        <label>New Password: </label>
                    </div>
                    <div class="form-group col-md-4">
                        <div class="input-group">
                            <input type="password" name="newp" pattern=".{5,25}"  minlength="6" class="form-control" id="newp" required>    
                            <div class="input-group-append">  
                                <button class="btn btn-secondary" type="button" onclick="functionNewp()">
                                    <i class="fa fa-eye"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-2">
                        <label>Confirm new Password:</label>
                    </div>
                    <div class="form-group col-md-4">
                        <div class="input-group">
                            <input type="password" name="confirmp" pattern=".{5,25}"  minlength="6" class="form-control" id="cp" required>
                            <div class="input-group-append">  
                                <button class="btn btn-secondary" type="button" onclick="functionCp()">
                                    <i class="fa fa-eye"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group col-md-2">
                    </div>
                    <div class="form-group col-md-3">
                        <button type="submit" name="changepass" class="btn btn-success"><i class="fa fa-check mr-2" aria-hidden="true"></i>  Save Changes</button>
                    </div>
                </div>

            </form>
        </div>
    </div>
</div>
<script>
    function functionOldp() 
    {
        var x = document.getElementById("oldp");
        if (x.type === "password") {
            x.type = "text";
        } else {
            x.type = "password";
        }
    }
    function functionNewp() 
    {
        var x = document.getElementById("newp");
        if (x.type === "password") {
            x.type = "text";
        } else {
            x.type = "password";
        }
    }
    function functionCp() 
    {
        var x = document.getElementById("cp");
        if (x.type === "password") {
            x.type = "text";
        } else {
            x.type = "password";
        }    
    }
</script>
<?php
include('../../includes/scripts.php');
include('../../includes/footer.php');
?>