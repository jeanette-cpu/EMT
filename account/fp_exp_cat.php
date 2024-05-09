<?php
include('../security.php');
include('../includes/header.php'); 
include('../includes/acc_navbar.php');
include('accQuery.php'); 
$currentMname = date('F');
$currentYear = date('Y');
$q1_d=$currentYear.'-01-01';
$q2_d=$currentYear.'-04-01';
$q3_d=$currentYear.'-07-01';
$q4_d=$currentYear.'-10-01';
?>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card shadow mb-5">
                <div class="card-header py-3">
                    <h5 class="m-0 font-weight-bold text-primary">Automated Payment Plans
                        <button type="button" class="btn btn-sm btn-primary float-right mr-1 " data-toggle="modal" data-target="#addplan">
                            <i class="fa fa-plus" aria-hidden="true"></i> Create Plan
                        </button>
                    </h5>
                </div>
                <div class="card-body">
                    <div class="table table-responsive">
                        <table class="table table-bordered" id="dataTable">
                            <thead>
                                <th class="d-none"></th>
                                <th>Category</th>
                                <th>Description</th>
                                <th>Frequency</th>
                                <th>Amount</th>
                                <th colspan="6" class="d-none"></th>
                                <th>Actions</th>
                            </thead>
                            <tbody>
                        <?php 
                        if(mysqli_num_rows($q_plan_run)>0){
                            while($row_p=mysqli_fetch_assoc($q_plan_run)){
                                $p_id=$row_p['Plan_Id'];
                                $cat_desc=$row_p['Transaction_Category_Description'];
                                $cat_id=$row_p['Transaction_Category_Id'];
                                $p_desc=$row_p['Plan_Desc'];
                                $p_freq=$row_p['Frequency'];
                                $p_amount=$row_p['Plan_Amount'];
                                $p_output=number_format($p_amount,2);
                                $y_date=$row_p['Y_Date'];
                                $q1_date=$row_p['Q1_Date'];
                                $q2_date=$row_p['Q2_Date'];
                                $q3_date=$row_p['Q3_Date'];
                                $q4_date=$row_p['Q4_Date'];
                            ?>
                                <tr>
                                    <td class="d-none"><?php echo $p_id;?></td>
                                    <td><?php echo $cat_desc;?></td>
                                    <td><?php echo $p_desc;?></td>
                                    <td><?php echo $p_freq;?></td>
                                    <td><?php echo $p_output;?></td>
                                    <td class="d-none"><?php echo $cat_id?></td>
                                    <td class="d-none"><?php echo $y_date;?></td>
                                    <td class="d-none"><?php echo $q1_date;?></td>
                                    <td class="d-none"><?php echo $q2_date;?></td>
                                    <td class="d-none"><?php echo $q3_date;?></td>
                                    <td class="d-none"><?php echo $q4_date;?></td>
                                    <td class="btn btn-group">
                                        <!-- EDIT -->
                                        <button type="button" class="btn btn-success editPlan mr-1 rounded">
                                            <i class="fa fa-edit" area-hidden="true"></i>
                                        </button>
                                        <!-- DELETE -->
                                        <form action="code.php" method="POST">  
                                            <input type="hidden" name="p_id" value="<?php echo $p_id;?>">  
                                            <button type="submit" name ="delPlan" class="btn btn-danger">
                                                <i class="fa fa-trash" area-hidden="true"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            <?php 
                            }
                        }    
                        ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-6">
            <div class="card shadow mb-5">
                <div class="card-header py-3">
                    <h5 class="m-0 font-weight-bold text-primary"> Expected Expense (<?php echo $currentMname?>)</h5>
                </div>
                <div class="card-body">
                    <div class="table table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <th>Code</th>
                                <th>Description</th>
                                <th>Amount</th>
                            </thead>
                            <tbody>
                    <?php 
                    if(mysqli_num_rows($q_cat_run)>0){ $tot_bgt=0; $catLabel=""; $actual ="";$actualData=""; $expData="";
                        while($rowc=mysqli_fetch_assoc($q_cat_run)){
                            $t_cat_id=$rowc['Transaction_Category_Id'];

                            $actual=getActualAmt($t_cat_id);
                            $actual=abs($actual);
                            $actualData.=$actual.",";

                            $t_desc=$rowc['Transaction_Category_Description'];
                            $t_code=$rowc['Transaction_Cat_Code'];
                            $catLabel.='"'.$t_desc.'",';
                            
                            $budget=getMonthExpAmt($t_cat_id);
                            $budget_output=number_format($budget,2);
                            $expData.=$budget.",";
                            
                            $tot_bgt=$tot_bgt+$budget;
                    ?>
                                <tr>
                                    <td><?php echo $t_code;?></td>
                                    <td>
                                        <form action="fp_exp_tbl.php" method="post">
                                            <input type="hidden" name="cat_id" value="<?php echo $t_cat_id;?>">
                                            <input type="hidden" name="cat_desc" value="<?php echo $t_desc;?>">
                                            <button class="btn btn-link" name="monthEx" type="submit">
                                                <?php echo $t_desc;?>
                                            </button>
                                        </form>
                                    </td>
                                    <td class="text-right"><?php echo $budget_output;?></td>
                                </tr>
                    <?php 
                        }
                        $tot_bgt=number_format($tot_bgt,2);
                        ?>
                            <tfoot>
                                <tr>
                                    <td></td>
                                    <td><span class="float-right font-weight-bold">Total:</span> </td>
                                    <td class="text-right"><?php echo $tot_bgt;?></td>
                                </tr>
                            </tfoot>
                        <?php
                    }
                    else{
                        echo 'No found accounts';
                    }
                    ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6">
            <div class="card shadow mb-5">
                <div class="card-header py-3">
                    <h5 class="m-0 font-weight-bold text-primary">Budget Overview</h5>
                </div>
                <div class="card-body">
                    <canvas id="myChart" width="400" height="200"></canvas>
                    <hr>
                </div>
            </div>
        </div>
    </div>
