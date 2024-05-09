<?php 
error_reporting(0);
  $username=$_SESSION['USERNAME'];
  // $query_user ="SELECT * FROM users WHERE USERNAME='$username'  LIMIT 1";
  $query_user ="SELECT * FROM users WHERE USERNAME='$username' AND USERTYPE='purchase' LIMIT 1";
  $query_run_user = mysqli_query($connection, $query_user);
  if(mysqli_num_rows($query_run_user)==1){
    $row=mysqli_fetch_assoc($query_run_user);
    $user_id=$row['USER_ID'];
  }
  else{
    $user_id='';
    echo "<script type='text/javascript'>
    alert ('Access Denied, Please try again.');
    window.location.href='login.php';</script>";
  }

  // NEW REGISTER NOTIFICATION
  $comp_name='';$q=0; $new_quote_m=null;
  $q_notif="SELECT COUNT(Notif_Id) as cnt, Not_Date, Not_Type, Post_Id FROM notification WHERE User_id='$user_id' AND Not_Status=1 GROUP BY  DAY(Not_Date),Not_Type, Post_Id ORDER BY Not_Date DESC LIMIT 6 ";
  $q_notif_run=mysqli_query($connection,$q_notif);
  
if(mysqli_num_rows($q_notif_run)>0){
    while($row_date=mysqli_fetch_assoc($q_notif_run)){
      $post_id=$row_date['Post_Id'];
      $cnt=$row_date['cnt'];
      $date=$row_date['Not_Date'];
      $not_type=$row_date['Not_Type'];
      if($not_type=='new_reg'){
        $q_comp="SELECT notif.Comp_Id, comp.Comp_Name
              FROM notification as notif
              LEFT JOIN company AS comp on comp.Comp_Id=notif.Comp_Id
              WHERE notif.Not_Date='$date' AND notif.Not_Status=1 AND notif.Not_Type='new_reg' LIMIT 2";
        $q_comp_run=mysqli_query($connection,$q_comp);
        $row_comp=mysqli_fetch_assoc($q_comp_run);
        if($cnt==1){
          $comp_name=$row_comp['Comp_Name'];
          $notif_message=$comp_name.' company new register';
        }
        elseif($cnt==2){
          $q_comp="SELECT notif.Comp_Id, comp.Comp_Name FROM notification as notif LEFT JOIN company AS comp on comp.Comp_Id=notif.Comp_Id WHERE notif.Not_Date='$date' AND notif.Not_Status=1 AND notif.Not_Type='new_reg' LIMIT 2";
          $q_comp_run=mysqli_query($connection,$q_comp);
          // $row_comp=mysqli_fetch_assoc($q_comp_run);
          $comp_name_arr=array();
          while($row_comp_1=mysqli_fetch_assoc($q_comp_run)){
            $comp_name_arr[]=$row_comp_1['Comp_Name'];
          }
          $comp_name = implode(" and ", $comp_name_arr);
          $notif_message=$comp_name.' newly registered';
        }
        elseif($cnt>=3){
          $q_comp="SELECT notif.Comp_Id, comp.Comp_Name FROM notification as notif LEFT JOIN company AS comp on comp.Comp_Id=notif.Comp_Id WHERE notif.Not_Date='$date' AND notif.Not_Status=1 AND notif.Not_Type='new_reg' LIMIT 2";
          $q_comp_run=mysqli_query($connection,$q_comp);
          $cnt=$cnt-2;
          $comp_name_arr=array();
          while($row_comp_1=mysqli_fetch_assoc($q_comp_run)){
            $comp_name_arr[]=$row_comp_1['Comp_Name'];
          }
          $comp_name = implode(", ", $comp_name_arr);
          $notif_message=$comp_name.' and '.$cnt.' others new company registered';
        }
        $format_date=date("M d, Y",strtotime($date));
        echo "<script>
          $(document).ready(function() {
            notif_dropdown.innerHTML += `<a class='dropdown-item d-flex align-items-center' href='#'>
            <div class='mr-3'>
                <div class='icon-circle bg-success'>
                    <i class='fas fa-briefcase text-white'></i>
                </div>
            </div>
            <div>
                <!-- DATE -->
                <div class='small text-gray-500'>$format_date</div>
                <span class='small text-dark-500 font-weight-bold'>New Register: </span>
                $notif_message
            </div>
        </a>`;
        })</script>";
      }
      elseif($not_type=='new_quote' && $post_id){
        $q_details="SELECT notif.Comp_Id, comp.Comp_Name, p.Post_Name
                FROM notification as notif
                LEFT JOIN company AS comp on comp.Comp_Id=notif.Comp_Id
                LEFT JOIN post as p on notif.Post_Id=p.Post_Id
                WHERE notif.Not_Date='$date' AND notif.Not_Status=1 AND notif.Not_Type='new_quote' LIMIT 2";
        $q_details_run=mysqli_query($connection,$q_details);
        $row_details=mysqli_fetch_assoc($q_details_run);
        $post_name=$row_details['Post_Name'];
        if($cnt==1){
          $comp_name=$row_details['Comp_Name'];
          $new_quote_m=$comp_name.' send a quote for '.$post_name;
        }
        elseif($cnt==2){
          $q_comp2="SELECT notif.Comp_Id, comp.Comp_Name, p.Post_Name FROM notification as notif LEFT JOIN company AS comp on comp.Comp_Id=notif.Comp_Id LEFT JOIN post as p on notif.Post_Id=p.Post_Id WHERE notif.Not_Date='$date' AND notif.Not_Status=1 AND notif.Not_Type='new_quote' LIMIT 2";
          $q_comp_run2=mysqli_query($connection,$q_comp2);
          $comp_name_arr2=array();
          while($row_comp_2=mysqli_fetch_assoc($q_comp_run2)){
            $comp_name_arr2[]=$row_comp_2['Comp_Name'];
          }
          $comp_name = implode(" and ", $comp_name_arr2);
          $new_quote_m=$comp_name.' send quote for '.$post_name;
        }
        elseif($cnt>=3){
          $q_comp3="SELECT notif.Comp_Id, comp.Comp_Name, p.Post_Name FROM notification as notif LEFT JOIN company AS comp on comp.Comp_Id=notif.Comp_Id LEFT JOIN post as p on notif.Post_Id=p.Post_Id WHERE notif.Not_Date='$date' AND notif.Not_Status=1 AND notif.Not_Type='new_quote' LIMIT 2";
          $q_comp_run3=mysqli_query($connection,$q_comp3);
          $comp_name_arr3=array();
          while($row_comp_3=mysqli_fetch_assoc($q_comp_run3)){
            $comp_name_arr3[]=$row_comp_3['Comp_Name'];
          }
          $cnt=$cnt-2;
          $comp_name = implode(", ", $comp_name_arr3);
          $new_quote_m=$comp_name.' and '.$cnt.' others send quote for '.$post_name;
        }
        $format_date=date("M d, Y",strtotime($date));
        echo "<script>
          $(document).ready(function() {
            notif_dropdown.innerHTML += `<a class='dropdown-item d-flex align-items-center' href='#'>
            <div class='mr-3'>
                <div class='icon-circle bg-info'>
                    <i class='fas fa-file-text text-white'></i>
                </div>
            </div>
            <div>
                <!-- DATE -->
                <div class='small text-gray-500'>$format_date</div>
                <span class='small text-dark-500 font-weight-bold'>Sent Quote:</span>
                $new_quote_m
            </div>
        </a>`;
        })</script>";
      }
      else{}
    }
  $total_not=mysqli_num_rows($q_notif_run);
    echo '<script>
    $(document).ready(function() {
        document.getElementById("not_no").textContent='.$total_not.';
        $("#not_no").removeClass("d-none");
    });
    </script>';
}
else{
  $inactive_notif="SELECT COUNT(Notif_Id) as cnt, Not_Date, Not_Type, Post_Id FROM notification WHERE User_id='$user_id' AND Not_Status=0 GROUP BY Post_Id,Not_Type,DAY(Not_Date) ORDER BY Not_Date DESC limit 6";
  $inactive_notif_run=mysqli_query($connection,$inactive_notif);
  if(mysqli_num_rows($inactive_notif_run)>0){
    while($row_date=mysqli_fetch_assoc($inactive_notif_run)){
      $cnt=$row_date['cnt'];
      $date=$row_date['Not_Date'];
        $post_id=$row_date['Post_Id'];
      $not_type=$row_date['Not_Type'];
      if($not_type=='new_reg'){
        $q_comp="SELECT notif.Comp_Id, comp.Comp_Name
              FROM notification as notif
              LEFT JOIN company AS comp on comp.Comp_Id=notif.Comp_Id
              WHERE notif.Not_Date='$date' AND notif.Not_Status=0 AND notif.Not_Type='new_reg'  LIMIT 2";
        $q_comp_run=mysqli_query($connection,$q_comp);
        $row_comp=mysqli_fetch_assoc($q_comp_run);
        if($cnt==1){
          $comp_name=$row_comp['Comp_Name'];
          $notif_message=$comp_name.' company new register';
        }
        elseif($cnt==2){
          $q_comp="SELECT notif.Comp_Id, comp.Comp_Name FROM notification as notif LEFT JOIN company AS comp on comp.Comp_Id=notif.Comp_Id WHERE notif.Not_Date='$date' AND notif.Not_Status=0 AND notif.Not_Type='new_reg' LIMIT 2";
          $q_comp_run=mysqli_query($connection,$q_comp);
          // $row_comp=mysqli_fetch_assoc($q_comp_run);
          $comp_name_arr=array();
          while($row_comp_1=mysqli_fetch_assoc($q_comp_run)){
            $comp_name_arr[]=$row_comp_1['Comp_Name'];
          }
          $comp_name = implode(" and ", $comp_name_arr);
          $notif_message=$comp_name.' newly registered';
        }
        elseif($cnt>=3){
          $q_comp="SELECT notif.Comp_Id, comp.Comp_Name FROM notification as notif LEFT JOIN company AS comp on comp.Comp_Id=notif.Comp_Id WHERE notif.Not_Date='$date' AND notif.Not_Status=0 AND notif.Not_Type='new_reg' LIMIT 2";
          $q_comp_run=mysqli_query($connection,$q_comp);
          $cnt=$cnt-2;
          $comp_name_arr=array();
          while($row_comp_1=mysqli_fetch_assoc($q_comp_run)){
            $comp_name_arr[]=$row_comp_1['Comp_Name'];
          }
          $comp_name = implode(", ", $comp_name_arr);
          $notif_message=$comp_name.' and '.$cnt.' others new company registered';
        }
        $format_date=date("M d, Y",strtotime($date));
        echo "<script>
          $(document).ready(function() {
            notif_dropdown.innerHTML += `<a class='dropdown-item d-flex align-items-center' href='#'>
            <div class='mr-3'>
                <div class='icon-circle bg-success'>
                    <i class='fas fa-briefcase text-white'></i>
                </div>
            </div>
            <div>
                <!-- DATE -->
                <div class='small text-gray-500'>$format_date</div>
                <span class='small text-dark-500 font-weight-bold'>New Register: </span>
                $notif_message
            </div>
        </a>`;
        })</script>";
      }
      elseif($not_type=='new_quote' && $post_id){
        $q_details="SELECT notif.Comp_Id, comp.Comp_Name, p.Post_Name
                FROM notification as notif
                LEFT JOIN company AS comp on comp.Comp_Id=notif.Comp_Id
                LEFT JOIN post as p on notif.Post_Id=p.Post_Id
                WHERE notif.Not_Date='$date' AND notif.Not_Status=0 AND notif.Not_Type='new_quote' AND p.Post_Id='$post_id' LIMIT 2";
        $q_details_run=mysqli_query($connection,$q_details);
        $row_details=mysqli_fetch_assoc($q_details_run);
        if($cnt==1){
          $comp_name=$row_details['Comp_Name'];
          $post_name=$row_details['Post_Name'];
          $new_quote_m=$comp_name.' send a quote for '.$post_name;
        }
        elseif($cnt==2){
          $q_comp2="SELECT notif.Comp_Id, comp.Comp_Name, p.Post_Name FROM notification as notif LEFT JOIN company AS comp on comp.Comp_Id=notif.Comp_Id LEFT JOIN post as p on notif.Post_Id=p.Post_Id WHERE notif.Not_Date='$date' AND notif.Not_Status=0 AND notif.Not_Type='new_quote' AND p.Post_Id='$post_id'  LIMIT 2";
          $q_comp_run2=mysqli_query($connection,$q_comp2);
          $row_p=mysqli_fetch_assoc($q_comp_run2);
          $post_name=$row_p['Post_Name'];
          $comp_name_arr2=array();
          while($row_comp_2=mysqli_fetch_assoc($q_comp_run2)){
            $comp_name_arr2[]=$row_comp_2['Comp_Name'];
          }
          $comp_name = implode(" and ", $comp_name_arr2);
          $new_quote_m=$comp_name.' send quote for '.$post_name;
        }
        // elseif($cnt>=3){
        //   $q_comp3="SELECT notif.Comp_Id, comp.Comp_Name, p.Post_Name FROM notification as notif LEFT JOIN company AS comp on comp.Comp_Id=notif.Comp_Id LEFT JOIN post as p on notif.Post_Id=p.Post_Id WHERE notif.Not_Date='$date' AND notif.Not_Status=0 AND notif.Not_Type='new_quote' AND p.Post_Id='$post_id' LIMIT 2";
        //   $q_comp_run3=mysqli_query($connection,$q_comp3);
        //   if(mysqli_num_rows($q_comp_run3)>0){
        //     $row_p=mysqli_fetch_assoc($q_comp_run3);
        //     $post_name=$row_p['Post_Name'];
        //     $comp_name_arr3=array();
        //     while($row_comp_3=mysqli_fetch_assoc($q_comp_run3)){
        //       $comp_name_arr3[]=$row_comp_3['Comp_Name'];
        //     }
        //     $cnt=$cnt-2;
        //     $comp_name = implode(", ", $comp_name_arr3);
        //     $new_quote_m=$comp_name.' and '.$cnt.' others send quote for '.$post_name;
        //   }
        //   else{
        //     $new_quote_m='';
        //   }
        // }
        $format_date=date("M d, Y",strtotime($date));
        echo "<script>
          $(document).ready(function() {
            notif_dropdown.innerHTML += `<a class='dropdown-item d-flex align-items-center' href='#'>
            <div class='mr-3'>
                <div class='icon-circle bg-info'>
                    <i class='fas fa-file-text text-white'></i>
                </div>
            </div>
            <div>
                <!-- DATE -->
                <div class='small text-gray-500'>$format_date</div>
                <span class='small text-dark-500 font-weight-bold'>Sent Quote:</span>
                $new_quote_m
            </div>
        </a>`;
        })</script>";
      }
      else{}
    }
  }
  else{}
}
  
