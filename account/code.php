<?php
include('../security.php');
// include('accQuery.php'); 

function checkChqNo($acc_id,$chq_no){
    include('../dbconfig.php');
    if($chq_no){
        $q_search="SELECT * FROM transaction WHERE Transaction_Status=1 AND Transaction_Cheque_No='$chq_no' AND Account_Id='$acc_id'";
        $q_search_run=mysqli_query($connection,$q_search);
        if(mysqli_num_rows($q_search_run)>0){
            return 'duplicate';
        }
    }
    return 'not';
}
///BANK
if(isset($_POST['addBank'])){
    $bank_name=$_POST['bank_name'];
    $bank_code =$_POST['bank_code'];

    $q_insert="INSERT INTO bank (Bank_Code,Bank_Name,Bank_Status) VALUES ('$bank_code','$bank_name',1)";
    $q_insert_run=mysqli_query($connection,$q_insert);
    if($q_insert_run){
        $_SESSION['status'] = "Bank Details Inserted";
        $_SESSION['status_code'] = "success";
        header('Location: bank.php');
    }
    else{
        $_SESSION['status'] = "Error Inserting Bank Details";
        $_SESSION['status_code'] = "error";
        header('Location: bank.php');
    }
}
if(isset($_POST['editBank'])){
    $bank_id=$_POST['Bank_Id'];
    $bank_name=$_POST['bank_name'];
    $bank_code =$_POST['bank_code'];
    $q_update="UPDATE bank SET Bank_Code='$bank_code', Bank_Name='$bank_name' WHERE Bank_Id='$bank_id'";
    $q_update_run=mysqli_query($connection,$q_update);
    if($q_update_run){
        $_SESSION['status'] = "Bank Details Updated";
        $_SESSION['status_code'] = "success";
        header('Location: bank.php');
    }
    else{
        $_SESSION['status'] = "Error Updating Bank Details";
        $_SESSION['status_code'] = "error";
        header('Location: bank.php');
    }
}
if(isset($_POST['delBank'])){
    $bank_id=$_POST['Bank_Id'];
    $q_update="UPDATE bank SET Bank_Status=0 WHERE Bank_Id='$bank_id'";
    $q_update_run=mysqli_query($connection,$q_update);
    if($q_update_run){
        $_SESSION['status'] = "Bank Deleted";
        $_SESSION['status_code'] = "success";
        header('Location: bank.php');
    }
    else{
        $_SESSION['status'] = "Error Deleting Bank";
        $_SESSION['status_code'] = "error";
        header('Location: bank.php');
    }
}
//ACCOUNT TYPES
if(isset($_POST['addAccType'])){
    $acc_desc=$_POST['acc_desc'];

    $q_insert="INSERT INTO account_type (Account_Desc ,Account_Type_Status ) VALUES ('$acc_desc',1)";
    $q_insert_run=mysqli_query($connection,$q_insert);
    if($q_insert_run){
        $_SESSION['status'] = "Bank Details Inserted";
        $_SESSION['status_code'] = "success";
        header('Location: bank.php');
    }
    else{
        $_SESSION['status'] = "Error Inserting Bank Details";
        $_SESSION['status_code'] = "error";
        header('Location: bank.php');
    }
}
if(isset($_POST['editAccType'])){
    $accTypeId=$_POST['acc_type_id'];
    $accTypeDesc=$_POST['acc_type'];
    $update_accType="UPDATE account_type SET Account_Desc='$accTypeDesc' WHERE Account_Type_Id ='$accTypeId'";
    $update_accType_run=mysqli_query($connection,$update_accType);
    if($update_accType_run){
        $_SESSION['status'] = "Account Type Updated";
        $_SESSION['status_code'] = "success";
        header('Location: bank.php');
    }
    else{
        $_SESSION['status'] = "Error Updating Account Type";
        $_SESSION['status_code'] = "error";
        header('Location: bank.php');
    }
}
if(isset($_POST['delAccType'])){
    $accTypeId=$_POST['accTypeId'];
    $q_del="UPDATE account_type SET Account_Type_Status=0 WHERE Account_Type_Id ='$accTypeId'";
    $q_del_run=mysqli_query($connection,$q_del);
    if($q_del_run){
        $_SESSION['status'] = "Account Type Deleted";
        $_SESSION['status_code'] = "success";
        header('Location: bank.php');
    }
    else{
        $_SESSION['status'] = "Error Deleting Account Type";
        $_SESSION['status_code'] = "error";
        header('Location: bank.php');
    }
}
//ACCOUNTS
if(isset($_POST['addAccount'])){
    $acc_name=$_POST['acc_name'];
    $acc_type_id=$_POST['acc_type'];
    $bank_id=$_POST['bank_id'];
    $iban=$_POST['iban'];
    $currency=$_POST['currency'];
    $date_open=$_POST['date_open'];
    $date_valid=$_POST['date_valid'];
    $iban = str_replace(' ', '', $iban);

    $q_insert="INSERT INTO account (Account_Name,Account_Type_Id,Bank_Id,Account_IBAN,Account_Currency,Account_Date_Open,Account_Date_Expire,Account_Status) VALUES ('$acc_name','$acc_type_id','$bank_id','$iban','$currency','$date_open','$date_valid',1)";
    if($connection->query($q_insert)===TRUE){   
        $id = $connection->insert_id; 
        $targetfolder= "../uploads/bankAccProf/EMTAccountDetails".$id.".pdf";
        $file_type=$_FILES['accFile']['type'];
        if(move_uploaded_file($_FILES['accFile']['tmp_name'], $targetfolder)){
            $_SESSION['status'] = "Account Details Inserted";
            $_SESSION['status_code'] = "success";
            header('Location: bank.php');
        }
        else{$_SESSION['status'] = "Error Inserting Account Details";
            $_SESSION['status_code'] = "error";
            header('Location: bank.php');}
    }
    else{$_SESSION['status'] = "Error Inserting Account Details";
        $_SESSION['status_code'] = "error";
        header('Location: bank.php');
    }
    
}
if(isset($_POST['editAcc'])){
    $acc_id=$_POST['acc_id'];
    $acc_name=$_POST['acc_name'];
    $acc_type_id=$_POST['acc_type'];
    $bank_id=$_POST['bank_id'];
    $iban=$_POST['iban'];
    $currency=$_POST['currency'];
    $date_open=$_POST['date_open'];
    $date_valid=$_POST['date_valid'];
    $iban = str_replace(' ', '', $iban);
    $update_acc="UPDATE account SET Account_Name='$acc_name',Account_Type_Id='$acc_type_id',Bank_Id='$bank_id',Account_IBAN='$iban',Account_Currency='$currency',Account_Date_Open='$date_open',Account_Date_Expire='$date_valid' WHERE Account_Id='$acc_id'";
    $update_acc_run=mysqli_query($connection,$update_acc);
    if($update_acc_run){
        $_SESSION['status'] = "Account Details Updated";
        $_SESSION['status_code'] = "success";
        header('Location: bank.php');
    }
    else{
        $_SESSION['status'] = "Error Updating Account Details";
        $_SESSION['status_code'] = "error";
        header('Location: bank.php');
    }
}
if(isset($_POST['delAccount'])){
    $acc_id=$_POST['Account_Id'];

    $q_del="UPDATE account SET Account_Status=0 WHERE Account_Id='$acc_id'";
    $q_del_run=mysqli_query($connection,$q_del);
    if($q_del_run){
        $_SESSION['status'] = "Account Details Deleted";
        $_SESSION['status_code'] = "success";
        header('Location: bank.php');
    }
    else{
        $_SESSION['status'] = "Error Deleting Account Details";
        $_SESSION['status_code'] = "error";
        header('Location: bank.php');
    }
}
//TRANSACTION TYPE
if(isset($_POST['addTransType'])){
    $trans_type_code=$_POST['trans_type_code'];
    $trans_type_name=$_POST['trans_type_name'];
    // $trans_type_sign=$_POST['trans_type_sign'];
    $q_insert="INSERT INTO transaction_type (Transaction_Type_Code,Transaction_Type_Name,Transaction_Type_Status) VALUES ('$trans_type_code','$trans_type_name',1)";
    $q_insert_run=mysqli_query($connection,$q_insert);
    if($q_insert_run){
        $_SESSION['status'] = "Transaction Type Added";
        $_SESSION['status_code'] = "success";
        header('Location: transaction_setup.php');
    }
    else{
        $_SESSION['status'] = "Error Adding Transaction Type";
        $_SESSION['status_code'] = "error";
        header('Location: transaction_setup.php');
    }
}
if(isset($_POST['editTransType'])){
    $trans_type_id=$_POST['trans_type_id'];
    $trans_type_code=$_POST['trans_type_code'];
    $trans_type_name=$_POST['trans_type_name'];
    // $trans_type_sign=$_POST['trans_type_sign'];
    $q_update="UPDATE transaction_type SET Transaction_Type_Code='$trans_type_code', Transaction_Type_Name='$trans_type_name' WHERE Transaction_Type_Id='$trans_type_id'";
    $q_update_run=mysqli_query($connection,$q_update);
    if($q_update_run){
        $_SESSION['status'] = "Transaction Type Updated";
        $_SESSION['status_code'] = "success";
        header('Location: transaction_setup.php');
    }
    else{
        $_SESSION['status'] = "Error Updating Transaction Type";
        $_SESSION['status_code'] = "error";
        header('Location: transaction_setup.php');
    }
}
if(isset($_POST['delTransType'])){
    $trans_type_id=$_POST['trans_type_id'];
    $q_update="UPDATE transaction_type SET Transaction_Type_Status=0 WHERE Transaction_Type_Id='$trans_type_id'";
    $q_update_run=mysqli_query($connection,$q_update);
    if($q_update_run){
        $_SESSION['status'] = "Bank Deleted";
        $_SESSION['status_code'] = "success";
        header('Location: transaction_setup.php');
    }
    else{
        $_SESSION['status'] = "Error Deleting Bank";
        $_SESSION['status_code'] = "error";
        header('Location: transaction_setup.php');
    }
}
//TRANSACTION STATUS
if(isset($_POST['addTransacStatus'])){
    $transDesc=$_POST['trans_desc'];
    $insert_status="INSERT INTO transaction_status (Transaction_Status_Description,Transaction_Status_Status) VALUES('$transDesc',1)";
    $insert_status_run=mysqli_query($connection,$insert_status);
    if($insert_status_run){
        $_SESSION['status'] = "Status Added";
        $_SESSION['status_code'] = "success";
        header('Location: transaction_setup.php');
    }
    else{
        $_SESSION['status'] = "Error Adding Status";
        $_SESSION['status_code'] = "error";
        header('Location: transaction_setup.php');
    }
}
if(isset($_POST['editTransStatus'])){
    $trans_stat_id=$_POST['trans_stat_id'];
    $trans_stat_desc=$_POST['trans_desc'];
    $update_status="UPDATE transaction_status SET Transaction_Status_Description='$trans_stat_desc' WHERE Transaction_Status_Id ='$trans_stat_id'";
    $update_status_run=mysqli_query($connection,$update_status);
    if($update_status_run){
        $_SESSION['status'] = "Status Updated";
        $_SESSION['status_code'] = "success";
        header('Location: transaction_setup.php');
    }
    else{
        $_SESSION['status'] = "Error Updating Status";
        $_SESSION['status_code'] = "error";
        header('Location: transaction_setup.php');
    }
}
if(isset($_POST['delTransStatus'])){
    $trans_stat_id=$_POST['trans_stat_id'];
    $del_status="UPDATE transaction_status SET Transaction_Status_Status=0 WHERE Transaction_Status_Id='$trans_stat_id'";
    $del_status_run=mysqli_query($connection,$del_status);
    if($del_status_run){
        $_SESSION['status'] = "Status Deleted";
        $_SESSION['status_code'] = "success";
        header('Location: transaction_setup.php');
    }
    else{
        $_SESSION['status'] = "Error Deleting Status";
        $_SESSION['status_code'] = "error";
        header('Location: transaction_setup.php');
    }
}
//TRANSACTION CATEGORY
if(isset($_POST['addCat'])){
    $cat_code=$_POST['cat_code'];
    $cat_name=$_POST['cat_name'];
    
    $q_insert="INSERT INTO transaction_category (Transaction_Cat_Code,Transaction_Category_Description,Transaction_Category_Status) VALUES ('$cat_code','$cat_name',1)";
    $q_insert_run=mysqli_query($connection,$q_insert);
    if($q_insert_run){
        $_SESSION['status'] = "Transaction Category Added";
        $_SESSION['status_code'] = "success";
        header('Location: transaction_setup.php');
    }
    else{
        $_SESSION['status'] = "Error Adding Transaction Category";
        $_SESSION['status_code'] = "error";
        header('Location: transaction_setup.php');
    }
}
if(isset($_POST['editCat'])){
    $cat_id=$_POST['cat_id'];
    $cat_code=$_POST['cat_code'];
    $cat_name=$_POST['cat_name'];
    $update="UPDATE transaction_category SET Transaction_Cat_Code='$cat_code',Transaction_Category_Description='$cat_name' WHERE Transaction_Category_Id ='$cat_id'";
    $update_run=mysqli_query($connection,$update);
    if($update_run){
        $_SESSION['status'] = "Category Updated";
        $_SESSION['status_code'] = "success";
        header('Location: transaction_setup.php');
    }
    else{
        $_SESSION['status'] = "Error Updating Category";
        $_SESSION['status_code'] = "error";
        header('Location: transaction_setup.php');
    }
}
if(isset($_POST['delCat'])){
    $cat_id=$_POST['cat_id'];
    $del="UPDATE transaction_category SET Transaction_Category_Status=0 WHERE Transaction_Category_Id='$cat_id'";
    $del_run=mysqli_query($connection,$del);
    if($del_run){
        $_SESSION['status'] = "Status Deleted";
        $_SESSION['status_code'] = "success";
        header('Location: transaction_setup.php');
    }
    else{
        $_SESSION['status'] = "Error Deleting Status";
        $_SESSION['status_code'] = "error";
        header('Location: transaction_setup.php');
    }
}
//CHANGE Account file
if(isset($_POST['changeFile'])){
    $acc_id=$_POST['accId'];
    $file_type=$_FILES['accFile']['type'];
    
    $targetfolder= "../uploads/bankAccProf/EMTAccountDetails".$acc_id.".pdf";
    if ($file_type=="application/pdf" ) { //check if file is pdf.
        $remove='/EMT/uploads/bankAccProf/EMTAccountDetails'.$acc_id.'.pdf';//remove old file
        unlink($_SERVER['DOCUMENT_ROOT'] .$remove);
        // $targetfolderTRN = $targetfolder."EMTAccountDetails".$acc_id.".pdf";
        if(move_uploaded_file($_FILES['accFile']['tmp_name'], $targetfolder)){
            $_SESSION['status'] = "File Changed";
            $_SESSION['status_code'] = "success";
            header('Location: bank.php');
        }
    }
    else{
        $_SESSION['status'] = "Please upload a PDF file";
        $_SESSION['status_code'] = "error";
        header('Location: bank.php');
    }
}
// TRANSACTION
if(isset($_POST['addTransaction'])){
    $date=$_POST['tdate'];
    $ttype_id=$_POST['t_type_id'];
    // $ttype_id=NULL;
    $chq_no=$_POST['chq_no'];
    $acc_id=$_POST['acc_id'];
    $details=$_POST['details'];
    $amount=$_POST['amount'];
    $amount = str_replace(',', '', $amount);
    $mode=$_POST['mode'];
    $remarks=$_POST['remarks'];
    $status_id=$_POST['stat_id'];
    $user_id=$_POST['user_id'];
    $prj_id=$_POST['prj_id'];
    $cat_id=$_POST['cat_id'];
    if($cat_id){
        $cat_id="'$cat_id'";
    }else{$cat_id='NULL';}
    if($prj_id){
        $prj_id="'$prj_id'";
    }else{$prj_id='NULL';}
    //check if there are same cheque no for 1 account
    if("duplicate"==checkChqNo($acc_id,$chq_no)){
        $_SESSION['status'] = "Duplicated Cheque No.";
        $_SESSION['status_code'] = "warning";
        header('Location: transaction.php');
        // echo 'duplicate cheque no';
    }
    else{
        if($mode=='paid'){
            $amount = -$amount;
        }
        // check if status is cancelled 
        $cancel_stat="SELECT Transaction_Status_Id FROM transaction_status WHERE Transaction_Status_Description LIKE '%cancel%' AND Transaction_Status_Status=1";
        $cancel_stat_run=mysqli_query($connection,$cancel_stat);
        if(mysqli_num_rows($cancel_stat_run)>0){
            $row=mysqli_fetch_assoc($cancel_stat_run);
            $trans_stat_id=$row['Transaction_Status_Id'];
        }
        if($status_id==$trans_stat_id){
            $cancel_stat_val=1;
        }
        else{
            $cancel_stat_val=0;
        }
        $insert_trans="INSERT INTO transaction (Transaction_Date, Transaction_Type_Id,Transaction_Cheque_No,Transaction_Amount,Account_Id,Transaction_Details,Transaction_Status_Id,Transaction_Remarks,User_Id,Prj_Id,Transaction_Cancel_Status,Transaction_Category_Id,Transaction_Status) VALUES ('$date','$ttype_id','$chq_no','$amount','$acc_id','$details','$status_id','$remarks','$user_id',$prj_id,$cancel_stat_val,$cat_id,1)";
        // echo $insert_trans;
        $insert_trans_run=mysqli_query($connection,$insert_trans);
        if($insert_trans_run){
            $_SESSION['status'] = "Transaction Inserted";
            $_SESSION['status_code'] = "success";
            header('Location: transaction.php');
        }
        else{
            $_SESSION['status'] = "Error recording transaction";
            $_SESSION['status_code'] = "error";
            header('Location: transaction.php');
        }
    }
}
if(isset($_POST['importTrans'])){
    $user_id=$_POST['user_id'];
    if($_FILES['file']['name']){
        $filename = explode(".", $_FILES['file']['name']);
        if($filename[1] == 'csv'){
            $handle = fopen($_FILES['file']['tmp_name'], "r");
            //search for cancelled status id
            $cancel_stat="SELECT Transaction_Status_Id FROM transaction_status WHERE Transaction_Status_Description LIKE '%cancel%' AND Transaction_Status_Status=1";
            $cancel_stat_run=mysqli_query($connection,$cancel_stat);
            if(mysqli_num_rows($cancel_stat_run)>0){
                $row=mysqli_fetch_assoc($cancel_stat_run);
                $trans_stat_id=$row['Transaction_Status_Id']; // cancelled id
            }
            while($data = fgetcsv($handle)){
                $date= mysqli_real_escape_string($connection, $data[0]); // date            20231211 yr month day
                $chq_no = mysqli_real_escape_string($connection, $data[1]); // cheque no    48
                $acc_code = mysqli_real_escape_string($connection, $data[2]); // bank code  ADCB
                $tran_code=mysqli_real_escape_string($connection, $data[3]); // type        transaction type code CDC PDC ATM CH
                $details = mysqli_real_escape_string($connection, $data[4]); //details      Company Name
                $details= preg_replace('/[^A-Za-z0-9 ]/', '', $details); 
                $amount = mysqli_real_escape_string($connection, $data[5]); // amount       50,000
                $amount = str_replace(',', '', $amount);
                $mode=mysqli_real_escape_string($connection, $data[6]); // PAID/ RECEIVED
                $remarks=mysqli_real_escape_string($connection, $data[7]);
                $remarks= preg_replace('/[^A-Za-z0-9 ]/', '', $remarks); 
                $trans_stat_name =mysqli_real_escape_string($connection, $data[8]); // realeased/check with accounts
                $prj_code=mysqli_real_escape_string($connection, $data[9]); // P0062
                $category=mysqli_real_escape_string($connection, $data[10]); //t category HR SB MP
                $sr_no=mysqli_real_escape_string($connection, $data[11]); // check erorrs
                //search account id
                $acc_q="SELECT * FROM bank as b
                        LEFT JOIN account as acc on acc.Bank_Id=b.Bank_Id
                        WHERE b.Bank_Code LIKE '%$acc_code%' and b.Bank_Status=1 AND acc.Account_Status=1";
                $acc_q_run=mysqli_query($connection,$acc_q);
                if(mysqli_num_rows($acc_q_run)>0){
                    $row_acc=mysqli_fetch_assoc($acc_q_run);
                    $acc_id=$row_acc['Account_Id'];
                }
                else{
                    $acc_id=null;}
                //search transaction type id
                $q_trans_id="SELECT * FROM transaction_type 
                            WHERE Transaction_Type_Code='$tran_code' AND Transaction_Type_Status=1 ";
                $q_trans_id_run=mysqli_query($connection,$q_trans_id);
                if(mysqli_num_rows($q_trans_id_run)>0){
                    $row_tt=mysqli_fetch_assoc($q_trans_id_run);
                    $ttype_id=$row_tt['Transaction_Type_Id'];
                }
                else{ $ttype_id=NULL;}
                //search for trans stat id
                $q_trans_t="SELECT * FROM transaction_status 
                            WHERE Transaction_Status_Description LIKE '%$trans_stat_name%' AND Transaction_Status_Status=1";
                $q_trans_t_run=mysqli_query($connection,$q_trans_t);
                if(mysqli_num_rows($q_trans_t_run)>0){
                    $row_ts=mysqli_fetch_assoc($q_trans_t_run);
                    $status_id=$row_ts['Transaction_Status_Id'];
                }
                else{
                    $status_id=NULL;
                }
                //search prj id
                $q_prj="SELECT Prj_Id FROM project WHERE Prj_Code LIKE '%$prj_code%' AND Prj_Status=1";
                $q_prj_run=mysqli_query($connection,$q_prj);
                if(mysqli_num_rows($q_prj_run)>0){
                    $row_p=mysqli_fetch_assoc($q_prj_run);
                    $prj_id=$row_p['Prj_Id'];
                }
                else{
                    $prj_id=NULL;
                }
                // check cat id
                $q_cat_id="SELECT Transaction_Category_Id FROM transaction_category WHERE Transaction_Cat_Code='$category' AND Transaction_Category_Status=1";
                $q_cat_run=mysqli_query($connection,$q_cat_id);
                if(mysqli_num_rows($q_cat_run)>0){
                    $row_c=mysqli_fetch_assoc($q_cat_run);
                    $cat_id=$row_c['Transaction_Category_Id'];
                }
                else{
                    $cat_id=NULL;
                }
                if($cat_id){
                    $cat_id="'$cat_id'";
                }else{$cat_id='NULL';}
                if($prj_id){
                    $prj_id="'$prj_id'";
                }else{$prj_id='NULL';}
                //check if there are same cheque no for 1 account
                if("duplicate"==checkChqNo($acc_id,$chq_no)){
                    // $_SESSION['status'] = "Duplicated Cheque No.";
                    // $_SESSION['status_code'] = "warning";
                    $failed++;
                    // echo 'duplicate cheque no';
                }
                else{
                    if($mode=='paid'){
                        $amount = -$amount;
                    }
                    // check if status is cancelled 
                    if($status_id==$trans_stat_id){
                        $cancel_stat_val=1;
                    }
                    else{
                        $cancel_stat_val=0;
                    }
                    $insert_trans="INSERT INTO transaction (Transaction_Date, Transaction_Type_Id,Transaction_Cheque_No,Transaction_Amount,Account_Id,Transaction_Details,Transaction_Status_Id,Transaction_Remarks,User_Id,Prj_Id,Transaction_Category_Id,Transaction_Cancel_Status,Transaction_Status) VALUES ('$date','$ttype_id','$chq_no','$amount','$acc_id','$details','$status_id','$remarks','$user_id',$prj_id,$cat_id,$cancel_stat_val,1)";
                    // echo $insert_trans;
                    $insert_trans_run=mysqli_query($connection,$insert_trans);
                    if($insert_trans_run){
                    }
                    else{ $failed++;
                        $sr_nos.=$sr_no.'<br>';
                    }
                }
            }
            if($failed>0){
                echo $failed.'failed upload: do not refresh, contact IT'; echo $sr_nos;
            }
            else{
                $_SESSION['status'] = "Transactions Imported";
                $_SESSION['status_code'] = "success";
                header('Location: transaction.php');
            }
            fclose($handle);
        }
        else{
            $_SESSION['status'] = "Please upload .csv file";
            $_SESSION['status_code'] = "warning";
            header("location:transaction.php");
        }
    }
}
if(isset($_POST['editTrans'])){
    $trans_id=$_POST['trans_id'];
    $date=$_POST['tdate'];
    $ttype_id=$_POST['t_type_id'];
    $chq_no=$_POST['chq_no'];
    $acc_id=$_POST['acc_id'];
    $details=$_POST['details'];
    $amount=$_POST['amount'];
    $amount = str_replace(',', '', $amount);
    $mode=$_POST['mode'];
    $remarks=$_POST['remarks'];
    $status_id=$_POST['stat_id'];
    $user_id=$_POST['user_id'];
    $cat_id=$_POST['cat_id'];
    $prj_id=$_POST['prj_id'];
    //check for duplicate cheque number except from same record
    if("duplicate"==checkChqNo($acc_id,$chq_no)){
        $_SESSION['status'] = "Duplicated Cheque No.";
        $_SESSION['status_code'] = "warning";
        header('Location: transaction.php');
    }
    else{
        if($mode=='paid' && $amount<0){
        }
        elseif($mode=='paid' && $amount>0){
            $amount = -$amount;
        }
        elseif($mode=='received' && $amount<0){
            $amount=abs($amount);
        }
        // check if status is cancelled 
        $cancel_stat="SELECT Transaction_Status_Id FROM transaction_status WHERE Transaction_Status_Description LIKE '%cancel%' AND Transaction_Status_Status=1";
        $cancel_stat_run=mysqli_query($connection,$cancel_stat);
        if(mysqli_num_rows($cancel_stat_run)>0){
            $row=mysqli_fetch_assoc($cancel_stat_run);
            $trans_stat_id=$row['Transaction_Status_Id'];
        }
        if($status_id==$trans_stat_id){
            $cancel_stat_val=1;
        }
        else{
            $cancel_stat_val=0;
        }
        $update="UPDATE transaction SET Transaction_Date='$date', Transaction_Type_Id='$ttype_id', Transaction_Cheque_No='$chq_no', Transaction_Amount='$amount', Account_Id='$acc_id', Transaction_Details='$details', Transaction_Status_Id='$status_id', Transaction_Remarks='$remarks', User_Id='$user_id', Prj_Id=$prj_id, Transaction_Cancel_Status=$cancel_stat_val,Transaction_Category_Id=$cat_id WHERE Transaction_Id ='$trans_id'";
        // echo $update;
        $update_run=mysqli_query($connection,$update);
        if($update_run){
            $_SESSION['status'] = "Transaction Updated";
            $_SESSION['status_code'] = "success";
            header('Location: transaction.php');
        }
        else{
            $_SESSION['status'] = "Error updating transaction";
            $_SESSION['status_code'] = "error";
            header('Location: transaction.php');
        }
    }

}
if(isset($_POST['delTrans'])){
    $trans_id=$_POST['trans_id'];
    $del_trans="UPDATE transaction SET Transaction_Status=0 WHERE Transaction_Id='$trans_id'";
    $del_trans_run=mysqli_query($connection,$del_trans);
    if($del_status_run){
        $_SESSION['status'] = "Transaction Deleted";
        $_SESSION['status_code'] = "success";
        header('Location: transaction.php');
    }
    else{
        $_SESSION['status'] = "Error Deleting Transaction";
        $_SESSION['status_code'] = "error";
        header('Location: transaction.php');
    }
}
// ACCOUNT USERS
if(isset($_POST['addUser'])){
    $username = $_POST['username']; 
    $password = md5($_POST['password']);
    $cpassword = md5($_POST['confirmpassword']);
    $usertype = $_POST['usertype'];

    $email_query = "SELECT * FROM users WHERE USERNAME='$username' AND USER_STATUS=1";
    $email_query_run = mysqli_query($connection, $email_query);

    if(mysqli_num_rows($email_query_run) > 0){
        $_SESSION['status'] = "Username Already Exist";
        $_SESSION['status_code'] = "error";
        header('Location: user_setup.php');
    }
    else{
        if($username =="" or $password =="" or $usertype ==""){
            $_SESSION['status'] = "Fill out the form";
            $_SESSION['status_code'] = "warning";
            header('Location: user_setup.php');
        }
        else{
            if($password === $cpassword){
                $query = "INSERT INTO users (USERNAME,USER_PASSWORD,USERTYPE,USER_STATUS) VALUES ('$username','$password','$usertype', 1)";
                $query_run = mysqli_query($connection, $query);
               if($query_run){
                    // success
                    $_SESSION['status'] = "Success creating user";
                    $_SESSION['status_code'] = "success";
                    header('Location: user_setup.php');
                }
                else{
                    //error
                    $_SESSION['status'] = "Error creating user";
                    $_SESSION['status_code'] = "error";
                    header('Location: user_setup.php');
                }
            }
            else{
                $_SESSION['status'] = "Passord does not match";
                $_SESSION['status_code'] = "warning";
                header('Location: user_setup.php');
            }
        }
    }
}
// UPDATING USER
if(isset($_POST['updatebtn'])){
    // PASSING VARIABLE
    $id = $_POST['user_update_id'];
    $username = $_POST['edit_username'];
    $usertype=$_POST['edit_type'];
    $password = md5($_POST['edit_password']);
    // $usertype = $_POST ['update_usertype'];
    $email_query = "SELECT * FROM users WHERE USERNAME='$username' AND USER_STATUS=1";
    $email_query_run = mysqli_query($connection, $email_query);
    if($username =="" or $password ==""){
        $_SESSION['status'] = "Fill out the form";
        $_SESSION['status_code'] = "warning";
        header('Location: user_setup.php');
    }
    else{
        $query = "UPDATE users SET USERNAME='$username', USERTYPE='$usertype', USER_PASSWORD='$password' WHERE USER_ID='$id'";
        $query_run = mysqli_query($connection, $query);
        if($query_run){
            $_SESSION['status'] = "User is Updated";
            $_SESSION['status_code'] = "success";
            header('Location: user_setup.php');
        }
        else{
            $_SESSION['status'] = "Error updating user";
            $_SESSION['status_code'] = "error";
            header('Location: user_setup.php');
        }
    }
}
// DELETING USER
if(isset($_POST['delete_btn'])){
    $id = $_POST['delete_id'];
    $query="UPDATE users SET USER_STATUS=0 WHERE USER_ID='$id'";
    $query_run = mysqli_query($connection, $query);
    if($query_run){
        $_SESSION['status'] = "User Deleted";
        $_SESSION['status_code'] = "success";
        header('Location: user_setup.php');
    }
    else{
        $_SESSION['status'] = "Error Deleting";
        $_SESSION['status_code'] = "error";
        header('Location: user_setup.php');
    }
}
//multiple cancel transaction
if(isset($_POST['cancelTrans'])){
    $error_cnt=NULL;
    // echo 'fdfefehhuhu';
    $data = array( 
        'chkIds' => $_POST['chkIds']
    );
    $count = count($_POST['chkIds']);
    if($count>0){
        // get cancel status id in transation_status
        $cancel_stat="SELECT Transaction_Status_Id FROM transaction_status WHERE Transaction_Status_Description LIKE '%cancel%' AND Transaction_Status_Status=1";
        $cancel_stat_run=mysqli_query($connection,$cancel_stat);
        if(mysqli_num_rows($cancel_stat_run)>0){
            $row=mysqli_fetch_assoc($cancel_stat_run);
            $trans_stat_id=$row['Transaction_Status_Id'];
        }
        for ($k=0; $k < $count; $k++) {
            $q_cancel="UPDATE transaction SET Transaction_Cancel_Status=1, Transaction_Status_Id='$trans_stat_id' WHERE Transaction_Id IN ({$_POST['chkIds'][$k]})";
            $q_cancel_run=mysqli_query($connection,$q_cancel);
            if($q_cancel_run){
            }
            else{
                $error_cnt++;
            }
        } 
        if($error_cnt>0){
            $_SESSION['status'] = "Error Cancelling Status";
            $_SESSION['status_code'] = "error";
            header('Location: transaction.php');
        }
        else{
            $_SESSION['status'] = "Transactions Cancelled";
            $_SESSION['status_code'] = "success";
            header('Location: transaction.php');
        }
    }
    else{
        $_SESSION['status'] = "Error Cancelling Status";
        $_SESSION['status_code'] = "error";
        header('Location: transaction.php');
    }
}
//multiple delete transaction
if(isset($_POST['multDelTrans'])){
    $trans_id=$_POST['chkIds'];
    $del_trans="UPDATE transaction SET Transaction_Status=0 WHERE Transaction_Id IN ($trans_id)";
    $del_trans_run=mysqli_query($connection,$del_trans);
    if($del_trans_run){
        $_SESSION['status'] = "Transaction Deleted";
        $_SESSION['status_code'] = "success";
        header('Location: transaction.php');
    }
    else{
        $_SESSION['status'] = "Error Deleting Transaction";
        $_SESSION['status_code'] = "error";
        header('Location: transaction.php');
    }
}
//CODE
if(isset($_POST['addCode'])){
    $code=$_POST['code'];
    $insert_code="INSERT INTO chq_code (Chq_Code,Chq_Code_Status) VALUES ('$code',1)";
    $insert_code_run=mysqli_query($connection,$insert_code);
    if($insert_code_run){
        $_SESSION['status'] = "Code Created";
        $_SESSION['status_code'] = "success";
        header('Location: transaction_setup.php');
    }
    else{
        $_SESSION['status'] = "Error Creating Code";
        $_SESSION['status_code'] = "error";
        header('Location: transaction_setup.php');
    }
}
if(isset($_POST['editCode'])){
    $code=$_POST['code'];
    $code_id=$_POST['code_id'];
    $update_code="UPDATE chq_code SET Chq_Code='$code' WHERE Chq_Code_Id='$code_id'";
    $update_code_run=mysqli_query($connection,$update_code);
    if($update_code_run){
        $_SESSION['status'] = "Code Updated";
        $_SESSION['status_code'] = "success";
        header('Location: transaction_setup.php');
    }
    else{
        $_SESSION['status'] = "Error Updating Code";
        $_SESSION['status_code'] = "error";
        header('Location: transaction_setup.php');
    }

}
if(isset($_POST['delCode'])){
    $code_id=$_POST['chqCode_id'];
    $del_code="UPDATE chq_code SET Chq_Code_Status=0 WHERE Chq_Code_Id='$code_id'";
    $del_code_run=mysqli_query($connection,$del_code);
    if($del_code_run){
        $_SESSION['status'] = "Code Deleted";
        $_SESSION['status_code'] = "success";
        header('Location: transaction_setup.php');
    }
    else{
        $_SESSION['status'] = "Error Deleting Code";
        $_SESSION['status_code'] = "error";
        header('Location: transaction_setup.php');
    }
}
//payment plans
if(isset($_POST['addPlan'])){
    $cat_id=$_POST['cat_id'];
    $details=$_POST['details'];
    $freq=$_POST['freq'];
    $amount=$_POST['amount'];
    $amount = str_replace(',', '', $amount);
    if($freq=="Yearly"){
        $y_date=$_POST['y_date'];
    }
    elseif($freq=="Quarterly"){
        $q1_date=$_POST['q1_date'];
        $q2_date=$_POST['q2_date'];
        $q3_date=$_POST['q3_date'];
        $q4_date=$_POST['q4_date'];
    }
    elseif($freq=="Monthly"){
        $y_date=NULL; $q1_date=NULL; $q2_date=NULL;  $q3_date=NULL; $q4_date=NULL;
    }
    $q_insert="INSERT INTO payment_plan (Transaction_Category_Id,Plan_Desc,Frequency,Plan_Amount,Y_Date,Q1_Date,Q2_Date,Q3_Date,Q4_Date,Plan_Status) VALUES ('$cat_id','$details','$freq','$amount','$y_date','$q1_date','$q2_date','$q3_date','$q4_date',1)";
    $q_insert_run=mysqli_query($connection,$q_insert);
    if($q_insert_run){
        $_SESSION['status'] = "Plan Added";
        $_SESSION['status_code'] = "success";
        header('Location: fp_exp_cat.php');
    }
    else{
        $_SESSION['status'] = "Error Creating Plan";
        $_SESSION['status_code'] = "error";
        header('Location: fp_exp_cat.php');
    }
}
if(isset($_POST['editPlan'])){
    $p_id=$_POST['p_id'];
    $cat_id=$_POST['cat_id'];
    $details=$_POST['details'];
    $freq=$_POST['freq'];
    $amount=$_POST['amount'];
    $amount = str_replace(',', '', $amount);
    if($freq=="Yearly"){
        $y_date=$_POST['y_date'];
        $q1_date=NULL; $q2_date=NULL;  $q3_date=NULL; $q4_date=NULL;
    }
    elseif($freq=="Quarterly"){
        $q1_date=$_POST['q1_date'];
        $q2_date=$_POST['q2_date'];
        $q3_date=$_POST['q3_date'];
        $q4_date=$_POST['q4_date'];
        $y_date=NULL;
    }
    elseif($freq=="Monthly"){
        $y_date=NULL; $q1_date=NULL; $q2_date=NULL;  $q3_date=NULL; $q4_date=NULL;
    }
    $q_update="UPDATE payment_plan SET Transaction_Category_Id='$cat_id',Plan_Desc='$details',Frequency='$freq',Plan_Amount='$amount',Y_Date='$y_date',Q1_Date='$q1_date',Q2_Date='$q2_date',Q3_Date='$q3_date',Q4_Date='$q4_date' WHERE Plan_Id='$p_id'";
    $q_update_run=mysqli_query($connection,$q_update);
    if($q_update_run){
        $_SESSION['status'] = "Plan Updated";
        $_SESSION['status_code'] = "success";
        header('Location: fp_exp_cat.php');
    }
    else{
        $_SESSION['status'] = "Error Updating Plan";
        $_SESSION['status_code'] = "error";
        header('Location: fp_exp_cat.php');
    }
}
if(isset($_POST['delPlan'])){
    $p_id=$_POST['p_id'];
    $q_del="UPDATE payment_plan SET Plan_Status=0 WHERE Plan_Id='$p_id'";
    $q_del_run=mysqli_query($connection,$q_del);
    if($q_del_run){
        $_SESSION['status'] = "Plan Deleted";
        $_SESSION['status_code'] = "success";
        header('Location: fp_exp_cat.php');
    }
    else{
        $_SESSION['status'] = "Error Deleting Plan";
        $_SESSION['status_code'] = "error";
        header('Location: fp_exp_cat.php');
    }
}
// expected expense
if(isset($_POST['addExp'])){
    $desc=$_POST['details'];
    $amt=$_POST['amount'];
    $month=$_POST['month'];
    $amt = str_replace(',', '', $amt);
    $prj_id=$_POST['prj_id'];
    $year=$_POST['year'];
    $cat_id=$_POST['cat_id'];
    $date=$year.'-'.$month.'-01';
    $q_insert="INSERT INTO expected_expense (Exp_Desc,Exp_Amount,Exp_Date,Transaction_Category_Id,Prj_Id,Exp_Status ) VALUES ('$desc','$amt','$date','$cat_id',$prj_id,1)";
    $q_insert_run=mysqli_query($connection,$q_insert);
    //MONTH NO YEAR CAT ID
    if($q_insert_run){
        $_SESSION['status'] = "Expense Added";
        $_SESSION['status_code'] = "success";
        header('Location: fp_exp_tbl.php?cat_id='.$cat_id.'&month='.$month.'&year='.$year);
    }
    else{
        $_SESSION['status'] = "Error Adding Expense";
        $_SESSION['status_code'] = "error";
        header('Location: fp_exp_tbl.php?cat_id='.$cat_id.'&month='.$month.'&year='.$year);
    }
}
if(isset($_POST['editExp'])){
    $ee_id=$_POST['ee_id'];
    $desc=$_POST['desc'];
    $amt=$_POST['amount'];
    $amt = str_replace(',', '', $amt);
    $prj_id=$_POST['prj_id'];
    if($prj_id){
        $prj_id="'$prj_id'";
    }else{$prj_id='NULL';}

    $cat_id=$_POST['cat_id'];
    $month=$_POST['month'];
    $year=$_POST['year'];
    $q_update="UPDATE expected_expense SET Exp_Desc='$desc', Exp_Amount='$amt', Prj_Id=$prj_id WHERE Expense_Id='$ee_id'";
    $q_update_run=mysqli_query($connection,$q_update);
    if($q_insert_run){
        $_SESSION['status'] = "Expense Deleted";
        $_SESSION['status_code'] = "success";
        header('Location: fp_exp_tbl.php?cat_id='.$cat_id.'&month='.$month.'&year='.$year);
    }
    else{
        $_SESSION['status'] = "Error Deleting Expense";
        $_SESSION['status_code'] = "error";
        header('Location: fp_exp_tbl.php?cat_id='.$cat_id.'&month='.$month.'&year='.$year);
    }
}
if(isset($_POST['delExp'])){
    $ee_id=$_POST['ee_id'];
    $cat_id=$_POST['cat_id'];
    $month=$_POST['month'];
    $year=$_POST['year'];
    $q_update="UPDATE expected_expense SET Exp_Status=0 WHERE Expense_Id='$ee_id'";
    $q_update_run=mysqli_query($connection,$q_update);
    if($q_insert_run){
        $_SESSION['status'] = "Expense Deleted";
        $_SESSION['status_code'] = "success";
        header('Location: fp_exp_tbl.php?cat_id='.$cat_id.'&month='.$month.'&year='.$year);
    }
    else{
        $_SESSION['status'] = "Error Deleting Expense";
        $_SESSION['status_code'] = "error";
        header('Location: fp_exp_tbl.php?cat_id='.$cat_id.'&month='.$month.'&year='.$year);
    }
}
if(isset($_POST['cat_id'])){
    $cat_id=$_POST['cat_id'];
    $month=$_POST['month'];
    $year=$_POST['year'];
    $p_id=$_POST['p_id']; 
    $date=$year.'-'.$month.'-01';
    // function get amount, desc
    $q_search="SELECT * FROM payment_plan WHERE Plan_Status=1 AND Plan_Id='$p_id'";
    $q_search_run=mysqli_query($connection,$q_search);
    if(mysqli_num_rows($q_search_run)>0){
        $row=mysqli_fetch_assoc($q_search_run);
        $amt=$row['Plan_Amount'];
        $desc=$row['Plan_Desc'];
    }
    $q_insert="INSERT INTO expected_expense (Exp_Desc,Exp_Amount,Exp_Date,Transaction_Category_Id,Plan_Id,Exp_Status ) VALUES ('$desc','$amt','$date','$cat_id','$p_id',1)";
    $q_insert_run=mysqli_query($connection,$q_insert);
}
if(isset($_POST['cat_id_r'])){
    $cat_id=$_POST['cat_id_r'];
    $month=$_POST['month'];
    $year=$_POST['year'];
    $p_id=$_POST['p_id']; 
    $date=$year.'-'.$month.'-01';
    $q_update="UPDATE expected_expense SET Exp_Status=0 WHERE Plan_Id='$p_id' AND Exp_Date='$date' AND Transaction_Category_Id='$cat_id'";
    $q_update_run=mysqli_query($connection,$q_update);
}
// expense division
if(isset($_POST['addED'])){
    include('accQuery.php'); 
    $ee_id=$_POST['ee_id'];
    $cat_id=$_POST['cat_id_ed'];
    $desc=$_POST['desc'];
    $amt=$_POST['amount'];
    $amt = str_replace(',', '', $amt);
    $month=$_POST['month'];
    $year=$_POST['year'];
    // check if applicable
    $q="SELECT SUM(ED_Amount) as ed_amt FROM expense_division 
        WHERE ED_Status=1 AND Expense_Id='$ee_id'";
    $q_run=mysqli_query($connection,$q);
    if(mysqli_num_rows($q_run)>0){ $total=0;
        while($row=mysqli_fetch_assoc($q_run)){
            $ed_amt=$row['ed_amt'];
            $total=$total+$ed_amt;
        }
    }
    $total=$total+$amt;
    $ee_amt=eeAmt($ee_id);
    if($ee_amt){
        $diff=$ee_amt-$total;
    }
    if($diff>0){
        // echo 'added';
        $q_ed="INSERT INTO expense_division (ED_Desc,ED_Amount,Expense_Id,ED_Paid_Status,ED_Status) VALUES ('$desc','$amt','$ee_id',0,1)";
        $q_ed_run=mysqli_query($connection,$q_ed);
        if($q_ed_run){
            $_SESSION['status'] = "Sub Expense Added";
            $_SESSION['status_code'] = "success";
            header('Location: fp_exp_tbl.php?cat_id='.$cat_id.'&month='.$month.'&year='.$year);
        }
        else{
            $_SESSION['status'] = "Error Adding Sub Expense";
            $_SESSION['status_code'] = "error";
            header('Location: fp_exp_tbl.php?cat_id='.$cat_id.'&month='.$month.'&year='.$year);
        }
    }
    else{
        $_SESSION['status'] = "Amount exceeded from Expected Expense";
        $_SESSION['status_code'] = "warning";
        header('Location: fp_exp_tbl.php?cat_id='.$cat_id.'&month='.$month.'&year='.$year);
    }
}
if(isset($_POST['editED'])){
    include('accQuery.php'); 
    $ed_id=$_POST['ed_id'];
    $ee_id=$_POST['ee_id'];
    $cat_id=$_POST['cat_id'];
    $month=$_POST['month'];
    $year=$_POST['year'];
    $desc=$_POST['desc'];
    $amt=$_POST['amount'];
    $amt = str_replace(',', '', $amt);
    // check if applicable
    $q="SELECT SUM(ED_Amount) as ed_amt FROM expense_division 
        WHERE ED_Status=1 AND Expense_Id='$ee_id'";
    $q_run=mysqli_query($connection,$q);
    if(mysqli_num_rows($q_run)>0){ $total=0;
        while($row=mysqli_fetch_assoc($q_run)){
            $ed_amt=$row['ed_amt'];
            $total=$total+$ed_amt;
        }
    }
    $total=$total+$amt;
    $ee_amt=eeAmt($ee_id);
    if($ee_amt){
        $diff=$ee_amt-$total;
    }
    if($diff>0){
        $q_update="UPDATE expense_division SET ED_Desc='$desc', ED_Amount='$amt' WHERE Exp_Div_Id='$ed_id'";
        $q_update_run=mysqli_query($connection,$q_update);
        if($q_update_run){
            $_SESSION['status'] = "Sub Expense Updated";
            $_SESSION['status_code'] = "success";
            header('Location: fp_exp_tbl.php?cat_id='.$cat_id.'&month='.$month.'&year='.$year);
        }
        else{
            $_SESSION['status'] = "Error Updating Sub Expense";
            $_SESSION['status_code'] = "error";
            header('Location: fp_exp_tbl.php?cat_id='.$cat_id.'&month='.$month.'&year='.$year);
        }
    }
    else{
        $_SESSION['status'] = "Amount exceeded from Expected Expense";
        $_SESSION['status_code'] = "warning";
        header('Location: fp_exp_tbl.php?cat_id='.$cat_id.'&month='.$month.'&year='.$year);
    }
}
if(isset($_POST['delED'])){
    $ed_id=$_POST['ed_id'];
    $cat_id=$_POST['cat_id'];
    $month=$_POST['month'];
    $year=$_POST['year'];
    $q_del="UPDATE expense_division set ED_Status=0 WHERE Exp_Div_Id='$ed_id'";
    $q_del_run=mysqli_query($connection,$q_del);
    if($q_ed_run){
        $_SESSION['status'] = "Sub Expense Deleted";
        $_SESSION['status_code'] = "success";
        header('Location: fp_exp_tbl.php?cat_id='.$cat_id.'&month='.$month.'&year='.$year);
    }
    else{
        $_SESSION['status'] = "Error Deleting Sub Expense";
        $_SESSION['status_code'] = "error";
        header('Location: fp_exp_tbl.php?cat_id='.$cat_id.'&month='.$month.'&year='.$year);
    }
}
if(isset($_POST['paid'])){
    $type=$_POST['paid'];
    $id=$_POST['id'];
    if($type=='ee'){
        $paid_q="UPDATE expected_expense SET Exp_Paid_Status=1 WHERE Expense_Id='$id'";
    }
    elseif($type=='ed'){
        $paid_q="UPDATE expense_division SET ED_Paid_Status=1 WHERE Exp_Div_Id='$id'";
    }
    // echo $paid_q;
    $paid_q_run=mysqli_query($connection,$paid_q);
}
if(isset($_POST['remove_paid'])){
    $type=$_POST['remove_paid'];
    $id=$_POST['id'];

    if($type=='ee'){
        $paid_q="UPDATE expected_expense SET Exp_Paid_Status=0 WHERE Expense_Id='$id'";
    }
    elseif($type=='ed'){
        $paid_q="UPDATE expense_division SET ED_Paid_Status=0 WHERE Exp_Div_Id='$id'";
    }
    $paid_q_run=mysqli_query($connection,$paid_q);

}
if(isset($_POST['remove_r'])){
    $type=$_POST['remove_paid'];
    $id=$_POST['id'];

    $paid_q="UPDATE expected_income SET Income_Receive_Status=0 WHERE Income_Id='$id'";
    $paid_q_run=mysqli_query($connection,$paid_q);
}
if(isset($_POST['addChqNo'])){
    $type=$_POST['type'];
    $id=$_POST['id'];
    $trans_id=$_POST['t_id'];
    $cat_id=$_POST['cat_id'];
    $month=$_POST['month'];
    $year=$_POST['year'];
    if($type=='ee'){
        $paid_q="UPDATE expected_expense SET Exp_Paid_Status=1, Transaction_Id='$trans_id' WHERE Expense_Id='$id'";
    }
    elseif($type=='ed'){
        $paid_q="UPDATE expense_division SET ED_Paid_Status=1, Transaction_Id='$trans_id' WHERE Exp_Div_Id='$id'";
    }
    // echo $paid_q;
    $paid_q_run=mysqli_query($connection,$paid_q);
    if($q_update_run){
        header('Location: fp_exp_tbl.php?cat_id='.$cat_id.'&month='.$month.'&year='.$year);
    }
    else{
        $_SESSION['status'] = "Error Updating Expense";
        $_SESSION['status_code'] = "error";
        header('Location: fp_exp_tbl.php?cat_id='.$cat_id.'&month='.$month.'&year='.$year);
    }
}
if(isset($_POST['rChqNo'])){
    $type=$_POST['type'];
    $id=$_POST['id'];
    $trans_id=$_POST['t_id'];
    $cat_id=$_POST['cat_id'];
    $month=$_POST['month'];
    $year=$_POST['year'];
    $paid_q="UPDATE expected_income SET Income_Receive_Status=1, Transaction_Id='$trans_id' WHERE Income_Id='$id'";
    $paid_q_run=mysqli_query($connection,$paid_q);
    if($q_update_run){
        header('Location: fp_income_tbl.php?cat_id='.$cat_id.'&month='.$month.'&year='.$year);
    }
    else{
        $_SESSION['status'] = "Error Updating Expense";
        $_SESSION['status_code'] = "error";
        header('Location: fp_income_tbl.php?cat_id='.$cat_id.'&month='.$month.'&year='.$year);
    }
}
if(isset($_POST['addIncome'])){
    $desc=$_POST['details'];
    $amt=$_POST['amount'];
    $month=$_POST['month'];
    $amt = str_replace(',', '', $amt);
    $prj_id=$_POST['prj_id'];
    $year=$_POST['year'];
    $cat_id=$_POST['cat_id'];
    $date=$year.'-'.$month.'-01';
    $q_insert="INSERT INTO expected_income (Income_Desc,Income_Amount,Income_Date,Transaction_Category_Id,Prj_Id,Income_Status ) VALUES ('$desc','$amt','$date','$cat_id','$prj_id',1)";
    $q_insert_run=mysqli_query($connection,$q_insert);
    //MONTH NO YEAR CAT ID
    if($q_insert_run){
        $_SESSION['status'] = "Income Added";
        $_SESSION['status_code'] = "success";
        header('Location: fp_income_tbl.php?cat_id='.$cat_id.'&month='.$month.'&year='.$year);
    }
    else{
        $_SESSION['status'] = "Error Adding Income";
        $_SESSION['status_code'] = "error";
        header('Location: fp_income_tbl.php?cat_id='.$cat_id.'&month='.$month.'&year='.$year);
    }
}
if(isset($_POST['editIncome'])){
    $ee_id=$_POST['ee_id'];
    $desc=$_POST['desc'];
    $amt=$_POST['amount'];
    $amt = str_replace(',', '', $amt);
    $prj_id=$_POST['prj_id'];
    if($prj_id){
        $prj_id="'$prj_id'";
    }else{$prj_id='NULL';}

    $cat_id=$_POST['cat_id'];
    $month=$_POST['month'];
    $year=$_POST['year'];
    $q_update="UPDATE expected_income SET Income_Desc='$desc', Income_Amount='$amt', Prj_Id=$prj_id WHERE Income_Id='$ee_id'";
    $q_update_run=mysqli_query($connection,$q_update);
    if($q_insert_run){
        $_SESSION['status'] = "Income Deleted";
        $_SESSION['status_code'] = "success";
        header('Location: fp_income_tbl.php?cat_id='.$cat_id.'&month='.$month.'&year='.$year);
    }
    else{
        $_SESSION['status'] = "Error Deleting Income";
        $_SESSION['status_code'] = "error";
        header('Location: fp_income_tbl.php?cat_id='.$cat_id.'&month='.$month.'&year='.$year);
    }
}
if(isset($_POST['delIncome'])){
    $ed_id=$_POST['ee_id'];
    $cat_id=$_POST['cat_id'];
    $month=$_POST['month'];
    $year=$_POST['year'];
    $q_del="UPDATE expected_income set Income_Status=0 WHERE Income_Id='$ed_id'";
    $q_del_run=mysqli_query($connection,$q_del);
    if($q_ed_run){
        $_SESSION['status'] = "Income Deleted";
        $_SESSION['status_code'] = "success";
        header('Location: fp_income_tbl.php?cat_id='.$cat_id.'&month='.$month.'&year='.$year);
    }
    else{
        $_SESSION['status'] = "Income Sub Expense";
        $_SESSION['status_code'] = "error";
        header('Location: fp_income_tbl.php?cat_id='.$cat_id.'&month='.$month.'&year='.$year);
    }
}
?>