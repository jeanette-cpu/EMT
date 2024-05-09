<?php
include('../security.php');
include('../includes/header.php'); 
include('../includes/acc_navbar.php'); 
$query = "SELECT * FROM bank WHERE Bank_Status='1'";
$query_run = mysqli_query($connection, $query);
?>
<style>
.pdf,
.modal-content {
    /* 80% of window height */
    height: 95%;
}
.modal-body {
    /* 100% = dialog height, 120px = header + footer */
    max-height: calc(100% - 120px);
    overflow-y: scroll;
}
.popover {
  z-index: 1070;
}
</style>
<div class="container-fluid">
    <div class="card shadow mb-4">
        <div class="card-header py-3">
                <h5 class="m-0 font-weight-bold text-primary"><i class="fa fa-university" aria-hidden="true"></i> Banks
                <!-- BUTTON -->
                <button type="button" class="btn btn-primary float-right" data-toggle="modal" data-target="#addBank">
                    <i class="fa fa-plus" aria-hidden="true"></i>
                        Add Bank
                </button></h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <th class="d-none"></th>
                        <th>Bank Code</th>
                        <th>Bank Name</th>
                        <th>Actions</th>
                    </thead>
                    <tbody>
                    <?php
                        if(mysqli_num_rows($query_run)>0){
                            while($row = mysqli_fetch_assoc($query_run)){
                                ?>
                        <tr>
                            <td class="d-none"><?php echo $row['Bank_Id']?></td>
                            <td><?php echo $row['Bank_Code'];?></td>
                            <td><?php echo $row['Bank_Name']?></td>
                            <td class="btn-group text-center">
                                <!-- EDIT -->
                                <button type="button" class="btn btn-success editBank">
                                    <i class="fa fa-edit" area-hidden="true"></i>
                                </button>
                                <!-- DELETE -->
                                <form action="code.php" method="POST">  
                                <input type="hidden" name="Bank_Id" value="<?php echo $row['Bank_Id']?>">  
                                    <button type="submit" name ="delBank" class="btn btn-danger">
                                        <i class="fa fa-trash" area-hidden="true"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        <?php
                    }
                }
                else
                {
                    echo "No Record Found";
                }
            ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                        <h5 class="m-0 font-weight-bold text-primary"><i class="fa fa-user" aria-hidden="true"></i> Accounts
                        <!-- BUTTON -->
                        <button type="button" class="btn btn-primary float-right" data-toggle="modal" data-target="#addAccount">
                            <i class="fa fa-plus" aria-hidden="true"></i>
                                Add Account
                        </button></h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped" id="accTbl" width="100%" cellspacing="0">
                            <thead>
                                <th class="d-none"></th>
                                <th>Account Name</th>
                                <th>Type</th>
                                <th>Bank</th>
                                <th>IBAN</th>
                                <th class="d-none"></th>
                                <th class="d-none"></th>
                                <th class="d-none"></th>
                                <th>Acc. Detail File</th>
                                <th class="d-none"></th>
                                <th>Actions</th>
                            </thead>
                            <tbody>
                            <?php
                            $account_query="SELECT * FROM account WHERE Account_Status=1";
                            $account_query_run=mysqli_query($connection,$account_query);
                                if(mysqli_num_rows($account_query_run)>0){
                                    while($row_a = mysqli_fetch_assoc($account_query_run)){
                                        $acc_bank_id=$row_a['Bank_Id'];
                                        $acc_type_id=$row_a['Account_Type_Id'];
                                        $q_type_desc="SELECT * FROM account_type WHERE Account_Type_Id='$acc_type_id' AND Account_Type_Status=1";
                                        $q_type_desc_run=mysqli_query($connection,$q_type_desc);
                                        if(mysqli_num_rows($q_type_desc_run)==1){
                                            $row_accType=mysqli_fetch_assoc($q_type_desc_run);
                                            $accType=$row_accType['Account_Desc'];
                                        }
                                        $bank_query="SELECT * FROM bank WHERE Bank_Id='$acc_bank_id' AND Bank_Status=1";
                                        $bank_query_run=mysqli_query($connection,$bank_query);
                                        if(mysqli_num_rows($bank_query_run)==1){
                                            $row_bname=mysqli_fetch_assoc($bank_query_run);
                                            $acc_bank_name=$row_bname['Bank_Name'];
                                        }
                                        ?>
                                <tr>
                                    <td class="d-none"><?php echo $row_a['Account_Id']?></td>
                                    <td><?php echo $row_a['Account_Name'];?></td> 
                                    <td><?php echo $accType;?></td> <!-- TYPE NAME -->
                                    <td><?php echo $acc_bank_name; ?></td> <!-- BANK -->
                                    <td><?php echo $row_a['Account_IBAN']?></td>
                                    <td class="d-none"><?php echo $row_a['Account_Currency']?></td>
                                    <td class="d-none"><?php echo $row_a['Account_Date_Open']?></td>
                                    <td class="d-none"><?php echo $row_a['Account_Date_Expire']?></td>
                                    <td>
                                        <button class="btn btn-info btn-sm viewProf"><i class="fa fa-file-text-o " area-hidden="true"></i> View</button>
                                        <input type="hidden" name="Account_Id" value="<?php echo $row_a['Account_Id']?>">  
                                        <button class="btn btn-success btn-sm changeFile"><i class="fa fa-refresh " area-hidden="true"></i></button>
                                    </td>
                                    <td class="d-none"><?php echo $acc_bank_id?></td>
                                    <td class="btn-group text-center">
                                        <!-- VIEW -->
                                        <button type="button" class="btn btn-info viewAcc">
                                            <i class="fa fa-eye" area-hidden="true"></i>
                                        </button>
                                        <!-- EDIT -->
                                        <button type="button" class="btn btn-success editAcc">
                                            <i class="fa fa-edit" area-hidden="true"></i>
                                        </button>
                                        <!-- DELETE -->
                                        <form action="code.php" method="POST">  
                                        <input type="hidden" name="Account_Id" value="<?php echo $row_a['Account_Id']?>">  
                                            <button type="submit" name ="delAccount" class="btn btn-danger">
                                                <i class="fa fa-trash" area-hidden="true"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                <?php
                            }
                        }
                        else{
                            echo "No Record Found";
                        }
                    ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                        <h5 class="m-0 font-weight-bold text-primary">Account Types
                        <!-- BUTTON -->
                        <button type="button" class="btn btn-primary float-right" data-toggle="modal" data-target="#addAccType">
                            <i class="fa fa-plus" aria-hidden="true"></i>
                                Add Account Type
                        </button></h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped" id="accTypeTbl" width="100%" cellspacing="0">
                            <thead>
                                <th class="d-none"></th>
                                <th>Account Type</th>
                                <th>Actions</th>
                            </thead>
                            <tbody>
                            <?php
                            $acc_type_query="SELECT * FROM account_type WHERE Account_Type_Status=1";
                            $acc_type_query_run=mysqli_query($connection,$acc_type_query);
                                if(mysqli_num_rows($acc_type_query_run)>0){
                                    while($row_at = mysqli_fetch_assoc($acc_type_query_run)){
                                        ?>
                                <tr>
                                    <td class="d-none"><?php echo $row_at['Account_Type_Id']?></td>
                                    <td><?php echo $row_at['Account_Desc']?></td>
                                    <td class="btn-group text-center">
                                        <!-- EDIT -->
                                        <button type="button" class="btn btn-success editAccType">
                                            <i class="fa fa-edit" area-hidden="true"></i>
                                        </button>
                                        <!-- DELETE -->
                                        <form action="code.php" method="POST">  
                                        <input type="hidden" name="accTypeId" value="<?php echo $row_at['Account_Type_Id']?>">  
                                            <button type="submit" name ="delAccType" class="btn btn-danger">
                                                <i class="fa fa-trash" area-hidden="true"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                <?php
                            }
                        }
                        else{
                            echo "No Record Found";
                        }
                    ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Modal Add Bank -->