?>   
  <input type="hidden" id="user_id" value="<?php echo $user_id?>">

   <!-- Sidebar -->
   <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

<!-- Sidebar - Brand -->
<a class="sidebar-brand d-flex align-items-center justify-content-center" href="#">
  <div class="sidebar-brand-icon">
    <div class="mt-4">
      <img src="img\logowhites.png" alt="emt-logo" style="fill:#000 !important;" width="83" height="43">
    </div>
  </div>
  <div class="sidebar-brand-text mx-3"></div>
</a>

<!-- Divider -->
<hr class="sidebar-divider my-0 mt-3">

<!-- Divider -->
<hr class="sidebar-divider">

<!-- Nav Item - Pages Collapse Menu -->
<li class="nav-item">
  <a class="nav-link" href="index.php">
    <i class="fas fa-fw fa-line-chart"></i>
    <span>Dashboard</span></a>
</li>

<hr class="sidebar-divider my-0 mt-3">

<li class="nav-item">
  <a class="nav-link" href="#" data-toggle="collapse" data-target="#collapseReport" aria-expanded="false" aria-controls="collapseMaterial">
    <i class="fas fa-fw fa-comments"></i> <span>Post</span></a>
    <div id="collapseReport" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
      <div class="bg-white py-2 collapse-inner rounded">
        <a class="collapse-item" href="p_job_post.php"><i class="fas fa-fw fa-user mr-2"></i>Job</a>
        <a class="collapse-item" href="p_material_post.php"><i class="fas fa-fw fa-users mr-2"></i>Material</a>
      </div>
    </div>
