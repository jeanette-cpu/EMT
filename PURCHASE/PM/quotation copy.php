<?php
include('../../security.php');
include('../../includes/header.php'); 
include('../../includes/purchase_navbar.php'); $p_body='';
if(isset($_GET['post_id']))
{
    $post_id=$_GET['post_id']; // may question mark
    $p_details="SELECT * FROM post WHERE Post_Id='$post_id' LIMIT 1";
    $p_details_run=mysqli_query($connection,$p_details);
    $row_p=mysqli_fetch_assoc($p_details_run);
    $post_name=$row_p['Post_Name'];
    $desc=$row_p['Post_Desc'];
    $post_type=$row_p['Post_Type'];
    $date=$row_p['Post_Date'];
    $status=$row_p['Post_Status']; 
    //company applied
    $quotes="SELECT DISTINCT q.Quote_Id FROM quote as q 
            LEFT JOIN post as p on p.Post_Id=q.Post_Id 
            LEFT JOIN quote_detail as qd on qd.Quote_Id=q.Quote_Id 
            WHERE q.Quote_Status=1 AND p.Post_Id='$post_id' AND Quote_Detail_Id is not null";
    $quote_run=mysqli_query($connection,$quotes);
    $q_no=mysqli_num_rows($quote_run);
    if($q_no>0){
        $editClass='d-none';
    }
    else{
        $editClass='';
    }
    if($quote_applied=mysqli_num_rows($quote_run)>0){
        while($row_q = mysqli_fetch_assoc($quote_run)){
            $comp_arr[]=$row_q['Comp_Id'];
        }
         $comp_ids=implode(",",$comp_arr);
    }
    if($status=='1'){
        $stat='<button class="btn btn-sm btn-success disabled">Active</button>';$visibility="";}
    else{
        $stat='<button class="btn btn-sm btn-danger disabled">Closed</button>'; $visibility='d-none';}
    $type= $_GET['type'];
    if($type='job_post'){
        //get the companies applied in quotation
        //manpower
        $q_detail="SELECT * FROM manpower_post WHERE MP_Post_Status=1 AND Post_Id='$post_id'";
        $q_detail_run=mysqli_query($connection,$q_detail);
        if($post_type=='manpower'){
            $post_table_details='
            <table width="100%"  cellspacing="0">
                    <thead>
                        <th width="20%">Department</th>
                        <th>Description</th>
                        <th>Requested Quantity</th>
                    </thead><tbody>';
            $post_table_detail2='
            <table width="100%"  cellspacing="0">
                    <thead>
                        <th width="20%">Department</th>
                        <th width="50%">Description</th>
                        <th width="15%">Requested Quantity</th>
                        <th width="15%">Rate</th>
                    </thead><tbody>';
            if(mysqli_num_rows($q_detail_run)>0){ $qty_total=0;
                while($row_d=mysqli_fetch_assoc($q_detail_run)){
                    $dept=$row_d['Dept_Id'];
                    $dept_q="SELECT * FROM department where Dept_Id='$dept' and Dept_Status=1 limit 1 ";
                    $dept_q_run=mysqli_query($connection,$dept_q);
                    $row_dept=mysqli_fetch_assoc($dept_q_run);
                    $department=$row_dept['Dept_Name'];
                    $desc=$row_d['MP_Post_Desc'];
                    $qty=$row_d['MP_Post_Qty'];
                    $qty_total=$qty_total+$qty;
                    $post_table_details.='        
                    <tr>
                        <td>'.$department.'</td>
                        <td>'.$desc.'</td>
                        <td>'.$qty.'</td>
                    </tr>';
                }
                $post_table_details.='        
                <tr>
                    <td></td>
                    <td class="float-right mr-2">Total Qty:</td>
                    <td class="border-top font-weight-bold">'.$qty_total.'</td>
                </tr>';
            }
            $post_table_details.=' </tbody></table>  ';
        }
        if($post_type=='subcontractor'){
            $post_table_details='
            <table width="100%"  cellspacing="0">
                    <thead>
                        <th width="15%">Department</th>
                        <th width="45%">Description</th>
                        <th width="20%">Unit</th>
                        <th width="20%">Requested Quantity</th>
                    </thead>';
            $post_table_detail2='
            <table width="100%"  cellspacing="0">
                    <thead>
                        <th width="10%">Department</th>
                        <th width="45%">Description</th>
                        <th width="15%">Unit</th>
                        <th width="15%">Requested Quantity</th>
                        <th width="15%">Rate</th>
                    </thead>';
        if(mysqli_num_rows($q_detail_run)>0){
            while($row_d=mysqli_fetch_assoc($q_detail_run)){
                $dept=$row_d['Dept_Id'];
                $dept_q="SELECT * FROM department where Dept_Id='$dept' and Dept_Status=1 limit 1 ";
                $dept_q_run=mysqli_query($connection,$dept_q);
                $row_dept=mysqli_fetch_assoc($dept_q_run);
                $department=$row_dept['Dept_Name'];
                $desc=$row_d['MP_Post_Desc'];
                $qty=$row_d['MP_Post_Qty'];
                $unit=$row_d['MP_Post_Unit'];
                $post_table_details.='        
                        <tr>
                            <td>'.$department.'</td>
                            <td>'.$desc.'</td>
                            <td>'.$unit.'</td>
                            <td>'.$qty.'</td>';

                $post_table_details.='
                        </tr>
                    </table>  
                ';
                }
            }
        }
    }
    else{
        echo 'Error Loading Details';
    }
}
?>
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-1">
        <h4 class="text-primary-300 ">Quotations</h4>
    </div>
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <div class="form-row">
                <h5 class="text-primary col-11 mb-1">Post Details <i class="fa fa-edit ml-2 " aria-hidden="true"></i></h5>
                <p class="font-weight-normal col-1 <?php echo $editClass?>"><a href="edit_job_post.php?post_id=<?php echo $post_id?>">
                    <i class="fa fa-file-text mr-2" aria-hidden="true"></i>Edit Post 
                    <!-- <input type="submit"/> -->
                </p></a>
            </div>
            <table width="100%" cellspacing="0">
                <tr >
                    <td><span class="font-weight-bold mr-4">Post Name:</span><?php echo $post_name;?></td>
                    <td><span class="font-weight-bold mr-4">Companies Applied:</span><?php echo $quote_applied?></td>
                </tr>
                <tr>
                    <td><span class="font-weight-bold mr-3">Date Posted:</span><?php echo $date?></td>
                    <td><span class="mr-3 font-weight-bold">Quote Applications:</span>  <?php echo $q_no?></td>
                </tr>
                <tr>
                    <td ><span class="mr-3 font-weight-bold">Description:</span>  <?php echo $desc?></td>
                    <td> 
                        <span class="mr-3 font-weight-bold"> Status:</span>      <?php echo $stat?>
                        <div class="float-right <?php echo $visibility?>"> 
                            <button type="submit" class="btn btn-sm btn-warning" data-toggle="modal" data-target="#exampleModal">Deactivate Post</button>
                        </div>
                    </td>
                </tr>
            </table>
        </div>
        <div class="card-body">
            <?php echo $post_table_details;?>
        </div>
    </div>
    <!-- DISPLAY QUOTES -->
