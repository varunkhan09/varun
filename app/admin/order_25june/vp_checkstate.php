<?php
include 'vp_dbconfig.php';

$orderid = $_REQUEST['orderid'];
$query = "select MAX(state) from vendor_processing where orderid=$orderid";
$result = mysql_query($query);
while($row = mysql_fetch_row($result))
{
	echo $row[0];
}

?>