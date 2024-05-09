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
        ORDER BY prj.PE_Code ASC";
}
else{
    $est="SELECT * FROM estimate AS est LEFT JOIN project_estimation as prj ON est.Prj_Est_Id=prj.Prj_Est_Id WHERE est.Est_Status=1 AND prj.PE_Status=1 ORDER BY prj.PE_Date ASC";
}
// ECHO $est;
$est_run=mysqli_query($connection,$est);
// echo lastPrjCode();
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
                    <select name="type_id" id="type_opt" class="form-control filter"></select>
                </div>
                <div class="col-1">
                    <label for="">Avg BUA Aprt</label>
                    <select name="ave_bua" id="" class="form-control selectpicker">
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
                        <th><span class="invisible">DdDD</span> Date <span class="invisible">DdDD</span></th>
                        <th>Category</th>
                        <th>Location</th>
                        <th>Client</th>
                        <th>Main Contractor</th>
                        <th>Consultant</th>
                        <th>System</th>
                        <th>Avg BUA</th>
                        <th>Total BUA</th>
                        <th>Total System Price</th>
                        <th>BUA Rate</th>
                        <th>Status</th>
                        <th class="d-none"></th>
                        <th class="d-none"></th>
                        <th class="d-none"></th>
                        <th class="d-none"></th>
                        <th class="d-none"></th>
                        <th class="d-none"></th>
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
                                $date=$row['PE_Date'];
                                $emirate=$row['PE_Emirate_Location'];
                                $client_id=$row['Client_Id'];  $client_name=clientName($client_id);
                                $mc_id=$row['Main_Contractor_Id']; $mc_name=mcName($mc_id);
                                $cons_id=$row['Consultant_Id']; $cons_name=consName($cons_id);
                                $sys_id=$row['Prj_Sys_Id']; $sys_name=sysName($sys_id);
                                $ave_bua=$row['Est_Ave_BUA'];
                                $tot_bua=$row['Est_Total_BUA']; 
                                $stat_id=$row['Estimate_Status_Id']; $stat_name=statName($stat_id);
                                $no_apt=$row['Est_No_Appartment'];
                                $no_br=$row['Est_No_Bathroom'];
                                $con_load=$row['Est_Connected_Load']; $con_load=number_format($con_load,2);
                                $tot_ton=$row['Est_Total_Tonnage']; $tot_ton=number_format($tot_ton,2);

                                //system prices/department
                                $hvac_sp=$row['HVAC_sp']; 
                                $elec_sp=$row['Electric_sp']; 
                                $plumb_sp=$row['Plumbing_sp'];
                                $ff_sp=$row['FF_sp']; 
                                $fa_sp=$row['FA_sp']; 
                                $lpg_sp=$row['LPG_sp'];
                                $tot_price=$row['Est_Total_Price']; $tot_price=number_format($tot_price,2);
                                $bua_rate=$tot_bua/$ave_bua; $bua_rate=number_format($bua_rate,2);
                                $ave_bua=number_format($ave_bua,2);
                                $tot_bua=number_format($tot_bua,2);
                                ?>
                        <tr>
                            <td class="d-none"><?php echo $est_id; ?></td><!-- 0 -->
                            <td class="d-none"><?php echo $prj_id; ?></td><!-- 1 -->
                            <td class="d-none"><?php echo $code.' '.$name; ?></td><!-- 2 -->
                            <td class="d-none"><?php echo $sys_id; ?></td><!-- 3 -->
                            <td class="d-none"><?php echo $no_apt; ?></td><!-- 4 -->
                            <td class="d-none"><?php echo $no_br; ?></td><!-- 5 -->
                            <td class="d-none"><?php echo $con_load; ?></td><!-- 6 -->
                            <td class="d-none"><?php echo $tot_ton; ?></td><!-- 7 -->
                            <td class="d-none"><?php echo $stat_id; ?></td><!-- 8 -->                            
                            <td><a href="est_prj_system.php?id=<?php echo $prj_id?>" > <?php echo $code; ?></a></td> <!--  -->
                            <td><?php echo $name; ?></td> <!-- 10 -->
                            <td><?php echo $date; ?></td> <!--  -->
                            <td><?php echo $type; ?></td>
                            <td><?php echo $emirate; ?></td> <!--  -->
                            <td><?php echo $client_name; ?></td> <!--  -->
                            <td><?php echo $mc_name; ?></td> <!--15  -->
                            <td><?php echo $cons_name; ?></td>
                            <td><?php echo $sys_name;?></td>
                            <td class="text-right"><?php echo $ave_bua;?></td><!-- 18 -->
                            <td class="text-right"><?php echo $tot_bua;?></td>
                            <td class="text-right"><?php echo $tot_price;?></td>
                            <td class="text-right"><?php echo $bua_rate;?></td>
                            <td><?php echo $stat_name;?></td>
                            <td class="d-none"><?php echo $hvac_sp;?></td> <!-- 22-->
                            <td class="d-none"><?php echo $elec_sp;?></td>
                            <td class="d-none"><?php echo $plumb_sp;?></td>
                            <td class="d-none"><?php echo $ff_sp;?></td>
                            <td class="d-none"><?php echo $fa_sp;?></td>
                            <td class="d-none"><?php echo $lpg_sp;?></td> <!-- 27-->
                            <td class="btn-group text-center">
                                <!-- EDIT -->
                                <button type="button" class="btn btn-success editProject" data-toggle="modal" data-target="#EditProjectModal">
                                    <i class="fa fa-edit" area-hidden="true"></i>
                                </button>
                                <!-- DELETE button PERMANENT -->
                                <form action="code.php" method="POST">
                                    <input type="hidden" name="est_id" value="<?php echo $est_id;?>">
                                    <input type="hidden" name="prj_id" value="<?php echo $prj_id;?>">
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
        <h5 class="modal-title text-primary" id="exampleModalLabel"><i class="fa fa-file mr-2" aria-hidden="true"></i>Add Estimation</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
    <form action="code.php" method="post">
      <div class="modal-body">
            <!-- <h4 class="text-dark">PROJECT DETAILS</h4>
            <div class="form-row">
                <div class="col-2">
                    <label for="" class="font-weight-bold">Project Code</label>
                    <input type="text" name="prj_code" class="form-control" value="<?php echo lastPrjCode();?>" required readonly>
                </div>
                <div class="col-6">
                    <div class="form-group">
                        <label class="font-weight-bold" for="">Project Name</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>
                </div>
                <div class="col-2">
                    <label class="font-weight-bold" >Project Type</label>
                    <select name="prj_type" class="form-control" id="ptype_opt" required></select>
                </div>
                <div class="col-2">
                    <label class="font-weight-bold" >Date</label>
                    <input type="date" name="date" class="form-control" required>
                </div>
            </div> -->
            <!-- <div class="form-row">
                <div class="col-3">
                    <div class="form-group">
                        <label class="font-weight-bold" for="">Emirate Location</label>
                        <select name="location" id="emirateOpt" class="form-control" required></select>
                    </div>
                </div>
                <div class="col-3">
                    <div class="form-group">
                        <label class="font-weight-bold" for="">Client Name</label>
                        <select name="client_id" id="client_opt1" class="form-control" ></select>
                    </div>
                </div>
                <div class="col-3">
                    <div class="form-group">
                        <label class="font-weight-bold" for="">Main Contractor Name</label>
                        <select name="mc_id" id="mc_opt1" class="form-control" ></select>
                    </div>
                </div>
                <div class="col-3">
                    <div class="form-group">
                        <label class="font-weight-bold" for="">Consultant Name</label>
                        <select name="cons_id" id="cons_opt1" class="form-control" ></select>
                    </div>  
                </div>
            </div> -->
            <div class="form-row">
                <div class="col-6">
                    <label for="">Project: </label>
                    <select name="prj_id" id="prj_opt" class="form-control" required></select>
                </div>
            </div>
            <h4 class="text-dark mt-2">ESTIMATION DETAILS</h4>
            <div class="form-row">
                <div class="col-3">
                    <label class="" for="">System Type</label>
                    <select name="sys_id" id="type_opt1" class="form-control" required></select>
                </div>
                <div class="col-3">
                    <div class="form-group">
                        <label for="">No. of Appartment/Villa</label>
                        <input type="decimal" name="no_apt" id="no_apt1" class="form-control addEst" required>
                    </div>
                </div>
                <div class="col-4">
                    <label for="">Total BUA</label>
                    <input type="decimal" name="tot_bua" id="tot_bua1" class="form-control addEst" required>
                </div>
                <div class="col-2">
                    <div class="form-group">
                        <label for="">Average BUA</label>
                        <input type="decimal" name="ave_bua" id="ave_bua1" class="form-control">
                    </div>
                </div>
                
            </div>
            <div class="form-row mt-2">
                <div class="col-2">
                    <label for="">HVAC System Price</label>
                    <input type="decimal" name="hvac_sp" id="hvac_sp" class="form-control sp">
                </div>
                <div class="col-2">
                    <label for="">Electrical System Price</label>
                    <input type="decimal" name="elec_sp" id="elec_sp" class="form-control sp">
                </div>
                <div class="col-2">
                    <label for="">Plumbing System Price</label>
                    <input type="decimal" name="plumb_sp" id="plumb_sp" class="form-control sp">
                </div>
                <div class="col-2">
                    <label for="">Fire Fighting Price</label>
                    <input type="decimal" name="ff_sp" id="ff_sp" class="form-control sp">
                </div>
                <div class="col-2">
                    <label for="">Fire Alarm System Price</label>
                    <input type="decimal" name="fa_sp" id="fa_sp" class="form-control sp">
                </div>
                <div class="col-2">
                    <label for="">LPG System Price</label>
                    <input type="decimal" name="lpg_sp" id="lpg_sp" class="form-control sp">
                </div>
            </div> 
            
            <div class="form-row mt-3">
                <div class="col-3">
                    <label for="">Total System Price</label>
                    <input type="decimal" name="tot_sp" id="tot_sp" class="form-control" required>
                </div>
               
                <div class="col-3">
                    <label for="">No. of Bathroom</label>
                    <input type="decimal" name="no_br" class="form-control">
                </div>
                <div class="col-3">
                    <label for="">Connected Load</label>
                    <input type="decimal" name="con_load" class="form-control">
                </div>
                <div class="col-3">
                    <label for="">Total Tonnage</label>
                    <input type="decimal" name="tot_ton" class="form-control">
                </div>
            </div>
        <!-- END FORM -->
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-times" aria-hidden="true"></i> Close</button>
            <button type="submit" name="addEstimation" class="btn btn-success"><i class="fa fa-check" aria-hidden="true"></i> Save</button>
        </div>
      </form>
    </div>
  </div>
