<?php
/**
 * This_cms_system 程序安装
 */

require 'next/step_admin.php';

?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<meta name="viewport" content="width=device-width,initial-scale=1.0,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no">
<title>This_cms_system 7.4.1 User 程序安装</title>
<script type="text/javascript" src="next/js/jquery-1.7.1.min.js"></script>
<style type="text/css">
*{margin:0;padding:0;font-family:"微软雅黑","Times New Roman",Georgia,Serif}
</style>
</head>
<body>
<?php

$act = $_REQUEST['act'] == ''?'step1':$_REQUEST['act'];
	
if( $act!=null && function_exists( $act ) )
{
	$act();
}

?>
</body>
</html>