<div class="modal fade bd-example-modal-md" id="addBank" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-md" role="document">
    <div class="modal-content">
      <div class="modal-header">
          <!-- MODAL TITLE -->
        <h5 class="modal-title" id="exampleModalLabel"><i class="fa fa-bank" aria-hidden="true"></i> Add Bank</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
    <form action="code.php" method="POST">
      <div class="modal-body">
        <!-- THE FORM -->
            <div class="form-row">
                <div class="col-4">
                    <div class="form-group">
                        <label for="">Bank Code</label>
                        <input type="text" name="bank_code" class="form-control" required>
                    </div>
                </div>
                <div class="col-8">
                    <div class="form-group">
                        <label for="">Bank Name</label>
                        <input type="text" name="bank_name" class="form-control" required>
                    </div>
                </div>
            </div>
            <div class="form-row d-none">
                <div class="form-group">
                    <div class="col-6">
                        <label for="">Logo</label>
                        <input type="file" name="bank_logo" id="s1_1" class="imgFile" >
                    </div>
                </div>
            </div>
            
        <!-- END FORM -->
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-times" aria-hidden="true"></i> Close</button>
        <button type="submit" name="addBank" class="btn btn-success"><i class="fa fa-check" aria-hidden="true"></i> Save</button>
      </div>
      </form>
    </div>
  </div>
