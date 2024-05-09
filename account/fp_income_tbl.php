<?php
include('../security.php');
include('../includes/header.php'); 
include('../includes/acc_navbar.php');
include('accQuery.php'); 
if(isset($_POST['monthEx'])){
    $cat_id=$_POST['cat_id'];
    $cat_desc=$_POST['cat_desc'];
}
if(isset($_GET['cat_id'])){
    $month_no = $_GET['month'];
    $month = date("F", mktime(0, 0, 0, $month_no, 1));
    $year = $_GET['year'];
    $cat_id=$_GET['cat_id'];
    $cat_desc=catName($cat_id);
}
else{
    $month = date('F'); //name
    $month_no = date('m');
    $year = date('Y');
}
?>
<div class="container-fluid">
    <div class="d-none">
        <input type="text" id="cat_id_in" value="<?php echo $cat_id;?>">
        <input type="text" id="month_in" value="<?php echo $month_no;?>">
        <input type="text" id="year_in" value="<?php echo $year;?>">
    </div>
    <div class="row">
        <div class="col-5 h3">
            <?php echo $month.' '.$year.' - '.$cat_desc;?>
        </div>
    </div>
    <form action="fp_Income_tbl.php" method="GET">
    <div class="row mt-4">
        <div class="col-2 ">
            <div class="form-group">
                <label for="">Month</label>
                <select name="month" id="month" class="form-control"></select>
            </div>
        </div>
        <div class="col-2 ">
            <div class="form-group">
                <label for="">Year</label>
                <select name="year" id="year" class="form-control"></select>
            </div>
        </div>
        <div class="col-2 ">
            <div class="form-group">
                <label for="">Category</label>
                <select name="cat_id" id="cat_opt" class="form-control"></select>
            </div>
        </div>
        <div class="col-2">
            <div class="form-group">
                <label for="" class="invisible">L</label><br>
                <button name="search" class="btn btn-sm btn-warning" type="submit" >Search</button>
            </div>
        </div>
    </div>
    </form>
    <div class="row">
        <div class="col-6">
            <div class="card shadow mb-5">
                <div class="card-header py-3">
                    <h5 class="m-0 font-weight-bold text-primary"> Expected Income (<?php echo $month;?>)
                        <button type="button" class="btn btn-sm btn-primary float-right mr-1 " data-toggle="modal" data-target="#addIncome">
                            <i class="fa fa-plus" aria-hidden="true"></i> Add Income
                        </button>
                    </h5>
                </div>
                <div class="card-body">
                    <div class="table table-responsive ">
                        <table class="table table-bordered table-sm">
                            <thead>
                                <th class="d-none">Code</th>
                                <th>Description</th>
                                <th>Amount</th>
                                <th>Project</th>
                                <th>Received</th>
                                <th>Cheque No.</th>
                                <th></th>
                            </thead>
                            <tbody>
                    <?php 
                    $xx_cat="SELECT * FROM expected_income 
                            WHERE Income_Status=1 AND MONTH(Income_Date)='$month_no'  AND YEAR(Income_Date)='$year'
                            AND Transaction_Category_Id='$cat_id'";
                    $xx_cat_run=mysqli_query($connection,$xx_cat);
                    if(mysqli_num_rows($xx_cat_run)>0){ $tot_exp=0; $remaining_amt=0;
                        while($row_x=mysqli_fetch_assoc($xx_cat_run)){
                            $ee_id=$row_x['Income_Id'];
                            $x_desc=$row_x['Income_Desc'];
                            $x_amt=$row_x['Income_Amount'];
                            $tot_exp=$tot_exp+$x_amt;
                            $x_output=number_format($x_amt,2);
                            $ee_p=$row_x['Income_Receive_Status'];
                            $prj_id=$row_x['Prj_Id'];
                            $prj_name=prjCode($prj_id);
                            $paid_html=""; $chq_no="";
                            if($ee_p==1){
                                $paid_html='checked';
                                $t_id=$row_x['Transaction_Id'];
                                $chq_no=chqNo($t_id);
                            }
                    ?>
                                <tr>
                                    <td class="d-none"><?php echo $ee_id;?></td>
                                    <td><?php echo $x_desc;?></td>
                                    <td class="text-right font-weight-bold"><?php echo $x_output;?></td>
                                    <td><?php echo $prj_name?></td>
                                    <td class="text-center">
                                        <input type="checkbox" class="received" name="paid" value="<?php echo $ee_id?>" <?php echo $paid_html;?>>
                                        <input type="hidden" value="ee">
                                    </td>
                                    <td class="d-none"><?php echo $prj_id?></td>
                                    <td><?php echo $chq_no;?></td>
                                    <td class="btn-group">
                                        <!-- EDIT -->
                                        <button type="button" class="btn btn-success editEI rounded mr-1">
                                            <i class="fa fa-edit" area-hidden="true"></i>
                                        </button>
                                        <form action="code.php" method="POST">  
                                            <input type="hidden" name="cat_id" id="" value="<?php echo $cat_id;?>">
                                            <input type="hidden" name="month" id="" value="<?php echo $month_no;?>">
                                            <input type="hidden" name="year" id="" value="<?php echo $year;?>">
                                            <input type="hidden" name="ee_id" value="<?php echo $ee_id;?>">  
                                            <button type="submit" name ="delIncome" class="btn btn-danger rounded mr-1">
                                                <i class="fa fa-trash" area-hidden="true"></i>
                                            </button>
                                        </form>
                                        <a href="#" class="btn btn-info btn-circle circle addED d-none">
                                            <i class="fas fa-plus"></i>
                                        </a>
                                        <input type="hidden" class="" value="<?php echo $ee_id;?>">
                                    </td>
                                </tr>
                            <?php 
                        }
                        $tot_exp=number_format($tot_exp,2);
                        ?>
                            <tfoot>
                                <tr>
                                    <td class="d-none"></td>
                                    <td><span class="float-right font-weight-bold">Total:</span> </td>
                                    <td class="text-right font-weight-bold"><?php echo $tot_exp;?></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                            </tfoot>
                        <?php
                    }
                    else{
                        echo 'Message: No Records Found';
                    }
                    ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6">
            <div class="card shadow mb-5">
                <div class="card-header py-3">
                    <h5 class="m-0 font-weight-bold text-primary"> Actual Income</h5>
                </div>
                <div class="card-body">
                    <div class="table table-responsive">
                        <table class="table table-bordered table-sm">
                            <thead>
                                <th class="d-none">Code</th>
                                <th>Bank</th>
                                <th>Cheque No.</th>
                                <th>Description</th>
                                <th>Amount</th>
                            </thead>
                            <tbody>
                    <?php 
                    $q_actual="SELECT * FROM transaction 
                                WHERE Transaction_Status=1 AND YEAR(Transaction_Date)='$year' 
                                AND MONTH(Transaction_Date)='$month_no' AND Transaction_Category_Id='$cat_id'
                                AND Transaction_Amount>0 AND Transaction_Cancel_Status=0";
                    $q_actual_run=mysqli_query($connection,$q_actual);

                    if(mysqli_num_rows($q_actual_run)>0){ $tot_t=0;
                        while($rowc=mysqli_fetch_assoc($q_actual_run)){
                            $t_cat_id=$rowc['Transaction_Category_Id'];
                            $t_desc=$rowc['Transaction_Details'];
                            $t_acc_id=$rowc['Account_Id'];
                            $t_bank_code= bankCode($t_acc_id);
                            $t_amt=$rowc['Transaction_Amount'];
                            $t_prj_id=$rowc['Prj_Id'];
                            $tot_t=$tot_t+$t_amt;
                            $t_amt=abs($t_amt);
                            $t_amt_amount=number_format($t_amt,2);
                            $t_chq_no=$rowc['Transaction_Cheque_No'];
                    ?>
                                <tr>
                                    <!-- <td class="d-none"></td> -->
                                    <td><?php echo $t_bank_code;?></td>
                                    <td><?php echo $t_chq_no;?></td>
                                    <td><?php echo $t_desc;?></td>
                                    <td class="text-right"><?php echo $t_amt_amount;?></td>
                                </tr>
                    <?php 
                        }
                        $tot_t=abs($tot_t);
                        $tot_t=number_format($tot_t,2);
                        ?>
                            <tfoot>
                                <tr>
                                    <td></td>
                                    <td></td>
                                    <td><span class="float-right font-weight-bold">Total:</span> </td>
                                    <td class="text-right font-weight-bold"><?php echo $tot_t;?></td>
                                </tr>
                            </tfoot>
                        <?php
                    }
                    else{
                        echo 'No found accounts';
                    }
                    ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Modal Add income-->
