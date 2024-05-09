<?php
include('security.php');
include('includes/header.php');
include('includes/navbar.php');
?>
<!-- Add Payslip Modal --> 
<div class="modal fade bd-example-modal-lg" id="addpayslip" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
          <!-- MODAL TITLE -->
        <h5 class="modal-title" id="exampleModalLabel"><i class="fa fa-file-text" aria-hidden="true"></i> Add Payslip</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

    <form action="code.php" method="POST">
      <div class="modal-body">
        <!-- THE FORM -->
        <?php
          $query = "SELECT DISTINCT employee.EMP_ID, employee.EMP_NO 
                    FROM employee
                    LEFT JOIN payslip on payslip.EMP_ID = employee.EMP_ID
                    WHERE EMP_STATUS = 1";
          $query_run = mysqli_query($connection, $query);
        ?>
            <div class="form-group">
              <label> Employee Number </label>
                <select name="empidp" class="form-control selectpicker" data-live-search="true" data-width="100%" required>
                <option></option>
                <option> Employee No </option>
                  <?php
                    while($row = mysqli_fetch_array($query_run))
                    {
                      ?>
                        <option name="empidp" value="<?php echo $row['EMP_ID']?>"><?php echo $row['EMP_NO'] ?> </option>
                      <?php
                    }     
                    // mysqli_close($connection);      
                  ?>        
            
            <div class="form-group">
                <label>  </label>
                <input type="hidden" class="form-control" placeholder="">                
            </div>

            <div class="form-row">
              <div class="form-group col-md-6">
                  <label> Date: </label>
                  <input type="date" name="date" class="form-control" required>
              </div>
              <div class="form-group col-md-6">
                  <label> Monthly Full Basic: </label>
                  <input type="number" step=".001" name="full_basic" class="form-control" placeholder="Enter Basic Salary" required>
              </div>
            </div>

<!-- FULL ALLOWANCE -->
<div class="table-responsive">
              <table class="table table-bordered" id="falwTbl">
                <tr>
                  <th width="47%">Full Allowance Description</th>
                  <th width="47%">Amount</th>
                  <th width="6%"></th>
                </tr>
                <tr>
                  <td><input width="47%" class="form-control no-border falw_name" name="falw_name[]"  type="text"></td>
                  <td><input width="47%" class="form-control no-border falw_amt" name="falw_amt[]" step=".001" type="number"></td>
                  <td></td>
                </tr>
              </table>
              <div align="right">
              <button type="button" name="fadd" id="faddAl" class="btn btn-success btn-xs">+</button>
              </div>
          </div>

          <div class="form-row">
            <div class="form-group col-md-12">
              <label> Computed Basic: </label>
              <input type="number" step=".001" name="basic" class="form-control" placeholder="Enter Basic Salary" required>
            </div>
          </div>
<!-- ALLOWANCE -->
        <div class="table-responsive">
            <table class="table table-bordered" id="alwTbl">
              <tr>
                <th width="47%">Allowance Description</th>
                <th width="47%">Amount</th>
                <th width="6%"></th>
              </tr>
              <tr>
                <td><input width="47%" class="form-control no-border alw_name" name="alw_name[]"  type="text"></td>
                <td><input width="47%" class="form-control no-border alw_amt" name="alw_amt[]" step=".001" type="number"></td>
                <td></td>
              </tr>
            </table>
            <div align="right">
            <button type="button" name="add" id="addAl" class="btn btn-success btn-xs">+</button>
            </div>
        </div>
<!-- DEDUCTIONS -->
            <div class="table-responsive">
              <table class="table table-bordered" id="dTbl">
                <tr>
                  <th width="47%">Deduction Description</th>
                  <th width="47%">Amount</th>
                  <th width="6%"></th>
                </tr>
                <tr>
                  <td><input width="47%" class="form-control no-border deduc_name" name="deduc_name[]" type="text"></td>
                  <td><input width="47%" class="form-control no-border deduc_amt" name="deduc_amt[]" step=".001" type="number"></td>
                  <td></td>
                </tr>
              </table>
              <div align="right">
              <button type="button" name="add" id="addD" class="btn btn-success btn-xs">+</button>
              </div>
          </div>

