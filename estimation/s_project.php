<?php
include('../security.php');
include('../includes/header.php'); 
include('../includes/estimation_nav.php');
include('est_queries.php');
?>
<div class="container-fluid">
    <div class="card shadow mb-4">
        <div class="card-header py-3">
                <h5 class="m-0 font-weight-bold text-primary"><i class="fa fa-home" aria-hidden="true"></i> Manage Projects
                <!-- BUTTON -->
                <button type="button" class="btn btn-primary float-right" data-toggle="modal" data-target="#adduser">
                    <i class="fa fa-plus" aria-hidden="true"></i>
                    Add Project
                </button></h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <th class="d-none"></th>
                        <th>Project Code</th>
                        <th>Project Name</th>
                        <th class="d-none">Category</th>
                        <th>Type</th>
                        <th>Date</th>
                        <th>Location</th>
                        <th>Client</th>
                        <th>Main Contractor</th>
                        <th>Consultant</th>
                        <th class="d-none"></th>
                        <th class="d-none"></th>
                        <th class="d-none"></th>
                        <th>Actions</th>
                    </thead>
                    <tbody>
                    <?php
                        if(mysqli_num_rows($prj_run)>0){
                            while($row = mysqli_fetch_assoc($prj_run)){
                                $prj_id=$row['Prj_Est_Id'];
                                $code = $row['PE_Code'];
                                $name=$row['PE_Name'];
                                $category=$row['PE_Category'];
                                $type=$row['PE_Type'];
                                $date=$row['PE_Date'];
                                $emirate=$row['PE_Emirate_Location'];
                                $client_id=$row['Client_Id'];  $client_name=clientName($client_id);
                                $mc_id=$row['Main_Contractor_Id']; $mc_name=mcName($mc_id);
                                $cons_id=$row['Consultant_Id']; $cons_name=consName($cons_id);
                                ?>
                        <tr>
                            <td class="d-none"><?php echo $prj_id; ?></td><!--  0 -->
                            <td><?php echo $code; ?></td> <!-- 1 -->
                            <td><?php echo $name; ?></td> <!-- 2 -->
                            <td class="d-none"></td> <!-- 3 -->
                            <td><?php echo $type; ?></td> <!-- 4 -->
                            <td class=""><?php echo $date; ?></td> <!-- 5 -->
                            <td class=""><?php echo $emirate; ?></td> <!-- 6 -->
                            <td class=""><?php echo $client_name; ?></td>
                            <td class=""><?php echo $mc_name; ?></td>
                            <td class=""><?php echo $cons_name; ?></td>
                            <td class="d-none"><?php echo $client_id; ?></td>
                            <td class="d-none"><?php echo $mc_id; ?></td>
                            <td class="d-none"><?php echo $cons_id; ?></td>
                            <td class="btn-group ">
                                <!-- EDIT -->
                                <button type="button" class="btn btn-success editProject" data-toggle="modal" data-target="#EditProjectModal">
                                    <i class="fa fa-edit" area-hidden="true"></i>
                                </button>
                                <!-- DELETE button  -->
                                <form action="code.php" method="POST" class="">
                                    <input type="hidden" name="prj_id" value="<?php echo $prj_id;?>">
                                        <button type="submit" name ="delPE" class="btn btn-danger d-inline">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                </form>
                            </td>
                        </tr>
                        <?php
                            }
                        }
                        else{
                            echo "No Record Found";
                        }
                    ?> 
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal Add Project -->
<div class="modal fade bd-example-modal-lg" id="adduser" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
          <!-- MODAL TITLE -->
        <h5 class="modal-title" id="exampleModalLabel"><i class="fa fa-building mr-2" aria-hidden="true"></i>Add Project</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

    <form action="code.php" method="post">
      <div class="modal-body">
            <div class="form-row">
                <div class="col-2">
                    <label for="" class="font-weight-bold">Project Code</label>
                    <input type="text" name="prj_code" class="form-control" value="<?php echo lastPrjCode();?>" required readonly>
                </div>
                <div class="col-10">
                    <div class="form-group">
                        <label class="font-weight-bold" for="">Project Name</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>
                </div>
            </div>
            <div class="form-row">
                <div class="col-4">
                    <label class="font-weight-bold" >Date</label>
                    <input type="date" name="date" class="form-control" required>
                </div>
                <div class="col-4">
                    <label class="font-weight-bold" >Project Type</label>
                    <select name="prj_type" class="form-control" id="ptype_opt" required></select>
                </div>
                <div class="col-4">
                    <div class="form-group">
                        <label class="font-weight-bold" for="">Emirate Location</label>
                        <select name="location" id="emirateOpt" class="form-control" required></select>
                    </div>
                </div>
            </div>
            <div class="form-row">
                <div class="col-6">
                    <div class="form-group">
                        <label class="font-weight-bold" for="">Client Name</label>
                        <select name="client_id" id="client_opt" class="form-control"></select>
                    </div>
                </div>
                <div class="col-4">
                    <label for="" class="mt-1">Other Client:</label>
                    <input type="text" id="oth_client" name="" class="form-control">
                </div>
                <div class="col-2">
                    <div class="invisible"><label>1</label></div>
                    <button id="addClient" class="btn btn-success btn-sm mt-1" type="button">+ Add</button>
                </div>
            </div>
            <div class="form-row">
                <div class="col-6">
                    <div class="form-group">
                        <label class="font-weight-bold" for="">Main Contractor Name</label>
                        <select name="mc_id" id="mc_opt" class="form-control"></select>
                    </div>
                </div>
                <div class="col-4">
                    <label for="" class="mt-1">Other Main Contractor</label>
                    <input type="text" id="oth_mc" name="" class="form-control">
                </div>
                <div class="col-2">
                    <div class="invisible"><label>1</label></div>
                    <button id="addMc" class="btn btn-success btn-sm mt-1" type="button">+ Add</button>
                </div>
            </div>
            <div class="form-row">
                <div class="col-6">
                    <div class="form-group">
                        <label class="font-weight-bold" for="">Consultant Name</label>
                        <select name="cons_id" id="cons_opt" class="form-control"></select>
                    </div>  
                </div>
                <div class="col-4">
                    <label for="" class="mt-1">Other Consultant</label>
                    <input type="text" id="oth_cons" name="" class="form-control">
                </div>
                <div class="col-2">
                    <div class="invisible"><label>1</label></div>
                    <button id="addcons" class="btn btn-success btn-sm mt-1" type="button">+ Add</button>
                </div>
            </div>
        <!-- END FORM -->
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-times" aria-hidden="true"></i> Close</button>
            <button type="submit" name="addPE" class="btn btn-success"><i class="fa fa-check" aria-hidden="true"></i> Save</button>
        </div>
      </form>
    </div>
  </div>
