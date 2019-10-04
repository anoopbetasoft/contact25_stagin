<?php

include("../../include/config.php");

global $db; 
  
	if (strlen($_POST['sort_by'])>0)
	{
		$_SESSION['extra_sort'] = 'ORDER BY '.$_POST['sort_by'];
		$_SESSION['limit_start'] = 0;
	}

	if (empty($_SESSION['limit_start'])){
		
		$_SESSION['limit_start'] = 0;
		
	}
	
	if ($_POST['limit_start']>-1){
		
		$_SESSION['limit_start'] = $_POST['limit_start'];
	}

	if ($_POST['limit_start'] == -1){
		
		$_SESSION['limit_start'] = $_SESSION['limit_start']-12;
		//die("test");
	}

	if ($_POST['limit_start'] == -2){
		
		$_SESSION['limit_start'] = $_SESSION['limit_start']+12;
	}

	if ($_SESSION['limit_start'] < 0){
		
		$_SESSION['limit_start'] = 0;
	}


	// find the total results for pagination
$sql = '
  		SELECT 
			s_id, 
			s_s_id, 
			s_price_buy_inc_vat,
			s_price_lend_inc_vat, 
			s_desc, s_label, 
			s_condition, 
			s_u_id, 
			s_qty, 
			users.u_name,
			stock_room.sr_name 
		FROM 
			stock_c25, users, stock_room, stock_box 
		WHERE 
			stock_c25.s_u_id = users.u_id
		AND 
			stock_c25.s_room = stock_room.sr_id
		AND 
			stock_c25.s_box = stock_box.sb_id
		AND
			stock_room.sr_id = "'.$_POST['filter_by_room'].'"
		AND
			stock_box.sb_id = "'.$_POST['filter_by_box'].'"
		AND
			users.u_id = "'.$_SESSION['c25_id'].'"
		AND
			stock_c25.s_label like "%'.$_SESSION['search_my_stuff'].'%"
		Limit 200
	
			
			
			'; # Where is the condition of the book #


	$query = mysqli_query($db,$sql);
	$row = mysqli_fetch_assoc($query);
	$num_rows = mysqli_num_rows($query);

	$total_rows_for_pagination = $num_rows;


	if($_POST['filter_by_room']>0){
		
		$table_room = ", stock_room";
		$where_room = 'AND 
			stock_c25.s_room = stock_room.sr_id
			AND
			stock_room.sr_id = "'.$_POST['filter_by_room'].'"';
	}else{
		
		$table_room = "";
		$where_room = "";
	}

