<?php

include("../../include/config.php");

global $db; 

#Thinking about adding to query for cancelled and shipped ##

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

$sql = 'SELECT
			*
		FROM
			order_details
		WHERE
			order_details.od_purchased_via = 22212
		AND
			order_details.od_purchased = 0
		AND
			order_details.od_rm_label = 0

		';

	$query = mysqli_query($db,$sql);
	$row = mysqli_fetch_assoc($query);
	$num_rows = mysqli_num_rows($query);


 


		
			if ($num_rows>0){
		
			do{
								
				$orderdetails = $row['od_s_id'];
				$qty_od = $row['od_qty'];
				$price_od = $row['od_price'];
				
				
				$path = 'https://contact25.com/uploads/7_'.$orderdetails;
				$type = pathinfo($path, PATHINFO_EXTENSION);
				$data = file_get_contents($path);
				$base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);

				if ($base64 == 'data:image/;base64,PCFkb2N0eXBlIGh0bWw+CjxodG1sIGNsYXNzPSJuby1qcyIgbGFuZz0iIj48aGVhZD4KPG1ldGEgaHR0cC1lcXVpdj0iQ29udGVudC10eXBlIiBjb250ZW50PSJ0ZXh0L2h0bWw7IGNoYXJzZXQ9dXRmLTgiIC8+CjxtZXRhIG5hbWU9Im1zdmFsaWRhdGUuMDEiIGNvbnRlbnQ9IkY1MTdEMEQ1OTE5NzU1QzlEMzNGQUFCMTg4NTM1MUI1IiAvPgoJCTwhLS0gQmFzaWMgcGFnZSBuZWVkcwoJCT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09IC0tPgkKICAgPG1ldGEgaHR0cC1lcXVpdj0ieC11YS1jb21wYXRpYmxlIiBjb250ZW50PSJpZT1lZGdlIj4KICAgICAgICA8dGl0bGU+Q29udGFjdDI1IC0gQnV5ICZhbXA7IFNlbGwgQW55dGhpbmchIDwvdGl0bGU+CiAgICAgICAgPG1ldGEgbmFtZT0iZGVzY3JpcHRpb24iIGNvbnRlbnQ9IiI+CgkJCgkJPCEtLSBNb2JpbGUgc3BlY2lmaWMgbWV0YXMKCQk9PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PSAtLT4JCQogICAgICAgIDxtZXRhIG5hbWU9InZpZXdwb3J0IiBjb250ZW50PSJ3aWR0aD1kZXZpY2Utd2lkdGgsIGluaXRpYWwtc2NhbGU9MSI+CgkJCgkJPCEtLSBGYXZpY29uCgkJPT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT0gLS0+CgkJPGxpbmsgcmVsPSJzaG9ydGN1dCBpY29uIiB0eXBlPSJpbWFnZS94LWljb24iIGhyZWY9ImltZy9mYXZpY29uLmljbyI+CgoJCTwhLS0gRk9OVFMKCQk9PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PSAtLT4KCQk8bGluayBocmVmPSdodHRwczovL2ZvbnRzLmdvb2dsZWFwaXMuY29tL2Nzcz9mYW1pbHk9T3BlbitTYW5zOjQwMCwzMDAsMzAwaXRhbGljLDQwMGl0YWxpYyw2MDAsNjAwaXRhbGljLDcwMCw3MDBpdGFsaWMsODAwLDgwMGl0YWxpYycgcmVsPSdzdHlsZXNoZWV0JyB0eXBlPSd0ZXh0L2Nzcyc+CgkJPGxpbmsgaHJlZj0naHR0cHM6Ly9mb250cy5nb29nbGVhcGlzLmNvbS9jc3M/ZmFtaWx5PU1vbnRzZXJyYXQ6NDAwLDcwMCcgcmVsPSdzdHlsZXNoZWV0JyB0eXBlPSd0ZXh0L2Nzcyc+CgkJPGxpbmsgaHJlZj0naHR0cHM6Ly9mb250cy5nb29nbGVhcGlzLmNvbS9jc3M/ZmFtaWx5PU9zd2FsZDo0MDAsMzAwLDcwMCcgcmVsPSdzdHlsZXNoZWV0JyB0eXBlPSd0ZXh0L2Nzcyc+CgkJPGxpbmsgaHJlZj0naHR0cHM6Ly9mb250cy5nb29nbGVhcGlzLmNvbS9jc3M/ZmFtaWx5PVVidW50dTo0MDAsMzAwaXRhbGljLDMwMCw0MDBpdGFsaWMsNTAwLDUwMGl0YWxpYyw3MDAsNzAwaXRhbGljJyByZWw9J3N0eWxlc2hlZXQnIHR5cGU9J3RleHQvY3NzJz4KCgkJPCEtLSBDU1MgIC0tPgoJCQoJCTwhLS0gQm9vdHN0cmFwIENTUwoJCT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09IC0tPgogICAgICAgIDxsaW5rIHJlbD0ic3R5bGVzaGVldCIgaHJlZj0iY3NzL2Jvb3RzdHJhcC5taW4uY3NzIj4KCQkKCQk8IS0tIGZvbnQtYXdlc29tZSBDU1MKCQk9PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PSAtLT4KICAgICAgICA8bGluayByZWw9InN0eWxlc2hlZXQiIGhyZWY9ImNzcy9mb250LWF3ZXNvbWUubWluLmNzcyI+CgkJCgkJPCEtLSBvd2wuY2Fyb3VzZWwgQ1NTCgkJPT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT0gLS0+CiAgICAgICAgPGxpbmsgcmVsPSJzdHlsZXNoZWV0IiBocmVmPSJjc3Mvb3dsLmNhcm91c2VsLmNzcyI+CiAgICAgICAgPGxpbmsgcmVsPSJzdHlsZXNoZWV0IiBocmVmPSJjc3Mvb3dsLnRoZW1lLmNzcyI+CiAgICAgICAgPGxpbmsgcmVsPSJzdHlsZXNoZWV0IiBocmVmPSJjc3Mvb3dsLnRyYW5zaXRpb25zLmNzcyI+CgkJCgkJPCEtLSBhbmltYXRlIENTUwoJCT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09IC0tPgogICAgICAgIDxsaW5rIHJlbD0ic3R5bGVzaGVldCIgaHJlZj0iY3NzL2FuaW1hdGUuY3NzIj4KCQkKCQk8IS0tIEZJTFRFUl9QUklDRSBDU1MKCQk9PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PSAtLT4KICAgICAgICA8bGluayByZWw9InN0eWxlc2hlZXQiIGhyZWY9ImNzcy9qcXVlcnktdWkubWluLmNzcyI+CgkJCgkJPCEtLSBmYW5jeWJveCBDU1MKCQk9PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PSAtLT4KICAgICAgICA8bGluayByZWw9InN0eWxlc2hlZXQiIGhyZWY9ImNzcy9mYW5jeWJveC9qcXVlcnkuZmFuY3lib3guY3NzIj4KCiAgICAgICAgPCEtLSBJbWFnZSBab29tIENTUwoJCT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09IC0tPgogICAgICAgIDxsaW5rIHJlbD0ic3R5bGVzaGVldCIgaHJlZj0iY3NzL2ltZy16b29tL2pxdWVyeS5zaW1wbGVMZW5zLmNzcyI+CgkJCgkJPCEtLSBNb2JpbGUgbWVudSBDU1MKCQk9PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PSAtLT4JCQogICAgICAgIDxsaW5rIHJlbD0ic3R5bGVzaGVldCIgaHJlZj0iY3NzL21lYW5tZW51Lm1pbi5jc3MiPgoJCQoJCTwhLS0gbm9ybWFsaXplIENTUwoJCT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09IC0tPgkJCiAgICAgICAgPGxpbmsgcmVsPSJzdHlsZXNoZWV0IiBocmVmPSJjc3Mvbm9ybWFsaXplLmNzcyI+CgogICAgICAgIDwhLS0gUlMgc2xpZGVyIENTUwoJCT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09IC0tPgoJCTxsaW5rIHJlbD0ic3R5bGVzaGVldCIgdHlwZT0idGV4dC9jc3MiIGhyZWY9ImxpYi9ycy1wbHVnaW4vY3NzL3NldHRpbmdzLmNzcyIgbWVkaWE9InNjcmVlbiIgLz4KCQkKCQk8IS0tIG1haW4gQ1NTCgkJPT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT0gLS0+CQkKICAgICAgICA8bGluayByZWw9InN0eWxlc2hlZXQiIGhyZWY9ImNzcy9tYWluLmNzcyI+CgkJCgkJPCEtLSBzdHlsZSBDU1MKCQk9PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PSAtLT4JCQkKICAgICAgICA8bGluayByZWw9InN0eWxlc2hlZXQiIGhyZWY9InN0eWxlLmNzcyI+CgkJCgkJPCEtLSByZXNwb25zaXZlIENTUwoJCT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09IC0tPgkJCQogICAgICAgIDxsaW5rIHJlbD0ic3R5bGVzaGVldCIgaHJlZj0iY3NzL3Jlc3BvbnNpdmUuY3NzIj4KCQkKCQk8IS0tIG1vZGVybml6ciBqcwoJCT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09IC0tPgkJCiAgICAgICAgPHNjcmlwdCBzcmM9ImpzL3ZlbmRvci9tb2Rlcm5penItMi44LjMubWluLmpzIj48L3NjcmlwdD4KICAgICAgICA8c2NyaXB0IHNyYz0iaHR0cHM6Ly9hamF4Lmdvb2dsZWFwaXMuY29tL2FqYXgvbGlicy9qcXVlcnkvMS4xMS4zL2pxdWVyeS5taW4uanMiPjwvc2NyaXB0PgogICAgICAgIDxzY3JpcHQgc3JjPSJqcy9iYXNrZXQuanMiPjwvc2NyaXB0PgogICAgICAgIDwhLS0gbW9kaWZpY2F0aW9ucyAtIEFWCgkJPT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT0gLS0+CiAgCSAKICAJIAkKICAJIAogIAkgCiAgIAkgCiAgIDxtZXRhIGh0dHAtZXF1aXY9IkNvbnRlbnQtdHlwZSIgY29udGVudD0idGV4dC9odG1sOyBjaGFyc2V0PXV0Zi04IiAvPiAgICAgCiAgICA8L2hlYWQ+CiAgICAKICAgIDxib2R5IGNsYXNzPSJob21lLTkiPgogICAgICAgIDwhLS1baWYgbHQgSUUgOF0+CiAgICAgICAgICAgIDxwIGNsYXNzPSJicm93c2VydXBncmFkZSI+WW91IGFyZSB1c2luZyBhbiA8c3Ryb25nPm91dGRhdGVkPC9zdHJvbmc+IGJyb3dzZXIuIFBsZWFzZSA8YSBocmVmPSJodHRwczovL2Jyb3dzZWhhcHB5LmNvbS8iPnVwZ3JhZGUgeW91ciBicm93c2VyPC9hPiB0byBpbXByb3ZlIHlvdXIgZXhwZXJpZW5jZS48L3A+CiAgICAgICAgPCFbZW5kaWZdLS0+CgogICAgICAgIDwhLS0gIEhFQURFUi1BUkVBIFNUQVJULS0+CgkJPGRpdiBjbGFzcz0iaGVhZGVyX2FyZWEiPgogICAgICAgIAkJCTxkaXYgY2xhc3M9ImhlYWRlci10b3AtYmFyIj4KCQkJCTxkaXYgY2xhc3M9ImNvbnRhaW5lciI+CgkJCQkJPGRpdiBjbGFzcz0icm93Ij4KCQkJCQkJPGRpdiBjbGFzcz0iY29sLXNtLTEyIGNvbC14cy0xMiBjb2wtbGctNSBjb2wtbWQtNSBjb2wtbWQtNSI+CgkJCQkJCQk8ZGl2IGNsYXNzPSJoZWFkZXItbGVmdCI+CgkJCQkJCQkJPGRpdiBjbGFzcz0iaGVhZGVyLWVtYWlsIj4KCQkJCQkJCQkJPHN0cm9uZz48YSBocmVmPSJjb250YWN0LXVzIj5IZWxwICZhbXA7IENvbnRhY3Q8L2E+PC9zdHJvbmc+CgkJCQkJCQkJPC9kaXY+CgkJCQkJCQkJPGRpdiBjbGFzcz0iaGVhZGVyLXBob25lIj4KCQkJCQkJCQkJQ29udGFjdDI1IC0gQnV5ICZhbXA7IFNlbGwgYW55dGhpbmchCgkJCQkJCQkJPC9kaXY+CgkJCQkJCQk8L2Rpdj4KCQkJCQkJPC9kaXY+CgkJCQkJCTxkaXYgY2xhc3M9ImNvbC1zbS0xMiBjb2wteHMtMTIgY29sLWxnLTcgY29sLW1kLTciPgoJCQkJCQkJPGRpdiBjbGFzcz0iaGVhZGVyLXJpZ2h0Ij4KCQkJCQkJCQk8ZGl2IGNsYXNzPSJtZW51LXRvcC1tZW51Ij4KCQkJCQkJCQkJPHVsPgoJCQkJCQkJCQkJPGxpPjxhIGhyZWY9Im15X2FjY291bnQiPk15IEFjY291bnQ8L2E+PC9saT4KCQkJCQkJCQkJCQkJCQkJCQkJCQk8bGk+PGEgaHJlZj0iYmFza2V0Ij5CYXNrZXQ8L2E+PC9saT4KCQkJCQkJCQkJCTxsaT48YSBocmVmPSJjaGVja291dC1uZXciPkNoZWNrb3V0PC9hPjwvbGk+CgkJCQkJCQkJCTwvdWw+CgkJCQkJCQkJPC9kaXY+CgkJCQkJCQkJPGRpdiBjbGFzcz0ibGFuZy1zZWwtbGlzdCI+CgkJCQkJCQkJCTx1bD4KCQkJCQkJCQkJCTxsaT4KCQkJCQkJCQkJCQk8YSBocmVmPSIjIj48aW1nIGFsdD0iZW4iIHNyYz0iaW1nL2VuLnBuZyI+PC9hPgoJCQkJCQkJCQkJPC9saT4KCQkJCQkJCQkJPC91bD4KCQkJCQkJCQk8L2Rpdj4KCQkJCQkJCTwvZGl2PgoJCQkJCQk8L2Rpdj4KCQkJCQk8L2Rpdj4KCQkJCTwvZGl2PgoJCQk8L2Rpdj4KICAgICAgICAgICAgCQkJPGRpdiBjbGFzcz0iY29udGFpbmVyIj4KICAgICAgICAgICAgCQkJCQk8IS0tIExPR08tU0VBUkNILUFSRUEgU1RBUlQtLT4KCQkJCTxkaXYgY2xhc3M9InJvdyI+CgkJCQkJPGRpdiBjbGFzcz0iY29sLXhzLTEyIGNvbC1sZy0zIGNvbC1tZC0zIj4KCQkJCQkJPGRpdiBjbGFzcz0ibG9nbyI+CgkJCQkJCQk8YSBocmVmPSIvIj48aW1nIGFsdD0iIiBzcmM9ImltZy9sb2dvLTIucG5nIj48L2E+CgkJCQkJCTwvZGl2PgoJCQkJCTwvZGl2PgoJCQkJCTxkaXYgY2xhc3M9ImNvbC14cy0xMiBjb2wtbGctOSBjb2wtbWQtOSI+CgkJCQkJCTxkaXYgY2xhc3M9InNlYXJjaC1jYXJ0LWxpc3QiPgoJCQkJCQk8ZGl2IGNsYXNzPSJoZWFkZXItc2VhcmNoIj4KCQkJCQkJCTxkaXYgY2xhc3M9ImNhdGUtdG9nZ2xlciI+Q2F0ZWdvcmllczwvZGl2PgoJCQkJCQkJPGRpdiBjbGFzcz0icHJvZHVjdC1jYXRlZ29yeSI+CgkJCQkJCQkJPHVsPgogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICA8bGk+PGEgaHJlZj0idGV4dGJvb2tzLWJvb2tzLXVzZWQtbmV3LWJvb2tzLWN0bWFpbiI+Qm9va3M8L2E+PC9saT4KICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgPGxpPjxhIGhyZWY9InR2LW1vdmllcy1tdXNpYy1nYW1lcy1jdG1haW4iPlRWLCBNdXNpYywgVmlkZW88L2E+PC9saT4KICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgPGxpPjxhIGhyZWY9ImNvbXB1dGVycy1lbGVjdHJpY2FsLWN0bWFpbiI+Q29tcHV0ZXJzICYgRWxlY3RyaWNhbDwvYT48L2xpPgogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICA8bGk+PGEgaHJlZj0iaG9tZS1nYXJkZW4tZGl5LXBldHMtY3RtYWluIj5Ib21lICYgR2FyZGVuLCBESVkgJiBQZXRzPC9hPjwvbGk+CiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIDxsaT48YSBocmVmPSJ0b3lzLWNoaWxkcmVuLWJhYnktY3RtYWluIj5Ub3lzLCBDaGlsZHJlbiAmIEJhYnk8L2E+PC9saT4KICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgPGxpPjxhIGhyZWY9ImNsb3RoZXMtc2hvZXMtamV3ZWxsZXJ5LWN0bWFpbiI+Q2xvdGhlcywgU2hvZXMgJiBKZXdlbGxlcnk8L2E+PC9saT4KICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgPGxpPjxhIGhyZWY9InNwb3J0cy1vdXRkb29yLWN0bWFpbiI+U3BvcnRzICYgT3V0ZG9vcnM8L2E+PC9saT4KICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgPGxpPjxhIGhyZWY9ImJlYXV0eS1oZWFsdGgtZ3JvY2VyaWVzLWN0bWFpbiI+QmVhdXR5LCBIZWFsdGggJiBHcm9jZXJ5PC9hPjwvbGk+CiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIDxsaT48YSBocmVmPSJzdGlja2Vycy1jb2xsZWN0b3JzLWNhcmRzLWN0bWFpbiI+U3RpY2tlcnMgLyBDb2xsZWN0b3JzIENhcmRzPC9hPjwvbGk+CgkJCQkJCQkJPC91bD4KCQkJCQkJCTwvZGl2PgoJCQkJCQkJCgkJCQkJCQkJPGRpdj4KICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAoJCQkJCQkJCQk8aW5wdXQgdHlwZT0idGV4dCIgcGxhY2Vob2xkZXI9IiIgaWQ9InNpdGVfc2VhcmNoX2JveCIgdmFsdWU9IlNlYXJjaCBwcm9kdWN0Li4uIiBvbmJsdXI9ImlmICh0aGlzLnZhbHVlID09ICcnKSB7dGhpcy52YWx1ZSA9ICdTZWFyY2ggcHJvZHVjdC4uLic7fSIgb25mb2N1cz0iaWYgKHRoaXMudmFsdWUgPT0gJ1NlYXJjaCBwcm9kdWN0Li4uJykge3RoaXMudmFsdWUgPSAnJzt9Ij4KCQkJCQkJCQkJPGJ1dHRvbiB0eXBlPSJzdWJtaXQiIGlkPSJzZWFyY2hfc2l0ZV9idG4iPgoJCQkJCQkJCQkJPGkgY2xhc3M9ImZhIGZhLXNlYXJjaCI+PC9pPgoJCQkJCQkJCQk8L2J1dHRvbj4KCQkJCQkJCQk8L2Rpdj4KCQkJCQkJCQoJCQkJCQk8L2Rpdj4gICAgICAgICAgICAgICAgICAgICAgICA8ZGl2IGNsYXNzPSJjYXJ0LXRvdGFsIj4KCQkJCQkJCTx1bD4KCQkJCQkJCQk8bGk+PGEgY2xhc3M9ImNhcnQtdG9nZ2xlciIgaHJlZj0iLi4vLi4vYmFza2V0Ij4KCQkJCQkJCQkJPHNwYW4gY2xhc3M9ImNhcnQtaWNvbiI+PC9zcGFuPiAKCQkJCQkJCQkJPHNwYW4gY2xhc3M9ImNhcnQtbm8iPjxpIGNsYXNzPSJmYSBmYS1zaG9wcGluZy1jYXJ0Ij48L2k+IE15IEJhc2tldDogPHNwYW4gY2xhc3M9ImJhc2tldF9xdHkiPjA8L3NwYW4+IGl0ZW1zPC9zcGFuPjwvYT4KCQkJCQkJCQkJPGRpdiBjbGFzcz0ibWluaS1jYXJ0LWNvbnRlbnQiPgoJCQkJCQkJCQkJCgkJCQkJCQkJCQkKCQkJCQkJCQkJCTxkaXYgY2xhc3M9ImNhcnQtaW5uZXItYm90dG9tIj4KCQkJCQkJCQkJCQk8cCBjbGFzcz0idG90YWwiPlN1YnRvdGFsOiA8c3BhbiBjbGFzcz0iYW1vdW50Ij7CozAuMDA8L3NwYW4+PC9wPgoJCQkJCQkJCQkJCTxkaXYgaWQ9InN1Yl90b3RhbCIgc3R5bGU9ImRpc3BsYXk6bm9uZTsiPjwvZGl2PgoJCQkJCQkJCQkJCTxkaXYgY2xhc3M9ImNsZWFyIj48L2Rpdj4KCQkJCQkJCQkJCQk8cCBjbGFzcz0iYnV0dG9ucyI+PGEgaHJlZj0iY2hlY2tvdXQtbmV3Ij5DaGVja291dDwvYT48L3A+CgkJCQkJCQkJCQk8L2Rpdj4KCQkJCQkJCQkJPC9kaXY+CgkJCQkJCQkJPC9saT4KCQkJCQkJCTwvdWw+CgkJCQkJCTwvZGl2PiAgICAgICAgICAgICAgICAgICAgICAgIAoJCQkJCQkKCQkJCQkJPC9kaXY+CgkJCQkJPC9kaXY+CgkJCQk8L2Rpdj4KCQkJCTwhLS0gTE9HTy1TRUFSQ0gtQVJFQSBFTkQtLT4KICAgICAgICAgICAgICAgIAkJCTwvZGl2PgogICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgCQkJCgkJCQkJCTwhLS0gTUFJTk1FTlUtQVJFQSBTVEFSVC0tPgoJCQk8ZGl2IGNsYXNzPSJtYWlubWVudS1hcmVhIj4KCQkJCTxkaXYgY2xhc3M9ImNvbnRhaW5lciI+CgkJCQkJPGRpdiBjbGFzcz0icm93Ij4KCQkJCQkJCgkJCQkJCTxkaXYgY2xhc3M9ImNvbC1sZy05IGNvbC1tZC05Ij4KCQkJCQkJCTxkaXYgY2xhc3M9Im1haW4tbWVudSI+CgkJCQkJCQkJPG5hdj4KCQkJCQkJCQkJPHVsPgoJCQkJCQkJCQkJPGxpPjxhIGhyZWY9Ii8iPkhvbWU8aSBjbGFzcz0iZmEgZmEtYW5nbGUtZG93biI+PC9pPjwvYT4KCQkJCQkJCQkJCQk8dWwgY2xhc3M9InN1cC1tZW51Ij4KCQkJCQkJCQkJCQkJPGxpPjxhIGhyZWY9InRleHRib29rcy1ib29rcy11c2VkLW5ldy1ib29rcy1jdG1haW4iPkJvb2tzPC9hPjwvbGk+CgkJCQkJCQkJCQkJCTxsaT48YSBocmVmPSJ0di1tb3ZpZXMtbXVzaWMtZ2FtZXMtY3RtYWluIj5UViwgTXVzaWMsIFZpZGVvPC9hPjwvbGk+CgkJCQkJCQkJCQkJCTxsaT48YSBocmVmPSJjb21wdXRlcnMtZWxlY3RyaWNhbC1jdG1haW4iPkNvbXB1dGVycyAmIEVsZWN0cmljYWw8L2E+PC9saT4KCQkJCQkJCQkJCQkJPGxpPjxhIGhyZWY9ImhvbWUtZ2FyZGVuLWRpeS1wZXRzLWN0bWFpbiI+SG9tZSAmIEdhcmRlbiwgRElZICYgUGV0czwvYT48L2xpPgoJCQkJCQkJCQkJCQk8bGk+PGEgaHJlZj0idG95cy1jaGlsZHJlbi1iYWJ5LWN0bWFpbiI+VG95cywgQ2hpbGRyZW4gJiBCYWJ5PC9hPjwvbGk+CgkJCQkJCQkJCQkJCTxsaT48YSBocmVmPSJjbG90aGVzLXNob2VzLWpld2VsbGVyeS1jdG1haW4iPkNsb3RoZXMsIFNob2VzICYgSmV3ZWxsZXJ5PC9hPjwvbGk+CgkJCQkJCQkJCQkJCTxsaT48YSBocmVmPSJzcG9ydHMtb3V0ZG9vci1jdG1haW4iPlNwb3J0cyAmIE91dGRvb3JzPC9hPjwvbGk+CgkJCQkJCQkJCQkJCTxsaT48YSBocmVmPSJiZWF1dHktaGVhbHRoLWdyb2Nlcmllcy1jdG1haW4iPkJlYXV0eSwgSGVhbHRoICYgR3JvY2VyeTwvYT48L2xpPgoJCQkJCQkJCQkJCQk8bGk+PGEgaHJlZj0ic3RpY2tlcnMtY29sbGVjdG9ycy1jYXJkcy1jdG1haW4iPlN0aWNrZXJzIC8gQ29sbGVjdG9ycyBDYXJkczwvYT48L2xpPgoJCQkJCQkJCQkJCTwvdWw+CgkJCQkJCQkJCQk8L2xpPgogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCQkJCQkJCQkJCQogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgPGxpPjxhIGhyZWY9ImMyNXdpc2hsaXN0Ij5NeSBXaXNobGlzdDxpPjwvaT48L2E+CgkJCQkJCQkJCQk8L2xpPgogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgPGxpPjxhIGhyZWY9ImJhc2tldCI+TXkgQmFza2V0PGk+PC9pPjwvYT4KCQkJCQkJCQkJCTwvbGk+CiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCQkJCQkJCQkJCTxsaT48YSBocmVmPSJjb250YWN0LXVzIj5IRUxQPGkgY2xhc3M9ImZhIGZhLWFuZ2xlLWRvd24iPjwvaT48L2E+CgkJCQkJCQkJCQkJPHVsIGNsYXNzPSJzdXAtbWVudSI+CgkJCQkJCQkJCQkJCTxsaT48YSBocmVmPSJ0cmFjay1vcmRlciI+VHJhY2sgT3JkZXI8L2E+PC9saT4KCQkJCQkJCQkJCQkJPGxpPjxhIGhyZWY9InJldHVybnMiPlJldHVybnMgJiBSZXBsYWNlbWVudHM8L2E+PC9saT4KCQkJCQkJCQkJCQkJICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgPGxpPjxhIGhyZWY9ImNvbnRhY3QtdXMiPkNvbnRhY3QgVXM8L2E+PC9saT4KCQkJCQkJCQkJCQk8L3VsPgoJCQkJCQkJCQkJPC9saT4KCQkJCQkJCQkJPC91bD4KCQkJCQkJCQk8L25hdj4KCQkJCQkJCTwvZGl2PgoJCQkJCQk8L2Rpdj4KCQkJCQk8L2Rpdj4KCQkJCTwvZGl2PgoJCQk8L2Rpdj4KCQkJPCEtLSBNQUlOTUVOVS1BUkVBIEVORC0tPgogICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgCiAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgCSAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgCiAgICAKICAgIAk8IS0tIExPR09CUkFORC1BUkVBLUVORCAtLT4KCTwhLS0gRk9PVEVSLVRPUC1BUkVBIFNUQVJULS0+CiAgICAJPGRpdiBjbGFzcz0iZm9vdGVyLXRvcC1hcmVhIj4KCQk8ZGl2IGNsYXNzPSJjb250YWluZXIiPgoJCQk8ZGl2IGNsYXNzPSJyb3ciPgoJCQkJPGRpdiBjbGFzcz0iY29sLXNtLTEyIGNvbC14cy0xMiBjb2wtbGctOCBjb2wtbWQtOCI+CgkJCQkJPGRpdiBjbGFzcz0ic3ViY3JpYmUtYXJlYSI+CgkJCQkJCTxoMj5OZXcgQnV5ZXIgLyBTZWxsZXI8L2gyPgoJCQkJCQk8Zm9ybSBhY3Rpb249Im15X2FjY291bnRfbG9naW4iIG1ldGhvZD0icG9zdCI+CgkJCQkJCQk8aW5wdXQgdHlwZT0idGV4dCIgbmFtZT0ibmV3X2J1eWVyX3NlbGxlcl9lbWFpbCIgcGxhY2Vob2xkZXI9IkVudGVyIHlvdXIgZW1haWwuLi4iPgoJCQkJCQkJPGlucHV0IHR5cGU9InN1Ym1pdCIgdmFsdWU9IlJlZ2lzdGVyIj4KCQkJCQkJPC9mb3JtPgoJCQkJCTwvZGl2PgoJCQkJPC9kaXY+CgkJCQk8ZGl2IGNsYXNzPSJjb2wtc20tMTIgY29sLXhzLTEyIGNvbC1sZy00IGNvbC1tZC00Ij4KCQkJCQk8ZGl2IGNsYXNzPSJzb2NpYWwtbWVkaWEiPgoJCQkJCQk8dWw+CgkJCQkJCQk8bGk+CgkJCQkJCQkJPGEgY2xhc3M9ImNvbG9yLXRvb2x0aXAgZmFjZWJvb2siIHRhcmdldD0iX2JsYW5rIiBocmVmPSJodHRwczovL3d3dy5mYWNlYm9vay5jb20vc2hhcmVyL3NoYXJlci5waHA/dT1odHRwczovL2NvbnRhY3QyNS5jb20vdXBsb2Fkcy83XzUzMjYwNiIgZGF0YS10b2dnbGU9InRvb2x0aXAiIHRpdGxlPSJGYWNlYm9vayI+CgkJCQkJCQkJCTxpIGNsYXNzPSJmYSBmYS1mYWNlYm9vayI+PC9pPgoJCQkJCQkJCTwvYT4KCQkJCQkJCTwvbGk+CgkJCQkJCQk8bGk+CgkJCQkJCQkJPGEgY2xhc3M9ImNvbG9yLXRvb2x0aXAgdHdpdHRlciIgdGFyZ2V0PSJfYmxhbmsiIGRhdGEtdG9nZ2xlPSJ0b29sdGlwIiB0aXRsZT0iVHdpdHRlciIgaHJlZj0iaHR0cHM6Ly90d2l0dGVyLmNvbS9pbnRlbnQvdHdlZXQ/c291cmNlPXdlYmNsaWVudCZ0ZXh0PUNoZWNrK3RoaXMrb3V0K2h0dHBzOi8vY29udGFjdDI1LmNvbS91cGxvYWRzLzdfNTMyNjA2Ij4KCQkJCQkJCQkJPGkgY2xhc3M9ImZhIGZhLXR3aXR0ZXIiPjwvaT4KCQkJCQkJCQk8L2E+CgkJCQkJCQk8L2xpPgogICAgICAgICAgICAgICAgICAgICAgICAgICAgCQkJCQkJPC91bD4KCQkJCQk8L2Rpdj4KCQkJCTwvZGl2PgoJCQk8L2Rpdj4KCQk8L2Rpdj4KCTwvZGl2PgoJPCEtLSBGT09URVItVE9QLUFSRUEgRU5ELS0+Cgk8IS0tIEZPT1RFUi1NSURETEUtQVJFQSBTVEFSVC0tPgoJPGRpdiBjbGFzcz0iZm9vdGVyLW1pZGRsZS1hcmVhIj4KCQk8ZGl2IGNsYXNzPSJjb250YWluZXIiPgoJCQk8ZGl2IGNsYXNzPSJyb3ciPgoJCQkJPGRpdiBjbGFzcz0iY29sLXhzLTEyIGNvbC1zbS02IGNvbC1sZy0zIGNvbC1tZC0zIj4KCQkJCQk8ZGl2IGNsYXNzPSJmb290ZXItbWlkLW1lbnUiPgoJCQkJCQk8aDM+TXkgQWNjb3VudDwvaDM+CgkJCQkJCTx1bD4KCQkJCQkJCTxsaT48YSBocmVmPSJteV9hY2NvdW50Ij5NeSBBY2NvdW50PC9hPjwvbGk+CgkJCQkJCQk8bGk+PGEgaHJlZj0iYmFza2V0Ij5TaG9wcGluZyBiYXNrZXQ8L2E+PC9saT4KCQkJCQkJCTxsaT48YSBocmVmPSJjMjV3aXNobGlzdCI+V2lzaGxpc3Q8L2E+PC9saT4KCQkJCQkJCTxsaT48YSBocmVmPSJjb250YWN0LXVzIj5IZWxwPC9hPjwvbGk+CgkJCQkJCTwvdWw+CgkJCQkJPC9kaXY+CgkJCQk8L2Rpdj4KCQkJCTxkaXYgY2xhc3M9ImNvbC14cy0xMiBjb2wtc20tNiBjb2wtbGctMyBjb2wtbWQtMyI+CgkJCQkJPGRpdiBjbGFzcz0iZm9vdGVyLW1pZC1tZW51Ij4KCQkJCQkJPGgzPkluZm9tYXRpb248L2gzPgoJCQkJCQk8dWw+CgkJCQkJCQkKCQkJCQkJCTxsaT48YSBocmVmPSJwcml2YWN5Ij5Qcml2YWN5IFBvbGljeTwvYT48L2xpPgogICAgICAgICAgICAgICAgICAgICAgICAgICAgPGxpPjxhIGhyZWY9InJldHVybnMiPlJldHVybnMgJmFtcDsgUmVwbGFjZW1lbnRzPC9hPjwvbGk+CgkJCQkJCQkKCQkJCQkJPC91bD4KCQkJCQk8L2Rpdj4KCQkJCTwvZGl2PgoJCQkJCgkJCQk8ZGl2IGNsYXNzPSJjb2wteHMtMTIgY29sLXNtLTYgY29sLWxnLTMgY29sLW1kLTMiPgoJCQkJCTxkaXYgY2xhc3M9ImZvb3Rlci1taWQtbWVudSI+CgkJCQkJCTxoMz5IZWxwPC9oMz4KICAgICAgICAgICAgICAgICAgICAgICAJPHVsPgogICAgICAgICAgICAgICAgICAgICAgICAgICAgPGxpPjxhIGhyZWY9InRyYWNrLW9yZGVyIj5UcmFjayBPcmRlcjwvYT48L2xpPgogICAgICAgICAgICAgICAgICAgICAgICAgICAgPGxpPjxhIGhyZWY9InJldHVybnMiPlJldHVybnMgJmFtcDsgUmVwbGFjZW1lbnRzPC9hPjwvbGk+CiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgPGxpPjxhIGhyZWY9ImNvbnRhY3QtdXMiPkNvbnRhY3QgVXM8L2E+PC9saT4KCQkJCQkJPC91bD4KCQkJCQk8L2Rpdj4KCQkJCTwvZGl2PgoJCQk8L2Rpdj4KCQk8L2Rpdj4KCTwvZGl2PgoJPCEtLSBGT09URVItTUlERExFLUFSRUEgRU5ELS0+Cgk8IS0tIEZPT1RFUi1CT1RUVU0tQVJFQSBTVEFSVC0tPgoJPGRpdiBjbGFzcz0iZm9vdGVyLWJvdHR1bS1hcmVhIj4KCQk8ZGl2IGNsYXNzPSJjb250YWluZXIiPgoJCQk8ZGl2IGNsYXNzPSJyb3ciPgoJCQkJPGRpdiBjbGFzcz0iY29sLXNtLTEyIGNvbC14cy0xMiBjb2wtbWQtNyBjb2wtbGctNiI+CgkJCQkJPGRpdiBjbGFzcz0iZm9vdGVyLWJvdHRvbS1tZW51Ij4KCQkJCQkJPG5hdj4KCQkJCQkJCTx1bD4KCQkJCQkJCQkJCQkJCQkJCTxsaT48YSBocmVmPSJ0cmFjay1vcmRlciI+VHJhY2sgT3JkZXI8L2E+PC9saT4KICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICA8bGk+PGEgaHJlZj0icmV0dXJucyI+T3JkZXJzIGFuZCBSZXR1cm5zPC9hPjwvbGk+CgkJCQkJCQkJPGxpPjxhIGhyZWY9ImNvbnRhY3QtdXMiPkNvbnRhY3QgVXM8L2E+PC9saT4KCQkJCQkJCTwvdWw+CgkJCQkJCTwvbmF2PgoJCQkJCTwvZGl2PgoJCQkJCTxkaXYgY2xhc3M9ImNvcHlyaWdodC1pbmZvIj4KCQkJCQkJQ29weXJpZ2h0ICZjb3B5OyAyMDE4IDxhIGhyZWY9Ii8iPkNvbnRhY3QyNTwvYT4gQWxsIFJpZ2h0cyBSZXNlcnZlZAoJCQkJCTwvZGl2PgoJCQkJPC9kaXY+CgkJCQk8ZGl2IGNsYXNzPSJjb2wtc20tMTIgY29sLXhzLTEyIGNvbC1sZy02IGNvbC1tZC01Ij4KCQkJCQk8ZGl2IGNsYXNzPSJmb290ZXItcGF5bWVudC1sb2dvIj4KICAgICAgICAgICAgICAgICAgICAJPGltZyBzcmM9ImltZy9wYXltZW50L2FtZXgucG5nIiBhbHQ9IkFtZXgiIC8+CgkJCQkJCTxpbWcgc3JjPSJpbWcvcGF5bWVudC92aXNhLnBuZyIgYWx0PSJWaXNhIiAvPgoJCQkJCQk8aW1nIHNyYz0iaW1nL3BheW1lbnQvbWFzdGVyLnBuZyIgYWx0PSJNYXN0ZXJDYXJkIiAvPgoJCQkJCQk8aW1nIHNyYz0iaW1nL3BheW1lbnQvcGF5cGFsLnBuZyIgYWx0PSJQYXlQYWwiIC8+CgkJCQkJCQoJCQkJCQkKCQkJCQk8L2Rpdj4KCQkJCTwvZGl2PgoJCQk8L2Rpdj4KCQk8L2Rpdj4KCTwvZGl2PgoJPCEtLSBGT09URVItQk9UVFVNLUFSRUEgRU5ELS0+CiAgICAKCQk8IS0tIEpTIC0tPgoJCQoJCTwhLS0ganF1ZXJ5IGpzIC0tPgogICAgICAgIDxzY3JpcHQgc3JjPSJqcy92ZW5kb3IvanF1ZXJ5LTEuMTEuMy5taW4uanMiPjwvc2NyaXB0PgoJCQoJCTwhLS0gYm9vdHN0cmFwIGpzIC0tPgogICAgICAgIDxzY3JpcHQgc3JjPSJqcy9ib290c3RyYXAubWluLmpzIj48L3NjcmlwdD4KCQkKCQk8IS0tIG93bC5jYXJvdXNlbC5taW4ganMgLS0+CiAgICAgICAgPHNjcmlwdCBzcmM9ImpzL293bC5jYXJvdXNlbC5taW4uanMiPjwvc2NyaXB0PgoJCQoJCTwhLS0gTW9iaWxlIE1lbnUganMgLS0+CiAgICAgICAgPHNjcmlwdCBzcmM9ImpzL2pxdWVyeS5tZWFubWVudS5qcyI+PC9zY3JpcHQ+CgkJCgkJPCEtLSBGSUxURVJfUFJJQ0UganMgLS0+CiAgICAgICAgPHNjcmlwdCBzcmM9ImpzL2pxdWVyeS11aS5taW4uanMiPjwvc2NyaXB0PgoJCQoJCTwhLS0gbWl4aXR1cCBqcyAtLT4KICAgICAgICA8c2NyaXB0IHNyYz0ianMvanF1ZXJ5Lm1peGl0dXAubWluLmpzIj48L3NjcmlwdD4KCiAgICAgICAgPCEtLSBmYW5jeWJveCBqcyAtLT4KCQk8c2NyaXB0IHNyYz0ianMvZmFuY3lib3gvanF1ZXJ5LmZhbmN5Ym94LnBhY2suanMiPjwvc2NyaXB0PgoKCQk8IS0tIEltZyBab29tIGpzIC0tPgoJCTxzY3JpcHQgc3JjPSJqcy9pbWctem9vbS9qcXVlcnkuc2ltcGxlTGVucy5taW4uanMiPjwvc2NyaXB0PgoJCQoJCTwhLS0ganF1ZXJ5LmNvdW50ZG93biBqcyAtLT4KICAgICAgICA8c2NyaXB0IHNyYz0ianMvanF1ZXJ5LmNvdW50ZG93bi5taW4uanMiPjwvc2NyaXB0PgoJCQoJCTwhLS0gcGFyYWxsYXgganMgLS0+CiAgICAgICAgPHNjcmlwdCBzcmM9ImpzL3BhcmFsbGF4LmpzIj48L3NjcmlwdD4JCgoJCTwhLS0ganF1ZXJ5LmNvbGxhcHNlIGpzIC0tPgogICAgICAgIDxzY3JpcHQgc3JjPSJqcy9qcXVlcnkuY29sbGFwc2UuanMiPjwvc2NyaXB0PgoJCQoJCTwhLS0ganF1ZXJ5LmVhc2luZyBqcyAtLT4KICAgICAgICA8c2NyaXB0IHNyYz0ianMvanF1ZXJ5LmVhc2luZy4xLjMubWluLmpzIj48L3NjcmlwdD4JCgkJCgkJPCEtLSBqcXVlcnkuc2Nyb2xsVXAganMgLS0+CiAgICAgICAgPHNjcmlwdCBzcmM9ImpzL2pxdWVyeS5zY3JvbGxVcC5taW4uanMiPjwvc2NyaXB0PgkKCQkKCQk8IS0tIGtub2IgY2lyY2xlIGpzIC0tPgogICAgICAgIDxzY3JpcHQgc3JjPSJqcy9qcXVlcnkua25vYi5qcyI+PC9zY3JpcHQ+CQoJCQoJCTwhLS0ganF1ZXJ5LmFwcGVhciBqcyAtLT4KCQk8c2NyaXB0IHNyYz0ianMvanF1ZXJ5LmFwcGVhci5qcyI+PC9zY3JpcHQ+CQkJCgoJCTwhLS0ganF1ZXJ5LmNvdW50ZXJ1cCBqcyAtLT4KICAgICAgICA8c2NyaXB0IHNyYz0ianMvanF1ZXJ5LmNvdW50ZXJ1cC5taW4uanMiPjwvc2NyaXB0PgogICAgICAgIDxzY3JpcHQgc3JjPSJqcy93YXlwb2ludHMubWluLmpzIj48L3NjcmlwdD4JCQoJCQoJCTwhLS0gd293IGpzIC0tPgogICAgICAgIDxzY3JpcHQgc3JjPSJqcy93b3cuanMiPjwvc2NyaXB0PgkJCgkJPHNjcmlwdD4KCQkJbmV3IFdPVygpLmluaXQoKTsKCQk8L3NjcmlwdD4KCgkJPCEtLSBycy1wbHVnaW4ganMgLS0+CgkJPHNjcmlwdCB0eXBlPSJ0ZXh0L2phdmFzY3JpcHQiIHNyYz0ibGliL3JzLXBsdWdpbi9qcy9qcXVlcnkudGhlbWVwdW5jaC50b29scy5taW4uanMiPjwvc2NyaXB0PiAgIAoJCTxzY3JpcHQgdHlwZT0idGV4dC9qYXZhc2NyaXB0IiBzcmM9ImxpYi9ycy1wbHVnaW4vanMvanF1ZXJ5LnRoZW1lcHVuY2gucmV2b2x1dGlvbi5taW4uanMiPjwvc2NyaXB0PgoJCTxzY3JpcHQgc3JjPSJsaWIvcnMtcGx1Z2luL3JzLmhvbWUuanMiPjwvc2NyaXB0PgoJCQoJCTwhLS0gcGx1Z2lucyBqcyAtLT4KICAgICAgICA8c2NyaXB0IHNyYz0ianMvcGx1Z2lucy5qcyI+PC9zY3JpcHQ+CgkJCgkJPCEtLSBtYWluIGpzIC0tPgogICAgICAgIDxzY3JpcHQgc3JjPSJqcy9tYWluLmpzIj48L3NjcmlwdD4KCQk8c2NyaXB0PgogICAgICAgICAgKGZ1bmN0aW9uKGkscyxvLGcscixhLG0pe2lbJ0dvb2dsZUFuYWx5dGljc09iamVjdCddPXI7aVtyXT1pW3JdfHxmdW5jdGlvbigpewogICAgICAgICAgKGlbcl0ucT1pW3JdLnF8fFtdKS5wdXNoKGFyZ3VtZW50cyl9LGlbcl0ubD0xKm5ldyBEYXRlKCk7YT1zLmNyZWF0ZUVsZW1lbnQobyksCiAgICAgICAgICBtPXMuZ2V0RWxlbWVudHNCeVRhZ05hbWUobylbMF07YS5hc3luYz0xO2Euc3JjPWc7bS5wYXJlbnROb2RlLmluc2VydEJlZm9yZShhLG0pCiAgICAgICAgICB9KSh3aW5kb3csZG9jdW1lbnQsJ3NjcmlwdCcsJy8vd3d3Lmdvb2dsZS1hbmFseXRpY3MuY29tL2FuYWx5dGljcy5qcycsJ2dhJyk7CiAgICAgICAgCiAgICAgICAgICBnYSgnY3JlYXRlJywgJ1VBLTU0NTk0NS0xJywgJ2F1dG8nKTsKICAgICAgICAgIGdhKCdzZW5kJywgJ3BhZ2V2aWV3Jyk7CiAgICAgICAgCiAgICAgICAgPC9zY3JpcHQ+CiAgICA8L2JvZHk+CjwvaHRtbD4K'){
					$image_link = 'assets/images/logo-balls.png';
				}else{
				
					if (false === file_get_contents('https://contact25.com/uploads/7_'.$orderdetails.'.jpg',0,null,0,1)) {
						$image_link = 'assets/images/logo-balls.png';
					}else{
						$image_link = 'https://contact25.com/uploads/7_'.$orderdetails.'.jpg';
					}
	
				}
				
				$image_link = 'https://contact25.com/uploads/7_'.$orderdetails.'.jpg';
				
				if ($_SESSION['c25_id']==1){
					
					
					if(isset($row['od_collection'])){
						//$deadline_date = date ('Y-m-d H:i:s',strtotime($row['od_collection'])); - check when fixing dates
						$deadline_date = $row['od_collection'];
					}else{
						
						$deadline_date = date ('Y-m-d 23:59:59',strtotime($row['od_date'].'+ 1 day'));
					}
					
					if ($row['od_collection']>0){
						
					$timer = '<span class="text-success" style="font-size:25px;"><i class="fa fa-map-marker"></i> <span style="font-size:12px; color:black;">Collection in</span>
					<div class="text-danger deadline_date_time" data-deadline="'.$deadline_date.'" style="font-size:18px; font-weight:bold;"><i class="fas fa-spinner fa-spin" style="display: block;margin-left: auto;margin-right: auto;width: 50%;"></i></div>';
						
					
				}else{
					$timer = '<span class="text-purple" style="font-size:25px; color:grey;"><i class="fa fa fa-truck"></i> <span class="text-purple" style="font-size:12px;">Post in</span>
					<div class="text-danger deadline_date_time" data-deadline="'.$deadline_date.'" style="font-size:18px; font-weight:bold;"><i class="fas fa-spinner fa-spin" style="display: block;margin-left: auto;margin-right: auto;width: 50%;"></i></div>';
						
					
				}
					
					
				if ($row['od_collection']>0){
						
					
						
					$label_dispatch =' <a class="btn btn-success btn-block waves-effect waves-light" style="height: 60px;font-size:30px;">Collected <i class="fas fa-people-carry"></i> </a>
			   
			   		<a href="https://inpost.co.uk/en/where-is-your-locker" class="btn btn-dark  btn-block waves-effect waves-light ">Collection Date - Thursday 15 Mar 2018</a>';	
				}else{					
						
					$label_dispatch ='<a class="btn btn-warning btn-block waves-effect waves-light" style="height: 60px;font-size:30px;">INPOST <i class="fas fa-shipping-fast"></i></a>
			   
			   		<a href="https://inpost.co.uk/en/where-is-your-locker" class="btn btn-dark  btn-block waves-effect waves-light ">Where is my inpost locker?</a>
					
					'.delivery_options().'
					
					';
				}
					
					$stock = product_details($row['od_s_id']);
					print '<div class="col-lg-4 col-md-6 col-sm-6 col-xs-12">
					
          <div class="white-box ribbon-wrapper card">
		  '.$stock[0].'
		 
                                    
               <div class="ribbon ribbon-corner ribbon-right ribbon-info" style="font-size:10px; padding-top:24px; padding-right:17px;">
			    <i class="fas fa-times"> <span  style="font-size:20px;"> '.$qty_od.'</span></i>
			   </div>        
			   
			   <div style="padding:10px"></div>
			   
		  
		  <div style="text-align:center;">
		
		  	'.$timer.'
			
				<span class="text-purple" style="font-size:35px; color:grey;  display:none;"><i class="fa fa fa-truck"></i> 
				
				<span class="text-danger" style="font-size:25px;">5h 7m 5s</span>
				
				 <div class="product-text" style="border-top:0px;">
				
				</div>
				

				
				<div style="padding:15px"></div>
				
				
				
		  		</div> 
				
				
				
			
            <div class="product-img">
                <img src="'.$image_link.'" min-height="200px;"> 
                
            </div> 
			
			
			<h1>£'.$price_od.'<span style="font-size: 12px"><small class="text-muted db"> '.$stock[1].' </small></span></h1>
			
			<div style="height:55px;">
			
           		<h3 class="box-title m-b-0">
			  		'.$row['od_num'].'
			  	</h3>
				
			
			 </div>
			  
			  <div style="padding:5px"></div>
		
			
			   
			   '.$label_dispatch.'
			   
				
            </div>
          </div>
        </div>
				';
					
				}else
					
					print '<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12 hash_link_64386">
          <div class="white-box">
            <div class="product-img">
                <img src="http://contact25.com/uploads/7_797559.jpg" height="200px;" style="margin: 30px"> 
                
            </div>
            <div class="product-text">
              <span class="pro-price bg-danger">1</span>
			  
			  
			  <h1>64386 <span style="font-size: 12px">(SKU: 5292450)</span></h1>
              <h3 class="box-title m-b-0">
			  <a href="https://sellercentral.amazon.co.uk/inventory/ref=ag_invmgr_dnav_myo_?tbla_myitable=sort:%7B%22sortOrder%22%3A%22DESCENDING%22%2C%22sortedColumnId%22%3A%22date%22%7D;search:SKU-CONTACT25-797559;pagination:1;" target="_blank">Faber-Castell fineline super polymer 2B 0.5mm pencil leads (pack of Users)
			  </a>
			  </h3>
			  <a href="https://sellercentral.amazon.co.uk/inventory/ref=ag_invmgr_dnav_myo_?tbla_myitable=sort:%7B%22sortOrder%22%3A%22DESCENDING%22%2C%22sortedColumnId%22%3A%22date%22%7D;search:SKU-CONTACT25-797559;pagination:1;" target="_blank">
              <small class="text-muted db">£1.89 (<span style="color:green">£0.37)</span> 4005401205029 (179503)</small>
			  <br>
			   </a>
			   <h3 class="box-title m-b-0" style="font-size:30px;">
			   <a href="https://sellercentral.amazon.co.uk/inventory/ref=ag_invmgr_dnav_myo_?tbla_myitable=sort:%7B%22sortOrder%22%3A%22DESCENDING%22%2C%22sortedColumnId%22%3A%22date%22%7D;search:SKU-CONTACT25-797559;pagination:1;" target="_blank"></a>
			   <a href="/rm_labels/64386.pdf" class="print_label_again_64386" target="_blank" style="display:none;font-size: 42px;color:red;">
			   <i class="fa fa-file-pdf-o"></i>
			   </a>
				
<span class="format_display format_display_797559" style="color:green; font-size:68px;"><span style="font-size:48px; color:#03a9f3;">LL</span></span><span class="nextformat_display" font-size:10px;=""></span> <span class="packing_display packing_display_797559" style="color:orange;">A/000</span> <span style="font-size:18px">(<span class="weight_display weight_display_797559">3</span>g)</span></h3><br>
			   
			   
			   <div class="spinner_update_size_weight"></div>
				<span style="font-size:30px;">
				
				
			   <select class="update_format" data-s_id="797559">
			   		<option selected="">&gt;&gt;</option><option>L</option><option selected="">LL</option><option>P</option>
			   </select>
				
			  	
				
			   <select class="update_packing" data-s_id="797559">
			   		<option>&gt;&gt;</option><option value="1" selected="">A/000</option><option value="2">B/00</option><option value="3">C/0</option><option value="4">D/1</option><option value="5">E/2</option><option value="6">F/3</option><option value="7">G/4</option><option value="8">H/5</option><option value="9">J/6</option><option value="10">K/7</option><option value="11">BOX</option>
			   </select>


			   <input type="text" placeholder="Weight" class="weight_update" data-s_id="797559" value="3" style="width: 80px">
			   
			   
			   
				
			   <select class="update_nextformat" data-s_id="797559">
			   		<option selected="" value="">NEXT FORMAT</option><option>1</option><option>2</option><option>3</option><option>4</option><option>5</option>
			   </select>
			    
			   </span>
			   
			   <div style="padding:15px"></div>
			   
			    
			   
			   <a href="#hash_link_64386" class="btn btn-custom btn-block waves-effect waves-light produce_rm_label hide_64386" data-order_id="64386" style="background-color: #03a9f3; border:#03a9f3; height: 150px;font-size:30px;"><br>
API LABEL <i class="ti-angle-double-right"></i></a>
			   
			    <a href="#hash_link_64386" class="btn btn-custom btn-block waves-effect waves-light produce_rm_label_upgrade hide_64386" data-order_id="64386" style="background-color: red; border:#03a9f3; font-size:30px;">
RM24</a>
			   
			   <a href="#hash_link_64386" class="btn btn-custom btn-block waves-effect waves-light produce_inpost_label hide_64386" data-order_id="64386" style="background-color: orange; border:#03a9f3; font-size:30px;">
INPOST</a> 
			   
			   
			   
			   <div style="padding:15px"></div>
			   
			   <a target="_blank" href="http://contact25.com/tcpdf/examples/invoice.php?special_req=64386&amp;special_req_vat=1" class="btn btn-custom  btn-block waves-effect waves-light text-info" style="background-color: #00c292; border:#00c292;">INV COPY PDF</a>
			   
			    
			  
				   <a href="#hash_link_64386" class="btn refund_display_wrapper_64386 btn-custom  btn-block waves-effect waves-light text-info damaged_req_refund_reorder" data-order_id="64386" data-s_id="797559" style="background-color: #03a9f3; border:#03a9f3;height: 50px;">DAMAGED<br>
	RE-ORDER &amp; REQ REFUND</a>
			   
			   
			   
				<a href="#hash_link_64386" data-order_id="64386" data-s_id="797559" class="btn btn-custom  btn-block waves-effect waves-light text-info undelivered_chase_supplier undelivered_wrapper_64386" style="background-color: #00c292; border:#00c292;" "="">UNDELIVERED - CHASE SUPPLIER</a>
				
				<div class="refund_64386" style="margin-bottom:5px;"></div>
				
			   <div style="padding:15px"></div>

				<a href="#hash_link_64386" class="btn btn-custom btn-block waves-effect waves-light refund_order hide_refund_label_64386" data-order_id="64386" data-value="1.89" data-od_id="79176" style="background-color: RED; border:#03a9f3; font-size:30px;">
REFUND</a> 
			   
			   
			   
			   <div style="padding:15px"></div>
			  
			  
			   
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

	function product_details($orderdetails){
		global $db;
		$sql = 'SELECT
			*
		FROM
			stock_c25
		LEFT JOIN
			stock_room
		ON
			stock_room.sr_id = stock_c25.s_room
		WHERE
			stock_c25.s_s_id = "'.$orderdetails.'"

		';
		
		$query = mysqli_query($db,$sql);
		$row = mysqli_fetch_assoc($query);
		$num_rows = mysqli_num_rows($query);
			if($num_rows>0){
				do{
					if(strlen($row['sr_name'])>0){
						
						return array('<div class="ribbon ribbon ribbon-left ribbon-success" style="font-size:20px; height:50px; margin: auto; width: 40%; padding: 10px;">'.$row['sr_name'].' <i class="ti-package"></i> '.$row['s_box'].' </div>',$row['s_ISBN13']);
					}else{
					
						return array('',$row['s_ISBN13']);
						
					}
					
				}while($row = mysqli_fetch_assoc($query));
		}
	}

function delivery_options(){
		global $db;
		$sql = 'SELECT 
					*
				FROM
					users_delivery_provider
				WHERE
					users_delivery_provider.udp_u_id = "'.$_SESSION['c25_id'].'"
		';

	$query = mysqli_query($db,$sql);
	$row = mysqli_fetch_assoc($query);
	$num_rows = mysqli_num_rows($query);
	
	$output = '';
	$style = array('danger','success','info','warning','purple','inverse');
	
	if($num_rows>0){
		$i = 0;
				do{
					if(($row['udp_deleted'])<1){
						
					if (($i==6)||($i==12)){
					$i = 0;	
						
					}
				
					$output .= '<a class="btn btn-'.$style[$i].' btn-block waves-effect waves-light" style="height: 60px;font-size:30px;">'.$row['udp_name'].' <i class="fas fa-shipping-fast"></i></a>';	
					$i++;
					}
				}while($row = mysqli_fetch_assoc($query));
		
		}
		return $output;
	}

?>

<script> 
$(document).ready(function () {
	
	
		
		
		$('.deadline_date_time').each(function(deadline,item){
			
			var deadline2 = $(item).data('deadline');
			
			
			//var countDownDate = new Date("Jan 5, 2021 15:37:25").getTime();
			var countDownDate = new Date(deadline2).getTime();

// Update the count down every 1 second
var x = setInterval(function() {

  // Get todays date and time
  var now = new Date().getTime();
    
  // Find the distance between now and the count down date
  var distance = countDownDate - now;
    
  // Time calculations for days, hours, minutes and seconds
  var days = Math.floor(distance / (1000 * 60 * 60 * 24));
  var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
  var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
  var seconds = Math.floor((distance % (1000 * 60)) / 1000);
	 if (days>0){
		var display_days = days + "d " ; 

	 }else{

		var display_days =  "" ;  
	 }
	
	
	
	$(item).html(display_days + hours + "h "
  + minutes + "m " + seconds + "s ");
  // Output the result in an element with id="demo"
  
    
  // If the count down is over, write some text 
  if (distance < 0) {
    clearInterval(x);
	  $(item).html("Overdue");
  }
}, 1000);
		
		});
		
		
		
		
		

	
	});


</script>	