</div>
<!-- End Modal ADD Project -->
<!-- End EDIT Project Modal -->
<div class="modal fade bd-example-modal-lg" id="EditProjectModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
          <!-- MODAL TITLE -->
        <h5 class="modal-title text-primary" id="exampleModalLabel"><i class="fa fa-home mr-1" aria-hidden="true"></i> Edit Project Details</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
      </div>
            <form action="code.php" method="POST">
                <div class="modal-body">
                    <!-- THE FORM -->
                        <input type="hidden" name="prj_id" id="update_id" class="form-control" >
                <div class="form-row">
                    <div class="col-2">
                        <div class="form-group">
                            <label class="font-weight-bold" for="">Code</label>
                            <input type="text" name="e_prj_code" id="prj_code" class="form-control" required readonly>
                        </div>
                    </div>
                    <div class="col-10">
                        <div class="form-group">
                            <label class="font-weight-bold" for="">Project Name</label>
                            <input type="text" name="name" id="prj_name" class="form-control" required>
                        </div>
                    </div>
                </div>
                <!-- <div class="col-6">
                    <div class="form-group">
                        <label class="font-weight-bold">Category</label><br>
                        <input type="radio" name="category" id="Building" class="radio mr-4 ml-4" value="Building" required>Building
                        <input type="radio" name="category" id="Villa" class="radio mr-4 ml-3" value="Villa" required>Villa
                    </div>
                </div> -->
                <div class="form-row">
                    <div class="col-4">
                        <label class="font-weight-bold" for="">Project Type</label>
                        <select name="type" id="ptype_opt1" class="form-control"></select>
                    </div>
                    <div class="col-4">
                        <label class="font-weight-bold">Date</label>
                        <input type="date" name="date" id="prj_date_start" class="form-control" required>
                    </div>
                    <div class="col-4">
                        <label class="font-weight-bold" for="">Emirate Location</label>
                        <select name="location" id="emirateOpt1" class="form-control"></select>
                    </div>
                </div>
                <div class="form-row mt-2">
                    <div class="col-4">
                        <label for="" class="font-weight-bold">Client</label>
                        <select name="client_id" id="e_client_opt" class="form-control"></select>
                    </div>
                    <div class="col-4">
                        <label for="" class="font-weight-bold">Main Control</label>
                        <select name="mc_id" id="e_mc_opt" class="form-control"></select>
                    </div>
                    <div class="col-4">
                        <label for="" class="font-weight-bold">Consultant</label>
                        <select name="cons_id" id="e_cons_opt" class="form-control"></select>
                    </div>
                </div>
        <!-- END FORM -->
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-times" aria-hidden="true"></i> Close</button>
            <button type="submit" name="editPE" class="btn btn-success"><i class="fa fa-check" aria-hidden="true"></i> Save</button>
        </div>
        </form>
    </div>
  </div>
