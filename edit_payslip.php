<?php
include('security.php');
include('includes/header.php');
include('includes/navbar.php');
?>
<!-- Content Wrapper -->
<div class="container-fluid">
<!-- DataTales Example -->
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary"><i class="fa fa-file-text" aria-hidden="true"></i> Edit Payslip Details</h6>
    </div>
    <div class="card-body d-flex">
<?php
    if(isset($_POST['edit_p']) or isset($_GET['id']) or isset($_GET['idf']))
        {
            if(isset($_POST['edit_p']))
            {
                $id_e = $_POST['p_id'];
                $id=$id_e;
            }
            elseif(isset($_GET['fid']))
            {
                $id=$_GET['fid'];
            }
            else
            {
                $id_d=$_GET['id'];
                $id=$id_d;
            }
            $query ="SELECT * FROM employee LEFT JOIN payslip on payslip.EMP_ID = employee.EMP_ID WHERE PAYSLIP_ID='$id' ";
            $query1 ="SELECT * FROM allowance LEFT JOIN payslip on payslip.PAYSLIP_ID = allowance.PAYSLIP_ID WHERE allowance.PAYSLIP_ID='$id'";
            $query2 ="SELECT * FROM deduction LEFT JOIN payslip on payslip.PAYSLIP_ID = deduction.PAYSLIP_ID WHERE deduction.PAYSLIP_ID='$id'";
            $query3 ="SELECT * FROM additional LEFT JOIN payslip on payslip.PAYSLIP_ID = additional.PAYSLIP_ID WHERE additional.PAYSLIP_ID='$id'";
            $query4 ="SELECT * FROM full_allowance LEFT JOIN payslip on payslip.PAYSLIP_ID = full_allowance.PAYSLIP_ID WHERE full_allowance.PAYSLIP_ID='$id'";


            $query_run = mysqli_query($connection, $query);
            $query_run1 = mysqli_query($connection, $query1);
            $query_run2 = mysqli_query($connection, $query2);
            $query_run3 = mysqli_query($connection, $query3);
            $query_run4 = mysqli_query($connection, $query4);

            foreach($query_run as $row)
            {
                ?>
                <form action="code.php" method="POST">
                    <!-- FORM -->
                    <input type="hidden" name="edit_pid" value="<?php echo $row['PAYSLIP_ID'] ?>">

                    <div class="row">
                        <div class="col-2">
                            <label class="">Employee Number:</label>
                            <input type="number" name="e_basic" value="<?php echo $row['EMP_NO'] ?>" class="form-control" readonly>
                        </div>
                        <div class="col-4">
                            <label>Employee Name:</label>
                            <input type="text" name="e_basic" value="<?php echo ucfirst($row['EMP_FNAME']); ?><?php echo ' '.ucfirst($row ['EMP_MNAME']); ?><?php echo ' '.ucfirst($row['EMP_LNAME']); ?><?php echo ' '.ucwords($row['EMP_SNAME']); ?>" class="form-control" readonly>
                        </div>
                        <div class="col-3">
                            <label> Date: </label>
                            <input type="date" name="e_date" value="<?php echo $row['P_DATE'] ?>" class="form-control" required>
                        </div>
                    <div>
                    <div class="row ml-1">
                        <div class="col-4">
                            <label> Full Basic Salary: </label>
                            <input type="number" step=".001" name="fe_basic" value="<?php echo $row['P_FULL_BASIC'] ?>" class="form-control" required>
                        </div>
                        
                        <div class="col-4">
                            <label> Cumputed Basic: </label>
                            <input type="number" step=".001" name="e_basic" value="<?php echo $row['P_BASIC_SALARY'] ?>" class="form-control" required>
                        </div>

                        <div class="col-4">
                            <label> Bonus & OT: </label>
                            <input type="number" step=".001" name="e_bot" value="<?php echo $row['P_BONUS_AND_OT'] ?>" class="form-control">
                        </div>
                    </div>

<!-- FULL ALLOWANCE -->
<div class="row ml-1 mt-3">
                        <div class="table-responsive">
                            <table class="table table-bordered" id="falwTbl">
                                <thead>
                                    <th width="47%">Full Allowance</th>
                                    <th width="47%">Amount</th>
                                    <th width="6%"></th>
                                </thead>
                                <tbody>
                                    <?php
                                        if(mysqli_num_rows($query_run4)>0)
                                        {
                                            while($row4 = mysqli_fetch_assoc($query_run4))
                                            {
                                                ?>
                                    <tr>
                                        <td class="d-none"><input class="form-control" name="fe_alw_id[]" value="<?php echo $row4['FULL_ALW_ID']; ?>"></td>
                                        <td><input width="47%" class="form-control no-border falw_name" name="fe_alw_name[]" type="text" value="<?php echo $row4['FULL_ALW_NAME']; ?>"></td>
                                        <td><input width="47%" class="form-control no-border falw_amt" name="fe_alw_amt[]" step=".001" type="number" value="<?php echo $row4['FULL_ALW_AMT']; ?>"></td>
                                        <td class="btn-group text-center">
                                            <form action="code.php" method="POST">
                                                <input type="hidden" name="fd_alw_id" value="<?php echo $row4['FULL_ALW_ID'];?>">
                                                <input type="hidden" name="e_pid" value="<?php echo $row4['PAYSLIP_ID'] ?>">
                                                    <button type="submit" name ="delete_falw" class="btn btn-danger d-inline">-</button>
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
                            <div align="right">
                                <button type="button" name="afdd" id="faddAl" class="btn btn-success btn-xs">+</button>
                            </div>
                        </div>              
                    </div>

<!-- ALLOWANCE -->
                    <div class="row ml-1 mt-3">
                        <div class="table-responsive">
                            <table class="table table-bordered" id="alwTbl">
                                <thead>
                                    <th width="47%">Allowance Description</th>
                                    <th width="47%">Amount</th>
                                    <th width="6%"></th>
                                </thead>
                                <tbody>
                                    <?php
                                        if(mysqli_num_rows($query_run1)>0)
                                        {
                                            while($row1 = mysqli_fetch_assoc($query_run1))
                                            {
                                                ?>
                                    <tr>
                                        <td class="d-none"><input class="form-control" name="e_alw_id[]" value="<?php echo $row1['ALW_ID']; ?>"></td>
                                        <td><input width="47%" class="form-control no-border alw_name" name="e_alw_name[]" type="text" value="<?php echo $row1['ALW_NAME']; ?>"></td>
                                        <td><input width="47%" class="form-control no-border alw_amt" name="e_alw_amt[]" step=".001" type="number" value="<?php echo $row1['ALW_AMT']; ?>"></td>
                                        <td class="btn-group text-center">
                                            <form action="code.php" method="POST">
                                                <input type="hidden" name="d_alw_id" value="<?php echo $row1['ALW_ID'];?>">
                                                <input type="hidden" name="e_pid" value="<?php echo $row['PAYSLIP_ID'] ?>">
                                                    <button type="submit" name ="delete_alw" class="btn btn-danger d-inline">-</button>
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
                            <div align="right">
                                <button type="button" name="add" id="addAl" class="btn btn-success btn-xs">+</button>
                            </div>
                        </div>              
                    </div>
<!-- DEDUCTION -->
                    <div class="row ml-1 mt-3">
                        <div class="table-responsive">
                            <table class="table table-bordered" id="dTbl">
                                <thead>
                                    <th width="47%">Deduction Description</th>
                                    <th width="47%">Amount</th>
                                    <th width="6%"></th>
                                </thead>
                                <tbody>
                                    <?php
                                        if(mysqli_num_rows($query_run2)>0)
                                        {
                                            while($row2 = mysqli_fetch_assoc($query_run2))
                                            {
                                                ?>
                                    <tr>
                                        <td class="d-none"><input type="hidden" name="e_d_id[]" value="<?php echo $row2['DEDUC_ID']; ?>"></td>
                                        <td><input width="47%" class="form-control no-border d_name" name="e_d_name[]" type="text" value="<?php echo $row2['DEDUC_NAME']; ?>"></td>
                                        <td><input width="47%" class="form-control no-border d_amt" name="e_d_amt[]" step=".001" type="number" value="<?php echo $row2['DEDUC_AMT']; ?>"></td>
                                        <td class="btn-group text-center">
                                            <form action="code.php" method="POST">
                                                <input type="hidden" name="e_pid" value="<?php echo $row['PAYSLIP_ID'] ?>">
                                                <input type="hidden" name="d_deduc_id" value="<?php echo $row2['DEDUC_ID'];?>">
                                                    <button type="submit" name ="delete_d" class="btn btn-danger d-inline">-</button>
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
                            <div align="right">
                                <button type="button" name="add" id="addD" class="btn btn-success btn-xs">+</button>
                            </div>
                        </div> 
                    </div>
<!-- ADDITIONAL -->
                    <div class="row ml-1 mt-3">
                        <div class="table-responsive">
                            <table class="table table-bordered" id="addTbl">
                                <thead>
                                    <th width="47%">Additional Description</th>
                                    <th width="47%">Amount</th>
                                    <th width="6%"></th>
                                </thead>
                                <tbody>
                                    <?php
                                        if(mysqli_num_rows($query_run3)>0)
                                        {
                                            while($row3 = mysqli_fetch_assoc($query_run3))
                                            {
                                                ?>
                                    <tr>
                                        <td class="d-none"><input type="hidden" name="e_ad_id[]" value="<?php echo $row3['ADD_ID']; ?>"></td>
                                        <td><input width="47%" class="form-control no-border add_name" name="e_add_name[]" type="text" value="<?php echo $row3['ADD_NAME'];?>"></td>
                                        <td><input width="47%" class="form-control no-border add_amt" name="e_add_amt[]" step=".001" type="number" value="<?php echo $row3['ADD_AMT'];?>"></td>
                                        <td class="btn-group text-center">
                                            <form action="code.php" method="POST">
                                                <input type="hidden" name="e_pid" value="<?php echo $row['PAYSLIP_ID'] ?>">
                                                <input type="hidden" name="d_add_id" value="<?php echo $row3['ADD_ID'];?>">
                                                    <button type="submit" name ="delete_add" class="btn btn-danger d-inline">-</button>
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
                            <div align="right">
                                <button type="button" name="add" id="addAdd" class="btn btn-success btn-xs">+</button>
                            </div>
                        </div> 
                    </div>

                    <div class="row ml-1">
                        <div class="col">
                            <label> Normal OT Hours: </label>
                            <input type="number" step=".01" name="e_norm_ot_hrs" value="<?php echo $row['P_NORM_OTHRS'] ?>" class="form-control">
                        </div>
                        <div class="col">
                            <label> Amount </label>
                            <input type="number" step=".001" name="e_norm_ot_amt" value="<?php echo $row['P_NORM_OTAMT'] ?>" class="form-control">
                        </div>
                        <div class="col">
                            <label> Normal Holiday Hours: </label>
                            <input type="number" step=".01" name="e_norm_hol_hrs" value="<?php echo $row['P_HOL_OTHRS'] ?>" class="form-control">
                        </div>
                        <div class="col">
                            <label> Amount </label>
                            <input type="number" step=".001" name="e_norm_hol_amt" value="<?php echo $row['P_HOL_OTAMT'] ?>" class="form-control">
                        </div>
                    </div>

                    <div class="row ml-1">
                        <div class="col">
                            <label> Special Hours: </label>
                            <input type="number" step=".01" name="e_sp_hrs" value="<?php echo $row['P_SP_HRS'] ?>" class="form-control">
                        </div>
                        <div class="col">
                            <label> Amount </label>
                            <input type="number" step=".001" name="e_sp_amt" value="<?php echo $row['P_SP_AMT'] ?>" class="form-control">
                        </div>
                        <div class="col">
                            <label> Bonus Hours: </label>
                            <input type="number" step=".0001" name="e_bns_hrs" value="<?php echo $row['P_BNS_HR'] ?>" class="form-control">
                        </div>
                        <div class="col">
                            <label> Amount </label>
                            <input type="number" step=".0001" name="e_bns_amt" value="<?php echo $row['P_BNS_AMT'] ?>" class="form-control">
                        </div>
                    </div>

                    <div class="row ml-1">
                        <div class="col">
                            <label> Absent Days: </label>
                            <input type="number" step=".01" name="e_ab_days" value="<?php echo $row['P_ABDAYS'] ?>" class="form-control">
                        </div>
                        <div class="col">
                            <label> Leave Days: </label>
                            <input type="number" step=".01" name="e_l_days" value="<?php echo $row['P_LDAYS'] ?>" class="form-control">
                        </div>
                    </div>

                    <div class="btn-toolbar pull-right">
                        <a href="payslip.php" class="btn btn-danger mr-3"><i class="fa fa-times" aria-hidden="true"></i> Cancel</a>
                        <button type="submit" name="update_p" class="btn btn-success mr-3"><i class="fa fa-check" aria-hidden="true"></i> Update </button>
                    </div>
                </form>
                <?php
            }
        }
?>
    </div>
        <!-- END FORM -->
</div>

</div>
</div>

<script>
    $(document).ready(function(){
    var count = 1;
    $('#addAl').click(function(){
    count = count + 1;
    var html_code = "<tr id='row"+count+"'>";
        html_code += "<td><input width='47%' class='form-control no-border alw_name' name='alw_name[]' type='text'></td>";
        html_code += "<td><input width='47%' class='form-control no-border alw_amt' name='alw_amt[]' step='.001' type='number'></td>";
        html_code += "<td><button type='button' name='remove' data-row='row"+count+"' class='btn btn-danger btn-xs remove'>-</button></td>";
        html_code += "</tr>";
        $('#alwTbl').append(html_code);
    });

    var countfa = 1;
    $('#faddAl').click(function(){
    countfa = countfa + 1;
    var html_code = "<tr id='row"+countfa+"'>";
        html_code += "<td><input width='47%' class='form-control no-border falw_name' name='falw_name[]' type='text'></td>";
        html_code += "<td><input width='47%' class='form-control no-border falw_amt' name='falw_amt[]' step='.001' type='number'></td>";
        html_code += "<td><button type='button' name='remove' data-row='row"+countfa+"' class='btn btn-danger btn-xs remove'>-</button></td>";
        html_code += "</tr>";
        $('#falwTbl').append(html_code);
    });

    var countD = 1;
    $('#addD').click(function(){
    countD = countD + 1;
    var html_code = "<tr id='row"+countD+"'>";
        html_code += "<td><input width='47%' class='form-control no-border deduc_name' name='deduc_name[]' type='text'></td>";
        html_code += "<td><input width='47%' class='form-control no-border deduc_amt' name='deduc_amt[]' step='.001' type='number'></td>";
        html_code += "<td><button type='button' name='remove' data-row='row"+countD+"' class='btn btn-danger btn-xs remove'>-</button></td>";
        html_code += "</tr>";
        $('#dTbl').append(html_code);
    });
    
    var countAd = 1;
    $('#addAdd').click(function(){
    countAd = countAd + 1;
    var html_code = "<tr id='row"+countAd+"'>";
        html_code += "<td><input width='47%' class='form-control no-border add_name' name ='add_name[]' type='text'></td>";
        html_code += "<td><input width='47%' class='form-control no-border add_amt' name='add_amt[]' step='.001' type='number'></td>";
        html_code += "<td><button type='button' name='remove' data-row='row"+countAd+"' class='btn btn-danger btn-xs remove'>-</button></td>";
        html_code += "</tr>";
        $('#addTbl').append(html_code);
    });

    $(document).on('click', '.remove', function(){
            var delete_row = $(this).data("row");
            $('#' + delete_row).remove();
           });
});
</script>
<?php
include('includes/scripts.php');
include('includes/footer.php');
?>