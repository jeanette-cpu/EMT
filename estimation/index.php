<?php
include('../security.php');
include('../includes/header.php'); 
include('../includes/estimation_nav.php');
error_reporting(0);
include('est_queries.php');
if(isset($_POST['search'])){
    $year=$_POST['year'];
    $year_qP="AND YEAR(PE_Date)='$year'";
}
else{ 
    $year = date('Y');
    $year_qP="AND YEAR(PE_Date)='$year'"; 
}
$noProjects="SELECT Prj_Est_Id FROM project_estimation WHERE PE_Status=1 $year_qP";
$noProjects_run=mysqli_query($connection,$noProjects);
$prjNo=0;
if(mysqli_num_rows($noProjects_run)>0){ 
    while($row_p=mysqli_fetch_assoc($noProjects_run)){
        $prjNo++;
        $prj_ids[]=$row_p['Prj_Est_Id'];
    }
    $pids=implode("', '", $prj_ids);
    $p_q="AND Prj_Est_Id IN ('$pids')";

    $Q_Stat_Id=quotedStatId();
    $noQuoted="SELECT COUNT(Estimate_Id) as quoted FROM estimate WHERE Est_Status=1 AND Estimate_Status_Id='$Q_Stat_Id' $p_q";
    $noQuoted_run=mysqli_query($connection,$noQuoted);

    $awardStatId=wonStatId();
    $noAwarded="SELECT COUNT(Estimate_Id) as awarded FROM estimate WHERE Est_Status=1 AND Estimate_Status_Id='$awardStatId' $p_q";
    $noAwarded_run=mysqli_query($connection,$noAwarded);

    $row_q=mysqli_fetch_assoc($noQuoted_run);
    $qNo=$row_q['quoted'];
    $row_a=mysqli_fetch_assoc($noAwarded_run);
    $awardNo=$row_a['awarded'];
}
else{ $p_q=""; $qNo=0; $awardNo=0;}
//target number
$target_no="SELECT Target_Prj_No FROM target WHERE Target_Status=1 AND YEAR(Target_Date)='$year'";
$target_run1=mysqli_query($connection,$target_no);
$row_t=mysqli_fetch_assoc($target_run1);
$targetNo=$row_t['Target_Prj_No'];
//status 
$q_stat="SELECT COUNT(pe.Prj_Est_Id) AS no, es.Est_Status, es.Estimate_Status_Id
        FROM project_estimation AS pe
        LEFT JOIN estimate as e on e.Prj_Est_Id=pe.Prj_Est_Id
        LEFT JOIN estimate_status as es on es.Estimate_Status_Id=e.Estimate_Status_Id
        WHERE pe.PE_Status=1 AND YEAR(PE_Date)='$year' AND e.Est_Status=1 AND es.Est_Status_Status=1 
        GROUP BY es.Estimate_Status_Id;";
