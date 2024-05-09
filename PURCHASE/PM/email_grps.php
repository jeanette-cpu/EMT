<?php
include('../../security.php');
include('../../includes/header.php'); 
include('../../includes/purchase_navbar.php'); 
error_reporting(0);
?>
<div class="container-fluid">
    <div class="card shadow mb-4">
        <div class="card-header py-3">
                <h5 class="m-0 font-weight-bold text-primary">Manage Email Groups
                <!-- BUTTON -->
                <button type="button" class="btn btn-primary float-right" data-toggle="modal" data-target="#adduser">
                        <i class="fa fa-plus" aria-hidden="true"></i>
                        Add Email Group
                </button></h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
            <?php
                $query = "SELECT * FROM email_group WHERE Email_Grp_Status=1  ORDER BY Email_Grp_Name ASC";
                $query_run = mysqli_query($connection, $query);
            ?>
                <table class="table table-bordered table-striped" id="dataTable" width="100%" cellspacing="0"  data-order='[[ 1, "asc" ]]'>
                    <thead>
                        <th class="d-none"></th>
                        <th>Group Name</th>
                        <th>Description</th>
                        <th>No.</th>
                        <th>Emails</th>
                        <th>Action</th>
                    </thead>
                    <tbody>
                    <?php
                        if(mysqli_num_rows($query_run)>0){
                            while($row = mysqli_fetch_assoc($query_run)){
                                $grp_id=$row['Email_Grp_Id'];
                                $q_emails="SELECT * FROM email WHERE Email_Grp_Id='$grp_id' AND Email_Status=1";
                                $q_email_run=mysqli_query($connection,$q_emails);
                                $mail_no=mysqli_num_rows($q_email_run); $emails="";
                                if($mail_no>0){
                                    if($mail_no>6){
                                        $q_emails1="SELECT * FROM email WHERE Email_Grp_Id='$grp_id' AND Email_Status=1 LIMIT 6";
                                        $q_email_run1=mysqli_query($connection,$q_emails1);
                                        while($row_e1=mysqli_fetch_assoc($q_email_run1)){
                                            $email =$row_e1['Email'];
                                            if($email){
                                                $emails .=$email.', ';
                                            }
                                            else{
                                                $user_id=$row_e1['User_Id'];
                                                $q_user="SELECT USERNAME FROM users WHERE USER_ID ='$user_id'";
                                                $q_user_run=mysqli_query($connection,$q_user);
                                                if($q_user_run){
                                                    $row_u=mysqli_fetch_assoc($q_user_run);
                                                    $user_email=$row_u['USERNAME'];
                                                }
                                                $emails .=$user_email.', ';
                                            }
                                        }
                                        // trim last 2 characters
                                        $emails=substr_replace($emails, "", -2);
                                        $emails .='..';
                                    }
                                    else{
                                        while($row_e=mysqli_fetch_assoc($q_email_run)){
                                            $email =$row_e['Email'];
                                            if($email){
                                                $emails .=$email.', ';
                                            }
                                            else{
                                                $user_id=$row_e['User_Id'];
                                                $q_user="SELECT USERNAME FROM users WHERE USER_ID ='$user_id'";
                                                $q_user_run=mysqli_query($connection,$q_user);
                                                if($q_user_run){
                                                    $row_u=mysqli_fetch_assoc($q_user_run);
                                                    $user_email=$row_u['USERNAME'];
                                                    if($user_email){
                                                        $emails .=$user_email.', ';
                                                    }
                                                }
                                            }
                                        }
                                        // trim last 2 characters
                                        $emails=substr_replace($emails, "", -2);
                                    }
                                }
                        ?>
                        <tr>
                            <td class="d-none"><?php echo $grp_id; ?></td>
                            <td><?php echo $row['Email_Grp_Name']; ?></td>
                            <td><?php echo $row['Email_Grp_Desc'];?></td>
                            <td><?php echo $mail_no;?></td>
                            <td><?php echo $emails;?></td>
                            <td class="btn btn-group">
                                <!-- EDIT -->
                                <form action="email_edit_grp.php" method="POST">
                                    <input type="hidden" name="grp_id" value="<?php echo $grp_id?>">
                                    <button type="submit" name="edit_grp" class="btn btn-success editUsers" data-toggle="modal" data-target="#EditUsersModal">
                                        <i class="fa fa-edit" area-hidden="true"></i>
                                    </button>
                                </form>
                                <!-- DELETE -->
                                <form action="code.php" method="post">
                                    <input type="hidden" name="grp_id" value="<?php echo $grp_id;?>">
                                    <button type="submit" name="del_email_grp" class="btn btn-danger">
                                        <i class="fa fa-trash" area-hidden="true"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        <?php
                    }
                }
                else{ echo "No Record Found";}
            ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<!-- Modal Add Group -->
