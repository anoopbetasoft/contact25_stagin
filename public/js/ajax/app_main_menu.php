<?php 

include('../../config.php');  
include('include/includes.php'); 
	
$sql = 'SELECT 
			* 
		FROM 
			category
		WHERE 
			category.c_parent = 0
		AND
			category.c_active = 1
		ORDER BY
			category.c_name ASC
			
		';
		#die($sql);
$query		= mysql_query($sql) or die(mysql_error());
$row		= mysql_fetch_assoc($query);
$num_rows	= mysql_num_rows($query);
if ($num_rows >0){
	$i = 1;
	do{
		
		$result .= '
		
		
		<div class="navigation-item" >
                <a onClick="show_dropdown('.$i.');" id="menu'.$i.'" class="nav-item submenu-deploy rac-icon">'.$row['c_our_name'].'<em class="dropdown-item"></em></a>';
				
				
			$sql2 = 'SELECT 
						* 
					FROM 
						category
					WHERE 
						category.c_parent = "'.$row['c_id'].'"
					AND
						category.c_active = 1
					ORDER BY
						category.c_name ASC
						
					';
					#die($sql);
			$query2		= mysql_query($sql2);
			$row2		= mysql_fetch_assoc($query2);
			$num_rows2	= mysql_num_rows($query2);
			if ($num_rows2 >0){
				
				$result .= '		
                <div id="sub_menu'.$i.'"   class="nav-submenu">
                  ';
				 do{
					 
					 #onClick="main_cat_search('.$row2['c_id'].');"
					$result .= '		
              
                    <a href="search-'.$row['c_our_url'].'_'.$row2['c_our_url'].'_1" >  '.$row2['c_our_name'].' <em class="unselected-item"></em></a>
                   
               ';
				}while($row2	= mysql_fetch_assoc($query2));
				$result .= '		
                
                   
                </div>';
			}	
				
		$result .= '		
                
              
		<div class="sidebar-decoration"></div>';
		$i ++;
	}while($row	= mysql_fetch_assoc($query));
}else{
	$result .= ' 
		<div class="navigation-item">
                <a href="#" class="nav-item rac-icon">Problem Connecting to the Internet...<em></em></a>
                
            </div> 
		<div class="sidebar-decoration"></div>
		';
}
?>


	
    
    
		<?php echo $result?>
<?
if ($_POST['is_global'] == 1){
	echo '
	<div class="navigation-item">
                <a href="weapons-check" class="nav-item rac-icon">WEAPONS CHECK ... Admin ONLY<em></em></a>
                
            </div> 
		<div class="sidebar-decoration"></div>	';
		
	}?>