$q_stat_run=mysqli_query($connection,$q_stat);
$stat_label=NULL; $snos=NULL; $pielbl=NULL;
if(mysqli_num_rows($q_stat_run)>0){
    while($row_s=mysqli_fetch_assoc($q_stat_run)){
        $sno=$row_s['no'];
        $stat_name=$row_s['Est_Status'];
        $es_id=$row_s['Estimate_Status_Id'];
        // echo $sno.$stat_name.$es_id.'<br>'; 
        $stat_label.='"'.$stat_name.'",';
        $pielbl[]=$stat_name;
        $pieData[]=$sno;
        $es_ids[]=$es_id;
        $snos.=$sno.",";
    }
}
$colors=["#4FC98A","#F6C23E","#4E73DF","36B9CC","#87bc45","#ea5545", "#27aeef", "#edbf33", "#b33dc6"];
// echo $stat_label; echo $snos;
?>
<div class="container-fluid">   
    <form action="index.php" method="post">
    <div class="row mb-4">
        <div class="col-2">
            <label for="">Year</label>
            <select name="year" id="yr_opt" class="form-control"></select>
        </div>
        <div class="col-2">
            <label for="" class="invisible">P</label><br>
            <button class="btn btn-warning" name="search">Search</button>
        </div>
        <div class="col-3 text-right" >
            <label for="" class="invisible">P</label><br>
            <h4 class="mt-1"><label for="">Year-<?php echo $year;?></label></h4>
        </div>
    </div>
    </form>
    <div class="row">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                        <div class="text-s font-weight-bold text-info text-uppercase mb-1">Projects</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            <form action="est_project.php" method="post">
                                <input type="hidden" name="year[]" value="<?php echo $year;?>">
                            <?php  echo'<button class="btn" type="submit" name="search"><h2>'.$prjNo.'</h2></button>
                        </form>'; ?>
                        </div>
                        </div>
                        <div class="col-auto">
                        <i class="fas fa-fw fa-building fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                        <div class="text-s font-weight-bold text-success text-uppercase mb-1">Awarded</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                <form action="est_project.php" method="post">
                                    <input type="hidden" name="year[]" value="<?php echo $year;?>">
                                    <input type="hidden" name="stat_id[]" value="<?php echo $awardStatId;?>">
                                    <button class="btn" type="submit" name="search"><?php  echo'<h2>'.$awardNo.'</h2>'; ?></button>
                                </form>
                            </div>
                        </div>
                        <div class="col-auto">
                        <i class="fas fa-fw fa-trophy fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                        <div class="text-s font-weight-bold text-warning text-uppercase mb-1">Quoted</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                <form action="est_project.php" method="post">
                                    <input type="hidden" name="year[]" value="<?php echo $year;?>">
                                    <input type="hidden" name="stat_id[]" value="<?php echo $Q_Stat_Id;?>">
                                    <button class="btn" type="submit" name="search"><?php  echo'<h2>'.$qNo.'</h2>'; ?></button>
                                </form>
                            </div>
                        </div>
                        <div class="col-auto">
                        <i class="fas fa-fw fa-pencil-square-o fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-danger shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                        <div class="text-s font-weight-bold text-danger text-uppercase mb-1">Target</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                <h2 ><a href="s_target.php" class="text-secondary mt-2"><?php echo $targetNo?></a></h2>
                            </div>
                        </div>
                        <div class="col-auto">
                        <i class="fas fa-fw fa-bullseye fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php 
    $currentMonth = date('Y-m');  // Get the current month in the format 'YYYY-MM'
    $balances=NULL; $balance_arr=[]; $months=null; $colorMapping = [];
    for ($i = 0; $i < 12; $i++) {
        $syear = date('Y', strtotime("$year-01 + $i months"));
        $smonth = date('m', strtotime("$year-01 + $i months"));
        $smonth1 = date('M', strtotime("$year-01 + $i months"));

        $q_prjs="SELECT count(Prj_Est_Id) as prj_no FROM project_estimation WHERE PE_Status=1 AND YEAR(PE_Date)='$syear' AND MONTH(PE_Date)='$smonth'";
        $q_prjs_run=mysqli_query($connection,$q_prjs);
        $row_c=mysqli_fetch_assoc($q_prjs_run);

        $balances.=$row_c['prj_no'].",";
        $balance_arr[]=$row_c['prj_no'];
        $months.='"'.$smonth1.'",';
    }
    ?>
    <script>
        var maxValue=<?php echo max($balance_arr)?>;
        var newDataset=[<?php echo $balances;?>];
        var newLabel=[<?php echo $months?>];
        var pieLabel=[<?php echo $stat_label;?>];
        var pieData=[<?php echo $snos;?>];
        
    </script>
    <div class="row">
        <div class="col-7">
            <div class="card shadow mb-5">
                <div class="card-header py-3">
                    <h5 class="m-0 font-weight-bold text-primary">QUOTED PROJECTS</h5>
                </div>
                <div class="card-body">
                    <div class="chart-bar">
                        <canvas id="myBarChart"></canvas>
                    </div>
                    <hr>
                </div>
            </div>
        </div>
        <div class="col-5">
            <div class="card shadow mb-5">
                <div class="card-header py-3">
                    <h5 class="m-0 font-weight-bold text-primary">Status</h5>
                </div>
                <div class="card-body">
                    <div class="form-row">
                        <div class="col-4 p-3">
                        
                        <?php 
                        if($pielbl){
                            for ($i = 0; $i < count($pielbl); $i++) {
                                $colorMapping[$pielbl[$i]] = $colors[$i % count($colors)];
                                echo'                           
                                <div class="row">
                                    <div class="col-8">
                                        <form action="est_project.php" method="post">
                                            <input type="hidden" name="year[]" value="'.$year.'">
                                            <input type="hidden" name="stat_id[]" value="'.$es_ids[$i].'">
                                            <button class="btn btn-link text-secondary" type="submit" name="search">
                                                <i class="fas fa-square" style="color: '.$colors[$i % count($colors)].'"></i>  '.$pielbl[$i].'
                                            </button>
                                        </form>
                                    </div>
                                    <div class="col-4 text-right">
                                        '.$pieData[$i].'
                                    </div>
                                </div>';
                            }
                            echo' <hr>
                                <div class="row">
                                    <div class="col-8">Total</div>
                                    <div class="col-4 text-right font-weight-bold">
                                        '.$prjNo.'
                                    </div>
                                </div>';
                        }
                        
                        ?>
                        </div>
                        <div class="col-8">
                            <div class="chart-bar">
                                <canvas id="myPieChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
        </div>
    </div>
    
</div>
<script>
$(document).ready(function(){
    var client="q";
    $.ajax({
        url:'est_queries.php',
        method: 'POST',
        data:{'yr_opt': client},
        success:function(data){
            $(document).find('#yr_opt').html(data).change();
        }
    });  
});  

</script>
<?php
include('../includes/scripts.php');
include('../includes/footer.php');
?>