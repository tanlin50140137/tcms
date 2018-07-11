<?php
require '../../public_include.php';

$path = '/ueditor/php/upload/image/'.date('Ymd');

if( !is_dir($_SERVER['DOCUMENT_ROOT'].$path) )
{
	mkdir($_SERVER['DOCUMENT_ROOT'].$path,0777,true);
}

if( $_FILES['file']['error'] == 0 )
{
	$ext = explode('.', $_FILES['file']['name']);
	
	$ext = end($ext);
	
	$destination = $path.'/'.date('YmdHis').mt_rand(1000, 9999).'.'.$ext;
	
	if( move_uploaded_file($_FILES['file']['tmp_name'], $_SERVER['DOCUMENT_ROOT'].$destination) )
	{
		$response = new StdClass;
        $response->link = $destination;
        echo stripslashes(json_encode($response));
	}
}