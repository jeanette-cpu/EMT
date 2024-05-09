<?php
include('../../security.php');
if(isset($_POST['comp_id'])){
    $comp_id = $_POST['comp_id'];
    $profile='';$table='';
    $q_comp="SELECT * FROM company WHERE Comp_Id='$comp_id' LIMIT 1";
    $q_comp_run=mysqli_query($connection,$q_comp);
    if($q_comp_run){ $c=0;
        $row_c=mysqli_fetch_assoc($q_comp_run);
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
        $TRN=$row_c['Comp_TRN'];
        if($cType=='laborSupply' || $cType=='subcon' || $cType=='agency'){
            $query="SELECT * FROM service where Comp_Id='$comp_id'";
            $query_run=mysqli_query($connection,$query);
            $serv_count=mysqli_num_rows($query_run);
            $ps_name="Manpower/Subcontractor Service Listed".' '.$serv_count.')';

        }
        else{
            $query="SELECT * FROM product where Comp_Id='$comp_id'";
            $query_run=mysqli_query($connection,$query);
            $mat_count=mysqli_num_rows($query_run);
            $ps_name="Material Listed".' ('.$mat_count.')';
        }

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
        $filename=$TRN;
        $table .='
        <table id="downloadProf" width="100%">
            <tbody>
                <tr>
                    <td width="15%"> <span class="font-weight-bold mr-2">Company Name:</span></td>
                    <td width="35%">'.$company_name.'</td>
                    <td width="15%"><span class="font-weight-bold mr-2">Website:</span></td>
                    <td width="35%">'.$website.'</td>
                </tr>
                <tr>
                    <td width="15%"> <span class="font-weight-bold mr-3">Company Type:</span></td>
                    <td width="35%">'.$type.' </td>
                    <td width="15%"> <span class="font-weight-bold mr-2">Date Registered:</span></td>
                    <td width="35%">'.$row_c['Comp_Reg_Date'].'</td>
                </tr>
                <tr>
                    <td width="15%"> <span class="font-weight-bold mr-3">TRN:</span></td>
                    <td width="35%"> '.$TRN.'</td>
                    <td width="15%">
                        <a href="#" class=" view">
                            <span class="icon">
                                <i class="fas fa-file-text"></i>
                            </span>
                            <span class="text">View TRN File</span>
                        </a>
                        <input id="fl'.$c.'" type="hidden" value="'.$filename.'">
                    </td>
                    <td width="35%">
                    </td>
                </tr>
                <tr>
                    <td><h5 class="text-primary mt-2">Contact Details</h5></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td colspan="2">
                        <table class="table table-bordered table-sm">
                            <tbody>
                                <tr>
                                    <td>Name:</td>
                                    <td>'.$cname.'</td>
                                </tr>
                                <tr>
                                    <td>Position</td>
                                    <td>'.$cpos.'</td>
                                </tr>
                                <tr>
                                    <td>Contact No:</td>
                                    <td>'.$cmob.'</td>
                                </tr>
                                <tr>
                                    <td>Landline:</td>
                                    <td>'.$cland.'</td>
                                </tr>
                                <tr>
                                    <td>Email:</td>
                                    <td>'.$cemail.'</td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                    <td colspan="2">
                        <table class="table table-bordered table-sm">
                            <tbody>
                                <tr>
                                    <td>Manager:</td>
                                    <td>'.$mname.'</td>
                                </tr>
                                <tr>
                                    <td>Email:</td>
                                    <td>'.$memail.'</td>
                                </tr>
                                <tr>
                                    <td>Contact No:</td>
                                    <td>'.$mmob.'</td>
                                </tr>
                                <tr>
                                    <td>Landline:</td>
                                    <td>'.$mland.'</td>
                                </tr>
                                <tr>
                                    <td><span class="invisible">l</span></td>
                                    <td><span class="invisible">l</span></td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td colspan="4"><h5 class="text-primary mt-2">'.$ps_name.'</h5></td>
                </tr>';
    if($row_c['Comp_Type']=='oem'||$row_c['Comp_Type']=='trading'||$row_c['Comp_Type']=='distributor')
    {
        $query_p="SELECT * FROM product where Prod_Status=1 and Comp_Id='$comp_id'";
        $query_p_run=mysqli_query($connection,$query_p);
        if(mysqli_num_rows($query_p_run)>0)
        {            
            $table .='
            <tr><td colspan="2" class="font-weight-bold">Product Desc.</td><td class="font-weight-bold">Brand</td><td class="font-weight-bold">Country</td></tr>';
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
                    <td colspan="2" width="60%">'.$desc.'</td>
                    <td  width="40%">'.$row_p['Prod_Brand'].'</td>
                    <td width="40%">'.$row_p['Prod_Country'].'</td>
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
            <tr><td colspan="2">Service Desc.</td><td>Unit</td><td>Rate</td></tr>
            ';
            while($row_s=mysqli_fetch_assoc($query_s_run))
            {
                $table .='
                <tr>
                    <td colspan="2">'.$row_s['Serve_Desc'].'</td>
                    <td>'.$row_s['Serve_Unit'].'</td>
                    <td>'.$row_s['Serve_Rate'].'</td>
                </tr> ';
            }
        }
        // $table .='</table>';
    }
    $table.='
            </tbody>
        </table>';
    }
    echo $table;
}
?>
