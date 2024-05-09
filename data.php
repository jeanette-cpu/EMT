<?php
// COUNT NUMBER OF PAYSLIP PER MONTH
$connection = mysqli_connect("localhost","root","","emt");
$query = "SELECT LEFT(MONTHNAME(P_DATE),3) AS month,COUNT(*) AS count
          FROM payslip
          WHERE YEAR(P_DATE)='2020' AND P_STATUS=1
          GROUP BY MONTH(P_DATE)";
$result = $connection->query($query);
if($result->num_rows > 0)
    {
        $final_array = array();
        while($row = $result->fetch_assoc())
        {
            $arr = array
            (
                'month' => $row ['month'],
                'count' => $row ['count']
            );
            $final_array [] = $arr;
        }
        return json_encode($final_array); 
    }
else
    {
        echo "error";
    }
$connection->close();
?>