<?php 
if(isset($_POST['monthExp'])){
    $ccat_id=$_POST['cat_id'];
    $cat_name_chart=catName($ccat_id);
}
else{
    $cat_name_chart=""; $ccat_id=NULL;
}
$previousMonths=NULL;$balances=NULL; $balance_arr=[];
// Loop to generate the previous 5 months in ascending order
$actualData1=""; $expData1=""; 
for ($i =3; $i >= 0; $i--) {
    // Calculate the timestamp for the previous month
    $timestamp = strtotime("-$i months", strtotime("$currentYear-$currentMonth-01"));
    $month =date('m', $timestamp);
    $month_lbl=date('F', $timestamp);
    $yr =date('Y', $timestamp);
    $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $month, $yr);
    if($currentMonth==1){
        $currentYear=$currentYear--;
    }
    // Format the timestamp to get the month and year
    $from =date('Y-m-'.$daysInMonth, $timestamp);
    $previousMonth = date('Y-m-'.$daysInMonth, $timestamp);
    $previousMonths.='"'.$month_lbl.'",';
    $actualExp=actualMonth($month,$yr,$ccat_id);
    $actualData1.=$actualExp.',';
    $expAmt = ExpMonth($month,$yr,$ccat_id);
    $expData1.=$expAmt.",";
}
for ($j =0; $j <= 3; $j++) {
    $timestamp = strtotime("+$j months", strtotime("$currentYear-$currentMonth-01"));
    $month =date('m', $timestamp);
    $month_lbl=date('F', $timestamp);
    $yr =date('Y', $timestamp);
    $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $month, $yr);
    if($currentMonth==1){
        $currentYear=$currentYear--;
    }
    $from =date('Y-m-'.$daysInMonth, $timestamp);
    $previousMonth = date('Y-m-'.$daysInMonth, $timestamp);
    if($j==0){
    }
    else{
        $previousMonths.='"'.$month_lbl.'",';
        $actualExp=actualMonth($month,$yr,$ccat_id);
        $actualData1.=$actualExp.',';
        $expAmt = ExpMonth($month,$yr,$ccat_id);
        $expData1.=$expAmt.",";
    }
}
?>
<script>
    var catLabel1=[<?php echo $previousMonths;?>];
    var actualData1=[<?php echo $actualData1?>];
    var expData1=[<?php echo $expData1?>];
</script>
    <?php
