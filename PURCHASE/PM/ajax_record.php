<?php
include('../../security.php');
if(isset($_POST['item_opt'])){
    $q_mat_record="SELECT DISTINCT(Mat_Id) FROM material_post as mp 
                LEFT JOIN quote_detail as qd on qd.Mat_Post_Id=mp.Mat_Post_Id
                WHERE qd.Quote_Detail_Status!=0 AND mp.Mat_Post_Status!=0";
    $q_mat_record_run=mysqli_query($connection,$q_mat_record);
    $options='';
    if(mysqli_num_rows($q_mat_record_run)>0){
        while($row=mysqli_fetch_assoc($q_mat_record_run)){
            $mat=$row['Mat_Id'];
            if(is_numeric($mat)){
                // search from materials table
                $q_mat="SELECT Mat_Desc, Mat_Unit, Mat_Code FROM material WHERE Mat_Id='$mat' AND Mat_Status!=0";
                $q_mat_run=mysqli_query($connection,$q_mat);
                if(mysqli_num_rows($q_mat_run)>0){
                    $row_m=mysqli_fetch_assoc($q_mat_run);
                    $desc=$row_m['Mat_Desc'];
                    $mat_code=$row_m['Mat_Code'];
                    $unit=$row_m['Mat_Unit'];
                    $options.='<option value="'.$mat.'">'.$mat_code.' '.$desc.'</option>';
                }
            }
            else{
                $mat_ref_code=$row['Mat_Post_Ref_Code'];
                $options.='<option value="'.$mat.'">'.$mat_ref_code.' '.$mat.'</option>';
            }
        }
    }
    echo $options;
}
function decPlace($num){
    if($num!=null){
        if ( strpos( $num, "." ) !== false ) {
            $num=number_format($num, 2);
        }
    }
    $num=number_format($num);
    return $num;
}
if(isset($_POST['item_opt_change'])){
    $item=$_POST['item_opt_change'];
    $q_ids="SELECT DISTINCT(q.Quote_Id)  FROM post as p
            LEFT JOIN material_post_group as mpg on mpg.Post_Id=p.Post_Id
            LEFT JOIN quote as q on q.Post_Id=p.Post_Id
            WHERE p.Post_Status!=0 AND mpg.MP_Grp_Status=1 ";
    $q_ids_run=mysqli_query($connection,$q_ids);
    if(mysqli_num_rows($q_ids_run)>0){ $qoute_ids='';
        while($row_q=mysqli_fetch_assoc($q_ids_run)){
            // $quote_id=
            $qId_array[]=$row_q['Quote_Id'];
        }
        $qoute_ids=implode("','", $qId_array);
    }
    $q_mat_record="SELECT * FROM quote_detail as qd
                LEFT JOIN material_post as mp on mp.Mat_Post_Id=qd.Mat_Post_Id 
                LEFT JOIN quote as q on q.Quote_Id=qd.Quote_Id
                LEFT JOIN post as p on p.Post_Id=mp.Post_Id
                LEFT JOIN material_post_group as mpg on mpg.Post_Id = p.Post_Id
                WHERE  mp.Mat_Post_Status!=0  AND qd.Quote_Detail_Status!=0 AND mp.Mat_Id='$item'  AND p.Post_Status!=0 AND mpg.MP_Grp_Status!=0
                AND q.Quote_Id in('$qoute_ids')
                GROUP BY qd.Quote_Detail_Id";
    $q_mat_record_run=mysqli_query($connection,$q_mat_record);
    if(mysqli_num_rows($q_mat_record_run)>0){
        $table='
        <table class="table table-bordered table-striped" id="dataTable" width="100%" cellspacing="0">
            <thead>
                <th>Company Name</th>
                <th>Unit</th>
                <th>Price</th>
                <th>Discount</th>
                <th>Discounted Price</th>
                <th>Quote Record</th>
                <th>Status</th>
            </thead>
            <tbody>';
        while($row=mysqli_fetch_assoc($q_mat_record_run)){
            $comp_id=$row['Comp_Id'];
            $q_company="SELECT Comp_Name FROM company WHERE Comp_Id=$comp_id AND Comp_Status!=0";
            $q_company_run=mysqli_query($connection,$q_company);
            if(mysqli_num_rows($q_company_run)>0){
                $row_c=mysqli_fetch_assoc($q_company_run);
                $comp_name=$row_c['Comp_Name'];
            }
            if(is_numeric($item)){
                // search from materials table
                $q_mat="SELECT Mat_Desc, Mat_Unit FROM material WHERE Mat_Id='$item' AND Mat_Status!=0";
                $q_mat_run=mysqli_query($connection,$q_mat);
                if(mysqli_num_rows($q_mat_run)>0){
                    $row_m=mysqli_fetch_assoc($q_mat_run);
                    $desc=$row_m['Mat_Desc'];
                    $unit=$row_m['Mat_Unit'];
                }
            }
            else{
                $unit=$row['Mat_Post_Unit'];
                if($unit){
                    $unit=$row['Mat_Post_Unit'];
                }
                else{
                    $unit='';
                }
            }
            $unit_price=$row['Quote_Detail_Value'];
            $discount=$row['Quote_Detail_Disc'];
            if($discount){
                if($discount==0){
                    $discount='';
                    $discounted_p='';
                }
                else{
                    $ddiscount=$discount*.01;
                    $minus=$unit_price*$ddiscount;
                    $discounted_p=$unit_price-$minus;
                }
            }
            else{
                $discount=NULL;
                $discounted_p=NULL;
            }
        $quote_id=$row['Quote_Id'];
        $quote_app_stat=$row['Quote_Approval'];
        if($quote_app_stat==1){//approved
            $button='<button class="btn btn-success btn-sm" disabled>Approved</button>';
        }
        elseif($quote_app_stat==2){//pending
            $button='<button class="btn btn-warning btn-sm" disabled>Pending</button>';
        }
        elseif($quote_app_stat==0){//rejected
            $button='<button class="btn btn-danger btn-sm" disabled>Rejected</button>';
        }
        else{
            $button='';
        }
        $post_name=$row['Post_Name'];
        $table.='<tr>
                    <td>'.$comp_name.'</td>
                    <td>'.$unit.'</td>
                    <td>'.$unit_price.'</td>
                    <td>'.$discount.'</td>
                    <td>'.$discounted_p.'</td>
                    <td>
                        <input type="hidden" value="'.$quote_id.'">
                        <a href="#" class="quoteView">'.$post_name.'</a>
                    </td>
                    <td>'.$button.'</td>
                </tr>';
        }
        $table.='</tbody>
        </table>';
    }
    else{
        echo 'No Record Found';
        $table='';
    }
    echo $table;
}
?>