</div>
<!-- End Modal ADD Bank -->
<!-- Modal EDIT Bank -->
<div class="modal fade bd-example-modal-md" id="editBank" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-md" role="document">
    <div class="modal-content">
      <div class="modal-header">
          <!-- MODAL TITLE -->
        <h5 class="modal-title" id="exampleModalLabel"><i class="fa fa-folder" aria-hidden="true"></i> Edit Bank</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
    <form action="code.php" method="POST">
      <div class="modal-body">
        <!-- THE FORM -->
        <input type="hidden" name="Bank_Id" id="Bank_Id" value="">
            <div class="form-group">
                <label for="">Bank Code</label>
                <input type="text" id="bank_code" name="bank_code" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="">Bank Name</label>
                <input type="text" id="bank_name" name="bank_name" class="form-control" required>
            </div>
        <!-- END FORM -->
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-times" aria-hidden="true"></i> Close</button>
        <button type="submit" name="editBank" class="btn btn-success"><i class="fa fa-check" aria-hidden="true"></i> Save</button>
      </div>
      </form>
    </div>
  </div>
</div>
<!-- End Modal EDIT Bank -->
<!-- Modal Add Account Type -->
<div class="modal fade bd-example-modal-md" id="addAccType" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-md" role="document">
    <div class="modal-content">
      <div class="modal-header">
          <!-- MODAL TITLE -->
        <h5 class="modal-title" id="exampleModalLabel"><i class="fa fa-bank" aria-hidden="true"></i> Add Account Type</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
    <form action="code.php" method="POST">
      <div class="modal-body">
        <!-- THE FORM -->
            <div class="form-group">
                <label for="">Account Description</label>
                <input type="text" name="acc_desc" class="form-control" required>
            </div>
        <!-- END FORM -->
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-times" aria-hidden="true"></i> Close</button>
        <button type="submit" name="addAccType" class="btn btn-success"><i class="fa fa-check" aria-hidden="true"></i> Save</button>
      </div>
      </form>
    </div>
  </div>
</div>
<!-- End Modal add acc type -->
<!-- Modal EDIT acc type -->
<div class="modal fade bd-example-modal-md" id="editAccType" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-md" role="document">
    <div class="modal-content">
      <div class="modal-header">
          <!-- MODAL TITLE -->
        <h5 class="modal-title" id="exampleModalLabel"><i class="fa fa-folder" aria-hidden="true"></i> Edit Account Type</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
    <form action="code.php" method="POST">
      <div class="modal-body">
        <!-- THE FORM -->
        <input type="hidden" name="acc_type_id" id="acc_type_id" value="">
            <div class="form-group">
                <label for="">Account Type</label>
                <input type="text" id="acc_type_name" name="acc_type" class="form-control" required>
            </div>
        <!-- END FORM -->
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-times" aria-hidden="true"></i> Close</button>
        <button type="submit" name="editAccType" class="btn btn-success"><i class="fa fa-check" aria-hidden="true"></i> Save</button>
      </div>
      </form>
    </div>
  </div>
