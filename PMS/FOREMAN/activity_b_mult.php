<?php
include('../../security.php');
include('../../includes/header.php'); 
include('../../includes/foreman_navbar.php'); 
include('function.php');
// error_reporting(0);
date_default_timezone_set('Asia/Dubai');
$Today = date('Y-m-d');

$username = $_SESSION['USERNAME'];
$query = "SELECT * FROM users WHERE USERNAME='$username'";
$query_run = mysqli_query($connection, $query);
$row=mysqli_fetch_assoc($query_run);
$user_id = $row['USER_ID'];

if(isset($_POST['actBtn']) or isset($_GET['id']))
{
    if(isset($_POST['actBtn'])){
        $prj_id=$_POST['prj_id'];

        $q_prj_name="SELECT Prj_Name,Prj_Code,Prj_Category from project where Prj_Id='$prj_id'";
        $run=mysqli_query($connection, $q_prj_name);
        $row3 = mysqli_fetch_assoc($run);

        $prj_name= $row3['Prj_Code'].' - '.$row3['Prj_Name'];
        $prj_cat = $row3['Prj_Category'];
        $flat_name="Choose Area";
        $Date = date('Y-m-d');
    }
    else{
        $prj_id=$_GET['id'];
        $flt_id=$_GET['flt_id'];
        $Date=$_GET['date'];
        if($Date){
            $Date=$_GET['date'];
        }
        else{
            $Date = date('Y-m-d');
        }

        $q_prj_name="SELECT Prj_Name,Prj_Code,Prj_Category from project where Prj_Id='$prj_id'";
        $run=mysqli_query($connection, $q_prj_name);
        $row3 = mysqli_fetch_assoc($run);

        $flt_name_q="SELECT f.flat_code, f.flat_name, lvl.Lvl_Code, lvl.Lvl_Name FROM flat as f
                    LEFT JOIN level as lvl on lvl.Lvl_Id=f.Lvl_Id
                    where f.Flat_Status=1 AND f.Flat_Id in ('$flt_id')";
        $run_q=mysqli_query($connection, $flt_name_q);
        if(mysqli_num_rows($run_q)>0){
            $flat_name='<span  class=" pr-3 font-weight-bold">Updating on: </span>';
            $flats=''; $f_count=1;
            while($row4 = mysqli_fetch_assoc($run_q)){
                $lvl_name= 'Level - '.$row4['Lvl_Code'].' '.$row4['Lvl_Name'].'<br>';
                $flats .= '<span class="pl-3">'.$f_count.'. '.$row4['flat_code'].' '.$row4['flat_name'].' ('.$row4['Lvl_Code'].')'.'</span><br>';
                $f_count++;
            }
        }
        $flat_name.=$lvl_name.$flats;
        $prj_name= $row3['Prj_Code'].' - '.$row3['Prj_Name'];
        $prj_cat = $row3['Prj_Category'];

        ?>
        <script  type="text/javascript">
            $(document).ready(function () {
                // let flt_id = "< ?php echo $flt_id;?>";
                // //add modal
                // document.getElementById('flat_id').value= flt_id;
                // //edit modal
                // document.getElementById('eflat_id').value= flt_id;
                // //add manpower modal
                // document.getElementById('flat_id1').value= flt_id;
                $('#addActBtn').removeClass("d-none");
                $('.hide').removeClass("d-none");
            });
        </script>
        <?php
    }   
    $all_flt_ids=getFlatIds($prj_id);
}
?>