<div class="modal fade bd-example-modal-xl" id="addIncome" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl" role="document">
    <div class="modal-content">
      <div class="modal-header">
          <!-- MODAL TITLE -->
        <h5 class="modal-title" id="exampleModalLabel"><i class="fa fa-clipboard" aria-hidden="true"></i> Add Expected Income - <?php echo $cat_desc;?></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
    <form action="code.php" method="POST">
      <div class="modal-body">
        <!-- THE FORM -->
            <div class="form-row mt-2">
                <div class="col-6">
                    <div class="form-group">
                        <label for="">Description: <i class="ml-3">i.e. Payroll, Subcontractor</i> </label> 
                        <input type="text" name="details" class="form-control">
                    </div>
                </div>
                <div class="col-3">
                    <label for="">Amount:</label>
                    <input type="decimal" name="amount" class="form-control">
                </div>
                <div class="col-3">
                    <label for="">Project:</label>
                    <select name="prj_id" id="prj_opt" class="form-control"></select>
                </div>
                <input type="hidden" name="year" value="<?php echo $year;?>">
                <input type="hidden" name="month" value="<?php echo $month_no;?>">
                <input type="hidden" name="cat_id" value="<?php echo $cat_id;?>">
            </div>
        <!-- END FORM -->
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-times" aria-hidden="true"></i> Close</button>
        <button type="submit" name="addIncome" class="btn btn-success"><i class="fa fa-check" aria-hidden="true"></i> Save</button>
      </div>
      </form>
    </div>
  </div>