<!-- ADDITIONS -->
            <div class="table-responsive">
              <table class="table table-bordered" id="addTbl">
                <tr>
                  <th width="47%">Additional Description</th>
                  <th width="47%">Amount</th>
                  <th width="6%"></th>
                </tr>
                <tr>
                  <td><input width="47%" class="form-control no-border add_name" name="add_name[]" type="text"></td>
                  <td><input width="47%" class="form-control no-border add_amt" name="add_amt[]" step=".001" type="number"></td>
                  <td></td>
                </tr>
              </table>
              <div align="right">
              <button type="button" name="add" id="addAdd" class="btn btn-success btn-xs">+</button>
              </div>
          </div>

            <div class="form-row">
              <div class="form-group col-md-3">
                  <label> Normal OT Hours: </label>
                  <input type="number" step=".01" name="norm_ot_hrs" class="form-control" placeholder="OT Hours">
              </div>
              <div class="form-group col-md-3">
                  <label> Amount </label>
                  <input type="number" step=".001" name="norm_ot_amt" class="form-control" placeholder="Amount">
              </div>
              <div class="form-group col-md-3">
                  <label> Normal Holiday Hours: </label>
                  <input type="number" step=".01" name="norm_hol_hrs" class="form-control" placeholder="Holiday Hours">
              </div>
              <div class="form-group col-md-3">
                  <label> Amount </label>
                  <input type="number" step=".001" name="norm_hol_amt" class="form-control" placeholder="Amount">
              </div>
            </div>

            <div class="form-row">
              <div class="form-group col-md-3">
                <label> Special Hours: </label>
                <input type="number" step=".01" name="sp_hrs" class="form-control" placeholder="Enter Special Hours">
              </div>
              <div class="form-group col-md-3">
                <label> Amount </label>
                <input type="number" step=".001" name="sp_amt" class="form-control" placeholder="Enter Special Hours Amount">
              </div>
              <div class="form-group col-md-3">
                <label> Bonus Hours: </label>
                <input type="number" step=".0001" name="bns_hrs" class="form-control" placeholder="Enter Bonus Hours">
              </div>
              <div class="form-group col-md-3">
                <label> Bonus Amount </label>
                <input type="number" step=".0001" name="bns_amt" class="form-control" placeholder="Enter Bonus Amt">
              </div>
            </div>

            <div class="form-row">
              <div class="form-group col-md-6">
                  <label> Absent Days: </label>
                  <input type="number" step=".01" name="ab_days" class="form-control" placeholder="Enter Absent Days">
              </div>
              <div class="form-group col-md-6">
                  <label> Leave Days: </label>
                  <input type="number" step=".01" name="l_days" class="form-control" placeholder="Enter Leave Days">
              </div>
            </div>

            <input type="hidden" name="pstatus" value="1">
        <!-- END FORM -->
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-times" aria-hidden="true"></i> Close</button>
        <button type="submit" name="addp" id="addp" class="btn btn-success"><i class="fa fa-check" aria-hidden="true"></i> Save</button>
      </div>
      </form>
    </div>
  </div>
</div>
<!-- End Add Employee Modal -->

