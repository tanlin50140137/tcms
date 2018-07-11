<?php
require('../../public_include.php');
/**
 * XML-RPC协议地址
 * */
$id = trim(htmlspecialchars($_POST['id'],ENT_QUOTES));
$row = db()->select('xmlrpc,themename,flag')->from(PRE.'theme')->where(array('id'=>$id))->get()->array_row();

if( $_POST != array() )
{
	#普通数据
	$filename = dir_url($row['xmlrpc']);//存储至xml文件	
	$data = (array)simplexml_load_string(file_get_contents($filename));
	
	$files = $_FILES;
	$rows = $_POST;
	
	#有文件上传
	foreach($files as $k=>$v)
	{
		if( $v['error'] == 0 )
		{
			#先册除旧图片
			$unlipath = dir_url(urldecode($data[$k]));
			if( is_file($unlipath) )
			{
				unlink($unlipath);
			}
			#上传旧图片
			$extarr = explode('.', $v['name']);
			$ext = end($extarr);

			$destination = str_replace('xml-rpc.xml', '', dir_url($row['xmlrpc'])).'upload/'.date('YmdHis').mt_rand(10000,99999).'.'.$ext;
			if( move_uploaded_file($v['tmp_name'], $destination) )
			{
				$rows[$k] = str_replace(DIR_URL, '', $destination);
			}
		}
	}
	
	#重新记录
	$xml = xml_str2($rows);
	file_put_contents($filename, $xml);

	session_start();
	$_SESSION['flagEorre'] = 1;
	
	if( $row['flag'] == 1 )
	{
		header('location:'.apth_url('system/admin/index.php?act=plugIns&id='.$id));
	}
	elseif( $row['flag'] == 0 ) 
	{
		header('location:'.apth_url('system/admin/index.php?act=ThemePlugin&id='.$id));
	}
	
}