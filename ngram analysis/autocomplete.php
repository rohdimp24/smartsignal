<?php
require_once('login.php');
/* get querystring parameter */
$param = ($_GET['query']);

//return $param;

if(strlen($param)>0)
{

	/*protect from sql injections */

	/* query the database */
	$query = "SELECT Distinct ngram FROM ngrams WHERE ngram LIKE '".$param."%'";
	//echo $query;
	$result = mysql_query($query);
	$num_rows = mysql_num_rows($result);

	$output='';
	// show maximum of 10 suggestions
	/* loop through and return matching entries */
	if($num_rows>10)
		$num_rows=10;
	for ($x = 0; $x <$num_rows; $x++) {

	$row = mysql_fetch_row($result);
	if($x==($num_rows-1))
		$output .= $row[0];
	else
		$output .= $row[0].";";

	}
	//$tagArrayString=implode(";",$tagArray);
	echo $output;
}
?>