</li>
<li class="nav-item">
  <a class="nav-link" href="email_grps.php">
    <i class="fas fa-fw fa-envelope"></i>
    <span>Email Groups</span></a>
</li>
<li class="nav-item">
  <a class="nav-link" href="#" data-toggle="collapse" data-target="#collapse" aria-expanded="false" aria-controls="collapse">
    <i class="fas fa-fw fa-building"></i> <span>Company</span></a>
    <div id="collapse" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
      <div class="bg-white py-2 collapse-inner rounded">
        <a class="collapse-item" href="a_comp_approval.php"><i class="fas fa-fw fa-building mr-2"></i>Company for Approval</a>
        <a class="collapse-item" href="approved_company.php"><i class="fas fa-fw fa-building mr-2"></i>Listed Company</a>
      </div>
    </div>
</li>
<li class="nav-item">
  <a class="nav-link" href="m_material.php">
    <i class="fas fa-fw fa-archive" ></i>
      <span>Material</span></a>
</li>
<li class="nav-item">
  <a class="nav-link" href="record.php">
    <i class="fas fa-fw fa-book" ></i>
      <span>Record</span></a>
</li>
<li class="nav-item">
  <a class="nav-link" href="purchase_user.php">
    <i class="fas fa-fw fa-user" ></i>
      <span>Users</span></a>