<div class="modal fade" id="adduser" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
          <!-- MODAL TITLE -->
        <h5 class="modal-title" id="exampleModalLabel"><i class="fa fa-envelope" aria-hidden="true"></i> Add Email Group</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
    <form action="code.php" method="POST">
      <div class="modal-body">
        <!-- THE FORM -->
            <div class="form-group">
                <label>Group Name: *</label>
                <input type="text" name="grpName" id="" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Description:</label>
                <input type="text" name="desc" class="form-control" >
            </div>
            <div class="form-group">
                <label>Companies: *</label>
                <select name="comps[]" id="emails" class="form-control selectpicker" multiple data-live-search="true" required>
                    <?php
                        $q_comp="SELECT Comp_Name, User_Id FROM company WHERE Comp_Status=1 AND Comp_Approval=1";
                        $q_comp_run=mysqli_query($connection,$q_comp);
                        if(mysqli_num_rows($q_comp_run)>0){
                            while($rowc=mysqli_fetch_assoc($q_comp_run)){
                                $comp_name=$rowc['Comp_Name'];
                                $user_id=$rowc['User_Id'];
                                echo '<option value="'.$user_id.'">'.$comp_name.'</option>';
                            }
                        }
                    ?>
                </select>
                <div class="form-row">
                    <div class="col-1">
                        <div class="invisible"><label>1</label></div>
                        <input type="text" id="row_val" class="form-control form-control-sm ml-2">
                    </div>
                    <div class="col-3">
                        <div class="invisible"><label>1</label></div>
                        <button type="button" id="addRow" class="btn btn-success btn-sm ml-1">+ Rows</button>
                    </div>
                </div>
                <div class="form-row mt-3">
                    <div class="col-8">
                        <table cellspacing="5" class="m-2" id="otherTbl" width="100%">
                            <thead>
                                <th width="80%">Other Emails:</th>
                                <th ></th>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><input type="email" name="comps[]" class="form-control form-control-sm"></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    
                </div>
            </div>
        <!-- END FORM -->
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-times" aria-hidden="true"></i> Close</button>
        <button type="submit" name="addGroup" class="btn btn-success"><i class="fa fa-check" aria-hidden="true"></i> Save</button>
      </div>
      </form>
    </div>
  </div>
</div>
<script>
$(document).ready(function () { 
    var d=1;
    $('#addRow').click(function(){
        var row = $('#row_val').val();
        if(row==0){
            var row_html='<tr id="rrow'+d+'">';
                row_html+='<td><input type="email" name="comps[]" class="form-control form-control-sm" required></td>';
                row_html +='<td><button class="btn btn-danger btn-sm rremove" data-row="rrow'+d+'" type="button">-</button><td></tr>';
            $('#otherTbl').append(row_html);
            d++;
        }
        if(row!=0 || row != null){
            for(i=0;i<row;i++){
                var row_html='<tr id="rrow'+d+'">';
                    row_html+='<td><input type="email" name="comps[]" class="form-control form-control-sm" required></td>';
                    row_html +='<td><button class="btn btn-danger btn-sm rremove" data-row="rrow'+d+'" type="button">-</button><td></tr>';
                $('#otherTbl').append(row_html);
                d++;
            }
        }
        $('input').bind('paste', function (e) {
            var $start = $(this);
            var source
            //check for access to clipboard from window or event
            if (window.clipboardData !== undefined) {
                source = window.clipboardData
            } else {
                source = e.originalEvent.clipboardData;
            }
            var data = source.getData("Text");
            if (data.length > 0) {// if has content
                // if (data.indexOf("\t") > -1) {
                    // console.log(':v2') // not going here
                    var columns = data.split("\n");
                    $.each(columns, function () {
                        var values = this.split("\t");
                        // console.log(values);
                        $.each(values, function () {
                            $start.val(this);
                            if ($start.closest('tr').next('td').find('input,textarea')[0] != undefined || $start.closest('tr').next('td').find('textarea')[0] != undefined) {
                                $start = $start.closest('tr').next('td').find('input,textarea');
                            }
                            else{   
                                return false;  
                            }
                        });
                        $start = $start.closest('td').parent().next('tr').children('td:first').find('input,textarea');
                    });
                    e.preventDefault();
                // }
            }
        });
    });
    $(document).on('click', '.rremove', function(){
        var delete_row = $(this).data("row");
        $('#' + delete_row).remove();
    }); 
});
$(document).ready(function(){   // move to next td paste
    $('input').bind('paste', function (e) {
        var $start = $(this);
        var source
        //check for access to clipboard from window or event
        if (window.clipboardData !== undefined) {
            source = window.clipboardData
        } else {
            source = e.originalEvent.clipboardData;
        }
        var data = source.getData("Text");
        if (data.length > 0) {// if has content
            // if (data.indexOf("\t") > -1) {
                // console.log(':v2') // not going here
                var columns = data.split("\n");
                $.each(columns, function () {
                    var values = this.split("\t");
                    // console.log(values);
                    $.each(values, function () {
                        $start.val(this);
                        if ($start.closest('tr').next('td').find('input,textarea')[0] != undefined || $start.closest('tr').next('td').find('textarea')[0] != undefined) {
                        $start = $start.closest('tr').next('td').find('input,textarea');
                        }
                        else
                        {
                        return false;  
                        }
                    });
                    $start = $start.closest('td').parent().next('tr').children('td:first').find('input,textarea');
                });
                e.preventDefault();
            // }
        }
    });
});
</script>
<?php
include('../../includes/scripts.php');
include('../../includes/footer.php');
?>