?>
    <div class="row">
        <div class="col-12">
            <div class="card shadow mb-5">
                <div class="card-header py-3">
                    <h5 class="m-0 font-weight-bold text-primary">Monthly Overview</h5>
                </div>
                <div class="card-body">
                     <form action="fp_exp_cat.php" method="post">
                    
                    <div class="row">
                        <div class="col-2">
                            <label for="">Category</label>
                            <select name="cat_id" id="cat_opt1" class="form-control"></select>
                        </div>
                        <div class="col-2">
                            <label class="invisible">1</label><br>
                            <button class="btn btn-warning" type="submit" name="monthExp">Submit</button>
                        </div>
                    </div>
                    </form>
                    <div class="row">
                        <div class="col-12">
                            <h4 class="text-center text-primary"><?php echo $cat_name_chart;?></h4>
                        </div>
                    </div>
                    <!-- <div id="expChart"></div> -->
                    <canvas id="expReport" height="80"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    var catLabel=[<?php echo $catLabel;?>];
    var actualData=[<?php echo $actualData?>];
    var expData=[<?php echo $expData?>];
</script>
<!-- Modal Add payment plan -->
<div class="modal fade bd-example-modal-xl" id="addplan" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl" role="document">
    <div class="modal-content">
      <div class="modal-header">
          <!-- MODAL TITLE -->
        <h5 class="modal-title" id="exampleModalLabel"><i class="fa fa-clipboard" aria-hidden="true"></i> Add Payment Plan</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
    <form action="code.php" method="POST">
      <div class="modal-body">
        <!-- THE FORM -->
            <div class="form-row">
                <div class="col-4">
                    <label for="">Category</label>
                    <select name="cat_id" id="cat_opt" class="form-control "> </select>
                </div>
                <div class="col-4">
                    <label for="">Frequency</label>
                    <select name="freq" id="freq_opt" class="form-control">
                        <option value="Monthly">Monthly</option>
                        <option value="Quarterly">Quarterly</option>
                        <option value="Yearly">Yearly</option>
                    </select>
                </div>
                <div class="col-3 d-none " id="yr_class">
                    <label for="">Date of Payment</label>
                    <input type="date" name="y_date" class="form-control">
                </div>
            </div>
            <div class="form-row mt-4 d-none " id="q_class">
                <div class="col-3">
                    <label for="">1st Payment Date</label>
                    <input type="date" name="q1_date" class="form-control" value="<?php echo $q1_d;?>">
                </div>
                <div class="col-3">
                    <label for="">2nd Payment Date</label>
                    <input type="date" name="q2_date" class="form-control" value="<?php echo $q2_d;?>">
                </div>
                <div class="col-3">
                    <label for="">3rd Payment Date</label>
                    <input type="date" name="q3_date" class="form-control" value="<?php echo $q3_d;?>">
                </div>
                <div class="col-3">
                    <label for="">4th Payment Date</label>
                    <input type="date" name="q4_date" class="form-control" value="<?php echo $q4_d;?>">
                </div>
            </div>
            <div class="form-row mt-4">
                <div class="col-8">
                    <div class="form-group">
                        <label for="">Description: <i class="ml-3">i.e. Payroll, Subcontractor</i> </label> 
                        <input type="text" name="details" class="form-control">
                    </div>
                </div>
                <div class="col-3">
                    <label for="">Amount:</label>
                    <input type="decimal" name="amount" class="form-control">
                </div>
            </div>
        <!-- END FORM -->
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-times" aria-hidden="true"></i> Close</button>
        <button type="submit" name="addPlan" class="btn btn-success"><i class="fa fa-check" aria-hidden="true"></i> Save</button>
      </div>
      </form>
    </div>
  </div>
