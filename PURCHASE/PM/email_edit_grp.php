<?php
include('../../security.php');
include('../../includes/header.php'); 
include('../../includes/purchase_navbar.php'); 
error_reporting(0);
if(isset($_POST['edit_grp']) || isset($_GET['grp_id'])){
    if($_POST['grp_id']){
        $grp_id=$_POST['grp_id'];
    }
    else{
        $grp_id=$_GET['grp_id'];
    }
    $grp_details="SELECT * FROM email_group WHERE Email_Grp_Id='$grp_id'";
    $grp_details_run=mysqli_query($connection,$grp_details);
    if(mysqli_num_rows($grp_details_run)>0){
        while($row=mysqli_fetch_assoc($grp_details_run)){
            $grp_name=$row['Email_Grp_Name'];
            $grp_desc=$row['Email_Grp_Desc'];
            //LISTED COMPANY
            $comp_email="SELECT * FROM email WHERE Email_Grp_Id='$grp_id' AND User_Id is not NULL AND Email_Status=1";
            $comp_email_run=mysqli_query($connection,$comp_email);
            //OTHER
            $other_email="SELECT * FROM email WHERE Email_Grp_Id='$grp_id' AND Email !='' AND Email_Status=1";
            $other_email_run=mysqli_query($connection,$other_email);
        }
    }
    else{ echo "no data available"; }
}
?>
<div class="container-fluid">
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Edit Email Group</h6>
        </div>
        <div class="card-body">
            <form action="code.php" method="post">
                <input type="hidden" name="grp_id" id="grp_id" value="<?php echo $grp_id;?>">
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label>Group Name: *</label>
                        <input type="text" name="grp_name" value="<?php echo $grp_name;?>" class="form-control" maxlength="30">
                    </div>
                    <div class="form-group col-md-6">
                        <label>Description: </label>
                        <input type="text" name="grp_desc" value="<?php echo $grp_desc;?>" class="form-control" maxlength="30">
                    </div>
                </div>
                <!-- FROM LISTED COMPANY -->
                <div class="row ml-1 mt-3">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="compTbl">
                            <thead>
                                <th width="47%">Company Name</th>
                                <th width="47%">Email</th>
                                <th width="6%"></th>
                            </thead>
                            <tbody>
                                <?php
                                    if(mysqli_num_rows($comp_email_run)>0){
                                        while($row_c=mysqli_fetch_assoc($comp_email_run)){
                                            $cEmail_Id=$row_c['Email_Id'];
                                            $user_id=$row_c['User_Id'];
                                            $comp_d="SELECT c.Comp_Name, u.USERNAME FROM company as c
                                                    LEFT JOIN users as u on u.USER_ID=c.User_Id
                                                        WHERE c.User_Id='$user_id'";
                                            $comp_d_run=mysqli_query($connection,$comp_d);
                                            if(mysqli_num_rows($comp_d_run)>0){
                                                $row_cd=mysqli_fetch_assoc($comp_d_run);
                                                $comp_name=$row_cd['Comp_Name'];
                                                $email=$row_cd['USERNAME'];
                                            }
                                ?>
                                <tr>
                                    <td class="d-none"><input class="form-control" name="cmail_id[]" value="<?php echo $cEmail_Id; ?>"></td>
                                    <td><?php echo $comp_name;?></td>
                                    <td><?php echo $email;?></td>
                                    <td class="text-center">
                                        <form action="code.php" method="POST">
                                            <input type="hidden" name="grp_id_del" value="<?php echo $grp_id;?>">
                                            <input type="hidden" name="email_id" value="<?php echo $cEmail_Id; ?>">
                                                <button type="submit" name ="del_email" class="btn btn-danger d-inline">-</button>
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
                        <div class="float-right">
                            <button type="button" name="afdd" id="addEmailComp" class="btn btn-success btn-xs">+</button>
                        </div>
                    </div>              
                </div>
                <!-- OTHER EMAILS -->
                <div class="row ml-1 mt-3">
                    <div class="form-row mb-2">
                        <div class="col-6">
                            <input type="number" class="form-control form-control-sm" id="addRows">
                        </div>
                        <div class="col-2">
                            <button class="btn btn-success btn-sm pt-1" id="addBtn">+</button>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered" id="otherTbl">
                            <thead>
                                <th width="47%">Other Emails</th>
                                <th width="6%"></th>
                            </thead>
                            <tbody>
                                <?php
                                    if(mysqli_num_rows($other_email_run)>0){
                                        while($row_o=mysqli_fetch_assoc($other_email_run)){
                                            $oEmail_Id=$row_o['Email_Id'];
                                            $oEmail=$row_o['Email'];
                                ?>
                                <tr>
                                    <td class="d-none">
                                        <input class="form-control" name="cmail_id[]" value="<?php echo $oEmail_Id; ?>">
                                    </td>
                                    <td><?php echo $oEmail;?></td>
                                    <td class="text-center">
                                        <form action="code.php" method="POST">
                                            <input type="hidden" name="grp_id_del" value="<?php echo $grp_id;?>">
                                            <input type="hidden" name="email_id" value="<?php echo $oEmail_Id; ?>">
                                            <button type="submit" name ="del_email" class="btn btn-danger d-inline">-</button>
                                        </form>
                                    </td>
                                </tr>
                                            <?php
                                        }
                                    }
                                    else
                                    { echo "No Record Found";}
                                ?>
                            </tbody>
                        </table>
                        <div class="float-right">
                            <button type="button" name="afdd" id="addOther" class="btn btn-success btn-xs">+</button>
                        </div>
                    </div>              
                </div>
                <div class="btn-toolbar pull-left ">
                    <button type="submit" name="update_grp" class="btn btn-success mr-3"><i class="fa fa-check" aria-hidden="true"></i> Update </button>
                </div> 
            </form>
        </div>
    </div>
</div>
<!-- container fluid -->
<script>
$(document).ready(function(){
    var count = 0;
    $('#addOther').click(function(){
    var html_code = "<tr id='row"+count+"'>";
        html_code += "<td><input  class='form-control no-border alw_name' name='other[]' type='text' required></td>";
        html_code += "<td><button type='button' name='remove' data-row='row"+count+"' class='btn btn-danger btn-xs remove'>-</button></td>";
        html_code += "</tr>";
        $('#otherTbl').append(html_code);
        count = count + 1;
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
    //comp list
    var grp_id =$('#grp_id').val(); 
    var count1=0; 
    $('#addEmailComp').click(function(){
        $.ajax({
        url: 'code.php',
        method:'POST',
        data: {'grp_id':grp_id},
        success:function(data){
            var html_code = "<tr id='row"+count1+"'>";
            html_code += "<td>";
            html_code += "<select name='comp_email[]' id='emailc' class='selectpicker form-control' data-live-search='true' required></select>";
            html_code += "</td>";
            html_code += "<td></td>";
            html_code += "<td><button type='button' name='remove' data-row='row"+count1+"' class='btn btn-danger btn-xs remove'>-</button></td>";
            html_code += "</tr>";
            $('#compTbl').append(html_code);
            console.log(data);
            $(document).find('#row'+count1+' #emailc').html(data).change();
            $(".selectpicker").selectpicker("refresh");
            }
        });
        count1 = count1 + 1;
    });
    $(document).on('click', '.remove', function(){
        var delete_row = $(this).data("row");
        $('#'+delete_row).remove();
    });
    // add row field
    $('#addBtn').click(function(){
        var row = $('#addRows').val();
        if(row==0){
            var html_code = "<tr id='row"+count+"'>";
                html_code += "<td><input  class='form-control no-border alw_name' name='other[]' type='text' required></td>";
                html_code += "<td><button type='button' name='remove' data-row='row"+count+"' class='btn btn-danger btn-xs remove'>-</button></td>";
                html_code += "</tr>";
                $('#otherTbl').append(html_code);
                count = count + 1;
        }
        if(row!=0 || row != null){
            for(i=0;i<row;i++){
                var html_code = "<tr id='row"+count+"'>";
                    html_code += "<td><input  class='form-control no-border alw_name' name='other[]' type='text' required></td>";
                    html_code += "<td><button type='button' name='remove' data-row='row"+count+"' class='btn btn-danger btn-xs remove'>-</button></td>";
                    html_code += "</tr>";
                    $('#otherTbl').append(html_code);
                    count = count + 1;
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
});
</script>
<?php
include('../../includes/scripts.php');
include('../../includes/footer.php');
?>