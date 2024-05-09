<?php
include('../../dbconfig.php');
if(isset($_POST['query'])){
    $query=$_POST['query'];
    // $query_run=mysqli_query($connection,$query);
    $table='';
    $table.='
    <div class="table-responsive">
            <table class="table table-bordered table-striped" id="dataTable" width="100%" cellspacing="0">
                <thead>
                    <th class="d-none"></th>
                    <th >Company Name</th>
                    <th>Company Profile</th>
                    <th >Type</th>
                    <th >TRN</th>
                    <th >Trade License</th>
                    <th >Contact Details</th>
                    <th >Product/Service <span class="invisible">ccccccccccccccccccccccccccccccccccccccccccccccccccccccccccccccccccccccccccccccccccccccc</span></th>
                    <th >Date Registered</th>
                    <th >Actions</th>
                </thead>
                <tbody> ';
                $c=1;
                $comp_run=mysqli_query($connection,$query); 
                // echo $query;
                if(mysqli_num_rows($comp_run)>0)
                {
                    // while($row_c= mysqli_fetch_array($query_run))
                    while($row_c =mysqli_fetch_assoc($comp_run))
                    { 
                        $company_name=$row_c['Comp_Name'];
                        $cname=$row_c['Comp_Contact_Person'];
                        $cpos=$row_c['Comp_Contact_Position'];
                        $cmob=$row_c['Comp_Contact_Mobile'];
                        $cland=$row_c['Comp_Contact_Landline'];
                        $cemail=$row_c['Comp_Contact_Email'];
                        $mname=$row_c['Comp_Manager_Name'];
                        $mmob=$row_c['Comp_Manager_Mobile'];
                        $mland=$row_c['Comp_Manager_Landline'];
                        $memail=$row_c['Comp_Manager_Email'];
                        $website=$row_c['Company_Website'];
                        $comp_id=$row_c['Comp_Id'];
                        $cType=$row_c['Comp_Type'];
                        $emTRL=$row_c['Comp_Emirate_TRL'];
                        if($cType=='laborSupply' || $cType=='subcon'){
                            $query="SELECT * FROM service where Comp_Id='$comp_id'";
                        }
                        else{
                            $query="SELECT * FROM product where Comp_Id='$comp_id'";
                        }
                        $query_run=mysqli_query($connection,$query);
                        $filename=$row_c['Comp_TRN'];
                        //search if theres uploaded
                        $comp_prof="../../uploads/cProf".$comp_id.".pdf";
                        $trn_file="../../uploads/TRN".$comp_id.".pdf";
                        $table .='<tr>
                        <td class="d-none">'.$comp_id.'</td>
                            
                            <td> <input type="hidden" value="'.$comp_id.'"><a href="#" class="cname">'.$company_name.'</a><br>
                                Website:'.$website.'
                            </td>
                            <td>';
                            
                            if (file_exists($comp_prof)) {
                                $table.= '<a href="#" class="btn btn-info btn-icon-split viewProf">
                                            <span class="icon text-white">
                                                <i class="fas fa-file-text"></i>
                                            </span>
                                            <span class="text">View</span>
                                        </a>
                                        <input type="hidden" value="'.$comp_id.'">';
                            } else {
                                $table.= "no file";
                            }
                        $table.='</td>
                            <td>';
                            $type=$row_c['Comp_Type'];
                            if($type=='oem'){
                                $type='Manufacturer/OEM';
                            }
                            elseif($type=='agency'){
                                $type='Recruitment Agency';
                            }
                            elseif($type=='distributor' or $type=='trading'){
                                $type =ucfirst($type);
                            }
                            elseif($type=='subcon'){
                                $type='Subcontractor';
                            }
                            elseif($type=='laborSupply'){
                                $type='Labor Supply';
                            }
                        $table .=''.$type.'
                            </td>
                            <td>'.$filename.'</td>
                            <td>
                            Licensed: '.$emTRL.'
                            <br>';
                        if (file_exists($trn_file)) {
                        $table.= '<a href="#" class="btn btn-info btn-icon-split view">
                                    <span class="icon text-white">
                                        <i class="fas fa-file-text"></i>
                                    </span>
                                    <span class="text">View</span>
                                </a>
                                <input id="fl'.$c.'" type="hidden" value="'.$comp_id.'">';
                        } else {
                            $table.= "";
                        }
                        $table.='
                            </td>
                            <td>
                                <table class="table table-sm">
                                    <tr class="padding-0">
                                        <td>Name:</td>
                                        <td>'.$cname.'</td>
                                        <td>Manager:</td>
                                        <td>'.$mname.'</td>
                                    </tr>
                                    <tr>
                                        <td>Position</td>
                                        <td>'.$cpos.'</td>
                                        <td>Email:</td>
                                        <td>'.$memail.'</td>
                                    </tr>
                                    <tr>
                                        <td>Contact No:</td>
                                        <td>'.$cmob.'</td>
                                        <td>Contact No:</td>
                                        <td>'.$mmob.'</td>
                                    </tr>
                                    <tr>
                                        <td>Landline:</td>
                                        <td>'.$cland.'</td>
                                        <td>Landline:</td>
                                        <td>'.$mland.'</td>
                                    </tr>
                                    <tr>
                                        <td>Email:</td>
                                        <td>'.$cemail.'</td>
                                    </tr>
                                </table>
                            <!-- </div> -->
                        </td>
                        <!-- PRODUCT/SERVICES -->
                        <td >
                        <div class="table-responsive" >
                            <table class="table table-sm table-bordered" id="ps'.$c.'" width="100%" cellspacing="0">
                        ';
                        if($row_c['Comp_Type']=='oem'||$row_c['Comp_Type']=='trading'||$row_c['Comp_Type']=='distributor')
                        {
                            $query_p="SELECT * FROM product where Prod_Status=1 and Comp_Id='$comp_id'";
                            $query_p_run=mysqli_query($connection,$query_p);
                            if(mysqli_num_rows($query_p_run)>0)
                            {
                                $table .='
                                <thead><th>Product Desc.</th><th>Brand</th><th>Country</th></thead>
                                <tbody>
                                ';
                                
                                while($row_p=mysqli_fetch_assoc($query_p_run))
                                {
                                  $prod_id=$row_p['Prod_Desc'];
                                  $q_mat="SELECT * FROM material WHERE Mat_Id='$prod_id' LIMIT 1";
                                  $q_mat_run=mysqli_query($connection,$q_mat);
                                  if(mysqli_num_rows($q_mat_run)>0){
                                    $row_m=mysqli_fetch_assoc($q_mat_run);
                                    $desc=$row_m['Mat_Code'].' '.$row_m['Mat_Desc'];
                                  }
                                  else{
                                      $desc=$prod_id;
                                  }
                                    $table .='
                                    <tr>
                                        <td>'.$desc.'</td>
                                        <td>'.$row_p['Prod_Brand'].'</td>
                                        <td>'.$row_p['Prod_Country'].'</td>
                                    </tr>';
                                }
                            }
                        }
                        else{
                            $query_s="SELECT * FROM `service` where Serve_Status=1 and Comp_Id='$comp_id'";
                            $query_s_run=mysqli_query($connection,$query_s);
                            if(mysqli_num_rows($query_s_run)>0)
                            {
                                $table .='
                                <thead><th>Service Desc.</th><th>Unit</th><th>Rate</th></thead>
                                ';
                                while($row_s=mysqli_fetch_assoc($query_s_run))
                                {
                                    $table .='
                                    <tr>
                                        <td>'.$row_s['Serve_Desc'].'</td>
                                        <td>'.$row_s['Serve_Unit'].'</td>
                                        <td>'.$row_s['Serve_Rate'].'</td>
                                    </tr> ';
                                }
                            }
                        }
                        $table.='
                                    </tbody>
                                </table>
                            </div>
                        </td>
                        <td>'.$row_c['Comp_Reg_Date'].'</td>
                        <td class="btn-group">
                            <button type="button" class="btn btn-success approveBtn">
                                <i class="fa fa-check" aria-hidden="true"></i>
                            </button>
                            <button type="button" class="btn btn-danger delBtn">
                                <i class="fa fa-times" aria-hidden="true"></i>
                            </button>
                        </td>
                    </tr>';
                        ?>
                        <script>
                            // $(document).ready(function() {
                            //     var tbl_id=< ? php echo $c?>;
                            //     $(document).find('#ps'+tbl_id).DataTable({
                            //     pageLength: 10,
                            //     "searching": true,
                            //     });
                            // });
                        </script>
                    <?php $c++;
                    }
                }
            $table .='
                    </tbody>
                    </table>
                </div>
            ';
echo $table;
}
if(isset($_POST['query_a'])){
    $query=$_POST['query_a'];
    $table='';
    $table.='
    <div class="table-responsive">
            <table class="table table-bordered table-striped" id="dataTable" width="100%" cellspacing="0">
                <thead>
                    <th class="d-none"></th>
                    <th >Company Name</th>
                    <th>Company Profile</th>
                    <th >Type</th>
                    <th >TRN</th>
                    <th >Trade License</th>
                    <th >Contact Details</th>
                    <th >Product/Service <span class="invisible">ccccccccccccccccccccccccccccccccccccccccccccccccccccccccccccccccccccccccccccccccccccccc</span></th>
                    <th >Date Registered</th>
                    <th >Actions</th>
                </thead>
                <tbody> ';
                $c=1;
                $comp_run=mysqli_query($connection,$query); 
                // echo $query;
                if(mysqli_num_rows($comp_run)>0)
                {
                    // while($row_c= mysqli_fetch_array($query_run))
                    while($row_c =mysqli_fetch_assoc($comp_run))
                    { 
                        $company_name=$row_c['Comp_Name'];
                        $cname=$row_c['Comp_Contact_Person'];
                        $cpos=$row_c['Comp_Contact_Position'];
                        $cmob=$row_c['Comp_Contact_Mobile'];
                        $cland=$row_c['Comp_Contact_Landline'];
                        $cemail=$row_c['Comp_Contact_Email'];
                        $mname=$row_c['Comp_Manager_Name'];
                        $mmob=$row_c['Comp_Manager_Mobile'];
                        $mland=$row_c['Comp_Manager_Landline'];
                        $memail=$row_c['Comp_Manager_Email'];
                        $website=$row_c['Company_Website'];
                        $comp_id=$row_c['Comp_Id'];
                        $cType=$row_c['Comp_Type'];
                        $emTRL=$row_c['Comp_Emirate_TRL'];
                        if($cType=='laborSupply' || $cType=='subcon' || $cType=='agency'){
                            $query="SELECT * FROM service where Comp_Id='$comp_id'";
                        }
                        else{
                            $query="SELECT * FROM product where Comp_Id='$comp_id'";
                        }
                        $query_run=mysqli_query($connection,$query);
                        $filename=$row_c['Comp_TRN'];
                        $comp_prof="../../uploads/cProf".$comp_id.".pdf";//search if theres uploaded
                        $trn_file="../../uploads/TRN".$comp_id.".pdf";
                        
                        $table .='<tr>
                            <td class="d-none">'.$comp_id.'</td>
                            <td><input type="hidden" value="'.$comp_id.'"><a href="#" class="cname">'.$company_name.'</a><br>
                                Website:'.$website.'
                            </td>
                            <td>';
                            if (file_exists($comp_prof)) {
                                $table.= '<a href="#" class="btn btn-info btn-icon-split viewProf">
                                            <span class="icon text-white">
                                                <i class="fas fa-file-text"></i>
                                            </span>
                                            <span class="text">View</span>
                                        </a>
                                        <input type="hidden" value="'.$comp_id.'">';
                            } else {
                                $table.= "no file";
                            }
                        $table.= 
                            '</td>
                            <td>';
                            $type=$row_c['Comp_Type'];
                            if($type=='oem'){
                                $type='Manufacturer/OEM';
                            }
                            elseif($type=='agency'){
                                $type='Recruitment Agency';
                            }
                            elseif($type=='distributor' or $type=='trading'){
                                $type =ucfirst($type);
                            }
                            elseif($type=='subcon'){
                                $type='Subcontractor';
                            }
                            elseif($type=='laborSupply'){
                                $type='Labor Supply';
                            }
                        $table .=''.$type.'
                            </td>
                            <td>'.$filename.'
                            </td>
                            <td>
                            Licensed: '.$emTRL.'
                            <br>';
                        if (file_exists($trn_file)) {
                            $table.= '<a href="#" class="btn btn-info btn-icon-split view">
                                        <span class="icon text-white">
                                            <i class="fas fa-file-text"></i>
                                        </span>
                                        <span class="text">View</span>
                                    </a>
                                    <input id="fl'.$c.'" type="hidden" value="'.$comp_id.'">';
                            } else {
                                $table.= "";
                            }
                        $table.='</td>
                            <td>
                                <table class="table table-sm">
                                    <tr class="padding-0">
                                        <td>Name:</td>
                                        <td>'.$cname.'</td>
                                        <td>Manager:</td>
                                        <td>'.$mname.'</td>
                                    </tr>
                                    <tr>
                                        <td>Position</td>
                                        <td>'.$cpos.'</td>
                                        <td>Email:</td>
                                        <td>'.$memail.'</td>
                                    </tr>
                                    <tr>
                                        <td>Contact No:</td>
                                        <td>'.$cmob.'</td>
                                        <td>Contact No:</td>
                                        <td>'.$mmob.'</td>
                                    </tr>
                                    <tr>
                                        <td>Landline:</td>
                                        <td>'.$cland.'</td>
                                        <td>Landline:</td>
                                        <td>'.$mland.'</td>
                                    </tr>
                                    <tr>
                                        <td>Email:</td>
                                        <td>'.$cemail.'</td>
                                    </tr>
                                </table>
                            <!-- </div> -->
                        </td>
                        <!-- PRODUCT/SERVICES -->
                        <td >
                        <div class="table-responsive" >
                            <table class="table table-sm table-bordered" id="ps'.$c.'" width="100%" cellspacing="0">
                        ';
                        if($row_c['Comp_Type']=='oem'||$row_c['Comp_Type']=='trading'||$row_c['Comp_Type']=='distributor')
                        {
                            $query_p="SELECT * FROM product where Prod_Status=1 and Comp_Id='$comp_id'";
                            $query_p_run=mysqli_query($connection,$query_p);
                            if(mysqli_num_rows($query_p_run)>0)
                            {
                                $table .='
                                <thead><th>Product Desc.</th><th>Brand</th><th>Country</th></thead>
                                <tbody>
                                ';
                                while($row_p=mysqli_fetch_assoc($query_p_run))
                                {
                                    $prod_id=$row_p['Prod_Desc'];
                                    $q_mat="SELECT * FROM material WHERE Mat_Id='$prod_id' LIMIT 1";
                                    $q_mat_run=mysqli_query($connection,$q_mat);
                                    if(mysqli_num_rows($q_mat_run)>0){
                                    $row_m=mysqli_fetch_assoc($q_mat_run);
                                    $desc=$row_m['Mat_Code'].' '.$row_m['Mat_Desc'];
                                    }
                                    else{
                                        $desc=$prod_id;
                                    }
                                    $table .='
                                    <tr>
                                        <td>'.$desc.'</td>
                                        <td>'.$row_p['Prod_Brand'].'</td>
                                        <td>'.$row_p['Prod_Country'].'</td>
                                    </tr>';
                                }
                            }
                        }
                        else{
                            $query_s="SELECT * FROM `service` where Serve_Status=1 and Comp_Id='$comp_id'";
                            $query_s_run=mysqli_query($connection,$query_s);
                            if(mysqli_num_rows($query_s_run)>0)
                            {
                                $table .='
                                <thead><th>Service Desc.</th><th>Unit</th><th>Rate</th></thead>
                                ';
                                while($row_s=mysqli_fetch_assoc($query_s_run))
                                {
                                    $table .='
                                    <tr>
                                        <td>'.$row_s['Serve_Desc'].'</td>
                                        <td>'.$row_s['Serve_Unit'].'</td>
                                        <td>'.$row_s['Serve_Rate'].'</td>
                                    </tr> ';
                                }
                            }
                        }
                        $table.='
                                    </tbody>
                                </table>
                            </div>
                        </td>
                        <td>'.$row_c['Comp_Reg_Date'].'</td>
                        <td class="btn-group">
                            <button type="button" class="btn btn-danger delBtn">
                                <i class="fa fa-times" aria-hidden="true"></i>
                            </button>
                        </td>
                    </tr>';
                        ?>
                        <script>
                            $(document).ready(function() {
                                var tbl_id=<?php echo $c?>;
                                $(document).find('#ps'+tbl_id).DataTable({
                                pageLength: 10,
                                "searching": true,
                                });
                            });
                        </script>
                    <?php $c++;
                    }
                }
            $table .='
                    </tbody>
                    </table>
                </div>';
    echo $table;
}
if(isset($_POST['approve'])){
    $comp_id=$_POST['comp_id'];
    $sql="UPDATE company SET Comp_Approval=1 WHERE Comp_Id='$comp_id'";
    // echo $sql;
    $sql_run=mysqli_query($connection,$sql);
    if($sql_run){
        $q_user="SELECT USER_ID, USERNAME  FROM company where Comp_Id='$comp_id'";
        $q_user_run=mysqli_query($connection,$q_user);
        if(mysqli_num_rows($q_user_run)>0){
            $row_u=mysqli_fetch_assoc($q_user_run);
            $username=$row_u['USERNAME'];
            $user_id = $row_u['User_Id'];
        }
        $q_update="UPDATE users SET USER_STATUS=1 WHERE USER_ID='$user_id'";
        $q_update_run=mysqli_query($connection,$q_update);
        if($q_update_run){
            //SEND email to company
            $q_comp_details="SELECT Comp_Name, Comp_Contact_Email, Comp_Manager_Email 
                            FROM company WHERE Comp_Id='$comp_id' LIMIT 1";
            $q_comp_details_run=mysqli_query($connection,$q_comp_details);
            if(mysqli_num_rows($q_comp_details_run)>0){
                $row_c=mysqli_fetch_assoc($q_comp_details_run);
                $company_name=$row_c['Comp_Name'];
                $cMail=$row_c['Comp_Contact_Email'];
                $mMail=$row_c['Comp_Manager_Email'];
                $subject="Company Profile Application Approved : EMT Online Registration";
                $body="<b>Hello $username,</b><br><br>

                $company_name Profile was approved!<br><br>
                
                You can now login with your enrolled username and password. Login through this link:https://emtdubai.ae/EMT/login.php <br><br>
                
                Sincerely,<br>
                EMT Registration System<br><br><br><br>
                
                
                This is a system-generated email. Please do not reply.";
                if($cMail==$mMail){ // check if same email
                    $to=$mMail;
                    sendmail($to,$subject,$body);
                }
                else{
                    if($cMail){
                        $to=$cMail;
                        sendmail($to,$subject,$body);
                    }
                    if($mMail){
                        $to=$mMail;
                        sendmail($to,$subject,$body);
                    }
                }
            }
            // success
            $_SESSION['status'] = "Company Approved";
            $_SESSION['status_code'] = "success";
            header('Location: a_comp_approval.php');
        }
        else{
            //error
            $_SESSION['status'] = "Error Approving Company";
            $_SESSION['status_code'] = "error";
            header('Location: a_comp_approval.php');
        }
    }
    else{
        //error
        $_SESSION['status'] = "Error Approving Company";
        $_SESSION['status_code'] = "error";
        header('Location: a_comp_approval.php');
    }
}
if(isset($_POST['remove'])){
    $comp_id=$_POST['comp_id'];
    $sql="UPDATE company SET Comp_Status=0 WHERE Comp_Id='$comp_id'";
    // echo $sql;
    $sql_run=mysqli_query($connection,$sql);
    if($sql_run){
        // success
        $_SESSION['status'] = "Company Removed";
        $_SESSION['status_code'] = "success";
        header('Location: a_comp_approval.php');
    }
    else{
        //error
        $_SESSION['status'] = "Error Removing Company";
        $_SESSION['status_code'] = "error";
        header('Location: a_comp_approval.php');
    }
}
if(isset($_POST['dept_id']))
{
    $dept_id=$_POST['dept_id'];
    $q_products="SELECT * FROM material where Dept_Id='$dept_id' and Mat_Status='1'";
    // echo $dept_id;
    $q_products_run=mysqli_query($connection,$q_products);
    $options='';
    $options.='
    <option></option>';

    if(mysqli_num_rows($q_products_run)>0){
        while($row_p=mysqli_fetch_assoc($q_products_run)){
            $options.='<option value='.$row_p['Mat_Id'].'>'.$row_p['Mat_Code'].' '.$row_p['Mat_Desc'].'</option>';
        }
    }
    echo $options;
}
if(isset($_POST['filename'])){
    $filename= $_POST['filename'];
    $backslash='\ ';
    $backslash= str_replace(' ', '', $backslash);
    $first='<iframe src=\'..\..\uploads';
    $end='\' width=\'100%\' style=\'height:100%\'></iframe>';
    $file=$first.$backslash.$filename.$end;
    echo $file;
}
if(isset($_POST['user_id'])){
    $user_id=$_POST['user_id'];
    $query="SELECT * FROM notification WHERE User_Id='$user_id' AND Not_Status=1";
    $query_run=mysqli_query($connection,$query);

    if(mysqli_num_rows($query_run)>0){
        while($row=mysqli_fetch_assoc($query_run))
        {
            $not_id=$row['Notif_Id'];
            $q_update="UPDATE notification SET Not_Status=0 WHERE Notif_Id='$not_id'";
            $q_update_run=mysqli_query($connection,$q_update);
        }
    }
    else{}
}
if(isset($_POST['post_id'])){//SHOW post details by notif
 $post_id=$_POST['post_id'];
 $comp_id=$_POST['comp_id'];
 $q_comp="SELECT Comp_Type FROM company WHERE Comp_Id='$comp_id'";
 $q_comp_run=mysqli_query($connection,$q_comp);
 $row_c=mysqli_fetch_assoc($q_comp_run);
 $comp_type=$row_c['Comp_Type'];
 $p_details="SELECT * FROM post as p
            LEFT JOIN project as prj on prj.Prj_Id=p.Prj_Id
            WHERE  p.Post_Id='$post_id' LIMIT 1";
 $p_details_run=mysqli_query($connection,$p_details);
 if($p_details_run){
    $row_p=mysqli_fetch_assoc($p_details_run);
    //post details
    $post_name=$row_p['Post_Name'];
    $prj_name=$row_p['Prj_Name'];
    $prj_code=$row_p['Prj_Code'];
    $post_date=$row_p['Post_Date'];
    $post_desc=$row_p['Post_Desc'];
    $post_date=date("M d, Y",strtotime($post_date));
    $post_type=$row_p['Post_Type'];
    if($post_type=='material'){
        $q_details="SELECT * FROM material_post WHERE Post_Id='$post_id' AND Mat_Post_Status=1";
        $q_details_run=mysqli_query($connection,$q_details);
        $th_html='<th class="d-none">Department</th>
                    <th width="55%">Material</th>
                    <th width="5%">Unit</th>
                    <th width="10%">Qty Needed</th>
                    <th width="15%">Preffered Brand</th>
                    <th width="15%" class="d-none inviTD">Rate</th>
                    <th class="d-none">Id</th>';
    }
    if($post_type=='manpower'){
        $q_details="SELECT * FROM manpower_post WHERE Post_Id='$post_id' AND MP_Post_Status=1";
        $q_details_run=mysqli_query($connection,$q_details);
        if($comp_type=='subcon'){
            $th_html='<th class="d-none">Department</th>
                        <th width="55%">Service Desc</th>
                        <th width="15%">Unit</th>
                        <th width="15%">Total Area No.</th>
                        <th width="15%" class="d-none inviTD">Rate per Unit</th>
                        <th class="d-none">Id</th>';
        }
        elseif($comp_type=='laborSupply' || $comp_type=='agency'){
            $th_html='<th width="15%">Department</th>
                        <th  width="55%">Description</th>
                        <th  width="15%">Qty</th>
                        <th width="15%" class="d-none inviTD">Rate</th>
                        <th class="d-none">Id</th>';
        }
    }
    $p_desc='
        <div class="form-row mb-3">
            <div class="col-9">
                <h5 class="m-0 font-weight-bold text-primary">'.$post_name.'</h5>
            </div>
            <div class="col-3">'.$post_date.'</div>
        </div>
        <div class="form-row">
            <div class="col-2">
                <label class="font-weight-bold mr-1">Project:</label><br>
            </div>
            <div class="col-10">
                <span>'.$prj_code.' '.$prj_name.'</span>
            </div>
        </div>
        <div class="form-row">
            <div class="col-2">
                <label class="font-weight-bold mr-1">Description:</label><br>
            </div>
            <div class="col-10">
                <span>'.$post_desc.'</span>
            </div>
        </div>
        <div class="table-responsive" id="tbl">
            <table class="table table-sm table-bordered mt-3 dtable "  width="100%" cellspacing="0" >
                <thead> '.$th_html.' </thead>
                <tbody>';
    if(mysqli_num_rows($q_details_run)>0){
        while($row_d=mysqli_fetch_assoc($q_details_run)){
            if($post_type=='manpower' && $comp_type=='subcon'){
                $td1=$row_d['MP_Post_Desc'];//description
                $td2=$row_d['MP_Post_Unit'];//unit
                $td3=$row_d['MP_Post_Qty'];//area
                $td4=$row_d['MP_Post_Id'];
                $unit=$td2;
            }
            if($post_type=='manpower' && $comp_type=='laborSupply'){
                $dept_id=$row_d['Dept_Id'];//department
                $td2=$row_d['MP_Post_Desc'];//desc    
                $td3=$row_d['MP_Post_Qty'];//qty person need
                $td4=$row_d['MP_Post_Id'];
                $dept_q="SELECT * FROM department WHERE Dept_Status=1 and Dept_Id='$dept_id'";
                $dept_q_run=mysqli_query($connection,$dept_q);
                $row_dept=mysqli_fetch_assoc($dept_q_run);
                $td1=$row_dept['Dept_Name'];
                $unit='hour';
            }
            if($post_type=='material'){
                $mat_id=$row_d['Mat_Id'];//material name
                $td2=$row_d['Mat_Post_Qty'];//qty
                $td3=$row_d['Mat_Post_Brand'];//preffered brand
                $td4=$row_d['Mat_Post_Id'];//

                $mat_q="SELECT * FROM material WHERE Mat_Status=1 AND Mat_Id='$mat_id'";
                $mat_q_run=mysqli_query($connection,$mat_q);
                $row_m=mysqli_fetch_assoc($mat_q_run);
                $mat_unit=$row_m['Mat_Unit'];
                $td1=$row_m['Mat_Desc'];
            }
            if($post_type=='material'){
                $p_desc.='
                <tr>
                    <td>'.$td1.'</td>
                    <td>'.$mat_unit.'</td>
                    <td>'.$td2.'</td>
                    <td>'.$td3.'</td>
                    <td class="d-none inviTD">
                        <div class="form-row">
                            <div class="col-8">
                                <input type="number" name="q_qty[]" class="form-control" required>
                            </div>
                            <div class="col-4">
                            '.'/'.$mat_unit.'
                            </div>
                        </div>
                    </td>
                    <td class="d-none"><input type="text" name="post_desc_id[]" value="'.$td4.'"></td>
                </tr> ';
            }
            else{
                $p_desc.='
                <tr>
                    <td>'.$td1.'</td>
                    <td>'.$td2.'</td>
                    <td>'.$td3.'</td>
                    <td class="d-none inviTD">
                        <div class="form-row">
                            <div class="col-8">
                                <input type="number" name="q_qty[]" class="form-control" required>
                            </div>
                            <div class="col-4">
                                /'.$unit.'
                            </div>
                        </div>
                    </td>
                    <td class="d-none"><input type="text" name="post_desc_id[]" value="'.$td4.'"></td>
                </tr> '; 
            }
    }
    $p_desc.='
                </tbody>
            </table>
        </div> ';
    }
    echo $p_desc;
    //Check if company send quote
    $quote_chk="SELECT * FROM quote WHERE Post_Id='$post_id' AND Comp_Id='$comp_id' AND Quote_Status=1";
    $quote_chk_run=mysqli_query($connection,$quote_chk);
    if(mysqli_num_rows($quote_chk_run)>0){
        $btn_html='<button class="btn btn-sm btn-light ">Quotation Already Sent</button>';
    }
    else{
        $btn_html='<button class="btn btn-sm btn-info sendQBtn">Send Quote</button>';
    }
    echo '<div class="float-right">'.$btn_html.'</div>';
 }
}
//index.php company user (quote details)
if(isset($_POST['sent_quote'])){
$quote_id=$_POST['sent_quote'];
$comp_id=$_POST['comp_id'];
//get post id
$Post_Id="SELECT Post_Id FROM quote WHERE Quote_Id='$quote_id'";$Post_Id_run=mysqli_query($connection,$Post_Id);
$row_pid=mysqli_fetch_assoc($Post_Id_run);
$post_id=$row_pid['Post_Id'];
 $q_comp="SELECT Comp_Type FROM company WHERE Comp_Id='$comp_id'";
 $q_comp_run=mysqli_query($connection,$q_comp);
 $row_c=mysqli_fetch_assoc($q_comp_run);
 $comp_type=$row_c['Comp_Type'];
 $p_details="SELECT * FROM post as p
            LEFT JOIN project as prj on prj.Prj_Id=p.Prj_Id
            WHERE  p.Post_Id='$post_id' LIMIT 1";
 $p_details_run=mysqli_query($connection,$p_details);
 if($p_details_run){
    $row_p=mysqli_fetch_assoc($p_details_run);
    //post details
    $post_name=$row_p['Post_Name'];
    $prj_name=$row_p['Prj_Name'];
    $prj_code=$row_p['Prj_Code'];
    $post_date=$row_p['Post_Date'];
    $post_desc=$row_p['Post_Desc'];
    $post_date=date("M d, Y",strtotime($post_date));
    $post_type=$row_p['Post_Type'];
    if($post_type=='material'){
        $q_details="SELECT * FROM material_post WHERE Post_Id='$post_id' AND Mat_Post_Status=1";
        $q_details_run=mysqli_query($connection,$q_details);
        $th_html='<th class="d-none">Department</th>
                    <th width="55%">Material</th>
                    <th width="5%">Unit</th>
                    <th width="10%">Qty Needed</th>
                    <th width="15%">Preffered Brand</th>
                    <th width="15%">Rate</th>';
    }
    if($post_type=='manpower'){
        $q_details="SELECT * FROM manpower_post WHERE Post_Id='$post_id' AND MP_Post_Status=1";
        $q_details_run=mysqli_query($connection,$q_details);
        if($comp_type=='subcon'){
            $th_html='<th class="d-none">Department</th>
                        <th width="55%">Service Desc</th>
                        <th width="15%">Unit</th>
                        <th width="15%">Total Area No.</th>
                        <th width="15%">Rate per Unit</th>';
        }
        elseif($comp_type=='laborSupply' || $comp_type=='agency'){
            $th_html='<th width="15%">Department</th>
                        <th width="55%">Description</th>
                        <th width="15%">Qty</th>
                        <th width="15%">Rate</th>';
        }
    }
    $p_desc='
        <div class="form-row mb-3">
            <div class="col-9">
                <h5 class="m-0 font-weight-bold text-primary">'.$post_name.'</h5>
            </div>
            <div class="col-3">'.$post_date.'</div>
        </div>
        <div class="form-row">
            <div class="col-2">
                <label class="font-weight-bold mr-1">Project:</label><br>
            </div>
            <div class="col-10">
                <span>'.$prj_code.' '.$prj_name.'</span>
            </div>
        </div>
        <div class="form-row">
            <div class="col-2">
                <label class="font-weight-bold mr-1">Description:</label><br>
            </div>
            <div class="col-10">
                <span>'.$post_desc.'</span>
            </div>
        </div>
        <div class="table-responsive" id="tbl">
            <table class="table table-sm table-bordered mt-3 dtable"  width="100%" cellspacing="0">
                <thead> '.$th_html.' </thead>
                <tbody>';
    if(mysqli_num_rows($q_details_run)>0){
        while($row_d=mysqli_fetch_assoc($q_details_run)){
            if($post_type=='manpower' && $comp_type=='subcon'){
                $td1=$row_d['MP_Post_Desc'];//description
                $td2=$row_d['MP_Post_Unit'];//unit
                $td3=$row_d['MP_Post_Qty'];//area
                $td4=$row_d['MP_Post_Id'];
                $unit=$td2;
            }
            if($post_type=='manpower' && $comp_type=='laborSupply'){
                $dept_id=$row_d['Dept_Id'];//department
                $td2=$row_d['MP_Post_Desc'];//desc    
                $td3=$row_d['MP_Post_Qty'];//qty person need
                $td4=$row_d['MP_Post_Id'];
                $dept_q="SELECT * FROM department WHERE Dept_Status=1 and Dept_Id='$dept_id'";
                $dept_q_run=mysqli_query($connection,$dept_q);
                $row_dept=mysqli_fetch_assoc($dept_q_run);
                $td1=$row_dept['Dept_Name'];
                $unit='hour';
            }
            if($post_type=='material'){
                $mat_id=$row_d['Mat_Id'];//material name
                $td2=$row_d['Mat_Post_Qty'];//qty
                $td3=$row_d['Mat_Post_Brand'];//preffered brand
                $td4=$row_d['Mat_Post_Id'];//

                $mat_q="SELECT * FROM material WHERE Mat_Status=1 AND Mat_Id='$mat_id'";
                $mat_q_run=mysqli_query($connection,$mat_q);
                $row_m=mysqli_fetch_assoc($mat_q_run);
                $mat_unit=$row_m['Mat_Unit'];
                $td1=$row_m['Mat_Desc'];

                $q_rate="SELECT * FROM  quote_detail WHERE Quote_Id='$quote_id' AND Mat_Post_Id='$td4'";
                $q_rate_run=mysqli_query($connection,$q_rate);
                $row_qr=mysqli_fetch_assoc($q_rate_run);
                $rate=$row_qr['Quote_Detail_Value']; // rate offer by quote comp
            }
            if($post_type=='material'){
                $p_desc.='
                <tr>
                    <td>'.$td1.'</td>
                    <td>'.$mat_unit.'</td>
                    <td>'.$td2.'</td>
                    <td>'.$td3.'</td>
                    <td>'.$rate.'</td>
                </tr> ';
            }
            else{
                $p_desc.='
                <tr>
                    <td>'.$td1.'</td>
                    <td>'.$td2.'</td>
                    <td>'.$td3.'</td>
                    <td>'.$td4.'</td>
                    <td>'.$rate.'</td>
                </tr> '; 
            }
    }
    $p_desc.='
                </tbody>
            </table>
        </div> ';
    }
    echo $p_desc;
 }
}
//view post details (purchase view)
if(isset($_POST['ppost_id'])){//SHOW post details by notif
    $post_id=$_POST['ppost_id'];
    $p_details="SELECT * FROM post as p
               LEFT JOIN project as prj on prj.Prj_Id=p.Prj_Id
               WHERE  p.Post_Id='$post_id' LIMIT 1";
    $p_details_run=mysqli_query($connection,$p_details);
    if($p_details_run){
       $row_p=mysqli_fetch_assoc($p_details_run);
       //post details
       $post_name=$row_p['Post_Name'];
       $prj_name=$row_p['Prj_Name'];
       $prj_code=$row_p['Prj_Code'];
       $post_date=$row_p['Post_Date'];
       $post_desc=$row_p['Post_Desc'];
       $post_date=date("M d, Y",strtotime($post_date));
       $post_type=$row_p['Post_Type'];
       if($post_type=='material'){
           $q_details="SELECT * FROM material_post WHERE Post_Id='$post_id' AND Mat_Post_Status=1";
           $q_details_run=mysqli_query($connection,$q_details);
           $th_html='<th class="d-none">Department</th>
                       <th width="55%">Material</th>
                       <th width="5%">Unit</th>
                       <th width="10%">Qty Needed</th>
                       <th width="15%">Preffered Brand</th>
                       <th width="15%" class="d-none inviTD">Rate</th>
                       <th class="d-none">Id</th>';
       }
       if($post_type=='manpower'){
           $q_details="SELECT * FROM manpower_post WHERE Post_Id='$post_id' AND MP_Post_Status=1 ";
           $q_details_run=mysqli_query($connection,$q_details);
           $th_html='<th width="15%">Department</th>
           <th  width="55%">Description</th>
           <th  width="15%">Qty</th>
           <th width="15%" class="d-none inviTD">Rate</th>
           <th class="d-none">Id</th>';
       }
       if($post_type=='subcontractor'){
            $q_details="SELECT * FROM manpower_post WHERE Post_Id='$post_id' AND MP_Post_Status=1 ";
            $q_details_run=mysqli_query($connection,$q_details);
            $th_html='<th class="d-none">Department</th>
            <th width="55%">Service Desc</th>
            <th width="15%">Unit</th>
            <th width="15%">Total Area No.</th>
            <th width="15%" class="d-none inviTD">Rate per Unit</th>
            <th class="d-none">Id</th>';
       }
       $p_desc='
           <div class="form-row mb-3">
               <div class="col-9">
                   <h5 class="m-0 font-weight-bold text-primary">'.$post_name.'</h5>
               </div>
               <div class="col-3">'.$post_date.'</div>
           </div>
           <div class="form-row">
               <div class="col-2">
                   <label class="font-weight-bold mr-1">Project:</label><br>
               </div>
               <div class="col-10">
                   <span>'.$prj_code.' '.$prj_name.'</span>
               </div>
           </div>
           <div class="form-row">
               <div class="col-2">
                   <label class="font-weight-bold mr-1">Description:</label><br>
               </div>
               <div class="col-10">
                   <span>'.$post_desc.'</span>
               </div>
           </div>
           <div class="table-responsive" id="tbl">
               <table class="table table-sm table-bordered mt-3 dtable"  width="100%" cellspacing="0">
                   <thead> '.$th_html.' </thead>
                   <tbody>';
       if(mysqli_num_rows($q_details_run)>0){
           while($row_d=mysqli_fetch_assoc($q_details_run)){
               if($post_type=='subcontractor' ){
                   $td1=$row_d['MP_Post_Desc'];//description
                   $td2=$row_d['MP_Post_Unit'];//unit
                   $td3=$row_d['MP_Post_Qty'];//area
                   $td4=$row_d['MP_Post_Id'];
                   $unit=$td2;
               }
               if($post_type=='manpower' ){
                   $dept_id=$row_d['Dept_Id'];//department
                   $td2=$row_d['MP_Post_Desc'];//desc    
                   $td3=$row_d['MP_Post_Qty'];//qty person need
                   $td4=$row_d['MP_Post_Id'];
                   $dept_q="SELECT * FROM department WHERE Dept_Status=1 and Dept_Id='$dept_id'";
                   $dept_q_run=mysqli_query($connection,$dept_q);
                   $row_dept=mysqli_fetch_assoc($dept_q_run);
                   $td1=$row_dept['Dept_Name'];
                   $unit='hour';
               }
               if($post_type=='material'){
                   $mat_id=$row_d['Mat_Id'];//material name
                   $td2=$row_d['Mat_Post_Qty'];//qty
                   $td3=$row_d['Mat_Post_Brand'];//preffered brand
                   $td4=$row_d['Mat_Post_Id'];//
   
                   $mat_q="SELECT * FROM material WHERE Mat_Status=1 AND Mat_Id='$mat_id'";
                   $mat_q_run=mysqli_query($connection,$mat_q);
                   if(mysqli_num_rows($mat_q_run)>0 ){
                    $row_m=mysqli_fetch_assoc($mat_q_run);
                    $mat_unit=$row_m['Mat_Unit'];
                    $td1=$row_m['Mat_Desc'];
                   }
                   else{
                       $td1=$row_d['Mat_Id'];;
                       $mat_unit=$row_d['Mat_Post_Unit'];
                   }
               }
               if($post_type=='material'){
                   $p_desc.='
                   <tr>
                       <td>'.$td1.'</td>
                       <td>'.$mat_unit.'</td>
                       <td>'.$td2.'</td>
                       <td>'.$td3.'</td>
                       <td class="d-none inviTD">
                           <div class="form-row">
                               <div class="col-8">
                                   <input type="number" name="q_qty[]" class="form-control" required>
                               </div>
                               <div class="col-4">
                               '.'/'.$mat_unit.'
                               </div>
                           </div>
                       </td>
                       <td class="d-none"><input type="text" name="post_desc_id[]" value="'.$td4.'"></td>
                   </tr> ';
               }
               else{
                   $p_desc.='
                   <tr>
                       <td>'.$td1.'</td>
                       <td>'.$td2.'</td>
                       <td>'.$td3.'</td>
                       <td class="d-none inviTD">
                           <div class="form-row">
                               <div class="col-8">
                                   <input type="number" name="q_qty[]" class="form-control" required>
                               </div>
                               <div class="col-4">
                                   /'.$unit.'
                               </div>
                           </div>
                       </td>
                       <td class="d-none"><input type="text" name="post_desc_id[]" value="'.$td4.'"></td>
                   </tr> '; 
               }
       }
       $p_desc.='
                   </tbody>
               </table>
           </div> ';
       }
       echo $p_desc;
    }
}
//material post view
if(isset($_POST['mpost_id'])){
    $post_id=$_POST['mpost_id']; $mat_details='';
    $p_details="SELECT * FROM post as p
               LEFT JOIN project as prj on prj.Prj_Id=p.Prj_Id
               WHERE  p.Post_Id='$post_id' LIMIT 1";
    $p_details_run=mysqli_query($connection,$p_details);
    if($p_details_run){
        $row_p=mysqli_fetch_assoc($p_details_run);
        //post details
        $post_name=$row_p['Post_Name'];
        $prj_name=$row_p['Prj_Name'];
        $prj_code=$row_p['Prj_Code'];
        $post_date=$row_p['Post_Date'];
        $post_desc=$row_p['Post_Desc'];
        $post_date=date("M d, Y",strtotime($post_date));
        $post_type=$row_p['Post_Type'];
        $p_desc='
           <div class="form-row mb-3">
               <div class="col-9">
                   <h5 class="m-0 font-weight-bold text-primary">'.$post_name.'</h5>
               </div>
               <div class="col-3">'.$post_date.'</div>
           </div>
           <div class="form-row">
               <div class="col-2">
                   <label class="font-weight-bold mr-1">Project:</label><br>
               </div>
               <div class="col-10">
                   <span>'.$prj_code.' '.$prj_name.'</span>
               </div>
           </div>
           <div class="form-row">
               <div class="col-2">
                   <label class="font-weight-bold mr-1">Description:</label><br>
               </div>
               <div class="col-10">
                   <span>'.$post_desc.'</span>
               </div>
           </div>
           <div class="table-responsive" id="tbl" data-role="main" class="ui-content">
               <table class="table table-sm table-bordered mt-3 dtable ui-responsive"  width="100%" cellspacing="0" data-role="table" data-mode="columntoggle">
                    <tbody>
                    ';
        // header check 
        $q_header="SELECT sum(length(`Mat_Post_Capacity`)) as sum_capacity,Sum(length(`Mat_Post_Esp`)) as sum_esp,sum(length(Mat_Post_Location)) as sum_location,sum(length(Mat_Post_Brand)) as sum_brand FROM `material_post` WHERE Post_Id='$post_id';";
        $q_header_run=mysqli_query($connection,$q_header); 
        $row_h=mysqli_fetch_assoc($q_header_run);
        $t_location=$row_h['sum_location']; $t_capacity=$row_h['sum_capacity'];
        $t_esp=$row_h['sum_esp']; $t_prefBrand=$row_h['sum_brand'];//preffered brand
        $other_th=''; $td_span=3; $c_cap='';$c_esp='';$c_pb='';$c_loc='';
        if($t_capacity>0){
            $other_th .='<th>Capacity</th>'; $td_span++; $c_cap=1;
        }
        if( $t_esp>0){
            $other_th .='<th>ESP(pa)</th>'; $td_span++;$c_esp=1;
        }
        if($t_prefBrand>0){
            $other_th .='<th>Preffered Brand</th>'; $td_span++;$c_pb=1;
        }
        if($t_location>0){
            $other_th .='<th>Location</th>'; $td_span++;$c_loc=1;
        }
        $mat_details.='
            <thead>
                <th>Description</th>
                '.$other_th.'                
                <th>Unit</th>
                <th>QTY</th>
            </thead>';
        //SEARCH GROUPS by post id
        $q_grp="SELECT * FROM material_post_group WHERE Post_Id='$post_id' AND MP_Grp_Status=1";  
        $q_grp_run=mysqli_query($connection,$q_grp); $visb='';
        if(mysqli_num_rows($q_grp_run)>0){
            while($row_g=mysqli_fetch_assoc($q_grp_run)){
                $grp_id=$row_g['MP_Grp_Id'];
                $grp_name=$row_g['MP_Grp_Name'];
                $grp_location=$row_g['MP_Grp_Location'];
                if($grp_location==''){
                    $visb='d-none';
                }
                // $mat_details.='';
                $mat_details.='
                <tr>
                    <td colspan="'.$td_span.'">
                    '.$grp_name.'
                   <span class="float-right '.$visb.'">(Location: '.$grp_location.')</span>
                   </td>
                </tr>';
                // post details by group
                $q_post="SELECT * FROM material_post WHERE Post_Id='$post_id' AND MP_Grp_Id='$grp_id'";
                $q_post_details=mysqli_query($connection,$q_post); $other_td='';
                if(mysqli_num_rows($q_post_details)>0){
                    while($row_p=mysqli_fetch_assoc($q_post_details)){
                        // header check
                        $mat_id=$row_p['Mat_Id'];//material name
                    ///////
                    if($c_cap==1){
                        $capacity=$row_p['Mat_Post_Capacity'];
                        $other_td .=' <td>'.$capacity.'</td>';
                    }
                    if($c_esp==1){
                        $esp=$row_p['Mat_Post_Esp'];
                        $other_td .='<td>'.$esp.'</td>';
                    }
                    if($c_pb==1){
                        $prefBrand=$row_p['Mat_Post_Brand'];//preffered brand
                        $other_td .='<td>'.$prefBrand.'</td>';
                    }
                    if($c_loc==1){
                        $location=$row_p['Mat_Post_Location'];
                        $other_td .='<td>'.$location.'</td>';
                    }
                    $qty=$row_p['Mat_Post_Qty'];//qty
                    $mat_q="SELECT * FROM material WHERE Mat_Status=1 AND Mat_Id='$mat_id'";
                    $mat_q_run=mysqli_query($connection,$mat_q);
                    if(mysqli_num_rows($mat_q_run)>0){
                        $row_m=mysqli_fetch_assoc($mat_q_run);
                        $mat_desc=$row_m['Mat_Desc'];
                        $mat_unit=$row_m['Mat_Unit'];
                    }
                    else{
                        $mat_desc=$row_p['Mat_Id'];
                        $mat_unit=$row_p['Mat_Post_Unit'];
                    }
                    $mat_details.='
                    <tr>
                        <td>'.$mat_desc.'</td>
                        '.$other_td.'
                        <td>'.$mat_unit.'</td>
                        <td>'.$qty.'</td>
                    </tr>';$other_td='';
                    }
                }
            }
        }          
        $mat_details .='</tbody>
                </table>
            </div> ';       
    }
    echo $p_desc.$mat_details;
}
//sending a quote ajax
if(isset($_POST['quote_apply'])){
    //
    $post_id=$_POST['post_id'];
}
if(isset($_POST['file'])){
    // echo "<iframe src=\"file.pdf\" width=\"100%\" style=\"height:100%\"></iframe>";
    $filename= $_POST['file'];
    $backslash='\ ';
    $backslash= str_replace(' ', '', $backslash);
    $first='<iframe src=\'..\..\uploads';
    $end='\' width=\'100%\' style=\'height:100%\'></iframe>';
    $file=$first.$backslash.$filename.$end;
    echo $file;
}
?>