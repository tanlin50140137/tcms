<?php 
/**
 * 主题调用外部插件 <?php include Pagecall('email');?>
 * */
$row = db()->select('xmlrpc,themeimg,themeas,addmenu')->from(PRE.'theme')->where(array('themename'=>'email'))->get()->array_row();
if($row['addmenu'] == 'OFF')
{
	if(!empty($row))
	{
		$email = (array)simplexml_load_string(file_get_contents(dir_url($row['xmlrpc'])));
		//print_r($email);
	}
}
#邮件类
require 'MailAndSSL.class.php';