<?php
if($quote_applied>0)
{
    //APPROVED
    $quote_query="SELECT * from quote as q 
                LEFT JOIN company as comp on comp.Comp_Id=q.Comp_Id 
                LEFT JOIN quote_detail as qd on qd.Quote_Id=q.Quote_Id 
                WHERE comp.Comp_Status=1 AND q.Quote_Status=1 AND q.Post_Id='$post_id' 
                AND q.Quote_Approval=1  AND qd.Quote_Detail_Status=1 ";
    $quote_query_run=mysqli_query($connection,$quote_query);
    if(mysqli_num_rows($quote_query_run)>0)
    {
        while($row_c=mysqli_fetch_assoc($quote_query_run))
        {
            $comp_id=$row_c['Comp_Id'];
            $company_name=$row_c['Comp_Name'];
            $quote_id=$row_c['Quote_Id'];
            $app_stat=$row_c['Quote_Approval'];
            $date=$row_c['Quote_Submitted'];
            $message=$row_c['Quote_Message'];
            $tc=$row_c['Quote_T&C'];$border="";
            //BUTTONS 
            if($app_stat==0){//rejected
                $status='<button class="btn btn-sm btn-danger mb-1 disabled">Rejected</button>';
                $button_html='';
            }
            elseif($app_stat==1){//approved
                $status='<button class="btn btn-sm btn-success mb-1 disabled">Approved</button>';
                $button_html='';
                $border="border-success";
            }
            elseif($app_stat==2){// pending, /show approve button
                $status='<button class="btn btn-sm btn-warning mb-1 disabled">Pending</button>';
                $button_html='
                <div class="btn-group">
                    <form action="code.php" method="POST">
                        <input type="hidden" name="post_id" value='.$post_id.'>
                        <input type="hidden" name="type" value='.$type.'>
                        <input type="hidden" name="q_id" value='.$quote_id.'>
                        <button  name="reject" type="submit" class="border-0 btn btn-light">
                            <a href="#" class="btn btn-danger btn-icon-split">
                                <span class="icon text-white-50">
                                    <i class="fas fa-exclamation-triangle"></i>
                                </span>
                                <span class="text">Reject</span>
                            </a>
                        </button>
                    </form>
                    <form action="code.php" method="POST">
                        <input type="hidden" name="comp_id" value='.$comp_id.'>
                        <input type="hidden" name="post_id" value='.$post_id.'>
                        <input type="hidden" name="type" value='.$type.'>
                        <input type="hidden" name="q_id" value='.$quote_id.'>
                        <button name="approve_quote" type="submit" class="border-0 btn btn-light">
                            <a href="#" class="btn btn-success btn-icon-split">
                                <span class="icon text-white-50">
                                    <i class="fas fa-check"></i>
                                </span>
                                <span class="text">Approve</span>
                            </a>
                        </button>
                    </form>
                </div>';
            }
            else{
                $status='';
                $button_html=''; 
            }
            //quotaion details
            ?>
            <div class="card mb-4 <?php echo $border;?>">
                <div class="card-header">
                    <div class="form-row">
                        <div class="font-weight-bold mr-3">Company:</div> <?php echo $company_name?>
                    </div>
                    <div class="form-row">
                        <div class="col-12">
                            <span class="font-weight-bold mr-3">Date: </span><?php echo $date?><div class="float-right mr-2">Status: <?php echo $status;?></div><br>
                        </div>
                    </div>
                </div>
                <div class="card-body">
            <?php
                if($type='job_post')
                {
                    $q_detail="SELECT * FROM manpower_post WHERE MP_Post_Status=1 AND Post_Id='$post_id'";
                    $q_detail_run=mysqli_query($connection,$q_detail);
                    if($post_type=='manpower'){
                        if(mysqli_num_rows($q_detail_run)>0){
                            while($row_d=mysqli_fetch_assoc($q_detail_run)){
                                $dept=$row_d['Dept_Id']; 
                                $dept_q="SELECT * FROM department where Dept_Id='$dept' and Dept_Status=1 limit 1 ";
                                $dept_q_run=mysqli_query($connection,$dept_q);
                                $row_dept=mysqli_fetch_assoc($dept_q_run);
                                $department=$row_dept['Dept_Name'];
                                $desc=$row_d['MP_Post_Desc'];
                                $qty=$row_d['MP_Post_Qty'];
                                $mp_id=$row_d['MP_Post_Id'];
                                $q_rate="SELECT * FROM quote_detail WHERE Quote_Id='$quote_id' AND MP_Post_Id='$mp_id'";
                                $q_rate_run=mysqli_query($connection,$q_rate);
                                $row_r=mysqli_fetch_assoc($q_rate_run);
                                $rate=$row_r['Quote_Detail_Value'];
                                $p_body.='        
                                <tr>
                                    <td>'.$department.'</td>
                                    <td>'.$desc.'</td>
                                    <td>'.$qty.'</td>
                                    <td>'.$rate.'</td>
                                </tr>';
                            }
                        }
                    }
                    elseif($post_type=='subcontractor'){

                    }
                    echo $post_table_detail2;
                    echo $p_body.'</tbody></table>';
                    $p_body='';
                }
                ?>
                                <span class="font-weight-bold">Message:</span><br>
                                <?php echo $message?><br>
                                <span class="font-weight-bold">Terms & Conditions:</span><br>
                                <?php echo $tc?>
                                <div class="float-right">
                                        <?php echo $button_html?>
                                </div>
                            </div>
                        </div>
                        <?php
        }
    }
    //REJECTED & PENDING
    $quote_query="SELECT * from quote as q 
                LEFT JOIN company as comp on comp.Comp_Id=q.Comp_Id 
                LEFT JOIN quote_detail as qd on qd.Quote_Id=q.Quote_Id 
                WHERE comp.Comp_Status=1 AND q.Quote_Status=1
                AND q.Post_Id='$post_id' AND q.Quote_Approval!=1 AND Quote_Detail_Id is not null";
    $quote_query_run=mysqli_query($connection,$quote_query);
    if(mysqli_num_rows($quote_query_run)>0)
    {
        while($row_c=mysqli_fetch_assoc($quote_query_run))
        {
            $company_name=$row_c['Comp_Name'];
            $quote_id=$row_c['Quote_Id'];
            $app_stat=$row_c['Quote_Approval'];
            $date=$row_c['Quote_Submitted'];
            $message=$row_c['Quote_Message'];
            $tc=$row_c['Quote_T&C'];$border="";
            //BUTTONS 
            if($app_stat==0){//rejected
                $status='<button class="btn btn-sm btn-danger mb-1 disabled">Rejected</button>';
                $button_html='';
            }
            elseif($app_stat==1){//approved
                $status='<button class="btn btn-sm btn-success mb-1 disabled">Approved</button>';
                $button_html='';
                $border="border-success";
            }
            elseif($app_stat==2){// pending, /show approve button
                $status='<button class="btn btn-sm btn-warning mb-1 disabled">Pending</button>';
                $button_html='
                <div class="btn-group">
                    <form action="code.php" method="POST">
                        <input type="hidden" name="post_id" value='.$post_id.'>
                        <input type="hidden" name="type" value='.$type.'>
                        <input type="hidden" name="q_id" value='.$quote_id.'>
                        <button  name="reject" type="submit" class="border-0 btn btn-light">
                            <a href="#" class="btn btn-danger btn-icon-split">
                                <span class="icon text-white-50">
                                    <i class="fas fa-exclamation-triangle"></i>
                                </span>
                                <span class="text">Reject</span>
                            </a>
                        </button>
                    </form>
                    <form action="code.php" method="POST">
                        <input type="hidden" name="post_id" value='.$post_id.'>
                        <input type="hidden" name="type" value='.$type.'>
                        <input type="hidden" name="q_id" value='.$quote_id.'>
                        <button name="approve_quote" type="submit" class="border-0 btn btn-light">
                            <a href="#" class="btn btn-success btn-icon-split">
                                <span class="icon text-white-50">
                                    <i class="fas fa-check"></i>
                                </span>
                                <span class="text">Approve</span>
                            </a>
                        </button>
                    </form>
                </div>';
            }
            else{
                $status='';
                $button_html=''; 
            }
            //quotaion details
            ?>
            <div class="card mb-4 <?php echo $border;?>">
                <div class="card-header">
                    <div class="form-row">
                        <div class="font-weight-bold mr-3">Company:</div> <?php echo $company_name?>
                    </div>
                    <div class="form-row">
                        <div class="col-12">
                            <span class="font-weight-bold mr-3">Date: </span><?php echo $date?><div class="float-right mr-2">Status: <?php echo $status;?></div><br>
                        </div>
                    </div>
                </div>
                <div class="card-body">
            <?php
                if($type='job_post')
                {
                    $q_detail="SELECT * FROM manpower_post WHERE MP_Post_Status=1 AND Post_Id='$post_id'";
                    $q_detail_run=mysqli_query($connection,$q_detail);
                    if($post_type=='manpower'){
                        if(mysqli_num_rows($q_detail_run)>0){
                            while($row_d=mysqli_fetch_assoc($q_detail_run)){
                                $dept=$row_d['Dept_Id']; 
                                $dept_q="SELECT * FROM department where Dept_Id='$dept' and Dept_Status=1 limit 1 ";
                                $dept_q_run=mysqli_query($connection,$dept_q);
                                $row_dept=mysqli_fetch_assoc($dept_q_run);
                                $department=$row_dept['Dept_Name'];
                                $desc=$row_d['MP_Post_Desc'];
                                $qty=$row_d['MP_Post_Qty'];
                                $mp_id=$row_d['MP_Post_Id'];
                                $q_rate="SELECT * FROM quote_detail WHERE Quote_Id='$quote_id' AND MP_Post_Id='$mp_id'";
                                $q_rate_run=mysqli_query($connection,$q_rate);
                                $row_r=mysqli_fetch_assoc($q_rate_run);
                                $rate=$row_r['Quote_Detail_Value'];
                                $p_body.='        
                                <tr>
                                    <td>'.$department.'</td>
                                    <td>'.$desc.'</td>
                                    <td>'.$qty.'</td>
                                    <td>'.$rate.'</td>
                                </tr>';
                            }
                        }
                    }
                    echo $post_table_detail2;
                    echo $p_body.'</tbody></table>';
                    $p_body='';
                }
                ?>
                                <span class="font-weight-bold">Message:</span><br>
                                <?php echo $message?><br>
                                <span class="font-weight-bold">Terms & Conditions:</span><br>
                                <?php echo $tc?>
                                <div class="float-right">
                                        <?php echo $button_html?>
                                </div>
                            </div>
                        </div>
                        <?php
        }
    }

}
?>
</div>
<!-- MODAL DEACTIVATE POST -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Deactivate Confirmation</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        Are you sure to Deactivate this post?
      </div>
      <div class="modal-footer">
        <form action="code.php" method="post">
            <input type="hidden" name="post_id" value="<?php echo $post_id?>">
            <input type="hidden" name="type" value="<?php echo $type?>">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
            <button type="submit" name="post_deact"class="btn btn-primary">Confirm</button>
        </form>
      </div>
    </div>
  </div>
</div>
<?php
include('../../includes/scripts.php');
include('../../includes/footer.php');
?>
