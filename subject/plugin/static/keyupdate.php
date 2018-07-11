<?php
header('content-type:text/html;charset=utf-8');
require('../../../public_include.php');
include Pagecall('static');

if($data['filter'] == 'ON')
{
	echo "<script>		
		parent.loding_img_show();
		open('compile_index.php?act=1','update');
	</script>";
}
else 
{#动态
	echo '当前使用动态...';
}