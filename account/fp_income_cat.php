<?php
include('../security.php');
include('../includes/header.php'); 
include('../includes/acc_navbar.php');
include('accQuery.php'); 
$currentMname = date('F');
?>
<div class="container-fluid">
    <div class="row d-none">
        <div class="col-12">
            <div class="card shadow mb-5">
                <div class="card-header py-3">
                    <h5 class="m-0 font-weight-bold text-primary">Expected Profit
                        <button type="button" class="btn btn-sm btn-primary float-right mr-1 " data-toggle="modal" data-target="#addProfit">
                            <i class="fa fa-plus" aria-hidden="true"></i> Add Profit
                        </button>
                    </h5>
                </div>
                <div class="card-body">
                    <div class="table table-responsive">
                        <table class="table table-bordered" id="dataTable">
                            <thead>
                                <th class="d-none"></th>
                                <th>Category</th>
                                <th>Amount</th>
                                <th>Action</th>
                            </thead>
                            <tbody>
                        <?php 
                        if(mysqli_num_rows($q_plan_run)>0){
                            while($row_p=mysqli_fetch_assoc($q_plan_run)){
                                $p_id=$row_p['Plan_Id'];
                            ?>
                                <tr>
                                    <td class="d-none"><?php echo $p_id;?></td>
                                    <td></td>
                                    <td></td>
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
                    <h5 class="m-0 font-weight-bold text-primary"> Expected Income (<?php echo $currentMname?>)</h5>
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
                    if(mysqli_num_rows($q_cat_run)>0){ $tot_income=0; $catLabel=""; $actual ="";$actualData=""; $expData="";
                        while($rowc=mysqli_fetch_assoc($q_cat_run)){
                            $t_cat_id=$rowc['Transaction_Category_Id'];

                            $actual=getActualIncome($t_cat_id);
                            $actual=abs($actual);
                            $actualData.=$actual.",";

                            $t_desc=$rowc['Transaction_Category_Description'];
                            $t_code=$rowc['Transaction_Cat_Code'];
                            $catLabel.='"'.$t_desc.'",';
                            
                            $income=getMonthExpProfit($t_cat_id);
                            $income_output=number_format($income,2);
                            $expData.=$income.",";
                            
                            $tot_income=$tot_income+$income;
                    ?>
                                <tr>
                                    <td><?php echo $t_code;?></td>
                                    <td>
                                        <form action="fp_income_tbl.php" method="post">
                                            <input type="hidden" name="cat_id" value="<?php echo $t_cat_id;?>">
                                            <input type="hidden" name="cat_desc" value="<?php echo $t_desc;?>">
                                            <button class="btn btn-link" name="monthEx" type="submit">
                                                <?php echo $t_desc;?>
                                            </button>
                                        </form>
                                    </td>
                                    <td class="text-right"><?php echo $income_output;?></td>
                                </tr>
                    <?php 
                        }
                        $tot_income=number_format($tot_income,2);
                        ?>
                            <tfoot>
                                <tr>
                                    <td></td>
                                    <td><span class="float-right font-weight-bold">Total:</span> </td>
                                    <td class="text-right"><?php echo $tot_income;?></td>
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
<script>
    var catLabel=[<?php echo $catLabel;?>];
    var actualData=[<?php echo $actualData?>];
    var expData=[<?php echo $expData?>];
</script>
        <div class="col-6">
            <div class="card shadow mb-5">
                <div class="card-header py-3">
                    <h5 class="m-0 font-weight-bold text-primary">Overview</h5>
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
    $actualExp=actualMonthInc($month,$yr,$ccat_id);
    $actualData1.=$actualExp.',';
    $expAmt = incomeMonth($month,$yr,$ccat_id);
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
        $actualExp=actualMonthInc($month,$yr,$ccat_id);
        $actualData1.=$actualExp.',';
        $expAmt = incomeMonth($month,$yr,$ccat_id);
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
                     <form action="fp_income_cat.php" method="post">
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
<!-- Modal Add payment plan -->
<div class="modal fade bd-example-modal-xl" id="addProfit" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
                </div>" id="yr_class">
                <div class="col-3 d-none 
                    <label for="">Date of Payment</label>
                    <input type="date" name="y_date" class="form-control">
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
        <button type="submit" name="addProfit" class="btn btn-success"><i class="fa fa-check" aria-hidden="true"></i> Save</button>
      </div>
      </form>
    </div>
  </div>
</div>
<!-- End Modal ADD expected Profit/payment -->
<script src="test.js"></script>
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
</script>
<?php
include('../includes/scripts.php');
include('../includes/footer.php');
?>