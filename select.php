<?php
	$host='mysql10.000webhost.com';
	$uname='a2618514_admin';
	$pwd='centro123';
	$db="a2618514_andrGPS";

	$con = mysql_connect($host,$uname,$pwd) or die("connection failed");
	mysql_select_db($db,$con) or die("db selection failed");
	 
	$DeviceId=$_REQUEST['DeviceId'];
	 
	$r=mysql_query("select * from Reff_SmartPhone where mac_smartphone='".$DeviceId."'",$con) or die(mysql_error());

	$num_rows = mysql_num_rows($r);
	
	if($num_rows > 0)
	{
	 $flag[retur] = 1;
	}
	else
	{
	$flag[retur] = 0;
	}

	 
	echo json_encode($flag);
	mysql_close($con);
?>