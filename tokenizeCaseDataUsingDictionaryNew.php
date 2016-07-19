<?php
require_once 'login.php';
require_once("AnalyzedCase.php");
require_once("dictionaryItem.php");

$DEBUG_PRINT=0;

//read the list of the keywords
$fs=fopen("new_dictionary.txt","r");
$arrfinal=array();
while(($strfp = fgets($fs))!=null){
	/*if(!strlen(trim($strfp))==0)
	{
		$strfp=trim($strfp);
		$strfp=trim($strfp,'"');
		//$obj=new dictionaryItem($variant,$canonical);
		array_push($arrfinal,strtolower($strfp));

		//}
	}*/

	if(!strlen(trim($strfp))==0)
		{
			list($variant,$canonical)=explode("=>",$strfp);

			$obj=new dictionaryItem(strtolower($variant),strtolower($canonical));
			array_push($arrfinal,$obj);
		}
}

//print_r($arrfinal);

//exit();

function my_in_array($entry,$arr)
{
	/*$len=sizeof($arr);

	foreach ($arr as $item) {
		if($item==$entry)
			return $item;
	}
	return null;
	*/
	$len=sizeof($arr);

	foreach ($arr as $item) {
		if($item->variant==$entry)
			return trim($item->canonical);
	}
	return null;

}

function ContainsNumbers($String){
	$tt=preg_match('/\\d/', $String) > 0;
	// if($tt)
	// 	echo "number found..";
	// else
	// 	echo "no number found..";
	return $tt;
}



////////////////////////////////////
#$fp=fopen("crmcases.txt","r");
#$count=0;
$fetchQuery="Select normalizedUnigramDesc from crmcleanupnew";
$fetchResult=mysql_query($fetchQuery);
$fetchRowsNum=mysql_num_rows($fetchResult);

#echo $fetchRowsNum;

