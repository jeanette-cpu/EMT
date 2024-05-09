<?php
include('security.php');
include('includes/header.php');
include('includes/user_navbar.php');
error_reporting(0);
?>
<!-- start table -->
        <!-- Begin Page Content -->
        <div class="container-fluid">
          <!-- Page Heading -->
          
          <!-- DataTales Example -->
          <div class="card shadow mb-4">
            <div class="card-header py-3">
              <!-- <h6 class="m-0 font-weight-bold text-primary">Manage Employee</h6> -->
              <h5 class="m-0 font-weight-bold  text-primary">Payslip</h5>
            </div>
            <div class="card-body">
                <!-- TABLE -->
              <div class="table-responsive">
                  <!-- DATE RANGE -->
                <form name="frmSearch" action="upayslip.php" method="POST">
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
              $username=$_SESSION['USERNAME'];
              $user=$username;
                if(isset($_POST['search']))
                {
                  $from = $_POST['from'];
                  $to = $_POST['to'];
            
                  $query ="SELECT * 
                           FROM payslip
                           LEFT JOIN employee on payslip.EMP_ID = employee.EMP_ID
                           LEFT JOIN users on employee.USER_ID = users.USER_ID
                           WHERE USERNAME='".$user."' AND P_STATUS=1 AND EMP_STATUS=1 AND P_DATE BETWEEN '".$from."' AND '".$to."' ";
                  $search_result=filterTable($query);
                }
                else
                {
                  $query = "SELECT * 
                            FROM payslip
                            LEFT JOIN employee on payslip.EMP_ID = employee.EMP_ID
                            LEFT JOIN users on employee.USER_ID = users.USER_ID
                            WHERE USERNAME='".$user."' AND P_STATUS=1 AND EMP_STATUS=1
                            ORDER BY payslip.PAYSLIP_ID asc
                            ";
                  $search_result=filterTable($query);
                }
                function filterTable($query)
                {
                  include('security.php');           
                  $filter_Result = mysqli_query($connection, $query);
                  return $filter_Result;
                }              
                
              ?>
                <table class="table table-bordered table-striped" id="pTable" width="100%" cellspacing="0">
                  <thead>
                    <tr>
                      <th class="d-none">Payslip ID</th>
                      <!-- <th>User Email</th> -->
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
                      <!-- <th>User Email</th> -->
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
                    <td class="d-none"><?php echo $row['PAYSLIP_ID']; $p_id=$row['PAYSLIP_ID'] ?></td>                 
                    <td><?php echo $row['EMP_NO']; ?></td>
                    <td><?php echo ucwords($row['EMP_FNAME']); ?><?php echo ' '.ucwords($row ['EMP_MNAME']); ?><?php echo ' '.ucwords($row['EMP_LNAME']); ?><?php echo ' '.ucwords($row['EMP_SNAME']); ?></td>
                    <td><?php echo date('M-Y',strtotime($row['P_DATE'])); ?></td>
                    
                    <?php 
                  $query1="SELECT * FROM allowance LEFT JOIN payslip on payslip.PAYSLIP_ID = allowance.PAYSLIP_ID WHERE payslip.PAYSLIP_ID = '$p_id'";
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
                    $netsal=$al_total+$basic-$d_total+$bonus_ot+$ad_total;
                  ?>
                    <td><?php echo number_format(round($netsal),3,'.',','); ?></td>

                    <td class="btn-group">

                          <button type="submit" name="view" class="btn btn-info btn-xs view_data" id="<?php echo $row['PAYSLIP_ID'];?>">
                              <i class="fa fa-eye"></i>
                          </button>
                      <!-- </form> -->
                      <form action="view_payslip.php" method="POST">
                        <input type="hidden" name="pv_id" value="<?php echo $row['PAYSLIP_ID'];?>">
                          <button type="submit" name="view_p" class="btn btn-warning" onclick="this.form.target='_blank';return true;">
                              <i class="fa fa-file-text"></i>
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
    $('#pTable').DataTable({
        pageLength: 10,
        "searching": true,
        "aaSorting": [[ 0, 'desc' ]]
    });
});
</script>
<?php
include('includes/scripts.php');
include('includes/footer.php');
?>