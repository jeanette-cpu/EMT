<?php
include('../../security.php');
include('../../includes/header.php'); 
include('../../includes/prj_admin_navbar.php'); 
$query = "SELECT * FROM userlog LEFT JOIN users on users.USER_ID = userlog.User_Id ";
$query_run = mysqli_query($connection, $query);
?>
<div class="container-fluid">
    <div class="card shadow mb-4">
        <div class="card-header py-3">
                <h5 class="m-0 font-weight-bold text-primary">Userlog</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <th>Username</th>
                        <th>Usertype</th>
                        <th>Login</th>
                        <th>Login</th>
                        <th>Duration</th>
                    </thead>
                    <tbody>
                    <?php
                        if(mysqli_num_rows($query_run)>0)
                        {
                            while($row = mysqli_fetch_assoc($query_run))
                            {
                                ?>
                        <tr>
                            <td><?php echo $row['USERNAME'];?></td>
                            <td><?php 
                                    $usertype= $row['USERTYPE'];
                                    if($usertype=='planning_eng')
                                    {
                                        echo 'Project Admin';
                                    }
                                    else if($usertype=='str_mgr')
                                    {
                                        echo 'Store Manager';
                                    }
                                    else if($usertype=='proj_mgr')
                                    {
                                        echo 'Project Manager';
                                    }
                                    else if($usertype=='foreman')
                                    {
                                        echo 'Engineer';
                                    }
                                    else
                                    {
                                        echo $usertype;
                                    }
                                ?>
                            </td>
                            <td ><?php $login = $row['Login_Time']; echo $login;?></td>
                            <td><?php $logout = $row['Logout_Time']; echo $logout?></td>
                            <td>
                                <?php 
                                    $login=strtotime($login);
                                    $logout=strtotime($logout);
                                    $hours=($logout - $login)/3600;
                                    if($hours>=1)
                                    {
                                        echo floor($hours).'hr(s) ';
                                        $mins = ($logout - $login)% 60;
                                        echo $mins.'mins';
                                    }
                                   else
                                   {
                                        $mins = (int)(($logout - $login) / 60);
                                        if($mins>1)
                                        {
                                            echo $mins.' mins';
                                        }
                                        else{
                                            $seconds = (int)(($logout - $login) % 60);
                                            echo $seconds.' seconds';
                                        }
                                   }
                                ?>
                            </td>
                        </tr>
                        <?php
                    }
                }
                else
                {
                    echo "No Record Found";
                }
            ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<?php
include('../../includes/scripts.php');
include('../../includes/footer.php');
?>