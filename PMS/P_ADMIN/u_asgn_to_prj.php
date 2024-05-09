<?php
include('../../security.php');
include('../../includes/header.php'); 
include('../../includes/prj_admin_navbar.php'); 
?>
<!-- TABLE -->
<div class="container-fluid">
    <div class="card shadow mb-4">
        <div class="card-header py-3">
                <h5 class="m-0 font-weight-bold text-primary">Assign to Project 
                <!-- BUTTON -->
                <button type="button" class="btn btn-primary float-right" data-toggle="modal" data-target="#adduser">
                    <i class="fa fa-plus" aria-hidden="true"></i>
                        Assign
                </button></h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
            <?php
                $query = "SELECT * FROM `asgn_emp_to_prj` LEFT JOIN employee on employee.EMP_ID = asgn_emp_to_prj.Emp_Id LEFT JOIN project on project.Prj_Id = asgn_emp_to_prj.Prj_Id WHERE asgn_emp_to_prj.Asgd_Emp_to_Prj_Status = 1 AND employee.EMP_STATUS = 1 AND project.Prj_Status = 1";
                $query_run = mysqli_query($connection, $query);
            ?>
                <table class="table table-bordered table-striped" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <th class="d-none"></th>
                        <th class="d-none"></th>
                        <th class="d-none"></th>
                        <th class="d-none"></th>
                        <th>User Name</th>
                        <th>User Type</th>
                        <th>Employee Name</th>
                        <th>Project Code</th>
                        <th>Project Name</th>
                        <th>Actions</th>
                    </thead>
                    <tbody>
                    <?php
                        if(mysqli_num_rows($query_run)>0)
                        {
                            while($row = mysqli_fetch_assoc($query_run))
                            {
                                $prj_id = $row['Prj_Id'];
                                $user_id = $row['User_Id'];
                                $emp_id =$row['Emp_Id'];

                                $query1 = "SELECT Prj_Code, Prj_Name FROM project WHERE Prj_Status = 1 AND Prj_Id='$prj_id' LIMIT 1";
                                $prj = mysqli_query($connection, $query1);
                                $row1 = mysqli_fetch_assoc($prj);

                                $query2 = "SELECT USERNAME, USERTYPE FROM users WHERE USER_STATUS = 1 AND USER_ID='$user_id' LIMIT 1";
                                $user = mysqli_query($connection, $query2);
                                $row2 = mysqli_fetch_assoc($user);
                                
                                $query3 = "SELECT EMP_FNAME, EMP_MNAME, EMP_LNAME, EMP_SNAME FROM employee WHERE EMP_STATUS = 1 AND EMP_ID='$emp_id' LIMIT 1";
                                $emp = mysqli_query($connection, $query3);
                                $row3 = mysqli_fetch_assoc($emp);
                                $full_name = $row3['EMP_FNAME'].' '.$row3['EMP_MNAME'].' '.$row3['EMP_LNAME'].' '.$row3['EMP_SNAME'];
                                ?>
                        <tr>
                            <td class="d-none"><?php echo $row['Asgd_Emp_to_Prj'];?></td>
                            <td class="d-none"><?php echo $user_id?></td>
                            <td class="d-none"><?php echo $emp_id?></td>
                            <td class="d-none"><?php echo $prj_id?></td>
                            <td><?php echo $row2['USERNAME'];?></td>
                            <td><?php $usertype = $row2['USERTYPE'];
                                    if($usertype=="planning_eng")
                                    { echo 'Project Administrator';}
                                    else if($usertype=="proj_mgr")
                                    { echo 'Project Manager';}
                                    else if($usertype=="str_mgr")
                                    { echo 'Store Manager';}
                                    else if($usertype=="foreman")
                                    { echo 'Foreman';}
                            ?></td>
                            <td><?php echo $full_name?></td>
                            <td><?php echo $row1['Prj_Code'];?></td>
                            <td><?php echo $row1['Prj_Name'];?></td>
                            <td class="btn-group text-center">
                                <!-- EDIT -->
                                <button type="button" class="btn btn-success editAsgnBtn">
                                    <i class="fa fa-edit" area-hidden="true"></i>
                                </button>
                                <!-- DELETE -->
                                <form action="code.php" method="POST">
                                    <input type="text" class="d-none" name="delete_id" value="<?php echo $row['Asgd_Emp_to_Prj'];?>">
                                    <button type="submit" name="delete_asgn_user" class="btn btn-danger">
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
                    <tfoot>
                        <tr>
                            <th class="d-none"></th>
                            <th class="d-none"></th>
                            <th class="d-none"></th>
                            <th class="d-none"></th>
                            <th>User Name</th>
                            <th>User Type</th>
                            <th>Employee Name</th>
                            <th>Project Code</th>
                            <th>Project Name</th>
                            <th>Actions</th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal ADD Assign -->
<div class="modal fade bd-example-modal-lg" id="adduser" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
          <!-- MODAL TITLE -->
        <h5 class="modal-title" id="exampleModalLabel"><i class="fa fa-user-plus" aria-hidden="true"></i> Assign to Project</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
    <form action="code.php" method="POST">

      <div class="modal-body">
        <!-- THE FORM -->
        <?php
          $query = "SELECT Prj_Id, Prj_Code, Prj_Name FROM project where Prj_Status=1";
          $query_run = mysqli_query($connection, $query);
        ?>
            <div class="form-group">
                <label for="">Project Code</label>
                    <select name="prj_id" class="form-control selectpicker" data-live-search="true" required>
                        <option> Select Project Code</option>
                        <?php
                            while($row = mysqli_fetch_array($query_run))
                            {
                            ?>
                                <option name="" value="<?php echo $row['Prj_Id']?>"><?php echo $row['Prj_Code'].' - '.$row['Prj_Name'] ?> </option>
                            <?php
                            }
                        ?>
                    </select>
            </div>
            <div class="form-group">
                <label for="">User Type</label>
                    <select name="usertype" id="usertype" class="form-control"> 
                        <option value="">Select Usertype</option>
                        <!-- <option value="planning_eng">Project Administrator</option> -->
                        <option value="proj_mgr">Project Manager</option>
                        <option value="str_mgr">Store Manager</option>
                        <option value="foreman">Foreman</option>
                    </select>
            </div>
            <div class="table-responsive">
                <table class="table table-bordered" id="pmTbl">
                    <tr>
                        <th>Username</th>
                        <th>Employee Code</th>
                        
                        <th></th>
                    </tr>
                    <tr id="row">
                        <td>
                            <select id="username" name="username[]"class="form-control selectpicker username" data-live-search="true" data-width="100%" required>
                            
                                <option> Select Username</option>
                                
                            </select>
                        </td>
                        <td>
                        <?php
                            $query = "SELECT * FROM employee where EMP_STATUS=1";
                            $query_run = mysqli_query($connection, $query);
                        ?>
                            <select name='emp_id[]' id='emp_id' class='form-control selectpicker' data-live-search='true' required>
                                <option> Select Employee</option>
                                <?php
                                    while($row1 = mysqli_fetch_array($query_run))
                                    {
                                    ?>
                                        <option name='' value='<?php echo $row1['EMP_ID']?>'><?php echo $row1['EMP_NO'].' - '.$row1['EMP_FNAME'].' '.$row1['EMP_LNAME'].' '.$row1['EMP_MNAME'].' '.$row1['EMP_SNAME'] ?> </option>
                                    <?php
                                    }
                                ?>
                            </select>
                        </td>
                        
                        <td></td>
                    </tr>
                </table>
                <div align="right">
                    <button type="button" name="add" id="addBtn" class="btn btn-success btn-xs">+</button>
                </div>
            </div>
            <input type="hidden" name="pm_status" value="1">
        <!-- END FORM -->
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-times" aria-hidden="true"></i> Close</button>
        <button type="submit" name="asgnBtn" class="btn btn-success"><i class="fa fa-check" aria-hidden="true"></i> Save</button>
      </div>
      </form>
    </div>
  </div>
</div>
<!-- End Modal ADD User -->

<!-- Modal EDIT Assigned -->
<div class="modal fade bd-example-modal-lg" id="editAsgn" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
          <!-- MODAL TITLE -->
        <h5 class="modal-title" id="exampleModalLabel"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> Edit Assignation</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
    <form action="code.php" method="POST">
      <div class="modal-body">
        <!-- THE FORM -->
            <input name="eAsgnId" id="asgn_id" type="hidden">
            <div class="form-group">
                <label>Username</label>
                    <select name="eAsgnUsername" id="username_edit" class="form-control selectpicker" data-live-search="true"></select>
            </div>
            <div class="form-group">
                <label>Employee</label>
                    <select name="eAsgnEmp" id="emp_edit" class="form-control selectpicker" data-live-search="true"></select>
            </div>
            <div class="form-group">
                <label for="">Project Code</label>
                    <select name="eAsgnPrj" id="prj_edit" class="form-control selectpicker" data-live-search="true" required></select>
            </div>
                <div align="right">
                    <button type="button" name="add" id="addBtn" class="btn btn-success btn-xs">+</button>
                </div>
        <!-- END FORM -->
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-times" aria-hidden="true"></i> Close</button>
        <button type="submit" name="editAsgnBtn" class="btn btn-success"><i class="fa fa-check" aria-hidden="true"></i> Save</button>
      </div>
      </form>
    </div>
  </div>
</div>
<!-- End EDIT MODAL -->

<script>
$(document).ready(function(){

    $.ajax({
        url:'ajax_users.php',
        method: 'POST',
        data:{},
        success:function(data){
            $('#username_edit').html(data).change();
            $('.selectpicker').selectpicker('refresh');
        }
    });
    $.ajax({
        url:'ajax_emp.php',
        method: 'POST',
        data:{},
        success:function(data){
            $('#emp_edit').html(data).change();
            $('.selectpicker').selectpicker('refresh');
        }
    });
    $.ajax({
        url:'ajax_project.php',
        method: 'POST',
        data:{},
        success:function(data){
            $('#prj_edit').html(data).change();
            $('.selectpicker').selectpicker('refresh');
        }
    });

var count = 1;
    $('#addBtn').click(function(){
    var usertype = $('#usertype').val();
     $.ajax({
        url: 'u_ajax.php',
        method:'POST',
        data: {usertype:usertype},
        success:function(data){
            var emp = document.getElementById('emp_id');
            var html_code = "<tr id='row"+count+"'>";
            html_code +="<td><select name='username[]' id='username' class='form-control username selectpicker' data-width='100%' data-live-search='true' required><option> Select Username</option></select></td>";
            html_code += "<td><select name='emp_id[]' id='emp_id' class='form-control selectpicker data-live-search='true'></select></td>";
            html_code += "<td><button type='button' name='remove' data-row='row"+count+"' class='btn btn-danger btn-xs remove'>-</button></td>";
            html_code += "</tr>";
            $('#pmTbl').append(html_code);
            $(document).find('#row'+count+' #username').html(data).change();
            $('.selectpicker').selectpicker('refresh');
        }
    });
    });
    count++
});

$(document).on('click', '.remove', function(){
    var delete_row = $(this).data("row");
    $('#' + delete_row).remove();
});
</script>

<?php
include('../../includes/scripts.php');
include('../../includes/footer.php');
?>