<?php 

include ("../../config.php");


$sql = 'SELECT hotspots.*, SQRT(
    POW(69.1 * (hotspots.hs_lat - 53.85335), 2) +
    POW(69.1 * (-1.57836 - hotspots.hs_long) * COS(hotspots.hs_lat / 57.3), 2)) AS distance
FROM hotspots ORDER BY distance LIMIT 25'; ##HAVING distance < 25  ORDER BY distance ;
$query = mysql_query($sql);
$row = mysql_fetch_assoc($query);
$num_rows = mysql_num_rows($query);

$data = array();	

if ($num_rows>0){
	

		$value_3 = 'home';
	do{
		
		array_push($data, array($row['hs_lat'], $row['hs_long'], $value3));
		
	}while($row = mysql_fetch_assoc($query));
}
echo json_encode($data);
?>