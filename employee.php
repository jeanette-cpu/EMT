<?php
include('security.php');
include('includes/header.php');
include('includes/navbar.php');
?>

<!-- ADD EMPLOYEE MODAL -->
<div class="modal fade bd-example-modal-xl" id="adduser" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
          <!-- MODAL TITLE -->
        <h5 class="modal-title" id="exampleModalLabel"><i class="fa fa-user" aria-hidden="true">  </i>    Add Employee</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <form action="code.php" method="POST">
      <div class="modal-body">
        <!-- THE FORM -->
        <?php
          $query = "SELECT users.USER_ID, users.USERNAME FROM users 
                    LEFT JOIN employee on employee.USER_ID = users.USER_ID 
                    WHERE USER_STATUS = 1 ";
          $query_run = mysqli_query($connection, $query);
        ?>
          <div class="form-row"> 
            <div class="form-group col-md-6">
              <label>User: </label>
                <select name="userid" class="form-control selectpicker" data-live-search="true" data-width="100%" required>
                <option></option>
                <option> Select User </option>
                  <?php
                    while($row = mysqli_fetch_array($query_run))
                    {
                      ?>
                        <option name="" value="<?php echo $row['USER_ID']?>"><?php echo $row['USERNAME'] ?> </option>
                      <?php
                    }
                  ?>
                </select>
          </div>
          <div class="form-group">
          <label>   </label>
          <input type="hidden"  class="form-control">
          </div> 
          <div class="form-group col-md-5">
            <label>Employee Number: </label>
            <input type="number" pattern=".{3,10}" name="empno" class="form-control" placeholder="Enter Employee Number" required>
          </div>
        </div>
           
        <div class="form-row">
          <div class="form-group col-md-4">
              <label>First Name: </label>
              <input type="text" pattern=".{3,20}" id ="" name="empfname" class="form-control" placeholder="Enter First Name" maxlength="20" required>
          </div>
          <div class="form-group col-md-3">
              <label>Last Name: </label>
              <input type="text" pattern=".{3,20}" name="emplname" class="form-control" placeholder="Enter Last Name" maxlength="20" required>
          </div>
          <div class="form-group col-md-3">
              <label>Middle Name: </label>
              <input type="text" pattern=".{3,20}" name="empmname" class="form-control" placeholder="Enter Middle Name" maxlength="20">
          </div>
          <div class="form-group col-md-2">
              <label>Suffix: </label>
              <input type="text" pattern=".{,6}" name="empsname" class="form-control" placeholder="Enter Suffix" maxlength="5">
          </div>
        </div>

        <div class="form-row">
          <div class="form-group col-md-6">
              <label>Pay Mode: </label>
              <input type="text" name="pmode" class="form-control" placeholder="Enter Pay Mode" maxlength="5" required>
          </div>
          <div class="form-group col-md-6">
            <label>DOJ: </label>
            <input type="date" name="doj" class="form-control" placeholder="Enter DOJ" required>
          </div>
        </div>

        <div class="form-row">  
          <div class="form-group col-md-6">
              <label> Project/Location: </label>
              <input type="text" name="proloc" class="form-control" placeholder="Enter Project or Location" maxlength="30" required>
          </div>
          <div class="form-group col-md-6">
              <label> Designation: </label>
              <input type="text" name="designation" class="form-control" placeholder="Enter Desgination" maxlength="30" required>
          </div>
        </div>

        <div class="form-row">
          <div class="form-group col-md-12">
            <label> Bank Name: </label>
            <input type="text" name="bankname" class="form-control" placeholder="Enter Bank Name" maxlength="30">
          </div>
        </div>

        <div class="form-row">
          <div class="form-group col-md-6">
              <label> Account No. : </label>
              <input type="number" name="accno" class="form-control" placeholder="Enter Account No." maxlength="30">
          </div>
          <div class="form-group col-md-6">
              <label> IBAN No. : </label>
              <input type="text" name="ibanno" class="form-control" placeholder="Enter IBAN No." maxlength="30">
          </div>
        </div>
        <input type="hidden" name="empstatus" value="1">
    <!-- END FORM -->
        
        <div class="modal-footer">
          <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-times" aria-hidden="true"></i> Close</button>
          <button type="submit" name="addemp" class="btn btn-success"><i class="fa fa-check" aria-hidden="true"></i> Save</button>
        </div>
      </form>
      </div>
    </div>
  </div>
</div>

<!-- whole table -->
<div class="container-fluid">
  <div class="card shadow mb-4">
    <!-- Table head -->
    <div class="card-header py-3">
      <h5 class="m-0 font-weight-bold  text-primary">Manage Employee
        <!-- BUTTON -->
        <button type="button" class="btn btn-primary float-right" data-toggle="modal" data-target="#adduser">
            <i class="fa fa-plus" aria-hidden="true"></i>
            Add Employee
        </button>
      </h5>
    </div>
    <div class="card-body">
      
      <!-- TABLE -->
      <div class="table-responsive">
        <?php
          $query = "SELECT * FROM `employee` LEFT JOIN users on employee.USER_ID = users.USER_ID WHERE EMP_STATUS=1 UNION ALL SELECT * FROM `employee` RIGHT JOIN users on employee.USER_ID = users.USER_ID WHERE employee.EMP_ID is null AND USER_STATUS=1 AND EMP_STATUS=1";
          $query_run = mysqli_query($connection, $query);
        ?>
        <table class="table table-bordered table-striped" id="dataTable" width="100%" cellspacing="0">
          <thead>
            <tr>
              <th class="d-none">Employee ID</th>
              <!-- <th>User Email</th> -->
              <th>Employee No.</th>
              <th>Employee Name</th>
              <th>Project/Location</th>
              <th>Designation</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            <?php
              if(mysqli_num_rows($query_run)>0)
              {
                while($row = mysqli_fetch_assoc($query_run))
                {
                  ?>
              <tr>
                  <td class="d-none"><?php echo $row['EMP_ID']; ?></td>
                  <td><?php echo $row['EMP_NO']; ?></td>
                  <td><?php echo ucfirst($row['EMP_FNAME']); ?><?php echo ' '.ucfirst($row ['EMP_MNAME']); ?><?php echo ' '.ucfirst($row['EMP_LNAME']); ?><?php echo ' '.ucwords($row['EMP_SNAME']); ?></td>
                  <td><?php echo $row['EMP_LOCATION']; ?></td>
                  <td><?php echo $row['EMP_DESIGNATION']; ?></td>
                  <td class="btn-group">
                    <form action="edit_employee.php" method="POST">
                      <input type="hidden" name="emp_id" value="<?php echo $row['EMP_ID'];?>">
                        <button type="submit" name="edit_emp" class="btn btn-success">
                          <i class="fa fa-pencil" aria-hidden="true"></i>
                        </button>
                    </form>
                    <form action="code.php" method="POST">
                      <input type="hidden" name="del_empid" value="<?php echo $row['EMP_ID'];?>">
                        <button type="submit" name="del_emp" class="btn btn-danger">
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
          <tfoot>
            <tr>
              <th class="d-none">Employee ID</th>
              <!-- <th>User Email</th> -->
              <th>Employee No.</th>
              <th>Employee Name</th>
              <th>Project/Location</th>
              <th>Designation</th>
              <th>Action</th>
            </tr>
          </tfoot>
        </table>
      </div>
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