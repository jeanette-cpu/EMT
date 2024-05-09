<?php 
error_reporting(0);
  $username=$_SESSION['USERNAME'];
  $query_user ="SELECT * FROM users WHERE USERNAME='$username' AND USERTYPE='company' LIMIT 1";
  $query_run_user = mysqli_query($connection, $query_user);
  if(mysqli_num_rows($query_run_user)==1){
    $row=mysqli_fetch_assoc($query_run_user);
    $user_id=$row['USER_ID']; 
    //company details
    $comp="SELECT * FROM company WHERE User_Id='$user_id' AND Comp_Status=1 and Comp_Approval=1 LIMIT 1";
    $comp_run=mysqli_query($connection,$comp);
    $row_comp=mysqli_fetch_assoc($comp_run);
    $comp_type=$row_comp['Comp_Type'];
    if( $comp_type=='laborSupply' || $comp_type=='agency'){
      $post_type="manpower";
    }
    elseif($comp_type=='subcon' ){
      $post_type="subcontractor";
    }
    elseif($comp_type=='trading' || $comp_type=='distributor' || $comp_type=='oem'){
      $post_type="material";
    }
    // problem
    elseif($comp_type="both"){
      $post_type="";
      $query_post="SELECT * FROM notification as notif
                  LEFT JOIN post as p on notif.Post_Id=p.Post_Id
                  WHERE notif.user_id='$user_id' and p.Post_Status=1 and  notif.Not_Type='new_post' and notif.Not_Status=1";
    }
    else{$user_id='';
      echo "<script type='text/javascript'>
      alert ('Access Denied, Please try again.');
      window.location.href='login.php';</script>"; }
  }
  
  else{
    $user_id='';
    echo "<script type='text/javascript'>
    alert ('Access Denied, Please try again.');
    window.location.href='login.php';</script>";
  }
// all active notifications
$comp_name='';$q=0;$notif_message='';
$q_notif="SELECT COUNT(Notif_Id) as cnt, Not_Date, Not_Type, Quote_Id 
          FROM notification WHERE User_id='$user_id' AND Not_Status=1 
          GROUP BY DAY(Not_Date), Not_Type ORDER BY Not_Date ASC LIMIT 6";
$q_notif_run=mysqli_query($connection,$q_notif);

if(mysqli_num_rows($q_notif_run)>0){
    while($row_date=mysqli_fetch_assoc($q_notif_run)){
      $date=$row_date['Not_Date'];
      //notifications for posts       POSTS
      if($row_date['Not_Type']=='new_post'){
        $query_post="SELECT p.Post_Name as pname  FROM notification as notif
                LEFT JOIN post as p on notif.Post_Id=p.Post_Id
                WHERE  notif.user_id='$user_id' and p.Post_Status=1 and  notif.Not_Type='new_post' and notif.Not_Status=1 and notif.Not_Date='$date'";
        $query_post_run=mysqli_query($connection,$query_post);
        $cnt=mysqli_num_rows($query_post_run);
        if($cnt==1){
          $row_p=mysqli_fetch_assoc($query_post_run);
          $post_name=$row_p['pname'];
          $notif_message='New Post: '.$post_name; 
          
        }
        elseif($cnt==2){
          $query_post="SELECT p.Post_Name as pname  FROM notification as notif
                  LEFT JOIN post as p on notif.Post_Id=p.Post_Id
                  WHERE  notif.user_id='$user_id' and p.Post_Status=1 and  notif.Not_Type='new_post' and notif.Not_Status=1 and notif.Not_Date='$date'";
          $query_post_run=mysqli_query($connection,$query_post);
          $post_name_arr=array();
          while($row_p=mysqli_fetch_assoc($query_post_run)){
            $post_name_arr[]=$row_p['pname'];
          }
          $post_name = implode(" and ", $post_name_arr);
          $notif_message='New Post: '.$post_name;
        }
        elseif($cnt>=3){
          $query_post="SELECT p.Post_Name as pname  FROM notification as notif
                  LEFT JOIN post as p on notif.Post_Id=p.Post_Id
                  WHERE  notif.user_id='$user_id' and p.Post_Status=1 and  notif.Not_Type='new_post' and notif.Not_Status=1 and notif.Not_Date='$date' LIMIT 2";
          $query_post_run=mysqli_query($connection,$query_post);
          $post_name_arr=array();
          while($row_p=mysqli_fetch_assoc($query_post_run)){
            $post_name_arr[]=$row_p['pname'];
          }
          $cnt=$cnt-2;
          $post_name = implode(", ", $post_name_arr);
          $notif_message='New Post: '.$post_name.' and ('.$cnt.') other posts for '.$post_type;
        }
        $format_date=date("M d, Y",strtotime($date));
        echo "<script>
            $(document).ready(function() {
              notif_dropdown.innerHTML += `<a class='dropdown-item d-flex align-items-center' href='#'>
              <div class='mr-3'>
                  <div class='icon-circle bg-warning'>
                      <i class='fas fa-bullhorn text-white'></i>
                  </div>
              </div>
              <div>
                  <!-- DATE -->
                  <div class='small text-gray-500'>$format_date</div>
                  <span class='small text-dark-500 font-weight-bold'> </span>
                  $notif_message
              </div>
          </a>`;
        })</script>";
        $post_name='';
      }
      //notification for approval     
      elseif($row_date['Not_Type']=='quote_approved'){
        //    APPROVAL post_name "Your quote for ['post_name'] was approved"
        $quote_id=$row_date['Quote_Id'];
        $quote_q ="SELECT q.Quote_Approval, p.Post_Name
          FROM quote as q
          LEFT JOIN post as p on p.Post_Id=q.Post_Id
          WHERE q.Quote_Status=1 AND q.Quote_Id='$quote_id' LIMIT 1";
        $quote_q_run =mysqli_query($connection,$quote_q);
        $row_q=mysqli_fetch_assoc($quote_q_run);
        $quote_app_stat=$row_q['Quote_Approval'];
        $post_name=$row_q['Post_Name'];
        $format_date=date("M d, Y",strtotime($date));
        if($quote_app_stat==0){ //rejected
          $notif_message='Your quote for '.$post_name.' was rejected' ;
          echo "<script>
            $(document).ready(function() {
              notif_dropdown.innerHTML += `<a class='dropdown-item d-flex align-items-center' href='#'>
              <div class='mr-3'>
                  <div class='icon-circle bg-warning'>
                      <i class='fas fa-check text-white'></i>
                  </div>
              </div>
              <div>
                  <!-- DATE -->
                  <div class='small text-gray-500'>$format_date</div>
                  <span class='small text-dark-500 font-weight-bold'> </span>
                  $notif_message
              </div>
          </a>`;
          })</script>";
        }
        // elseif($quote_app_stat==1){ //pending
        //   $notif_message='';
        // }
        elseif($quote_app_stat==1){ //approved
          $notif_message='Your quote for '.$post_name.' was approved' ;
          echo "<script>
            $(document).ready(function() {
              notif_dropdown.innerHTML += `<a class='dropdown-item d-flex align-items-center' href='#'>
              <div class='mr-3'>
                  <div class='icon-circle bg-success'>
                      <i class='fas fa-check text-white'></i>
                  </div>
              </div>
              <div>
                  <!-- DATE -->
                  <div class='small text-gray-500'>$format_date</div>
                  <span class='small text-dark-500 font-weight-bold'> </span>
                  $notif_message
              </div>
          </a>`;
          })</script>";
        }
        else{
          $notif_message='';
        }
      }
    }
  
  $total_not=mysqli_num_rows($q_notif_run);
  echo '<script>
  $(document).ready(function() {
      document.getElementById("not_no").textContent='.$total_not.';
      $("#not_no").removeClass("d-none");
  });
  </script>';
}
// INACTIVE NOTIFS -no new notif, display previous notif
else{
  $q_notif="SELECT COUNT(Notif_Id) as cnt, Not_Date, Not_Type, Quote_Id, Post_Id 
            FROM notification WHERE User_id='$user_id' AND Not_Status=0
            GROUP BY DAY(Not_Date),  Not_Type ORDER BY Not_Date ASC limit 5";
  $q_notif_run=mysqli_query($connection,$q_notif);
  if(mysqli_num_rows($q_notif_run)>0){
    while($row_date=mysqli_fetch_assoc($q_notif_run))
    {
      $date=$row_date['Not_Date'];
      $row_date['Not_Type']; 
      if($row_date['Not_Type']=='new_post'){
       $query_post="SELECT p.Post_Name as pname  FROM notification as notif
                    LEFT JOIN post as p on notif.Post_Id=p.Post_Id
                    WHERE notif.user_id='$user_id'  and notif.Not_Type='new_post' and notif.Not_Status=0 and notif.Not_Date='$date'";
        $query_post_run=mysqli_query($connection,$query_post);
        $cnt=mysqli_num_rows($query_post_run);
        if($cnt==1){
          $row_p=mysqli_fetch_assoc($query_post_run);
          $post_name=$row_p['pname']; 
          $notif_message='New Post: '.$post_name;
        }
        elseif($cnt==2){ $post_name_arr2=array();
          $query_post="SELECT p.Post_Name as pname  FROM notification as notif
                  LEFT JOIN post as p on notif.Post_Id=p.Post_Id
                  WHERE  notif.user_id='$user_id'  and notif.Not_Type='new_post' and notif.Not_Status=0 and notif.Not_Date='$date' and p.Post_Name!=null and p.Post_Status=1 GROUP BY p.Post_Id";
          $query_post_run=mysqli_query($connection,$query_post);
          while($row_p=mysqli_fetch_assoc($query_post_run)){
            $post_name_arr2[]=$row_p['pname'];
          }
          $post_name = implode(" and ", $post_name_arr2);
          $notif_message='New Post: '.$post_name;
        }
        elseif($cnt>=3){
          $query_post="SELECT p.Post_Name as pname  FROM notification as notif
                  LEFT JOIN post as p on notif.Post_Id=p.Post_Id
                  WHERE  notif.user_id='$user_id' and notif.Not_Type='new_post' and notif.Not_Status=0 and notif.Not_Date='$date' GROUP BY p.Post_Id LIMIT 2";
          $query_post_run=mysqli_query($connection,$query_post);
          while($row_p=mysqli_fetch_assoc($query_post_run)){
            $post_name_arr3[]=$row_p['pname'];
          }
          $cnt=$cnt-2;
          $post_name3 = implode(", ", $post_name_arr3);
          $notif_message='New Post: '.$post_name3.' and '.$cnt.' posts for '.$post_type;
        }
        $format_date=date("M d, Y",strtotime($date));
        echo "<script>
          $(document).ready(function() {
            notif_dropdown.innerHTML += `<a class='dropdown-item d-flex align-items-center' href='#'>
            <div class='mr-3'>
                <div class='icon-circle bg-warning'>
                    <i class='fas fa-bullhorn text-white'></i>
                </div>
            </div>
            <div>
                <!-- DATE -->
                <div class='small text-gray-500'>$format_date</div>
                <span class='small text-dark-500 font-weight-bold'> </span>
                $notif_message
            </div>
        </a>`;
        })</script>";
      }
      elseif($row_date['Not_Type']=='quote_approved'){ 
        //    APPROVAL post_name "Your quote for ['post_name'] was approved"
        $quote_id=$row_date['Quote_Id'];
        $quote_q ="SELECT q.Quote_Approval, p.Post_Name
                  FROM quote as q
                  LEFT JOIN post as p on p.Post_Id=q.Post_Id
                  WHERE q.Quote_Status=1 AND q.Quote_Approval=1 AND q.Quote_Id='$quote_id' LIMIT 1";
        $quote_q_run =mysqli_query($connection,$quote_q);
        if(mysqli_num_rows($quote_q_run)>0 ){
          $row_q=mysqli_fetch_assoc($quote_q_run);
          $quote_app_stat=$row_q['Quote_Approval'];
          $post_name=$row_q['Post_Name'];
        }
        else{
          $quote_app_stat='';
        }
        $format_date=date("M d, Y",strtotime($date));
        if($quote_app_stat==0){ //rejected
          $notif_message='Your quote for '.$post_name.' was rejected' ;
          echo "<script>
            $(document).ready(function() {
              notif_dropdown.innerHTML += `<a class='dropdown-item d-flex align-items-center' href='#'>
              <div class='mr-3'>
                  <div class='icon-circle bg-warning'>
                      <i class='fas fa-check text-white'></i>
                  </div>
              </div>
              <div>
                  <!-- DATE -->
                  <div class='small text-gray-500'>$format_date</div>
                  <span class='small text-dark-500 font-weight-bold'> </span>
                  $notif_message
              </div>
          </a>`;
          })</script>";
        }
        // elseif($quote_app_stat==1){ //pending
        //   $notif_message='';
        // }
        elseif($quote_app_stat==1){ //approved
          $notif_message='Your quote for '.$post_name.' was approved' ;
          echo "<script>
            $(document).ready(function() {
              notif_dropdown.innerHTML += `<a class='dropdown-item d-flex align-items-center' href='#'>
              <div class='mr-3'>
                  <div class='icon-circle bg-success'>
                      <i class='fas fa-check text-white'></i>
                  </div>
              </div>
              <div>
                  <!-- DATE -->
                  <div class='small text-gray-500'>$format_date</div>
                  <span class='small text-dark-500 font-weight-bold'> </span>
                  $notif_message
              </div>
          </a>`;
          })</script>";
        }
        else{
          $notif_message='';
        }
      }
    }
  }
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
  <a class="nav-link" href="prodServe.php">
    <i class="fas fa-fw fa-archive" ></i>
      <span>Products/Services</span></a>
</li>
<li class="nav-item">
  <a class="nav-link" href="post.php">
    <i class="fas fa-fw fa-comments" ></i>
      <span>Posts</span></a>
</li>
<li class="nav-item">
  <a class="nav-link" href="quotation.php">
    <i class="fas fa-fw fa-file-text"></i>
      <span>Quotation Status</span></a>
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
                <a class="dropdown-item" href="profile.php">
                  <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                  Profile
                </a>
                <a class="dropdown-item" href="change_pw.php">
                  <i class="fas fa-cogs fa-sm fa-fw mr-2 text-gray-400"></i>
                  Change Password
                </a>
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
            url: '../PM/ajax_purchase.php',
            method: 'POST',
            data:{'user_id':user_id},
            success:function(data){
                $('.selectpicker').selectpicker('refresh');
            }
        });
  });
});
document.title = "EMT Electromechanial Works";
</script>