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
                        <th>Category</th>
                        <th>Type</th>
                        <th class="d-none"></th>
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
                                if($type=='CP'){
                                    $type_name="Car Parking";
                                }
                                elseif($type=='LC'){
                                    $type_name="Labour Camp";
                                }
                                else{
                                    $type_name=ucfirst($type);
                                }
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
                            <td><?php echo $category; ?></td> <!-- 3 -->
                            <td><?php echo $type_name; ?></td> <!-- 4 -->
                            <td class="d-none"><?php echo $date; ?></td> <!-- 5 -->
                            <td class=""><?php echo $emirate; ?></td> <!-- 6 -->
                            <td class=""><?php echo $client_name; ?></td>
                            <td class=""><?php echo $mc_name; ?></td>
                            <td class=""><?php echo $cons_name; ?></td>
                            <td class="d-none"><?php echo $client_id; ?></td>
                            <td class="d-none"><?php echo $mc_id; ?></td>
                            <td class="d-none"><?php echo $cons_id; ?></td>
                            <td class="btn-group text-center">
                                <!-- EDIT -->
                                <button type="button" class="btn btn-success editProject" data-toggle="modal" data-target="#EditProjectModal">
                                    <i class="fa fa-edit" area-hidden="true"></i>
                                </button>
                                <!-- DELETE button PERMANENT -->
                                <form action="code.php" method="POST">
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
                <div class="col-12">
                    <div class="form-group">
                        <label class="font-weight-bold" for="">Project Name</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>
                </div>
            </div>
            <div class="form-row">
                <div class="col-6">
                    <label class="font-weight-bold" for="">Category</label>
                    <div class="form-group">
                        <label class="radio mr-3 ml-4">
                            <input type="radio" name="category" class="mr-1" value="Building" required>Building
                        </label>
                        <label class="radio mr-3">
                            <input type="radio" name="category" class="mr-1" value="Villa" required>Villa
                        </label> 
                    </div>
                </div>
                <div class="col-6">
                    <label class="font-weight-bold" >Date</label>
                    <input type="date" name="date" class="form-control" required>
                </div>
            </div>
            <div class="form-row">
                <div class="col-12">
                    <label class="font-weight-bold" for="">Project Type</label>
                    <div class="form-group">
                        <label class="radio mr-3 ml-4">
                            <input type="radio" name="type" value="Residential" class="mr-1" required>Residential
                        </label>
                        <label class="radio mr-3">
                            <input type="radio" name="type" value ="Hotel" class="mr-1" required>Hotel
                        </label>
                        <label class="radio">
                            <input type="radio" name="type" value ="Car Parking" class="mr-1" required>Car Parking
                        </label>   
                    
                        <label class="radio mr-3 ml-4">
                            <input type="radio" name="type"  value ="Mosque" class="mr-1" required>Mosque
                        </label>
                        <label class="radio mr-3">
                            <input type="radio" name="type" value ="Labour Camp" class="mr-1" required>Labour Camp
                        </label>
                        <label class="radio">
                            <input type="radio" name="type" value ="Commercial" class="mr-1" required>Commercial
                        </label>   
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label class="font-weight-bold" for="">Emirate Location</label>
                    <div class="form-group"> 
                        <div class="form-group col-md-12">
                            <label class="radio mr-3 ml-4">
                                <input type="radio" name="location" value="Abu Dhabi" class="mr-1" required>Abu Dhabi
                            </label>
                            <label class="radio mr-3">
                                <input type="radio" name="location" value="Ajman" class="mr-1" required>Ajman
                            </label>
                            <label class="radio mr-3">
                                <input type="radio" name="location" value="Dubai" class="mr-1" required>Dubai
                            </label>
                            <label class="radio mr-3">
                                <input type="radio" name="location" value="Fujairah" class="mr-1" required>Fujairah
                            </label>          
                        </div>
                    </div>     
                    <div class="form-group">
                        <div class="form-group col-md-12">
                            <label class="radio mr-3 ml-4">
                                <input type="radio" name="location" value="Ras al Khaimah" class="mr-1" required>Ras al Khaimah
                            </label>
                            <label class="radio mr-3">
                                <input type="radio" name="location" value="Sharjah" class="mr-1" required>Sharjah
                            </label>
                            <label class="radio mr-3">
                                <input type="radio" name="location" value="Umm al Quwain" class="mr-1" required>Umm al Quwain
                            </label>
                        </div>
                    </div> 
            </div>
            <div class="form-row">
                <div class="col-4">
                    <div class="form-group">
                        <label class="font-weight-bold" for="">Client Name</label>
                        <select name="client_id" id="client_opt" class="form-control"></select>
                    </div>
                </div>
                <div class="col-4">
                    <div class="form-group">
                        <label class="font-weight-bold" for="">Main Contractor Name</label>
                        <select name="mc_id" id="mc_opt" class="form-control"></select>
                    </div>
                </div>
                <div class="col-4">
                    <div class="form-group">
                        <label class="font-weight-bold" for="">Consultant Name</label>
                        <select name="cons_id" id="cons_opt" class="form-control"></select>
                    </div>  
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
        <h5 class="modal-title" id="exampleModalLabel"><i class="fa fa-home mr-1" aria-hidden="true"></i> Edit Project Details</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
      </div>
            <form action="code.php" method="POST">
                <div class="modal-body">
                    <!-- THE FORM -->
                        <input type="hidden" name="prj_id" id="update_id" class="form-control" >
                <div class="form-row">
                    <div class="col-3">
                        <div class="form-group">
                            <label class="font-weight-bold" for="">Code</label>
                            <input type="text" name="e_prj_code" id="prj_code" class="form-control" required readonly>
                        </div>
                    </div>
                    <div class="col-9">
                        <div class="form-group">
                            <label class="font-weight-bold" for="">Project Name</label>
                            <input type="text" name="name" id="prj_name" class="form-control" required>
                        </div>
                    </div>
                </div>
                <div class="form-row">
                    <div class="col-6">
                        <div class="form-group">
                            <label class="font-weight-bold">Category</label><br>
                            <input type="radio" name="category" id="Building" class="radio mr-4 ml-4" value="Building" required>Building
                            <input type="radio" name="category" id="Villa" class="radio mr-4 ml-3" value="Villa" required>Villa
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label class="font-weight-bold" >Date</label>
                                <input type="date" name="date" id="prj_date_start" class="form-control" required>
                            </div>
                        </div>
                    </div>
                </div>
                <div>
                    <label class="font-weight-bold" for="">Project Type</label>
                    <div class="form-group">
                        <label class="radio mr-3 ml-4">
                            <input type="radio" name="type" id="Residential" value ="Residential" class="mr-1" required>Residential
                        </label>
                        <label class="radio mr-3">
                            <input type="radio" name="type" id="Hotel" value ="Hotel" class="mr-1" required>Hotel
                        </label>
                        <label class="radio">
                            <input type="radio" name="type" id="CP" value ="Car Parking" class="mr-1" required>Car Parking
                        </label>    
                        <label class="radio mr-3 ml-4">
                            <input type="radio" name="type" id="Mosque"  value ="Mosque" class="mr-1" required>Mosque
                        </label>
                        <label class="radio mr-3">
                            <input type="radio" name="type" id="LC" value ="Labour Camp" class="mr-1" required>Labour Camp
                        </label>
                        <label class="radio">
                            <input type="radio" name="type" id="Commercial" value ="Commercial" class="mr-1" required>Commercial
                        </label>   
                    </div>
                </div>
                <div class="form-group">
                    <label class="font-weight-bold" for="">Emirate Location</label>
                    <div class="form-group"> 
                        <div class="form-group col-md-12">
                            <label class="radio mr-3 ml-4">
                                <input type="radio" name="location" id="AD" value="Abu Dhabi" class="mr-1" required>Abu Dhabi
                            </label>
                            <label class="radio mr-3">
                                <input type="radio" name="location" id="Ajman" value="Ajman" class="mr-1" required>Ajman
                            </label>
                            <label class="radio mr-3">
                                <input type="radio" name="location" id="Dubai" value="Dubai" class="mr-1" required>Dubai
                            </label>
                            <label class="radio mr-3">
                                <input type="radio" name="location" id="Fujairah" value="Fujairah" class="mr-1" required>Fujairah
                            </label>          
                        </div>
                    </div>     
                    <div class="form-group">
                        <div class="form-group col-md-12">
                            <label class="radio mr-3 ml-4">
                                <input type="radio" name="location" id="rak" value="Ras al Khaimah" class="mr-1" required>Ras al Khaimah
                            </label>
                            <label class="radio mr-3">
                                <input type="radio" name="location" id="Sharjah" value="Sharjah" class="mr-1" required>Sharjah
                            </label>
                            <label class="radio mr-3">
                                <input type="radio" name="location" id="uaq" value="Umm al Quwain" class="mr-1" required>Umm al Quwain
                            </label>
                        </div>
                    </div> 
                </div>
                <div class="form-row">
                    <div class="col-4">
                        <label for="">Client</label>
                        <select name="client_id" id="e_client_opt" class="form-control"></select>
                    </div>
                    <div class="col-4">
                        <label for="">Main Control</label>
                        <select name="mc_id" id="e_mc_opt" class="form-control"></select>
                    </div>
                    <div class="col-4">
                        <label for="">Consultant</label>
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
            var item = data[3];
            $('#'+item).val(data[3]).prop('checked', true);

            var type = data[4];
            var prj_type = "";
            if(type==='Car Parking')
            {prj_type='CP';}
            else if(type==='Labour Camp')
            {prj_type='LC';}
            else{prj_type=type;}
            $('#'+prj_type).val(prj_type).prop('checked', true);
            $('#prj_date_start').val(data[5]);

            var location = data[6];
            var prj_location="";
            if(location ==='Abu Dhabi')
            {prj_location='AD';}
            else if (location ==='Ras al Khaimah')
            {prj_location='rak';}
            else if (location ==='Umm al Quwain')
            {prj_location='uaq';}
            else{prj_location=location;}

            $('#'+prj_location).val(prj_location).prop('checked', true);
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
});
</script>
<?php
include('../includes/scripts.php');
include('../includes/footer.php');
?>