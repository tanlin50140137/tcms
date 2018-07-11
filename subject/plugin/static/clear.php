<?php
header('content-type:text/html;charset=utf-8');
require('../../../public_include.php');
ob_start();
ob_clean();
#主题
$theme = db()->select('themename')->from(PRE.'theme')->where(array('addmenu'=>'OFF','flag'=>0))->get()->array_row();

$path = dir_url('subject/'.$theme['themename'].'/cache');
if( is_dir($path) )
{
	$arrlist = scandir($path);
	foreach($arrlist as $k=>$v)
	{
		if( $v!='.' && $v!='..' )
		{
			$filename = $path.'/'.$v;
			if(is_file($filename))
			{
				unlink($filename);
			}
		}
	}
}
echo '清空缓存：'.date('Y-m-d H:i:s').'<br/>';
echo '--------------------------------------<br/>';
echo ' 操作成功...！　暂无缓存<br/>';
echo '--------------------------------------';