</div>
<!-- End Modal EDIT acc type -->
<!-- Modal Add Account-->
<div class="modal fade bd-example-modal-md" id="addAccount" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
          <!-- MODAL TITLE -->
        <h5 class="modal-title" id="exampleModalLabel"><i class="fa fa-id-card" aria-hidden="true"></i> Add Account</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
    <form action="code.php" method="POST" enctype="multipart/form-data">
      <div class="modal-body">
        <!-- THE FORM -->
        <div class="form-row">
            <div class="col-8">
                <div class="form-group">
                    <label for="">Account Name</label>
                    <input type="text" name="acc_name" class="form-control" required>
                </div>
            </div>
            <div class="col-4">
                <div class="form-group">
                    <label for="">Account Type</label>
                    <select name="acc_type" id="" class="form-control selectpicker" required>
                    <?php
                        $acc_type="SELECT * FROM account_type WHERE Account_Type_Status=1";
                        $acc_type_run=mysqli_query($connection,$acc_type);
                        if(mysqli_num_rows($acc_type_run)>0){
                            while($r_topt=mysqli_fetch_assoc($acc_type_run)){
                                $type_id=$r_topt['Account_Type_Id'];
                                $type_name=$r_topt['Account_Desc'];
                                echo"<option value=".$type_id.">".$type_name."</option>";
                            }
                        }
                    ?>
                    </select>
                </div>
            </div>
        </div>
        <div class="form-row">
            <div class="col-8">
                <div class="form-group">
                    <label for="">Bank</label>
                    <select name="bank_id" id="bank_opt" class="form-control"></select>
                </div>
            </div>
            <div class="col-4">
                <div class="form-group">
                    <label for="">Account Information File</label><span class="font-italic">(.pdf)</span>
                    <input type="file" id="formFile1" size="200" name="accFile" class="form-control check-fields5" onchange="return fileValidation()"> 
                </div>
            </div>
        </div>
        <div class="form-row">
            <div class="col-7">
                <div class="form-group">
                    <label for="">IBAN</label>
                    <input type="text" name="iban" class="form-control" >
                </div>
            </div>
            <div class="col-5">
                <div class="form-group">
                    <label for="">Currency</label>
                    <select name="currency" id="" class="form-control" required>
                        <option value="AED">AED - United Arab Emirates Dirham</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="form-row">
            <div class="col-6">
                <div class="form-group">
                    <label for="">Date Open</label>
                    <input type="date" name="date_open" class="form-control">
                </div>
            </div>
            <div class="col-6">
                <div class="form-group">
                    <label for="">Date Validity</label>
                    <input type="date" name="date_valid" class="form-control" required>
                </div>
            </div>
        </div>
        <!-- END FORM -->
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-times" aria-hidden="true"></i> Close</button>
        <button type="submit" name="addAccount" class="btn btn-success"><i class="fa fa-check" aria-hidden="true"></i> Save</button>
      </div>
      </form>
    </div>
  </div>
