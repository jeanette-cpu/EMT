<?php
include('security.php');
include('includes/header.php');
include('includes/navbar.php');
?>

<!-- Modal Add Timesheet -->
<div class="modal fade bd-example-modal-lg" id="addTimesheet" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
          <!-- MODAL TITLE -->
        <h5 class="modal-title" id="exampleModalLabel"><i class="fa fa-file" aria-hidden="true"></i> Add Timesheet</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

    <form action="timesheet_queries.php" method="POST">
      <div class="modal-body">
        <!-- THE FORM -->
        <?php
          // $connection = mysqli_connect("localhost","root","","emt");
          $query = "SELECT DISTINCT employee.EMP_ID, employee.EMP_NO FROM employee WHERE EMP_STATUS = 1 ";
          $query_run = mysqli_query($connection, $query);
        ?>
            
        <div class="form-group">
            <label> Employee Number </label>
                <select name="empid" class="form-control selectpicker" data-live-search="true" width="100%" required>
                <option></option>
                <option> Employee No </option>
                <?php
                    while($row = mysqli_fetch_array($query_run))
                    {
                    ?>
                        <option name="empid" value="<?php echo $row['EMP_ID']?>"><?php echo $row['EMP_NO'] ?> </option>
                    <?php
                    }     
                    // mysqli_close($connection);      
                ?>        
        </div>
        <div>
            <label>  </label>
            <input type="hidden" class="form-control" placeholder="">                
        </div>    
        <div class="form-row">
            <div class="form-group col-md-6">
                <label> Date </label>
                <input type="date" name="tsDate" class="form-control" required>
            </div>    
            <div class="form-group col-md-6">
                <label> Status </label>
                <select name="tsStatus" class="form-control selectpicker" data-width="100%" required>
                <option></option>
                <option>Present</option>
                <option>Week Off</option>
                <option>Leave</option>
            </div>
        </div>
        <div class="form-group">
            <label>  </label>
            <input type="hidden" class="form-control" placeholder="">                
        </div></div>   
        <div class="form-row">    
            <div class="form-group col-md-6">
                <label>Time In</label>
                <input type="time" name="tsTimeIn" class="form-control" required>
            </div>
            <div class="form-group col-md-6">
                <label>Time Out</label>
                <input type="time" name="tsTimeOut" class="form-control" required>
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-md-3">
                <label>Hours</label>
                <input type="number" step=".01" name="tsHrs" class="form-control" required>
            </div>
            <div class="form-group col-md-2">
                <label>OT(Hrs)</label>
                <input type="number" step=".01" name="tsOtHrs" class="form-control">
            </div>
            <div class="form-group col-md-2">
                <label>H.OT(Hrs)</label>
                <input type="number" step=".01" name="tsHOtHrs" class="form-control">
            </div>
            <div class="form-group col-md-2">
                <label>Bonus (Hrs)</label>
                <input type="number" step=".01" name="tsBnsHrs" class="form-control">
            </div>
            <div class="form-group col-md-2">
                <label>Special (Hrs)</label>
                <input type="number" step=".01" name="tsSpHrs" class="form-control">
            </div>
        </div>
        <div class="form-group">
            <label>Job Name</label>
            <input type="text" name="tsJobName" class="form-control" placeholder="Enter Job Name" maxlength="25" required>
        </div>         
        <!-- END FORM -->
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-times" aria-hidden="true"></i> Close</button>
        <button type="submit" name="addTimesheet" class="btn btn-success"><i class="fa fa-check" aria-hidden="true"></i> Save</button>
      </div>
      </form>
    </div>
  </div>
</div>
<!-- End Modal Add Timesheet -->

<div class="container-fluid">
    <div class="card shadow mb-5">
        <div class="card-header py-3">
            <h5 class="m-0 font-weight-bold  text-primary">Timesheet
            <!-- BUTTON -->
            <button type="button" class="btn btn-primary float-right" data-toggle="modal" data-target="#addTimesheet">
                <i class="fa fa-plus" aria-hidden="true"></i>
                Add Timesheet
            </button>
            </h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
            <?php 
                $query="SELECT * FROM time_sheet 
                LEFT JOIN employee on employee.EMP_ID = time_sheet.EMP_ID 
                WHERE EMP_STATUS=1 
                GROUP BY employee.EMP_ID, MONTH(TS_DATE)
                ORDER BY time_sheet.TS_DATE ASC";
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
                                <!-- EDIT -->
                                <form action="timesheet_edit.php" method="POST">
                                    <input type="hidden" name="emp_id" value="<?php echo $row['EMP_ID'] ?>">
                                    <input type="hidden" name="month" value="<?php echo date('m', strtotime($row['TS_DATE'])) ?>">
                                    <input type="hidden" name="year" value="<?php echo date('Y', strtotime($row['TS_DATE'])); ?>">
                                    <button type="submit" name="edit_ts" class="btn btn-success">
                                        <i class="fa fa-pencil" aria-hidden="true"></i>
                                    </button>
                                </form>
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
                                <!-- DELETE -->
                                <form action="timesheet_queries.php" method="POST">
                                    <input type="hidden" name="emp_no" value="<?php echo $row['EMP_NO'] ?>">
                                    <input type="hidden" name="month" value="<?php echo date('m', strtotime($row['TS_DATE'])) ?>">
                                    <input type="hidden" name="year" value="<?php echo date('Y', strtotime($row['TS_DATE'])); ?>">
                                    <button type="submit" name="ts_delete" class="btn btn-danger">
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
    $('#dataTable').dataTable( {
  "searching": true
} );
</script>
<?php
include('includes/scripts.php');
include('includes/footer.php');
?>