if($_POST['filter_by_box']>0){
		
		$table_box = ", stock_box";
		$where_box = 'AND 
			stock_c25.s_box = stock_box.sb_id
		AND
			stock_box.sb_id = "'.$_POST['filter_by_box'].'"';
	}else{
		
		$table_box = "";
		$where_box = "";
	}

  $sql = '
  		SELECT 
			s_id, 
			s_s_id, 
			s_price_buy_inc_vat,
			s_price_lend_inc_vat, 
			s_desc, s_label, 
			s_condition, 
			s_u_id, 
			s_qty, 
			users.u_name
		FROM 
			stock_c25, users '.$table_room.'  '.$table_box.'   
		WHERE 
			stock_c25.s_u_id = users.u_id
		'.$where_room.'
		'.$where_box.'
		
		AND
			users.u_id = "'.$_SESSION['c25_id'].'"
		AND
			stock_c25.s_label like "%'.$_SESSION['search_my_stuff'].'%"
		'.$_SESSION['extra_sort'].'
			limit '.$_SESSION['limit_start'].',12
			
			
			'; # Where is the condition of the book #

	$query = mysqli_query($db,$sql);
	$row = mysqli_fetch_assoc($query);
	$num_rows = mysqli_num_rows($query);

		
			if ($num_rows>0){
		
			do{
								
				
				/*
				if ($row['s_price_buy_inc_vat']>0){
					$price = $row['s_price_buy_inc_vat'];
					$colour = 'info';
				}elseif ($row['s_price_lend_inc_vat']>0){
					$price = $row['s_price_lend_inc_vat'];
					$colour = 'warning';
				}
				*/
				
				
				$path = 'https://contact25.com/uploads/7_'.$row['s_s_id'];
				$type = pathinfo($path, PATHINFO_EXTENSION);
				$data = file_get_contents($path);
				$base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);

				if ($base64 == 'data:image/;base64,PCFkb2N0eXBlIGh0bWw+CjxodG1sIGNsYXNzPSJuby1qcyIgbGFuZz0iIj48aGVhZD4KPG1ldGEgaHR0cC1lcXVpdj0iQ29udGVudC10eXBlIiBjb250ZW50PSJ0ZXh0L2h0bWw7IGNoYXJzZXQ9dXRmLTgiIC8+CjxtZXRhIG5hbWU9Im1zdmFsaWRhdGUuMDEiIGNvbnRlbnQ9IkY1MTdEMEQ1OTE5NzU1QzlEMzNGQUFCMTg4NTM1MUI1IiAvPgoJCTwhLS0gQmFzaWMgcGFnZSBuZWVkcwoJCT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09IC0tPgkKICAgPG1ldGEgaHR0cC1lcXVpdj0ieC11YS1jb21wYXRpYmxlIiBjb250ZW50PSJpZT1lZGdlIj4KICAgICAgICA8dGl0bGU+Q29udGFjdDI1IC0gQnV5ICZhbXA7IFNlbGwgQW55dGhpbmchIDwvdGl0bGU+CiAgICAgICAgPG1ldGEgbmFtZT0iZGVzY3JpcHRpb24iIGNvbnRlbnQ9IiI+CgkJCgkJPCEtLSBNb2JpbGUgc3BlY2lmaWMgbWV0YXMKCQk9PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PSAtLT4JCQogICAgICAgIDxtZXRhIG5hbWU9InZpZXdwb3J0IiBjb250ZW50PSJ3aWR0aD1kZXZpY2Utd2lkdGgsIGluaXRpYWwtc2NhbGU9MSI+CgkJCgkJPCEtLSBGYXZpY29uCgkJPT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT0gLS0+CgkJPGxpbmsgcmVsPSJzaG9ydGN1dCBpY29uIiB0eXBlPSJpbWFnZS94LWljb24iIGhyZWY9ImltZy9mYXZpY29uLmljbyI+CgoJCTwhLS0gRk9OVFMKCQk9PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PSAtLT4KCQk8bGluayBocmVmPSdodHRwczovL2ZvbnRzLmdvb2dsZWFwaXMuY29tL2Nzcz9mYW1pbHk9T3BlbitTYW5zOjQwMCwzMDAsMzAwaXRhbGljLDQwMGl0YWxpYyw2MDAsNjAwaXRhbGljLDcwMCw3MDBpdGFsaWMsODAwLDgwMGl0YWxpYycgcmVsPSdzdHlsZXNoZWV0JyB0eXBlPSd0ZXh0L2Nzcyc+CgkJPGxpbmsgaHJlZj0naHR0cHM6Ly9mb250cy5nb29nbGVhcGlzLmNvbS9jc3M/ZmFtaWx5PU1vbnRzZXJyYXQ6NDAwLDcwMCcgcmVsPSdzdHlsZXNoZWV0JyB0eXBlPSd0ZXh0L2Nzcyc+CgkJPGxpbmsgaHJlZj0naHR0cHM6Ly9mb250cy5nb29nbGVhcGlzLmNvbS9jc3M/ZmFtaWx5PU9zd2FsZDo0MDAsMzAwLDcwMCcgcmVsPSdzdHlsZXNoZWV0JyB0eXBlPSd0ZXh0L2Nzcyc+CgkJPGxpbmsgaHJlZj0naHR0cHM6Ly9mb250cy5nb29nbGVhcGlzLmNvbS9jc3M/ZmFtaWx5PVVidW50dTo0MDAsMzAwaXRhbGljLDMwMCw0MDBpdGFsaWMsNTAwLDUwMGl0YWxpYyw3MDAsNzAwaXRhbGljJyByZWw9J3N0eWxlc2hlZXQnIHR5cGU9J3RleHQvY3NzJz4KCgkJPCEtLSBDU1MgIC0tPgoJCQoJCTwhLS0gQm9vdHN0cmFwIENTUwoJCT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09IC0tPgogICAgICAgIDxsaW5rIHJlbD0ic3R5bGVzaGVldCIgaHJlZj0iY3NzL2Jvb3RzdHJhcC5taW4uY3NzIj4KCQkKCQk8IS0tIGZvbnQtYXdlc29tZSBDU1MKCQk9PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PSAtLT4KICAgICAgICA8bGluayByZWw9InN0eWxlc2hlZXQiIGhyZWY9ImNzcy9mb250LWF3ZXNvbWUubWluLmNzcyI+CgkJCgkJPCEtLSBvd2wuY2Fyb3VzZWwgQ1NTCgkJPT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT0gLS0+CiAgICAgICAgPGxpbmsgcmVsPSJzdHlsZXNoZWV0IiBocmVmPSJjc3Mvb3dsLmNhcm91c2VsLmNzcyI+CiAgICAgICAgPGxpbmsgcmVsPSJzdHlsZXNoZWV0IiBocmVmPSJjc3Mvb3dsLnRoZW1lLmNzcyI+CiAgICAgICAgPGxpbmsgcmVsPSJzdHlsZXNoZWV0IiBocmVmPSJjc3Mvb3dsLnRyYW5zaXRpb25zLmNzcyI+CgkJCgkJPCEtLSBhbmltYXRlIENTUwoJCT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09IC0tPgogICAgICAgIDxsaW5rIHJlbD0ic3R5bGVzaGVldCIgaHJlZj0iY3NzL2FuaW1hdGUuY3NzIj4KCQkKCQk8IS0tIEZJTFRFUl9QUklDRSBDU1MKCQk9PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PSAtLT4KICAgICAgICA8bGluayByZWw9InN0eWxlc2hlZXQiIGhyZWY9ImNzcy9qcXVlcnktdWkubWluLmNzcyI+CgkJCgkJPCEtLSBmYW5jeWJveCBDU1MKCQk9PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PSAtLT4KICAgICAgICA8bGluayByZWw9InN0eWxlc2hlZXQiIGhyZWY9ImNzcy9mYW5jeWJveC9qcXVlcnkuZmFuY3lib3guY3NzIj4KCiAgICAgICAgPCEtLSBJbWFnZSBab29tIENTUwoJCT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09IC0tPgogICAgICAgIDxsaW5rIHJlbD0ic3R5bGVzaGVldCIgaHJlZj0iY3NzL2ltZy16b29tL2pxdWVyeS5zaW1wbGVMZW5zLmNzcyI+CgkJCgkJPCEtLSBNb2JpbGUgbWVudSBDU1MKCQk9PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PSAtLT4JCQogICAgICAgIDxsaW5rIHJlbD0ic3R5bGVzaGVldCIgaHJlZj0iY3NzL21lYW5tZW51Lm1pbi5jc3MiPgoJCQoJCTwhLS0gbm9ybWFsaXplIENTUwoJCT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09IC0tPgkJCiAgICAgICAgPGxpbmsgcmVsPSJzdHlsZXNoZWV0IiBocmVmPSJjc3Mvbm9ybWFsaXplLmNzcyI+CgogICAgICAgIDwhLS0gUlMgc2xpZGVyIENTUwoJCT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09IC0tPgoJCTxsaW5rIHJlbD0ic3R5bGVzaGVldCIgdHlwZT0idGV4dC9jc3MiIGhyZWY9ImxpYi9ycy1wbHVnaW4vY3NzL3NldHRpbmdzLmNzcyIgbWVkaWE9InNjcmVlbiIgLz4KCQkKCQk8IS0tIG1haW4gQ1NTCgkJPT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT0gLS0+CQkKICAgICAgICA8bGluayByZWw9InN0eWxlc2hlZXQiIGhyZWY9ImNzcy9tYWluLmNzcyI+CgkJCgkJPCEtLSBzdHlsZSBDU1MKCQk9PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PSAtLT4JCQkKICAgICAgICA8bGluayByZWw9InN0eWxlc2hlZXQiIGhyZWY9InN0eWxlLmNzcyI+CgkJCgkJPCEtLSByZXNwb25zaXZlIENTUwoJCT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09IC0tPgkJCQogICAgICAgIDxsaW5rIHJlbD0ic3R5bGVzaGVldCIgaHJlZj0iY3NzL3Jlc3BvbnNpdmUuY3NzIj4KCQkKCQk8IS0tIG1vZGVybml6ciBqcwoJCT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09IC0tPgkJCiAgICAgICAgPHNjcmlwdCBzcmM9ImpzL3ZlbmRvci9tb2Rlcm5penItMi44LjMubWluLmpzIj48L3NjcmlwdD4KICAgICAgICA8c2NyaXB0IHNyYz0iaHR0cHM6Ly9hamF4Lmdvb2dsZWFwaXMuY29tL2FqYXgvbGlicy9qcXVlcnkvMS4xMS4zL2pxdWVyeS5taW4uanMiPjwvc2NyaXB0PgogICAgICAgIDxzY3JpcHQgc3JjPSJqcy9iYXNrZXQuanMiPjwvc2NyaXB0PgogICAgICAgIDwhLS0gbW9kaWZpY2F0aW9ucyAtIEFWCgkJPT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT0gLS0+CiAgCSAKICAJIAkKICAJIAogIAkgCiAgIAkgCiAgIDxtZXRhIGh0dHAtZXF1aXY9IkNvbnRlbnQtdHlwZSIgY29udGVudD0idGV4dC9odG1sOyBjaGFyc2V0PXV0Zi04IiAvPiAgICAgCiAgICA8L2hlYWQ+CiAgICAKICAgIDxib2R5IGNsYXNzPSJob21lLTkiPgogICAgICAgIDwhLS1baWYgbHQgSUUgOF0+CiAgICAgICAgICAgIDxwIGNsYXNzPSJicm93c2VydXBncmFkZSI+WW91IGFyZSB1c2luZyBhbiA8c3Ryb25nPm91dGRhdGVkPC9zdHJvbmc+IGJyb3dzZXIuIFBsZWFzZSA8YSBocmVmPSJodHRwczovL2Jyb3dzZWhhcHB5LmNvbS8iPnVwZ3JhZGUgeW91ciBicm93c2VyPC9hPiB0byBpbXByb3ZlIHlvdXIgZXhwZXJpZW5jZS48L3A+CiAgICAgICAgPCFbZW5kaWZdLS0+CgogICAgICAgIDwhLS0gIEhFQURFUi1BUkVBIFNUQVJULS0+CgkJPGRpdiBjbGFzcz0iaGVhZGVyX2FyZWEiPgogICAgICAgIAkJCTxkaXYgY2xhc3M9ImhlYWRlci10b3AtYmFyIj4KCQkJCTxkaXYgY2xhc3M9ImNvbnRhaW5lciI+CgkJCQkJPGRpdiBjbGFzcz0icm93Ij4KCQkJCQkJPGRpdiBjbGFzcz0iY29sLXNtLTEyIGNvbC14cy0xMiBjb2wtbGctNSBjb2wtbWQtNSBjb2wtbWQtNSI+CgkJCQkJCQk8ZGl2IGNsYXNzPSJoZWFkZXItbGVmdCI+CgkJCQkJCQkJPGRpdiBjbGFzcz0iaGVhZGVyLWVtYWlsIj4KCQkJCQkJCQkJPHN0cm9uZz48YSBocmVmPSJjb250YWN0LXVzIj5IZWxwICZhbXA7IENvbnRhY3Q8L2E+PC9zdHJvbmc+CgkJCQkJCQkJPC9kaXY+CgkJCQkJCQkJPGRpdiBjbGFzcz0iaGVhZGVyLXBob25lIj4KCQkJCQkJCQkJQ29udGFjdDI1IC0gQnV5ICZhbXA7IFNlbGwgYW55dGhpbmchCgkJCQkJCQkJPC9kaXY+CgkJCQkJCQk8L2Rpdj4KCQkJCQkJPC9kaXY+CgkJCQkJCTxkaXYgY2xhc3M9ImNvbC1zbS0xMiBjb2wteHMtMTIgY29sLWxnLTcgY29sLW1kLTciPgoJCQkJCQkJPGRpdiBjbGFzcz0iaGVhZGVyLXJpZ2h0Ij4KCQkJCQkJCQk8ZGl2IGNsYXNzPSJtZW51LXRvcC1tZW51Ij4KCQkJCQkJCQkJPHVsPgoJCQkJCQkJCQkJPGxpPjxhIGhyZWY9Im15X2FjY291bnQiPk15IEFjY291bnQ8L2E+PC9saT4KCQkJCQkJCQkJCQkJCQkJCQkJCQk8bGk+PGEgaHJlZj0iYmFza2V0Ij5CYXNrZXQ8L2E+PC9saT4KCQkJCQkJCQkJCTxsaT48YSBocmVmPSJjaGVja291dC1uZXciPkNoZWNrb3V0PC9hPjwvbGk+CgkJCQkJCQkJCTwvdWw+CgkJCQkJCQkJPC9kaXY+CgkJCQkJCQkJPGRpdiBjbGFzcz0ibGFuZy1zZWwtbGlzdCI+CgkJCQkJCQkJCTx1bD4KCQkJCQkJCQkJCTxsaT4KCQkJCQkJCQkJCQk8YSBocmVmPSIjIj48aW1nIGFsdD0iZW4iIHNyYz0iaW1nL2VuLnBuZyI+PC9hPgoJCQkJCQkJCQkJPC9saT4KCQkJCQkJCQkJPC91bD4KCQkJCQkJCQk8L2Rpdj4KCQkJCQkJCTwvZGl2PgoJCQkJCQk8L2Rpdj4KCQkJCQk8L2Rpdj4KCQkJCTwvZGl2PgoJCQk8L2Rpdj4KICAgICAgICAgICAgCQkJPGRpdiBjbGFzcz0iY29udGFpbmVyIj4KICAgICAgICAgICAgCQkJCQk8IS0tIExPR08tU0VBUkNILUFSRUEgU1RBUlQtLT4KCQkJCTxkaXYgY2xhc3M9InJvdyI+CgkJCQkJPGRpdiBjbGFzcz0iY29sLXhzLTEyIGNvbC1sZy0zIGNvbC1tZC0zIj4KCQkJCQkJPGRpdiBjbGFzcz0ibG9nbyI+CgkJCQkJCQk8YSBocmVmPSIvIj48aW1nIGFsdD0iIiBzcmM9ImltZy9sb2dvLTIucG5nIj48L2E+CgkJCQkJCTwvZGl2PgoJCQkJCTwvZGl2PgoJCQkJCTxkaXYgY2xhc3M9ImNvbC14cy0xMiBjb2wtbGctOSBjb2wtbWQtOSI+CgkJCQkJCTxkaXYgY2xhc3M9InNlYXJjaC1jYXJ0LWxpc3QiPgoJCQkJCQk8ZGl2IGNsYXNzPSJoZWFkZXItc2VhcmNoIj4KCQkJCQkJCTxkaXYgY2xhc3M9ImNhdGUtdG9nZ2xlciI+Q2F0ZWdvcmllczwvZGl2PgoJCQkJCQkJPGRpdiBjbGFzcz0icHJvZHVjdC1jYXRlZ29yeSI+CgkJCQkJCQkJPHVsPgogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICA8bGk+PGEgaHJlZj0idGV4dGJvb2tzLWJvb2tzLXVzZWQtbmV3LWJvb2tzLWN0bWFpbiI+Qm9va3M8L2E+PC9saT4KICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgPGxpPjxhIGhyZWY9InR2LW1vdmllcy1tdXNpYy1nYW1lcy1jdG1haW4iPlRWLCBNdXNpYywgVmlkZW88L2E+PC9saT4KICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgPGxpPjxhIGhyZWY9ImNvbXB1dGVycy1lbGVjdHJpY2FsLWN0bWFpbiI+Q29tcHV0ZXJzICYgRWxlY3RyaWNhbDwvYT48L2xpPgogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICA8bGk+PGEgaHJlZj0iaG9tZS1nYXJkZW4tZGl5LXBldHMtY3RtYWluIj5Ib21lICYgR2FyZGVuLCBESVkgJiBQZXRzPC9hPjwvbGk+CiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIDxsaT48YSBocmVmPSJ0b3lzLWNoaWxkcmVuLWJhYnktY3RtYWluIj5Ub3lzLCBDaGlsZHJlbiAmIEJhYnk8L2E+PC9saT4KICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgPGxpPjxhIGhyZWY9ImNsb3RoZXMtc2hvZXMtamV3ZWxsZXJ5LWN0bWFpbiI+Q2xvdGhlcywgU2hvZXMgJiBKZXdlbGxlcnk8L2E+PC9saT4KICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgPGxpPjxhIGhyZWY9InNwb3J0cy1vdXRkb29yLWN0bWFpbiI+U3BvcnRzICYgT3V0ZG9vcnM8L2E+PC9saT4KICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgPGxpPjxhIGhyZWY9ImJlYXV0eS1oZWFsdGgtZ3JvY2VyaWVzLWN0bWFpbiI+QmVhdXR5LCBIZWFsdGggJiBHcm9jZXJ5PC9hPjwvbGk+CiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIDxsaT48YSBocmVmPSJzdGlja2Vycy1jb2xsZWN0b3JzLWNhcmRzLWN0bWFpbiI+U3RpY2tlcnMgLyBDb2xsZWN0b3JzIENhcmRzPC9hPjwvbGk+CgkJCQkJCQkJPC91bD4KCQkJCQkJCTwvZGl2PgoJCQkJCQkJCgkJCQkJCQkJPGRpdj4KICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAoJCQkJCQkJCQk8aW5wdXQgdHlwZT0idGV4dCIgcGxhY2Vob2xkZXI9IiIgaWQ9InNpdGVfc2VhcmNoX2JveCIgdmFsdWU9IlNlYXJjaCBwcm9kdWN0Li4uIiBvbmJsdXI9ImlmICh0aGlzLnZhbHVlID09ICcnKSB7dGhpcy52YWx1ZSA9ICdTZWFyY2ggcHJvZHVjdC4uLic7fSIgb25mb2N1cz0iaWYgKHRoaXMudmFsdWUgPT0gJ1NlYXJjaCBwcm9kdWN0Li4uJykge3RoaXMudmFsdWUgPSAnJzt9Ij4KCQkJCQkJCQkJPGJ1dHRvbiB0eXBlPSJzdWJtaXQiIGlkPSJzZWFyY2hfc2l0ZV9idG4iPgoJCQkJCQkJCQkJPGkgY2xhc3M9ImZhIGZhLXNlYXJjaCI+PC9pPgoJCQkJCQkJCQk8L2J1dHRvbj4KCQkJCQkJCQk8L2Rpdj4KCQkJCQkJCQoJCQkJCQk8L2Rpdj4gICAgICAgICAgICAgICAgICAgICAgICA8ZGl2IGNsYXNzPSJjYXJ0LXRvdGFsIj4KCQkJCQkJCTx1bD4KCQkJCQkJCQk8bGk+PGEgY2xhc3M9ImNhcnQtdG9nZ2xlciIgaHJlZj0iLi4vLi4vYmFza2V0Ij4KCQkJCQkJCQkJPHNwYW4gY2xhc3M9ImNhcnQtaWNvbiI+PC9zcGFuPiAKCQkJCQkJCQkJPHNwYW4gY2xhc3M9ImNhcnQtbm8iPjxpIGNsYXNzPSJmYSBmYS1zaG9wcGluZy1jYXJ0Ij48L2k+IE15IEJhc2tldDogPHNwYW4gY2xhc3M9ImJhc2tldF9xdHkiPjA8L3NwYW4+IGl0ZW1zPC9zcGFuPjwvYT4KCQkJCQkJCQkJPGRpdiBjbGFzcz0ibWluaS1jYXJ0LWNvbnRlbnQiPgoJCQkJCQkJCQkJCgkJCQkJCQkJCQkKCQkJCQkJCQkJCTxkaXYgY2xhc3M9ImNhcnQtaW5uZXItYm90dG9tIj4KCQkJCQkJCQkJCQk8cCBjbGFzcz0idG90YWwiPlN1YnRvdGFsOiA8c3BhbiBjbGFzcz0iYW1vdW50Ij7CozAuMDA8L3NwYW4+PC9wPgoJCQkJCQkJCQkJCTxkaXYgaWQ9InN1Yl90b3RhbCIgc3R5bGU9ImRpc3BsYXk6bm9uZTsiPjwvZGl2PgoJCQkJCQkJCQkJCTxkaXYgY2xhc3M9ImNsZWFyIj48L2Rpdj4KCQkJCQkJCQkJCQk8cCBjbGFzcz0iYnV0dG9ucyI+PGEgaHJlZj0iY2hlY2tvdXQtbmV3Ij5DaGVja291dDwvYT48L3A+CgkJCQkJCQkJCQk8L2Rpdj4KCQkJCQkJCQkJPC9kaXY+CgkJCQkJCQkJPC9saT4KCQkJCQkJCTwvdWw+CgkJCQkJCTwvZGl2PiAgICAgICAgICAgICAgICAgICAgICAgIAoJCQkJCQkKCQkJCQkJPC9kaXY+CgkJCQkJPC9kaXY+CgkJCQk8L2Rpdj4KCQkJCTwhLS0gTE9HTy1TRUFSQ0gtQVJFQSBFTkQtLT4KICAgICAgICAgICAgICAgIAkJCTwvZGl2PgogICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgCQkJCgkJCQkJCTwhLS0gTUFJTk1FTlUtQVJFQSBTVEFSVC0tPgoJCQk8ZGl2IGNsYXNzPSJtYWlubWVudS1hcmVhIj4KCQkJCTxkaXYgY2xhc3M9ImNvbnRhaW5lciI+CgkJCQkJPGRpdiBjbGFzcz0icm93Ij4KCQkJCQkJCgkJCQkJCTxkaXYgY2xhc3M9ImNvbC1sZy05IGNvbC1tZC05Ij4KCQkJCQkJCTxkaXYgY2xhc3M9Im1haW4tbWVudSI+CgkJCQkJCQkJPG5hdj4KCQkJCQkJCQkJPHVsPgoJCQkJCQkJCQkJPGxpPjxhIGhyZWY9Ii8iPkhvbWU8aSBjbGFzcz0iZmEgZmEtYW5nbGUtZG93biI+PC9pPjwvYT4KCQkJCQkJCQkJCQk8dWwgY2xhc3M9InN1cC1tZW51Ij4KCQkJCQkJCQkJCQkJPGxpPjxhIGhyZWY9InRleHRib29rcy1ib29rcy11c2VkLW5ldy1ib29rcy1jdG1haW4iPkJvb2tzPC9hPjwvbGk+CgkJCQkJCQkJCQkJCTxsaT48YSBocmVmPSJ0di1tb3ZpZXMtbXVzaWMtZ2FtZXMtY3RtYWluIj5UViwgTXVzaWMsIFZpZGVvPC9hPjwvbGk+CgkJCQkJCQkJCQkJCTxsaT48YSBocmVmPSJjb21wdXRlcnMtZWxlY3RyaWNhbC1jdG1haW4iPkNvbXB1dGVycyAmIEVsZWN0cmljYWw8L2E+PC9saT4KCQkJCQkJCQkJCQkJPGxpPjxhIGhyZWY9ImhvbWUtZ2FyZGVuLWRpeS1wZXRzLWN0bWFpbiI+SG9tZSAmIEdhcmRlbiwgRElZICYgUGV0czwvYT48L2xpPgoJCQkJCQkJCQkJCQk8bGk+PGEgaHJlZj0idG95cy1jaGlsZHJlbi1iYWJ5LWN0bWFpbiI+VG95cywgQ2hpbGRyZW4gJiBCYWJ5PC9hPjwvbGk+CgkJCQkJCQkJCQkJCTxsaT48YSBocmVmPSJjbG90aGVzLXNob2VzLWpld2VsbGVyeS1jdG1haW4iPkNsb3RoZXMsIFNob2VzICYgSmV3ZWxsZXJ5PC9hPjwvbGk+CgkJCQkJCQkJCQkJCTxsaT48YSBocmVmPSJzcG9ydHMtb3V0ZG9vci1jdG1haW4iPlNwb3J0cyAmIE91dGRvb3JzPC9hPjwvbGk+CgkJCQkJCQkJCQkJCTxsaT48YSBocmVmPSJiZWF1dHktaGVhbHRoLWdyb2Nlcmllcy1jdG1haW4iPkJlYXV0eSwgSGVhbHRoICYgR3JvY2VyeTwvYT48L2xpPgoJCQkJCQkJCQkJCQk8bGk+PGEgaHJlZj0ic3RpY2tlcnMtY29sbGVjdG9ycy1jYXJkcy1jdG1haW4iPlN0aWNrZXJzIC8gQ29sbGVjdG9ycyBDYXJkczwvYT48L2xpPgoJCQkJCQkJCQkJCTwvdWw+CgkJCQkJCQkJCQk8L2xpPgogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCQkJCQkJCQkJCQogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgPGxpPjxhIGhyZWY9ImMyNXdpc2hsaXN0Ij5NeSBXaXNobGlzdDxpPjwvaT48L2E+CgkJCQkJCQkJCQk8L2xpPgogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgPGxpPjxhIGhyZWY9ImJhc2tldCI+TXkgQmFza2V0PGk+PC9pPjwvYT4KCQkJCQkJCQkJCTwvbGk+CiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCQkJCQkJCQkJCTxsaT48YSBocmVmPSJjb250YWN0LXVzIj5IRUxQPGkgY2xhc3M9ImZhIGZhLWFuZ2xlLWRvd24iPjwvaT48L2E+CgkJCQkJCQkJCQkJPHVsIGNsYXNzPSJzdXAtbWVudSI+CgkJCQkJCQkJCQkJCTxsaT48YSBocmVmPSJ0cmFjay1vcmRlciI+VHJhY2sgT3JkZXI8L2E+PC9saT4KCQkJCQkJCQkJCQkJPGxpPjxhIGhyZWY9InJldHVybnMiPlJldHVybnMgJiBSZXBsYWNlbWVudHM8L2E+PC9saT4KCQkJCQkJCQkJCQkJICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgPGxpPjxhIGhyZWY9ImNvbnRhY3QtdXMiPkNvbnRhY3QgVXM8L2E+PC9saT4KCQkJCQkJCQkJCQk8L3VsPgoJCQkJCQkJCQkJPC9saT4KCQkJCQkJCQkJPC91bD4KCQkJCQkJCQk8L25hdj4KCQkJCQkJCTwvZGl2PgoJCQkJCQk8L2Rpdj4KCQkJCQk8L2Rpdj4KCQkJCTwvZGl2PgoJCQk8L2Rpdj4KCQkJPCEtLSBNQUlOTUVOVS1BUkVBIEVORC0tPgogICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgCiAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgCSAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgCiAgICAKICAgIAk8IS0tIExPR09CUkFORC1BUkVBLUVORCAtLT4KCTwhLS0gRk9PVEVSLVRPUC1BUkVBIFNUQVJULS0+CiAgICAJPGRpdiBjbGFzcz0iZm9vdGVyLXRvcC1hcmVhIj4KCQk8ZGl2IGNsYXNzPSJjb250YWluZXIiPgoJCQk8ZGl2IGNsYXNzPSJyb3ciPgoJCQkJPGRpdiBjbGFzcz0iY29sLXNtLTEyIGNvbC14cy0xMiBjb2wtbGctOCBjb2wtbWQtOCI+CgkJCQkJPGRpdiBjbGFzcz0ic3ViY3JpYmUtYXJlYSI+CgkJCQkJCTxoMj5OZXcgQnV5ZXIgLyBTZWxsZXI8L2gyPgoJCQkJCQk8Zm9ybSBhY3Rpb249Im15X2FjY291bnRfbG9naW4iIG1ldGhvZD0icG9zdCI+CgkJCQkJCQk8aW5wdXQgdHlwZT0idGV4dCIgbmFtZT0ibmV3X2J1eWVyX3NlbGxlcl9lbWFpbCIgcGxhY2Vob2xkZXI9IkVudGVyIHlvdXIgZW1haWwuLi4iPgoJCQkJCQkJPGlucHV0IHR5cGU9InN1Ym1pdCIgdmFsdWU9IlJlZ2lzdGVyIj4KCQkJCQkJPC9mb3JtPgoJCQkJCTwvZGl2PgoJCQkJPC9kaXY+CgkJCQk8ZGl2IGNsYXNzPSJjb2wtc20tMTIgY29sLXhzLTEyIGNvbC1sZy00IGNvbC1tZC00Ij4KCQkJCQk8ZGl2IGNsYXNzPSJzb2NpYWwtbWVkaWEiPgoJCQkJCQk8dWw+CgkJCQkJCQk8bGk+CgkJCQkJCQkJPGEgY2xhc3M9ImNvbG9yLXRvb2x0aXAgZmFjZWJvb2siIHRhcmdldD0iX2JsYW5rIiBocmVmPSJodHRwczovL3d3dy5mYWNlYm9vay5jb20vc2hhcmVyL3NoYXJlci5waHA/dT1odHRwczovL2NvbnRhY3QyNS5jb20vdXBsb2Fkcy83XzUzMjYwNiIgZGF0YS10b2dnbGU9InRvb2x0aXAiIHRpdGxlPSJGYWNlYm9vayI+CgkJCQkJCQkJCTxpIGNsYXNzPSJmYSBmYS1mYWNlYm9vayI+PC9pPgoJCQkJCQkJCTwvYT4KCQkJCQkJCTwvbGk+CgkJCQkJCQk8bGk+CgkJCQkJCQkJPGEgY2xhc3M9ImNvbG9yLXRvb2x0aXAgdHdpdHRlciIgdGFyZ2V0PSJfYmxhbmsiIGRhdGEtdG9nZ2xlPSJ0b29sdGlwIiB0aXRsZT0iVHdpdHRlciIgaHJlZj0iaHR0cHM6Ly90d2l0dGVyLmNvbS9pbnRlbnQvdHdlZXQ/c291cmNlPXdlYmNsaWVudCZ0ZXh0PUNoZWNrK3RoaXMrb3V0K2h0dHBzOi8vY29udGFjdDI1LmNvbS91cGxvYWRzLzdfNTMyNjA2Ij4KCQkJCQkJCQkJPGkgY2xhc3M9ImZhIGZhLXR3aXR0ZXIiPjwvaT4KCQkJCQkJCQk8L2E+CgkJCQkJCQk8L2xpPgogICAgICAgICAgICAgICAgICAgICAgICAgICAgCQkJCQkJPC91bD4KCQkJCQk8L2Rpdj4KCQkJCTwvZGl2PgoJCQk8L2Rpdj4KCQk8L2Rpdj4KCTwvZGl2PgoJPCEtLSBGT09URVItVE9QLUFSRUEgRU5ELS0+Cgk8IS0tIEZPT1RFUi1NSURETEUtQVJFQSBTVEFSVC0tPgoJPGRpdiBjbGFzcz0iZm9vdGVyLW1pZGRsZS1hcmVhIj4KCQk8ZGl2IGNsYXNzPSJjb250YWluZXIiPgoJCQk8ZGl2IGNsYXNzPSJyb3ciPgoJCQkJPGRpdiBjbGFzcz0iY29sLXhzLTEyIGNvbC1zbS02IGNvbC1sZy0zIGNvbC1tZC0zIj4KCQkJCQk8ZGl2IGNsYXNzPSJmb290ZXItbWlkLW1lbnUiPgoJCQkJCQk8aDM+TXkgQWNjb3VudDwvaDM+CgkJCQkJCTx1bD4KCQkJCQkJCTxsaT48YSBocmVmPSJteV9hY2NvdW50Ij5NeSBBY2NvdW50PC9hPjwvbGk+CgkJCQkJCQk8bGk+PGEgaHJlZj0iYmFza2V0Ij5TaG9wcGluZyBiYXNrZXQ8L2E+PC9saT4KCQkJCQkJCTxsaT48YSBocmVmPSJjMjV3aXNobGlzdCI+V2lzaGxpc3Q8L2E+PC9saT4KCQkJCQkJCTxsaT48YSBocmVmPSJjb250YWN0LXVzIj5IZWxwPC9hPjwvbGk+CgkJCQkJCTwvdWw+CgkJCQkJPC9kaXY+CgkJCQk8L2Rpdj4KCQkJCTxkaXYgY2xhc3M9ImNvbC14cy0xMiBjb2wtc20tNiBjb2wtbGctMyBjb2wtbWQtMyI+CgkJCQkJPGRpdiBjbGFzcz0iZm9vdGVyLW1pZC1tZW51Ij4KCQkJCQkJPGgzPkluZm9tYXRpb248L2gzPgoJCQkJCQk8dWw+CgkJCQkJCQkKCQkJCQkJCTxsaT48YSBocmVmPSJwcml2YWN5Ij5Qcml2YWN5IFBvbGljeTwvYT48L2xpPgogICAgICAgICAgICAgICAgICAgICAgICAgICAgPGxpPjxhIGhyZWY9InJldHVybnMiPlJldHVybnMgJmFtcDsgUmVwbGFjZW1lbnRzPC9hPjwvbGk+CgkJCQkJCQkKCQkJCQkJPC91bD4KCQkJCQk8L2Rpdj4KCQkJCTwvZGl2PgoJCQkJCgkJCQk8ZGl2IGNsYXNzPSJjb2wteHMtMTIgY29sLXNtLTYgY29sLWxnLTMgY29sLW1kLTMiPgoJCQkJCTxkaXYgY2xhc3M9ImZvb3Rlci1taWQtbWVudSI+CgkJCQkJCTxoMz5IZWxwPC9oMz4KICAgICAgICAgICAgICAgICAgICAgICAJPHVsPgogICAgICAgICAgICAgICAgICAgICAgICAgICAgPGxpPjxhIGhyZWY9InRyYWNrLW9yZGVyIj5UcmFjayBPcmRlcjwvYT48L2xpPgogICAgICAgICAgICAgICAgICAgICAgICAgICAgPGxpPjxhIGhyZWY9InJldHVybnMiPlJldHVybnMgJmFtcDsgUmVwbGFjZW1lbnRzPC9hPjwvbGk+CiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgPGxpPjxhIGhyZWY9ImNvbnRhY3QtdXMiPkNvbnRhY3QgVXM8L2E+PC9saT4KCQkJCQkJPC91bD4KCQkJCQk8L2Rpdj4KCQkJCTwvZGl2PgoJCQk8L2Rpdj4KCQk8L2Rpdj4KCTwvZGl2PgoJPCEtLSBGT09URVItTUlERExFLUFSRUEgRU5ELS0+Cgk8IS0tIEZPT1RFUi1CT1RUVU0tQVJFQSBTVEFSVC0tPgoJPGRpdiBjbGFzcz0iZm9vdGVyLWJvdHR1bS1hcmVhIj4KCQk8ZGl2IGNsYXNzPSJjb250YWluZXIiPgoJCQk8ZGl2IGNsYXNzPSJyb3ciPgoJCQkJPGRpdiBjbGFzcz0iY29sLXNtLTEyIGNvbC14cy0xMiBjb2wtbWQtNyBjb2wtbGctNiI+CgkJCQkJPGRpdiBjbGFzcz0iZm9vdGVyLWJvdHRvbS1tZW51Ij4KCQkJCQkJPG5hdj4KCQkJCQkJCTx1bD4KCQkJCQkJCQkJCQkJCQkJCTxsaT48YSBocmVmPSJ0cmFjay1vcmRlciI+VHJhY2sgT3JkZXI8L2E+PC9saT4KICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICA8bGk+PGEgaHJlZj0icmV0dXJucyI+T3JkZXJzIGFuZCBSZXR1cm5zPC9hPjwvbGk+CgkJCQkJCQkJPGxpPjxhIGhyZWY9ImNvbnRhY3QtdXMiPkNvbnRhY3QgVXM8L2E+PC9saT4KCQkJCQkJCTwvdWw+CgkJCQkJCTwvbmF2PgoJCQkJCTwvZGl2PgoJCQkJCTxkaXYgY2xhc3M9ImNvcHlyaWdodC1pbmZvIj4KCQkJCQkJQ29weXJpZ2h0ICZjb3B5OyAyMDE4IDxhIGhyZWY9Ii8iPkNvbnRhY3QyNTwvYT4gQWxsIFJpZ2h0cyBSZXNlcnZlZAoJCQkJCTwvZGl2PgoJCQkJPC9kaXY+CgkJCQk8ZGl2IGNsYXNzPSJjb2wtc20tMTIgY29sLXhzLTEyIGNvbC1sZy02IGNvbC1tZC01Ij4KCQkJCQk8ZGl2IGNsYXNzPSJmb290ZXItcGF5bWVudC1sb2dvIj4KICAgICAgICAgICAgICAgICAgICAJPGltZyBzcmM9ImltZy9wYXltZW50L2FtZXgucG5nIiBhbHQ9IkFtZXgiIC8+CgkJCQkJCTxpbWcgc3JjPSJpbWcvcGF5bWVudC92aXNhLnBuZyIgYWx0PSJWaXNhIiAvPgoJCQkJCQk8aW1nIHNyYz0iaW1nL3BheW1lbnQvbWFzdGVyLnBuZyIgYWx0PSJNYXN0ZXJDYXJkIiAvPgoJCQkJCQk8aW1nIHNyYz0iaW1nL3BheW1lbnQvcGF5cGFsLnBuZyIgYWx0PSJQYXlQYWwiIC8+CgkJCQkJCQoJCQkJCQkKCQkJCQk8L2Rpdj4KCQkJCTwvZGl2PgoJCQk8L2Rpdj4KCQk8L2Rpdj4KCTwvZGl2PgoJPCEtLSBGT09URVItQk9UVFVNLUFSRUEgRU5ELS0+CiAgICAKCQk8IS0tIEpTIC0tPgoJCQoJCTwhLS0ganF1ZXJ5IGpzIC0tPgogICAgICAgIDxzY3JpcHQgc3JjPSJqcy92ZW5kb3IvanF1ZXJ5LTEuMTEuMy5taW4uanMiPjwvc2NyaXB0PgoJCQoJCTwhLS0gYm9vdHN0cmFwIGpzIC0tPgogICAgICAgIDxzY3JpcHQgc3JjPSJqcy9ib290c3RyYXAubWluLmpzIj48L3NjcmlwdD4KCQkKCQk8IS0tIG93bC5jYXJvdXNlbC5taW4ganMgLS0+CiAgICAgICAgPHNjcmlwdCBzcmM9ImpzL293bC5jYXJvdXNlbC5taW4uanMiPjwvc2NyaXB0PgoJCQoJCTwhLS0gTW9iaWxlIE1lbnUganMgLS0+CiAgICAgICAgPHNjcmlwdCBzcmM9ImpzL2pxdWVyeS5tZWFubWVudS5qcyI+PC9zY3JpcHQ+CgkJCgkJPCEtLSBGSUxURVJfUFJJQ0UganMgLS0+CiAgICAgICAgPHNjcmlwdCBzcmM9ImpzL2pxdWVyeS11aS5taW4uanMiPjwvc2NyaXB0PgoJCQoJCTwhLS0gbWl4aXR1cCBqcyAtLT4KICAgICAgICA8c2NyaXB0IHNyYz0ianMvanF1ZXJ5Lm1peGl0dXAubWluLmpzIj48L3NjcmlwdD4KCiAgICAgICAgPCEtLSBmYW5jeWJveCBqcyAtLT4KCQk8c2NyaXB0IHNyYz0ianMvZmFuY3lib3gvanF1ZXJ5LmZhbmN5Ym94LnBhY2suanMiPjwvc2NyaXB0PgoKCQk8IS0tIEltZyBab29tIGpzIC0tPgoJCTxzY3JpcHQgc3JjPSJqcy9pbWctem9vbS9qcXVlcnkuc2ltcGxlTGVucy5taW4uanMiPjwvc2NyaXB0PgoJCQoJCTwhLS0ganF1ZXJ5LmNvdW50ZG93biBqcyAtLT4KICAgICAgICA8c2NyaXB0IHNyYz0ianMvanF1ZXJ5LmNvdW50ZG93bi5taW4uanMiPjwvc2NyaXB0PgoJCQoJCTwhLS0gcGFyYWxsYXgganMgLS0+CiAgICAgICAgPHNjcmlwdCBzcmM9ImpzL3BhcmFsbGF4LmpzIj48L3NjcmlwdD4JCgoJCTwhLS0ganF1ZXJ5LmNvbGxhcHNlIGpzIC0tPgogICAgICAgIDxzY3JpcHQgc3JjPSJqcy9qcXVlcnkuY29sbGFwc2UuanMiPjwvc2NyaXB0PgoJCQoJCTwhLS0ganF1ZXJ5LmVhc2luZyBqcyAtLT4KICAgICAgICA8c2NyaXB0IHNyYz0ianMvanF1ZXJ5LmVhc2luZy4xLjMubWluLmpzIj48L3NjcmlwdD4JCgkJCgkJPCEtLSBqcXVlcnkuc2Nyb2xsVXAganMgLS0+CiAgICAgICAgPHNjcmlwdCBzcmM9ImpzL2pxdWVyeS5zY3JvbGxVcC5taW4uanMiPjwvc2NyaXB0PgkKCQkKCQk8IS0tIGtub2IgY2lyY2xlIGpzIC0tPgogICAgICAgIDxzY3JpcHQgc3JjPSJqcy9qcXVlcnkua25vYi5qcyI+PC9zY3JpcHQ+CQoJCQoJCTwhLS0ganF1ZXJ5LmFwcGVhciBqcyAtLT4KCQk8c2NyaXB0IHNyYz0ianMvanF1ZXJ5LmFwcGVhci5qcyI+PC9zY3JpcHQ+CQkJCgoJCTwhLS0ganF1ZXJ5LmNvdW50ZXJ1cCBqcyAtLT4KICAgICAgICA8c2NyaXB0IHNyYz0ianMvanF1ZXJ5LmNvdW50ZXJ1cC5taW4uanMiPjwvc2NyaXB0PgogICAgICAgIDxzY3JpcHQgc3JjPSJqcy93YXlwb2ludHMubWluLmpzIj48L3NjcmlwdD4JCQoJCQoJCTwhLS0gd293IGpzIC0tPgogICAgICAgIDxzY3JpcHQgc3JjPSJqcy93b3cuanMiPjwvc2NyaXB0PgkJCgkJPHNjcmlwdD4KCQkJbmV3IFdPVygpLmluaXQoKTsKCQk8L3NjcmlwdD4KCgkJPCEtLSBycy1wbHVnaW4ganMgLS0+CgkJPHNjcmlwdCB0eXBlPSJ0ZXh0L2phdmFzY3JpcHQiIHNyYz0ibGliL3JzLXBsdWdpbi9qcy9qcXVlcnkudGhlbWVwdW5jaC50b29scy5taW4uanMiPjwvc2NyaXB0PiAgIAoJCTxzY3JpcHQgdHlwZT0idGV4dC9qYXZhc2NyaXB0IiBzcmM9ImxpYi9ycy1wbHVnaW4vanMvanF1ZXJ5LnRoZW1lcHVuY2gucmV2b2x1dGlvbi5taW4uanMiPjwvc2NyaXB0PgoJCTxzY3JpcHQgc3JjPSJsaWIvcnMtcGx1Z2luL3JzLmhvbWUuanMiPjwvc2NyaXB0PgoJCQoJCTwhLS0gcGx1Z2lucyBqcyAtLT4KICAgICAgICA8c2NyaXB0IHNyYz0ianMvcGx1Z2lucy5qcyI+PC9zY3JpcHQ+CgkJCgkJPCEtLSBtYWluIGpzIC0tPgogICAgICAgIDxzY3JpcHQgc3JjPSJqcy9tYWluLmpzIj48L3NjcmlwdD4KCQk8c2NyaXB0PgogICAgICAgICAgKGZ1bmN0aW9uKGkscyxvLGcscixhLG0pe2lbJ0dvb2dsZUFuYWx5dGljc09iamVjdCddPXI7aVtyXT1pW3JdfHxmdW5jdGlvbigpewogICAgICAgICAgKGlbcl0ucT1pW3JdLnF8fFtdKS5wdXNoKGFyZ3VtZW50cyl9LGlbcl0ubD0xKm5ldyBEYXRlKCk7YT1zLmNyZWF0ZUVsZW1lbnQobyksCiAgICAgICAgICBtPXMuZ2V0RWxlbWVudHNCeVRhZ05hbWUobylbMF07YS5hc3luYz0xO2Euc3JjPWc7bS5wYXJlbnROb2RlLmluc2VydEJlZm9yZShhLG0pCiAgICAgICAgICB9KSh3aW5kb3csZG9jdW1lbnQsJ3NjcmlwdCcsJy8vd3d3Lmdvb2dsZS1hbmFseXRpY3MuY29tL2FuYWx5dGljcy5qcycsJ2dhJyk7CiAgICAgICAgCiAgICAgICAgICBnYSgnY3JlYXRlJywgJ1VBLTU0NTk0NS0xJywgJ2F1dG8nKTsKICAgICAgICAgIGdhKCdzZW5kJywgJ3BhZ2V2aWV3Jyk7CiAgICAgICAgCiAgICAgICAgPC9zY3JpcHQ+CiAgICA8L2JvZHk+CjwvaHRtbD4K'){
					$image_link = 'assets/images/logo-balls.png';
				}else{
				
					if (false === file_get_contents('https://contact25.com/uploads/7_'.$row['s_s_id'].'.jpg',0,null,0,1)) {
						$image_link = 'assets/images/logo-balls.png';
					}else{
						$image_link = 'https://contact25.com/uploads/7_'.$row['s_s_id'].'.jpg';
					}
	
				}
				
				
				
				print '<div class="col-lg-4 col-md-6 col-sm-6 col-xs-12">
				<div class="white-box-pd" style="float:left; padding-top:35px; padding-left:35px;  padding-bottom:15px;">
				
				<div class="tick_box_'.$row['s_id'].'" style="position:absolute; left:370px; top:31px;">					
					
				</div>				
				
					<h3 style="padding-bottom:10px;" class="box-title m-b-0 ">'.substr($row['s_label'], 0, 27).'</h3>
						<div class="product-img" style="padding-bottom:5px; min-height: 229px;">
							<img style="max-width: 200px; max-height: 229px;" src="'.$image_link.'"/> 
						</div>
								<div style="float:left; margin-top:7px; margin-left:10px; background: #ffffff;  margin-bottom: 15px;">							
											
									<div class="col-md-6" style="float:left; padding-bottom:10px;">
									<label class="text-success" style="position:absolute; left:-17px; top:8px;">Qty</label>
                      					<input type="number" class="form-control form-control-line qty_update" data-s_id="'.$row['s_id'].'" value="'.$row['s_qty'].'">
                  					</div>
									
									<div class="col-md-6"  style="float:left;">
									<label class="text-info" style="position:absolute; left:-6px; top:8px;" title="Sale Price"><i class="fa fa-tag"></i></label>
                      					<input type="number" class="form-control form-control-line price_update" data-s_id="'.$row['s_id'].'" value="'.$row['s_price_buy_inc_vat'].'">
                  					</div>
									
								</div>	
						</div>	
              		</div>	
				';
				 
			}while($row = mysqli_fetch_assoc($query));
			
				$page_numbers = floor($total_rows_for_pagination/12);
				$_SESSION['limit_start'];
				
				if ($page_numbers>0){
					
					$i=0;
					do{
						if (($i==0)&&($_SESSION['limit_start']==0)){
							$active = 'active';
							$active_colour = 'btn-info';
						}else{
							
							$check = ($_SESSION['limit_start']/12);
							
							if ($check == $i){
								$active = ' active';
								$active_colour = 'btn-info';
							}else{
								$active = '';
								$active_colour = 'btn-secondary';
							}
						}
						
						
						$page_numbers_show_new .= '<button type="button" class="btn '.$active_colour.' page_change" data-pagination_change="'.($i*12).'"><a>'.($i+1).'</a></button>';
						
					$i ++;	
					}while($i<$page_numbers);
						echo '<div style="clear:both;"></div>
						
							<button type="button" class="btn btn-secondary page_change fa fa-angle-left" data-pagination_change="-1"></button>
												'.$page_numbers_show_new.'
                                                                                 
                           <button type="button" class="btn btn-secondary page_change fa fa-angle-right" data-pagination_change="-2"></button>
						
						
					'; 
				}
				
				
			//echo "#".$_SESSION['limit_start'];
		
		}else{
				
				echo 'You have nothing in stock mate' ;
			}

	
?>

	


