<?php
include('../../security.php');
include('../../includes/header.php'); 
include('../../includes/company_navbar.php');
$username=$_SESSION['USERNAME'];
$query ="SELECT * FROM users WHERE USERNAME='$username'";
$query_run = mysqli_query($connection, $query);
$row=mysqli_fetch_assoc($query_run);
$user_id=$row['USER_ID'];
$q_company="SELECT * FROM company WHERE User_Id='$user_id' AND Comp_Status=1 and Comp_Approval=1 LIMIT 1";
// $q_company="SELECT * FROM company WHERE Comp_id='63'  LIMIT 1";
$q_company_run=mysqli_query($connection,$q_company);
$row_c=mysqli_fetch_assoc($q_company_run); 
if($q_company_run){
    $comp_id=$row_c['Comp_Id'];
    $comp_name=$row_c['Comp_Name'];
    $type=$row_c['Comp_Type']; //
    $scopeAuth=$row_c['Comp_Scope_Auth'];//
    $website=$row_c['Company_Website'];
    $CP_name=$row_c['Comp_Contact_Person'];
    $CP_position=$row_c['Comp_Contact_Position'];
    $CP_mobile=$row_c['Comp_Contact_Mobile'];
    $CP_landline=$row_c['Comp_Contact_Landline'];
    $CP_email=$row_c['Comp_Contact_Email'];
    $TRN=$row_c['Comp_TRN'];
    $x_date=$row_c['Comp_Reg_End_Date'];
    $emirateTL=$row_c['Comp_Emirate_TRL'];
    $mg_name=$row_c['Comp_Manager_Name'];
    $mg_mobile=$row_c['Comp_Manager_Mobile'];
    $mg_landline=$row_c['Comp_Manager_Landline'];
    $mg_email=$row_c['Comp_Manager_Email'];
    $sig1=$row_c['Comp_Sig_Name1'];
    $sig2=$row_c['Comp_Sig_Name2'];
    $sig3=$row_c['Comp_Sig_Name3'];
    // $comma_count =substr_count($scopeAuth,",");
    //separate each string
    $separated_array = (explode(",",$scopeAuth));
    $count = count($separated_array);  $i=0; 
    while($i<$count)
    {
        $scope= $separated_array[$i];
        $scope=trim($scope);
        if($scope=='All'){
            ?> <script>
                $(document).ready(function() {
                    $("input[type=checkbox][value='All']").attr("checked","true");
                });
            </script> <?php
        }
        if($scope=='Abu Dhabi'){
            ?> <script>
                $(document).ready(function() {
                    $("input[type=checkbox][value='Abu Dhabi']").attr("checked","true");
                });
            </script> <?php
        }
        if($scope=='Ajman'){
            ?> <script>
                $(document).ready(function() {
                    $("input[type=checkbox][value='Ajman']").attr("checked","true");
                });
            </script> <?php
        }
        if($scope=='Dubai'){
            ?> <script>
                $(document).ready(function() {
                    $("input[type=checkbox][value='Dubai']").attr("checked","true");
                });
            </script> <?php
        }
        if($scope=='Fujairah'){
            ?> <script>
                $(document).ready(function() {
                    $("input[type=checkbox][value='Fujairah']").attr("checked","true");
                });
            </script> <?php
        }
        if($scope=='Ras al Khaimah'){
            ?> <script>
                $(document).ready(function() {
                    $("input[type=checkbox][value='Ras al Khaimah']").attr("checked","true");
                });
            </script> <?php
        }
        if($scope=='Sharjah'){
            ?><script>
                $(document).ready(function() {
                    $("input[type=checkbox][value='Sharjah']").attr("checked","true");
                });</script>
            <?php
        }
        if($scope=='Umm Al Quwain'){
            ?> <script>
                $(document).ready(function() {
                    $("input[type=checkbox][value='Umm Al Quwain']").attr("checked","true");
                });
            </script> <?php
        }
        $i++;
    }
}
else{
    echo 'Error Loading Company Details';
}
?>
<input type="hidden" id="ctype" value="<?php echo $type?>">
<input type="hidden" id="emirate" value="<?php echo $emirateTL?>">

 <script>
    $(document).ready(function() {
        $(function() {
            var $radios = $('input:radio[name=bType]');
            var $ctype=$('#ctype').val();
            if($radios.is(':checked') === false) {
                $radios.filter('[value='+$ctype+']').prop('checked', true);
            }
            var $radio = $('input:radio[name=emirateInTL]');
            var $em=$('#emirate').val();
            if($radio.is(':checked') === false) {
                $radio.filter('[value='+$em+']').prop('checked', true);
            }
            // // var date=< ?php echo $x_date?>;
            // $('#x_date').datepicker().val(new Date(2022,28,10)).trigger('change');
        });
        
    });
