<?php

if(isset($_POST['data'])){
	$data = $_POST['data'];
	$means = $_POST['means'];
$countdata = count($data);

$cluster = array(array());
foreach ($data as $key => $value) {
	$cluster[$value[0]][] = $value[1];
}
//echo "<pre>";
//var_dump($cluster);
echo "<div class='cluster-div' style='border:2px solid #eee; border-radious:4px;min-height:540px; background-color:#ccc; padding:30px;'>";

$j = 0;

foreach ($cluster as $key => $value) {
	# code...
	$cluster_value ='';
	foreach ($value as $key => $value1) {
		# code...
		$i=0;
		foreach ($value1 as $key => $value2) {
			# code...
			$cluster_value .= $value2;
			if($i==0){
				$cluster_value .= ',';
			}
			$i++;
		}
		$cluster_value .= "<br>";
	}
	echo "<h3><a href='../tsp-v1/index.php?listoflocations=".$cluster_value."' target='_blank' class='accHeader'>Draw Path For Cluster : {$j} </a></h3><br><br>";
	$j++;
}
echo "</div>";

}


?>