</div>
<!-- End Modal ADD Project -->
<!-- End EDIT Project Modal -->
<div class="modal fade bd-example-modal-xl" id="EditProjectModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl" role="document">
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
            <input type="hidden" name="est_id" id="es_id"  >
            <input type="hidden" name="prj_id" id="prj_opt1">
            <div class="form-row">
                <div class="col-8">
                    <div class="form-group">
                        <label class="font-weight-bold" for="">Project</label>
                        <input name="" id="prj_name" class="form-control" readonly>
                        <!-- <select name="prj_id" id="prj_opt1" class="form-control "></select> -->
                    </div>
                </div>
                <div class="col-4">
                    <label class="font-weight-bold" for="">System</label>
                    <select name="sys_id" id="sys_opt1" class="form-control" required></select>
                </div>
            </div>
            <div class="form-row">
                <div class="col-3">
                    <div class="form-group">
                        <label for="">No. of Appartment</label>
                        <input type="decimal" name="no_apt" id="no_apt" class="form-control editEst" required>
                    </div>
                </div>
                <div class="col-3">
                    <label for="">No. of Bathroom</label>
                    <input type="decimal" name="no_br" id="no_br" class="form-control" >
                </div>
                <div class="col-3">
                    <label for="">Connected Load</label>
                    <input type="text" name="con_load" id="con_load" class="form-control">
                </div>
                <div class="col-3">
                    <label for="">Total Tonnage</label>
                    <input type="text" name="tot_ton" id="tot_ton" class="form-control editEst" required>
                </div>
            </div>
            <div class="form-row mt-1">
                <div class="col-2">
                    <label for="">HVAC System Price</label>
                    <input type="decimal" name="hvac_sp" id="hvac_sp1" class="form-control spe">
                </div>
                <div class="col-2">
                    <label for="">Electrical System Price</label>
                    <input type="decimal" name="elec_sp" id="elec_sp1" class="form-control spe">
                </div>
                <div class="col-2">
                    <label for="">Plumbing System Price</label>
                    <input type="decimal" name="plumb_sp" id="plumb_sp1" class="form-control spe">
                </div>
                <div class="col-2">
                    <label for="">Fire Fighting Price</label>
                    <input type="decimal" name="ff_sp" id="ff_sp1" class="form-control spe">
                </div>
                <div class="col-2">
                    <label for="">Fire Alarm System Price</label>
                    <input type="decimal" name="fa_sp" id="fa_sp1" class="form-control spe">
                </div>
                <div class="col-2">
                    <label for="">LPG System Price</label>
                    <input type="decimal" name="lpg_sp" id="lpg_sp1" class="form-control spe">
                </div>
            </div> 
            <div class="form-row mt-1">
                <div class="col-6">
                    <label for="">Total BUA</label>
                    <input type="decimal" name="tot_bua" id="tot_bua" class="form-control editEst" required>
                </div>
                <div class="col-6">
                    <div class="form-group">
                        <label for="">Average BUA</label>
                        <input type="text" name="ave_bua" id="ave_bua" class="form-control" required>
                    </div>
                </div>
            </div>
            <div class="form-row">
                <div class="col-6">
                    <label for="">Total System Price</label>
                    <input type="text" name="tot_sysP" id="tot_sysP" class="form-control" required>
                </div>
                <div class="col-6">
                    <label for="">Status</label>
                    <select name="status_id" id="status_opt3" class="form-control" required></select>
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
            $('#prj_name').val(data[2]);
            $('#sys_opt1').val(data[3]);
            $('#no_apt').val(data[4]);
            $('#no_br').val(data[5]);
            $('#con_load').val(data[6]);
            $('#tot_ton').val(data[7]);
            $('#ave_bua').val(data[18]);
            $('#tot_bua').val(data[19]);
            $('#tot_sysP').val(data[20]);
            $('#hvac_sp1').val(data[22]);
            $('#elec_sp1').val(data[23]);
            $('#plumb_sp1').val(data[24]);
            $('#ff_sp1').val(data[25]);
            $('#fa_sp1').val(data[26]);
            $('#lpg_sp1').val(data[27]);
            $('#status_opt3').val(data[8]);
    });
});
$(document).ready(function(){
    var dept_id = "";
    $.ajax({
        url:'est_queries.php',
        method: 'POST',
        data:{'sys_opt': dept_id},
        success:function(data){
            $(document).find('#sys_opt1').html(data).change();
            data="<option value=''>Select System Type</option>"+data;
            $(document).find('#type_opt1').html(data).change();
        }
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
            // $(document).find('#prj_opt').html(data).change();
            $(document).find('#prj_opt1').html(data).change();
        }
    });  
    $.ajax({
        url:'est_queries.php',
        method: 'POST',
        data:{'add_est_prj': client},
        success:function(data){
            $(document).find('#prj_opt').html(data).change();
            // console.log(data);
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
    $('.addEst').on('input', function() {
        var no_apt = parseFloat($('#no_apt1').val().replace(/,/g, '')) || 0;
        var tot_bua = parseFloat($('#tot_bua1').val().replace(/,/g, '')) || 0;
        var ave= tot_bua/no_apt;
        ave=parseFloat(ave.toFixed(2));
        $('#ave_bua1').val(ave);
    });
});
$(document).ready(function(){
    $('.editEst').on('input', function() {
        var no_apt1 = parseFloat($('#no_apt').val().replace(/,/g, '')) || 0;
        var tot_bua1 = parseFloat($('#tot_bua').val().replace(/,/g, '')) || 0;
        var ave1= tot_bua1/no_apt1;
        ave1=parseFloat(ave1.toFixed(2));
        $('#ave_bua').val(ave1);
    });
});

$(document).ready(function(){
    $('.sp').on('input', function() {
        let tot_sp=0;
        let hvac_sp = parseFloat($('#hvac_sp').val().replace(/,/g, '')) || 0;
        let elec_sp = parseFloat($('#elec_sp').val().replace(/,/g, '')) || 0;
        let plumb_sp = parseFloat($('#plumb_sp').val().replace(/,/g, '')) || 0;
        let ff_sp = parseFloat($('#ff_sp').val().replace(/,/g, '')) || 0;
        let fa_sp = parseFloat($('#fa_sp').val().replace(/,/g, '')) || 0;
        let lpg_sp = parseFloat($('#lpg_sp').val().replace(/,/g, '')) || 0;

        tot_sp= hvac_sp + elec_sp + plumb_sp + ff_sp + fa_sp + lpg_sp;
        console.log(tot_sp);
        tot_sp=parseFloat(tot_sp.toFixed(2));
        $('#tot_sp').val(tot_sp);
    });
});
$(document).ready(function(){
    $('.spe').on('input', function() {
        let tot_sp1=0;
        let hvac_sp1 = parseFloat($('#hvac_sp1').val().replace(/,/g, '')) || 0;
        let elec_sp1 = parseFloat($('#elec_sp1').val().replace(/,/g, '')) || 0;
        let plumb_sp1 = parseFloat($('#plumb_sp1').val().replace(/,/g, '')) || 0;
        let ff_sp1 = parseFloat($('#ff_sp1').val().replace(/,/g, '')) || 0;
        let fa_sp1 = parseFloat($('#fa_sp1').val().replace(/,/g, '')) || 0;
        let lpg_sp1 = parseFloat($('#lpg_sp1').val().replace(/,/g, '')) || 0;

        tot_sp1= hvac_sp1 + elec_sp1 + plumb_sp1 + ff_sp1 + fa_sp1 + lpg_sp1;
        // console.log(tot_sp1);
        tot_sp1=parseFloat(tot_sp1.toFixed(2));
        $('#tot_sysP').val(tot_sp1);
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
            $(document).find('#client_opt1').html(data).change();
            $('#client_opt').selectpicker('refresh');
            // $('#client_opt1').selectpicker('refresh');

        }
    });  
    $.ajax({
        url:'est_queries.php',
        method: 'POST',
        data:{'mc_opt': client},
        success:function(data){
            $(document).find('#mc_opt').html(data).change();
            $(document).find('#mc_opt1').html(data).change();
            $('#mc_opt').selectpicker('refresh');
        }
    });
    $.ajax({
        url:'est_queries.php',
        method: 'POST',
        data:{'cons_opt': client},
        success:function(data){
            $(document).find('#cons_opt').html(data).change();
            $(document).find('#cons_opt1').html(data).change();
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
</script>
<?php
include('../includes/scripts.php');
include('../includes/footer.php');
?>