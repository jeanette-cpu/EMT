<?php
include('security.php');
include('includes/header.php');
include('includes/navbar.php');
?>
<div class="container-fluid">
    <div class="card shadow mb-5">
        <div class="card-header py-3">
            <h5 class="m-0 font-weight-bold text-primary">Import</h5>
        </div>
        <div class="card-body">
            <div class="mb-3">
                <form action="code.php" method="post" enctype="multipart/form-data">                     
                        <h6 class="text-primary">Payslip</h6>
                        <input type="file" name="file" required/>
                        <input class="mt-1" type="submit" name="submit" value="Import"/>
                </form>
                <form action="timesheet_queries.php" method="post" enctype="multipart/form-data">       
                        <h6 class="text-primary mt-4">Time Sheet</h6>
                        <input type="file" name="file" required/>
                        <input class="mt-1" type="submit" name="ts_import" value="Import"/>
                </form>
            <br>
            <label class="mt-3"><b>NOTE : </b>Upload CSV File Only </label>
            </div> 
        </div>
    </div>
    <div class="card shadow mb-5">
        <div class="card-header py-3">
            <h5 class="m-0 font-weight-bold text-primary">Bulk Delete Timesheet</h5>
        </div>
        <div class="card-body">
            <div class="mb-3">
                <label for="" class="font-weight-bold" >Timesheet</label>
                <form action="timesheet_queries.php" method="post" onsubmit="return confirm('Are you sure you want delete timesheets?');">
                    <div class="form-row">
                        <div class="col-3">
                            <label for="">Month</label>    
                            <select class="form-control" name="month" id="">
                                <option value="01">January</option>
                                <option value="02">February</option>
                                <option value="03">March</option>
                                <option value="04">April</option>
                                <option value="05">May</option>
                                <option value="06">June</option>
                                <option value="07">July</option>
                                <option value="08">August</option>
                                <option value="09">September</option>
                                <option value="10">October</option>
                                <option value="11">November</option>
                                <option value="12">December</option>
                            </select>
                        </div>
                        <div class="col-3">
                            <label for="">Year</label>    
                            <select class="form-control" name="year" id="">
                                <option value="2020">2020</option>
                                <option value="2021">2021</option>
                                <option value="2022">2022</option>
                                <option value="2023">2023</option>
                                <option value="2024">2024</option>
                                <option value="2025">2025</option>
                                <option value="2026">2026</option>
                                <option value="2027">2027</option>
                            </select>
                        </div>
                        <div class="col-3 mt-4 pt-1">
                            <button type="submit" name="bulk_delete" class="btn btn-primary">Delete</button>
                        </div>
                    </div>
                </form>
            </div> 
        </div>
    </div>
    <div class="card shadow mb-5">
        <div class="card-header py-3">
            <h5 class="m-0 font-weight-bold text-primary">Bulk Delete Payslip</h5>
        </div>
        <div class="card-body">
            <div class="mb-3">
                <label for="" class="font-weight-bold" >Payslip</label>
                <form action="timesheet_queries.php" method="post" onsubmit="return confirm('Are you sure you want delete payslips?');">
                    <div class="form-row">
                        <div class="col-3">
                            <label for="">Month</label>    
                            <select class="form-control" name="month" id="">
                                <option value="01">January</option>
                                <option value="02">February</option>
                                <option value="03">March</option>
                                <option value="04">April</option>
                                <option value="05">May</option>
                                <option value="06">June</option>
                                <option value="07">July</option>
                                <option value="08">August</option>
                                <option value="09">September</option>
                                <option value="10">October</option>
                                <option value="11">November</option>
                                <option value="12">December</option>
                            </select>
                        </div>
                        <div class="col-3">
                            <label for="">Year</label>    
                            <select class="form-control" name="year" id="">
                                <option value="2020">2020</option>
                                <option value="2021">2021</option>
                                <option value="2022">2022</option>
                                <option value="2023">2023</option>
                                <option value="2024">2024</option>
                                <option value="2025">2025</option>
                                <option value="2026">2026</option>
                                <option value="2027">2027</option>
                            </select>
                        </div>
                        <div class="col-3 mt-4 pt-1">
                            <button type="submit" name="bulk_payslip" class="btn btn-primary">Delete</button>
                        </div>
                    </div>
                </form>
            </div> 
        </div>
    </div>
</div>
<?php
include('includes/scripts.php');
include('includes/footer.php');
?>