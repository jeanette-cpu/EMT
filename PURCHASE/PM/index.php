<?php
include('../../security.php');
include('../../includes/header.php'); 
include('../../includes/purchase_navbar.php'); 
// error_reporting(0);
$username=$_SESSION['USERNAME'];
//   $query_user ="SELECT * FROM users WHERE USERNAME='$username'  LIMIT 1";
  $query_user ="SELECT * FROM users WHERE USERNAME='$username' AND USERTYPE='purchase' AND USER_STATUS=1 LIMIT 1";
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
?>
<div class="container-fluid">
    <!-- Page Heading -->
  <div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
  </div>
    <div class="row">
        <!--Total Companies-->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-s font-weight-bold text-primary text-uppercase mb-1">Total Companies</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    <?php 
                                        $query = "SELECT Comp_Id FROM company WHERE  Comp_Status=1 AND Comp_Approval!=0";
                                        $query_run = mysqli_query($connection, $query);
                                        $row1= mysqli_num_rows($query_run);
                                        echo'<h2>'.$row1.'</h2>';
                                    ?>
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-fw fa-building fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
        </div>
        <!-- Quotations Sent -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                        <div class="text-s font-weight-bold text-success text-uppercase mb-1">Approved</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            <?php 
                            $query = "SELECT Comp_Id FROM company WHERE Comp_Approval=1 AND Comp_Status=1";
                            $query_run = mysqli_query($connection, $query);
                            $row1= mysqli_num_rows($query_run);
                            echo'<h2>'.$row1.'</h2>';
                            ?>
                        </div>
                        </div>
                        <div class="col-auto">
                        <i class="fas fa-fw fa-check-square fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Registered Products-->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                        <div class="text-s font-weight-bold text-warning text-uppercase mb-1">Pending Approval</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            <?php 
                            $query = "SELECT Comp_Id FROM company WHERE Comp_Approval=2 AND Comp_Status=1";
                            $query_run = mysqli_query($connection, $query);
                            $row1= mysqli_num_rows($query_run);
                            echo'<h2>'.$row1.'</h2>';
                            ?>
                        </div>
                        </div>
                        <div class="col-auto">
                        <i class="fas fa-fw fa-pencil-square-o fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h5 class="m-0 font-weight-bold text-primary">Notifications <i class="fas fa-fw fa-bell ml-1 text-primary"></i></h5>
        </div>
        <div class="card-body">
            <table class="table table-sm">
        <?php
$q_notif="SELECT COUNT(Notif_Id) as cnt, Not_Date, Not_Type, Post_Id 
            FROM notification WHERE User_id='$user_id' 
            GROUP BY  DAY(Not_Date),Not_Type, Post_Id 
            ORDER BY Not_Date DESC LIMIT 6";
