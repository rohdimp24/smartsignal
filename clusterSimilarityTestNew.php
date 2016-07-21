

<?php

set_time_limit(0);
// based on this we need to find out the tokens in the text. all else is noise
require_once("login.php");


//get the list of all the cases in the database
$queryCases="Select max(firstDoc) from casesimilarity";
$resultCases=mysql_query($queryCases);
$rowCases=mysql_fetch_row($resultCases);
$maxCase=$rowCases[0];

$fc=fopen("clustersFinalNew.txt","r");
$arrClusterMap=array();
while(($clusterDetail=fgets($fc))!=null)
{
	//echo $clusterDetail;
	list($docNum,$clusterNum)=explode(":",$clusterDetail);
	$arrClusterMap[$docNum]=$clusterNum;
}



//print_r($arrCases);

for($kk=1;$kk<=$maxCase;$kk++)
{
	$caseId=$kk;
	$query="SELECT `firstDoc`,`secondDoc`,`clusterId`,`score` FROM `casesimilarity` WHERE firstDoc='".$caseId."'";
	//echo $query;
	$result=mysql_query($query);
	$rowsnum=mysql_num_rows($result);
	if($rowsnum==0)
	{
		echo "This case is not available <br/>";

	}
	else
	{
		$tot=0;
		$mismatch=0;
		
		for($j=0;$j<$rowsnum;$j++)
		{
			$row=mysql_fetch_row($result);
			$originalCase=$row[0];
			$matchingCase=$row[1];
			#if($originalcase==$matchingCase)
			$caseCluster=$row[2];
			$score=$row[3];
			//$count++;
			$tot=$tot+1;
	//		echo "sdsd". $arrClusterMap[$originalCase]."<br/>";
			if($caseCluster!=intval($arrClusterMap[$originalCase]))
			{
				$mismatch=$mismatch+1;
				echo "originalcase=>".$caseId."=>original cluster=>".$arrClusterMap[$originalCase]."matchDoc=>".$matchingCase."score=>
				".$score."mismatchCluster=>".$caseCluster."<br/>";
			}
			
		
		}
		$percentError=($mismatch/$tot)*100;
		if($percentError>1)
		{
			echo "Percent Error =>".$percentError."<br/>";
			//echo "</table>";
			echo "<hr/>";
		}
}

}
?>