<!-- start table -->
        <!-- Begin Page Content -->
        <div class="container-fluid">
          <!-- Page Heading -->
          
          <!-- DataTales Example -->
          <div class="card shadow mb-4">
            <div class="card-header py-3">
              <!-- <h6 class="m-0 font-weight-bold text-primary">Manage Employee</h6> -->
              <h5 class="m-0 font-weight-bold  text-primary">Manage Payslip
                <!-- BUTTON -->
                  <button type="button" class="btn btn-primary float-right" data-toggle="modal" data-target="#addpayslip">
                      <i class="fa fa-plus" aria-hidden="true"></i>
                      Add Payslip
                  </button>
              </h5>
            </div>
            <div class="card-body">
                <!-- TABLE -->
              <div class="table-responsive">
                  <!-- DATE RANGE -->
                <form name="frmSearch" action="payslip.php" method="POST">
                  <p class="search_input">
                    <div class="form-row">
                      <div class="form-group col-md-4 mt-n3">
                        <label>From</label>
                          <input type="date" placeholder="From Date" name="from" id="post_at" class="form-control">
                      </div>
                      <div class="form-group col-md-4 mt-n3">
                        <label>To</label>
                          <input type="date" placeholder="To Date" name="to" id="post_at_to_date" class="form-control">
                      </div>
                      <div class="form-group col-md-4 pt-3">
                        <label> </label>
                        <input class="btn btn-warning" type="submit" name="search" value="Filter">
                      </div>
                    </div>
                  </p>
                </form>
              <?php
                
                if(isset($_POST['search'])){
                  $from = $_POST['from'];
                  $to = $_POST['to'];
            
                  $query ="SELECT * FROM `payslip` LEFT JOIN employee on payslip.EMP_ID = employee.EMP_ID WHERE P_STATUS=1 AND EMP_STATUS=1 AND P_DATE BETWEEN '".$from."' AND '".$to."' ORDER BY P_DATE ASC";
                  $search_result=filterTable($query);}
                else{
                  $query = "SELECT PAYSLIP_ID,EMP_NO, EMP_FNAME, EMP_LNAME, EMP_MNAME, EMP_SNAME, P_BASIC_SALARY, P_NORM_OTAMT, P_HOL_OTAMT, P_SP_AMT, P_BNS_AMT, P_DATE FROM `payslip` LEFT JOIN employee on payslip.EMP_ID = employee.EMP_ID WHERE P_STATUS=1 AND EMP_STATUS=1 ORDER BY P_DATE ASC";
                  $search_result=filterTable($query);}
                function filterTable($query){
                  /////
                  include('dbconfig.php');          
                  $filter_Result = mysqli_query($connection, $query);
                  return $filter_Result;}              
              ?>
                <table class="table table-bordered table-striped" id="dataTable" width="100%" cellspacing="0">
                  <thead>
                    <tr>
                      <th class="d-none">Payslip ID</th>
                      <th>Employee No.</th>
                      <th>Employee Name</th>
                      <th>Date</th>
                      <th>Net Salary</th>
                      <th>Action</th>
                    </tr>
                  </thead>
                  <tfoot>
                    <tr>
                      <th class="d-none">Payslip ID</th>
                      <th>Employee No.</th>
                      <th>Employee Name</th>
                      <th>Date</th>
                      <th>Net Salary</th>
                      <th>Action</th>
                    </tr>
                  </tfoot>
                  <tbody>
                  <?php
                if(mysqli_num_rows($search_result)>0)
                {
                    while($row = mysqli_fetch_assoc($search_result))
                    {
                        ?>
                <tr>
                    <td class="d-none"><?php echo $row['PAYSLIP_ID']; $p_id=$row['PAYSLIP_ID']?></td>                 
                    <td><?php echo $row['EMP_NO']; ?></td>
                    <td><?php echo ucwords($row['EMP_FNAME']); ?><?php echo ' '.ucwords($row ['EMP_MNAME']); ?><?php echo ' '.ucwords($row['EMP_LNAME']); ?><?php echo ' '.ucwords($row['EMP_SNAME']); ?></td>
                    <td><?php echo date('M-Y',strtotime($row['P_DATE'])); ?></td>

                  <?php 
                  $query1="SELECT * FROM allowance LEFT JOIN payslip on payslip.PAYSLIP_ID = allowance.PAYSLIP_ID WHERE payslip.PAYSLIP_ID = '$p_id'";
                  /////
                  // $connection = mysqli_connect("localhost","root","","emt");
                  $al_q1 = mysqli_query($connection, $query1); 
                  $al_total=0; $d_total=0; $bonus_ot=0; $ad_total=0;

                  if(mysqli_num_rows($al_q1)>0)
                    {
                      while($row1 = mysqli_fetch_assoc($al_q1))
                      {
                        $al_total = $al_total + $row1['ALW_AMT'];
                      }
                    }
                    else{}

                  $query2 = "SELECT * FROM deduction LEFT JOIN payslip on payslip.PAYSLIP_ID = deduction.PAYSLIP_ID WHERE payslip.PAYSLIP_ID = '$p_id'";
                  $d_q = mysqli_query($connection,$query2);
                  if(mysqli_num_rows($d_q)>0)
                  {
                    while($row2 = mysqli_fetch_assoc($d_q))
                    {
                      $d_total = $d_total + $row2['DEDUC_AMT'];
                    }
                  }
                  
                  $query3 = "SELECT * FROM additional LEFT JOIN payslip on payslip.PAYSLIP_ID = additional.PAYSLIP_ID WHERE payslip.PAYSLIP_ID = '$p_id'";
                  $ad_q = mysqli_query($connection,$query3);
                  if(mysqli_num_rows($ad_q)>0)
                  {
                    while($row3 = mysqli_fetch_assoc($ad_q))
                    {
                      $ad_total = $ad_total + $row3['ADD_AMT'];
                    }
                  }

                  else{}
                    $basic=$row['P_BASIC_SALARY'];
                    $bonus_ot=$row['P_NORM_OTAMT']+$row['P_HOL_OTAMT']+$row['P_SP_AMT']+$row['P_BNS_AMT'];
                    $netsal=$al_total+$basic-$d_total+$bonus_ot + $ad_total;
                  ?>
                    <td><?php echo number_format(round($netsal),3,'.',','); ?></td>
                    <td class="btn-group">
                      
                      <form action="edit_payslip.php" method="POST">
                        <input type="hidden" name="p_id" value="<?php echo $row['PAYSLIP_ID'];?>">  
                        <button type="submit" name="edit_p" class="btn btn-success">
                          <i class="fa fa-pencil" aria-hidden="true"></i>
                        </button>
                      </form>
                          <button type="submit" name="view" class="btn btn-info btn-xs view_data" id="<?php echo $row['PAYSLIP_ID'];?>">
                              <i class="fa fa-eye"></i>
                          </button>
                      <form action="view_payslip.php" method="POST">
                        <input type="hidden" name="pv_id" value="<?php echo $row['PAYSLIP_ID'];?>">
                          <button type="submit" name="view_p" class="btn btn-warning" onclick="this.form.target='_blank';return true;">
                              <i class="fa fa-file-text"></i>
                          </button>
                      </form>
      
                      <form action="code.php" method="POST">
                        <input type="hidden" name="del_pid" value="<?php echo $row['PAYSLIP_ID'];?>">
                          <button type="submit" name="delete_payslip" class="btn btn-danger">
                              <i class="fa fa-trash"></i>
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
          
        </div>
</div>

<!-- Modal --> 
<div class="modal fade bd-example-modal-xl" id="dataModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel"></h5>
      <div class="modal-body" id="payslip_detail">
        <div class="modal-footer">  
              <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>  
        </div>  
    </div>
  </div>
</div>
              </div>
              </div>

<script>
$(document).ready(function(){
 var countF = 1;
 $('#faddAl').click(function(){
  countF = countF + 1;
  var html_code = "<tr id='row"+countF+"'>";
   html_code += "<td><input width='47%' class='form-control no-border falw_name' name='falw_name[]' type='text'></td>";
   html_code += "<td><input width='47%' class='form-control no-border falw_amt' name='falw_amt[]' step='.001' type='number'></td>";
   html_code += "<td><button type='button' name='remove' data-row='row"+countF+"' class='btn btn-danger btn-xs remove'>-</button></td>";
   html_code += "</tr>";
   $('#falwTbl').append(html_code);
 });
 
 $('#addAl').click(function(){
  var count = count + 1;
  var html_code = "<tr id='row"+count+"'>";
   html_code += "<td><input width='47%' class='form-control no-border alw_name' name='alw_name[]' type='text'></td>";
   html_code += "<td><input width='47%' class='form-control no-border alw_amt' name='alw_amt[]' step='.001' type='number'></td>";
   html_code += "<td><button type='button' name='remove' data-row='row"+count+"' class='btn btn-danger btn-xs remove'>-</button></td>";
   html_code += "</tr>";
   $('#alwTbl').append(html_code);
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
   html_code += "<td><input width='47%' class='form-control no-border add_name' name ='add_name' type='text'></td>";
   html_code += "<td><input width='47%' class='form-control no-border add_amt' name='add_amt' step='.001' type='number'></td>";
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