$q_notif_run=mysqli_query($connection,$q_notif); $tr_html='';
if(mysqli_num_rows($q_notif_run)>0){ 
    while($row=mysqli_fetch_assoc($q_notif_run)){
        // echo $cnt=$row['cnt'].' '.$not_type=$row['Not_Type'].'<br>'; 
        $cnt=$row['cnt']; 
        $post_id=$row['Post_Id'];
        $date=$row['Not_Date'];
        $not_type=$row['Not_Type'];
        // $comp_name='';
        if($not_type=='new_reg'){
            $q_comp="SELECT notif.Comp_Id, comp.Comp_Name
            FROM notification as notif
            LEFT JOIN company AS comp on comp.Comp_Id=notif.Comp_Id
            WHERE notif.Not_Date='$date' AND notif.Not_Type='new_reg' LIMIT 2";
            $q_comp_run=mysqli_query($connection,$q_comp);
            $row_comp=mysqli_fetch_assoc($q_comp_run);$comp_name='';
            if($cnt==1){
                $comp_name=$row_comp['Comp_Name'];
                $notif_message=$comp_name.' company new register';
            }
            elseif($cnt==2){
                $q_comp="SELECT notif.Comp_Id, comp.Comp_Name FROM notification as notif LEFT JOIN company AS comp on comp.Comp_Id=notif.Comp_Id WHERE notif.Not_Date='$date'  AND notif.Not_Type='new_reg' LIMIT 2";
                $q_comp_run=mysqli_query($connection,$q_comp);
                if(mysqli_num_rows($q_comp_run)>0){
                    $comp_name_2 = array();
                    while($row_comp_1=mysqli_fetch_assoc($q_comp_run)){
                        $comp_name_2[]=$row_comp_1['Comp_Name'];
                    }
                }
                // $row_comp=mysqli_fetch_assoc($q_comp_run);
                $comp_name = implode(" and ", $comp_name_2);
                $notif_message=$comp_name.' newly registered';
            }
            // elseif($cnt>=3){
            //     $q_comp="SELECT notif.Comp_Id, comp.Comp_Name FROM notification as notif LEFT JOIN company AS comp on comp.Comp_Id=notif.Comp_Id WHERE notif.Not_Date='$date'  AND notif.Not_Type='new_reg' LIMIT 2";
            //     $q_comp_run=mysqli_query($connection,$q_comp);
            //     $$comp_name_ = array();
            //     $cnt=$cnt-2; 
            //     while($row_co=mysqli_fetch_assoc($q_comp_run))
            //     {
            //         // $row_co['Comp_Name'];
            //         $comp_name_[]=$row_co['Comp_Name'];
            //     }
            //     $comp_name = implode(", ", $comp_name_);
            //     $notif_message=$comp_name.' and '.$cnt.' others new company registered';
            // } 
            $format_date=date("M d, Y",strtotime($date));
            $tr_html.='<tr>
                            <td><i class="fas fa-briefcase text-gray mr-2"></i>'.$notif_message.'<span class="float-right mr-4">'.$format_date.'</span>
                            </td>
                        </tr>';  
            $comp_name='';               
        }
        elseif($not_type=='new_quote' && $post_id){
            $q_details="SELECT notif.Comp_Id, comp.Comp_Name, p.Post_Name
                  FROM notification as notif
                  LEFT JOIN company AS comp on comp.Comp_Id=notif.Comp_Id
                  LEFT JOIN post as p on notif.Post_Id=p.Post_Id
                  WHERE notif.Not_Date='$date' AND notif.Not_Type='new_quote' AND p.Post_Id='$post_id' AND p.Post_Id IS NOT NULL GROUP BY comp.Comp_Id LIMIT 2";
            $q_details_run=mysqli_query($connection,$q_details);
            $row_details=mysqli_fetch_assoc($q_details_run);
            $post_name=$row_details['Post_Name'];
            $comp_name='';
            if($cnt==1){
                $comp_name=$row_details['Comp_Name'];
                $new_quote_m=$comp_name.' send a quote for '.$post_name;
            }
            elseif($cnt==2){
               $comp_name='';
               $q_comp2="SELECT DISTINCT notif.Comp_Id, comp.Comp_Name, p.Post_Name 
                        FROM notification as notif 
                        LEFT JOIN company AS comp on comp.Comp_Id=notif.Comp_Id 
                        LEFT JOIN post as p on notif.Post_Id=p.Post_Id 
                        WHERE notif.Not_Date='$date' AND notif.Not_Type='new_quote' 
                        AND p.Post_Id='$post_id' AND p.Post_Id IS NOT NULL 
                        GROUP BY comp.Comp_Id LIMIT 2";
                $q_comp_run2=mysqli_query($connection,$q_comp2);
                if(mysqli_num_rows($q_comp_run2)>0){ 
                    $comp_name_arr2 = array();
                    while($row_comp_2=mysqli_fetch_assoc($q_comp_run2)){
                        $comp_name_arr2[]=$row_comp_2['Comp_Name'];
                    }
                }
                $comp_name = implode(" and ", $comp_name_arr2);
                $new_quote_m=$comp_name.' send quote for '.$post_name;
            }
            elseif($cnt>=3){
                $q_comp3="SELECT notif.Comp_Id, comp.Comp_Name, p.Post_Name 
                FROM notification as notif 
                LEFT JOIN company AS comp on comp.Comp_Id=notif.Comp_Id 
                LEFT JOIN post as p on notif.Post_Id=p.Post_Id 
                WHERE notif.Not_Date='$date'  AND notif.Not_Type='new_quote' 
                AND p.Post_Id='$post_id' AND p.Post_Id IS NOT NULL 
                GROUP BY comp.Comp_Id LIMIT 2";
                $q_comp_run3=mysqli_query($connection,$q_comp3);$comp_name='';
                if(mysqli_num_rows($q_comp_run3)>0){
                    $comp_name_arr3 = array();
                    while($row_comp_3=mysqli_fetch_assoc($q_comp_run3)){
                        $comp_name_arr3[]=$row_comp_3['Comp_Name'];
                        $comp_name = implode(", ", $comp_name_arr3);
                    }
                }
                $cnt=$cnt-2;
                $new_quote_m=$comp_name.' and '.$cnt.' others send quote for '.$post_name;
            }
            $format_date=date("M d, Y",strtotime($date));
            $tr_html.= '<tr><td><i class="fas fa-file-text text-gray mr-2"></i>
            '.$new_quote_m.'<span class="float-right mr-4">'.$format_date.'</span></td>
            </tr>';  
        }
        $comp_name='';
    }
    echo $tr_html;
}

        ?>
            </table>
        </div>
    </div>
