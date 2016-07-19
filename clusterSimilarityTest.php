

<?php

set_time_limit(0);
// based on this we need to find out the tokens in the text. all else is noise
require_once("SimilarDocument.php");
require_once("login.php");


//get the list of all the cases in the database
$queryCases="Select * from casesimilarity";
$resultCases=mysql_query($queryCases);
$rownumCases=mysql_num_rows($resultCases);
$arrCases=array();
for($i=0;$i<$rownumCases;$i++)
{
	$rowCases=mysql_fetch_row($resultCases);
	$arrCases[$i]=$rowCases[0];
}


//print_r($arrCases);


$lenCases=sizeof($arrCases);
for($kk=0;$kk<2000;$kk++)
{
	$caseId=$arrCases[$kk];
	$query="SELECT `similaritydata`,`cluster` FROM `casesimilarity` WHERE docId='".$caseId."'";
	//echo $query."<br/>";
	$result=mysql_query($query);
	$rowsnum=mysql_num_rows($result);
	if($rowsnum==0)
	{
		echo "This case is not available <br/>";

	}
	else
	{
		$row=mysql_fetch_row($result);
		$arrData=json_decode($row[0]);
		$caseCluster=$row[1];
		$tot=0;
		$mismatch=0;
		foreach ($arrData as $data) {
			$queryTokenizedData="SELECT cleanupDescription,cluster,docId,sno FROM `crmcleanup`,`casesimilarity` 
								where casesimilarity.docId=crmcleanup.sno and sno='".$data->docNum."'";
			$resultTokenizedData=mysql_query($queryTokenizedData);
			$rowTokenizedData=mysql_fetch_row($resultTokenizedData);
			//$count++;
			$tot=$tot+1;
			if($caseCluster!=$rowTokenizedData[1])
			{
				$mismatch=$mismatch+1;
				echo "originalcase=>".$caseId."=>original cluster=>".$caseCluster."matchDoc=>".$data->docNum."score=>
				".$data->score."mismatchCluster=>".$rowTokenizedData[1]."<br/>";
			}
			
		
		}
		$percentError=($mismatch/$tot)*100;
		echo "Percent Error =>".$percentError."<br/>";
	//echo "</table>";
		echo "<hr/>";
}

}
?>