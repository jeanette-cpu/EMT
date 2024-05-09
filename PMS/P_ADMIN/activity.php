<?php
include('../../security.php');
include('../../includes/header.php'); 
include('../../includes/prj_admin_navbar.php'); 
$username1 = $_SESSION['USERNAME'];
$query1 = "SELECT USER_ID FROM users WHERE USERNAME='$username1'";
$query_run1 = mysqli_query($connection, $query1);
$row1=mysqli_fetch_assoc($query_run1);
$user_id = $row1['USER_ID'];

if(isset($_GET['prj_id']) or isset($_GET['id']))
{
    if(isset($_GET['prj_id'])){
        $prj_id=$_GET['prj_id'];
        $q_prj_name="SELECT Prj_Name,Prj_Code,Prj_Category from project where Prj_Id='$prj_id'";
        $run=mysqli_query($connection, $q_prj_name);
        $row3 = mysqli_fetch_assoc($run);

        $prj_name= $row3['Prj_Code'].' - '.$row3['Prj_Name'];
        $prj_cat = $row3['Prj_Category'];
        $flat_name="Choose Area";
        ?>
        <script>
            $(document).find('#addActBtn').addClass('d-none');
        </script>
        <?php
    }
    else{
        $prj_id=$_GET['id'];
        $flt_id=$_GET['flt_id'];
        $q_prj_name="SELECT Prj_Name,Prj_Code,Prj_Category from project where Prj_Id='$prj_id'";
        $run=mysqli_query($connection, $q_prj_name);
        $row3 = mysqli_fetch_assoc($run);

        $flt_name_q="SELECT flat_code,flat_name FROM flat where Flat_Status=1 AND FLAT_ID='$flt_id'";
        $run_q=mysqli_query($connection, $flt_name_q);
        $row4 = mysqli_fetch_assoc($run_q);

        $flat_name = $row4['flat_code'].' '.$row4['flat_name'];
        $prj_name= $row3['Prj_Code'].' - '.$row3['Prj_Name'];
        $prj_cat = $row3['Prj_Category'];
        
        ?>
        <script>
        $(document).ready(function () {
            var flt_id = '<?php echo $flt_id?>';
            //add modal
            document.getElementById('flat_id').value= flt_id;
            //edit modal
            document.getElementById('eflat_id').value= flt_id;
            //add manpower modal
            document.getElementById('flat_id1').value= flt_id;
            $(document).find('#addActBtn').removeClass('d-none');
        });
        </script>
        <?php
    }

    if($prj_cat=='Building'){
        ?>
        <script>
            $(document).ready(function(){
                $(document).find('#villa').addClass('d-none');
            });
        </script>
        <?php
        }
    else{
        ?>
        <script>
            $(document).ready(function(){
                $(document).find('#building').addClass('d-none');
            });
        </script>
        <?php
    }
}
?>
<div class="col-xl-12 col-lg-12">
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <input type="hidden" id="prj_id" value="<?php echo $prj_id?>">
            <h4><?php echo $prj_name;?></h4>
        </div>
        <form action="activity.php" method="POST" class="pl-3 pt-2 pb-3">
            <label>Project</label>
            <select name="project_opt" id="project_opt" class="form-control col-5">
            </select>
        </form>
        <form action="act_code.php" method="POST">
            <div id="building" class="d-none">
                <div class="row pb-3 pl-3 pt-2">
                    <div class="col-3">
                        <label>Building</label>
                        <select name="" id="bblg_opt" class="form-control selectpicker" data-live-search="true" required>
                            <option value="">Select Building</option>
                        </select>
                    </div>
                    <div class="col-3">
                        <label>Level</label>
                        <select name="" id="blvl_opt" class="form-control selectpicker" data-live-search="true" required>
                            <option value="">Select Level</option>
                        </select>
                    </div>
                    <div class="col-3">
                        <label>Flat</label>
                        <select name="flt_id" id="bflt_opt" class="form-control selectpicker" data-live-search="true" required>
                            <option value="">Select Flat</option>
                        </select>
                    </div>
                    <div class="col-3">
                        <br>
                        <input type="hidden" id="prjId_b" name="prj_id" value="<?php echo $prj_id?>">
                        <button type="submit" name="search" class="btn btn-warning mt-2">
                            <i class="fa fa-search" aria-hidden="true"></i>
                            Search
                        </button>
                    </div>
                </div>
            </div>
        </form>
        <form action="act_code.php" method="POST">
            <div id="villa" class="d-none">
                <div class="row pb-3 pl-3 pt-2">
                    <div class="col-4">
                        <label>Area</label>
                        <select name="" id="villa_opt" class="form-control selectpicker" data-live-search="true" required></select>
                    </div>
                    <div class="col-4">
                        <label>Plex</label>
                        <select name="" id="plex_opt" class="form-control selectpicker" data-live-search="true" required>
                            <option value="">Select Plex</option>
                        </select>
                    </div>
                </div>
                <div class="row pb-3 pl-3 pt-2">
                    <div class="col-4">
                        <label>Villa</label>
                        <select name="" id="blg_opt" class="form-control selectpicker" data-live-search="true" required>
                            <option value="">Select Building</option>
                        </select>
                    </div>
                    <div class="col-3">
                        <label>Level</label>
                        <select name="" id="lvl_opt" class="form-control selectpicker" data-live-search="true" required>
                            <option value="">Select Level</option>
                        </select>
                    </div>
                    <div class="col-3">
                        <label>Flat</label>
                        <select name="flt_id" id="flt_opt" class="form-control selectpicker" data-live-search="true" required>
                            <option value="">Select Flat</option>
                        </select>
                    </div>
                    <div class="col-2">
                        <br>
                        <input type="hidden" id="prjId_v" name="prj_id" value="<?php echo $prj_id?>">
                        <button type="submit" name="villa_search" class="btn btn-warning mt-2">
                            <i class="fa fa-search" aria-hidden="true"></i>
                            Search
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
<div>
    <div class="col-xl-12 col-lg-12">
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <div class="row">
                <div class="col-6">
                    <h5 class="m-0 font-weight-bold text-primary">Daily Activity <span class="text-normal ml-2 text-dark"> <?php echo $flat_name?></span></h5>
                </div>
                <div class="col-6">
                    <!-- BUTTON -->
                    <input type="hidden" name="flt_id_tbl" id="flt_id_tbl">
                    <button type="button" name="addB" id="addActBtn" class="btn btn-primary float-right d-none" data-toggle="modal" data-target="#addAct">
                        <i class="fa fa-plus" aria-hidden="true"></i>
                        Add New
                    </button>
                </div>
            </div>
        </div>

    <div class="card-body">
    <div class="table-responsive">
    <?php
        if( isset( $flt_id ) ) {
            $query2 = "SELECT Asgd_Act_Id FROM assigned_activity where Asgd_Act_Status=1 AND Asgd_Pct_Done <=100 AND Flat_Id='$flt_id'";
        }
        else{
            $query2 = "SELECT Asgd_Act_Id FROM assigned_activity where Asgd_Act_Status=3";
        }

        $query_run2 = mysqli_query($connection, $query2);
        if(mysqli_num_rows($query_run2)>0)
        {
            while($row2 = mysqli_fetch_assoc($query_run2)){
                $ids[] = $row2['Asgd_Act_Id'];
            }
            $userStr = implode("', '", $ids);
            $query="SELECT * FROM daily_entry where DE_Status=1 and Asgd_Act_Id in ('$userStr')";
            $query_run = mysqli_query($connection, $query);
        }
        else
        { 
            $query="SELECT * FROM daily_entry where DE_Status=3 ";
            $query_run = mysqli_query($connection, $query); 
        }
        
    ?>
        <table class="table table-bordered table-striped" id="dataTable" width="100%" cellspacing="0">
        <thead>
            <th class="d-none"></th>
            <th class="align-middle">Activity </th>
            <th class="align-middle">Department</th>
            <th class="align-middle">Category</th>
            <th class="align-middle" colspan="2">Manpower <br>
                <p class="font-weight-light">No | Manage</p>
            </th>
            <th class="align-middle">Progress</th>
            <th class="d-none"></th>
            <th class="d-none"></th>
            <!-- <th>MP Total</th>
            <th>SB Total</th> -->
            <th class="align-middle">Date</th>
            <th class="align-middle">Action</th>
        </thead>
        <tbody>
        <?php
            if(mysqli_num_rows($query_run)>0)
            {
                while($row = mysqli_fetch_assoc($query_run))
                {
                    $asgd_act_id = $row['Asgd_Act_Id'];
                    $query1 = "SELECT * FROM assigned_activity where Asgd_Act_Status=1 and Asgd_Act_Id='$asgd_act_id'";
                    $query_run1 = mysqli_query($connection, $query1);
                    $row1 = mysqli_fetch_assoc($query_run1);

                    $act_id = $row1['Act_Id'];
                    // activity name
                    $q1 = "SELECT * FROM activity where Act_Id='$act_id'";
                    $q_run1=mysqli_query($connection, $q1);
                    $row4 = mysqli_fetch_assoc($q_run1);
                    //act category name
                    $act_cat_id = $row4['Act_Cat_Id'];
                    $q3="SELECT * from activity_category where Act_Cat_Id='$act_cat_id'";
                    $q_run3=mysqli_query($connection, $q3);
                    $row5 = mysqli_fetch_assoc($q_run3);
                    //dept name
                    $dept_id=$row5['Dept_Id'];
                    $q4="SELECT * from department where Dept_Id='$dept_id'";
                    $q_run4=mysqli_query($connection, $q4);
                    $row6 = mysqli_fetch_assoc($q_run4);
                    // count EMT EMP
                    $DE_Id = $row['DE_Id'];
                    $q5="SELECT COUNT(Asgd_Worker_Id) AS ctn  FROM asgn_worker where DE_Id='$DE_Id' and Asgd_Worker_Status=1";
                    $q5_run=mysqli_query($connection, $q5);
                    $row7 = mysqli_fetch_assoc($q5_run);
                    // COUNT MP
                    $q_mp_count="SELECT SUM(Asgn_MP_Qty) AS mp_c, Asgn_MP_Total  FROM asgn_mp where DE_Id='$DE_Id' and Asgn_MP_Status=1"; 
                    $q_mp_count_run=mysqli_query($connection, $q_mp_count);
                    $mp_row = mysqli_fetch_assoc($q_mp_count_run);
                    // COUNT SB
                    $q_sb_count="SELECT SUM(Asgn_SB_Qty) AS sb_c, Asgn_SB_Total  FROM asgn_subcon where DE_Id='$DE_Id' and Asgn_SB_Status=1";
                    $q_sb_count_run=mysqli_query($connection, $q_sb_count);
                    $sb_row = mysqli_fetch_assoc($q_sb_count_run);
                    ///
                    $total_mp =  $row7['ctn']+$mp_row['mp_c']+$sb_row['sb_c'];
                ?>
            <tr>
                <td class="d-none"><?php echo $row['DE_Id']?></td>
                <td><?php $act_name = $row4['Act_Code'].' - '.$row4['Act_Name']; echo $act_name?></td>
                <td><?php echo $row6['Dept_Name']?></td>
                <td><?php echo $row5['Act_Cat_Name']?></td>
                <td >
                    <!-- TOTAL LABOUR COUNT -->
                    <?php echo $total_mp?>
                </td>
                <td class="btn-group ">
                    <!-- manage button-->
                    <input type="hidden" id="DE_Id_mng_emp" value="<?php echo $row['DE_Id']?>">
                    <input type="hidden"  id="flat_mng_emp" class="del_flat">
                    <input type="hidden" id="act_name" value="<?php echo $act_name?>">
                    <input type="hidden" id="project_id_mng_emp" value="<?php echo $prj_id?>">
                    <button type="button" class="btn btn-success manageBtn mr-2">
                        <i class="fa fa-pencil" aria-hidden="true"></i>
                    </button>
                    <!-- add manpower button-->
                    <button type="button" class="btn btn-info addEmp">
                        <i class="fa fa-user-plus" aria-hidden="true"></i>
                    </button>              
                </td>
                <td><?php echo number_format($row['DE_Pct_Done'],0,'',' ').'%'?></td>      
                <td class="d-none"><?php echo number_format($row['DE_Pct_Done'],0,'',' ')?></td>
                <td class="d-none"><?php echo $asgd_act_id?></td>
                <td><?php echo $row['DE_Date_Entry']?></td>
                <td class="btn-group text-center">
                    <!-- edit -->
                    <button type="button" class="btn btn-success editBtn">
                        <i class="fa fa-pencil" aria-hidden="true"></i>
                    </button>
                    <!-- delete -->
                    <form action="act_code.php" method="POST">
                        <input type="hidden" name="DE_Id" id="Del_DE_Id" value="<?php echo $row['DE_Id']?>">
                        <input type="hidden" name="flat_id" id="del_flat" class="del_flat">
                        <input type="hidden" name="prj_id" id="project_id" value="<?php echo $prj_id?>">
                        <button type="submit" name="delDE" class="btn btn-primary">
                            <i class="fa fa-trash" aria-hidden="true"></i>
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
    </div>
</div>
<!-- Modal Add Daily Entry -->
<div class="modal fade bd-example-modal-lg" id="addAct" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
          <!-- MODAL TITLE -->
        <h5 class="modal-title" id="exampleModalLabel"><i class="fa fa-cogs" aria-hidden="true"></i> Add Activity</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <div class="modal-body" >
        <!-- THE FORM -->
        <form action="act_code.php" method="POST">
            <div class="form-group">
                <div class="row ">
                    <div class="col-6">
                        <label >Department</label>
                        <select name="dept_id" id="dept" class="form-control" required></select>
                    </div>
                    <div class="col-6">
                        <label  class="mt-1">Category</label>
                        <select name="dept_id" id="category" class="form-control" required></select>
                    </div> 
                </div>
                <label class="mt-1">Activity</label>
                <select name="activity_id" id="activity" class="form-control col-9" required></select>
            </div>
            <div class="table-responsive mt-4">
                <table class="table table-bordered" id="materialTbl">
                    <tr>
                        <th>EMT Labour</th>
                        <th></th>
                    </tr>
                    <tr>
                        <td>
                            <select name="employee[]" class="form-control selectpicker" data-live-search="true" id="employee" >
                            </select>
                        </td>
                        <td></td>
                    </tr>
                </table>
                <div align="right">
                    <button type="button" name="add" id="addBtn" class="btn btn-success btn-xs">+</button>
                </div>
            </div>
            <div class="table-responsive mt-4">
                <table class="table table-bordered" id="ManpowerTbl">
                    <tr>
                        <th width="70%">Manpower Supply</th>
                        <th></th>
                        <th></th>
                    </tr>
                    <tr>
                        <td>
                            <select name="manpower[]" class="form-control selectpicker" data-live-search="true" id="manpowerOpt">
                            </select>
                        </td>
                        <td >
                            <div class="form-row block">
                                <!-- <button class="btn btn-xs btn-outline-danger minBtn" >-</button> -->
                                <button type="button" class="btn btn-xs btn-outline-danger minBtn" >-</button>
                                <div class="col-4">
                                    <input type="text" name="mp_qty[]" class="form-control" id="mpVal">
                                </div>
                                <button type="button" class="btn btn-xs btn-outline-success addBtn">+</button>
                            </div>
                        </td>
                        <td></td>
                    </tr>
                </table>
                <div align="right">
                    <button type="button" name="add" id="addMpRow" class="btn btn-success btn-xs">+</button>
                </div>
            </div>
            <div class="table-responsive mt-4">
                <table class="table table-bordered" id="SubConTbl">
                    <tr>
                        <th width="70%">Sub-Contractor</th>
                        <th></th>
                        <th></th>
                    </tr>
                    <tr>
                        <td>
                            <select name="subcontractor[]" class="form-control selectpicker" data-live-search="true" id="sbOpt">
                            </select>
                        </td>
                        <td >
                            <div class="form-row block">
                                <!-- <button class="btn btn-xs btn-outline-danger minBtn" >-</button> -->
                                <button type="button" class="btn btn-xs btn-outline-danger minBtn">-</button>
                                <div class="col-4">
                                    <input type="text" name='sb_qty[]' class="form-control" id="SubConVal">
                                </div>
                                <button type="button" class="btn btn-xs btn-outline-success addBtn">+</button>
                            </div>
                        </td>
                        <td></td>
                    </tr>
                </table>
                <div align="right">
                    <button type="button" name="add" id="addSbRow" class="btn btn-success btn-xs">+</button>
                </div>
            </div>
        <!-- END FORM -->
      </div>
            <input type="hidden" name="flat_id" id="flat_id">
            <!-- <input type="hidden" name="dept_id" id="dept_id" value="<?php echo $dept_id?>"> -->
            <input type="hidden" name="user_id" value="<?php echo $user_id?>">
            <input type="hidden" name="prj_id" value="<?php echo $prj_id?>">
        <div class="modal-footer">
            <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-times" aria-hidden="true"></i> Close</button>
            <button type="submit" name="addDE" class="btn btn-success"><i class="fa fa-check" aria-hidden="true"></i> Save</button>
        </div>
            </form>
        
    </div>
  </div>
</div>
<!-- End Modal ADD Daily Entry -->

<!-- Modal Add Manpower -->
<div class="modal fade bd-example-modal-lg" id="addManpower" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
          <!-- MODAL TITLE -->
        <h5 class="modal-title" id="exampleModalLabel"><i class="fa fa-users" aria-hidden="true"></i> Add Labours</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <div class="modal-body">
        <!-- FORM -->
        <form action="act_code.php" method="POST">
            <div class="table-responsive">
                <table class="table table-bordered" id="manpowerTbl">
                    <tr>
                        <th>EMT Labours</th>
                        <th></th>
                    </tr>
                    <tr>
                        <td><select name="employee[]" id="manpower" class="form-control selectpicker" data-live-search="true" ></select></td>
                        <td></td>
                    </tr>
                </table>
                <div align="right">
                    <button type="button" name="add" id="addBtnM" class="btn btn-success btn-xs">+</button>
                </div>
            </div>
            <div class="table-responsive mt-4">
                <table class="table table-bordered" id="ManpowerTbl1">
                    <tr>
                        <th width="70%">Manpower Supply</th>
                        <th></th>
                        <th></th>
                    </tr>
                    <tr>
                        <td>
                            <select name="manpower[]" class="form-control selectpicker" data-live-search="true" id="manpowerOpt1">
                            </select>
                        </td>
                        <td >
                            <div class="form-row block">
                                <!-- <button class="btn btn-xs btn-outline-danger minBtn" >-</button> -->
                                <button type="button" class="btn btn-xs btn-outline-danger minBtn" >-</button>
                                <div class="col-4">
                                    <input type="text" name="mp_qty[]" class="form-control" id="mpVal">
                                </div>
                                <button type="button" class="btn btn-xs btn-outline-success addBtn">+</button>
                            </div>
                        </td>
                        <td></td>
                    </tr>
                </table>
                <div align="right">
                    <button type="button" name="add" id="addMpRow1" class="btn btn-success btn-xs">+</button>
                </div>
            </div>
            <div class="table-responsive mt-4">
                <table class="table table-bordered" id="SubConTbl1">
                    <tr>
                        <th width="70%">Sub-Contractor</th>
                        <th></th>
                        <th></th>
                    </tr>
                    <tr>
                        <td>
                            <select name="subcontractor[]" class="form-control selectpicker" data-live-search="true" id="sbOpt1">
                            </select>
                        </td>
                        <td >
                            <div class="form-row block">
                                <!-- <button class="btn btn-xs btn-outline-danger minBtn" >-</button> -->
                                <button type="button" class="btn btn-xs btn-outline-danger minBtn">-</button>
                                <div class="col-4">
                                    <input type="text" name='sb_qty[]' class="form-control" id="SubConVal">
                                </div>
                                <button type="button" class="btn btn-xs btn-outline-success addBtn">+</button>
                            </div>
                        </td>
                        <td></td>
                    </tr>
                </table>
                <div align="right">
                    <button type="button" name="add" id="addSbRow1" class="btn btn-success btn-xs">+</button>
                </div>
            </div>
        <!-- END FORM -->
      </div>
      <input type="hidden" name="flat_id" id="flat_id1">
      <input type="hidden" name="DE_Id" id="DE_Idm">
      <input type="hidden" name="prj_id" value="<?php echo $prj_id?>">
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-times" aria-hidden="true"></i> Close</button>
        <button type="submit" name="addWorker" class="btn btn-success"><i class="fa fa-check" aria-hidden="true"></i> Save</button>
      </div>
      </form>
    </div>
  </div>
</div>
<!-- End Modal ADD Manpower -->

<!-- Modal Edit Entry -->
<div class="modal fade bd-example-modal-sm" id="editAct" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-sm" role="document">
    <div class="modal-content">
      <div class="modal-header">
          <!-- MODAL TITLE -->
        <h5 class="modal-title" id="exampleModalLabel"><i class="fa fa-cogs" aria-hidden="true"></i> Edit Progress</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <div class="modal-body">
        <form action="act_code.php" method="POST">
            <div class="form-group">
                <input type="hidden" name="DE_Id" id="DE_Id" >
                <label>Progress</label>
                <input type="number" name="DE_Pct" id="DE_Pct" class="form-control">
                <label class="mt-2">Date</label>
                <input type="date" name="DE_Date" id="DE_Date" class="form-control">

            </div>
      </div>
            <input type="hidden" name="flat_id" id="eflat_id">
            <input type="hidden" name="asgd_act_id" id="Easgd_act_id">
            <input type="hidden" name="prj_id" value="<?php echo $prj_id?>">
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-times" aria-hidden="true"></i> Close</button>
        <button type="submit" name="editDE" class="btn btn-success"><i class="fa fa-check" aria-hidden="true"></i> Save</button>
      </div>
      </form>
    </div>
  </div>
</div>
<!-- End Modal Edit Entry -->

<?php
include('activity_script.php');
include('../../includes/scripts.php');
include('../../includes/footer.php');
?>
<!-- Modal Manage Emp --> 
<div class="modal fade bd-example-modal-xl" id="EmployeeAsgn" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel"></h5>
      <div class="modal-body" id="manage_emp">
        <!-- <div class="modal-footer">  
              <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>  
        </div>   -->
    </div>
  </div>
</div>
