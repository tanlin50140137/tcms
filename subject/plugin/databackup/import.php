<?php
require('../../../public_include.php');
date_default_timezone_set('PRC');
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<meta name="viewport" content="width=device-width,initial-scale=1.0,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no">
<title>外部导入备份文件</title>
<script type="text/javascript" src="<?php echo apth_url('subject/plugin/databackup/js/jquery-1.7.1.min.js');?>"></script>
<style type="text/css">
*{margin:0;padding:0;font-family:"微软雅黑","Times New Roman",Georgia,Serif}
</style>
</head>
<body>
<form action="import_data.php" method="post" enctype="multipart/form-data">
<div style="width:1000px;margin:20px auto auto auto;">

					<table class="tableBox" style="border:1px solid #E1E1E1;width:100%;border-collapse:collapse;font-size:14px;color:#666666;" border="1">
						<tr>
							<td height="40" align="center">&nbsp; <font color="red">* 本地上传备份文件格式ZIP文件，文件上传成功，将会自动导入！</font></td>
						</tr>
						<tr>
							<td height="60" align="center">&nbsp; 
							<input type="file" name="file" size="60" style="border: 1px solid #CCCCCC;padding: 0.25em 0.25em 0.25em 0.25em;background-position: bottom;background: #FFFFFF;font-size: 1em;">
							 &nbsp; &nbsp; &nbsp;
							<span>
							<input type="hidden" name="id" value="<?php echo $_GET['id'];?>"/> 
							<input type="submit" value="上传" style="border:1px solid #3A6EA5;width:50px;height:29px;background:#3A6EA5;color:#FFFFFF;">
							 &nbsp; 
							<input type="reset" value="重置" style="border:1px solid #3A6EA5;width:50px;height:29px;background:#3A6EA5;color:#FFFFFF;">
							</span>
							</td>
						</tr>
					</table>

	</div>
</form>	
	<script>
	$(function(){
		$(".tableBox tr").filter(":even").css("background","#F4F4F4").end().filter(":odd").css("background","#FFFFFF");
		$(".tableBox tr").hover(function(){
		$(this).css({"background":"#FFFFDD"});
		},function(){
			$(".tableBox tr").filter(":even").css("background","#F4F4F4").end().filter(":odd").css("background","#FFFFFF");
		});
	});
	</script>
</body>
</html>