</div>
<!-- End Modal ADD payment plan -->
<!-- Modal Edit payment plan -->
<div class="modal fade bd-example-modal-xl" id="editPlan" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl" role="document">
    <div class="modal-content">
      <div class="modal-header">
          <!-- MODAL TITLE -->
        <h5 class="modal-title" id="exampleModalLabel"><i class="fa fa-clipboard" aria-hidden="true"></i> Edit Plan</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
    <form action="code.php" method="POST">
      <div class="modal-body">
        <!-- THE FORM -->
            <input type="hidden" id="eplan_id" name="p_id">
            <div class="form-row">
                <div class="col-4">
                    <label for="">Category</label>
                    <select name="cat_id" id="eCatId" class="form-control "> </select>
                </div>
                <div class="col-4">
                    <label for="">Frequency</label>
                    <select name="freq" id="efreq_opt" class="form-control">
                        <option value="Monthly">Monthly</option>
                        <option value="Quarterly">Quarterly</option>
                        <option value="Yearly">Yearly</option>
                    </select>
                </div>
                <div class="col-3 d-none " id="eyr_class">
                    <label for="">Date of Payment</label>
                    <input type="date" name="y_date" id="eYr" class="form-control">
                </div>
            </div>
            <div class="form-row mt-4 d-none " id="eq_class">
                <div class="col-3">
                    <label for="">1st Payment Date</label>
                    <input type="date" name="q1_date" class="form-control" id="eQ1" value="<?php echo $q1_d;?>">
                </div>
                <div class="col-3">
                    <label for="">2nd Payment Date</label>
                    <input type="date" name="q2_date" class="form-control" id="eQ2" value="<?php echo $q2_d;?>">
                </div>
                <div class="col-3">
                    <label for="">3rd Payment Date</label>
                    <input type="date" name="q3_date" class="form-control" id="eQ3" value="<?php echo $q3_d;?>">
                </div>
                <div class="col-3">
                    <label for="">4th Payment Date</label>
                    <input type="date" name="q4_date" class="form-control" id="eQ4" value="<?php echo $q4_d;?>">
                </div>
            </div>
            <div class="form-row mt-4">
                <div class="col-8">
                    <div class="form-group">
                        <label for="">Description: <i class="ml-3">i.e. Payroll, Subcontractor</i> </label> 
                        <input type="text" name="details" id="eDesc" class="form-control">
                    </div>
                </div>
                <div class="col-3">
                    <label for="">Amount:</label>
                    <input type="decimal" name="amount" id="eAmt" class="form-control" >
                </div>
            </div>
        <!-- END FORM -->
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-times" aria-hidden="true"></i> Close</button>
        <button type="submit" name="editPlan" class="btn btn-success"><i class="fa fa-check" aria-hidden="true"></i> Save</button>
      </div>
      </form>
    </div>
  </div>
</div>
<!-- End Modal edit payment plan -->
<script>
$(document).ready(function(){
    var month_opt="";
    $.ajax({
    url:'options.php',
    method: 'POST',
    data:{'cat_opt': month_opt},
    success:function(data){
            $(document).find('#cat_opt').html(data).change();
            data = "<option value=''>All</option>"+data;
            $(document).find('#cat_opt1').html(data).change();
            $(document).find('#eCatId').html(data).change();
        }
    });
});
$(document).ready(function(){
    $('#freq_opt').on('change', function () {
        var freq=$(this).val();
        if(freq=="Yearly"){
            $('#yr_class').removeClass('d-none');
            $('#q_class').addClass('d-none');
        }
        if(freq=="Quarterly"){
            $('#q_class').removeClass('d-none');
            $('#yr_class').addClass('d-none');
        }
        if(freq=="Monthly"){
            $('#yr_class').addClass('d-none');
            $('#q_class').addClass('d-none');
        }
    });
});
$(document).ready(function(){
    $('#efreq_opt').on('change', function () {
        var freq=$(this).val();
        if(freq=="Yearly"){
            $('#eyr_class').removeClass('d-none');
            $('#eq_class').addClass('d-none');
        }
        if(freq=="Quarterly"){
            $('#eq_class').removeClass('d-none');
            $('#eyr_class').addClass('d-none');
        }
        if(freq=="Monthly"){
            $('#eyr_class').addClass('d-none');
            $('#eq_class').addClass('d-none');
        }
    });
});
$(document).ready(function () {
    $(document).on('click', '.editPlan', function() {
        $('#editPlan').modal('show');
        $tr = $(this).closest('tr');
        var data = $tr.children("td").map(function(){
            return $(this).text();
        }).get();
       $('#eplan_id').val(data[0]);
       $('#eCatId').val(data[5]);
       $('#efreq_opt').val(data[3]);
       $('#eYr').val(data[6]);
       $('#eQ1').val(data[7]);
       $('#eQ2').val(data[8]);
       $('#eQ3').val(data[9]);
       $('#eQ4').val(data[10]);
       $('#eDesc').val(data[2]);
       $('#eAmt').val(data[4]);
       if(data[3]=="Yearly"){
            $('#eyr_class').removeClass('d-none');
            $('#eq_class').addClass('d-none');
        }
        if(data[3]=="Quarterly"){
            $('#eq_class').removeClass('d-none');
            $('#eyr_class').addClass('d-none');
        }
        if(data[3]=="Monthly"){
            $('#eyr_class').addClass('d-none');
            $('#eq_class').addClass('d-none');
        }
    });
});
</script>
<script src="test.js"></script>
<?php
include('../includes/scripts.php');
include('../includes/footer.php');
?>