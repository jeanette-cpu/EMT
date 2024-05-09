<?php
include('security.php');
include('includes/header.php');
include('includes/navbar.php');
?>

<div class="container-fluid">
<!-- DataTales Example -->
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary"> Edit Employee Details</h6>
    </div>
    <div class="card-body">
<?php
    if(isset($_POST['edit_emp']) or isset($_GET['id']))
        {
            if(isset($_POST['edit_emp']))
            {
                $id_e = $_POST['emp_id'];
                $id=$id_e;
            }
            else
            {
                $id_d=$_GET['id'];
                $id=$id_d;
            }

            $query ="SELECT * FROM employee WHERE EMP_ID='$id' ";
            $query1 ="SELECT * FROM allowance LEFT JOIN employee on employee.EMP_ID = allowance.EMP_ID WHERE employee.EMP_ID='$id'";

            $query_run = mysqli_query($connection, $query);
            $query_run1 = mysqli_query($connection, $query1);

            foreach($query_run as $row)
            {
                ?>
                <form action="code.php" method="POST">
                    <!-- FORM -->
                    <input type="hidden" name="edit_empid" value="<?php echo $row['EMP_ID'] ?>">

                    <div class="form-group">
                        <label> Employee Number: </label>
                        <input type="number" name="edit_empno" value="<?php echo $row['EMP_NO'] ?>" class="form-control" maxlength="10" required>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-4">
                            <label> First Name: </label>
                            <input type="text" name="edit_empfname" value="<?php echo $row['EMP_FNAME'] ?>" class="form-control" maxlength="20" required>
                        </div>
                        <div class="form-group col-md-4">
                            <label> Last Name: </label>
                            <input type="text" name="edit_emplname" value="<?php echo ucwords($row['EMP_LNAME']) ?>" class="form-control" maxlength="20" required>
                        </div>
                        <div class="form-group col-md-2">
                            <label> Middle Name: </label>
                            <input type="text" name="edit_empmname" value="<?php echo ucwords($row['EMP_MNAME']) ?>" class="form-control" maxlength="20">
                        </div>
                        <div class="form-group col-md-2">
                            <label> Suffix: </label>
                            <input type="text" name="edit_empsname" value="<?php echo ucwords($row['EMP_SNAME']) ?>" class="form-control" maxlength="5">
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-1">
                            <label> Paymode: </label>
                            <input type="text" name="edit_pmode" value="<?php echo $row['EMP_PAYMODE'] ?>" class="form-control" placeholder="" maxlength="5">
                        </div>
                        <div class="form-group col-md-4">
                            <label> Project/Location: </label>
                            <input type="text" name="edit_proloc" value="<?php echo $row['EMP_LOCATION'] ?>" class="form-control" placeholder="" maxlength="30" required>
                        </div>
                        <div class="form-group col-md-4">
                            <label> Designation: </label>
                            <input type="text" name="edit_desig" value="<?php echo $row['EMP_DESIGNATION'] ?>" class="form-control" placeholder="" maxlength="20" required>
                        </div>
                        <div class="form-group col-md-2">
                            <label> DOJ: </label>
                            <input type="date" name="edit_doj" value="<?php echo $row['EMP_DOJ'] ?>" class="form-control" required>
                        </div>
                    </div>

                    <div class="form-row">
                        
                        <div class="form-group col-md-12">
                            <label> Bank Name: </label>
                            <input type="text" name="edit_bankname" value="<?php echo $row['EMP_BANK'] ?>" class="form-control" maxlength="30">
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label> Account No.: </label>
                            <input type="text" name="edit_accno" value="<?php echo $row['EMP_ACCNO'] ?>" class="form-control" maxlength="30">
                        </div>
                        <div class="form-group col-md-6">
                            <label> IBAN No.: </label>
                            <input type="text" name="edit_ibanno" value="<?php echo $row['EMP_IBANNO'] ?>" class="form-control" maxlength="30">
                        </div>
                    </div>
                    <div class="btn-toolbar pull-right ">
                        <a href="employee.php" class="btn btn-danger mr-3"><i class="fa fa-times" aria-hidden="true"></i> Cancel</a>
                        <button type="submit" name="update_emp" class="btn btn-success mr-3"><i class="fa fa-check" aria-hidden="true"></i> Update </button>
                    </div>           
                </form>
                <?php
            }
        }
?>
    </div>
        <!-- END FORM -->
</div>
<!-- container fluid -->

<?php
include('includes/scripts.php');
include('includes/footer.php');
?>