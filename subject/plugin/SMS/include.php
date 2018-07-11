<?php 
require "external/TopSdk.php";
require "external/Autoloader.php";
require 'external/config/config.php';
require 'external/alidayu_message.php';
/**
 * 主题调用外部插件 <?php include Pagecall('SMS');?>
 * */
$row = db()->select('xmlrpc,themeimg,themeas,addmenu')->from(PRE.'theme')->where(array('themename'=>'SMS'))->get()->array_row();
if($row['addmenu'] == 'OFF')
{
	if(!empty($row))
	{
		$SMS = (array)simplexml_load_string(file_get_contents(dir_url($row['xmlrpc'])));
		//print_r($SMS);
	}
}