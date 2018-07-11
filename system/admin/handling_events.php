<?php
header("content-type:text/html;charset=utf-8");
require 'processing_program.php';

$act = $_REQUEST['act'];

if( $act!=null && function_exists( $act ) )
{
	$act();
}