</div>
<!-- End EDIT Project Modal -->
<script>
$(document).ready(function () {
    $('.editProject').on('click', function() {
        $('#EditProjectModal').modal('show');
            $tr = $(this).closest('tr');
            var data = $tr.children("td").map(function(){
                return $(this).text();
            }).get();
            // console.log(data);
            // alert(data);
            $('#update_id').val(data[0]);
            $('#prj_code').val(data[1]);
            $('#prj_name').val(data[2]);
            $('#ptype_opt1').val(data[4]);
            $('#prj_date_start').val(data[5]);
            $('#emirateOpt1').val(data[6]);
            $('#e_client_opt').val(data[10]);
            $('#e_mc_opt').val(data[11]);
            $('#e_cons_opt').val(data[12]);
    });
});
$(document).ready(function(){
    var client="";
    $.ajax({
        url:'est_queries.php',
        method: 'POST',
        data:{'client_opt': client},
        success:function(data){
            $(document).find('#client_opt').html(data).change();
            $(document).find('#e_client_opt').html(data).change();
        }
    });  
    $.ajax({
        url:'est_queries.php',
        method: 'POST',
        data:{'mc_opt': client},
        success:function(data){
            $(document).find('#mc_opt').html(data).change();
            $(document).find('#e_mc_opt').html(data).change();
        }
    });
    $.ajax({
        url:'est_queries.php',
        method: 'POST',
        data:{'cons_opt': client},
        success:function(data){
            $(document).find('#cons_opt').html(data).change();
            $(document).find('#e_cons_opt').html(data).change();
        }
    });
    $.ajax({
        url:'est_queries.php',
        method: 'POST',
        data:{'ptype_opt': client},
        success:function(data){
            $(document).find('#ptype_opt').html(data).change();
            $(document).find('#ptype_opt1').html(data).change();
        }
    });
    $.ajax({
        url:'est_queries.php',
        method: 'POST',
        data:{'emirateOpt': client},
        success:function(data){
            $(document).find('#emirateOpt').html(data).change();
            $(document).find('#emirateOpt1').html(data).change();
        }
    });
    $.ajax({
        url:'est_queries.php',
        method: 'POST',
        data:{'ptype_opt': client},
        success:function(data){
            $(document).find('#ptype_opt').html(data).change();
            data="<option value=''>All</option>"+data;
            $(document).find('#type_opt').html(data).change();
            // $('#yr_opt').selectpicker('refresh');
        }
    });
    $.ajax({
        url:'est_queries.php',
        method: 'POST',
        data:{'emirateOpt': client},
        success:function(data){
            data="<option value=''>Select Emirate</option>"+data;
            $(document).find('#emirateOpt').html(data).change();
        }
    });
});
$(document).ready(function(){ // add client name 
    $(document).on('click', '#addClient',function() {
        var other_client=$('#oth_client').val();
        var clients_opt =$('#client_opt').val();
        var all_clients=[];
        if(other_client.length != 0){
            $('#client_opt').append('<option val="'+other_client+'">'+other_client+'</option>');
            $("#client_opt").selectpicker("refresh");
            all_clients.push(other_client);
            $('#cEmail').val("");
        }
        if(clients_opt.length != 0){
            for(i=0;i<clients_opt.length;i++){
                all_clients.push(clients_opt[i]);
            }
        }
        $('#client_opt').selectpicker('val',all_clients);
        $("#client_opt").selectpicker("refresh");
    });
});
$(document).ready(function(){ // add main contractor
    $(document).on('click', '#addMc',function() {
        var other_mc=$('#oth_mc').val();
        var mcs_opt =$('#mc_opt').val();
        var all_mcs=[];
        if(other_mc.length != 0){
            $('#mc_opt').append('<option val="'+other_mc+'">'+other_mc+'</option>');
            $("#mc_opt").selectpicker("refresh");
            all_mcs.push(other_mc);
            $('#cEmail').val("");
        }
        if(mcs_opt.length != 0){
            for(i=0;i<mcs_opt.length;i++){
                all_mcs.push(mcs_opt[i]);
            }
        }
        $('#mc_opt').selectpicker('val',all_mcs);
        $("#mc_opt").selectpicker("refresh");
    });
});
$(document).ready(function(){ // add consultant
    $(document).on('click', '#addcons',function() {
        var other_cons=$('#oth_cons').val();
        var cons_opt =$('#cons_opt').val();
        var all_cons=[];
        if(other_cons.length != 0){
            $('#cons_opt').append('<option val="'+other_cons+'">'+other_cons+'</option>');
            $("#cons_opt").selectpicker("refresh");
            all_cons.push(other_cons);
            $('#cEmail').val("");
        }
        if(cons_opt.length != 0){
            for(i=0;i<cons_opt.length;i++){
                all_cons.push(cons_opt[i]);
            }
        }
        $('#cons_opt').selectpicker('val',all_cons);
        $("#cons_opt").selectpicker("refresh");
    });
});
</script>
<?php
include('../includes/scripts.php');
include('../includes/footer.php');
?>