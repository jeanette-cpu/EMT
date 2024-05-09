<?php
include('../security.php');
include('../includes/header.php'); 
include('../includes/estimation_nav.php');
include('est_queries.php');
if(isset($_POST['search'])){
    if(isset($_POST['type_id'])){
        $type=$_POST['type_id'];
        $type_q=" AND prj.PE_Type LIKE '%$type%'";
    }
    else{ $type_q=NULL;}
    if(isset($_POST['ave_bua'])){
        $bua=$_POST['ave_bua'];
        if($bua=="less 1000"){
            $ave_bua_q=" AND est.Est_Ave_BUA<1000";
        }
        elseif($bua=="1000-1500"){
            $ave_bua_q=" AND est.Est_Ave_BUA BETWEEN 1000 AND 1500";
        }
        elseif($bua=="1500-2000"){
            $ave_bua_q=" AND est.Est_Ave_BUA BETWEEN 1500 AND 2000";
        }
        elseif($bua=="more 2000"){
            $ave_bua_q=" AND est.Est_Ave_BUA>2000";
        }
        else{$ave_bua_q=NULL;}
    }
    else{$ave_bua_q=NULL;}
    if(isset($_POST['mc_id'])){
        $mc_ids=implode("', '", $_POST['mc_id']);
        $mc_q=" AND prj.Main_Contractor_Id IN ('$mc_ids')";
    }
    else{ $mc_q=NULL; }
    if(isset($_POST['cons_id'])){
        $cons_ids=implode("', '", $_POST['cons_id']);
        $cons_q=" AND prj.Consultant_Id IN ('$cons_ids')";
    }
    else{$cons_q=NULL;}
    if(isset($_POST['client_id'])){
        $client_ids=implode("', '", $_POST['client_id']);
        $client_q=" AND prj.Client_Id IN ('$client_ids')";
    }
    else{$client_q=NULL;}
    if(isset($_POST['month'])){
        $months=implode("', '", $_POST['month']);
        $month_q =" AND MONTH(prj.PE_Date) IN ('$months')";
    }
    else{$month_q=NULL;}
    if(isset($_POST['year'])){
        $years=implode("', '", $_POST['year']);
        $yr_q =" AND YEAR(prj.PE_Date) IN ('$years')";
    }
    else{$yr_q=NULL; }
    if(isset($_POST['stat_id'])){
        $stat_ids=implode("', '", $_POST['stat_id']);
        // echo $stat_ids;
        $stat_q=" AND est.Estimate_Status_Id IN ('$stat_ids')";
    }
    else{ $stat_q=NULL;}
    $est="SELECT * FROM estimate AS est
        LEFT JOIN project_estimation as prj ON est.Prj_Est_Id=prj.Prj_Est_Id
        WHERE est.Est_Status=1 AND prj.PE_Status=1 
        $type_q $ave_bua_q $mc_q $cons_q $client_q $month_q $yr_q $stat_q
        ORDER BY prj.PE_Date";
}
else{
    $est="SELECT * FROM estimate AS est
        LEFT JOIN project_estimation as prj ON est.Prj_Est_Id=prj.Prj_Est_Id
        WHERE est.Est_Status=1 AND prj.PE_Status=1 ORDER BY prj.PE_Date";
}
$est_run=mysqli_query($connection,$est);
?>
<div class="container-fluid">
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h5 class="m-0 font-weight-bold text-primary"><i class="fa fa-file mr-2" aria-hidden="true"></i> Estimations
            <!-- BUTTON -->
            <button type="button" class="btn btn-primary float-right" data-toggle="modal" data-target="#adduser">
                <i class="fa fa-plus" aria-hidden="true"></i>
                Add Estimate
            </button></h5>
        </div>
        <div class="card-body">
            <form action="est_project.php" method="POST">
            <div class="form-row mb-2">
                <div class="col-1">
                    <label for="">Type</label>
                    <select name="type_id" id="type_opt" class="form-control filter selectpicker">
                        <option value="">All</option>
                        <option value="Residential">Residential</option>
                        <option value="Hotel">Hotel</option>
                        <option value="Car Parking">Car Parking</option>
                        <option value="Mosque">Mosque</option>
                        <option value="Labour Camp">Labour Camp</option>
                        <option value="Commercial">Commercial</option>
                    </select>
                </div>
                <div class="col-1">
                    <label for="">Ave BUA</label>
                    <select name="ave_bua" id="ave_bua1" class="form-control selectpicker">
                        <option value="">All</option>
                        <option value="less 1000"><1000</option>
                        <option value="1000-1500">1000-1500</option>
                        <option value="1500-2000">1500-2000</option>
                        <option value="more 2000">>2000</option>
                    </select>
                </div>
                <div class="col-2">
                    <label for="">Main Contractor</label>
                    <select name="mc_id[]" id="mc_opt" class="form-control filter selectpicker" multiple></select>
                </div>
                <div class="col-2">
                    <label for="">Consultant</label>
                    <select name="cons_id[]" id="cons_opt" class="form-control filter selectpicker" multiple></select>
                </div>
                <div class="col-2">
                    <label for="">Client</label>
                    <select name="client_id[]" id="client_opt" class="form-control filter selectpicker" multiple></select>
                </div>
                <div class="col-1">
                    <label for="">Month</label>
                    <select name="month[]" id="month_opt" class="form-control filter selectpicker" multiple></select>
                </div>
                <div class="col-1">
                    <label for="">Year</label>
                    <select name="year[]" id="yr_opt" class="form-control filter selectpicker" multiple></select>
                </div>
                <div class="col-1">
                    <label for="">Status</label>
                    <select name="stat_id[]" id="status_opt2" class="form-control filter selectpicker" multiple></select>
                </div>
                <div class="col-1">
                    <label for="" class="invisible">1</label><br>
                    <button type="submit" name="search" class="btn btn-warning" id="search">Search</button>
                </div>
            </div>
            </form>
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-sm" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <th class="d-none"></th>
                        <th class="d-none"></th>
                        <th class="d-none"></th>
                        <th class="d-none"></th>
                        <th class="d-none"></th>
                        <th class="d-none"></th>
                        <th class="d-none"></th>
                        <th class="d-none"></th>
                        <th class="d-none"></th>
                        <th>Project Code</th>
                        <th>Project Name</th>
                        <th>Category</th>
                        <th>Type</th>
                        <th>Department</th>
                        <th>System</th>
                        <th>Location</th>
                        <th>Client</th>
                        <th>Main Contractor</th>
                        <th>Consultant</th>
                        <th>Date</th>
                        <th>Ave BUA</th>
                        <th>Total BUA</th>
                        <th>Total System Price</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </thead>
                    <tbody>
                    <?php
                        if(mysqli_num_rows($est_run)>0){
                            while($row = mysqli_fetch_assoc($est_run)){
                                $est_id=$row['Estimate_Id'];
                                $prj_id=$row['Prj_Est_Id'];
                                $code=$row['PE_Code'];
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
                                $sys_id=$row['Prj_Sys_Id']; $sys_name=sysName($sys_id);
                                $dept_name=sysDeptName($sys_id); $dept_id=sysDeptId($sys_id);
                                $ave_bua=$row['Est_Ave_BUA']; $ave_bua=number_format($ave_bua);
                                $tot_bua=$row['Est_Total_BUA']; $tot_bua=number_format($tot_bua);
                                $stat_id=$row['Estimate_Status_Id']; $stat_name=statName($stat_id);
                                $no_apt=$row['Est_No_Appartment'];
                                $no_br=$row['Est_No_Bathroom'];
                                $con_load=$row['Est_Connected_Load']; $con_load=number_format($con_load);
                                $tot_ton=$row['Est_Total_Tonnage']; $tot_ton=number_format($tot_ton);
                                $tot_price=$row['Est_Total_Price']; $tot_price=number_format($tot_price);
                                ?>
                        <tr>
                            <td class="d-none"><?php echo $est_id; ?></td><!-- 0 -->
                            <td class="d-none"><?php echo $prj_id; ?></td><!-- 1 -->
                            <td class="d-none"><?php echo $dept_id; ?></td><!-- 2 -->
                            <td class="d-none"><?php echo $sys_id; ?></td><!-- 3 -->
                            <td class="d-none"><?php echo $no_apt; ?></td><!-- 4 -->
                            <td class="d-none"><?php echo $no_br; ?></td><!-- 5 -->
                            <td class="d-none"><?php echo $con_load; ?></td><!-- 6 -->
                            <td class="d-none"><?php echo $tot_ton; ?></td><!-- 7 -->
                            <td class="d-none"><?php echo $stat_id; ?></td><!-- 8 -->                            
                            <td><a href="est_prj_system.php?id=<?php echo $prj_id?>" > <?php echo $code; ?></a></td> <!--  -->
                            <td><?php echo $name; ?></td> <!-- 10 -->
                            <td><?php echo $category; ?></td> <!--  -->
                            <td><?php echo $type_name; ?></td>
                            <td><?php echo $dept_name;?></td> <!-- 13 -->
                            <td><?php echo $sys_name;?></td>
                            <td><?php echo $emirate; ?></td> <!--  -->
                            <td><?php echo $client_name; ?></td> <!-- 16 -->
                            <td><?php echo $mc_name; ?></td> <!-- 17 -->
                            <td><?php echo $cons_name; ?></td>
                            <td><?php echo $date; ?></td>
                            <td><?php echo $ave_bua;?></td><!-- 20 -->
                            <td><?php echo $tot_bua;?></td>
                            <td><?php echo $tot_price;?></td>
                            <td><?php echo $stat_name;?></td>
                            <td class="btn-group text-center">
                                <!-- EDIT -->
                                <button type="button" class="btn btn-success editProject" data-toggle="modal" data-target="#EditProjectModal">
                                    <i class="fa fa-edit" area-hidden="true"></i>
                                </button>
                                <!-- DELETE button PERMANENT -->
                                <form action="code.php" method="POST">
                                    <input type="hidden" name="est_id" value="<?php echo $est_id;?>">
                                        <button type="submit" name ="delEs" class="btn btn-danger d-inline">
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

<!-- Modal Add Estimation -->
<div class="modal fade bd-example-modal-xl" id="adduser" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl" role="document">
    <div class="modal-content">
      <div class="modal-header">
          <!-- MODAL TITLE -->
        <h5 class="modal-title" id="exampleModalLabel"><i class="fa fa-file mr-2" aria-hidden="true"></i>Add Estimation</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
    <form action="code.php" method="post">
      <div class="modal-body">
            <div class="form-row">
                <div class="col-6">
                    <div class="form-group">
                        <label class="font-weight-bold" for="">Project</label>
                        <select name="prj_id" id="prj_opt" class="form-control "></select>
                    </div>
                </div>
                <div class="col-3">
                    <label class="font-weight-bold" for="">Department</label>
                    <select name="dept_id" id="dept_opt" class="form-control "></select>
                </div>
                <div class="col-3">
                    <label class="font-weight-bold" for="">System</label>
                    <select name="sys_id" id="sys_opt" class="form-control "></select>
                </div>
            </div>
            <div class="form-row">
                <div class="col-3">
                    <div class="form-group">
                        <label for="">No. of Appartment</label>
                        <input type="text" name="no_apt" class="form-control">
                    </div>
                </div>
                <div class="col-3">
                    <label for="">No. of Bathroom</label>
                    <input type="text" name="no_br" class="form-control">
                </div>
                <div class="col-3">
                    <label for="">Connected Load</label>
                    <input type="text" name="con_load" class="form-control">
                </div>
                <div class="col-3">
                    <label for="">Total Tonnage</label>
                    <input type="text" name="tot_ton" class="form-control">
                </div>
            </div>
            <div class="form-row">
                <div class="col-6">
                    <div class="form-group">
                        <label for="">Average BUA</label>
                        <input type="text" name="ave_bua" class="form-control">
                    </div>
                </div>
                <div class="col-6">
                    <label for="">Total BUA</label>
                    <input type="text" name="tot_bua" class="form-control">
                </div>
            </div>
            <div class="form-row">
                <div class="col-6">
                    <label for="">Total System Price</label>
                    <input type="text" name="tot_sysP" class="form-control">
                </div>
                <div class="col-6">
                    <label for="">Status</label>
                    <select name="status_id" id="status_opt1" class="form-control"></select>
                </div>
            </div>
        <!-- END FORM -->
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-times" aria-hidden="true"></i> Close</button>
            <button type="submit" name="addEs" class="btn btn-success"><i class="fa fa-check" aria-hidden="true"></i> Save</button>
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
        <h5 class="modal-title" id="exampleModalLabel"><i class="fa fa-file mr-1" aria-hidden="true"></i> Edit Estimation Details</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
      </div>
            <form action="code.php" method="POST">
                <div class="modal-body">
                    <!-- THE FORM -->
            <input type="hidden" name="est_id" id="es_id" class="form-control" >
            <div class="form-row">
                <div class="col-6">
                    <div class="form-group">
                        <label class="font-weight-bold" for="">Project</label>
                        <select name="prj_id" id="prj_opt1" class="form-control "></select>
                    </div>
                </div>
                <div class="col-3">
                    <label class="font-weight-bold" for="">Department</label>
                    <select name="dept_id" id="dept_opt1" class="form-control"></select>
                </div>
                <div class="col-3">
                    <label class="font-weight-bold" for="">System</label>
                    <select name="sys_id" id="sys_opt1" class="form-control "></select>
                </div>
            </div>
            <div class="form-row">
                <div class="col-3">
                    <div class="form-group">
                        <label for="">No. of Appartment</label>
                        <input type="text" name="no_apt" id="no_apt" class="form-control">
                    </div>
                </div>
                <div class="col-3">
                    <label for="">No. of Bathroom</label>
                    <input type="text" name="no_br" id="no_br" class="form-control">
                </div>
                <div class="col-3">
                    <label for="">Connected Load</label>
                    <input type="text" name="con_load" id="con_load" class="form-control">
                </div>
                <div class="col-3">
                    <label for="">Total Tonnage</label>
                    <input type="text" name="tot_ton" id="tot_ton" class="form-control">
                </div>
            </div>
            <div class="form-row">
                <div class="col-6">
                    <div class="form-group">
                        <label for="">Average BUA</label>
                        <input type="text" name="ave_bua" id="ave_bua" class="form-control">
                    </div>
                </div>
                <div class="col-6">
                    <label for="">Total BUA</label>
                    <input type="text" name="tot_bua" id="tot_bua" class="form-control">
                </div>
            </div>
            <div class="form-row">
                <div class="col-6">
                    <label for="">Total System Price</label>
                    <input type="text" name="tot_sysP" id="tot_sysP" class="form-control">
                </div>
                <div class="col-6">
                    <label for="">Status</label>
                    <select name="status_id" id="status_opt3" class="form-control"></select>
                </div>
            </div>
        <!-- END FORM -->
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-times" aria-hidden="true"></i> Close</button>
            <button type="submit" name="editEs" class="btn btn-success"><i class="fa fa-check" aria-hidden="true"></i> Save</button>
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
            $('#es_id').val(data[0]);
            $('#prj_opt1').val(data[1]);
            $('#dept_opt1').val(data[2]);
            var id =data[2];
            var sys_id=data[3];
            $.ajax({
                url:'est_queries.php',
                method: 'POST',
                data:{'sys_opt': id},
                success:function(data){
                    $(document).find('#sys_opt1').html(data).change();
                    $('#sys_opt1').val(sys_id);
                }
            });  
            $('#no_apt').val(data[4]);
            $('#no_br').val(data[5]);
            $('#con_load').val(data[6]);
            $('#tot_ton').val(data[7]);
            $('#ave_bua').val(data[20]);
            $('#tot_bua').val(data[21]);
            $('#tot_sysP').val(data[22]);
            $('#status_opt3').val(data[8]);
            
    });
});
$(document).ready(function () {
    // $('#search').on('click', function() {
    //     var type_id=$('#type_opt').val();
    //     var ave_bua=$('#ave_bua1').val();
    //     var mc_id=$('#mc_opt').val();
    //     var cons_id=$('#cons_opt').val();
    //     var client_id=$('#client_opt').val();
    //     var month=$('#month_opt').val();
    //     var year=$('#yr_opt').val();
    //     var status=$('#status_opt2').val();

    //     $.ajax({
    //         url:'est_project.php',
    //         method: 'POST',
    //         data:{'search': dept_id},
    //         success:function(data){
    //             // $(document).find('#sys_opt').html(data).change();
    //             // $(window).load();
    //         }
    //     });  
    // });
});
$(document).ready(function(){
    $('#dept_opt').on('change', function() {
        var dept_id = $(this).val();
        $.ajax({
            url:'est_queries.php',
            method: 'POST',
            data:{'sys_opt': dept_id},
            success:function(data){
                $(document).find('#sys_opt').html(data).change();
            }
        });  
    });
    $('#dept_opt1').on('change', function() {
        var dept_id = $(this).val();
        $.ajax({
            url:'est_queries.php',
            method: 'POST',
            data:{'sys_opt': dept_id},
            success:function(data){
                $(document).find('#sys_opt1').html(data).change();
            }
        });  
    });
});
$(document).ready(function(){
    var client="";
    $.ajax({
        url:'../PMS/P_ADMIN/ajax_dept.php',
        method: 'POST',
        data:{'dept_opt': client},
        success:function(data){
            $(document).find('#dept_opt').html(data).change();
            $(document).find('#dept_opt1').html(data).change();
        }
    });
    $.ajax({
        url:'est_queries.php',
        method: 'POST',
        data:{'prj_opt': client},
        success:function(data){
            $(document).find('#prj_opt').html(data).change();
            $(document).find('#prj_opt1').html(data).change();
        }
    });  
    $.ajax({
        url:'est_queries.php',
        method: 'POST',
        data:{'status_opt': client},
        success:function(data){
            $(document).find('#status_opt').html(data).change();
            $(document).find('#status_opt1').html(data).change();
            $(document).find('#status_opt3').html(data).change();
            data="<option value=''>All</option>"+data;
            $(document).find('#status_opt2').html(data).change();
            $('#status_opt2').selectpicker('refresh');
        }
    });
    $.ajax({
        url:'est_queries.php',
        method: 'POST',
        data:{'status_opt': client},
        success:function(data){
            $(document).find('#status_opt1').html(data).change();
        }
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
            $('#client_opt').selectpicker('refresh');
        }
    });  
    $.ajax({
        url:'est_queries.php',
        method: 'POST',
        data:{'mc_opt': client},
        success:function(data){
            $(document).find('#mc_opt').html(data).change();
            $('#mc_opt').selectpicker('refresh');
        }
    });
    $.ajax({
        url:'est_queries.php',
        method: 'POST',
        data:{'cons_opt': client},
        success:function(data){
            $(document).find('#cons_opt').html(data).change();
            $('#cons_opt').selectpicker('refresh');
        }
    });
    $.ajax({
        url:'est_queries.php',
        method: 'POST',
        data:{'month_opt': client},
        success:function(data){
            $(document).find('#month_opt').html(data).change();
            $('#month_opt').selectpicker('refresh');
        }
    });
    $.ajax({
        url:'est_queries.php',
        method: 'POST',
        data:{'yr_opt': client},
        success:function(data){
            $(document).find('#yr_opt').html(data).change();
            $('#yr_opt').selectpicker('refresh');
        }
    });
});
</script>
<?php
include('../includes/scripts.php');
include('../includes/footer.php');
?>