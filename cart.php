<?php
	require_once('includes/configure.php');
	$mysql_servicer=DB_SERVER;//服务器名
	$mysql_username=DB_SERVER_USERNAME;//数据库用户名
	$mysql_passwrod=DB_SERVER_PASSWORD;//数据库密码
	$mysql_database=DB_DATABASE; //要连接的数据库名字
	$DB_PREFIX = DB_PREFIX;
	$sql_table_prefix = $DB_PREFIX;
	//连接数据库
	error_reporting(0);
	$conntion=mysql_connect($mysql_servicer,$mysql_username,$mysql_passwrod) or die ("0=不能连接数据库:");
	mysql_select_db($mysql_database) or die("0=不能选择这个数据库，或数据库不存在");  
	mysql_query("SET CHARACTER SET utf8");	

	//main 
	$usersql="select * from customers inner join customers_info on customers.customers_id = customers_info.customers_info_id order by customers_info.customers_info_date_of_last_logon desc";
	$result=mysql_query($usersql,$conntion) or die("0=查询失败！错误是：".mysql_error());
	while ( $row = mysql_fetch_array($result)) 
	{
		$shopsql = "SELECT customers.customers_id,customers_basket.customers_basket_id,customers_basket.customers_basket_quantity,customers_basket.products_id,customers_basket.customers_basket_date_added FROM customers INNER JOIN customers_basket ON customers.customers_id = customers_basket.customers_id where customers.customers_id='".$row['customers_id']."'";
		$cartresult=mysql_query($shopsql,$conntion) or die("0=查询失败！错误是：".mysql_error());
		while ( $shopcart = mysql_fetch_array($cartresult)) {
		    $PruID = preg_match('/([1-9]\d*):/', $shopcart['products_id'], $newPruID);
		    $PruIDs = $newPruID['1']; 
		    $shopcartitem = array(
		    	'product_id' => $PruIDs,
		    	'product_num' => $shopcart['customers_basket_quantity'],
		    	'add_time' => $shopcart['customers_basket_date_added'],
		    );
		}


		$user[] = array(
			'username' =>$row['customers_firstname']." ".$row['customers_lastname'],
			'email' =>$row['customers_email_address'],
			'creat_time' =>$row['customers_info_date_account_created'],
			'shopcart'=> $shopcartitem,
			'last_login_time' =>$row['customers_info_date_of_last_logon'],
		);

	}

	$jsonuser = json_encode($user);
?>