</li>
<!-- Nav Item LOGOUT -->
<li class="nav-item">
  <a class="nav-link" data-toggle="modal" data-target="#logoutModal">
    <i class="fas fa-fw fa-sign-out-alt" ></i>
      <span>Logout</span></a>
</li>
<!-- Sidebar Toggler (Sidebar) -->
<div class="text-center d-none d-md-inline">
  <button class="rounded-circle border-0" id="sidebarToggle"></button>
</div>

</ul>
<!-- End of Sidebar -->
    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">
      <!-- Main Content -->
      <div id="content">
        <!-- Topbar -->
        <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">
          <!-- Sidebar Toggle (Topbar) -->
          <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
            <i class="fa fa-bars"></i>
          </button>
          <!-- Topbar Navbar -->
          <ul class="navbar-nav ml-auto">
            <!-- Nav Item - Search Dropdown (Visible Only XS) -->
            <li class="nav-item dropdown no-arrow d-sm-none">
              <a class="nav-link dropdown-toggle" href="#" id="searchDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="fas fa-search fa-fw"></i>
              </a>
              <!-- Dropdown - Messages -->
              <div class="dropdown-menu dropdown-menu-right p-3 shadow animated--grow-in" aria-labelledby="searchDropdown">
                <form class="form-inline mr-auto w-100 navbar-search">
                  <div class="input-group">
                    <input type="text" class="form-control bg-light border-0 small" placeholder="Search for..." aria-label="Search" aria-describedby="basic-addon2">
                    <div class="input-group-append">
                      <button class="btn btn-primary" type="button">
                        <i class="fas fa-search fa-sm"></i>
                      </button>
                    </div>
                  </div>
                </form>
              </div>
            </li>
            <!-- Nav Item - Alerts -->
            <li class="nav-item dropdown no-arrow mx-1">
                <a id="bell_icon" class="nav-link dropdown-toggle" href="#" id="alertsDropdown" role="button"
                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fas fa-bell fa-fw"></i>
                    <!-- Counter - Alerts -->
                    <span id="not_no" class="badge badge-danger badge-counter d-none"></span>
                </a>
                <!-- Dropdown - Alerts -->
                <div id="notif_dropdown" class="dropdown-list dropdown-menu dropdown-menu-right shadow animated--grow-in"
                    aria-labelledby="alertsDropdown">
                    <h6 class="dropdown-header">
                        Notifications
                    </h6>
                </div>
            </li>
            <div class="topbar-divider d-none d-sm-block"></div>
            <!-- Nav Item - User Information -->
            <li class="nav-item dropdown no-arrow">
              <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <span class="mr-2 d-none d-lg-inline text-gray-600 small">
                <!-- DISPLAY OF CURRENT USER -->
                <?php 
                    $username = $_SESSION['USERNAME'];
                    // $query = "SELECT * FROM `asgn_emp_to_prj` as ass_e LEFT JOIN employee as e on e.EMP_ID = ass_e.Emp_Id LEFT JOIN users as u on u.USER_ID = ass_e.User_Id WHERE u.USERNAME = '$username' and u.USER_STATUS=1 and e.EMP_STATUS=1 and ass_e.Asgd_Emp_to_Prj_Status=1;";
                    // $query_run = mysqli_query($connection, $query);
                    // foreach($query_run as $row)
                    // {
                    //   echo $row['EMP_FNAME'];
                    // }
                    echo $username;
                  ?>              
                </span>
                <i class="fa fa-lg fa-user-circle-o " style="color:gray" aria-hidden="true"></i>
              </a>
              <!-- Dropdown - User Information -->
              <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
                
                <!-- <a class="dropdown-item" href="user_profile.php">
                  <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                  Profile
                </a>

                <a class="dropdown-item" href="user_settings.php">
                  <i class="fas fa-cogs fa-sm fa-fw mr-2 text-gray-400"></i>
                  Settings
                </a> -->
                
                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
                  <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                  Logout
                </a>
              </div>
            </li>

          </ul>

        </nav>
        <!-- End of Topbar -->

  <!-- Scroll to Top Button-->
  <a class="scroll-to-top rounded" href="#page-top">
    <i class="fas fa-angle-up"></i>
  </a>
 
  <!-- Logout Modal-->
  <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">×</span>
            </button>
        </div>  
        <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
        <div class="modal-footer">
          <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
            <form action="../logout.php" method="POST">          
              <button type="submit" name="logout_btn" class="btn btn-primary">Logout</button>
            </form>
        </div>
      </div>
    </div>
  </div>
<script>
$(document).ready(function () {
    $(document).on('click','#bell_icon', function(){
      $("#not_no").addClass("d-none");
      var user_id=$('#user_id').val();
      $.ajax({
            url: 'ajax_purchase.php',
            method: 'POST',
            data:{'user_id':user_id},
            success:function(data){
                $('.selectpicker').selectpicker('refresh');
            }
        });
  });
});
</script>