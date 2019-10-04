<?
include('../../config.php');  
include('include/includes.php'); 


#if ($_POST['value']>0){
	$sql = 'UPDATE
				stages 
			SET  
				 s_budget = "'.$_POST['value'].'"
			WHERE 
				s_id = "'.$_POST['order_item_id'].'"
			';
		
	mysql_query($sql);
#}
#echo $sql; 

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


	