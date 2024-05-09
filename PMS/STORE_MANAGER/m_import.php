<?php
include('../../security.php');
include('../../includes/header.php');
include('../../includes/store_mgr_navbar.php');
$message='';$e_message='';
if( isset($_GET['success']))
    {
        $success = $_GET['success'];
        $error = $_GET['error'];
        $err_names = $_GET['err_names'];

        $message = "Materials Updated: ".$success;
        if($error>=1){
            $message .= ("\n Error Uploads: ".$error);
            $e_message = ("\n On Material Code(s):");
            $e_message .= $err_names = str_replace(","," \t\n",$err_names);
        }
    }
?>
<div class="container-fluid">
    <div class="card shadow mb-5">
        <div class="card-header py-3">
            <h5 class="m-0 font-weight-bold text-primary">Import</h5>
        </div>
        <div class="card-body">
            <div class="mb-3">
                <form action="code.php" method="post" enctype="multipart/form-data" class="mb-3">                     
                        <h6 class="text-primary">Material Quantity</h6>
                        <input type="file" name="file" required/>
                        <input class="mt-1" type="submit" name="import" value="Import"/>
                </form>
                <div id="message" class="mt-3">
                    <?php echo nl2br ($message)?>
                </div>
                <div class="ml-3">
                    <?php echo nl2br ($e_message)?>
                </div>
            <label class="mt-3"><b>NOTE : </b>Upload CSV File Only </label>
            </div> 
        </div>
    </div>
</div>
<?php
include('../../includes/scripts.php');
include('../../includes/footer.php');
?>