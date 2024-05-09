<?php
include('security.php');
include('includes/header.php');
include('includes/navbar.php');
?>
<style>
#viewPdf .modal-dialog ,
.modal-content {
    /* 80% of window height */
    height: 95%;
}
</style>
<div class="container-fluid">
    <div class="card shadow mb-5">
        <div class="card-header py-3">
            <h5 class="m-0 font-weight-bold text-primary">Employee Files</h5>
            <button type="button" class="btn btn-primary float-right" data-toggle="modal" data-target="#addpayslip">
                <i class="fa fa-plus" aria-hidden="true"></i> Add File
            </button>
        </div>
        <div class="card-body">
        <?php
                $q_files="SELECT * FROM files ";
                $q_files_run=mysqli_query($connection,$q_files);
            ?>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped" id="dataTable" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <td>Employee Code</td>
                                    <td>Employee Name</td>
                                    <td>File Description</td>
                                    <td>Actions</td>
                                </tr>
                            </thead>
                            <tbody>
                            <?php
                                if(mysqli_num_rows($q_files_run)>0){ $file_html='';
                                    while($row_f=mysqli_fetch_assoc($q_files_run)){
                                        $file_id=$row_f['File_Id'];
                                        $file_type=$row_f['File_Type'];
                                        $filename=$row_f['File_Desc'];
                                        $emp_id=$row_f['Emp_Id'];
                                        if($emp_id){
                                            $q_emp="SELECT * FROM employee WHERE EMP_ID='$emp_id'";
                                            $q_emp_run=mysqli_query($connection,$q_emp);
                                            if(mysqli_num_rows($q_emp_run)>0){
                                                $row_p=mysqli_fetch_assoc($q_emp_run);
                                                $emp_no=$row_p['EMP_NO'];
                                                $fname=ucwords($row_p['EMP_FNAME']);
                                                $mname=ucwords($row_p['EMP_MNAME']);
                                                $lname=ucwords($row_p['EMP_LNAME']);
                                                $sname=ucwords($row_p['EMP_SNAME']);
                                                $emp_name=$fname.' '.$mname.' '.$lname.' '.$sname;
                                            }
                                        }
                                        if($file_type=='EID'){ //EMIRATES ID
                                            $file_desc='Emirates ID';
                                            $cfilename='EID'.$emp_no;
                                        }
                                        elseif($file_type=='PP'){ //PASSPORT
                                            $file_desc='Passport';
                                            $cfilename='PP'.$emp_no;
                                        }
                                        elseif($file_type=='VS'){ //VISA
                                            $file_desc='Visa';
                                            $cfilename='VS'.$emp_no; 
                                        }
                                        elseif($file_type=='CNT'){ //CONTRACT
                                            $file_desc='Contract';
                                            $cfilename='CNT'.$emp_no;
                                        }
                                        else{ //OTHER FILE TYPE
                                            // $filename=$file_type.$emp_no;
                                            // $filename=$row_f['File_Desc'];
                                            $file_desc=$file_type;
                                        }
                                        // check if 2nd or 3rd copy
                                        $ordinal=''; $number=null;
                                        if($cfilename==$filename){
                                            //first copy
                                        }
                                        else{// count how many 1
                                            if (str_contains($cfilename, $filename) || str_contains($filename,$cfilename)) { 
                                                $number=str_replace($cfilename,'',$filename);
                                                // echo $number;
                                                $number=strlen($number);
                                                if($number==1){
                                                    $ordinal='2nd copy';
                                                }
                                                elseif($number==2){
                                                    $ordinal='3rd copy';
                                                }
                                                elseif($number>=3){
                                                    $ordinal=$number++.'th copy';
                                                }
                                                else{
                                                    $ordinal='';
                                                }
                                            }
                                            else{
                                                $ordinal='';
                                            }
                                        }
                                        $checkFile="empFiles/".$filename.".pdf";
                                        $file_html.='<tr>';
                                        //CHECK FILE EXISTS
                                        if(file_exists($checkFile)){
                                            $file_html.='
                                                <td>'.$emp_no.'</td>
                                                <td>'.$emp_name.'</td>
                                                <td>'.$file_desc." ".$ordinal.'</td>
                                                <td class="btn-group inline-block">
                                                    <a href="#" class="btn btn-info  viewFile mr-2 rounded">
                                                        <span class="icon text-white">
                                                            <i class="fas fa-file-text"></i>
                                                        </span>
                                                    </a>
                                                    <input type="hidden" value="'.$filename.'">
                                                    <form action="code.php" method="POST">
                                                        <input type="hidden" name="filename" value="'.$filename.'">
                                                        <input type="hidden" name="file_id" value="'.$file_id.'">
                                                        <button type="submit" name="delFile" class="btn btn-danger  delFile ">
                                                                <i class="fas fa-trash"></i>
                                                        </button>
                                                    </form>
                                                    
                                                </td>';
                                        }
                                        else{
                                            $file_html.= '<td>nothing uploaded yet</td>
                                                    <td> '.$emp_name.' </td>
                                                    <td>'.$file_desc.'</td>
                                                    <td>
                                                        <form action="code.php" method="POST">
                                                            <input type="hidden" name="filename" value="'.$filename.'">
                                                            <input type="hidden" name="file_id" value="'.$file_id.'">
                                                            <button type="submit" name="delFile" class="btn btn-danger  delFile ">
                                                                    <i class="fas fa-trash"></i>
                                                            </button>
                                                        </form>
                                                    </td>';
                                        }
                                    }
                                }
                                else{
                                    echo "no data available";
                                }
                                echo $file_html;
                            ?>
                            </tbody>
                        </table>
                    </div>
                </div>
        </div>
    </div>
