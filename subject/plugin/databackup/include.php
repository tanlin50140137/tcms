<?php 
/**
 * 主题调用外部插件 <?php include Pagecall('databackup');?>
 * */
$row = db()->select('xmlrpc,themeimg,themeas,addmenu')->from(PRE.'theme')->where(array('themename'=>'databackup'))->get()->array_row();
if(!empty($row))
{
	$data = (array)simplexml_load_string(file_get_contents(dir_url($row['xmlrpc'])));
	print_r($data);
}
?>