</div>
<!-- End Modal ADD income-->
<!-- Modal Edit Income-->
<div class="modal fade bd-example-modal-xl" id="editEI" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl" role="document">
    <div class="modal-content">
      <div class="modal-header">
          <!-- MODAL TITLE -->
        <h5 class="modal-title" id="exampleModalLabel"><i class="fa fa-clipboard" aria-hidden="true"></i> Edit Income</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
    <form action="code.php" method="POST">
      <div class="modal-body">
        <!-- THE FORM -->
            <input type="hidden" name="ee_id" id="ee_id" name="exp_id">
            <div class="form-row mt-2">
                <div class="col-6">
                    <div class="form-group">
                        <label for="">Description: <i class="ml-3">i.e. Payroll, Subcontractor</i> </label> 
                        <input type="text" name="desc" id="e_desc" class="form-control">
                    </div>
                </div>
                <div class="col-3">
                    <label for="">Amount:</label>
                    <input type="decimal" name="amount" id="e_amt" class="form-control">
                </div>
                <div class="col-3">
                    <label for="">Project:</label>
                    <select name="prj_id" id="ee_prj_id" class="form-control prj_opt"></select>
                </div>
                <input type="hidden" name="year" value="<?php echo $year;?>">
                <input type="hidden" name="month" value="<?php echo $month_no;?>">
                <input type="hidden" name="cat_id" value="<?php echo $cat_id;?>">
            </div>
        <!-- END FORM -->
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-times" aria-hidden="true"></i> Close</button>
        <button type="submit" name="editIncome" class="btn btn-success"><i class="fa fa-check" aria-hidden="true"></i> Save</button>
      </div>
      </form>
    </div>
  </div>