for($j=1;$j<=$fetchRowsNum;$j++)
{
	$fetchRow=mysql_fetch_row($fetchResult);
	$details=$fetchRow[0];
	#if($j<4)
	#	continue;
	if($j>11000)
	 	break;
	else
	{
		//$details="Bearing Metal Temp - Generator B has been decreasing and flatlining to 0degF before returning to the estimate";

		//echo $details."<br/>";		
		if(strlen($details)<5)
			continue;
		else
		{

			echo $j."=>"."<b>Original=></b>".$details."<br/>";
			$originalDescription=$details;
			//this will remove any text which has a digit and alhpabets
			$details=preg_replace('/[^A-Za-z0-9 _\-\+\&\,\#]/','', $details);
			$details=trim($details);
			$details=trim($details,'"');
			$details=str_ireplace("\"", ' ', $details);
		    $details=str_ireplace(">", ' ', $details);
		    $details=str_ireplace("@", ' ', $details);
		    $details=str_ireplace("<", ' ', $details);
		    $details=str_ireplace(":", ' ', $details);
		    $details=str_ireplace(".", ' ', $details);
		    $details=str_ireplace("[", ' ', $details);
		    $details=str_ireplace("]", '', $details);
		    $details=str_ireplace("_", ' ', $details);
		    $details=str_ireplace(",", ' ', $details);
		    $details=str_ireplace("#", ' ', $details);
		    $details=str_ireplace("-", ' ', $details);
		    $details=str_ireplace("/", ' ', $details);
		   # $details = preg_replace('/[0-9]+/', ' ', $details);

		    echo "<b>Cleanup (after removing puctuations)=></b>".$details."<br/>";		   

			$arrTempTerms=explode(" ",$details);
			$lenCase=sizeof($arrTempTerms);
			$str='';
			
			$lenCase=sizeof($arrTempTerms);
			if($DEBUG_PRINT)
				echo "case length=>".$lenCase."<br/>";
			$str='';
			for($i=0;$i<$lenCase;)
			{
				$largestStringFound='';
				$firstword='';
				$secondword='';
				$thirdword='';


				$firstword=strtolower($arrTempTerms[$i]);
				if($i<=($lenCase-3))
				{
					$secondword=strtolower($arrTempTerms[$i+1]);
					$thirdword=strtolower($arrTempTerms[$i+2]);
				}
				if(ContainsNumbers($secondword) && $i<($lenCase-4))
				{
					//then considert 4th word
					$secondword=strtolower($arrTempTerms[$i+2]);				
					$thirdword=strtolower($arrTempTerms[$i+3]);
				
				}
				if(ContainsNumbers($thirdword) && $i<($lenCase-4))
				{
					//then considert 4th word
					//$secondword=strtolower($arrTempTerms[$i+2]);				
					$thirdword=strtolower($arrTempTerms[$i+3]);
				
				}

				
				$tempword=trim($firstword." ".$secondword." ".$thirdword);
				
				if($DEBUG_PRINT)
					echo "tempword=>".$tempword."<br/>";


				//trigrams
				if($i<($lenCase-3))
				{
					//echo "<br/>inside trigram=>".$tempword;
					if($firstword==" "||$secondword==" "||$thirdword==" ")
					{
						$i=$i+1;
						continue;
					}
					//if(in_array($tempword,$arrfinal))
					$retWord=my_in_array($tempword,$arrfinal);
					if($retWord)
					{
						//echo "trigram added";
						//$str.=$firstword."_".$secondword."_".$thirdword." ";
						$str.=str_replace(" ", "_", $retWord)." ";
						$i=$i+3;
						if($DEBUG_PRINT)
							echo "tri=>".$tempword."=>".$retWord."=>". $i."<br/>"; 
						continue;
						//$largestStringFound=$retWord;
						//echo "<br/>the largest".$largestStringFound;
					}

				}

				//bigram
				if($i<($lenCase-2))
				{
					
					$tempword=$firstword." ".$secondword;
					//echo "<br/>inside bigram=>".$tempword;
					//echo strlen($firstword).",".strlen($secondword)."<br/>";
					if((strlen($secondword)<1)||
						(strlen($tempword)<1))
					{
						$i=$i+1;
						continue;
					}
					//if(in_array($tempword,$arrfinal))
					$retWord=my_in_array($tempword,$arrfinal);
					if($retWord)
					{
						//echo "bigram added";
						$str.=str_replace(" ", "_", $retWord)." ";
						$i=$i+2;
						if($DEBUG_PRINT)
							echo "bi=>".$tempword."=>".$retWord."=>".$i."<br/>";
						continue;
						//$largestStringFound=$retWord;
						//echo "<br/>the largest".$largestStringFound;
					}
				}

				//unigram
				#$tempword=strtolower($arrTempTerms[$i]);
				#if($tempword==" ")
				#	continue;
				$tempword=$firstword;
				if($DEBUG_PRINT)
					echo "reassigned=>".$tempword."<br/>";
				$retWord=my_in_array($tempword,$arrfinal);
				if($retWord)
				{
					$str.=$retWord." ";
					$i=$i+1;
					if($DEBUG_PRINT)
						echo "uni=>".$tempword."=>".$retWord."=>".$i."<br/>";
					continue;
				}
				else
				{
					//echo $i."<br/>";
					$i=$i+1;
					$str.=$tempword." ";
					if($DEBUG_PRINT)
						echo "nochange=>".$tempword."<br/>";
				}


				
				#if(strlen($largestStringFound)>1)
					#$str.=str_replace(" ","_",$largestStringFound)." ";
			}


			$str=preg_replace('/[0-9]+/', ' ', $str);
			echo "<b>Converted to =></b>".$str."<br/>-------------------------------------<br/>";
			if(strlen(trim($str))>2)
			{
				//enter to the sql
				$query="UPDATE `crmcleanupnew` SET normalizedNGramDesc='".$str."' Where `sno`='".$j."'";
				$result=mysql_query($query);
				if(!$result)
				{
					echo $query;
				}
			}

		}
		
	}

}

//fclose($fp);
?>