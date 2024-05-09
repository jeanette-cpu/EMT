<?php
include('security.php');
include('includes/header.php');
include('includes/user_navbar.php');
?>
<div class="container-fluid">
    <div class="card shadow mb-5">
        <div class="card-header py-3">
            <h5 class="m-0 font-weight-bold  text-primary">Time Sheet</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
            <?php 
                $username=$_SESSION['USERNAME'];
                $query="SELECT * FROM time_sheet 
                LEFT JOIN employee on employee.EMP_ID = time_sheet.EMP_ID 
                LEFT JOIN users on employee.USER_ID = users.USER_ID 
                WHERE EMP_STATUS=1 AND USERNAME='$username' 
                GROUP BY MONTH(TS_DATE)";
                $query_run = mysqli_query($connection, $query)
            ?>
                <table class="table table-bordered table-striped" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <th>Employee No.</th>
                        <th>Name</th>
                        <th>Date</th>
                        <th>Action</th>
                    </thead>
                    <tbody>
                    <?php
                        if(mysqli_num_rows($query_run)>0)
                        {
                            while($row = mysqli_fetch_assoc($query_run))
                            {
                                ?>
                        <tr>
                            <th><?php echo $row['EMP_NO'] ?></th>
                            <th><?php echo $row['EMP_FNAME'].' ' ?><?php echo $row['EMP_LNAME'] ?></th>
                            <td><?php echo date('F'.' '.'Y', strtotime($row['TS_DATE'])); ?></td>
                            <td class="btn-group">
                                <!-- VIEW -->
                                
                                    <input type="hidden" id="emp_id_m" value="<?php echo $row['EMP_ID'] ?>">
                                    <input type="hidden" id="emp_no_m" value="<?php echo $row['EMP_NO'] ?>">
                                    <input type="hidden" id="month_m" value="<?php echo date('m', strtotime($row['TS_DATE'])) ?>">
                                    <input type="hidden" id="year_m" value="<?php echo date('Y', strtotime($row['TS_DATE'])); ?>">
                                    
                                    <button type="submit" name="view_timesheet" id="view_timesheet" class="btn btn-info btn-xs view_timesheet">
                                        <i class="fa fa-eye"></i>
                                    </button> 
                               

                                <!-- PRINT -->
                                <form action="timesheet_print.php" method="POST">
                                    <input type="hidden" name="emp_id" value="<?php echo $row['EMP_ID'] ?>">
                                    <input type="hidden" name="emp_no" value="<?php echo $row['EMP_NO'] ?>">
                                    <input type="hidden" name="month" value="<?php echo date('m', strtotime($row['TS_DATE'])) ?>">
                                    <input type="hidden" name="year" value="<?php echo date('Y', strtotime($row['TS_DATE'])); ?>">
                                    
                                    <button type="submit" name="ts_print" class="btn btn-warning" onclick="this.form.target='_blank';return true;">
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
                    <tfoot>
                        <tr>
                            <th>Employee No.</th>
                            <th>Name</th>
                            <th>Date</th>
                            <th>Action</th>
                        </tr>
                    </tfoot>
                </table>
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
<?php
include('includes/scripts.php');
include('includes/footer.php');
?>