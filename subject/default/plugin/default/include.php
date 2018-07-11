<?php 
/**
 * 主题调用自带插件 <?php include ThemePagecall('default');?>
 * */
$row = db()->select('xmlrpc,themeimg,themeas,addmenu')->from(PRE.'theme')->where(array('themename'=>'default'))->get()->array_row();
if(!empty($row))
{
	$default = (array)simplexml_load_string(file_get_contents(dir_url($row['xmlrpc'])));
	print_r($default);
}
?>