</div>
<div class="modal fade" id="viewPdf" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog  modal-xl view-md" role="document">
    <div class="modal-content view">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel"></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body" id="ff">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
<div class="modal fade" id="delFile" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog  modal-xl view-md" role="document">
    <div class="modal-content view">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel"></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body" id="">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
<!-- Add File --> 
<div class="modal fade bd-example-modal-lg" id="addpayslip" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
          <!-- MODAL TITLE -->
        <h5 class="modal-title" id="exampleModalLabel"><i class="fa fa-file-text" aria-hidden="true"></i> Add Employee File</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
    </div>
        <form action="code.php" enctype="multipart/form-data" method="post">
        <div class="modal-body" >
            <div class="form-row">
                <div class="col-8">
                    <label for="">Employee</label>
                    <select class="form-control selectpicker" name="emp_id" data-live-search="true" id="" required>
                        <option value="">Select Employee</option>
                        <?php
                        $query = "SELECT * FROM employee WHERE EMP_STATUS = 1";  $query_run = mysqli_query($connection, $query);
                        while($row = mysqli_fetch_array($query_run)) {
                        ?>
                            <option name="" value="<?php echo $row['EMP_ID']?>"><?php echo $row['EMP_NO'].' '.$row['EMP_FNAME'].''.$row['EMP_LNAME']; ?> </option>
                        <?php
                        }     
                        ?>
                    </select>
                </div>
            </div>
            <div class="form-row mt-3">
                <div class="col-6">
                    <label for="">File Description</label>
                    <select class="form-control " name="file_desc" id="file_opt" required>
                        <option value="EID">Emirates Id</option>
                        <option value="PP">Passport</option>
                        <option value="VS">Visa</option>
                        <option value="CNT">Contract</option>
                    </select>
                </div>
                <div class="col-4">
                    <label for="">Others:</label>
                    <input type="text" class="form-control" id="otherName">
                </div>
                <div class="col-2">
                    <label for="" class="invisible">1</label><br>
                    <button type="button" class="btn btn-success" id="otherAdd">Add +</button>
                </div>
            </div>
            <div class="form-row mt-3">
                <input type="file" name="file" required>
            </div>
            <button name="addFile" class="btn btn-success mt-3 float-right" type="submit">Submit</button>
        </div>
        </form>
      
    </div>
    </div>
</div>
<script>
$(document).on('click','.viewFile', function(){
    var filename =$(this).next().val();
    filename=filename+'.pdf';
    $.ajax({
        url:'ajax_user.php',
        method: 'POST',
        data:{'filename':filename},
        success:function(data){
            $('#viewPdf .modal-body').html(data);
            $('#viewPdf').modal('show');
        }
    });  
});
$(document).on('click','#otherAdd', function(){
    var desc=$('#otherName').val();
    $("#file_opt").append('<option value="'+desc+'">'+desc+'</option>');
    $("#file_opt").val(desc);
});
</script>
<?php
include('includes/scripts.php');
include('includes/footer.php');
?>