<div class="col-xl-12 col-lg-12">
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <input type="hidden" id="prj_id" value="<?php echo $prj_id?>">
            <input type="hidden" id="user_id" value="<?php echo $user_id?>">
            <input type="hidden" id="flt_id" value="<?php echo $flt_id?>">
            <input type="date" class="d-none" value=<?php echo $Date?>>
            <h4><?php echo $prj_name;?></h4>
        </div>
        <form action="codeb_mult.php" method="POST">
            <div id="building">
                <div class="row pb-3 pl-3 pt-2">
                    <div class="col-2">
                        <label>Building</label>
                        <select name="" id="bblg_opt" class="form-control selectpicker" data-live-search="true" required>
                            <option value="">Select Building</option>
                        </select>
                    </div>
                    <div class="col-2">
                        <label>Level</label>
                        <select name="" id="blvl_opt" class="form-control selectpicker" data-live-search="true" required>
                            <option value="">Select Level</option>
                        </select>
                    </div>
                    <div class="col-2">
                        <label>Flat</label>
                        <select name="flt_id[]" id="bflt_opt" class="form-control selectpicker" data-live-search="true" multiple required>
                            <option value="">Select Flat</option>
                        </select>
                    </div>
                    <div class="col-2">
                        <label>Date</label>
                        <input type="date" name="date" id="" class="form-control">
                    </div>
                    <div class="col-1">
                        <br>
                        <input type="hidden" name="prj_id" value="<?php echo $prj_id?>">
                        <button type="submit" name="search" class="btn btn-warning mt-2">
                            <i class="fa fa-search" aria-hidden="true"></i>
                            Search
                        </button>
                    </div>
        </form>
                    <div class="col-3 float-left">
                        <br><br>
                        <form action="r_quick_view.php" method="post">
                            <input type="hidden" name="prj_id" value="<?php echo $prj_id?>">
                            <button type="submit" name="qck_sum" class="btn btn-link text-secondary" onclick="this.form.target='_blank';return true;">
                                <i class="fa fa-file-text-o" aria-hidden="true"></i> Quick Summary Current Entry
                            </button>
                        </form>
                </div>
            </div>
            </div>
        <div class="row pb-3 pl-3 pt-2">
            <div class="col-4">
                <form action="codeb_mult.php" method="post">
                    <label for="">All Flats</label>
                    <select name="flt_id[]" id="" class="form-control selectpicker" data-live-search="true" multiple required>
                        <option value="">Select Flat</option>
                        <?php 
                            $q_flats="SELECT * FROM flat as f LEFT JOIN level AS l on l.Lvl_Id=f.Lvl_Id WHERE f.Flat_Id IN ('$all_flt_ids') AND l.Lvl_Status=1";
                            $q_flats_run=mysqli_query($connection,$q_flats);
                            if(mysqli_num_rows($q_flats_run)>0){
                                while($row_f=mysqli_fetch_assoc($q_flats_run)){
                                    $flat_id=$row_f['Flat_Id'];
                                    $flt_name_opt=$row_f['Flat_Code'].' '.$row_f['Flat_Name'];
                                    $level=$row_f['Lvl_Code'];
                                    ?>
                                    <option value="<?php echo $flat_id;?>"><?php echo $level.' '.$flt_name_opt;?></option>
                                    <?php
                                }
                            }
                        ?>
                    </select>
            </div>
            <div class="col-3">
                    <br>
                    <input type="hidden" name="prj_id" value="<?php echo $prj_id?>">
                    <input type="date" name="date" id="" class="form-control" value="">
                    <button type="submit" name="search" class="btn btn-warning mt-2">
                        <i class="fa fa-search" aria-hidden="true"></i>
                        Search
                    </button>
                </form>
            </div>
        </div>
    </div>
    <!-- /////////// -->
    <div class="col-xl-12 col-lg-12">
    <div class="card shadow mb-4" id="section_2">
    <?php
    // manpower
    $q_manpower ="SELECT * FROM daily_entry as de 
                LEFT JOIN asgn_mp as asmp on de.DE_Id = asmp.DE_Id 
                LEFT JOIN assigned_activity as as_act on as_act.Asgd_Act_Id =de.Asgd_Act_Id 
                LEFT JOIN manpower as mp on mp.MP_Id = asmp.MP_Id 
                WHERE  asmp.Asgn_MP_Status =1 AND de.DE_Date_Entry='$Date' AND as_act.Flat_Id IN ('$all_flt_ids')
                and de.DE_Status=1 AND de.User_Id='$user_id' GROUP BY asmp.MP_Id";
    // echo $q_manpower;
    $q_manpower_run = mysqli_query($connection,$q_manpower);
    
    if(mysqli_num_rows($q_manpower_run)>0)
    {
        echo '
    <div class="card-body">
        <h4>Total Manpower Supply to Allocate</h4>
        <form method="POST" action="codeb_mult.php">
            <input type="hidden" name="prj_id" value="'.$prj_id.'">
            <input type="hidden" name="flt_id" value="'.$flt_id.'">
            <input type="date" name="date" class="d-none" value="'.$Date.'">
        <div class="table-responsive">
            <table class="table pb-3">';
        while ($row_mp = mysqli_fetch_assoc($q_manpower_run)){
            if($row_mp['Asgn_MP_Total']==NULL){
                ?>
                <script>
                    $(document).ready(function(){
                        var mp_total=0;
                        var asgn_id=$('#asgn_mp_id').val();
                        $.ajax({
                        type:'POST',
                        url: '../P_ADMIN/ajax_prj_cat.php',
                        data:{
                            'total_mp' : mp_total,
                            'asgn_id': asgn_id,
                            },
                        });
                    });
                </script>
                <?php 
            }
            echo '
            <tr>
                <input type="hidden" name="asgn_id[]" id="asgn_mp_id" class="text" value="'.$row_mp['Asgn_MP_Id'].'">
                <td class="col-3">'.$row_mp['MP_Name'].'</td>
                <td class="col-1"><input type="number" name="total_mp[]" class="form-control" value="'.$row_mp['Asgn_MP_Total'].'"></td>
                <td class="col-3"></td>
            </tr>
            ';
        }
        echo "</table>
        </div>
    </div>
    <div class='col-7'>
        <button name='mp_total' class='btn btn-success float-right' type='submit'>Save</button>
    </div>
    </form>
        ";
    }
        // subcontractor
        $q_subcon ="SELECT * FROM daily_entry as de LEFT JOIN asgn_subcon as assb on de.DE_Id = assb.DE_Id 
                    LEFT JOIN subcontractor as mp on mp.SB_Id = assb.SB_Id 
                    LEFT JOIN assigned_activity as as_act on as_act.Asgd_Act_Id =de.Asgd_Act_Id 
                    WHERE  de.DE_Date_Entry='$Date' AND as_act.Flat_Id IN ('$all_flt_ids')
                    AND assb.Asgn_SB_Status =1 and de.DE_Status=1 AND de.User_Id in ('$user_id') GROUP BY assb.SB_Id";
        // echo $q_subcon;
        $q_subcon_run = mysqli_query($connection,$q_subcon);
        if(mysqli_num_rows($q_subcon_run)>0){
            $sb_table = '
        <form method="POST" action="codeb_mult.php">
            <input type="hidden" name="prj_id" value="'.$prj_id.'">
            <input type="hidden" name="flt_id" value="'.$flt_id.'">
            <input type="date" class="d-none" name="date" value="'.$Date.'">
        <div class="card-body">
            <div class="table-responsive">
                <h4 >Total Subcontractor</h4>
            <table class="table pb-3">';
            while ($row_sb = mysqli_fetch_assoc($q_subcon_run)) {
                if($row_sb['Asgn_SB_Total']==NULL){ 
                    ?>
                        <script>
                        $(document).ready(function(){  
                            var sb_total=0;
                            var asgn_id=$('#asgn_sb_id').val();
                            $.ajax({
                                type:'POST',
                                url: '../P_ADMIN/ajax_prj_cat.php',
                                data:{
                                    'total_sb' : sb_total,
                                    'asgn_id': asgn_id,
                                    },
                                });
                            });
                        </script>
                    <?php
                }
                $sb_table .= '
                <tr>
                    <input type="hidden" name="asgn_id[]" id="asgn_sb_id" class="text" value="'.$row_sb['Asgn_SB_Id'].'">
                    <td class="col-3">'.$row_sb['SB_Name'].'</td>
                    <td class="col-1"><input type="number" name="total_sb[]" class="form-control" value="'.$row_sb['Asgn_SB_Total'].'"></td>
                    <td class="col-3"></td>
                </tr>
                ';
            }
            $sb_table .="
                </table>
            </div>
        </div>
        <div class='col-7 '>
            <button name='sb_total' class='btn btn-success mb-2 float-right' type='submit'>Save</button>
        </div>
        </form>";
            echo $sb_table;
        }
    ?>
    </div>