<?php
    $query_post="SELECT * FROM post as p 
                LEFT JOIN project as prj on prj.Prj_Id= p.Prj_Id
                WHERE p.Post_Status=1 AND prj.Prj_Status=1
                ORDER BY p.Post_Date  DESC LIMIT 5";
?>
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h5 class="m-0 font-weight-bold text-primary">Recent Posts <i class="fas fa-fw fa-comments ml-1 text-primary"></i></h5>
        </div>
        <div class="card-body">
            <table class="table table-sm">
            <?php $ctn=0;
                $query_post_run=mysqli_query($connection,$query_post);
                if(mysqli_num_rows($query_post_run)>0){
                    while($row_p=mysqli_fetch_assoc($query_post_run)){
                        $post_id=$row_p['Post_Id'];
                        $prj_name=$row_p['Prj_Name'];
                        $prj_code=$row_p['Prj_Code'];
                        $post_date=$row_p['Post_Date'];
                        $post_type=$row_p['Post_Type'];
                        if($post_type=='material'){
                            $class='postView';
                        }
                        else{
                            $class='mpPostView';
                        }
                        $post_date=date("M d, Y",strtotime($post_date));
                        $post_name=$row_p['Post_Name'];$ctn++;
                        echo '<tr><td>
                            <input type="hidden" name="post_id" id="p'.$ctn.'" value="'.$post_id.'"><a href="#" class="text-secondary '.$class.'">'.$prj_code.' '.$prj_name.' | '.$post_name.' | view more ... </a><div class="float-right mr-4"><span class="mr-4">'.$post_date.'</span></div>
                    </td></tr>';
                    }
                }
            ?>
            </table>
        </div>
    </div>
</div> 
<!-- POST MODAL -->
<div class="modal fade" id="postModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content ">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel"><i class="fas fa-fw fa-file mr-1"></i>Post Information</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div id="postDesc">
        </div>
      </div>
      <div class="modal-footer">
        <!-- <button id="quote" class="btn btn-sm btn-info float-right"><i class="fas fa-fw fa-paper-plane fa-1x mr-1"></i>Send Quote</button> -->
      </div>
    </div>
  </div>
</div> 
<script>
// view post info
$(document).ready(function () {
    $(document).on('click','.postView', function(){
        var post_id = $(this).prevAll('input').val();
        $('#postModal').modal('show');
        $.ajax({
                url:'ajax_purchase.php',
                method: 'POST',
                data:{'mpost_id':post_id
                },
                success:function(data){
                    $('#postDesc').html(data).change();
                }
            });
    });
});
$(document).ready(function () {
    $(document).on('click','.mpPostView', function(){
        var post_id = $(this).prevAll('input').val();
        $('#postModal').modal('show');
        $.ajax({
                url:'ajax_purchase.php',
                method: 'POST',
                data:{'ppost_id':post_id
                },
                success:function(data){
                    $('#postDesc').html(data).change();
                }
            });
    });
});
</script>
<?php
include('../../includes/scripts.php');
include('../../includes/footer.php');
?>