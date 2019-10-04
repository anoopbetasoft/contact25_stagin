<?
include('../../config.php');  




$sql = 'SELECT 
			s_budget
		FROM 
			stages
		
		';

$query		= mysql_query($sql);
$row		= mysql_fetch_assoc($query);
$num_rows	= mysql_num_rows($query);

$budget = 0;
if ($num_rows >0){
	do{

		
		$budget = $budget+$row['s_budget'];
		
		
	}while($row	= mysql_fetch_assoc($query));
}
if ($profit < 0){
	echo '<span style="color:red">';	
}else{
	echo '<span style="color:green">';		
}
echo '£'.number_format($budget, 0, '.', ',');
echo '</span>';	

?>


	