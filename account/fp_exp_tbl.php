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
    <form action="fp_exp_tbl.php" method="GET">
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
        <div class="col-12">
            <div class="card shadow mb-5">
                <div class="card-header py-3">
                    <h5 class="m-0 font-weight-bold text-primary">This month estimated expense</h5>
                </div>
                <div class="card-body">
                    <div class="table table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <th class="d-none"></th>
                                <th>Description</th>
                                <th>Amount</th>
                                <th>Included</th>
                            </thead>
                            <tbody>
                        <?php 
                            $p_arr=NULL; $p_ids=NULL;
                            //monthly 
                            $m="SELECT Plan_Id FROM payment_plan WHERE Frequency='Monthly' AND Plan_Status=1 AND Transaction_Category_Id='$cat_id'";  
                            $m_run=mysqli_query($connection,$m);
                            if(mysqli_num_rows($m_run)>0){
                                while($row_m=mysqli_fetch_assoc($m_run)){
                                    $p_arr[]=$row_m['Plan_Id'];
                                }
                            }
                            //yearly 
                            $y="SELECT Plan_Id FROM payment_plan WHERE Frequency='Yearly' AND MONTH(`Y_Date`)='$month_no' AND Transaction_Category_Id='$cat_id'";  
                            $y_run=mysqli_query($connection,$y);
                            if(mysqli_num_rows($y_run)>0){
                                while($row_y=mysqli_fetch_assoc($y_run)){
                                    $p_arr[]=$row_y['Plan_Id'];
                                }
                            }
                            //quarter 
                            $q="SELECT Plan_Id FROM payment_plan WHERE Frequency='Quarterly' AND Transaction_Category_Id='$cat_id' AND Plan_Status=1 OR 
                                MONTH(`Q1_Date`)='$month_no' OR MONTH(`Q2_Date`)='$month_no' OR MONTH(`Q3_Date`)='$month_no' OR MONTH(`Q4_Date`)='$month_no'
                                ";  
                            $q_run=mysqli_query($connection,$q);
                            if(mysqli_num_rows($q_run)>0){
                                while($row_q=mysqli_fetch_assoc($q_run)){
                                    $p_arr[]=$row_q['Plan_Id'];
                                }
                            }
                            if($p_arr){
                                $p_ids=implode("', '",$p_arr);
                            }
                            $q_plan="SELECT * FROM payment_plan WHERE Plan_Id IN ('$p_ids')";
                            $q_plan_run=mysqli_query($connection,$q_plan);
                            if(mysqli_num_rows($q_plan_run)>0){
                                while($row_p=mysqli_fetch_assoc($q_plan_run)){
                                    $p_id=$row_p['Plan_Id'];
                                    $p_desc=$row_p['Plan_Desc'];
                                    $pl_amt=$row_p['Plan_Amount'];
                                    $pl_amt=number_format($pl_amt,2);
                                    //checked
                                    $q_inc="SELECT * FROM expected_expense WHERE Exp_Status=1 AND MONTH(Exp_Date)='$month_no' 
                                            AND YEAR(Exp_Date)='$year' AND Transaction_Category_Id='$cat_id' AND Plan_Id='$p_id'";
                                    $q_inc_run=mysqli_query($connection,$q_inc);
                                    $chk_html="";
                                    if(mysqli_num_rows($q_inc_run)==1){
                                        $chk_html="checked";
                                    }
                        ?>
                                <tr>
                                    <td class="d-none"><?php echo $p_id;?></td>
                                    <td><?php echo $p_desc;?></td>
                                    <td class="text-right"><?php echo $pl_amt;?></td>
                                    <td>
                                        <input type="checkbox" class="include" name="included" value="<?php echo $p_id;?>" <?php echo $chk_html;?>>
                                    </td>
                                </tr>
                        <?php 
                                }
                            }
                            else{
                                ?>
                                <tr>
                                    <td colspan="3">No Records Found</td>
                                </tr>
                                <?php
                            }
                        ?>
                                
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-6">
            <div class="card shadow mb-5">
                <div class="card-header py-3">
                    <h5 class="m-0 font-weight-bold text-primary"> Expected Expense (<?php echo $month;?>)
                        <button type="button" class="btn btn-sm btn-primary float-right mr-1 " data-toggle="modal" data-target="#addexp">
                            <i class="fa fa-plus" aria-hidden="true"></i> Add Expense
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
                                <th>Paid</th>
                                <th>Cheque No.</th>
                                <th></th>
                            </thead>
                            <tbody>
                    <?php 
                    $xx_cat="SELECT * FROM expected_expense 
                            WHERE Exp_Status=1 AND MONTH(Exp_Date)='$month_no'  AND YEAR(Exp_Date)='$year'
                            AND Transaction_Category_Id='$cat_id'";
                    $xx_cat_run=mysqli_query($connection,$xx_cat);
                    if(mysqli_num_rows($xx_cat_run)>0){ $tot_exp=0; $remaining_amt=0;
                        while($row_x=mysqli_fetch_assoc($xx_cat_run)){
                            $ee_id=$row_x['Expense_Id'];
                            $x_desc=$row_x['Exp_Desc'];
                            $x_amt=$row_x['Exp_Amount'];
                            $tot_exp=$tot_exp+$x_amt;
                            $x_output=number_format($x_amt,2);
                            $ee_p=$row_x['Exp_Paid_Status'];
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
                                        <input type="checkbox" class="paid" name="paid" value="<?php echo $ee_id?>" <?php echo $paid_html;?>>
                                        <input type="hidden" value="ee">
                                    </td>
                                    <td class="d-none"><?php echo $prj_id?></td>
                                    <td><?php echo $chq_no;?></td>
                                    <td class="btn-group">
                                        <!-- EDIT -->
                                        <button type="button" class="btn btn-success editEE rounded mr-1">
                                            <i class="fa fa-edit" area-hidden="true"></i>
                                        </button>
                                        <form action="code.php" method="POST">  
                                            <input type="hidden" name="cat_id" id="" value="<?php echo $cat_id;?>">
                                            <input type="hidden" name="month" id="" value="<?php echo $month_no;?>">
                                            <input type="hidden" name="year" id="" value="<?php echo $year;?>">
                                            <input type="hidden" name="ee_id" value="<?php echo $ee_id;?>">  
                                            <button type="submit" name ="delExp" class="btn btn-danger rounded mr-1">
                                                <i class="fa fa-trash" area-hidden="true"></i>
                                            </button>
                                        </form>
                                        <a href="#" class="btn btn-info btn-circle circle addED">
                                            <i class="fas fa-plus"></i>
                                        </a>
                                        <input type="hidden" class="" value="<?php echo $ee_id;?>">
                                    </td>
                                </tr>
                            <?php 
                            //search for breakdown
                            $q_expense_div="SELECT * FROM expense_division WHERE ED_Status=1 AND Expense_Id='$ee_id'";
                            $q_expense_div_run=mysqli_query($connection,$q_expense_div);
                            if(mysqli_num_rows($q_expense_div_run)>0){
                                $remaining_amt=$x_amt;
                                while($row_ed=mysqli_fetch_assoc($q_expense_div_run)){
                                    $ed_id=$row_ed['Exp_Div_Id'];
                                    $ed_desc=$row_ed['ED_Desc'];
                                    $ed_amt=$row_ed['ED_Amount'];
                                    $remaining_amt=$remaining_amt-$ed_amt;
                                    $paid_html1=""; $ed_chq_no="";
                                    $ed_output=number_format($ed_amt,2);
                                    $ed_p=$row_ed['ED_Paid_Status'];
                                    if($ed_p==1){
                                        $paid_html1="checked";
                                        $ed_t_id=$row_ed['Transaction_Id'];
                                        $ed_chq_no=chqNo($ed_t_id);
                                    }
                                    ?> 
                                    <tr>
                                        <td class="d-none"><?php echo $ed_id;?></td>
                                        <td class="text-right"><?php echo $ed_desc;?></td>
                                        <td class="text-right"><?php echo $ed_output;?></td>
                                        <td> <span class="d-none"><?php echo $ee_id;?></span></td>
                                        <td class="text-center">
                                            <input type="checkbox" class="paid" name="paid" value="<?php echo $ed_id;?>" <?php echo $paid_html1;?>>
                                            <input type="hidden" value="ed">
                                        </td>
                                        <td><?php echo $ed_chq_no;?></td>
                                        <td class="btn-group">
                                            <!-- EDIT -->
                                            <button type="button" class="btn btn-success editED rounded mr-1">
                                                <i class="fa fa-edit" area-hidden="true"></i>
                                            </button>
                                            <form action="code.php" method="POST">  
                                                <input type="hidden" name="cat_id" id="" value="<?php echo $cat_id;?>">
                                                <input type="hidden" name="month" id="" value="<?php echo $month_no;?>">
                                                <input type="hidden" name="year" id="" value="<?php echo $year;?>">
                                                <input type="hidden" name="ed_id" value="<?php echo $ed_id;?>">  
                                                <button type="submit" name ="delED" class="btn btn-danger rounded mr-1">
                                                    <i class="fa fa-trash" area-hidden="true"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                    <?php
                                }
                                $remaining_amt=number_format($remaining_amt,2);
                                if($remaining_amt>0){
                                    ?>
                                    <tr>
                                        <td class="d-none"></td>
                                        <td class="text-right">Uncategorized</td>
                                        <td class="text-right"><?php echo $remaining_amt;?></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                    <?php 
                                }
                            }
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
                    <h5 class="m-0 font-weight-bold text-primary"> Actual Expense</h5>
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
                                AND Transaction_Amount<0 AND Transaction_Cancel_Status=0" ;
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
<!-- Modal Add Expected-->
<div class="modal fade bd-example-modal-xl" id="addexp" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl" role="document">
    <div class="modal-content">
      <div class="modal-header">
          <!-- MODAL TITLE -->
        <h5 class="modal-title" id="exampleModalLabel"><i class="fa fa-clipboard" aria-hidden="true"></i> Add Expected Expense - <?php echo $cat_desc;?></h5>
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
        <button type="submit" name="addExp" class="btn btn-success"><i class="fa fa-check" aria-hidden="true"></i> Save</button>
      </div>
      </form>
    </div>
  </div>
</div>
<!-- End Modal ADD expected-->
<!-- Modal Edit expected-->
<div class="modal fade bd-example-modal-xl" id="editEE" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl" role="document">
    <div class="modal-content">
      <div class="modal-header">
          <!-- MODAL TITLE -->
        <h5 class="modal-title" id="exampleModalLabel"><i class="fa fa-clipboard" aria-hidden="true"></i> Edit Expense</h5>
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
        <button type="submit" name="editExp" class="btn btn-success"><i class="fa fa-check" aria-hidden="true"></i> Save</button>
      </div>
      </form>
    </div>
  </div>
</div>
<!-- End Modal edit expected -->
<!-- Modal Add Expense Division-->
<div class="modal fade bd-example-modal-lg" id="addED" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
          <!-- MODAL TITLE -->
        <h5 class="modal-title" id="exampleModalLabel"><i class="fa fa-clipboard" aria-hidden="true"></i> Add Expense Sub Description</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
    <form action="code.php" method="POST">
      <div class="modal-body">
        <input type="hidden" name="ee_id" id="ee_id1" >
        <!-- THE FORM -->
            <div class="form-row mt-2">
                <div class="col-8">
                    <div class="form-group">
                        <label for="">Description: <i class="ml-3">i.e. Payroll, Subcontractor</i> </label> 
                        <input type="text" name="desc" class="form-control" required>
                    </div>
                </div>
                <div class="col-3">
                    <label for="">Amount:</label>
                    <input type="decimal" name="amount" class="form-control" required>
                </div>
                <input type="hidden" name="year" value="<?php echo $year;?>">
                <input type="hidden" name="month" value="<?php echo $month_no;?>">
                <input type="hidden" name="cat_id_ed" value="<?php echo $cat_id;?>">
            </div>
        <!-- END FORM -->
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-times" aria-hidden="true"></i> Close</button>
        <button type="submit" name="addED" class="btn btn-success"><i class="fa fa-check" aria-hidden="true"></i> Save</button>
      </div>
      </form>
    </div>
  </div>
</div>
<!-- End Modal ADD expected-->
<!-- Modal Edit expected desc-->
<div class="modal fade bd-example-modal-xl" id="editED" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl" role="document">
    <div class="modal-content">
      <div class="modal-header">
          <!-- MODAL TITLE -->
        <h5 class="modal-title" id="exampleModalLabel"><i class="fa fa-clipboard" aria-hidden="true"></i> Edit Sub Description</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
    <form action="code.php" method="POST">
      <div class="modal-body">
        <!-- THE FORM -->
            <input type="hidden" name="ed_id" id="ed_id" >
            <input type="hidden" name="ee_id" id="e_ee_id" >
            <div class="form-row mt-2">
                <div class="col-8">
                    <div class="form-group">
                        <label for="">Description: <i class="ml-3">i.e. Payroll, Subcontractor</i> </label> 
                        <input type="text" name="desc" id="ed_desc" class="form-control">
                    </div>
                </div>
                <div class="col-3">
                    <label for="">Amount:</label>
                    <input type="decimal" name="amount" id="ed_amt" class="form-control">
                </div>
                <input type="hidden" name="year" value="<?php echo $year;?>">
                <input type="hidden" name="month" value="<?php echo $month_no;?>">
                <input type="hidden" name="cat_id" value="<?php echo $cat_id;?>">
            </div>
        <!-- END FORM -->
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-times" aria-hidden="true"></i> Close</button>
        <button type="submit" name="editED" class="btn btn-success"><i class="fa fa-check" aria-hidden="true"></i> Save</button>
      </div>
      </form>
    </div>
  </div>
</div>
<!-- End Modal edit expected desc-->
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
                        <select name="t_id" id="chq_opt" class="form-control " ></select>
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
        <button type="submit" name="addChqNo" class="btn btn-success"><i class="fa fa-check" aria-hidden="true"></i> Paid</button>
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
$.ajax({
    url:'options.php',
    method: 'POST',
    data:{'prj_opt':month_opt},
    success:function(data){
        $(document).find('#prj_opt').html(data).change();
        $(document).find('#ee_prj_id').html(data).change();
    }
});
$.ajax({
        url:'options.php',
        method: 'POST',
        data:{'bank_opt': month_opt},
        success:function(data){
            $(document).find('#bank_opt').html(data).change();
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
$(document).ready(function(){
    var cat_id=$('#cat_id_in').val();
    $.ajax({
    url:'accQuery.php',
    method: 'POST',
    data:{'chq_opt_p': cat_id,
            'cat_id_p': cat_id},
    success:function(data){
            $(document).find('#chq_opt').html(data).change();
            $('#chq_opt').selectpicker('refresh');
            // alert(data);
        }
    });
});
$(document).ready(function(){
    $('.include').on('click', function () {
        //check if check or not
        var plan_id=$(this).val();
        // alert($(this).val());
        var cat_id=$('#cat_id_in').val();
        var month=$('#month_in').val();
        var year=$('#year_in').val();
        // alert(plan_id);
        if($(this).prop('checked')){
            // alert('add');
            $.ajax({
            url:'code.php',
            method: 'POST',
            data:{'cat_id': cat_id,
                    'month':month,
                    'year':year,
                    'p_id':plan_id},
            success:function(data){
                location.reload();}
            });
        }
        else{
            // alert('removed');
            $.ajax({
            url:'code.php',
            method: 'POST',
            data:{'cat_id_r': cat_id,
                    'month':month,
                    'year':year,
                    'p_id':plan_id},
            success:function(data){
                location.reload();}
            });
        }
    });
});
$(document).ready(function () {
    $(document).on('click', '.editEE', function() {
        $('#editEE').modal('show');
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
$(document).ready(function () {
    $(document).on('click', '.addED', function() {
        $('#addED').modal('show');
        var ee_id = $(this).next('input').val();
       $('#ee_id1').val(ee_id);
    });
});
$(document).ready(function () {
    $(document).on('click', '.editED', function() {
        $('#editED').modal('show');
        $tr = $(this).closest('tr');
        var data = $tr.children("td").map(function(){
            return $(this).text();
        }).get();
       $('#ed_id').val(data[0]);
       $('#ed_desc').val(data[1]);
       $('#ed_amt').val(data[2]);
       $('#e_ee_id').val(data[3]);
       
    });
});
$(document).ready(function(){
    $('.bank_opt').on('change', function () {
        var bank_id=$(this).val();
        $.ajax({
            url:'options.php',
            method: 'POST',
            data:{'cat_opt': month_opt},
        success:function(data){
                $(document).find('#c').html(data).change();
            }
        });
    });
});
$(document).ready(function(){
    $('.paid').on('click', function () {
        //check if check or not
        var id=$(this).val();
        var type= $(this).next('input').val();
        if($(this).prop('checked')){
            var cat_id=$('#cat_id_in').val();
            var month=$('#month_in').val();
            var year=$('#year_in').val();
            
            $.ajax({
            url:'code.php',
            method: 'POST',
            data:{'cat_id_p': cat_id,
                    'id':id},
            success:function(data){
                   
                    // location.reload();
                }
            });
            $('#id').val(id);
            $('#type').val(type);
            $('#addChqNo').modal('show');
        }
        else{
            $.ajax({
            url:'code.php',
            method: 'POST',
            data:{'remove_paid': type,
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