</script>
<div class="container-fluid">
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h5 class="m-0 font-weight-bold text-primary">Edit Company Profile</h5>
        </div>
        <div class="card-body">
            <form action="code.php" method="post" enctype="multipart/form-data">
                <input type="hidden" name="comp_id" value="<?php echo $comp_id;?>" minlength="4" maxlength="40" required>
                <div class="form-row">
                    <div class="col-5">
                        <label for="">Company Name</label>
                        <input type="text" name="comp_name" class="form-control" value="<?php echo $comp_name?>" required>
                    </div>
                    <div class="col-4">
                        <label for="">TRN:</label>
                        <input type="text" name="Comp_TRN" class="form-control" value="<?php echo $TRN;?>" >
                    </div>
                    <div class="col-3">
                        <label for="">Website:</label>
                        <input type="text" name="Company_Website" class="form-control" value="<?php echo $website;?>">
                    </div>
                </div>
                <div class="form-row mt-2">
                    <div class="col-4">
                        <label for="">Type of Business: *</label>
                        <div class="form-group">
                            <label class="radio mr-3 ml-4">
                                <input type="radio" name="bType" value="oem" class="mr-1 check-fields1" required>Manufacturer/OEM (Original Equipment Manufacturer)
                            </label><br>
                            <label class="radio mr-3 ml-4">
                                <input type="radio" name="bType" value="distributor" class="mr-1 check-fields1" required>Authorized Distributor
                            </label><br>
                            <label class="radio mr-3 ml-4">
                                <input type="radio" name="bType" value="trading" class="mr-1 check-fields1" required>Trading
                            </label><br>
                            <label class="radio mr-3 ml-4">
                                <input type="radio" name="bType" value="subcon" class="mr-1 check-fields1" required>Subcontractor
                            </label>  <br> 
                            <label class="radio mr-3 ml-4">
                                <input type="radio" name="bType" value="laborSupply" class="mr-1 check-fields1" required>Labor Supply
                            </label>  
                        </div>
                    </div>
                    <div class="col-4">
                        <label for="">Scope of Business Authorized: *</label>
                        <div id="my" class="form-row form-group checkbox-group1 ml-4 check-fields1" required>
                            <div class="col-6">
                                <input name ="scopeB[]" id="all" class="sba" type="checkbox" value="All">
                                <label >All over UAE</label><br>
                                <input name ="scopeB[]" class="sba" type="checkbox" value="Abu Dhabi" >
                                <label>Abu Dhabi</label><br>
                                <input name ="scopeB[]" class="sba" type="checkbox" value="Ajman">
                                <label>Ajman</label><br>
                                <input  name ="scopeB[]" class="sba" type="checkbox" value="Dubai">
                                <label>Dubai</label><br>
                            </div>
                            <div class="col-6">
                                <input  name ="scopeB[]" class="sba" type="checkbox" value="Fujairah">
                                <label>Fujairah</label><br>
                                <input  name ="scopeB[]" class="sba" type="checkbox" value="Ras al Khaimah">
                                <label>Ras al Khaimah</label><br>
                                <input name ="scopeB[]" class="sba" type="checkbox" value="Sharjah">
                                <label>Sharjah</label><br>
                                <input name ="scopeB[]" class="sba" type="checkbox" value="Umm Al Quwain">
                                <label>Umm Al Quwain</label>
                            </div>
                        </div>
                    </div>
                    <div class="col-4">
                        <label for="">Emirate in Trade License: *</label>
                        <div class="form-row form-group checkbox-group1 ml-4 check-fields5" required>
                            <div class="col-6">
                                <input name="emirateInTL" class="check-fields5" type="radio" value="Abu Dhabi"> 
                                <label>Abu Dhabi</label><br>
                                <input  name="emirateInTL" class="check-fields5" type="radio" value="Ajman">
                                <label>Ajman</label><br>
                                <input name="emirateInTL" class="check-fields5" type="radio" value="Dubai">
                                <label>Dubai</label><br>
                                <input  name="emirateInTL" class="check-fields5" type="radio" value="Fujairah">
                                <label>Fujairah</label><br>
                            </div>
                            <div class="col-6">
                                <input  name="emirateInTL" class="check-fields5" type="radio" value="Ras al Khaimah">
                                <label>Ras al Khaimah</label><br>
                                <input name="emirateInTL" class="check-fields5" type="radio" value="Sharjah">
                                <label>Sharjah</label><br>
                                <input name="emirateInTL" class="check-fields5" type="radio" value="Umm Al Quwain">
                                <label>Umm Al Quwain</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-row">
                    <div class="col-6 pl-2 pr-5">
                        <div class="form-group">
                            <label for="">Contact Person Name: *</label>
                            <input type="text" value="<?php echo $CP_name;?>" minlength="5" maxlength="30" name="cName" class="form-control check-fields2" required>
                        </div>
                        <div class="form-group">
                            <label for="">Position: *</label>
                            <input type="text" value="<?php echo $CP_position;?>" name="cPos" minlength="5" maxlength="30" class="form-control check-fields2" required>
                        </div>
                        <div class="form-group">
                            <label for="">Contact Mobile: *</label>
                            <input type="number" value="<?php echo $CP_mobile;?>" name="cMobile" minlength="10" maxlength="10" class="form-control check-fields2" required>
                        </div>
                        <div class="form-group">
                            <label for="">Contact Landline: *</label>
                            <input type="number" value="<?php echo $CP_landline;?>" name="cLand" minlength="5" maxlength="10" class="form-control check-fields2" required>
                        </div>
                        <div class="form-group ">
                            <label for="">Email: *</label>
                            <input type="email" value="<?php echo $CP_email;?>" name="cMail"  minlength="5" maxlength="25"  class="form-control check-fields2" required>
                        </div>
                    </div>
                    <div class="col-6 pr-2 pl-2">
                        <div class="form-group">
                            <label for="">Manager's Name: *</label>
                            <input type="text" value="<?php echo $mg_name;?>" name="mName" minlength="6" maxlength="30" class="form-control check-fields2" required>
                        </div>
                        <div class="form-group">
                            <label for="">Contact Mobile: *</label>
                            <input type="number" value="<?php echo $mg_mobile;?>" name="mMobile" minlength="10" maxlength="10" class="form-control check-fields2" required>
                        </div>
                        <div class="form-group">
                            <label for="">Contact Landline: *</label>
                            <input type="number" value="<?php echo $mg_landline;?>" name="mLand" minlength="5" maxlength="10" class="form-control check-fields2" required>
                        </div>
                        <div class="form-group ">
                            <label for="">Email: *</label>
                            <input type="email" value="<?php echo $mg_email;?>" name="mMail" minlength="5" maxlength="25"  class="form-control check-fields2" required>
                        </div>
                    </div>
                </div>
                <div class="form-row">
                    <div class="col-5 pl-2 pr-5">
                        <label for="" class="font-weight-bold">File Uploads</label>
                        <div class="form-group">
                            <label for="formFile">Company Profile: *</label>
                            <input type="file" id="formFile" size="50" name="profile" class="form-control"> 
                        </div>
                    </div>
                    <div class="col-5 pl-2 pr-5">
                        <div class="form-group">
                            <br>
                            <label for="formFile">Company Stamp: *</label>
                            <input type="file" id="" size="50" name="compStamp" class="form-control"> 
                        </div>
                    </div>
                </div>
                <div class="form-row">
                    <div class="col-5 pl-2 pr-5">
                        <div class="form-group">
                            <label for="formFile">TRN File: *</label>
                            <input type="file" id="formFile" size="50" name="file" class="form-control"> 
                        </div>
                    </div>
                    <div class="col-5 pl-2 pr-5">
                        <div class="form-group">
                            <label for="">License Expire Date: *</label>
                            <input type="date" id="x_date" name="x_date" value="<?php echo $x_date;?>" class="form-control"> 
                        </div>
                    </div>
                </div>
                <div class="form-row mt-1">
                    <div class="col-6">
                        <label for="" class="font-weight-bold">Upload Company Authorized Signatories</label><span class="font-italic"> (.png/.jpeg/.jpg)</span><br>
                    </div>
                </div>
                <div class="form-row">
                    <div class="col-2">
                        <label for="" class="mt-1">Name of Signatory 1*</label>
                    </div>
                    <div class="col-4">
                        <input type="text" name="s1_name" class="form-control form-control-sm" value="<?php echo $sig1;?>" required>
                    </div>
                </div>
                <div class="form-row mt-2">
                    <div class="col-4">
                        <input type="file" name="s1_1" id="s1_1" class="imgFile" >
                    </div>
                    <div class="col-4">
                        <input type="file" name="s1_2" id="s1_2" class="imgFile" >
                    </div>
                    <div class="col-4">
                        <input type="file" name="s1_3" id="s1_3" class="imgFile" >
                    </div>
                </div>
                <div class="form-row mt-3">
                    <div class="col-2">
                        <label for="" class="mt-1">Name of Signatory 2*</label>
                    </div>
                    <div class="col-4">
                        <input type="text" name="s2_name" class="form-control form-control-sm" value="<?php echo $sig2;?>" required>
                    </div>
                </div>
                <div class="form-row mt-2">
                    <div class="col-4">
                        <input type="file" name="s2_1" id="s2_1" class="imgFile">
                    </div>
                    <div class="col-4">
                        <input type="file" name="s2_2" id="s2_2" class="imgFile">
                    </div>
                    <div class="col-4">
                        <input type="file" name="s2_3" id="s2_3" class="imgFile">
                    </div>
                </div>
                <div class="form-row mt-3">
                    <div class="col-2">
                        <label for="" class="mt-1">Name of Signatory 3*</label>
                    </div>
                    <div class="col-4">
                        <input type="text" name="s3_name" class="form-control form-control-sm" value="<?php echo $sig3;?>" required>
                    </div>
                </div>
                <div class="form-row mt-2">
                    <div class="col-4">
                        <input type="file" name="s3_1" id="s3_1" class="imgFile">
                    </div>
                    <div class="col-4">
                        <input type="file" name="s3_2" id="s3_2" class="imgFile">
                    </div>
                    <div class="col-4">
                        <input type="file" name="s3_3" id="s3_3" class="imgFile">
                    </div>
                </div>
                <button type="submit" name="update" class="btn btn-success mt-2">Save<i class="fa fa-file-text ml-2" aria-hidden="true"></i></button>
            </form>
        </div>
    </div>
<div>
<script>
    $(document).ready(function () {
        $('#all').on('click', function() {    
            $(document).find(".sba").prop("checked", false);
            $(document).find("#all").prop("checked", true);
        });
    });
$(document).ready(function () {
    $(document).on('change', '.imgFile', function(){
        var id = $(this).attr('id');
        var fileInput = document.getElementById(id);
        var filePath = fileInput.value;
        // Allowing file type
        var allowedExtensions =
            /(\.jpg|\.jpeg|\.png)$/i;
        if (!allowedExtensions.exec(filePath)) {
            alert('Invalid file type. Please choose .png or .jpeg file');
            fileInput.value = '';
            return false;
        }
    });
});
</script>
<?php
include('../../includes/scripts.php');
include('../../includes/footer.php');
?>