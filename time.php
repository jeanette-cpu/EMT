<h1>time check</h1>
<?php
session_start();
echo time(); echo '<br>';
if (time()-$_SESSION['logCheck']>900)
{
    echo 'mallogout';
}

?>