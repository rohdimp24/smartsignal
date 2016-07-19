<a href="ssDashboard.php">Back to dashboard</a>
<?php

set_time_limit(0);

require_once("login.php");

$listAssets=array();

$queryAssets="Select Distinct(FirstEntity) from associateentity";
$resultAssets=mysql_query($queryAssets);
$rowsnumAssets=mysql_num_rows($resultAssets);
for($i=0;$i<$rowsnumAssets;$i++)
{
	$rowAsset=mysql_fetch_row($resultAssets);
	array_push($listAssets,$rowAsset[0]);
}




//print_r($listAssets);
?>

<form enctype="multipart/form-data" name="myForm"
  action="<?php echo $_SERVER['PHP_SELF']?>" method="POST" >
  	<!-- <table border='0' bgcolor='#f5f5f5' width='100%'  cellspacing=0 cellpadding='5' align='center'> -->
		<?php
		$cbo = '<tr><td>Choose Asset:<select name="assetName" id="assetName">';

		foreach ($listAssets as $key)
		{
			if($key==" ")
				continue;
			$cbo .='<option value="'.trim($key,'"').'">'.trim($key,'"').'</option>';
			
		}
		$cbo .= '</select>';
		echo $cbo;
		
		?>
		
		<!-- <td> -->
		<input type="submit" name="submit" value="Get Dependencies" />
		<!-- </td> -->
		<!-- </tr> -->
	<!-- </table>  -->
 </form>
</fieldset>
</body>
</html>


<?php
if (isset($_POST['submit'])) 	
{
	$entity=$_POST['assetName'];
	$query="Select * from associateentity where FirstEntity ='".$entity."'";
	$result=mysql_query($query);
	$rowsnum=mysql_num_rows($result);

	$str='';
	echo "Displaying the graph for <b>".$entity."</b><br/>";
	echo "<table border='1'>";
	echo "<tr><th>FirstEntity</th><th>SecondEntity</th><th>AB</th><th>A~B</th><th>~AB</th><th>~A~B</th><th>ChiSquare</th><th>PercentOccurence</th></tr>";

	for($i=0;$i<$rowsnum;$i++)
	{

		$row=mysql_fetch_row($result);
		$firstEntity=str_replace("-", "_", $row[0]);
		$firstEntity=str_replace("/", "_", $firstEntity);

		$secondEntity=str_replace("-", "_", $row[1]);
		$secondEntity=str_replace("/", "_", $secondEntity);

	//$str=$str.$target."-%3E".trim($row[0]).";";

		//$str.=$firstEntity." -%3E ".$secondEntity.";";
		if(strlen($secondEntity)>2){
			$str.=$firstEntity." -%3E ".$secondEntity.";";
			//echo $firstEntity."=>".$secondEntity."=>".$row[2]."=>".$row[3]."=>".$row[4]."=>".$row[5]."=>".$row[6]."=>".$row[7]."<br/>";
			echo "<tr>";
			echo "<td>".$firstEntity."</td>";
			echo "<td>".$secondEntity."</td>";
			echo "<td>".$row[3]."</td>";
			echo "<td>".$row[4]."</td>";
			echo "<td>".$row[5]."</td>";
			echo "<td>".$row[6]."</td>";
			echo "<td>".$row[7]."</td>";
			echo "<td>".$row[8]."</td>";
			
			echo "</tr>";

			//$str.=$firstEntity." -- ".$secondEntity." [type=s];";
		}
		if($i==0)
			continue;

		$querySecond="Select * from associateentity where FirstEntity ='".$row[1]."'";
		//echo $querySecond;
		$resultSecond=mysql_query($querySecond);
		$rowsnumSec=mysql_num_rows($resultSecond);
		for($j=0;$j<2;$j++)
		{
			$rowSecond=mysql_fetch_row($resultSecond);
			$thirdEntity=str_replace("-", "_", $rowSecond[1]);
			$thirdEntity=str_replace("/", "_", $thirdEntity);

			/*if(strlen($thirdEntity)>2){
				$str.=$secondEntity." -%3E ".$thirdEntity.";";
				//echo $secondEntity."=>".$thirdEntity."=>"."<br/>";
				echo "<tr>";
				echo "<td>".$secondEntity."</td>";
				echo "<td>".$thirdEntity."</td>";
				echo "<td>".$rowSecond[3]."</td>";
				echo "<td>".$rowSecond[4]."</td>";
				echo "<td>".$rowSecond[5]."</td>";
				echo "<td>".$rowSecond[6]."</td>";
				echo "<td>".$rowSecond[7]."</td>";
				echo "<td>".$rowSecond[8]."</td>";
				
				echo "</tr>";

							//$str.=$secondEntity." -- ".$thirdEntity." [type=s];";
			}
			*/
		}
		


	}

	//echo $str."<br/>";

	

	//echo "http://sandbox.kidstrythisathome.com/erdos/";

	$source="http://chart.apis.google.com/chart?cht=gv&chl=digraph{".$str."}";
	echo "<img src='".$source."'></img>";
}
?>


