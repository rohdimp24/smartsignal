<?php
require_once 'login.php';

$query="SELECT DISTINCT(incidentName) FROM `incidents`";
$result=mysql_query($query);
$rowsnum=mysql_num_rows($result);

for($i=0;$i<$rowsnum;$i++)
{

	$row=mysql_fetch_row($result);
	$incident=$row[0];

	echo $incident."<br/>";


}


?>