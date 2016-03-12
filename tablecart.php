<?php

	if($GET['verify='] == '123'){}  else{ exit(); }
	
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

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>用户购物车产品</title>
</head>

<body>
<table width="80%" border="1" align="center">
  <tr>
    <td>用户名</td>
    <td>注册时间</td>
    <td>购物车产品（产品ID,添加时间）</td>
    <td>最后登录时间</td>
  </tr>
<?php
$usersql="select * from customers inner join customers_info on customers.customers_id = customers_info.customers_info_id order by customers_info.customers_info_date_of_last_logon desc";
$result=mysql_query($usersql,$conntion) or die("0=查询失败！错误是：".mysql_error());
while ( $row = mysql_fetch_array($result)) 
{
?>
  <tr>
    <td><?php echo $row['customers_firstname']."&nbsp;".$row['customers_lastname'] ?></td>
    <td><?php echo $row['customers_info_date_account_created'] ?></td>
    <td>
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td>产品ID</td>
        <td>产品数量</td>
        <td>添加时间</td>
      </tr>
        <?php  
    	//查询用户购物车
    	$shopsql = "SELECT customers.customers_id,customers_basket.customers_basket_id,customers_basket.customers_basket_quantity,customers_basket.products_id,customers_basket.final_price,customers_basket.customers_basket_date_added FROM customers INNER JOIN customers_basket ON customers.customers_id = customers_basket.customers_id where customers.customers_id='".$row['customers_id']."'";
    	$cartresult=mysql_query($shopsql,$conntion) or die("0=查询失败！错误是：".mysql_error());
    	while ( $shopcart = mysql_fetch_array($cartresult)) {
    			$PruID = preg_match('/([1-9]\d*):/', $shopcart['products_id'], $newPruID);
    			$PruIDs = $newPruID['1']; 
    	?>
      <tr>
        <td><a  target="_blank" href="/index.php?main_page=product_info&products_id=<?php echo $PruIDs ?>"><?php echo $PruIDs ?></a></td>
        <td><?php echo $shopcart['customers_basket_quantity'] ?></td>
        <td><?php echo $shopcart['customers_basket_date_added'] ?></td>
      </tr>
      <?php
      	}
      ?>
    </table></td>
    <td><?php echo $row['customers_info_date_of_last_logon'] ?></td>
  </tr>

<?php
}
?>
</table>
</body>
</html>
