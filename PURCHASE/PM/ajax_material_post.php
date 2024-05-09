<?php
include('../../security.php');
//material table
if(isset($_POST['query'])){
    $query=$_POST['query']; 
    $query_run = mysqli_query($connection, $query);
    $tbl=''; $ctn=0;
    if(mysqli_num_rows($query_run)>0){
        $tbl.='
        <table class="table table-bordered table-striped post_tbl" id="dataTable" width="100%" cellspacing="0">    
            <thead>
                <th>Post Description</th>
                <th>Project</th>
                <th>Quotations</th>
                <th>Status</th>
                <th>Date Posted</th>
                <th>Action</th>
            </thead>
            <tbody>';
        while($row = mysqli_fetch_assoc($query_run))
        {
            $post_id=$row['Post_Id'];$ctn++;
            //status
            $post_status=$row['Post_Status'];
            if($post_status==1){
                $btn_html='<button class="btn btn-sm btn-success disabled">Active</button>';
            }
            elseif($post_status==2){
                $btn_html='<button class="btn btn-sm btn-danger disabled">Closed</button>';
            }
            else{ $btn_html='';}
            $date=$row['Post_Date'];
            $post_date=date("m/d/Y",strtotime($date));
            //  quote applied
            // $quotes="SELECT * FROM quote WHERE Quote_Status='1' AND Post_Id='$post_id'";
            $quotes="SELECT DISTINCT q.Quote_Id FROM quote as q 
                    LEFT JOIN post as p on p.Post_Id=q.Post_Id 
                    LEFT JOIN quote_detail as qd on qd.Quote_Id=q.Quote_Id 
                    WHERE q.Quote_Status=1 AND p.Post_Id='$post_id' AND Quote_Detail_Id is not null";

            $quote_run=mysqli_query($connection,$quotes);
            $quote_applied=mysqli_num_rows($quote_run);
            if($quote_applied>0){
                $disabled='disabled';
            }
            else{
                $disabled='';
            }
            $tbl.='<tr>
                    <td>
                        <input type="hidden" name="post_id" id="p'.$ctn.'" value="'.$post_id.'"><a href="#" class="text-primary postView">'.$row['Post_Name'].'</a></td>
                    <td>'.$row['Prj_Code'].' '.$row['Prj_Name'].'</td>
                    <td><a href="quotation_mat.php?post_id='.$post_id.'&type=job_post">'.$quote_applied.'</a></td>
                    <td>'.$btn_html.'</td>
                    <td>'.$post_date.'</td>
                    <td class="btn-group">
                        <input type="hidden" name="post_id" value="'.$post_id.'">
                        <button type="button" name="send_mail" class="btn btn-warning rounded sendMail">
                            <i class="fa fa-envelope-o" aria-hidden="true"></i>
                        </button>
                        <input type="hidden" name="post_name" value="'.$row['Post_Name'].'">
                        <form action="edit_mat_post.php" method="post">
                            <input type="hidden" name="post_id" value="'.$post_id.'">
                            <button type="submit" name="edit_post" class="btn btn-success editPost"  '.$disabled.'>
                                <i class="fa fa-edit" aria-hidden="true"></i>
                            </button>
                        </form>
                        <form action="code.php" method="post">
                            <input type="hidden" name="post_id" value="'.$post_id.'">
                            <button type="submit" name="delete_mat_post" class="btn btn-danger delBtn">
                                <i class="fa fa-times" aria-hidden="true"></i>
                            </button>
                        </form>
                    </td>
                </tr>';
        }
        $tbl.='</tbody>
        </table>';
        echo $tbl;
    }
    else{
        echo 'no record found';
    }
}
//job post table
if(isset($_POST['jquery'])){
    $query=$_POST['jquery']; 
    $query_run = mysqli_query($connection, $query);
    $tbl=''; $ctn=0;
    if(mysqli_num_rows($query_run)>0){
        $tbl.='
        <table class="table table-bordered table-striped" id="dataTable" width="100%" cellspacing="0">
            <thead>
                <th class="d-none">Ref. No</th>
                <th>Post Description</th>
                <th>Project</th>
                <th>Quotations</th>
                <th>Status</th>
                <th>Date Posted</th>
                <th>Action</th>
            </thead>
            <tbody>';
        while($row = mysqli_fetch_assoc($query_run))
        {
            $post_id=$row['Post_Id'];$ctn++;
            $post_status=$row['Post_Status'];
            if($post_status==1){
                $btn_html='<button class="btn btn-sm btn-success disabled">Active</button>';
            }
            elseif($post_status==2){
                $btn_html='<button class="btn btn-sm btn-danger disabled">Closed</button>';
            }
            else{ $btn_html='';}
            $date=$row['Post_Date'];
            $post_date=date("m/d/Y",strtotime($date));
            //  quote applied
            $quotes="SELECT DISTINCT q.Quote_Id FROM quote as q 
                    LEFT JOIN post as p on p.Post_Id=q.Post_Id 
                    LEFT JOIN quote_detail as qd on qd.Quote_Id=q.Quote_Id 
                    WHERE q.Quote_Status=1 AND p.Post_Id='$post_id' AND Quote_Detail_Id is not null";
            $quote_run=mysqli_query($connection,$quotes);
            $quote_applied=mysqli_num_rows($quote_run);
            if($quote_applied>0){
                $disabled='disabled';
            }
            else{
                $disabled='';
            }
            $tbl.='
            <tr>
                <td class="d-none"></td>
                <td><input type="hidden" value="'.$post_id.'"><a href="#" class="text-primary postView">'.$row['Post_Name'].'</a></td>
                <td>'.$row['Prj_Code'].' '.$row['Prj_Name'].'</td>
                <td><a href="quotation.php?post_id='.$post_id.'&type=job_post">'.$quote_applied.'</a></td>
                <td>'.$btn_html.'</td>
                <td>'.$post_date.'</td>
                <td class="btn-group">
                    <input type="hidden" name="post_id" value="'.$post_id.'">
                    <button type="button" name="send_mail" class="btn btn-warning rounded sendMail">
                        <i class="fa fa-envelope-o" aria-hidden="true"></i>
                    </button>
                    <form action="edit_job_post.php" method="post">
                        <input type="hidden" name="post_id" value="'.$post_id.'">
                        <button type="submit" name="edit_post" class="btn btn-success editPost '.$disabled.'">
                            <i class="fa fa-edit" aria-hidden="true"></i>
                        </button>
                    </form>
                    <form action="code.php" method="post">
                        <input type="hidden" name="post_id" value="'.$post_id.'">
                        <button type="submit" name="delPost" class="btn btn-danger delBtn">
                            <i class="fa fa-times" aria-hidden="true"></i>
                        </button>
                    </form>
                </td>
            </tr>';
        }
        $tbl.='
            </tbody>
        </table>';
        echo $tbl;
    }
    else{
        echo 'no record found';
    }
}
?>