</div>
<!-- End Modal ADD Bank -->
<!-- Modal EDIT Account -->
<div class="modal fade bd-example-modal-lg" id="editAcc" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
          <!-- MODAL TITLE -->
        <h5 class="modal-title" id="exampleModalLabel"><i class="fa fa-folder" aria-hidden="true"></i> Edit Account Details</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
    <form action="code.php" method="POST">
      <div class="modal-body">
        <!-- THE FORM -->
        <div class="form-row">
            <input type="hidden" name="acc_id" id="eAccId">
            <div class="col-8">
                <div class="form-group">
                    <label for="">Account Name</label>
                    <input type="text" id="eAccName" name="acc_name" class="form-control" required>
                </div>
            </div>
            <div class="col-4">
                <div class="form-group">
                    <label for="">Account Type</label>
                    <select name="acc_type" id="" class="form-control selectpicker" required>
                    <?php
                        $acc_type2="SELECT * FROM account_type WHERE Account_Type_Status=1";
                        $acc_type2_run=mysqli_query($connection,$acc_type2);
                        if(mysqli_num_rows($acc_type2_run)>0){
                            while($r_topt2=mysqli_fetch_assoc($acc_type2_run)){
                                $type_id=$r_topt2['Account_Type_Id'];
                                $type_name=$r_topt2['Account_Desc'];
                                echo"<option value=".$type_id.">".$type_name."</option>";
                            }
                        }
                    ?>
                    </select>
                </div>
            </div>
        </div>
        <div class="form-row">
            <div class="col-12">
                <div class="form-group">
                    <label for="">Bank</label>
                    <select name="bank_id" id="eBankId" class="form-control selectpicker">
                        <option value=""></option>
                        <?php 
                            $q_bank2="SELECT * FROM bank WHERE Bank_Status=1";
                            $q_bank2_run=mysqli_query($connection,$q_bank2);
                            if(mysqli_num_rows($q_bank2_run)>0){
                                while($row_b2=mysqli_fetch_assoc($q_bank2_run)){
                                    $bank_id2=$row_b2['Bank_Id'];
                                    $bank_name2=$row_b2['Bank_Name'];
                                    echo"<option value=".$bank_id2.">".$bank_name2."</option>";
                                }
                            }
                        ?>
                    </select>
                </div>
            </div>
        </div>
        <div class="form-row">
            <div class="col-7">
                <div class="form-group">
                    <label for="">IBAN</label>
                    <input type="text" name="iban" id="eIBAN" class="form-control">
                </div>
            </div>
            <div class="col-5">
                <div class="form-group">
                    <label for="">Currency</label>
                    <select name="currency" id="eAccId" class="form-control" required>
                        <option value="AED">AED - United Arab Emirates Dirham</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="form-row">
            <div class="col-6">
                <div class="form-group">
                    <label for="">Date Open</label>
                    <input type="date" id="eDateOpen" name="date_open" class="form-control">
                </div>
            </div>
            <div class="col-6">
                <div class="form-group">
                    <label for="">Date Validity</label>
                    <input type="date" id="eDateValid" name="date_valid" class="form-control" required>
                </div>
            </div>
        </div>
        <!-- END FORM -->
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-times" aria-hidden="true"></i> Close</button>
        <button type="submit" name="editAcc" class="btn btn-success"><i class="fa fa-check" aria-hidden="true"></i> Save</button>
      </div>
      </form>
    </div>
  </div>
</div>
<!-- End Modal EDIT Account -->
<!-- Modal View account details -->
<div class="modal fade bd-example-modal-md" id="viewAcc" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
          <!-- MODAL TITLE -->
        <h5 class="modal-title" id="exampleModalLabel"><i class="fa fa-folder" aria-hidden="true"></i> Account Details:</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div>
            <table class="bg-light" width="100%">
                <tbody class="p-4">
                    <tr>
                        <td class="font-weight-bold">A/c Name:</td>
                        <td><span id="mAccName"></span></td>
                    </tr>
                    <tr>
                        <td class="font-weight-bold">A/c Type:</td>
                        <td><span id="mAccType"></span></td>
                    </tr>
                    <tr>
                        <td class="font-weight-bold">Bank Name:</td>
                        <td><span id="mBankName"></span></td>
                    </tr>
                    <tr>
                        <td class="font-weight-bold">IBAN: </td>
                        <td><span id="mIBAN"></span></td>
                    </tr>
                    <tr>
                        <td class="font-weight-bold">Date Valid:</td>
                        <td><span id="mDateValid"></span></td>
                    </tr>
                    <tr>
                        <td class="font-weight-bold">Currency:</td>
                        <td><span id="mCurrency"></span></td>
                    </tr>
                </tbody>
            </table>
        </div>
      </div>
      <div class="modal-footer">
      <button type="button" class="btn btn-success d-none" id="download"><i class="fa fa-download mr-2" aria-hidden="true"></i>Download</button>
        <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-times" aria-hidden="true"></i> Close</button>
      </div>
    </div>
  </div>
</div>
<!-- End Modal EDIT Bank -->
<!-- view account profile -->
<div class="modal fade" id="viewPdf" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog pdf modal-xl" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel"></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body" id="ff">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
<!-- Modal change file -->
<div class="modal fade bd-example-modal-md" id="changeFile" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-md" role="document">
    <div class="modal-content">
      <div class="modal-header">
          <!-- MODAL TITLE -->
        <h5 class="modal-title" id="exampleModalLabel"><i class="fa fa-refresh" aria-hidden="true"></i> Change Account File</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
    <form action="code.php" method="POST" enctype="multipart/form-data">
      <div class="modal-body">
        <input type="hidden" name="accId" id="accId" value="">
            <div class="form-group">
                <label for="">Account Information File</label><span class="font-italic">(.pdf)</span>
                <input type="file" id="formFile1" size="200" name="accFile" class="form-control check-fields5"> 
            </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-times" aria-hidden="true"></i> Close</button>
        <button type="submit" name="changeFile" class="btn btn-success"><i class="fa fa-check" aria-hidden="true"></i> Save</button>
      </div>
      </form>
    </div>
  </div>
