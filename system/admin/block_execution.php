<?php
/**
 * @author Tanlin
 * 执行组件模块功能
 * */
require 'component_function.php';

$act = $_REQUEST['act'];
if( $act!=null && function_exists( $act ) )
{
	$act();
}