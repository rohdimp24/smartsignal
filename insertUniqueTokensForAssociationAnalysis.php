<?php
require_once 'login.php';


$query="Select sno,cleanupDescription from crmcleanup";
$result=mysql_query($query);
$rowsnum=mysql_num_rows($result);
for($i=0;$i<$rowsnum;$i++)
{
	$row=mysql_fetch_row($result);
	$sno=$row[0];
	$cleanupDescription=$row[1];

	$uniqueString=getUniqueString($cleanupDescription);
	echo "cleanupDescription=>".$cleanupDescription."<br/>unique=>".$uniqueString."<br/>";
	$updateQuery="UPDATE `crmcleanup` SET `uniquetokens`='".$uniqueString."' WHERE sno='".$sno."'";
	$resultupdateQuery=mysql_query($updateQuery);
	
}


function getUniqueString($tempString){
	$arrTemp=explode(" ",$tempString);
	$arrfi=array();
	for($i=0;$i<sizeof($arrTemp);$i++)
	{
		$str=trim($arrTemp[$i]);
		if(!in_array($str, $arrfi))
		{
			//echo "adding".$str."<br/>";
			array_push($arrfi, $str);
		}
	}
	return implode(" ",$arrfi);
}



?>