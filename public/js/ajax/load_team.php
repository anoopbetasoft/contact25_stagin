<?php 

include ("../../config.php");


$sql = "SELECT s_num FROM spares GROUP BY spares.s_num ORDER BY spares.s_num ASC";
$query = mysql_query($sql);
$row = mysql_fetch_assoc($query);
$num_rows = mysql_num_rows($query);

$data = array();	
$i = 0;
if ($num_rows>0){
	do{
		array_push($data, $row['s_num']);
			#echo "'n_".$i."':'".$row['p_name']."'"; ## 'one': 1, 'two': 2, 'three': 3
		if (($i +1) != $num_rows){
			#echo ",";
		}
		$i++;
	}while($row = mysql_fetch_assoc($query));
}
echo json_encode($data);
?>