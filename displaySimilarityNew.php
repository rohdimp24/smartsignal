

<?php

set_time_limit(0);
// based on this we need to find out the tokens in the text. all else is noise

require_once("login.php");

?>
<link rel="stylesheet" href="css/bootstrap.css" />

<a href="ssDashboard.php">Back to dashboard</a>

<form action= "<?php echo $_SERVER['PHP_SELF']; ?>" method="GET" >
<div>
<?php
echo "<b>Enter the case id for similar cases </b>";
echo "<input type='text' id='case_uuid' name='case_uuid' />";
echo "<input type='submit' class='btn-success btn-small' style='margin-bottom: 10px;' 
        name='querySubmit' value='Result'>";

?>
    </div>
</form>



<?php

if(isset($_GET["case_uuid"])&&isset($_GET["querySubmit"])){

	$caseId=$_GET['case_uuid'];


	$queryDetails="Select * from crmcleanupnew where sno='".$caseId."'";

	$resultDetails=mysql_query($queryDetails);
	$rowDetails=mysql_fetch_row($resultDetails);
	echo "<span style='color:blue;font-weight:bold'>OriginalCase</span>=>".$rowDetails[1]."<br/>";
	echo "<span style='color:blue;font-weight:bold'>tokenizedcase</span>=><b>".$rowDetails[3]."</b><br/>";

	echo "<hr/>";

	$query="SELECT `firstDoc`, `secondDoc`, `score`, `clusterId`,`vector` FROM `casesimilarity` where  firstDoc='".$caseId."'";
	$result=mysql_query($query);
	$rowsnum=mysql_num_rows($result);
	if($rowsnum==0)
	{
		echo "This case is not available <br/>";

	}
	else
	{

		echo "<table border='1'>";
		echo "<tr><th>SimilarDocNumber</th><th>Similarity Score</th><th>ClusterNumber</th><th>Tokenzed Content</th><th>Original Content</th></tr>";
		for($i=0;$i<$rowsnum;$i++){
			$row=mysql_fetch_row($result);
			$queryDetails="Select * from crmcleanupnew where sno='".$row[1]."' ";
			$resultDetails=mysql_query($queryDetails);
			$rowDetails=mysql_fetch_row($resultDetails);

			echo "<tr><td>".$row[1]."</td><td>".$row[2]."</td><td>".$row[3]."</td>
			<td>".$row[4]."</td><td>".$rowDetails[3]."</td></tr>";
			
		}
		echo "</table>";
	}
}

?>