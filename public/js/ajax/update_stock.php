<?php 

include ("../../config.php");

$sql = 'UPDATE spares SET s_qty = "'.$_POST['stock_level'].'" WHERE s_id = "'.$_POST['id'].'"';
mysql_query($sql);

?>