</div>
<?php
    $username1 = $_SESSION['USERNAME'];
    $query1 = "SELECT USER_ID FROM users WHERE USERNAME='$username1'";
    $query_run1 = mysqli_query($connection, $query1);
    $row1=mysqli_fetch_assoc($query_run1);
    $user_id = $row1['USER_ID'];
    if(isset($flt_id)){
        $query2 = "SELECT Asgd_Act_Id FROM assigned_activity 
                    where Asgd_Act_Status=1 
                    AND Asgd_Pct_Done <=100 AND Flat_Id IN ('$flt_id')";
    }
    else{
        $query2 = "SELECT Asgd_Act_Id FROM assigned_activity where Asgd_Act_Status=3";
    }
    $query_run2 = mysqli_query($connection, $query2);
    if(mysqli_num_rows($query_run2)>0){
        while($row2 = mysqli_fetch_assoc($query_run2)){
            $ids[] = $row2['Asgd_Act_Id'];
        }
        $asgn_ids = implode("', '", $ids);
        $query="SELECT * FROM daily_entry AS de
                LEFT JOIN assigned_activity as as_act on as_act.Asgd_Act_Id = de.Asgd_Act_Id
                where de.DE_Status=1  AND de.DE_Date_Entry='$Date'
                and de.User_Id='$user_id' and de.Asgd_Act_Id in ('$asgn_ids')
                ORDER BY as_act.Flat_Id, as_act.Act_Id";
        $query_run = mysqli_query($connection, $query);
        $query_opt="SELECT * FROM daily_entry AS de
                LEFT JOIN assigned_activity as as_act on as_act.Asgd_Act_Id = de.Asgd_Act_Id
                where de.DE_Status=1  AND de.DE_Date_Entry='$Date'
                and de.User_Id='$user_id' and de.Asgd_Act_Id in ('$asgn_ids')
                GROUP BY as_act.Act_Id
                ORDER BY as_act.Flat_Id, as_act.Act_Id ";
        $query_run_opt =mysqli_query($connection,$query_opt);
        $query_run_opt2 =mysqli_query($connection,$query_opt);
        $de_id_run=mysqli_query($connection, $query);
        if(mysqli_num_rows($de_id_run)>0){
            while($row_de=mysqli_fetch_assoc($de_id_run)){
                $de_id_arr[]=$row_de['DE_Id'];
                $asgn_ids_arr2[]=$row_de['Asgd_Act_Id'];
            }
            $de_ids = implode("', '", $de_id_arr);
            $asgn_ids_2= implode("', '", $asgn_ids_arr2);
        }
        else{
            $de_ids=null;
        }
    }
    else{ 
        $query="SELECT * FROM daily_entry where DE_Status=3 ";
        $query_run = mysqli_query($connection, $query); 
    }
