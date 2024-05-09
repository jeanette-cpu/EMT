<?php
include('dbconfig.php');
// // for approval
// $q_companies="SELECT DISTINCT Comp_Name, Comp_Id FROM company WHERE Comp_Approval=2 GROUP BY Comp_Name";
// $q_companies_run=mysqli_query($connection, $q_companies);
// //IDS TO EXCEPT
// if(mysqli_num_rows($q_companies_run)>0){
//     while($row=mysqli_fetch_assoc($q_companies_run)){
//         $comp_id_arr[] = $row['Comp_Id'];
//     }
//     $comp_ids = implode("', '", $comp_id_arr);
//     $duplicate ="SELECT * FROM company where Comp_Id not in ('$comp_ids') AND `Comp_Approval`=2";
//     $duplicate_run = mysqli_query($connection, $duplicate);
//     if(mysqli_num_rows($duplicate_run)>0){ //duplicated submit
//         while($row_dup=mysqli_fetch_assoc($duplicate_run)){
//             // $comp_id_dup[]=$row_dup['Comp_Id'];
//             $ccomp_id=$row_dup['Comp_Id'];
//             $update="UPDATE company SET `Comp_Approval`=0 where Comp_Id='$ccomp_id'";
//             $update_run=mysqli_query($connection,$update);
//         }
//         // $dup_ids = implode("', '", $comp_id_dup);
//     }
// }

//deleting records
// $q_users="SELECT u.USER_ID, c.Comp_Id from users as u LEFT JOIN company as c on c.User_Id = u.USER_ID WHERE Comp_Id in ('16', '17', '20', '21', '22', '23', '24', '25', '30', '33', '37', '38','44')";
// $q_users_run=mysqli_query($connection,$q_users);
// if(mysqli_num_rows($q_users_run)>0){
//     while($row_u=mysqli_fetch_assoc($q_users_run)){
//         $user_id_arr[]=$row_u['USER_ID'];
//         // $del_user="DELETE * FROM users WHERE USER_ID='$user_id'";
//         // $del_user_run=mysqli_query($connection,$del_user);
//     }
//     $user = implode("', '", $user_id_arr);
//     echo $user;
// }

//company id
//  $q_companies="SELECT u.USER_ID, c.Comp_Id 
// FROM company as c
// LEFT JOIN users as u on u.USER_ID=c.User_Id
// WHERE Comp_Id in ('16', '17', '20', '21', '22', '23', '24', '25', '30', '33', '37', '38','44')";
// $q_companies_run=mysqli_query($connection,$q_companies);
// if(mysqli_num_rows($q_companies_run)>0){
//     while($row_c=mysqli_fetch_assoc($q_companies_run)){
//         $comp_id=$row_c['Comp_Id'];
//         //SERVICES
//         $del_serv="DELETE FROM service WHERE  Comp_Id=$comp_id";
//         $del_serv_run=mysqli_query($connection,$del_serv);
//         if($del_serv_run){
//         }
//         else{
//             echo 'error service delete: comp_id='.$comp_id.'<br>';
//         }
//         //PRODUCTS
//         $del_prod="DELETE FROM product WHERE  Comp_Id=$comp_id";
//         $del_prod_run=mysqli_query($connection,$del_prod);
//         if($del_prod_run){
//         }
//         else{
//             echo 'error product delete: comp_id='.$comp_id.'<br>';
//         }
//         //NOTIF
//         $del_notif="DELETE FROM notification  WHERE Comp_Id=$comp_id";
//         $del_notif_run=mysqli_query($connection,$del_notif);
//         if($del_notif_run){
//         }
//         else{
//             echo 'error notif delete: comp_id='.$comp_id.'<br>';
//         }
//         // DELETE COMP
//         $del_comp="DELETE FROM company WHERE Comp_id=$comp_id";
//         $del_comp_run=mysqli_query($connection,$del_comp);
//         if($del_comp_run){
//         }
//         else{
//             echo 'error company delete: comp_id='.$comp_id.'<br>';
//         }
//     }
// }

?>