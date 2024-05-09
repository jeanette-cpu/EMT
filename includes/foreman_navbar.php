<?php 
  if($_SESSION['USERTYPE']!='foreman')
  {
    echo "<script type='text/javascript'>
    alert ('Access Denied, Please try again.');
    window.location.href='login.php';</script>";
  }
?>   
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
<hr class="sidebar-divider d-none">

<!-- Nav Item - Pages Collapse Menu -->
<li class="nav-item ">
  <a class="nav-link" href="index.php">
    <i class="fas fa-fw fa-briefcase"></i>
    <span>Projects</span></a>
</li>

<hr class="sidebar-divider my-0 mt-3">

<li class="nav-item">
  <a class="nav-link " href="project_choose.php">
    <i class="fas fa-fw fa-calendar-check-o" ></i>
      <span>Activity</span></a>
</li>

<!-- <li class="nav-item">
  <a class="nav-link" href="prev_act.php">
  <i class="fas fa-fw fa-check"> </i>
      <span>Activity</span></a>
</li> -->

<li class="nav-item">
  <a class="nav-link" href="#" data-toggle="collapse" data-target="#collapseReport" aria-expanded="false" aria-controls="collapseMaterial">
    <i class="fas fa-fw fa-file-text"></i>
    <span>Report</span></a>
    <div id="collapseReport" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
      <div class="bg-white py-2 collapse-inner rounded">
        <a class="collapse-item" href="r_manpower.php"><i class="fas fa-fw fa-user mr-2"></i> Employee </a>
        <a class="collapse-item" href=r_mp_rating.php><i class="fas fa-fw fa-trophy mr-2"></i>Employee Rating</a>
        <a class="collapse-item" href="r_mp.php"><i class="fas fa-fw fa-child mr-2"></i> Manpower </a>
        <a class="collapse-item" href="r_sb.php"><i class="fas fa-fw fa-users mr-2"></i> Subcontractor </a>
        <a class="collapse-item" href="r_mp_used.php"><i class="fas fa-fw fa-table mr-2"></i>All Manpower</a>
        <a class="collapse-item" href="r_DE_prj-list.php"><i class="fas fa-fw fa-file mr-2"></i> Activity Report</a>
        <a class="collapse-item" href="r_inventory.php"><i class="fas fa-fw fa-archive mr-2"></i> Inventory</a>
      </div>
    </div>
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
            <li class="nav-item dropdown no-arrow d-none">
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

            <li class="nav-item dropdown no-arrow">
              <div class="header mr-auto ">
                <span class="nav-link text-black" style="color:black"><?php date_default_timezone_set('Asia/Dubai'); echo date("F j, Y")?></span>
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
                      // echo $row['EMP_FNAME'];
                    // }
                    echo $username;
                  ?>              
                </span>
                <i class="fa fa-lg fa-user-circle-o " style="color:gray" aria-hidden="true"></i>
              </a>
              <!-- Dropdown - User Information -->
              <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
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
            <form action="../../logout.php" method="POST">          
              <button type="submit" name="logout_btn" class="btn btn-primary">Logout</button>
            </form>
        </div>
      </div>
    </div>
  </div>