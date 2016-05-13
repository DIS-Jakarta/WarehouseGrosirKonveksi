<?php
	$host='mysql10.000webhost.com';
	$uname='a2618514_admin';
	$pwd='centro123';
	$db="a2618514_andrGPS";

	$con = mysql_connect($host,$uname,$pwd) or die("connection failed");
	mysql_select_db($db,$con) or die("db selection failed");
	 
	$lati=$_REQUEST['lati'];
	$longi=$_REQUEST['longi'];
	$DeviceId=$_REQUEST['DeviceId'];
	$flag['code']=0;
	
	
	$selectdeviceid = mysql_query("select * from TransGPS where mac_smartphone = '$DeviceId'",$con);
	
	$num_rowsdeviceid = mysql_num_rows($selectdeviceid);
	
	if($num_rowsdeviceid > 0)
	{
		if($r=mysql_query("UPDATE TransGPS longitude = '$longi', lattidude = '$lati', SET lastupdate = NOW() WHERE mac_smartphone = '$DeviceId')",$con))
		{
			$flag['code']=1;
			//echo"hi";
		}
	}
	else
	{
		if($r=mysql_query("insert into TransGPS values('$DeviceId','$longi','$lati',NOW()) ",$con))
		{
			$flag['code']=1;
			//echo"hi";
		}
	}

	print(json_encode($flag));
	mysql_close($con);
?>