<?php
/**
 * 入口文件，所有主题
 * */

#检测安装
function TestInstallation()
{
	$filename = 'system/config/config.php';
	if(!file_exists($filename))
	{
		header("location:install/");exit;
	}
}

$SET_PHP = 'SET_URI';

TestInstallation();

require 'system/.setting_uri.php';
require 'public_include.php';
require 'system/index_module.php';

define(LIST_ID, $_REQUEST['id']);

load_theme( getThemeDir() );