?>
<div>
    <div class="col-xl-12 col-lg-12">
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <div class="row">
                <div class="col-6">
                    <div class="row">
                        <h5 class="font-weight-bold text-primary ml-1">Daily Activity </h5> <span class="ml-4"> Date: <?php echo date("M d, Y", strtotime($Date));?></span>
                    </div>
                    <span class="text-normal ml-2 text-dark"> <?php echo $flat_name?></span>
                </div>
                <div class="col-6">
                    <!-- BUTTON -->
                    <input type="hidden" name="flt_id_tbl" id="flt_id_tbl">
                    <button type="button" name="addB" id="addActBtn" class="btn btn-primary float-right d-none" data-toggle="modal" data-target="#addAct">
                        <i class="fa fa-plus" aria-hidden="true"></i>
                        Add New
                    </button>
                    <br><br>
                    <div class="row hide d-none">
                        <div class="col-3">
                            <form action="codeb_mult.php" method="post">
                                <label for="">All Percentage Update:</label>
                                <input type="decimal" name="percent" class="form-control" required>
                        </div>
                        <div class="col-3">
                            <label for="" class="invisible">d</label>
                            <select name="act_id" id="" class="form-control">
                                <option value="all">To all Activities</option>
                                <?php
                                    if(mysqli_num_rows($query_run_opt)>0){
                                        while($row_opt=mysqli_fetch_assoc($query_run_opt)){
                                            $act_id = $row_opt['Act_Id'];
                                            // activity name
                                            $q1_ = "SELECT * FROM activity where Act_Id='$act_id'";
                                            $q_run1_=mysqli_query($connection, $q1_);
                                            $row4_ = mysqli_fetch_assoc($q_run1_);
                                            $act_name = $row4_['Act_Code'].' - '.$row4_['Act_Name'];
                                            echo "<option value=".$act_id.">".$act_name."</option>";
                                        }
                                    }
                                ?>
                            </select>
                        </div>
                        <div class="col-2">
                                <input type="date" name="date" class="d-none" value="<?php echo $Date;?>">
                                <input type="hidden" name="prj_id" value="<?php echo $prj_id?>">
                                <input type="hidden" name="flt_id" value="<?php echo $flt_id?>">
                                <input type="hidden" name="de_ids" id="de_ids" value="<?php echo $de_ids;?>">
                                <input type="hidden" name="asgn_ids" value="<?php echo $asgn_ids_2;?>";>
                                <br>
                                <button type="submit" name="update_all_pct" class="btn btn-success"> Update</button>
                            </form>
                        </div>
                        <div class="col-3">
                            <br>
                            <button class="btn btn-info addEmp2"><i class="fa fa-user-plus " aria-hidden="true"></i> Assign to All</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    <div class="card-body">
    <div class="table-responsive">
    
        <table class="table table-bordered table-striped" id="dataTable" width="100%" cellspacing="0">
        <thead>
            <th class="d-none"></th>
            <th class="d-none"></th>
            <th class="align-middle">Area</th>
            <th class="align-middle">Activity </th>
            <th class="align-middle">Department</th>
            <th class="align-middle">Category</th>
            <th class="align-middle">Record</th>
            <th class="align-middle" colspan="2">Manpower <br>
                <p class="font-weight-light">No | Manage</p>
            </th>
            <th class="align-middle">Progress</th>
            <th class="d-none"></th>
            <th class="d-none"></th>
            <th class="align-middle">Action</th>
        </thead>
        <tbody>
        <?php
            if(mysqli_num_rows($query_run)>0){
                while($row = mysqli_fetch_assoc($query_run)){
                    $asgd_act_id = $row['Asgd_Act_Id'];
                    $query1 = "SELECT * FROM assigned_activity as as_act
                                LEFT JOIN flat as flt on flt.Flat_Id=as_act.Flat_Id
                                where as_act.Asgd_Act_Status=1 and as_act.Asgd_Act_Id='$asgd_act_id'";
                    $query_run1 = mysqli_query($connection, $query1);
                    $row1 = mysqli_fetch_assoc($query_run1);
                    $flat_name=$row1['Flat_Code'].' '.$row1['Flat_Name']; //area name
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
                    // count manpower
                    $DE_Id = $row['DE_Id'];
                    $q5="SELECT COUNT(Asgd_Worker_Id) AS ctn  FROM asgn_worker where DE_Id='$DE_Id' and Asgd_Worker_Status=1";
                    $q5_run=mysqli_query($connection, $q5);
                    $row7 = mysqli_fetch_assoc($q5_run);
                    // COUNT MP
                    $q_mp_count="SELECT SUM(Asgn_MP_Qty) AS mp_c  FROM asgn_mp where DE_Id='$DE_Id' and Asgn_MP_Status=1"; 
                    $q_mp_count_run=mysqli_query($connection, $q_mp_count);
                    $mp_row = mysqli_fetch_assoc($q_mp_count_run);
                    // COUNT SB
                    $q_sb_count="SELECT SUM(Asgn_SB_Qty) AS sb_c  FROM asgn_subcon where DE_Id='$DE_Id' and Asgn_SB_Status=1";
                    $q_sb_count_run=mysqli_query($connection, $q_sb_count);
                    $sb_row = mysqli_fetch_assoc($q_sb_count_run);
                    ///
                    $total_mp =  $row7['ctn']+$mp_row['mp_c']+$sb_row['sb_c'];
                ?>
            <tr>
                <td class="d-none"><?php echo $row['DE_Id']?></td>
                <td class="d-none"><?php echo $asgd_act_id;?></td>
                <td class="record"><?php echo $flat_name;?></td>
                <td><?php $act_name = $row4['Act_Code'].' - '.$row4['Act_Name']; echo $act_name?></td>
                <td><?php echo $row6['Dept_Name']?></td>
                <td><?php echo $row5['Act_Cat_Name']?></td>
                <td>
                    <button type="button" class="btn btn-info record">
                        Record <i class="fa fa-history" area-hidden="true"> </i> 
                    </button>
                </td>
                <td >
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
                <td class="btn-group text-center">
                    <!-- edit -->
                    <button type="button" class="btn btn-success editBtn">
                        <i class="fa fa-pencil" aria-hidden="true"></i>
                    </button>
                    <!-- delete -->
                    <form action="codeb_mult.php" method="POST">
                        <input type="hidden" name="DE_Id" id="Del_DE_Id" value="<?php echo $row['DE_Id']?>">
                        <input type="hidden" name="flat_id" id="del_flat" class="del_flat">
                        <input type="date" name="date" id="date"  class="d-none" value="<?php echo $Date;?>">
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
<!-- END TABLE -->

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
        <form action="codeb_mult.php" method="POST" id="addDE">
            <div class="form-group">
                <div class="row ">
                    <!-- <div class="col-6">
                        <label >Department</label>
                        <select name="dept_id" id="dept" class="form-control" required></select>
                    </div> -->
                    <div class="col-6">
                        <label  class="mt-1">Category</label>
                        <select name="dept_id" id="category" class="form-control" required></select>
                    </div> 
                    <div class="col-4">
                        <label for="">Progress %</label>
                        <input type="number" name="percent" class="form-control" required>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="table-reponsive mt-4">
                    <table class="table table-bordered" id="actTbl">
                        <tr>
                            <th>Activity</th>
                            <th></th>
                        </tr>
                        <tr>
                            <td>
                                <select name="activity_id[]" id="activity" class="form-control" required></select>
                            </td>
                            <td></td>
                        </tr>
                    </table>
                </div>
                <div align="right">
                    <label for="" class="invisible">1</label>
                    <button type="button" name="add" id="newAct" class="btn btn-success btn-xs">+</button>
                </div>
            </div>
            <div class="table-responsive mt-4">
                <table class="table table-bordered" id="materialTbl">
                    <tr>
                        <th>EMT Labours</th>
                        <th></th>
                    </tr>
                    <tr>
                        <td>
                            <select name="employee[]" class="form-control selectpicker" data-live-search="true" id="employee">
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
        <?php 
            $username = $_SESSION['USERNAME'];
            $query = "SELECT * FROM users WHERE USERNAME='$username'";
            $query_run = mysqli_query($connection, $query);
            $row=mysqli_fetch_assoc($query_run);
            $user_id = $row['USER_ID'];
            $dept_id = $row['Dept_Id'];
        ?>
            <input type="hidden" name="flat_id" id="flat_id" value="<?php echo $flt_id?>">
            <input type="hidden" name="dept_id" id="dept_id" value="<?php echo $dept_id?>">
            <input type="date" class="d-none" name="date" value="<?php echo $Date;?>">
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

<!-- End Modal ADD Manpower -->
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
        <!-- THE FORM -->
        <form action="codeb_mult.php" method="POST">
            <div class="form-group d-none" id="act_select" >
                <div class="row">
                    <div class="col-10">
                        <label for="">Activity</label>
                        <select name="act_id" id="" class="form-control">
                            <option value="all">To all Activities</option>
                            <?php
                                if(mysqli_num_rows($query_run_opt2)>0){
                                    while($row_opt2=mysqli_fetch_assoc($query_run_opt2)){
                                        $act_id = $row_opt2['Act_Id'];
                                        // activity name
                                        $q1_ = "SELECT * FROM activity where Act_Id='$act_id'";
                                        $q_act=mysqli_query($connection, $q1_);
                                        $row_act = mysqli_fetch_assoc($q_act);
                                        $act_name = $row_act['Act_Code'].' - '.$row_act['Act_Name'];
                                        echo "<option value=".$act_id.">".$act_name."</option>";
                                    }
                                }
                            ?>
                    </select>
                    </div>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-bordered" id="manpowerTbl">
                    <tr>
                        <th>EMT Labours</th>
                        <th></th>
                    </tr>
                    <tr>
                        <td><select name="employee[]" id="manpower" class="form-control selectpicker" data-live-search="true"></select></td>
                        <td></td>
                    </tr>
                </table>
                <div align="right">
                    <button type="button" name="add" id="addBtnM" class="btn btn-success btn-xs">+</button>
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
            </div>
        <!-- END FORM -->
      </div>
      <input type="hidden" name="flat_id" id="flat_id1" value="<?php echo $flt_id;?>">
      <input type="hidden" name="DE_Id" id="DE_Idm">
      <input type="hidden" name="DE_Ids" id="DE_Idm2">
      <input type="hidden" name="prj_id" value="<?php echo $prj_id?>">
      <input type="date" name="date" class="d-none" value="<?php echo $Date?>">
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-times" aria-hidden="true"></i> Close</button>
        <button type="submit" name="addWorker" id="addWorker" class="btn btn-success"><i class="fa fa-check" aria-hidden="true"></i> Save</button>
        <br>
        <button type="submit" name="addtoAll" id="addWorkertoAll" class="btn btn-success"><i class="fa fa-check" aria-hidden="true"></i> Assign to All</button>
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
        <form action="codeb_mult.php" method="POST">
            <div class="form-group">
                <input type="hidden" name="DE_Id" id="DE_Id" >
                <label>Progress</label>
                <input type="number" name="DE_Pct" id="DE_Pct" class="form-control">
            </div>
      </div>
            <input type="hidden" name="flat_id" id="eflat_id" value="<?php echo $flt_id?>">
            <input type="date" name="date" class="d-none" value="<?php echo $Date?>">
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
<!-- Modal Record -->
<div class="modal fade bd-example-modal-lg" id="editProg" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
          <!-- MODAL TITLE -->
        <h5 class="modal-title" id="exampleModalLabel"><i class="fa fa-history" aria-hidden="true"></i> Records</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <!-- THE FORM -->
        <div id="history">
        </div>
        <!-- END FORM -->
      </div>
    </div>
  </div>
</div>
<!-- End Modal Record -->
<?php
include('activityb_script_mult.php');
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