</div>
<!-- End Modal change file -->
<script>
$(document).ready(function () {
    $(document).on('click', '.editBank', function() {
        $('#editBank').modal('show');
        $tr = $(this).closest('tr');
        var data = $tr.children("td").map(function(){
            return $(this).text();
        }).get();
       $('#Bank_Id').val(data[0]);
       $('#bank_code').val(data[1]);
       $('#bank_name').val(data[2]);
    });
});
$(document).ready(function () {
    $(document).on('click', '.editAcc', function() {
        $('#editAcc').modal('show');
        $tr = $(this).closest('tr');
        var data = $tr.children("td").map(function(){
            return $(this).text();
        }).get();
        $('#eAccId').val(data[0]);
        $('#eAccName').val(data[1]);
        $('#eAccType').val(data[2]);
        $('#eBankId').val(data[9]);
        $('#eIBAN').val(data[4]);
        $('#eCurrency').val(data[5]);
        $('#eDateOpen').val(data[6]);
        $('#eDateValid').val(data[7]);
        // $('#eDateValid').val(data[7]);
        $('#eBankId').selectpicker('refresh');
        
    });
});
$(document).ready(function () {
    $(document).on('click', '.editAccType', function() {
        $('#editAccType').modal('show');
        $tr = $(this).closest('tr');
        var data = $tr.children("td").map(function(){
            return $(this).text();
        }).get();
       $('#acc_type_id').val(data[0]);
       $('#acc_type_name').val(data[1]);
    });
});
$(document).ready(function () {
    $(document).on('click','.viewAcc', function(){
        $tr = $(this).closest('tr');
        var data = $tr.children("td").map(function(){
            return $(this).text();
        }).get();
        $('#viewAcc').modal('show');
        $('#mAccId').val(data[0]);
        $('#mAccName').html(data[1]).change();
        $('#mAccType').html(data[2]).change();
        $('#mBankName').html(data[3]).change();
        $('#mIBAN').html(data[4]).change();
        $('#mCurrency').html(data[5]).change();
        $('#mDateOpen').html(data[6]).change();
        $('#mDateValid').html(data[7]).change();
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
$(document).ready(function () {
    $('#accTbl').DataTable({
        "searching": true,
        "bPaginate": true
    });
});
$(document).ready(function () {
    $('#accTypeTbl').DataTable({
        "searching": true,
        "bPaginate": true
    });
});
$(document).ready(function(){
    var bank_opt="";
    $.ajax({
        url:'options.php',
        method: 'POST',
        data:{'bbank_opt': bank_opt},
        success:function(data){
            $(document).find('#bank_opt').html(data).change();
        }
    });
});
function fileValidation() {
    var fileInput = document.getElementById('formFile1');
    var filePath = fileInput.value;
    
    // Allowing file type
    var allowedExtensions =
        /(\.pdf)$/i;
        
    if (!allowedExtensions.exec(filePath)) {
        alert('Invalid file type');
        fileInput.value = '';
        return false;
    }
}
$(document).on('click','.viewProf', function(){
    var acc_id = $(this).next('input').val();
    cprofile="EMTAccountDetails"+acc_id+'.pdf';
    $.ajax({
        url:'options.php',
        method: 'POST',
        data:{'bankfile':cprofile},
        success:function(data){
            $('#viewPdf .modal-body').html(data);
            $('#viewPdf').modal('show');
        }
    });  
});
$(document).ready(function () {
    $(document).on('click', '.changeFile', function() {
        var acc_id = $(this).prev('input').val();
        $('#accId').val(acc_id);
        $('#changeFile').modal('show');
        
    });
});
</script>
<?php
include('../includes/scripts.php');
include('../includes/footer.php');
?>