</div>
<!-- End Modal edit income -->
<!-- Modal add chq no-->
<div class="modal fade bd-example-modal-md" id="addChqNo" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-md" role="document">
    <div class="modal-content">
      <div class="modal-header">
          <!-- MODAL TITLE -->
        <h5 class="modal-title" id="exampleModalLabel"><i class="fa fa-clipboard" aria-hidden="true"></i> Cheque Number</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
    <form action="code.php" method="POST">
      <div class="modal-body" style="overflow: visible;">
        <!-- THE FORM -->
            <input type="hidden" name="type" id="type" value="">
            <input type="hidden" name="id" id="id" value="">
            <div class="form-row mt-2">
                <div class="col-5 d-none">
                    <div class="form-group">
                        <label for="">Select Bank</label> 
                        <select name="" id="bank_opt" class="form-control"></select>
                    </div>
                </div>
                <div class="col-5">
                    <div class="form-group">
                        <label for="">Select Cheque Number </label> 
                        <select name="t_id" id="chq_opt" class="form-control "></select>
                    </div>
                </div>
            </div>
            <input type="hidden" name="year" value="<?php echo $year;?>">
            <input type="hidden" name="month" value="<?php echo $month_no;?>">
            <input type="hidden" name="cat_id" value="<?php echo $cat_id;?>">
        <!-- END FORM -->
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-times" aria-hidden="true"></i> Close</button>
        <button type="submit" name="rChqNo" class="btn btn-success"><i class="fa fa-check" aria-hidden="true"></i> Received </button>
      </div>
      </form>
    </div>
  </div>
</div>
<!-- End Modal edit expected desc-->
<script>
var month_opt="1";
$.ajax({
    url:'options.php',
    method: 'POST',
    data:{'month_opt': month_opt},
    success:function(data){
        $(document).find('#month').html(data).change();
    }
});
$.ajax({
    url:'options.php',
    method: 'POST',
    data:{'yr_opt': month_opt},
    success:function(data){
        $(document).find('#year').html(data).change();
    }
});
$(document).ready(function(){
    $.ajax({
    url:'options.php',
    method: 'POST',
    data:{'cat_opt': month_opt},
    success:function(data){
            $(document).find('#cat_opt').html(data).change();
        }
    });
});
$.ajax({
    url:'options.php',
    method: 'POST',
    data:{'prj_opt':month_opt},
    success:function(data){
        $(document).find('#prj_opt').html(data).change();
        $(document).find('#ee_prj_id').html(data).change();
    }
});
$(document).ready(function () {
    $(document).on('click', '.editEI', function() {
        $('#editEI').modal('show');
        $tr = $(this).closest('tr');
        var data = $tr.children("td").map(function(){
            return $(this).text();
        }).get();
       $('#ee_id').val(data[0]);
       $('#e_desc').val(data[1]);
       $('#e_amt').val(data[2]);
       $('#ee_prj_id').val(data[5]);
    });
});
$(document).ready(function(){
    var cat_id=$('#cat_id_in').val();
    $.ajax({
    url:'accQuery.php',
    method: 'POST',
    data:{'chq_opt_r': cat_id,
            'cat_id_r': cat_id},
    success:function(data){
            $(document).find('#chq_opt').html(data).change();
            $('#chq_opt').selectpicker('refresh');
            // alert(data);
        }
    });
});
$(document).ready(function(){
    $('.received').on('click', function () {
        //check if check or not
        var id=$(this).val();
        var type= $(this).next('input').val();
        if($(this).prop('checked')){
            var cat_id=$('#cat_id_in').val();
            var month=$('#month_in').val();
            var year=$('#year_in').val();
            
            $('#id').val(id);
            $('#addChqNo').modal('show');
        }
        else{
            $.ajax({
            url:'code.php',
            method: 'POST',
            data:{'remove_r': type,
                'id': id},
            success:function(data){
                location.reload();}
            });
        }
    });
});
</script>
<?php
include('../includes/scripts.php');
include('../includes/footer.php');
?>