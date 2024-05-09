<?php
include('../../security.php');
include('../../includes/header.php'); 
include('../../includes/foreman_navbar.php'); 
?>
<!-- Begin Page Content -->
<div class="container-fluid">
  <div class="card shadow mb-4">
    <div class="card-header py-3">
      <h5 class="m-0 font-weight-bold text-primary">Employees Ratings
    </div>
        <h5 class="text-primary pl-4 pt-3" id="h5">Overall Rating</h5>
      <div class="card-body">
        <form action="r_mp_rating.php" method="post">
          <div class="form-row">
            <div class="col-3 pb-3">
                <label for="">Project</label>
                  <select name="prj_id" id="prj_opt" class="form-control" >
                    <option value="">Any</option>
                  </select>
            </div>
            <div class="col-2 pb-3">
                <label for="">Department</label>
                  <select name="dept_id" id="dept_opt" class="form-control" >
                    <option value="">Select Department</option>
                  </select>
            </div>
            <div class="col-3 pb-3">
                <label for="">Activity Category</label>
                  <select name="act_cat_id" id="act_cat_opt" class="form-control" >
                    <option value="">Select Category</option>
                  </select>
            </div>
            <div class="col-3 pb-3">
                <label for="">Activity </label>
                  <select name="act_id" id="act_opt" class="form-control selectpicker" data-live-search="true" required>
                    <option value="">Select Activity</option>
                  </select>
            </div>
            <div class="col-1">
              <label for="" class="pb-3"></label>
              <button class="form-control btn btn-success" name="search" type="submit">Search</button>
            </div>
          </div>
        </form>
        
          <div class="table-responsive">
            <table class="table table-bordered table-striped" id="dataTable" width="100%" cellspacing="0">
              <thead>
                <th>Employee Code</th>
                <th>Name</th>
                <th>Designation</th>
                <th>Days</th>
                <th>Ave Material Used Per Day</th>
              </thead>
              <tbody>
    <?php
      if(isset($_POST['search']))
      {
        $prj_id=$_POST['prj_id'];
        $act_id=$_POST['act_id'];
        $act_name="SELECT * FROM activity WHERE Act_Id='$act_id'";
        $act_name_run=mysqli_query($connection,$act_name);
        $row_aname=mysqli_fetch_assoc($act_name_run);
        ?>
        <input type="hidden" id="act_n" value="<?php echo $row_aname["Act_Name"]?>">
        <?php
        echo' <script>
                $(document).ready(function(){
                  name = $("#act_n").val();
                  name ="Top "+name+" Worker";
                  $("#h5").html(name);
                })
              </script>';
        // no prj, with act_id
        if($prj_id=='Select Project')
        {
          //all employee on site
          $q_emp="SELECT Emp_Id AS EMP_ID FROM asgn_worker GROUP BY Emp_Id";
          $q_emp_run=mysqli_query($connection,$q_emp);
          if(mysqli_num_rows($q_emp_run)>0)
            {
              while($row_mp=mysqli_fetch_assoc($q_emp_run))
              {
                  $emp_id= $row_mp['EMP_ID']; 
                  $query="SELECT as_act.Asgd_Act_Id as asgd, as_act.Act_Id as act_id, de.DE_Pct_Done as pct, as_act.Flat_Id as flt_id 
                          FROM asgn_worker as as_w
                          LEFT JOIN daily_entry as de on de.DE_Id=as_w.DE_Id 
                          LEFT JOIN assigned_activity as as_act on de.Asgd_Act_Id = as_act.Asgd_Act_Id 
                          WHERE as_w.Asgd_Worker_Status=1 and as_w.Emp_Id='$emp_id' and as_act.Act_Id='$act_id'"; $d=0;$tot_mat_emp=0;
                  // echo $query;
                  $query_run=mysqli_query($connection,$query);
                  if(mysqli_num_rows($query_run)>0)
                  {
                    while($row=mysqli_fetch_assoc($query_run))
                    {
                        $flat_id = $row['flt_id'];
                        $pct_done = $row['pct']; $asgd = $row['asgd'];

                        //sum of material assigned
                        $q_mat="SELECT SUM(Asgd_Mat_to_Act_Qty) as msum FROM asgn_mat_to_act as as_m  
                        LEFT JOIN assigned_material as mat on mat.Asgd_Mat_Id = as_m.Asgd_Mat_Id 
                        LEFT JOIN material on material.Mat_Id = mat.Mat_Id 
                        WHERE as_m.Asgn_Mat_to_Act_Status=1 and as_m.Asgd_Act_Id='$asgd'";
                        // ECHO $q_mat;
                        $q_mat_run=mysqli_query($connection,$q_mat);
                        $row_mat_sum=mysqli_fetch_assoc($q_mat_run);
                        
                        //MATERIAL Computation
                          $pct = 0.01*$row_mat_sum['msum'];
                          $mat_used= $pct*$pct_done;
                          $tot_mat_emp=$tot_mat_emp+$mat_used;
                          $d++;
                    }
                  }
                  if ($d!=0){
                    $ave=$tot_mat_emp/$d;
                    $emp="SELECT EMP_NO, EMP_FNAME, EMP_LNAME, EMP_DESIGNATION FROM employee Where EMP_ID='$emp_id'";
                    // echo $emp;
                    $emp_run=mysqli_query($connection,$emp);
                    $row_emp=mysqli_fetch_assoc($emp_run);
                    ?>
                    <tr>
                      <td><?php echo $row_emp['EMP_NO']?></td>
                      <td><?php echo $row_emp['EMP_FNAME'].' '.$row_emp['EMP_LNAME'] ?></td>
                      <td><?php echo $row_emp['EMP_DESIGNATION']?></td>
                      <td><?php echo $d?></td>
                      <td><?php echo  number_format($ave, 2, '.', '');?></td>
                    </tr>
                  <?php
                  }
              }
            }
        }// with prj, & act
        else{
            //all employee on site
            $q_emp="SELECT Emp_Id AS EMP_ID FROM asgn_worker GROUP BY Emp_Id"; 
            $q_emp_run=mysqli_query($connection,$q_emp);
            // select flat ids
            $prj_query = "SELECT * FROM project WHERE Prj_Status =1 and Prj_Id='$prj_id'";
            $prj_query_run = mysqli_query($connection, $prj_query);
            if(mysqli_num_rows($prj_query_run)>0)
            {
              while($prow = mysqli_fetch_assoc($prj_query_run))
                {
                  $category = $prow['Prj_Category'];echo $category;
                  $prj_id = $prow['Prj_Id'];
                  if($category=='Building')
                  {
                      // get building assigned
                      $q_building = "SELECT Blg_Id FROM building where Blg_Status='1' AND Prj_Id='$prj_id'";
                      echo $q_building;
                      $q_building_run = mysqli_query($connection, $q_building);
                      $b_id_arr=null; $b_ids= null;
                      if(mysqli_num_rows($q_building_run)>0)
                      {
                          while($row_b = mysqli_fetch_assoc($q_building_run))
                          { $b_id_arr[] = $row_b['Blg_Id']; }
                          $b_ids = implode("', '", $b_id_arr); // get levels
                          $q_levels ="SELECT Lvl_Id from level where Lvl_Status=1 and Blg_Id in ('$b_ids')";
                          $q_level_run = mysqli_query($connection, $q_levels);
                          $lvl_id_arr=null; $lvl_ids = null;
                          if(mysqli_num_rows($q_level_run)>0)
                          {
                              while($row_l = mysqli_fetch_assoc($q_level_run))
                              { $lvl_id_arr[] = $row_l['Lvl_Id']; }
                              $lvl_ids = implode("', '", $lvl_id_arr); // get flat id
                              $q_flat = "SELECT Flat_Id from flat WHERE Flat_Status=1 and Lvl_Id in ('$lvl_ids')";
                              $q_flat_run = mysqli_query($connection, $q_flat);
                              $flt_ids =null; $flat_id_arr=null;
                              if(mysqli_num_rows($q_flat_run)>0)
                              {
                                  while($row_f = mysqli_fetch_assoc($q_flat_run))
                                  { $flat_id_arr[] = $row_f['Flat_Id']; }
                                  $flt_ids = implode("', '", $flat_id_arr);
                              }
                            }
                      }
                                  
                  }
                  elseif($category=='Villa')
                  {
                      // get villas assigned
                      $q_villa = "SELECT Villa_Id FROM villa where Villa_Status='1' AND Prj_Id='$prj_id'";
                      $q_villa_run = mysqli_query($connection, $q_villa);
                      if(mysqli_num_rows($q_villa_run)>0)
                      {
                          $villa_id_arr= null; $villa_ids= null;
                          while($row_v = mysqli_fetch_assoc($q_villa_run))
                          {
                              $villa_id_arr[] = $row_v['Villa_Id'];
                          }
                          $villa_ids = implode("', '", $villa_id_arr);
                          // get plex
                          $q_plex = "SELECT Plx_Id from plex where Plx_Status='1' and Villa_Id in ('$villa_ids')";
                          $q_plex_run = mysqli_query($connection, $q_plex);
                          $plex_id_arr=null; $plex_ids=null;
                          if(mysqli_num_rows($q_plex_run)>0)
                          {
                              while($row_p = mysqli_fetch_assoc($q_plex_run))
                              {
                                  $plex_id_arr[] = $row_p['Plx_Id'];
                              }
                              $plex_ids = implode("', '", $plex_id_arr);
                              // get building
                              $q_building = "SELECT Blg_Id FROM building where Blg_Status='1' AND Plx_Id in ('$plex_ids')";
                              $q_building_run = mysqli_query($connection, $q_building);
                              $b_id_arr = null; $b_ids = null;
                              if(mysqli_num_rows($q_building_run)>0)
                              {
                                  while($row_b = mysqli_fetch_assoc($q_building_run))
                                  {
                                      $b_id_arr[] = $row_b['Blg_Id'];
                                  }
                                  $b_ids = implode("', '", $b_id_arr);
                                  // get levels
                                  $q_levels ="SELECT Lvl_Id from level where Lvl_Status=1 and Blg_Id in ('$b_ids')";
                                  $q_level_run = mysqli_query($connection, $q_levels);
                                  $lvl_id_arr=null;$lvl_ids=null;
                                  if(mysqli_num_rows($q_level_run)>0)
                                  {
                                      while($row_l = mysqli_fetch_assoc($q_level_run))
                                      {
                                          $lvl_id_arr[] = $row_l['Lvl_Id'];
                                      }
                                      $lvl_ids = implode("', '", $lvl_id_arr);
                                      // get flat id
                                      $q_flat = "SELECT Flat_Id from flat WHERE Flat_Status=1 and Lvl_Id in ('$lvl_ids')";
                                      $q_flat_run = mysqli_query($connection, $q_flat);
                                      $flat_id_arr=null;
                                      if(mysqli_num_rows($q_flat_run)>0)
                                      {
                                          while($row_f = mysqli_fetch_assoc($q_flat_run))
                                          {
                                              $flat_id_arr[] = $row_f['Flat_Id'];
                                          }
                                          $flt_ids = implode("', '", $flat_id_arr);
                                      }
                                  }
                              }
                          }
                      }
                  }
                }
            }
            // echo '<br>'.$flt_ids;
            if(mysqli_num_rows($q_emp_run)>0)
            {
              while($row_mp=mysqli_fetch_assoc($q_emp_run))
              {
                  $emp_id= $row_mp['EMP_ID']; 
                  $query="SELECT as_act.Asgd_Act_Id as asgd, as_act.Act_Id as act_id, de.DE_Pct_Done as pct, as_act.Flat_Id as flt_id 
                          FROM asgn_worker as as_w
                          LEFT JOIN daily_entry as de on de.DE_Id=as_w.DE_Id 
                          LEFT JOIN assigned_activity as as_act on de.Asgd_Act_Id = as_act.Asgd_Act_Id 
                          WHERE as_w.Asgd_Worker_Status=1 and as_w.Emp_Id='$emp_id' and as_act.Act_Id='$act_id' and as_act.Flat_Id in ('$flt_ids')"; $d=0;$tot_mat_emp=0;
                  // echo $query;
                  $query_run=mysqli_query($connection,$query);
                  if(mysqli_num_rows($query_run)>0)
                  {
                    while($row=mysqli_fetch_assoc($query_run))
                    {
                        $flat_id = $row['flt_id'];
                        $pct_done = $row['pct']; $asgd = $row['asgd'];

                        //sum of material assigned
                        $q_mat="SELECT SUM(Asgd_Mat_to_Act_Qty) as msum FROM asgn_mat_to_act as as_m  
                        LEFT JOIN assigned_material as mat on mat.Asgd_Mat_Id = as_m.Asgd_Mat_Id 
                        LEFT JOIN material on material.Mat_Id = mat.Mat_Id 
                        WHERE as_m.Asgn_Mat_to_Act_Status=1 and as_m.Asgd_Act_Id='$asgd'";
                        $q_mat_run=mysqli_query($connection,$q_mat);
                        $row_mat_sum=mysqli_fetch_assoc($q_mat_run);
                        
                        //MATERIAL Computation
                          $pct = 0.01*$row_mat_sum['msum'];
                          $mat_used= $pct*$pct_done;
                          $tot_mat_emp=$tot_mat_emp+$mat_used;
                          $d++;
                    }
                  }
                  if ($d!=0){
                    $ave=$tot_mat_emp/$d;
                    $emp="SELECT EMP_NO, EMP_FNAME, EMP_LNAME, EMP_DESIGNATION FROM employee Where EMP_ID='$emp_id'";
                    $emp_run=mysqli_query($connection,$emp);
                    $row_emp=mysqli_fetch_assoc($emp_run);
                    ?>
                    <tr>
                      <td><?php echo $row_emp['EMP_NO']?></td>
                      <td><?php echo $row_emp['EMP_FNAME'].' '.$row_emp['EMP_LNAME'] ?></td>
                      <td><?php echo $row_emp['EMP_DESIGNATION']?></td>
                      <td><?php echo $d?></td>
                      <td><?php echo  number_format($ave, 2, '.', '');?></td>
                    </tr>
                  <?php
                  }
              }
            }
        }
      }
      else{
        //all employee on site
        $q_emp="SELECT Emp_Id AS EMP_ID FROM asgn_worker GROUP BY Emp_Id";
        $q_emp_run=mysqli_query($connection,$q_emp);
       if(mysqli_num_rows($q_emp_run)>0)
        {
          while($row_mp=mysqli_fetch_assoc($q_emp_run))
          {
              $emp_id= $row_mp['EMP_ID']; 
              $query="SELECT as_act.Asgd_Act_Id as asgd, as_act.Act_Id as act_id, de.DE_Pct_Done as pct, as_act.Flat_Id as flt_id 
                      FROM asgn_worker as as_w
                      LEFT JOIN daily_entry as de on de.DE_Id=as_w.DE_Id 
                      LEFT JOIN assigned_activity as as_act on de.Asgd_Act_Id = as_act.Asgd_Act_Id 
                      WHERE as_w.Asgd_Worker_Status=1 and as_w.Emp_Id='$emp_id'" ; $d=0;$tot_mat_emp=0;
              $query_run=mysqli_query($connection,$query);
              if(mysqli_num_rows($query_run)>0)
              {
                while($row=mysqli_fetch_assoc($query_run))
                {
                    $act_id = $row['act_id']; $flat_id = $row['flt_id'];
                    $pct_done = $row['pct']; $asgd = $row['asgd'];
                    //sum of material assigned
                    $q_mat="SELECT SUM(Asgd_Mat_to_Act_Qty) as msum FROM asgn_mat_to_act as as_m  
                    LEFT JOIN assigned_material as mat on mat.Asgd_Mat_Id = as_m.Asgd_Mat_Id 
                    LEFT JOIN material on material.Mat_Id = mat.Mat_Id 
                    WHERE as_m.Asgn_Mat_to_Act_Status=1 and as_m.Asgd_Act_Id='$asgd'";
                    $q_mat_run=mysqli_query($connection,$q_mat);
                    $row_mat_sum=mysqli_fetch_assoc($q_mat_run);
                    //MATERIAL Computation
                      $pct = 0.01*$row_mat_sum['msum'];
                      $mat_used= $pct*$pct_done;
                      $tot_mat_emp=$tot_mat_emp+$mat_used;
                      $d++;
                }
              }
              if ($d!=0){
                $ave=$tot_mat_emp/$d;
                $emp="SELECT EMP_NO, EMP_FNAME, EMP_LNAME, EMP_DESIGNATION FROM employee Where EMP_ID='$emp_id'";
                $emp_run=mysqli_query($connection,$emp);
                $row_emp=mysqli_fetch_assoc($emp_run);
                ?>
                <tr>
                  <td><?php echo $row_emp['EMP_NO']?></td>
                  <td><?php echo $row_emp['EMP_FNAME'].' '.$row_emp['EMP_LNAME'] ?></td>
                  <td><?php echo $row_emp['EMP_DESIGNATION']?></td>
                  <td><?php echo $d?></td>
                  <td><?php echo  number_format($ave, 2, '.', '');?></td>
                </tr>
              <?php
              }
          }
        }
      }
    ?>
              </tbody>
              </table>
            </div>
        </div>
    </div>
</div>
<script>
  $(document).ready(function() {
    $('#dataTable').DataTable( {
        "order": [[ 4, "desc" ]]
    } );
    $.ajax({
        url:'../P_ADMIN/ajax_dept.php',
        method: 'POST',
        data:{},
        success:function(data){
            $('#dept_opt').html(data).change();
            $('.selectpicker').selectpicker('refresh');
        }
    });
    $.ajax({
        url:'../P_ADMIN/ajax_project.php',
        method: 'POST',
        data:{},
        success:function(data){
            $('#prj_opt').html(data).change();
            $('.selectpicker').selectpicker('refresh');
        }
    });
    $('#dept_opt').change(function(e) { 
        if(e.originalEvent)
        {
          var dept_id = $(this).val();
          $.ajax({
              url:'../P_ADMIN/ajax_act_cat.php',
              method: 'POST',
              data:{"dept_id":dept_id},
              success:function(data){
                  $('#act_cat_opt').html(data).change();
                  $('.selectpicker').selectpicker('refresh');
              }
          });
        }
    });
    $('#act_cat_opt').change(function(e) { 
        if(e.originalEvent)
        {
          var cat_id = $(this).val();
          $.ajax({
              url:'../P_ADMIN/ajax_act_cat.php',
              method: 'POST',
              data:{"act_cat_id":cat_id},
              success:function(data){
                  $('#act_opt').html(data).change();
                  $('.selectpicker').selectpicker('refresh');
              }
          });
        }
    });
} );
</script>
<?php
include('../../includes/scripts.php');
include('../../includes/footer.php');
?>