<?php
header('content-type:text/html;charset=utf-8');
require '../../public_include.php';
#注删一个函数
########################################################################
#处理主题事件,获取主题或插件
function GetLoad()
{
	session_start();
	
	$id = trim(htmlspecialchars($_GET['id'],ENT_QUOTES));

	$path = 'system/remotelink.php';
	$filename = SERVICE_LINK.$path;
	#查询数据,服务商
	$json = vcurl($filename,'act=GetLoad&id='.$id);
	$rows = json_decode($json,true);
		
	#从服务商下载文件包
	$path = dir_url('system/compile/uploadzip');
	$data = base64_decode($rows[1]);
	$filenamezip = $path.'/'.$rows[0]['themename'].'.zip';	
	file_put_contents($filenamezip, $data);
	#解压文件包
	if( $rows[0]['flag'] == 1 )
	{#插件
		$path = dir_url('subject/plugin');
	}
	elseif( $rows[0]['flag'] == 0 ) 
	{#主题
		$path = dir_url('subject');
	}
	#执行解压
	$data = pclzip($filenamezip,$path);
	#解压成功，记录开始记录数据
	if($data)
	{
		$int = db()->select('id')->from(PRE.'theme')->where(array('themename'=>$rows[0]['themename']))->get()->array_nums();	
		
		$datainfo = $path.'/'.$data.'/datainfo.txt';
		if( is_file($datainfo) )
		{
			$json = file_get_contents($datainfo);
			$datArr = json_decode($json,true);
			$art = $datArr['art'];//文档内容
			$th = $datArr['th'];//主题或插件
			$mo = $datArr['mo'];//模块
			$cu = $datArr['cu'];//栏目
			$cls = $datArr['cls'];//分类
			$tag = $datArr['tag'];//标签
		}
		
		if($int == 0)
		{#不存在，添加数据
			$int = db()->insert(PRE.'theme',$th);
			$themeId = db()->getlast_id();
			if( $rows[0]['flag'] == 0 ) 
			{#栏目
				if(!empty($cu))
				{
					foreach($cu as $k=>$v)
					{
						$v['templateid'] = $themeId;
						db()->insert(PRE.'template',$v);
					}
				}
			}
			#分类
			if(!empty($cls))
			{
				foreach($cls as $k=>$v)
				{
					array_shift($v);
					$v['templateid'] = $themeId;
					db()->insert(PRE.'classified',$v);
					$clsId = db()->getlast_id();
				}
			}
			#标签
			if(!empty($tag))
			{
				foreach($tag as $k=>$v)
				{
					$v['templateid'] = $themeId;
					db()->insert(PRE.'tag',$v);
				}
			}
			#保存模块
			if(!empty($mo))
			{
				foreach($mo as $k=>$v)
				{
					$v['templateid'] = $themeId;
					db()->insert(PRE.'module',$v);
				}
			}
			#文档内容
			if(!empty($art))
			{
				foreach($art as $k=>$v)
				{
					array_shift($v);
					$v['cipid'] = $clsId;
					$v['author'] = $_SESSION['username'];
					$v['templateid'] = $themeId;
					db()->insert(PRE.'article',$v);
				}
			}
		}
			
		if($int)
		{
			session_start();
			$_SESSION['flagEorre'] = 1;
			#删除文件包
			if(file_exists($filenamezip))
			{
				unlink($filenamezip);
				unlink($datainfo);
			}
			#跳转
			if( $rows[0]['flag'] == 1 )
			{#插件	
				header('location:index.php?act=PluginMng');
			}
			elseif( $rows[0]['flag'] == 0 ) 
			{#主题
				header('location:index.php?act=ThemeMng');
			}
		}
		else 
		{
			header('location:index.php?act=ApplicationCenter');
		}
	}
}
#处理主题事件,启用或禁用
function theme_enable()
{
	$id = trim(htmlspecialchars($_GET['id'],ENT_QUOTES));
	
	$row = db()->select('addmenu')->from(PRE.'theme')->where(array('id'=>$id))->get()->array_row();
	
	if( $row['addmenu'] == 'ON' )#当前主题为禁用状态
	{
		#让所有主题禁用
		$sql = 'update '.PRE.'theme set addmenu="ON",sort=0 where flag=0 ';
		db()->query($sql)->exect();
		#让当前主题启用
		db()->update(PRE.'theme', array('addmenu'=>'OFF','sort'=>1), array('id'=>$id));
	}
	else 
	{	#当前主题为启用状态
		db()->update(PRE.'theme', array('addmenu'=>'ON','sort'=>0), array('id'=>$id));
	}
	
	session_start();
	$_SESSION['flagEorre'] = 1;
	if( pingmwh() )
	{			
		header('location:index.php?act=ThemeMng');
	}
	else
	{
		header('location:index.php?act=ThemeMng_phone');
	}
}
#处理主题事件,删除主题,plugInsDeletet　(删除插件)
function ThemeMngDeletet()
{
	$id = trim(htmlspecialchars($_GET['id'],ENT_QUOTES));
	$row = db()->select('themename')->from(PRE.'theme')->where(array('id'=>$id))->get()->array_row();
	#先删除目录
	$mkdirApth1 = dir_url('subject/'.$row['themename']);
	if(is_dir($mkdirApth1))
	{
		deletedir($mkdirApth1);
	}
	#查询主题文章总数
	$rows = db()->select('id')->from(PRE.'article')->where(array('templateid'=>$id))->get()->array_rows();
	#将templateid修改成，默认值0
	if( !empty($rows) )
	{
		foreach( $rows as $k => $v )
		{
			db()->update(PRE.'article', array('templateid'=>0), array('id'=>$v['id']));
		}
	}
	#删除主题栏目
	db()->delete(PRE.'template',array('templateid'=>$id));	
	#删除主题模块
	db()->delete(PRE.'module',array('templateid'=>$id));
	#后删除数据
	$int = db()->delete(PRE.'theme',array('id'=>$id));
	if($int)
	{
		session_start();
		$_SESSION['flagEorre'] = 1;
		if( pingmwh() )
		{		
			header('location:index.php?act=ThemeMng');
		}
		else
		{
			header('location:index.php?act=ThemeMng_phone');
		}
	}
	else 
	{
		if( pingmwh() )
		{
			echo "<script>alert('主题删除失败');location.href='index.php?act=ThemeMng';</script>";
		}
		else
		{
			echo "<script>alert('主题删除失败');location.href='index.php?act=ThemeMng_phone';</script>";
		}
	}
}
#处理主题事件,新建主题
function Theme_edit()
{
	$data['themename'] = $_POST['themename'];
	if( $data['themename'] == '' )
	{
		echo "<script>alert('主题ID未命名');location.href='index.php?act=Theme_edit';</script>";exit;
	}
	$int = db()->select('*')->from(PRE.'theme')->where(array('themename'=>$data['themename']))->get()->array_nums();
	if( $int == 1 )
	{
		echo "<script>alert('主题ID已经存在');location.href='index.php?act=Theme_edit';</script>";exit;
	}
	$data['themeas'] = $_POST['themeas'];
	if( $data['themeas'] == '' )
	{
		echo "<script>alert('主题名称未命名');location.href='index.php?act=Theme_edit';</script>";exit;
	}
	$data['author'] = $_POST['author'];
	$data['homepage'] = $_POST['homepage'];
	if( $data['author'] == '' )
	{
		echo "<script>alert('请输入主题作者');location.href='index.php?act=Theme_edit';</script>";exit;
	}
	$data['description'] = $_POST['description'];
	if( $data['description'] == '' )
	{
		echo "<script>alert('请输入主题简介');location.href='index.php?act=Theme_edit';</script>";exit;
	}
	
	$arr = $_FILES['file'];
	if($arr['error'] == 4)
	{
		echo "<script>alert('请上传主题图片');location.href='index.php?act=Theme_edit';</script>";exit;
	}
	if( $arr['size'] > (1024*1024*2) )
	{
		echo "<script>alert('上传主题图片，大小范围超出2MB');location.href='index.php?act=Theme_edit';</script>";exit;
	}
	$extarr = explode('.', $arr['name']);
	$ext = end($extarr);
	if( !in_array($ext, array('jpeg','jpg','gif','png')) )
	{
		echo "<script>alert('上传主题图片，类型不允许');location.href='index.php?act=Theme_edit';</script>";exit;
	}
	
	$data['price'] = $_POST['price'];
	$data['publitime'] = time();
	//如果验证都通过，选创建主题目录
	#生成主题目录
	$mkdirApth = dir_url('subject/'.$data['themename']);#主目录
	$dirNames = array('css','images','js','plugin','template','upload');#子目录
	if( !is_dir($mkdirApth) )
	{
		foreach( $dirNames as $k=>$v )
		{
			mkdir($mkdirApth.'/'.$v,0777,true);
		}
		#映射文件
		$mappingApgh = dir_url('system/compile/mapping.txt');
		$mappingCom = dir_url('system/compile/mapping-com.txt');
		$str = file_get_contents($mappingApgh);	
		$com = file_get_contents($mappingCom);
		#生成入口文件	
		file_put_contents($mkdirApth.'/index.php', $str);
		#生成公共文件
		file_put_contents($mkdirApth.'/common.php', $com);
		$str = "<!DOCTYPE html>\n<html>\n<head>\n<meta charset=\"UTF-8\">\n<title>Insert title here</title>\n</head>\n<body>\n1v1\n</body>\n</html>";
		#生成首页index.html
		file_put_contents($mkdirApth.'/template/index.html', str_replace('1v1', '首页 '.str_replace(DIR_URL, APTH_URL, $mkdirApth).'/template/index.html', $str));
		#生成栏目页article_list.html
		file_put_contents($mkdirApth.'/template/article_list.html', str_replace('1v1', '栏目页面 '.str_replace(DIR_URL, APTH_URL, $mkdirApth).'/template/article_list.html', $str));
		#生成内容页article_content.html
		file_put_contents($mkdirApth.'/template/article_content.html', str_replace('1v1', '内容页面 '.str_replace(DIR_URL, APTH_URL, $mkdirApth).'/template/article_content.html', $str));
	}
	#样式目录路径
	$data['style'] = str_replace(DIR_URL.'/', '', $mkdirApth.'/css');
	#生成后台插件,插件,后台管理 
	if( $_POST['autoplug1'] != '' )
	{
		if( strrpos($_POST['autoplug1'], '.') != false )
		{
			$autArr = explode('.', $_POST['autoplug1']);
			$autName = $autArr[0];
		}
		else 
		{
			$autName = $_POST['autoplug1'];
		}
		$mkdirApth2 = $mkdirApth.'/plugin/'.$data['themename'];#主目录
		$dirNames2 = array('css','images','js','logo','upload');#子目录
		if( !is_dir($mkdirApth2) )
		{
			foreach( $dirNames2 as $k=>$v )
			{
				mkdir($mkdirApth2.'/'.$v,0777,true);
			}
			#映射文件
			$mappingApgh3 = dir_url('system/compile/mapping-main2.txt');
			$str2 = file_get_contents($mappingApgh3);	
			#生成入口文件	
			file_put_contents($mkdirApth2.'/'.$_POST['autoplug1'], $str2);
			#生成xml文件
			file_put_contents($mkdirApth2.'/xml-rpc.xml', "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<box><tmpID>{$data['themename']}</tmpID></box>");
		}
	}
	$data['xmlrpc'] = str_replace(DIR_URL.'/', '', $mkdirApth2.'/xml-rpc.xml');
	$data['autoplug1'] = $autName;
	
	#生成前台插件,插件,前台页面
	if( $_POST['autoplug2'] != '' )
	{
		if( strrpos($_POST['autoplug2'], '.') != false )
		{
			$autArr = explode('.', $_POST['autoplug2']);
			$autName = $autArr[0];
		}
		else 
		{
			$autName = $_POST['autoplug2'];
		}
		#映射文件
		$mappingApgh4 = dir_url('system/compile/include2.txt');
		$str3 = file_get_contents($mappingApgh4);	
		
		$userPath = $mkdirApth.'/plugin/'.$data['themename'];
		
		file_put_contents($userPath.'/'.$autName.'.php', str_replace('%themename%', $data['themename'], $str3));
	}
	
	$data['autoplug2'] = $autName;
	
	#上传图片
	$uploadApth = $mkdirApth.'/upload/'.date('YmdHis').'.'.$ext;
	if( !move_uploaded_file($arr['tmp_name'], $uploadApth) )
	{
		echo "<script>alert('主题图片上传失败');location.href='index.php?act=Theme_edit';</script>";exit;
	}
	$data['themeimg'] = str_replace(DIR_URL.'/', '', $uploadApth);
		
	#添加记录数据,这是我有屎以来写得最长的一个函数
	$int = db()->insert(PRE.'theme',$data);
	if($int)
	{
		session_start();
		$_SESSION['flagEorre'] = 1;
				
		header('location:index.php?act=ThemeMng');
	}
	else 
	{
		echo "<script>alert('添加数据失败');location.href='index.php?act=Theme_edit';</script>";exit;	
	}	
}
#处理主题事件,插件信息
function PlugIn_edit()
{
	$data['themename'] = $_POST['themename'];
	if( $data['themename'] == '' )
	{
		echo "<script>alert('插件ID未命名');location.href='index.php?act=PlugIn_edit';</script>";exit;
	}
	$int = db()->select('*')->from(PRE.'theme')->where(array('themename'=>$data['themename']))->get()->array_nums();
	if( $int == 1 )
	{
		echo "<script>alert('插件ID已经存在');location.href='index.php?act=PlugIn_edit';</script>";exit;
	}
	$data['themeas'] = $_POST['themeas'];
	if( $data['themeas'] == '' )
	{
		echo "<script>alert('插件名称未命名');location.href='index.php?act=PlugIn_edit';</script>";exit;
	}
	$data['author'] = $_POST['author'];
	$data['homepage'] = $_POST['homepage'];
	if( $data['author'] == '' )
	{
		echo "<script>alert('请输入插件作者');location.href='index.php?act=PlugIn_edit';</script>";exit;
	}
	$data['description'] = $_POST['description'];
	if( $data['description'] == '' )
	{
		echo "<script>alert('请输入插件简介');location.href='index.php?act=PlugIn_edit';</script>";exit;
	}
	
	$arr = $_FILES['file'];
	if($arr['error'] == 4)
	{
		echo "<script>alert('请上传插件图片');location.href='index.php?act=PlugIn_edit';</script>";exit;
	}
	if( $arr['size'] > (1024*1024*2) )
	{
		echo "<script>alert('上传插件图片，大小范围超出2MB');location.href='index.php?act=PlugIn_edit';</script>";exit;
	}
	$extarr = explode('.', $arr['name']);
	$ext = end($extarr);
	if( !in_array($ext, array('jpeg','jpg','gif','png')) )
	{
		echo "<script>alert('上传插件图片，类型不允许');location.href='index.php?act=PlugIn_edit';</script>";exit;
	}
	
	$data['price'] = $_POST['price'];
	$data['publitime'] = time();
	
	//如果验证都通过，选创建插件目录
	#生成插件目录
	$mkdirApth = dir_url('subject/plugin/'.$data['themename']);#主目录
	$dirNames = array('css','images','js','logo','upload');#子目录
	if( !is_dir($mkdirApth) )
	{
		foreach( $dirNames as $k=>$v )
		{
			mkdir($mkdirApth.'/'.$v,0777,true);
		}
		#映射文件
		$mappingApgh = dir_url('system/compile/mapping-main.txt');
		$str = file_get_contents($mappingApgh);	
		#生成入口文件	
		file_put_contents($mkdirApth.'/'.$_POST['autoplug1'], $str);
		#生成xml文件	
		file_put_contents($mkdirApth.'/xml-rpc.xml', "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<box><tmpID>{$data['themename']}</tmpID></box>");
	}
	#样式目录路径
	$data['style'] = str_replace(DIR_URL.'/', '', $mkdirApth.'/css');
	$data['flag'] = 1;
	#生成后台插件,插件,后台管理 	
	$data['autoplug1'] = $autName;
	
	$data['xmlrpc'] = str_replace(DIR_URL.'/', '', $mkdirApth.'/xml-rpc.xml');
	
	#生成前台插件,插件,前台页面
	if( $_POST['autoplug2'] != '' )
	{
		if( strrpos($_POST['autoplug2'], '.') != false )
		{
			$autArr = explode('.', $_POST['autoplug2']);
			$autName = $autArr[0];
		}
		else 
		{
			$autName = $_POST['autoplug2'];
		}
		#映射文件
		$mappingApgh4 = dir_url('system/compile/include.txt');
		$str3 = file_get_contents($mappingApgh4);
		
		$userPath = dir_url('subject/plugin/'.$data['themename']);

		file_put_contents($userPath.'/'.$autName.'.php', str_replace('%themename%', $data['themename'], $str3));
	}
	
	$data['autoplug2'] = $autName;
	
	#上传图片
	$uploadApth = $mkdirApth.'/logo/'.date('YmdHis').'.'.$ext;
	if( !move_uploaded_file($arr['tmp_name'], $uploadApth) )
	{
		echo "<script>alert('插件图片上传失败');location.href='index.php?act=PlugIn_edit';</script>";exit;
	}
	$data['themeimg'] = str_replace(DIR_URL.'/', '', $uploadApth);
		
	#添加记录数据,这是我有屎以来写得最长的一个函数
	$int = db()->insert(PRE.'theme',$data);
	if($int)
	{
		session_start();
		$_SESSION['flagEorre'] = 1;
				
		header('location:index.php?act=PluginMng');
	}
	else 
	{
		echo "<script>alert('添加数据失败');location.href='index.php?act=PlugIn_edit';</script>";exit;	
	}	
}
#处理插件事件,启用插件
function plugIns_Enable()
{
	$id = trim(htmlspecialchars($_GET['id'],ENT_QUOTES));
	$row = db()->select('addmenu')->from(PRE.'theme')->where(array('id'=>$id))->get()->array_row();
	if($row['addmenu'] == 'ON')
	{
		db()->update(PRE.'theme', array('addmenu'=>'OFF'), array('id'=>$id));
	}
	else 
	{
		db()->update(PRE.'theme', array('addmenu'=>'ON'), array('id'=>$id));
	}
	
	session_start();
	$_SESSION['flagEorre'] = 1;
	if( pingmwh() )
	{			
		header('location:index.php?act=PluginMng');
	}
	else
	{
		header('location:index.php?act=PluginMng_phone');
	}
}
#处理主题事件,删除主题信息
function plugInsDeletet()
{
	$id = trim(htmlspecialchars($_GET['id'],ENT_QUOTES));
	$row = db()->select('themename')->from(PRE.'theme')->where(array('id'=>$id))->get()->array_row();
	#先删除目录
	$mkdirApth = dir_url('subject/plugin/'.$row['themename']);
	if( is_dir($mkdirApth) )
	{
		deletedir($mkdirApth);
	}
	#后删除数据
	$int = db()->delete(PRE.'theme',array('id'=>$id));
	if($int)
	{
		session_start();
		$_SESSION['flagEorre'] = 1;
		if( pingmwh() )
		{		
			header('location:index.php?act=PluginMng');
		}
		else
		{
			header('location:index.php?act=PluginMng_phone');
		}
	}
	else 
	{
		if( pingmwh() )
		{
			echo "<script>alert('删除插件失败');location.href='index.php?act=PluginMng';</script>";
		}
		else
		{
			echo "<script>alert('删除插件失败');location.href='index.php?act=PluginMng_phone';</script>";
		}
	}
}
#处理主题事件,上传插件
function Pluginupload()
{
	$file = $_FILES['file'];
	if( $file['error'] == 4 )
	{
		if( pingmwh() )
		{
			echo "<script>alert('没有文件上传');location.href='index.php?act=PluginMng';</script>";exit;
		}
		else
		{
			echo "<script>alert('没有文件上传');location.href='index.php?act=PluginMng';</script>";exit;
		}
	}
	$extArr = explode('.', $file['name']);
	$ext = end($extArr);
	if( !in_array(strtolower($ext), array('zip')) )
	{
		if( pingmwh() )
		{
			echo "<script>alert('上传插件类型为ZIP，请检查后重试');location.href='index.php?act=PluginMng';</script>";exit;
		}
		else
		{
			echo "<script>alert('上传插件类型为ZIP，请检查后重试');location.href='index.php?act=PluginMng';</script>";exit;
		}
	}
	#移动文件
	$destination = dir_url('system/compile/uploadzip/'.date('YmdHis').'.'.$ext);
	if(!move_uploaded_file($file['tmp_name'], $destination))
	{
		if( pingmwh() )
		{
			echo "<script>alert('文件移动失败');location.href='index.php?act=PluginMng';</script>";exit;
		}
		else
		{
			echo "<script>alert('文件移动失败');location.href='index.php?act=PluginMng';</script>";exit;
		}
	}
	#解压到
	$path = dir_url('subject/plugin');
	#获取插件ID	
	$dirId = pclzip($destination, $path);
	if( !empty($dirId) )
	{
		//解压成功,删除ZIP文件
		unlink($destination);
		#解析数据
		$datainfo = $path.'/'.$dirId.'/datainfo.txt';
		if( is_file($datainfo) )
		{
			$json = file_get_contents($datainfo);
			$datArr = json_decode($json,true);
			$th = $datArr['th'];//主题或插件
			$mo = $datArr['mo'];//模块
		}	
		$int = db()->insert(PRE.'theme',$th);
		$themeId = db()->getlast_id();
		if($int)
		{	
			if(!empty($mo))
			{#保存模块
				foreach($mo as $k=>$v)
				{
					$v['templateid'] = $themeId;
					db()->insert(PRE.'module',$v);
				}
			}	
			
			session_start();
			$_SESSION['flagEorre'] = 1;
			
			#填写插件补充信息		
			if( pingmwh() )
			{		
				header('location:index.php?act=PluginMng');
			}
			else
			{
				header('location:index.php?act=PluginMng_phone');
			}
		}
		else 
		{
			if( pingmwh() )
			{
				echo "<script>alert('记录数据失败');location.href='index.php?act=PluginMng';</script>";exit;
			}
			else
			{
				echo "<script>alert('记录数据失败');location.href='index.php?act=PluginMng_phone';</script>";exit;
			}
		}
	}
	else 
	{
		if( pingmwh() )
		{
			echo "<script>alert('解压失败');location.href='index.php?act=PluginMng';</script>";exit;
		}
		else
		{
			echo "<script>alert('解压失败');location.href='index.php?act=PluginMng_phone';</script>";exit;
		}
	}
}
#处理主题事件,补充插件信息
function Plugins_add()
{
	$themename = $_POST['themename'];
	$data['themeas'] = $_POST['themeas'];
	if( $data['themeas'] == '' )
	{
		echo "<script>alert('插件名称未命名');location.href='index.php?act=PlugIn_edit';</script>";exit;
	}
	$data['author'] = $_POST['author'];
	$data['homepage'] = $_POST['homepage'];
	if( $data['author'] == '' )
	{
		echo "<script>alert('请输入插件作者');location.href='index.php?act=PlugIn_edit';</script>";exit;
	}
	$data['description'] = $_POST['description'];
	if( $data['description'] == '' )
	{
		echo "<script>alert('请输入插件简介');location.href='index.php?act=PlugIn_edit';</script>";exit;
	}
	
	$arr = $_FILES['file'];
	if($arr['error'] == 4)
	{
		echo "<script>alert('请上传插件图片');location.href='index.php?act=PlugIn_edit';</script>";exit;
	}
	if( $arr['size'] > (1024*1024*2) )
	{
		echo "<script>alert('上传插件图片，大小范围超出2MB');location.href='index.php?act=PlugIn_edit';</script>";exit;
	}
	$extarr = explode('.', $arr['name']);
	$ext = end($extarr);
	if( !in_array($ext, array('jpeg','jpg','gif','png')) )
	{
		echo "<script>alert('上传插件图片，类型不允许');location.href='index.php?act=PlugIn_edit';</script>";exit;
	}
	
	$data['price'] = $_POST['price'];
	$data['publitime'] = time();
	
	//如果验证都通过，选创建插件目录
	#生成插件目录
	$mkdirApth = dir_url('subject/plugin/'.$themename);#主目录
	$dirNames = array('css','images','js','logo','upload');#子目录
	if( !is_dir($mkdirApth) )
	{
		foreach( $dirNames as $k=>$v )
		{
			if(!is_dir($mkdirApth.'/'.$v))
			{
				mkdir($mkdirApth.'/'.$v,0777,true);
			}
		}
		#映射文件
		$mappingApgh = dir_url('system/compile/mapping-main.txt');
		$str = file_get_contents($mappingApgh);	
		#生成入口文件	
		if(!file_exists($mkdirApth.'/'.$_POST['autoplug1']))
		{
		file_put_contents($mkdirApth.'/'.$_POST['autoplug1'], $str);
		}
		#生成xml文件
		if(!file_exists($mkdirApth.'/xml-rpc.xml'))
		{
		file_put_contents($mkdirApth.'/xml-rpc.xml', "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<box><tmpID>{$themename}</tmpID></box>");
		}
	}
	#样式目录路径
	$data['style'] = str_replace(DIR_URL.'/', '', $mkdirApth.'/css');
	$data['flag'] = 1;
	#生成后台插件,插件,后台管理 	
	$data['autoplug1'] = $autName;
	$data['xmlrpc'] = str_replace(DIR_URL.'/', '', $mkdirApth.'/xml-rpc.xml');
	#生成前台插件,插件,前台页面
	if( $_POST['autoplug2'] != '' )
	{
		if( strrpos($_POST['autoplug2'], '.') != false )
		{
			$autArr = explode('.', $_POST['autoplug2']);
			$autName = $autArr[0];
		}
		else 
		{
			$autName = $_POST['autoplug2'];
		}
		#映射文件
		$mappingApgh4 = dir_url('system/compile/include.txt');
		$str3 = file_get_contents($mappingApgh4);
		
		$userPath = dir_url('subject/plugin/'.$themename);
		if(!file_exists($userPath.'/'.$autName.'.php'))
		{
		file_put_contents($userPath.'/'.$autName.'.php', str_replace('%themename%', $themename, $str3));
		}
	}
	
	$data['autoplug2'] = $autName;
	
	#上传图片
	$uploadApth = $mkdirApth.'/logo/'.date('YmdHis').'.'.$ext;
	if( !move_uploaded_file($arr['tmp_name'], $uploadApth) )
	{
		echo "<script>alert('插件图片上传失败');location.href='index.php?act=PlugIn_edit';</script>";exit;
	}
	$data['themeimg'] = str_replace(DIR_URL.'/', '', $uploadApth);
		
	#添加记录数据,这是我有屎以来写得最长的一个函数
	$int = db()->update(PRE.'theme',$data,array('id'=>$_POST['id']));
	if($int)
	{
		session_start();
		$_SESSION['flagEorre'] = 1;
				
		header('location:index.php?act=PluginMng');
	}
	else 
	{
		echo "<script>alert('添加数据失败');location.href='index.php?act=PlugIn_edit';</script>";exit;	
	}
}
#处理主题事件,补充主题信息
function Theme_add()
{
	$themename = $_POST['themename'];
	$data['themeas'] = $_POST['themeas'];
	if( $data['themeas'] == '' )
	{
		echo "<script>alert('主题名称未命名');location.href='index.php?act=Theme_edit';</script>";exit;
	}
	$data['author'] = $_POST['author'];
	$data['homepage'] = $_POST['homepage'];
	if( $data['author'] == '' )
	{
		echo "<script>alert('请输入主题作者');location.href='index.php?act=Theme_edit';</script>";exit;
	}
	$data['description'] = $_POST['description'];
	if( $data['description'] == '' )
	{
		echo "<script>alert('请输入主题简介');location.href='index.php?act=Theme_edit';</script>";exit;
	}
	
	$arr = $_FILES['file'];
	if($arr['error'] == 4)
	{
		echo "<script>alert('请上传主题图片');location.href='index.php?act=Theme_edit';</script>";exit;
	}
	if( $arr['size'] > (1024*1024*2) )
	{
		echo "<script>alert('上传主题图片，大小范围超出2MB');location.href='index.php?act=Theme_edit';</script>";exit;
	}
	$extarr = explode('.', $arr['name']);
	$ext = end($extarr);
	if( !in_array($ext, array('jpeg','jpg','gif','png')) )
	{
		echo "<script>alert('上传主题图片，类型不允许');location.href='index.php?act=Theme_edit';</script>";exit;
	}
	
	$data['price'] = $_POST['price'];
	$data['publitime'] = time();
	//如果验证都通过，选创建主题目录
	#生成主题目录
	$mkdirApth = dir_url('subject/'.$themename);#主目录
	$dirNames = array('css','images','js','plugin','template','upload');#子目录
	if( !is_dir($mkdirApth) )
	{
		foreach( $dirNames as $k=>$v )
		{
			if(!is_dir($mkdirApth.'/'.$v))
			{
				mkdir($mkdirApth.'/'.$v,0777,true);
			}
		}
		#映射文件
		$mappingApgh = dir_url('system/compile/mapping.txt');
		$mappingCom = dir_url('system/compile/mapping-com.txt');
		$str = file_get_contents($mappingApgh);	
		$com = file_get_contents($mappingCom);
		#生成入口文件	
		if(!file_exists($mkdirApth.'/index.php'))
		{
		file_put_contents($mkdirApth.'/index.php', $str);
		}
		#生成公共文件	
		if(!file_exists($mkdirApth.'/common.php'))
		{
		file_put_contents($mkdirApth.'/common.php', $com);
		}
		$str = "<!DOCTYPE html>\n<html>\n<head>\n<meta charset=\"UTF-8\">\n<title>Insert title here</title>\n</head>\n<body>\n1v1\n</body>\n</html>";
		#生成首页index.html
		if(!file_exists($mkdirApth.'/template/index.html'))
		{
		file_put_contents($mkdirApth.'/template/index.html', str_replace('1v1', '首页 '.str_replace(DIR_URL, APTH_URL, $mkdirApth).'/template/index.html', $str));
		}
		#生成栏目页article_list.html
		if(!file_exists($mkdirApth.'/template/article_list.html'))
		{
		file_put_contents($mkdirApth.'/template/article_list.html', str_replace('1v1', '栏目页面 '.str_replace(DIR_URL, APTH_URL, $mkdirApth).'/template/article_list.html', $str));
		}
		#生成内容页article_content.html
		if(!file_exists($mkdirApth.'/template/article_content.html'))
		{
		file_put_contents($mkdirApth.'/template/article_content.html', str_replace('1v1', '内容页面 '.str_replace(DIR_URL, APTH_URL, $mkdirApth).'/template/article_content.html', $str));
		}
	}
	#样式目录路径
	$data['style'] = str_replace(DIR_URL.'/', '', $mkdirApth.'/css');
	#生成后台插件,插件,后台管理 
	if( $_POST['autoplug1'] != '' )
	{
		if( strrpos($_POST['autoplug1'], '.') != false )
		{
			$autArr = explode('.', $_POST['autoplug1']);
			$autName = $autArr[0];
		}
		else 
		{
			$autName = $_POST['autoplug1'];
		}
		$mkdirApth2 = $mkdirApth.'/plugin/'.$themename;#主目录
		$dirNames2 = array('css','images','js','logo','upload');#子目录
		if( !is_dir($mkdirApth2) )
		{
			foreach( $dirNames2 as $k=>$v )
			{
				if(!is_dir($mkdirApth2.'/'.$v))
				{
					mkdir($mkdirApth2.'/'.$v,0777,true);
				}
			}
			#映射文件
			$mappingApgh3 = dir_url('system/compile/mapping-main2.txt');
			$str2 = file_get_contents($mappingApgh3);	
			#生成入口文件	
			if(!file_exists($mkdirApth2.'/'.$_POST['autoplug1']))
			{
			file_put_contents($mkdirApth2.'/'.$_POST['autoplug1'], str_replace('%themename%', $themename, $str2));
			}
			#生成xml文件
			if(!file_exists($mkdirApth2.'/xml-rpc.xml'))
			{
			file_put_contents($mkdirApth2.'/xml-rpc.xml', "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<box><tmpID>{$themename}</tmpID></box>");
			}
		}
	}
	
	$data['autoplug1'] = $autName;
	
	$data['xmlrpc'] = str_replace(DIR_URL.'/','',$mkdirApth2.'/xml-rpc.xml');
	
	#生成前台插件,插件,前台页面
	if( $_POST['autoplug2'] != '' )
	{
		if( strrpos($_POST['autoplug2'], '.') != false )
		{
			$autArr = explode('.', $_POST['autoplug2']);
			$autName = $autArr[0];
		}
		else 
		{
			$autName = $_POST['autoplug2'];
		}
		#映射文件
		$mappingApgh4 = dir_url('system/compile/include2.txt');
		$str3 = file_get_contents($mappingApgh4);
		
		$userPath = $mkdirApth.'/plugin/'.$themename;
		if(!file_exists($userPath.'/'.$autName.'.php'))
		{
		file_put_contents($userPath.'/'.$autName.'.php', $str3);
		}
	}
	
	$data['autoplug2'] = $autName;
	
	#上传图片
	$uploadApth = $mkdirApth.'/upload/'.date('YmdHis').'.'.$ext;
	if( !move_uploaded_file($arr['tmp_name'], $uploadApth) )
	{
		echo "<script>alert('主题图片上传失败');location.href='index.php?act=Theme_edit';</script>";exit;
	}
	$data['themeimg'] = str_replace(DIR_URL.'/', '', $uploadApth);
		
	#添加记录数据,这是我有屎以来写得最长的一个函数
	$int = db()->update(PRE.'theme',$data, array('id'=>$_POST['id']));
	if($int)
	{
		session_start();
		$_SESSION['flagEorre'] = 1;
				
		header('location:index.php?act=ThemeMng');
	}
	else 
	{
		echo "<script>alert('添加数据失败');location.href='index.php?act=Theme_edit';</script>";exit;	
	}		
}
#处理主题事件,上传主题
function ThemeMng()
{
	$file = $_FILES['file'];
	
	if( $file['error'] == 4 )
	{
		if( pingmwh() )
		{
			echo "<script>alert('没有文件上传，请选择文件');location.href='index.php?act=ThemeMng';</script>";exit;
		}
		else
		{
			echo "<script>alert('没有文件上传，请选择文件');location.href='index.php?act=ThemeMng_phone';</script>";exit;
		}
	}
	$arr = explode('.', $file['name']);
	$ext = end($arr);
	if($ext != 'zip')
	{
		if( pingmwh() )
		{
			echo "<script>alert('只允许zip文件上传，请检查后重试');location.href='index.php?act=ThemeMng';</script>";exit;
		}
		else
		{
			echo "<script>alert('只允许zip文件上传，请检查后重试');location.href='index.php?act=ThemeMng_phone';</script>";exit;
		}
	}
	//要解压的路径
	$jieyaApth = dir_url('subject');
	//要上传的路径
	$loadApth = dir_url('system/compile/uploadzip/'.uniqid().'.'.$ext);
	
	if(move_uploaded_file($file['tmp_name'], $loadApth))
	{
		//上传成功,自动解压
		$dir = pclzip($loadApth, $jieyaApth);
		if($dir)
		{
			//解压成功,删除ZIP文件
			unlink($loadApth);
			#解析数据
			$path = dir_url('subject');
			$datainfo = $path.'/'.$dir.'/datainfo.txt';
			if( is_file($datainfo) )
			{
				$json = file_get_contents($datainfo);
				$datArr = json_decode($json,true);
				$art = $datArr['art'];//文档内容
				$th = $datArr['th'];//主题或插件
				$mo = $datArr['mo'];//模块
				$cu = $datArr['cu'];//栏目
				$cls = $datArr['cls'];//分类
				$tag = $datArr['tag'];//标签
			}	
			#记录信息
			$int = db()->select('*')->from(PRE.'theme')->where(array('themename'=>$dir))->get()->array_nums();
			if( $int == 0 )
			{
				$int = db()->insert(PRE.'theme',$th);
				$themeId = db()->getlast_id();
				if($int)
				{
					if(!empty($cu))
					{#栏目
						foreach($cu as $k=>$v)
						{
							$v['templateid'] = $themeId;
							db()->insert(PRE.'template',$v);
						}
					}					
					if(!empty($cls))
					{#分类
						foreach($cls as $k=>$v)
						{
							array_shift($v);
							$v['templateid'] = $themeId;
							db()->insert(PRE.'classified',$v);
							$clsId = db()->getlast_id();
						}
					}					
					if(!empty($tag))
					{#标签
						foreach($tag as $k=>$v)
						{
							$v['templateid'] = $themeId;
							db()->insert(PRE.'tag',$v);
						}
					}			
					if(!empty($mo))
					{#保存模块
						foreach($mo as $k=>$v)
						{
							$v['templateid'] = $themeId;
							db()->insert(PRE.'module',$v);
						}
					}				
					if(!empty($art))
					{#文档内容
						foreach($art as $k=>$v)
						{
							array_shift($v);
							$v['cipid'] = $clsId==''?0:$clsId;
							$v['author'] = $_SESSION['username'];
							$v['templateid'] = $themeId;
							db()->insert(PRE.'article',$v);
						}
					}		
					#补充主题信息
					session_start();
					$_SESSION['flagEorre'] = 1;
					#补充主题信息
					if( pingmwh() )
					{
						header('location:index.php?act=ThemeMng');
					}
					else
					{
						header('location:index.php?act=ThemeMng_phone');
					}
				}
				else
				{
					if( pingmwh() )
					{
						echo "<script>alert('添加信息添加失败');location.href='index.php?act=ThemeMng';</script>";exit;
					}
					else
					{
						echo "<script>alert('添加信息添加失败');location.href='index.php?act=ThemeMng_phone';</script>";exit;
					}
				}
			}
			else
			{
				if( pingmwh() )
				{
					echo "<script>alert('主题ID已经存在，请检测后重试');location.href='index.php?act=ThemeMng';</script>";exit;
				}
				else
				{
					echo "<script>alert('主题ID已经存在，请检测后重试');location.href='index.php?act=ThemeMng_phone';</script>";exit;
				}
			}
		}
		else 
		{
			if( pingmwh() )
			{
				echo "<script>alert('文件解压失败');location.href='index.php?act=ThemeMng';</script>";exit;
			}
			else
			{
				echo "<script>alert('文件解压失败');location.href='index.php?act=ThemeMng_phone';</script>";exit;
			}
		}
	}
	else 
	{
		if( pingmwh() )
		{
			echo "<script>alert('文件上传失败');location.href='index.php?act=ThemeMng';</script>";exit;
		}
		else
		{
			echo "<script>alert('文件上传失败');location.href='index.php?act=ThemeMng_phone';</script>";exit;
		}
	}
	
}
#处理页面事件,删除页面
function PageEdtDeletet()
{
	$id = trim(htmlspecialchars($_GET['id'],ENT_QUOTES));	
	$int = db()->delete(PRE.'template',array('id'=>$id));
	if($int)
	{
		session_start();
		$_SESSION['flagEorre'] = 1;
		if( pingmwh() )
		{		
			header('location:index.php?act=PageMng&page='.$_GET['page']);
		}
		else
		{
			header('location:index.php?act=PageMng_phone&page='.$_GET['page']);
		}
	}
	else
	{
		if( pingmwh() )
		{
			echo "<script>alert('删除页面失败');location.href='index.php?act=PageMng';</script>";exit;
		}
		else
		{
			echo "<script>alert('删除页面失败');location.href='index.php?act=PageMng_phone';</script>";exit;
		}
	}
}
#处理页面事件,修改页面
function PageEdtUp()
{
	$id = trim(htmlspecialchars($_POST['id'],ENT_QUOTES));
	
	$data['name'] = trim(htmlspecialchars($_POST['name'],ENT_QUOTES));
	if( $data['name'] == '' )
	{
		if( pingmwh() )
		{
			echo "<script>alert('栏目名称未命名');location.href='index.php?act=PageEdt';</script>";exit;
		}
		else
		{
			echo "<script>alert('栏目名称未命名');location.href='index.php?act=PageEdt_phone';</script>";exit;
		}
	}
	if( $_POST['module'] == '' )
	{
		if( pingmwh() )
		{
			echo "<script>alert('页面名称未命名');location.href='index.php?act=PageEdt';</script>";exit;
		}
		else
		{
			echo "<script>alert('页面名称未命名');location.href='index.php?act=PageEdt_phone';</script>";exit;
		}
	}
	else 
	{
		$module = trim(htmlspecialchars($_POST['module'],ENT_QUOTES));
		if( strrpos($module, '.') != false )
		{
			$modulearr = explode('.', $module);
			$module = $modulearr[0];
		}
	}
	$data['module'] = $module;
	if( $data['module'] == '' )
	{
		if( pingmwh() )
		{
			echo "<script>alert('页面名称未命名');location.href='index.php?act=PageEdt';</script>";exit;
		}
		else
		{
			echo "<script>alert('页面名称未命名');location.href='index.php?act=PageEdt_phone';</script>";exit;
		}
	}
	if( $_POST['pid'] != '-1' )
	{
		$data['pid'] = $_POST['pid'];
	}
	$data['templateid'] = $_POST['templateid'];
	if( $data['templateid'] == '-1' )
	{
		if( pingmwh() )
		{
			echo "<script>alert('未选择主题');location.href='index.php?act=PageEdt';</script>";exit;
		}
		else
		{
			echo "<script>alert('未选择主题');location.href='index.php?act=PageEdt_phone';</script>";exit;
		}
	}
	else 
	{	
		#查询主题目录	
		$row = db()->select('themename')->from(PRE.'theme')->where(array('id'=>$data['templateid']))->get()->array_row();
		#查询对应栏目
		$temp = db()->select('module')->from(PRE.'template')->where(array('id'=>$id))->get()->array_row();
		#旧文件
		$mkdirApth = dir_url('subject/'.$row['themename'].'/template/'.$temp['module'].'.html');
		if( is_file($mkdirApth) )
		{
			#读取旧内容
			$str = file_get_contents($mkdirApth);
			#删除旧文件
			unlink($mkdirApth);
			
			#新创文件
			$mkdirApth = dir_url('subject/'.$row['themename'].'/template/'.$data['module'].'.html');	
			#自动创建栏目页面
			file_put_contents($mkdirApth, $str);
		}
		else 
		{
			$str = "<!DOCTYPE html>\n<html>\n<head>\n<meta charset=\"UTF-8\">\n<title>Insert title here</title>\n</head>\n<body>\n1v1\n</body>\n</html>";
			#新创文件
			$mkdirApth = dir_url('subject/'.$row['themename'].'/template/'.$data['module'].'.html');	
			#自动创建栏目页面
			file_put_contents($mkdirApth, str_replace('1v1', '栏目页面 '.str_replace(DIR_URL, APTH_URL, $mkdirApth), $str));
		}
	}
	$data['keywords'] = trim(htmlspecialchars($_POST['keywords'],ENT_QUOTES));
	$data['description'] = $_POST['description'];
	$data['state'] = $_POST['state'];
	$data['userid'] = $_POST['userid'];
	$data['addmenu'] = $_POST['addmenu'];
	$data['forbidden'] = $_POST['forbidden'];
	$data['sort'] = $_POST['sort']==''?0:$_POST['sort'];
	
	#封面图片
	$coverfile = $_FILES['cover'];
	$scrpath = $_POST['srcpath'];#默认图片
	$path = '/ueditor/php/upload/image';#新图片
	if( $coverfile['error'] == 0 )
	{#有图片上传
		#先删除原有图片
		if(!strrpos($scrpath, 'a-ettra01.jpg'))
		{
			if(is_file(DIR_URL.$scrpath))
			{
				unlink(DIR_URL.$scrpath);
			}
		}
		
		$arrt = explode('.', $coverfile['name']);
		$ext = end($arrt);
		if(!in_array($ext, array('jpeg','jpg','gif','png')))
		{
			if( pingmwh() )
			{
				echo "<script>alert('封面图片类型不符');location.href='index.php?act=PageEdt';</script>";exit;
			}
			else
			{
				echo "<script>alert('封面图片类型不符');location.href='index.php?act=PageEdt_phone';</script>";exit;
			}
		}
		if( $coverfile['size'] > (1024*1024*2) )
		{
			if( pingmwh() )
			{
				echo "<script>alert('封面图片大小超出2M');location.href='index.php?act=PageEdt';</script>";exit;
			}
			else
			{
				echo "<script>alert('封面图片大小超出2M');location.href='index.php?act=PageEdt_phone';</script>";exit;
			}
		}
		$filedir = $path.'/'.date('Ymd');
		if( !is_dir(DIR_URL.$filedir) )
		{#目录不存就先创建目录
			mkdir(DIR_URL.$filedir,0777,true);
		}
		$filename = mt_rand(1000, 9999).mt_rand(1000, 9999).mt_rand(1000, 9999).mt_rand(1000, 9999);
		$destination = $filedir.'/'.$filename.'.'.$ext;
		if(move_uploaded_file($coverfile['tmp_name'], DIR_URL.$destination))
		{
			$data['cover'] = $destination;
		}
		else 
		{
			$data['cover'] = $scrpath;
		}
	}
	elseif( $coverfile['error'] == 4 ) 
	{#没有图片上传
		$data['cover'] = $scrpath;
	}
	
	$int = db()->update(PRE.'template', $data, array('id'=>$id));
	if($int)
	{
		session_start();
		$_SESSION['flagEorre'] = 1;
		if( pingmwh() )
		{
			header('location:index.php?act=PageMng&page='.$_POST['page']);
		}
		else
		{
			header('location:index.php?act=PageMng_phone&page='.$_POST['page']);
		}
	}
	else 
	{
		if( pingmwh() )
		{
			echo "<script>alert('修改页面失败');location.href='index.php?act=PageEdtUp';</script>";exit;
		}
		else
		{
			echo "<script>alert('修改页面失败');location.href='index.php?act=PageEdtUp_phone';</script>";exit;
		}
	}
}
#处理页面事件,新建页面
function PageEdt()
{
	$data['name'] = trim(htmlspecialchars($_POST['name'],ENT_QUOTES));
	if( $data['name'] == '' )
	{
		echo "<script>alert('栏目名称未命名');location.href='index.php?act=PageEdt';</script>";exit;
	}
	
	if( $_POST['module'] == '' )
	{
		echo "<script>alert('页面名称未命名');location.href='index.php?act=PageEdt';</script>";exit;
	}
	else 
	{
		$module = trim(htmlspecialchars($_POST['module'],ENT_QUOTES));
		if( strrpos($module, '.') != false )
		{
			$modulearr = explode('.', $module);
			$module = $modulearr[0];
		}
	}
	$data['module'] = $module;
	if( $_POST['pid'] != '-1' )
	{
		$data['pid'] = $_POST['pid'];
	}
	$data['templateid'] = $_POST['templateid'];
	if( $data['templateid'] == '-1' )
	{
		echo "<script>alert('未选择主题');location.href='index.php?act=PageEdt';</script>";exit;
	}
	else 
	{		
		$row = db()->select('themename')->from(PRE.'theme')->where(array('id'=>$data['templateid']))->get()->array_row();
		#主目录
		$mkdirApth = dir_url('subject/'.$row['themename'].'/template/'.$data['module'].'.html');
		if(!is_file($mkdirApth))
		{
			$str = "<!DOCTYPE html>\n<html>\n<head>\n<meta charset=\"UTF-8\">\n<title>Insert title here</title>\n</head>\n<body>\n1v1\n</body>\n</html>";
			#自动创建栏目页面
			file_put_contents($mkdirApth, str_replace('1v1', '栏目页面 '.str_replace(DIR_URL, APTH_URL, $mkdirApth), $str));
		}
	}
	$data['keywords'] = trim(htmlspecialchars($_POST['keywords'],ENT_QUOTES));
	$data['description'] = $_POST['description'];
	$data['state'] = $_POST['state'];
	$data['userid'] = $_POST['userid'];
	$data['addmenu'] = $_POST['addmenu'];
	$data['forbidden'] = $_POST['forbidden'];
	$data['sort'] = $_POST['sort']==''?0:$_POST['sort'];
	
	#封面图片
	$coverfile = $_FILES['cover'];
	$scrpath = 'system/admin/pic/defualt/a-ettra01.jpg';#默认图片
	$path = '/ueditor/php/upload/image';#新图片
	if( $coverfile['error'] == 0 )
	{#有图片上传
		$arrt = explode('.', $coverfile['name']);
		$ext = end($arrt);
		if(!in_array($ext, array('jpeg','jpg','gif','png')))
		{
			echo "<script>alert('封面图片类型不符');location.href='index.php?act=PageEdt';</script>";exit;
		}
		if( $coverfile['size'] > (1024*1024*2) )
		{
			echo "<script>alert('封面图片大小超出2M');location.href='index.php?act=PageEdt';</script>";exit;
		}
		$filedir = $path.'/'.date('Ymd');
		if( !is_dir(DIR_URL.$filedir) )
		{#目录不存就先创建目录
			mkdir(DIR_URL.$filedir,0777,true);
		}
		$filename = mt_rand(1000, 9999).mt_rand(1000, 9999).mt_rand(1000, 9999).mt_rand(1000, 9999);
		$destination = $filedir.'/'.$filename.'.'.$ext;
		if(move_uploaded_file($coverfile['tmp_name'], DIR_URL.$destination))
		{
			$data['cover'] = $destination;
		}
		else 
		{
			$data['cover'] = $scrpath;
		}
	}
	elseif( $coverfile['error'] == 4 ) 
	{#没有图片上传
		$data['cover'] = $scrpath;
	}
	
	$int = db()->insert(PRE.'template',$data);
	if($int)
	{
		session_start();
		$_SESSION['flagEorre'] = 1;
		
		#统计页面
		db()->update(PRE.'login', 'pagerows=pagerows+1',array('id'=>$data['userid']));
		
		header('location:index.php?act=PageMng');
	}
	else 
	{
		echo "<script>alert('添加页面失败');location.href='index.php?act=PageEdt';</script>";exit;
	}
}
#基础设置
function SettingMngBasic()
{
	if( $_POST['link'] != '' )
	{
		$data['link'] = $_POST['link'];
	}
	$data['title'] = $_POST['title'];
	$data['alias'] = $_POST['alias'];
	$data['addmenu'] = $_POST['addmenu'];
	$data['keywords'] = $_POST['keywords'];
	$data['description'] = $_POST['description'];
	$data['copyright'] = $_POST['copyright'];
	
	$int = db()->select('*')->from(PRE.'setting')->get()->array_nums();
	if( $int == 0 )
	{
		$int = db()->insert(PRE.'setting',$data);
		if($int)
		{
			session_start();
			$_SESSION['flagEorre'] = 1;
			if( pingmwh() )
			{
				header('location:index.php?act=SettingMng');
			}
			else
			{
				header('location:index.php?act=SettingMng_phone');
			}
		}
		else 
		{
			if( pingmwh() )
			{
				echo "<script>alert('修改失败');location.href='index.php?act=SettingMng';</script>";
			}
			else
			{
				echo "<script>alert('修改失败');location.href='index.php?act=SettingMng_phone';</script>";
			}
		}
	}
	else 
	{
		$int = db()->update(PRE.'setting', $data, array('id'=>1));
		if($int)
		{
			session_start();
			$_SESSION['flagEorre'] = 1;
			if( pingmwh() )
			{
				header('location:index.php?act=SettingMng');
			}
			else
			{
				header('location:index.php?act=SettingMng_phone');
			}
		}
		else 
		{
			if( pingmwh() )
			{
				echo "<script>alert('修改失败');location.href='index.php?act=SettingMng';</script>";
			}
			else
			{
				echo "<script>alert('修改失败');location.href='index.php?act=SettingMng_phone';</script>";
			}
		}
	}
}
#处理分类事件,删除
function CategoryDeletet()
{
	$id = trim(htmlspecialchars($_GET['id'],ENT_QUOTES));
	
	$int = db()->delete(PRE.'classified',array('id'=>$id));
	if($int)
	{
		session_start();
		$_SESSION['flagEorre'] = 1;
		if( pingmwh() )
		{
			header('location:index.php?act=CategoryMng');
		}
		else
		{
			header('location:index.php?act=CategoryMng_phone');
		}
	}
	else 
	{
		if( pingmwh() )
		{
			echo "<script>alert('删除失败');location.href='index.php?act=CategoryMng';</script>";
		}
		else
		{
			echo "<script>alert('删除失败');location.href='index.php?act=CategoryMng_phone';</script>";
		}
	}
}
#处理分类事件,修改
function CategoryUp()
{
	$id = trim(htmlspecialchars($_POST['id'],ENT_QUOTES));
	$data['classified'] = trim(htmlspecialchars($_POST['classified'],ENT_QUOTES));
	$data['clalias'] = trim(htmlspecialchars($_POST['clalias'],ENT_QUOTES));
	$data['sort'] = $_POST['sort'];
	$data['description'] = $_POST['description'];
	$data['addmenu'] = $_POST['addmenu'];
	$data['templateid'] = $_POST['templateid']==''?0:$_POST['templateid'];
	
	$int = db()->update(PRE.'classified', $data,array('id'=>$id));
	if($int)
	{
		session_start();
		$_SESSION['flagEorre'] = 1;
		if( pingmwh() )
		{
			header('location:index.php?act=CategoryMng');
		}
		else 
		{
			header('location:index.php?act=CategoryMng_phone');
		}
	}
	else 
	{
		if( pingmwh() )
		{
			echo "<script>alert('修改失败');location.href='index.php?act=CategoryUp&id=".$id."';</script>";
		}
		else 
		{
			echo "<script>alert('修改失败');location.href='index.php?act=CategoryUp_phone&id=".$id."';</script>";
		}
	}
	
}
#处理文章事件,删除
function ArticleDelete()
{
	$id = trim(htmlspecialchars($_GET['id'],ENT_QUOTES));
	
	$row = db()->select('imgurl,Thumurl')->from(PRE.'article')->where(array('id'=>$id))->get()->array_row();
	
	#删图
	deleteImg($row['imgurl']);
	deleteImg($row['Thumurl']);
	
	#内容
	$int = db()->delete(PRE.'article',array('id'=>$id));
	if($int)
	{
		session_start();
		$_SESSION['flagEorre'] = 1;
		if( pingmwh() )
		{
			header('location:index.php?act=ArticleMng&page='.$_GET['page']);
		}
		else
		{
			header('location:index.php?act=ArticleMng_phone&page='.$_GET['page']);
		}
	}
	else 
	{
		if( pingmwh() )
		{
			echo "<script>alert('删除失败');location.href='index.php?act=ArticleDelete';</script>";
		}
		else
		{
			echo "<script>alert('删除失败');location.href='index.php?act=ArticleDelete_phone';</script>";
		}
	}
}
function deleteImg($imgArr)
{#清除图片
	$arr = json_decode($imgArr);
	if(!empty($arr))
	{
		foreach( $arr as $k => $v )
		{
			$newurl = str_replace(APTH_URL, DIR_URL,$v);
			if(is_file($newurl))
			{
				$int = unlink($newurl);
			}
		}
	}
}
#处理文章事件,修改
function ArticleUp()
{
	$id = trim(htmlspecialchars($_POST['id'],ENT_QUOTES));	
	$row = db()->select('Thumurl')->from(PRE.'article')->where(array('id'=>$id))->get()->array_row();
	
	$data['title'] = trim($_POST['title']);
	if($data['title'] == '')
	{
		if( pingmwh() )
		{
			echo "<script>alert('标题未命名');location.href='index.php?act=ArticleUp';</script>";exit;
		}
		else 
		{
			echo "<script>alert('标题未命名');location.href='index.php?act=ArticleUp_phone';</script>";exit;
		}
	}
	$data['body'] = $_POST['body'];
	if($data['body'] == '' || $data['body'] == '<p><br/></p>')
	{
		if( pingmwh() )
		{
			echo "<script>alert('正文为空');location.href='index.php?act=ArticleUp';</script>";exit;
		}
		else 
		{
			echo "<script>alert('正文为空');location.href='index.php?act=ArticleUp_phone';</script>";
		}
	}
	#抓取图片	
	$imgsrc = getContImgs($data['body']);
	
	if( $imgsrc != '' )
	{
		$nImgsrc = dir_apth($imgsrc);
	}	
	$data['imgurl'] = json_encode($nImgsrc);//原图片路径
	$data['Thumurl'] = json_encode(thumImgs($imgsrc,json_decode($row['Thumurl'])));//缩略片路径
	
	$offon1 = $_POST['offon1'];
	if($offon1 == 'OFF')
	{
		$data['top'] = $_POST['top'];
	}
	else 
	{
		$data['top'] = 0;
	}
	
	$data['timerel'] = strtotime($_POST['timerel']);//定时发布时间
	
	$data['keywords'] = trim($_POST['keywords']==''?'':$_POST['keywords']);	
	$data['alias'] = trim($_POST['alias']);	
	$data['description'] = $_POST['description'];	
	$data['templateid'] = $_POST['templateid'];//模板ID
	$data['nocomment'] = $_POST['nocomment'];//禁止评论开关	
	$data['state'] = $_POST['state'];
	$data['cipid'] = $_POST['cipid'];//关联各种文章分类	
	$data['columnid'] = $_POST['columnid'];//对应栏目ID
	$data['author'] = $_POST['author'];	
	$data['posflag'] = $_POST['posflag'];
	$data['stage'] = $_POST['stage'];
	#商品信息
	$data['price'] = $_POST['price'];	
	$data['orprice'] = $_POST['orprice'];	
	$data['Sales'] = $_POST['Sales'];	
	$data['chain'] = $_POST['chain'];	
	$data['sizetype'] = $_POST['sizetype']==''?0:$_POST['sizetype'];	
	
	#封面图片
	$coverfile = $_FILES['cover'];
	$scrpath = $_POST['srcpath'];#默认图片
	$path = '/ueditor/php/upload/image';#新图片
	if( $coverfile['error'] == 0 )
	{#有图片上传
		#先删除原有图片
		if(!strrpos($scrpath, 'a-ettra01.jpg'))
		{
			if(is_file($_SERVER['DOCUMENT_ROOT'].$scrpath))
			{
				unlink($_SERVER['DOCUMENT_ROOT'].$scrpath);
			}
		}
		
		$arrt = explode('.', $coverfile['name']);
		$ext = end($arrt);
		if(!in_array($ext, array('jpeg','jpg','gif','png')))
		{
			if( pingmwh() )
			{
				echo "<script>alert('封面图片类型不符');location.href='index.php?act=PageEdt';</script>";exit;
			}
			else 
			{
				echo "<script>alert('封面图片类型不符');location.href='index.php?act=PageEdt_phone';</script>";exit;
			}
		}
		if( $coverfile['size'] > (1024*1024*2) )
		{
			if( pingmwh() )
			{
				echo "<script>alert('封面图片大小超出2M');location.href='index.php?act=PageEdt';</script>";exit;
			}
			else 
			{
				echo "<script>alert('封面图片大小超出2M');location.href='index.php?act=PageEdt_phone';</script>";exit;
			}
		}
		$filedir = $path.'/'.date('Ymd');
		if( !is_dir($_SERVER['DOCUMENT_ROOT'].$filedir) )
		{#目录不存就先创建目录
			mkdir($_SERVER['DOCUMENT_ROOT'].$filedir,0777,true);
		}
		$filename = mt_rand(1000, 9999).mt_rand(1000, 9999).mt_rand(1000, 9999).mt_rand(1000, 9999);
		$destination = $filedir.'/'.$filename.'.'.$ext;
		if(move_uploaded_file($coverfile['tmp_name'], $_SERVER['DOCUMENT_ROOT'].$destination))
		{
			$data['cover'] = $destination;
		}
		else 
		{
			$data['cover'] = $scrpath;
		}
	}
	elseif( $coverfile['error'] == 4 ) 
	{#没有图片上传
		$data['cover'] = $scrpath;
	}
	
	$int = db()->update(PRE.'article', $data,array('id'=>$id));
	if($int)
	{
		if($data['posflag'] == 1)
		{	
			#内容页面
			file_get_contents(apth_url('index.php?act=article_content&id='.$id));
		}	
		
		session_start();
		$_SESSION['flagEorre'] = 1;
		if( pingmwh() )
		{
			header('location:index.php?act=ArticleMng&page='.$_POST['page']);
		}
		else 
		{
			header('location:index.php?act=ArticleMng_phone&page='.$_POST['page']);
		}
	}
	else 
	{
		if( pingmwh() )
		{
			echo "<script>alert('修改失败');location.href='index.php?act=ArticleUp';</script>";
		}
		else 
		{
			echo "<script>alert('修改失败');location.href='index.php?act=ArticleUp_phone';</script>";
		}
	}
}
#处理会员事件3
function MemberEeeor()
{
	$id = trim(htmlspecialchars($_GET['id'],ENT_QUOTES));
	#先删除旧头像
	$picrow = db()->select('pic')->from(PRE.'login')->where(array('id'=>$id))->get()->array_row();
	if(!empty($picrow))
	{	
		$filename = base_url($picrow['pic']);
		if(is_file($filename))
		{
			unlink($filename);
		}
	}
	
	$int = db()->delete(PRE.'login',array('id'=>$id));
	if($int)
	{
		session_start();
		$_SESSION['flagEorre'] = 1;
		if( pingmwh() )
		{
			header('location:index.php?act=MemberMng&page='.$_GET['page']);
		}
		else 
		{
			header('location:index.php?act=MemberMng_phone&page='.$_GET['page']);
		}
	}
	else 
	{
		if( pingmwh() )
		{
			echo "<script>alert('删除用户失败');location.href='index.php?act=MemberMng';</script>";exit;
		}
		else 
		{
			echo "<script>alert('删除用户失败');location.href='index.php?act=MemberMng_phone';</script>";exit;
		}
	}
}
#处理会员事件2
function MemberEdt()
{
	$id = trim(htmlspecialchars($_POST['id'],ENT_QUOTES));
	
	$pwd = trim(htmlspecialchars($_POST['pwd'],ENT_QUOTES));
	if( $pwd == '' )
	{
		if( pingmwh() )
		{
			echo "<script>alert('请输入密码');location.href='index.php?act=MemberEdt&id=".$id."';</script>";exit;
		}
		else 
		{
			echo "<script>alert('请输入密码');location.href='index.php?act=MemberEdt_phone&id=".$id."';</script>";exit;
		}
	}
	$pwd2 = trim(htmlspecialchars($_POST['pwd2'],ENT_QUOTES));
	if( $pwd2 == '' )
	{
		if( pingmwh() )
		{
			echo "<script>alert('请输入确认密码');location.href='index.php?act=MemberEdt&id=".$id."';</script>";exit;
		}
		else 
		{
			echo "<script>alert('请输入确认密码');location.href='index.php?act=MemberEdt_phone&id=".$id."';</script>";exit;
		}
	}
	if( $pwd != $pwd2 )
	{
		if( pingmwh() )
		{
			echo "<script>alert('两次密码不一致');location.href='index.php?act=MemberEdt&id=".$id."';</script>";exit;
		}
		else 
		{
			echo "<script>alert('两次密码不一致');location.href='index.php?act=MemberEdt_phone&id=".$id."';</script>";exit;
		}
	}
	$password = mb_substr($pwd, 0,32,'utf-8');		
	$data['pwd'] = substr(md5(base64_decode($password)),10,10);
		
	$data['userName'] = trim(htmlspecialchars($_POST['userName'],ENT_QUOTES));
	if( $data['userName'] == '' )
	{
		if( pingmwh() )
		{
			echo "<script>alert('用户名称未命名');location.href='index.php?act=MemberEdt&id=".$id."';</script>";exit;
		}
		else 
		{
			echo "<script>alert('用户名称未命名');location.href='index.php?act=MemberEdt_phone&id=".$id."';</script>";exit;
		}
	}
	else
	{
		$int = db()->select('userName')->from(PRE.'login')->where(array('userName'=>$data['userName']))->get()->array_nums();
		if( $int == 0 )
		{
			if( pingmwh() )
			{
				echo "<script>alert('用户名称不存在');location.href='index.php?act=MemberEdt&id=".$id."';</script>";exit;
			}
			else 
			{
				echo "<script>alert('用户名称不存在');location.href='index.php?act=MemberEdt_phone&id=".$id."';</script>";exit;
			}
		}
	}
	$data['alias'] = trim(htmlspecialchars($_POST['alias'],ENT_QUOTES));
	$data['email'] = $_POST['email'];
	if($data['email'] == '')
	{
		if( pingmwh() )
		{
			echo "<script>alert('邮箱不能留空');location.href='index.php?act=MemberEdt&id=".$id."';</script>";exit;
		}
		else 
		{
			echo "<script>alert('邮箱不能留空');location.href='index.php?act=MemberEdt_phone&id=".$id."';</script>";exit;
		}
	}
	if(!preg_match('/^[0-9a-zA-Z_-]+@[0-9a-zA-Z]+\.[a-zA-Z]+$/', $data['email']))
	{
		if( pingmwh() )
		{
			echo "<script>alert('邮箱格式不正确');location.href='index.php?act=MemberEdt&id=".$id."';</script>";exit;
		}
		else 
		{
			echo "<script>alert('邮箱格式不正确');location.href='index.php?act=MemberEdt_phone&id=".$id."';</script>";exit;
		}
	}
	$data['homepage'] = $_POST['homepage'];
	$data['abst'] = $_POST['abst'];
	$data['Template'] = $_POST['Template'];
	$data['state'] = $_POST['state'];
	$data['level'] = $_POST['level'];
	
	#查获旧头像
	$picrow = db()->select('pic')->from(PRE.'login')->where(array('id'=>$id))->get()->array_row();	
	#上传头像
	$filepath = base_url('pic/');
	#默认头像
	$srcpath = $picrow['pic'];
	
	#实例化一个上传对像
	$up = new Uplod_class($_FILES['pic'],$filepath,$srcpath);	
	$up -> setEorre(array(
		"<script>alert('文件类型不允许');location.href='index.php?act=MemberEdt&id=".$id."';</script>",
		"<script>alert('文件大小超出限制');location.href='index.php?act=MemberEdt&id=".$id."';</script>"
	));	
	$data['pic'] = $up -> uplod();
	if( $up->flag == true )
	{#如果有新图片上传，删除旧图片
		if( $srcpath != '' )
		{
			$picPath = base_url($srcpath);
			if(is_file($picPath))
			{
				unlink($picPath);
			}
			else 
			{
				//echo "<script>alert('旧头像删除失败');location.href='index.php?act=MemberEdt&id=".$id."';</script>";exit;
			}
		}
	}
	
	$int = db()->update(PRE.'login', $data, array('id'=>$id));
	if($int)
	{
		session_start();
		$_SESSION['flagEorre'] = 1;
		if( pingmwh() )
		{
			header('location:index.php?act=MemberMng');
		}
		else 
		{
			header('location:index.php?act=MemberMng_phone');
		}
	}
	else 
	{
		if( pingmwh() )
		{
			echo "<script>alert('修改用户失败');location.href='index.php?act=MemberEdt&id=".$id."';</script>";exit;
		}
		else 
		{
			echo "<script>alert('修改用户失败');location.href='index.php?act=MemberEdt_phone&id=".$id."';</script>";exit;
		}
	}
	
}
#处理会员事件1
function MemberNew()
{
	$data['userName'] = trim(htmlspecialchars($_POST['userName'],ENT_QUOTES));
	if($data['userName'] == '')
	{
		if( pingmwh() )
		{
			echo "<script>alert('用户名称未命名');location.href='index.php?act=MemberNew';</script>";exit;
		}
		else
		{
			echo "<script>alert('用户名称未命名');location.href='index.php?act=MemberNew_phone';</script>";exit;
		}
	}
	else
	{
		$int = db()->select('userName')->from(PRE.'login')->where(array('userName'=>$data['userName']))->get()->array_nums();
		if( $int == 1 )
		{
			if( pingmwh() )
			{
				echo "<script>alert('用户名称已存在');location.href='index.php?act=MemberNew';</script>";exit;
			}
			else
			{
				echo "<script>alert('用户名称已存在');location.href='index.php?act=MemberNew_phone';</script>";exit;
			}
		}
	}	
	$pwd = trim(htmlspecialchars($_POST['pwd'],ENT_QUOTES));
	if( $pwd == '' )
	{
		if( pingmwh() )
		{
			echo "<script>alert('请输入密码');location.href='index.php?act=MemberNew';</script>";exit;
		}
		else
		{
			echo "<script>alert('请输入密码');location.href='index.php?act=MemberNew_phone';</script>";exit;
		}
	}
	$pwd2 = trim(htmlspecialchars($_POST['pwd2'],ENT_QUOTES));
	if( $pwd2 == '' )
	{
		if( pingmwh() )
		{
			echo "<script>alert('请输入确认密码');location.href='index.php?act=MemberNew';</script>";exit;
		}
		else
		{
			echo "<script>alert('请输入确认密码');location.href='index.php?act=MemberNew_phone';</script>";exit;
		}
	}
	if($pwd != $pwd2)
	{
		if( pingmwh() )
		{
			echo "<script>alert('两次密码不一致');location.href='index.php?act=MemberNew';</script>";exit;
		}
		else
		{
			echo "<script>alert('两次密码不一致');location.href='index.php?act=MemberNew_phone';</script>";exit;
		}
	}
	$password = mb_substr($pwd, 0,32,'utf-8');		
	$data['pwd'] = substr(md5(base64_decode($password)),10,10);
	
	$data['email'] = trim(htmlspecialchars($_POST['email'],ENT_QUOTES));
	if($data['email'] == '')
	{
		if( pingmwh() )
		{
			echo "<script>alert('邮箱不能留空');location.href='index.php?act=MemberNew';</script>";exit;
		}
		else
		{
			echo "<script>alert('邮箱不能留空');location.href='index.php?act=MemberNew_phone';</script>";exit;
		}
	}
	if(!preg_match('/^[0-9a-zA-Z_-]+@[0-9a-zA-Z]+\.[a-zA-Z]+$/', $data['email']))
	{
		if( pingmwh() )
		{
			echo "<script>alert('邮箱格式不正确');location.href='index.php?act=MemberNew';</script>";exit;
		}
		else
		{
			echo "<script>alert('邮箱格式不正确');location.href='index.php?act=MemberNew_phone';</script>";exit;
		}
	}
	$data['level'] = $_POST['level'];
	$data['state'] = $_POST['state'];		
	$data['alias'] = trim(htmlspecialchars($_POST['alias'],ENT_QUOTES));	
	$data['homepage'] = trim($_POST['homepage']);
	$data['abst'] = $_POST['abst'];
	$data['Template'] = $_POST['Template'];
	$data['flag'] = $_POST['flag']==''?0:$_POST['flag'];
	$data['tel'] = trim($_POST['tel']==''?'':$_POST['tel']);//手机号码
	$data['pid'] = $_POST['pid']==''?0:$_POST['pid'];//二级用户
	$data['loginIP'] = getIP();//登录IP
	$data['loginTime'] = time();//登录时间
		
	#上传头像
	$filepath = base_url('pic/');
	#默认头像
	$srcpath = 'pic/defualt/0.png';
	#实例化一个上传对像
	$up = new Uplod_class($_FILES['pic'],$filepath,$srcpath);
	if( $data['flag'] == '' )
	{
		$up -> setEorre(array(
			"<script>alert('文件类型不允许');location.href='index.php?act=MemberNew';</script>",
			"<script>alert('文件大小超出限制');location.href='index.php?act=MemberNew';</script>"
		));
	}
	elseif( $data['flag'] == 1 ) 
	{
		$up -> setEorre(array(
			"<script>alert('文件类型不允许');location.href='index.php?act=ApplicationCenterReset';</script>",
			"<script>alert('文件大小超出限制');location.href='index.php?act=ApplicationCenterReset';</script>"
		));
	}
	
	$data['pic'] = $up -> uplod();
	//print_r($data);exit;	
	$path = 'system/remotelink.php';
	$filename = SERVICE_LINK.$path;
	foreach($data as $k=>$v)
	{
		$post .= ($post==''?'':'&').$k.'='.$v;
	}
	$json = vcurl($filename,'act=InDataReset&'.$post);
	$int = json_decode($json,true);	

	if( $int[0] > 0 )
	{
		session_start();
		$_SESSION['flagEorre'] = 1;
		if( $data['flag'] == '' )
		{
			if( pingmwh() )
			{
				header('location:index.php?act=MemberMng');
			}
			else
			{
				header('location:index.php?act=MemberMng_phone');
			}
		}
		elseif( $data['flag'] == 1 ) 
		{
			if( pingmwh() )
			{
				header('location:index.php?act=ApplicationCenterUser');
			}
			else
			{
				header('location:index.php?act=ApplicationCenterUser_phone');
			}
		}
	}
	else 
	{
		if( $data['flag'] == '' )
		{
			if( pingmwh() )
			{
				echo "<script>alert('新建用户失败');location.href='index.php?act=MemberNew';</script>";exit;
			}
			else
			{
				echo "<script>alert('新建用户失败');location.href='index.php?act=MemberNew_phone';</script>";exit;
			}
		}
		elseif( $data['flag'] == 1 ) 
		{
			if( pingmwh() )
			{
				echo "<script>alert('新建用户失败');location.href='index.php?act=ApplicationCenterUser';</script>";exit;
			}
			else
			{
				echo "<script>alert('新建用户失败');location.href='index.php?act=ApplicationCenterUser_phone';</script>";exit;
			}
		}
	}
}
#处理会员事件2
function MemberNew2()
{
	$data['userName'] = trim(htmlspecialchars($_POST['userName'],ENT_QUOTES));
	if($data['userName'] == '')
	{
		if( pingmwh() )
		{
			echo "<script>alert('用户名称未命名');location.href='index.php?act=MemberNew';</script>";exit;
		}
		else
		{
			echo "<script>alert('用户名称未命名');location.href='index.php?act=MemberNew_phone';</script>";exit;
		}
	}
	else
	{
		$int = db()->select('userName')->from(PRE.'login')->where(array('userName'=>$data['userName']))->get()->array_nums();
		if( $int == 1 )
		{
			if( pingmwh() )
			{
				echo "<script>alert('用户名称已存在');location.href='index.php?act=MemberNew';</script>";exit;
			}
			else
			{
				echo "<script>alert('用户名称已存在');location.href='index.php?act=MemberNew_phone';</script>";exit;
			}
		}
	}	
	$pwd = trim(htmlspecialchars($_POST['pwd'],ENT_QUOTES));
	if( $pwd == '' )
	{
		if( pingmwh() )
		{
			echo "<script>alert('请输入密码');location.href='index.php?act=MemberNew';</script>";exit;
		}
		else
		{
			echo "<script>alert('请输入密码');location.href='index.php?act=MemberNew_phone';</script>";exit;
		}
	}
	$pwd2 = trim(htmlspecialchars($_POST['pwd2'],ENT_QUOTES));
	if( $pwd2 == '' )
	{
		if( pingmwh() )
		{
			echo "<script>alert('请输入确认密码');location.href='index.php?act=MemberNew';</script>";exit;
		}
		else
		{
			echo "<script>alert('请输入确认密码');location.href='index.php?act=MemberNew_phone';</script>";exit;
		}
	}
	if($pwd != $pwd2)
	{
		if( pingmwh() )
		{
			echo "<script>alert('两次密码不一致');location.href='index.php?act=MemberNew';</script>";exit;
		}
		else
		{
			echo "<script>alert('两次密码不一致');location.href='index.php?act=MemberNew_phone';</script>";exit;
		}
	}
	$password = mb_substr($pwd, 0,32,'utf-8');		
	$data['pwd'] = substr(md5(base64_decode($password)),10,10);
	
	$data['email'] = trim(htmlspecialchars($_POST['email'],ENT_QUOTES));
	if($data['email'] == '')
	{
		if( pingmwh() )
		{
			echo "<script>alert('邮箱不能留空');location.href='index.php?act=MemberNew';</script>";exit;
		}
		else
		{
			echo "<script>alert('邮箱不能留空');location.href='index.php?act=MemberNew_phone';</script>";exit;
		}
	}
	if(!preg_match('/^[0-9a-zA-Z_-]+@[0-9a-zA-Z]+\.[a-zA-Z]+$/', $data['email']))
	{
		if( pingmwh() )
		{
			echo "<script>alert('邮箱格式不正确');location.href='index.php?act=MemberNew';</script>";exit;
		}
		else
		{
			echo "<script>alert('邮箱格式不正确');location.href='index.php?act=MemberNew_phone';</script>";exit;
		}
	}
	$data['level'] = $_POST['level'];
	$data['state'] = $_POST['state'];		
	$data['alias'] = trim(htmlspecialchars($_POST['alias'],ENT_QUOTES));	
	$data['homepage'] = trim($_POST['homepage']);
	$data['abst'] = $_POST['abst'];
	$data['Template'] = $_POST['Template'];
	$data['flag'] = $_POST['flag']==''?0:$_POST['flag'];
	$data['tel'] = trim($_POST['tel']==''?'':$_POST['tel']);//手机号码
	$data['pid'] = $_POST['pid']==''?0:$_POST['pid'];//二级用户
	$data['loginIP'] = getIP();//登录IP
	$data['loginTime'] = time();//登录时间
	//print_r($data);exit;	
	#上传头像
	$filepath = base_url('pic/');
	#默认头像
	$srcpath = 'header/0.png';
	#实例化一个上传对像
	$up = new Uplod_class($_FILES['pic'],$filepath,$srcpath);
	if( $data['flag'] == '' )
	{
		$up -> setEorre(array(
			"<script>alert('文件类型不允许');location.href='index.php?act=MemberNew';</script>",
			"<script>alert('文件大小超出限制');location.href='index.php?act=MemberNew';</script>"
		));
	}
	elseif( $data['flag'] == 1 ) 
	{
		$up -> setEorre(array(
			"<script>alert('文件类型不允许');location.href='index.php?act=ApplicationCenterReset';</script>",
			"<script>alert('文件大小超出限制');location.href='index.php?act=ApplicationCenterReset';</script>"
		));
	}
	
	$data['pic'] = $up -> uplod();
		
	$int = db()->insert(PRE.'login',$data);

	if( $int )
	{
		session_start();
		$_SESSION['flagEorre'] = 1;
		if( pingmwh() )
		{
			header('location:index.php?act=MemberMng');
		}
		else
		{
			header('location:index.php?act=MemberMng_phone');
		}
	}
	else 
	{
		if( $data['flag'] == '' )
		{
			if( pingmwh() )
			{
				echo "<script>alert('新建用户失败');location.href='index.php?act=MemberNew';</script>";exit;
			}
			else
			{
				echo "<script>alert('新建用户失败');location.href='index.php?act=MemberNew_phone';</script>";exit;
			}
		}
		elseif( $data['flag'] == 1 ) 
		{
			if( pingmwh() )
			{
				echo "<script>alert('新建用户失败');location.href='index.php?act=ApplicationCenterUser';</script>";exit;
			}
			else
			{
				echo "<script>alert('新建用户失败');location.href='index.php?act=ApplicationCenterUser_phone';</script>";exit;
			}
		}
	}
}
#处理游客登录事件
function ApplicationCenterUser()
{
	session_start();
	
	if( $_POST['userName'] == '' )
	{
		echo "<script>alert('用户名不能留空');location.href='index.php?act=ApplicationCenterUser';</script>";exit;
	}
	if( $_POST['pwd'] == '' )
	{
		echo "<script>alert('密码不能留空');location.href='index.php?act=ApplicationCenterUser';</script>";exit;
	}
	//安全处理
	$username = mysql_escape_string(mb_substr($_POST['userName'], 0,32,'utf-8'));
	$password = mysql_escape_string(mb_substr($_POST['pwd'], 0,32,'utf-8'));		
	$password = substr(md5(base64_decode($password)),10,10);
	
	//保持登录
	setcookie(username2,$username,time()+60*60*24*365,"/");
	setcookie(password2,$password,time()+60*60*24*365,"/");
	
	//验证用户名、密码
	$path = 'system/remotelink.php';
	$filename = SERVICE_LINK.$path;
	$json = vcurl($filename,'act=InDataLogin&username='.$username.'&password='.$password);
	$item = json_decode($json,true);	
		
	if($item[0]==1){
		$_SESSION['ApplicationCenterUser'] = $username;
		
		header('location:index.php?act=ApplicationCenter');	
	}else{
		echo "<script>alert('用户名、密码登录失败');location.href='index.php?act=ApplicationCenterUser';</script>";exit;
	}	
}
#处理清空缓存并重新编译模板事件
function ClearCompile()
{
	ini_set("max_execution_time", "180");
	ob_start();
	#清空缓存
	ob_clean();
	#重新编译模板
	include Pagecall('static');
	
	if($data['filter'] == 'ON')
	{
		if($data['index'] == 1)
		{
			#更新首页
			file_get_contents(apth_url('index.php'));
		}	
		/*
		if($data['fy'] == 1)
		{	
			#列表分页
			$setreview = db()->select('listtotal,searchmaxtotal')->from(PRE.'review_up')->get()->array_row();		
			$rowsTotal = db()->select('id')->from(PRE.'article')->where('state=0 and (top=0 or top=102 or top=103)')->get()->array_nums();		
			$showTotal = $setreview['listtotal']==''?10:$setreview['listtotal'];		
			$pageTotal = ceil($rowsTotal/$showTotal);
			
			if( $pageTotal !=0 )
			{
				for($i=1;$i<=$pageTotal;$i++)
				{
					file_get_contents(apth_url('index.php?act=article_list&page='.$i));
				}
			}
		}	
		*/
		if($data['lanmu'] == 1)
		{
			#更新栏目
			$columnList = GetFenLaiList(0);
			
			if(!empty($columnList))
			{
				foreach($columnList as $k=>$v)
				{
					file_get_contents(apth_url('index.php?act=article_list&id='.$v['id']));
				}
			}
			#更新内容页
				$rows = db()->select('a.id,a.title,a.poslink')->from(PRE.'article as a')->get()->array_rows();
			
				if(!empty($rows))
				{
					foreach( $rows as $k => $v )
					{
						file_get_contents(apth_url('index.php?act=article_content&id='.$v['id']));
					}
				}
		}		
				session_start();
				$_SESSION['flagEorre'] = 1;
				if( pingmwh() )
				{
					header('location:index.php?act=admin');
				}
				else
				{
					header('location:index.php?act=admin_phone');
				}
	}
	else 
	{
			session_start();
			$_SESSION['flagEorre'] = 1;
			if( pingmwh() )
			{	
				header('location:index.php?act=admin');
			}
			else
			{
				header('location:index.php?act=admin_phone');
			}
	}
}
#获取屏幕宽度
function pingmwh()
{
	$data = file_get_contents('../pingmupixels.xml');
	$pixels = simplexml_load_string($data);
	$w = (int)$pixels->pixels;
	if( $w > 1250){
		return true;#PC屏
	}else{
		return false;#移动屏
	}
}
#处理游客退出事件
function ApplicationCenterOut()
{
	session_start();
	
	setcookie(username2,null,time()-1,"/");
	setcookie(password2,null,time()-1,"/");
	
	$_SESSION['ApplicationCenterUser'] = null;
	session_unset($_SESSION['ApplicationCenterUser']);
	
	header('location:index.php?act=ApplicationCenter');	
}
#处理文章事件
function ArticleEdt()
{
	$data['title'] = trim($_POST['title']);
	if($data['title'] == '')
	{
		if( pingmwh() )
		{
			echo "<script>alert('标题未命名');location.href='index.php?act=ArticleEdt';</script>";exit;
		}
		else
		{
			echo "<script>alert('标题未命名');location.href='index.php?act=ArticleEdt_phone';</script>";exit;
		}
	}
	$data['body'] = $_POST['body'];
	if($data['body'] == '' || $data['body'] == '<p><br/></p>')
	{
		if( pingmwh() )
		{
			echo "<script>alert('正文为空');location.href='index.php?act=ArticleEdt';</script>";exit;
		}
		else
		{
			echo "<script>alert('正文为空');location.href='index.php?act=ArticleEdt_phone';</script>";exit;
		}
	}
	$data['cipid'] = $_POST['cipid'];//关联各种文章分类
	if( $data['cipid'] == -1 )
	{
		if( pingmwh() )
		{
			echo "<script>alert('请选择分类');location.href='index.php?act=ArticleEdt';</script>";exit;
		}
		else
		{
			echo "<script>alert('请选择分类');location.href='index.php?act=ArticleEdt_phone';</script>";exit;
		}
	}
	#抓取内容图片
	$imgsrc = getContImgs($data['body']);	
	if( $imgsrc != '' )
	{
		$nImgsrc = dir_apth($imgsrc);
	}	
	$data['imgurl'] = json_encode($nImgsrc);//原图片路径
	$data['Thumurl'] = json_encode(thumImgs($imgsrc));//缩略片路径
	
	$offon1 = $_POST['offon1'];
	if($offon1 == 'OFF')
	{
		$data['top'] = $_POST['top'];
	}	
	$data['publitime'] = time();//当前发布时间
	$data['timerel'] = strtotime($_POST['timerel']);//定时发布时间
	
	$data['poslink'] = date('Ymdhis');//静态文档名称
	
	$data['keywords'] = trim($_POST['keywords']==''?'':$_POST['keywords']);

	$data['alias'] = trim($_POST['alias']);	
	$data['description'] = $_POST['description'];	
	$data['templateid'] = $_POST['templateid'];//模板ID
	$data['nocomment'] = $_POST['nocomment'];//禁止评论开关	
	$data['state'] = $_POST['state'];	
	$data['columnid'] = $_POST['columnid'];//对应栏目
	$data['author'] = $_POST['author'];	
	$data['posflag'] = $_POST['posflag'];	
	$data['stage'] = $_POST['stage'];	
	
	#商品信息
	$data['price'] = $_POST['price'];	
	$data['orprice'] = $_POST['orprice'];	
	$data['Sales'] = $_POST['Sales'];	
	$data['chain'] = $_POST['chain'];	
	$data['sizetype'] = $_POST['sizetype']==''?0:$_POST['sizetype'];	
	
	#封面图片
	$coverfile = $_FILES['cover'];
	$scrpath = 'system/admin/pic/defualt/a-ettra01.jpg';#默认图片
	$path = '/ueditor/php/upload/image';#新图片
	if( $coverfile['error'] == 0 )
	{#有图片上传
		$arrt = explode('.', $coverfile['name']);
		$ext = end($arrt);
		if(!in_array($ext, array('jpeg','jpg','gif','png')))
		{
			if( pingmwh() )
			{
				echo "<script>alert('封面图片类型不符');location.href='index.php?act=PageEdt';</script>";exit;
			}
			else
			{
				echo "<script>alert('封面图片类型不符');location.href='index.php?act=PageEdt_phone';</script>";exit;
			}
		}
		if( $coverfile['size'] > (1024*1024*2) )
		{
			if( pingmwh() )
			{
				echo "<script>alert('封面图片大小超出2M');location.href='index.php?act=PageEdt';</script>";exit;
			}
			else
			{
				echo "<script>alert('封面图片大小超出2M');location.href='index.php?act=PageEdt_phone';</script>";exit;
			}
		}
		$filedir = $path.'/'.date('Ymd');
		if( !is_dir( $_SERVER['DOCUMENT_ROOT'].$filedir) )
		{#目录不存就先创建目录
			mkdir($_SERVER['DOCUMENT_ROOT'].$filedir,0777,true);
		}
		$filename = mt_rand(1000, 9999).mt_rand(1000, 9999).mt_rand(1000, 9999).mt_rand(1000, 9999);
		$destination = $filedir.'/'.$filename.'.'.$ext;
		if(move_uploaded_file($coverfile['tmp_name'], $_SERVER['DOCUMENT_ROOT'].$destination))
		{
			$data['cover'] = $destination;
		}
		else 
		{
			$data['cover'] = $scrpath;
		}
	}
	elseif( $coverfile['error'] == 4 ) 
	{#没有图片上传
		$data['cover'] = $scrpath;
	}
	
	$bool = db()->insert(PRE.'article',$data);
	$id = db()->getlast_id();
	if($bool)
	{
		#统计标签项总数		
		countkeywords($data['keywords']);	
		if($data['posflag'] == 1)
		{	
			#内容页面
			file_get_contents(apth_url('index.php?act=article_content&id='.$id));
		}		
		session_start();
		$_SESSION['flagEorre'] = 1;
		if( pingmwh() )
		{		
			header('location:index.php?act=ArticleMng');
		}
		else
		{
			header('location:index.php?act=ArticleMng_phone');
		}
	}
	else 
	{
		if( pingmwh() )
		{
			echo "<script>alert('发布失败');location.href='index.php?act=ArticleEdt';</script>";
		}
		else
		{
			echo "<script>alert('发布失败');location.href='index.php?act=ArticleEdt_phone';</script>";
		}
	}
}
function setting_staitc()
{#重置静态页面
		ini_set("max_execution_time", "180");
		include Pagecall('static');
		if($data['filter'] == 'ON')
		{
			if( $data['art'] == 1 )
			{	
				#首页面
				file_get_contents(apth_url('index.php'));			
				#列表页面		
				file_get_contents(apth_url('index.php?act=article_list'));
				#栏目页面
				$columnList = GetFenLaiList_static(0);
				if(!empty($columnList))
				{
					foreach($columnList as $k=>$v)
					{
						file_get_contents(apth_url('index.php?act=article_list&id='.$v['id']));
					}
				}	
			}
		}
		/*
		if($data['filter'] == 'ON')
		{
			if($data['fy'] == 1)
			{#静态,分页
				#网站设置
				$setreview = db()->select('listtotal')->from(PRE.'review_up')->get()->array_row();
				$rowsTotal = db()->select('id')->from(PRE.'article')->where('state=0 and (top=0 or top=102 or top=103)')->get()->array_nums();
				$showTotal = $setreview['listtotal']==''?10:$setreview['listtotal'];
				$pageTotal = ceil($rowsTotal/$showTotal);
				
				if( $pageTotal !=0 )
				{
					for($i=1;$i<=$pageTotal;$i++)
					{
						file_get_contents(apth_url('index.php?act=article_list&page='.$i));
					}
				}
				
			}
		}*/
}
function imgurlcode($str)
{#url转换
	if( $str != '' )
	{
		$arr = json_decode($str);
		foreach( $arr as $k => $v )
		{
			$url = str_replace(APTH_URL, DIR_URL, $v);
			if(is_file($url))
			{
				unlink($url);
			}
		}
	}
}
function dir_apth($arr)
{#url转换
	$rows = array();
	foreach( $arr as $k => $v )
	{
		$rows[] = str_replace(DIR_URL, '', $v);
	}
	return $rows;
}
function countkeywords($string)
{#统计标签
	if( $string != '' )
	{ 
		if( strpos($string, ',') != false || strpos($string, '，') != false)
		{
			$str = str_replace(array(' ','，'), array(',',','), $string);
			$arrAll = explode(',', $str);	
			foreach($arrAll as $k=>$v)
			{	
				$int = db()->select('*')->from(PRE.'tag')->where('keywords="'.$v.'"')->get()->array_nums();
				if( $int == 0 )
				{
					db()->insert(PRE.'tag',array('keywords'=>$v,'addmenu'=>'ON'));
				}	
					db()->update(PRE.'tag', 'artrows=artrows+1', array('keywords'=>$v));			
			}
		}
		else
		{
			$int = db()->select('*')->from(PRE.'tag')->where('keywords="'.$string.'"')->get()->array_nums();
			if( $int == 0 )
			{
				$int = db()->insert(PRE.'tag',array('keywords'=>$string,'addmenu'=>'ON'));
			}	
				$int = db()->update(PRE.'tag', 'artrows=artrows+1', array('keywords'=>$string));
		}
	}
}
function getContImgs($body)
{#抓取内容图片
	$int = preg_match_all('/\/ueditor\/php\/upload\/image\/[0-9a-zA-Z-_\.\*]*\/[0-9a-zA-Z-_\.\*]*\.[gif|png|jpg|jpeg]*/', $body, $match);
	
	if(!$int)
	{
		$match = '';
		$preg='/(https?:\/\/)?([\da-z-\.]+)\.([a-z]{2,6})([\/\w \.-?&%-]*)*\/?/';
		preg_match_all($preg, $body, $matchs);

		foreach( $matchs[0] as $key=>$val )
		{
			if( preg_match('/http:\/\//',$val) )
			{
				$match[0][] = $val;
			}	
		}
	}
	
	$urlArr = '';
	if(!empty($match[0]))
	{
		foreach( $match[0] as $k => $v )
		{
			$urlArr[$k] = DIR_URL.$v;
		}
	}
	return $urlArr;
}
function thumImgs($imgArr,$minImg=null)
{#缩略图片
	$review = db()->select('thumbnail,tbwidth,tbheight')->from(PRE.'review_up')->get()->array_row();
	if( $review['thumbnail'] == "OFF" )
	{
	if(!empty($imgArr) && is_array($imgArr))
	{
		foreach($imgArr as $k=>$v)
		{		
			$exdArr = explode(".", $v);
			$exd = end($exdArr);
			
			$allPath = dirname($v);
			
			$tmpPath = date('YmdHis').'_'.uniqid().'.'.$exd;
			
			if(!empty($minImg))
			{
				$mgStr = str_replace(APTH_URL, DIR_URL,$minImg[$k]);
				if(!is_file($mgStr))
				{#如果不存在才生成缩略图片	
					$newPath[$k] = str_replace(DIR_URL, '',($allPath.'/'.$tmpPath));
					new Imagecreate($v,$review['tbwidth'],$review['tbheight'],$allPath.'/'.$tmpPath);
				}
				else 
				{
					$newPath[$k] = $minImg[$k];
				}
			}
			else 
			{	
				$newPath[$k] = str_replace(DIR_URL, '',($allPath.'/'.$tmpPath));		
				new Imagecreate($v,100,80,$allPath.'/'.$tmpPath);
			}
		}
		$imgArr = $newPath;
	}
	return $imgArr;
	}
	else 
	{
	return '';
	}
}
#处理分类事件
function CategoryEdt()
{
	if( $_POST['classified'] == '' )
	{
		if( pingmwh() )
		{
			echo "<script>alert('分类未命名');location.href='index.php?act=CategoryEdt';</script>";exit;
		}
		else
		{
			echo "<script>alert('分类未命名');location.href='index.php?act=CategoryEdt_phone';</script>";exit;
		}
	}
	else 
	{
		$data['classified'] = trim($_POST['classified']);
	}
	$data['clalias'] = trim($_POST['clalias']);
	$data['sort'] = $_POST['sort'];
	$data['description'] = $_POST['description'];
	$data['addmenu'] = $_POST['addmenu'];
	$data['templateid'] = $_POST['templateid']==''?0:$_POST['templateid'];
	
	$bool = db()->insert(PRE.'classified',$data);
	if($bool)
	{
		session_start();
		$_SESSION['flagEorre'] = 1;
		if( pingmwh() )
		{
			header('location:index.php?act=CategoryMng');
		}
		else
		{
			header('location:index.php?act=CategoryMng_phone');
		}
	}
	else 
	{
		if( pingmwh() )
		{
			echo "<script>alert('新建分类失败');location.href='index.php?act=CategoryEdt';</script>";
		}
		else
		{
			echo "<script>alert('新建分类失败');location.href='index.php?act=CategoryEdt_phone';</script>";
		}
	}
}
#处理标签事件
function TagEdt()
{
	if($_POST['keywords'] != '' )
	{
		$data['keywords'] = trim($_POST['keywords']);
	}
	else 
	{
		if( pingmwh() )
		{
			echo "<script>alert('标签未命名');location.href='index.php?act=TagEdt';</script>";exit;
		}
		else
		{
			echo "<script>alert('标签未命名');location.href='index.php?act=TagEdt_phone';</script>";exit;
		}
	}
	$data['tagas'] = trim($_POST['tagas']);
	$data['templateid'] = $_POST['templateid'];
	$data['description'] = $_POST['description'];
	$data['addmenu'] = $_POST['addmenu'];

	$bool = db()->insert(PRE.'tag',$data);
	if($bool)
	{
		session_start();
		$_SESSION['flagEorre'] = 1;
		if( pingmwh() )
		{
			header('location:index.php?act=TagMng');
		}
		else
		{
			header('location:index.php?act=TagMng_phone');
		}
	}
	else 
	{
		if( pingmwh() )
		{
			echo "<script>alert('新建标签失败');location.href='index.php?act=TagEdt';</script>";
		}
		else
		{
			echo "<script>alert('新建标签失败');location.href='index.php?act=TagEdt_phone';</script>";
		}
	}
}
#处理标签事件,修改
function TagEdtUp()
{
	$id = trim(htmlspecialchars($_POST['id'],ENT_QUOTES));
	$data['keywords'] = trim(htmlspecialchars($_POST['keywords'],ENT_QUOTES));
	$data['tagas'] = trim(htmlspecialchars($_POST['tagas'],ENT_QUOTES));
	$data['templateid'] = $_POST['templateid'];
	$data['description'] = $_POST['description'];
	$data['addmenu'] = $_POST['addmenu'];
	
	$int = db()->update(PRE.'tag', $data,array('id'=>$id));
	if($int)
	{
		session_start();
		$_SESSION['flagEorre'] = 1;
		if( pingmwh() )
		{
			header('location:index.php?act=TagMng');
		}
		else
		{
			header('location:index.php?act=TagMng_phone');
		}
	}
	else 
	{
		if( pingmwh() )
		{
			echo "<script>alert('修改失败');location.href='index.php?act=TagEdt&id=".$id."';</script>";
		}
		else
		{
			echo "<script>alert('修改失败');location.href='index.php?act=TagEdt_phone&id=".$id."';</script>";
		}
	}
}
#处理标签事件,删除
function TagEdtDelete()
{
	$id = trim(htmlspecialchars($_GET['id'],ENT_QUOTES));
	
	$int = db()->delete(PRE.'tag',array('id'=>$id));
	if($int)
	{
		session_start();
		$_SESSION['flagEorre'] = 1;
		if( pingmwh() )
		{
			header('location:index.php?act=TagMng&page='.$_GET['page']);
		}
		else
		{
			header('location:index.php?act=TagMng_phone&page='.$_GET['page']);
		}
	}
	else 
	{
		if( pingmwh() )
		{
			echo "<script>alert('删除失败');location.href='index.php?act=TagEdt&id=".$id."';</script>";
		}
		else
		{
			echo "<script>alert('删除失败');location.href='index.php?act=TagEdt_phone&id=".$id."';</script>";
		}
	}
}
#处理模块事件,新建模块
function ModuleEdt()
{
	$data['modulename'] = $_POST['modulename'];
	if( $data['modulename'] == '' )
	{
		echo "<script>alert('名称不能留空');location.href='index.php?act=ModuleEdt';</script>";exit;
	}
	$data['filename'] = $_POST['filename'];
	if( $data['filename'] == '' )
	{
		echo "<script>alert('文件名不能留空');location.href='index.php?act=ModuleEdt';</script>";exit;
	}
	$int = db()->select('id')->from(PRE.'module')->where(array('filename'=>$data['filename']))->get()->array_nums();
	if( $int == 1 )
	{
		echo "<script>alert('文件名已经存在');location.href='index.php?act=ModuleEdt';</script>";exit;
	}
	$data['htmlid'] = $_POST['htmlid'];
	if( $data['htmlid'] == '' )
	{
		echo "<script>alert('HTML ID不能留空');location.href='index.php?act=ModuleEdt';</script>";exit;
	}
	$data['divorul'] = $_POST['divorul'];
	if( $data['divorul'] == '' )
	{
		echo "<script>alert('请选择类型');location.href='index.php?act=ModuleEdt';</script>";exit;
	}
	$data['hiddenmenu'] = $_POST['hiddenmenu'];	
	$data['templateid'] = $_POST['templateid'];
	$data['updatepro'] = $_POST['updatepro'];
	$data['flag'] = $_POST['flag']==''?0:$_POST['flag'];
	if( $data['flag'] == 4 )
	{#动态
		#获取动态模块
		$modulePaht = apth_url('system/compile/module.php?act='.$data['filename'].'&divorul='.$data['divorul'].'&r=5');
		$str = file_get_contents($modulePaht);
		if( $str != null )
		{
			if( $data['updatepro'] == 'ON' )
			{
				$data['body'] = $str;
			}
			else 
			{
				if( $data['divorul'] == 1 )
				{#div
					$data['body'] = str_replace('li', 'div', $_POST['body']);
				}
				else 
				{#ul
					$data['body'] = str_replace('div', 'li', $_POST['body']);
				}
			}
		}
		else 
		{
			if( $data['divorul'] == 1 )
			{#div
				$data['body'] = str_replace('li', 'div', $_POST['body']);
			}
			else 
			{#ul
				$data['body'] = str_replace('div', 'li', $_POST['body']);
			}
		}
	}
	else 
	{#静态
		if( $data['divorul'] == 1 )
		{#div
			if(strpbrk($_POST['body'], 'div') || strpbrk($_POST['body'], 'li'))
			{
				$data['body'] = str_replace('li', 'div', $_POST['body']);
			}
			else 
			{
				$data['body'] = '<div>'.$_POST['body'].'</div>';
			}
		}
		else 
		{#ul
			if(strpbrk($_POST['body'], 'div') || strpbrk($_POST['body'], 'li'))
			{
				$data['body'] = str_replace('div', 'li', $_POST['body']);
			}
			else 
			{
				$data['body'] = '<li>'.$_POST['body'].'</li>';
			}
		}
	}
	#记录数据
	$int = db()->insert(PRE.'module',$data);
	if($int)
	{
		session_start();
		$_SESSION['flagEorre'] = 1;
		
		header('location:index.php?act=ModuleMng');
	}
	else 
	{
		echo "<script>alert('新建模块失败');location.href='index.php?act=ModuleEdt';</script>";exit;
	}
}
#处理模块事件,修改模块
function ModuleEdtUp()
{
	$id = trim(htmlspecialchars($_POST['id'],ENT_QUOTES));
	$data['modulename'] = $_POST['modulename'];
	if( $data['modulename'] == '' )
	{
		echo "<script>alert('名称不能留空');location.href='index.php?act=ModuleEdtUp';</script>";exit;
	}
	$data['htmlid'] = $_POST['htmlid'];
	if( $data['htmlid'] == '' )
	{
		echo "<script>alert('HTML ID不能留空');location.href='index.php?act=ModuleEdtUp';</script>";exit;
	}
	$data['divorul'] = $_POST['divorul'];
	if( $data['divorul'] == '' )
	{
		echo "<script>alert('请选择类型');location.href='index.php?act=ModuleEdtUp';</script>";exit;
	}
	if( $data['divorul'] == 1 )
	{#div
		$data['body'] = str_replace('li', 'div', $_POST['body']);		
	}
	else 
	{#ul		
		$data['body'] = str_replace('div', 'li', $_POST['body']);	
	}
	$data['hiddenmenu'] = $_POST['hiddenmenu'];	
	$data['updatepro'] = $_POST['updatepro'];
	$data['flag'] = $_POST['flag']==''?0:$_POST['flag'];
	#记录数据
	$int = db()->update(PRE.'module', $data, array('id'=>$id));
	if($int)
	{
		session_start();
		$_SESSION['flagEorre'] = 1;
		
		header('location:index.php?act=ModuleMng');
	}
	else 
	{
		echo "<script>alert('修改模块失败');location.href='index.php?act=ModuleEdtUp';</script>";exit;
	}
}
#处理模块事件,删除模块
function DeleteModule()
{
	$id = trim(htmlspecialchars($_GET['id'],ENT_QUOTES));
	$int = db()->delete(PRE.'module', array('id'=>$id));
	if($int)
	{
		session_start();
		$_SESSION['flagEorre'] = 1;
		
		header('location:index.php?act=ModuleMng');
	}
	else 
	{
		echo "<script>alert('删除模块失败');location.href='index.php?act=ModuleMng';</script>";
	}
}
#处理评论事件,添加评论
function review()
{
	session_start();
	#查询主题ID
	$theme = db()->select('id')->from(PRE.'theme')->where(array('addmenu'=>'OFF','flag'=>0))->get()->array_row();
	#设置选项
	$setcomment = db()->from(PRE.'review_up')->select('vifiy,listtotal,sort,filter,blacklist,sensitivelist,ipfilterlist,stopped,moderation')->get()->array_row();
	#评论表,拦截IP列表
	$array = db()->from(PRE.'review')->select('visitorip')->where(array('stopped'=>1))->get()->array_rows();
	$arr = GetoNesArr($array,'visitorip');
	
	$data['templateid'] = $theme['id'];
	$data['name'] = trim(htmlspecialchars($_POST['name'],ENT_QUOTES));
	$data['pid'] = $_POST['pid']==''?0:$_POST['pid'];
	
	$row = db()->select('name')->from(PRE.'review')->where(array('id'=>$data['pid']))->get()->array_row();
	if( !empty($row) )
	{
		if( $row['name'] == $data['name'] )
		{
			echo json_encode(array('error'=>'无法回复自已'));exit;
		}
	}
	
	if( $data['name'] == '' )
	{
		echo json_encode(array('error'=>'昵称不能留空'));exit;
	}	

	$data['tal'] = trim(htmlspecialchars($_POST['tal'],ENT_QUOTES));
	if( $data['tal'] != '' )
	{
		if(strlen($_POST['tal']) == 11)
		{
			if(!preg_match("/^0?(13|14|15|17|18)[0-9]{9}$/", $data['tal']) )
			{
				echo json_encode(array('error'=>'请输入正确的手机号码'));exit;
			}
		}
		else 
		{
			echo json_encode(array('error'=>'长度必须是11位'));exit;
		}
	}

	$data['email'] = trim(htmlspecialchars($_POST['email'],ENT_QUOTES));
	if( $data['email'] != '' )
	{
		if( !preg_match("/^\w[-\w.+]*@([A-Za-z0-9][-A-Za-z0-9]+\.)+[A-Za-z]{2,14}$/",$data['email']) )
		{
			echo json_encode(array('error'=>'请输入正确的邮箱'));exit;
		}
	}
	
	$data['qq'] = trim(htmlspecialchars($_POST['qq'],ENT_QUOTES));
	if( $data['qq'] != '' )
	{
		if( !preg_match("/^\d+$/",$data['qq']) )
		{
			echo json_encode(array('error'=>'QQ号码不正确'));exit;
		}
	}
	
	$data['body'] = mb_substr($_POST['body'],0,600);
	if( $data['body'] == '' )
	{
		echo json_encode(array('error'=>'正文不能留空'));exit;
	}
if($setcomment['filter'] == 'OFF')
{#反黑词，敏感词	
	$pattern = "/{$setcomment['blacklist']}/i";
	$data['body'] = preg_replace($pattern,'<font color="red">**警告：发布不良信息，后果自负**</font>',$data['body']);	
	if(!empty($setcomment['sensitivelist']))
	{
		$pattern = "/{$setcomment['sensitivelist']}/i";
		$data['body'] = preg_replace($pattern,'**',$data['body']);
	}
}
if( $setcomment['vifiy'] == 'OFF' )
{#开启验证码功能时生效	
	$virify1 = strtolower($_SESSION['virify']);
	$virify2 = strtolower($_POST['virify']);
	if( $virify1 != $virify2 )
	{
		echo json_encode(array('error'=>'验证码不正确'));exit;
	}
	$_SESSION['virify'] = null;
}	
	$data['titleid'] = $_POST['titleid'];//文章ID
	if( $data['titleid'] == '' )
	{
		echo json_encode(array('error'=>'文章ID不能为空'));exit;
	}
	
	$pic = str_replace(APTH_URL, '', apth_url('subject/plugin/comment/images/0.png'));#默认头像
	$data['pic'] = $_POST['pic']==''?$pic:$_POST['pic'];#头像	
	
	$data['publitime'] = time();#时间
	$data['visitorip'] = getIP();#IP
if(in_array($data['visitorip'], $arr))
{#拦截IP
	echo json_encode(array('error'=>'无法评论，IP已经被管理拦截！'));exit;
}	
if( $setcomment['stopped'] == 'OFF' )
{#拦截IP功能开启生效
	$array = explode('|', $setcomment['ipfilterlist']);
	if(in_array($data['visitorip'], $array))
	{
		echo json_encode(array('error'=>'无法评论，IP已经被管理员列入黑名单！'));exit;
	}
}	
if( $setcomment['moderation'] == 'OFF' )
{#管理员以下审核
	$data['state'] = 1;
}	
	$data['flag'] = $_POST['flag']==''?0:$_POST['flag'];
	//如果这个不为空时
	if($_POST['scoring']!='')
	{
		db()->update(PRE.'shelves', array('scoring'=>$_POST['scoring']),array('pid'=>$data['titleid']));
	}
	$int = db()->insert(PRE.'review',$data);
	if($int)
	{
		#开启时生效
		if( !empty($setcomment['listtotal']) )
		{
			$limit = " limit 0,{$setcomment['listtotal']} ";
		}
		$asc = 'desc';
		if( $setcomment['sort'] == 'OFF' )
		{
			$asc = 'asc';
		}
		
		$sql = "select id,pid,likes,name,tal,email,qq,body,pic,FROM_UNIXTIME(publitime) as publitime,visitorip,titleid,vifiy,filter,state from ".PRE."review where state=0 and flag={$data['flag']} and titleid={$data['titleid']} order by publitime {$asc} , likes {$asc} {$limit}";
		$rows = db()->query($sql)->array_rows();
		$all = $rows;
		foreach( $rows as $k => $v )
		{
			if($v['pid'] != 0 )
			{
				$sql = "select id,pid,likes,name,tal,email,qq,body,pic,FROM_UNIXTIME(publitime) as publitime,visitorip,titleid,vifiy,filter,state from ".PRE."review where state=0 and flag={$data['flag']} and id={$v['pid']}";
				$row = db()->query($sql)->array_row();
				$all[$k]['chill'] = $row;
			}
		}
		echo json_encode(array('error'=>'0','data'=>$all));
	}
	else 
	{
		echo json_encode(array('error'=>'添加评论失败'));
	}
}
#处理评论事件,评论瀑布流展示
function ReviewPuBuLiu()
{
	#设置选项
	$setcomment = db()->from(PRE.'review_up')->select('listtotal,sort')->get()->array_row();
	$data['titleid'] = $_POST['pid']==''?0:$_POST['pid'];
	#开启时生效
	$asc = 'desc';
	if( $review['sort'] == 'OFF' )
	{
		$asc = 'asc';
	}
	#分页
	$sql = "select id,pid,likes,name,tal,email,qq,body,pic,FROM_UNIXTIME(publitime) as publitime,visitorip,titleid,vifiy,filter,state from ".PRE."review where state=0 and titleid={$data['titleid']} order by publitime {$asc} , likes {$asc}";
	$rowsToal = db()->query($sql)->array_nums();
	if( $rowsToal != 0 )
	{
		$showPage = $setcomment['listtotal']==''?10:$setcomment['listtotal'];
		$pageTotal = ceil($rowsToal/$showPage);
		$page = $_POST['page']==0?1:$_POST['page'];
		$offset = ($page-1)*$showPage;
		$limit = " limit {$offset},{$showPage} ";
		
		$sql = "select id,pid,likes,name,tal,email,qq,body,pic,FROM_UNIXTIME(publitime) as publitime,visitorip,titleid,vifiy,filter,state from ".PRE."review where state=0 and titleid={$data['titleid']} order by publitime {$asc} , likes {$asc} {$limit}";
		$rows = db()->query($sql)->array_rows();
		if( !empty($rows) )
		{
			$all = $rows;
			foreach( $rows as $k => $v )
			{
				if($v['pid'] != 0 )
				{
					$sql = "select id,pid,likes,name,tal,email,qq,body,pic,FROM_UNIXTIME(publitime) as publitime,visitorip,titleid,vifiy,filter,state from ".PRE."review where state=0 and id={$v['pid']}";
					$row = db()->query($sql)->array_row();
					$all[$k]['chill'] = $row;
				}
			}
			echo json_encode(array('error'=>'0','data'=>$all));
		}
		else 
		{
			echo json_encode(array('error'=>'1'));
		}
	}
	else 
	{
		echo json_encode(array('error'=>'1'));
	}
}
#处理评论事件,审核
function CommentMngUp()
{
	$id = trim(htmlspecialchars($_GET['id'],ENT_QUOTES));
	$row = db()->select('state')->from(PRE.'review')->where(array('id'=>$id))->get()->array_row();
	if( $row['state'] == '0' )
	{
		$int = db()->update(PRE.'review', array('state'=>1), array('id'=>$id));
	}
	else
	{
		$int = db()->update(PRE.'review', array('state'=>0), array('id'=>$id));
	}
	
	if($int)
	{
		$ischecking = $_REQUEST['ischecking']==''?'':'&ischecking='.$_REQUEST['ischecking'];
		session_start();
		$_SESSION['flagEorre'] = 1;
		if( pingmwh() )
		{	
			header('location:index.php?act=CommentMng'.$ischecking);
		}
		else
		{
			header('location:index.php?act=CommentMng_phone'.$ischecking);	
		}
	}
	else 
	{
		if( pingmwh() )
		{
			echo "<script>alert('审核失败');location.href='index.php?act=CommentMng';</script>";
		}
		else
		{
			echo "<script>alert('审核失败');location.href='index.php?act=CommentMng_phone';</script>";
		}
	}
}
#处理评论事件,操作列，审核，删除
function CommentMngUplist()
{
	if( $_POST['flag3'] == 3 )
	{
		$idArr = $_POST['id'];
		if( !empty($idArr) )
		{#审核
			foreach( $idArr as $k => $v )
			{
				$row = db()->select('state')->from(PRE.'review')->where(array('id'=>$v))->get()->array_row();
				if( $row['state'] == '0' )
				{
					$int = db()->update(PRE.'review', array('state'=>1), array('id'=>$v));
				}
				else
				{
					$int = db()->update(PRE.'review', array('state'=>0), array('id'=>$v));
				}
			}
			
			if($int)
			{
				$ischecking = $_REQUEST['ischecking']==''?'':'&ischecking='.$_REQUEST['ischecking'];
				session_start();
				$_SESSION['flagEorre'] = 1;
				if( pingmwh() )
				{	
					header('location:index.php?act=CommentMng'.$ischecking);
				}
				else			
				{
					header('location:index.php?act=CommentMng_phone'.$ischecking);
				}
			}
			else 
			{
				if( pingmwh() )
				{
					echo "<script>alert('审核失败');location.href='index.php?act=CommentMng';</script>";
				}
				else			
				{
					echo "<script>alert('审核失败');location.href='index.php?act=CommentMng_phone';</script>";
				}
			}
		}
		else 
		{
			if( pingmwh() )
			{
				echo "<script>alert('请选择审核项');location.href='index.php?act=CommentMng';</script>";
			}
			else			
			{
				echo "<script>alert('请选择审核项');location.href='index.php?act=CommentMng_phone';</script>";
			}
		}
	}
	elseif(  $_POST['flag3'] == 2  )
	{#删除
		$idArr = $_POST['id'];
		if( !empty($idArr) )
		{
			foreach( $idArr as $k => $v )
			{
				$int = db()->delete( PRE.'review', array('id'=>$v) );
			}
			if($int)
			{
				$ischecking = $_REQUEST['ischecking']==''?'':'&ischecking='.$_REQUEST['ischecking'];
				session_start();
				$_SESSION['flagEorre'] = 1;
				if( pingmwh() )
				{	
					header('location:index.php?act=CommentMng'.$ischecking);
				}
				else
				{
					header('location:index.php?act=CommentMng_phone'.$ischecking);
				}
			}
			else 
			{
				if( pingmwh() )
				{
					echo "<script>alert('删除失败');location.href='index.php?act=CommentMng';</script>";
				}
				else
				{
					echo "<script>alert('删除失败');location.href='index.php?act=CommentMng_phone';</script>";
				}
			}
		}
		else 
		{
			if( pingmwh() )
			{
				echo "<script>alert('请选择删除项');location.href='index.php?act=CommentMng';</script>";
			}
			else
			{
				echo "<script>alert('请选择删除项');location.href='index.php?act=CommentMng_phone';</script>";
			}
		}
	}
}
#处理评论事件,拦截IP
function CommentMngIp()
{
	$id = trim(htmlspecialchars($_GET['id'],ENT_QUOTES));
	$row = db()->select('visitorip,stopped')->from(PRE.'review')->where(array('id'=>$id))->get()->array_row();
	if( $row['stopped'] == '0' )
	{
		$int = db()->update(PRE.'review', array('stopped'=>1), array('visitorip'=>$row['visitorip']));
	}
	else
	{
		$int = db()->update(PRE.'review', array('stopped'=>0), array('visitorip'=>$row['visitorip']));
	}
	
	if($int)
	{
		$ischecking = $_REQUEST['ischecking']==''?'':'&ischecking='.$_REQUEST['ischecking'];
		session_start();
		$_SESSION['flagEorre'] = 1;
		if( pingmwh() )
		{	
			header('location:index.php?act=CommentMng'.$ischecking);
		}
		else
		{
			header('location:index.php?act=CommentMng_phone'.$ischecking);
		}
	}
	else 
	{
		if( pingmwh() )
		{
			echo "<script>alert('拦截IP失败');location.href='index.php?act=CommentMng';</script>";
		}
		else
		{
			echo "<script>alert('拦截IP失败');location.href='index.php?act=CommentMng_phone';</script>";
		}
	}
}
#处理评论事件,删除评论
function CommentMngDelete()
{
	$id = trim(htmlspecialchars($_GET['id'],ENT_QUOTES));
	$int = db()->delete(PRE.'review',array('id'=>$id));
	if($int)
	{
		$ischecking = $_REQUEST['ischecking']==''?'':'&ischecking='.$_REQUEST['ischecking'];
		session_start();
		$_SESSION['flagEorre'] = 1;
		if( pingmwh() )
		{	
			header('location:index.php?act=CommentMng'.$ischecking);
		}
		else
		{
			header('location:index.php?act=CommentMng_phone'.$ischecking);
		}
	}
	else 
	{
		if( pingmwh() )
		{
			echo "<script>alert('删除失败');location.href='index.php?act=CommentMng';</script>";
		}
		else
		{
			echo "<script>alert('删除失败');location.href='index.php?act=CommentMng_phone';</script>";
		}
	}
}
#处理评论事件,后台管理评论插件
function ReviewMain()
{
	$data['filter'] = $_POST['filter'];
	$data['blacklist'] = $_POST['blacklist']==''?'':$_POST['blacklist'];
	$data['sensitivelist'] = $_POST['sensitivelist']==''?'':$_POST['sensitivelist'];
	$data['stopped'] = $_POST['stopped'];
	$data['ipfilterlist'] = $_POST['ipfilterlist']==''?'':$_POST['ipfilterlist'];
	$data['colsecomment'] = $_POST['colsecomment'];
	$data['moderation'] = $_POST['moderation'];
	$data['sort'] = $_POST['sort'];
	$data['vifiy'] = $_POST['vifiy'];
	$data['listtotal'] = $_POST['listtotal'];
	$data['talbox'] = $_POST['talbox']==''?0:$_POST['talbox'];
	$data['emailbox'] = $_POST['emailbox']==''?0:$_POST['emailbox'];
	$data['qqbox'] = $_POST['qqbox']==''?0:$_POST['qqbox'];
	
	$int = db()->select('id')->from(PRE.'review_up')->get()->array_nums();
	if( $int == 0 )
	{#第一次为空，就添加数据
		$int = db()->insert(PRE.'review_up',$data);
		if($int)
		{
			if( !empty($_POST['id']) )
			{	
				session_start();
				$_SESSION['flagEorre'] = 1;
				if( pingmwh() )
				{
					header('location:index.php?act=plugIns&id='.$_POST['id']);
				}
				else
				{
					header('location:index.php?act=plugIns_phone&id='.$_POST['id']);
				}
			}
			else 
			{
				session_start();
				$_SESSION['flagEorre'] = 1;
				if( pingmwh() )
				{
					header('location:index.php?act=SettingMng&flag='.$_POST['flag']);
				}
				else
				{
					header('location:index.php?act=SettingMng_phone&flag='.$_POST['flag']);
				}
			}
		}
		else 
		{
			if( pingmwh() )
			{
				echo "<script>alert('添加失败');location.href='index.php?act=plugIns&id=".$_POST['id']."';</script>";
			}
			else
			{
				echo "<script>alert('添加失败');location.href='index.php?act=plugIns_phone&id=".$_POST['id']."';</script>";
			}
		}
	}
	else 
	{#第二次不为空，修改数据
		$int = db()->update(PRE.'review_up', $data, array('id'=>1));
		if($int)
		{			
			if( !empty($_POST['id']) )
			{	
				session_start();
				$_SESSION['flagEorre'] = 1;
				if( pingmwh() )
				{
					header('location:index.php?act=plugIns&id='.$_POST['id']);
				}
				else
				{
					header('location:index.php?act=plugIns_phone&id='.$_POST['id']);
				}
			}
			else 
			{
				session_start();
				$_SESSION['flagEorre'] = 1;
				if( pingmwh() )
				{
					header('location:index.php?act=SettingMng&flag='.$_POST['flag']);
				}
				else
				{
					header('location:index.php?act=SettingMng_phone&flag='.$_POST['flag']);
				}
			}
		}
		else 
		{
			if( pingmwh() )
			{
				echo "<script>alert('修改失败');location.href='index.php?act=plugIns&id=".$_POST['id']."';</script>";
			}
			else
			{
				echo "<script>alert('修改失败');location.href='index.php?act=plugIns_phone&id=".$_POST['id']."';</script>";
			}
		}
	}
}
#处理全局事件,全局设置
function SetGlobal()
{
	$data['sitetimezone'] = $_POST['sitetimezone'];
	$data['weblanguage'] = $_POST['weblanguage'];
	$data['filestyle'] = $_POST['filestyle'];
	$data['updatemaxsize'] = $_POST['updatemaxsize'];
	$data['thumbnail'] = $_POST['thumbnail'];
	$data['tbwidth'] = $_POST['tbwidth'];
	$data['tbheight'] = $_POST['tbheight'];
	$data['pagcache'] = $_POST['pagcache'];
	$data['closesite'] = $_POST['closesite'];
	$data['development'] = $_POST['development'];
	$data['setTime'] = $_POST['setTime'];
	
	$int = db()->select('id')->from(PRE.'review_up')->get()->array_nums();
	if( $int == 0 )
	{#第一次为空，就添加数据
		$int = db()->insert(PRE.'review_up',$data);
		if($int)
		{
			session_start();
			$_SESSION['flagEorre'] = 1;
			if( pingmwh() )
			{	
				header('location:index.php?act=SettingMng&flag='.$_POST['flag']);
			}
			else
			{
				header('location:index.php?act=SettingMng_phone&flag='.$_POST['flag']);
			}
		}
		else 
		{
			if( pingmwh() )
			{
				echo "<script>alert('添加失败');location.href='index.php?act=SettingMng&id=".$_POST['id']."';</script>";
			}
			else
			{
				echo "<script>alert('添加失败');location.href='index.php?act=SettingMng_phone&id=".$_POST['id']."';</script>";
			}
		}
	}
	else 
	{#第二次不为空，修改数据
		$int = db()->update(PRE.'review_up', $data, array('id'=>1));
		if($int)
		{			
			session_start();
			$_SESSION['flagEorre'] = 1;
			if( pingmwh() )
			{	
				header('location:index.php?act=SettingMng&flag='.$_POST['flag']);
			}
			else
			{
				header('location:index.php?act=SettingMng_phone&flag='.$_POST['flag']);
			}
		}
		else 
		{
			if( pingmwh() )
			{
				echo "<script>alert('修改失败');location.href='index.php?act=SettingMng&id=".$_POST['id']."';</script>";
			}
			else
			{
				echo "<script>alert('修改失败');location.href='index.php?act=SettingMng_phone&id=".$_POST['id']."';</script>";
			}
		}
	}
}
#处理页面设置事件,页面设置
function SetPageTerm()
{
	$data['listtotal'] = $_POST['listtotal'];
	$data['searchmaxtotal'] = $_POST['searchmaxtotal'];
	$data['rowstotal'] = $_POST['rowstotal'];
$int = db()->select('id')->from(PRE.'review_up')->get()->array_nums();
	if( $int == 0 )
	{#第一次为空，就添加数据
		$int = db()->insert(PRE.'review_up',$data);
		if($int)
		{
			session_start();
			$_SESSION['flagEorre'] = 1;
			if( pingmwh() )
			{	
				header('location:index.php?act=SettingMng&flag='.$_POST['flag']);
			}
			else
			{
				header('location:index.php?act=SettingMng_phone&flag='.$_POST['flag']);
			}
		}
		else 
		{
			if( pingmwh() )
			{
				echo "<script>alert('添加失败');location.href='index.php?act=SettingMng&id=".$_POST['id']."';</script>";
			}
			else
			{
				echo "<script>alert('添加失败');location.href='index.php?act=SettingMng_phone&id=".$_POST['id']."';</script>";
			}
		}
	}
	else 
	{#第二次不为空，修改数据
		$int = db()->update(PRE.'review_up', $data, array('id'=>1));
		if($int)
		{			
			session_start();
			$_SESSION['flagEorre'] = 1;
			if( pingmwh() )
			{	
				header('location:index.php?act=SettingMng&flag='.$_POST['flag']);
			}
			else
			{
				header('location:index.php?act=SettingMng_phone&flag='.$_POST['flag']);
			}
		}
		else 
		{
			if( pingmwh() )
			{
				echo "<script>alert('修改失败');location.href='index.php?act=SettingMng&id=".$_POST['id']."';</script>";
			}
			else
			{
				echo "<script>alert('修改失败');location.href='index.php?act=SettingMng_phone&id=".$_POST['id']."';</script>";
			}
		}
	}
}
#处理附件管理事件,上传附件
function UploadMngFile()
{
	#网站设置
	$setreview = db()->select('filestyle,updatemaxsize')->from(PRE.'review_up')->get()->array_row();
	
	$flag = $_POST['name'];
	$file = $_FILES['file'];
	
	if( $file['error'] == 4 )
	{
		if( pingmwh() )
		{
			echo "<script>alert('没有文件上传');location.href='index.php?act=UploadMng';</script>";exit;
		}
		else
		{
			echo "<script>alert('没有文件上传');location.href='index.php?act=UploadMng_phone';</script>";exit;
		}
	}
	
	$array = explode('.', $file['name']);
	$ext = end($array);
	$typeArr = explode('|', $setreview['filestyle']);
	if(  !in_array( $ext, $typeArr ) )
	{
		if( pingmwh() )
		{
			echo "<script>alert('文件类型不允许');location.href='index.php?act=UploadMng';</script>";exit;
		}
		else
		{
			echo "<script>alert('文件类型不允许');location.href='index.php?act=UploadMng_phone';</script>";exit;
		}
	}
		
	if( $file['size'] >(1024*1024)*$setreview['updatemaxsize'] )
	{
		if( pingmwh() )
		{
			echo "<script>alert('文件大小超出允许范围');location.href='index.php?act=UploadMng';</script>";exit;
		}
		else
		{
			echo "<script>alert('文件大小超出允许范围');location.href='index.php?act=UploadMng_phone';</script>";exit;
		}
	}
	
	$path = dir_url('subject/upload/'.date('Y').'/'.date('m'));
	#目录不存在，直接创建目录
	if(!is_dir($path))
	{
		mkdir($path,0777,true);
	}
	#新文件名
	$destination = $path.'/'.date('YmdHis').mt_rand(10000, 99999).'.'.$ext;
	if( $flag == 0 )
	{#使用文件原名
		$destination = $path.'/'.$file['name'];
	}
	#转移文件
	if(!move_uploaded_file($file['tmp_name'], $destination))
	{
		if( pingmwh() )
		{
			echo "<script>alert('文件上传失败');location.href='index.php?act=UploadMng';</script>";exit;
		}
		else
		{
			echo "<script>alert('文件上传失败');location.href='index.php?act=UploadMng_phone';</script>";exit;
		}
	}
	#文件类型
	$data['types'] = $file['type'];
	#文件路径
	$data['filepath'] = str_replace(DIR_URL.'/', '', $destination);
	#文件名
	$array = explode('/', $destination);
	$data['filename'] = end($array);
	#文件大小
	$data['filesize'] = getbyte(filesize($destination));
	#上传时间
	$data['uptime'] = time();
	#作者
	$data['username'] = $_POST['username'];
	
	$int = db()->insert(PRE.'fileupload',$data);
	if($int)
	{
		session_start();
		$_SESSION['flagEorre'] = 1;
		if( pingmwh() )
		{		
			header('location:index.php?act=UploadMng');
		}
		else
		{
			header('location:index.php?act=UploadMng_phone');
		}
	}
	else 
	{
		if( pingmwh() )
		{
			echo "<script>alert('添加失败');location.href='index.php?act=UploadMng';</script>";
		}
		else
		{
			echo "<script>alert('添加失败');location.href='index.php?act=UploadMng_phone';</script>";
		}
	}
}
#处理附件管理事件,删除
function UploadMngFileDelete()
{
	$id = trim(htmlspecialchars($_GET['id'],ENT_QUOTES));
	
	$row = db()->select('filepath')->from(PRE.'fileupload')->where(array('id'=>$id))->get()->array_row();
	$filename = DIR_URL.$row['filepath'];
	if(is_file($filename))
	{
		unlink($filename);
	}
	
	$int = db()->delete(PRE.'fileupload',array('id'=>$id));
	if($int)
	{
		session_start();
		$_SESSION['flagEorre'] = 1;
		if( pingmwh() )
		{		
			header('location:index.php?act=UploadMng&page='.$_POST['page']);
		}
		else
		{
			header('location:index.php?act=UploadMng_phone&page='.$_POST['page']);
		}
	}
	else 
	{
		if( pingmwh() )
		{
			echo "<script>alert('删除失败');location.href='index.php?act=UploadMng';</script>";
		}
		else
		{
			echo "<script>alert('删除失败');location.href='index.php?act=UploadMng_phone';</script>";
		}
	}
}
############################################################################################
#内容页面静态化
function artstatic()
{
	$id = trim(htmlspecialchars($_REQUEST['id'],ENT_QUOTES));
	#静态化插件
	include Pagecall('static');	
	if($data['filter'] == 'ON' && $data['art'] == 1)
	{
	file_get_contents(apth_url('index.php?act=article_content&id='.$id));
	}
}
#清除留言内容
function MessageInfoDelete()
{
	$id = trim(htmlspecialchars($_REQUEST['id'],ENT_QUOTES));
	$int = db()->delete(PRE.'message',array('id'=>$id));
	if($int)
	{
		session_start();
		$_SESSION['flagEorre'] = 1;
		if( pingmwh() )
		{
			header('location:index.php?act=OrderMng&ischecking=1');
		}		
		else
		{
			header('location:index.php?act=OrderMng_phone&ischecking=1');
		}
	}
	else 
	{
		if( pingmwh() )
		{
			echo "<script>alert('删除失败');location.href='index.php?act=OrderMng&ischecking=1';</script>";
		}		
		else
		{
			echo "<script>alert('删除失败');location.href='index.php?act=OrderMng_phone&ischecking=1';</script>";
		}
	}
}
#清除订单内容
function OrderInfoDelete()
{
	$id = trim(htmlspecialchars($_REQUEST['id'],ENT_QUOTES));
	$int = db()->delete(PRE.'orders',array('id'=>$id));
	if($int)
	{
		session_start();
		$_SESSION['flagEorre'] = 1;
		if( pingmwh() )
		{		
			header('location:index.php?act=OrderMng');
		}		
		else
		{
			header('location:index.php?act=OrderMng_phone');
		}
	}
	else 
	{
		if( pingmwh() )
		{	
			echo "<script>alert('删除失败');location.href='index.php?act=OrderMng';</script>";
		}		
		else
		{
			echo "<script>alert('删除失败');location.href='index.php?act=OrderMng_phone';</script>";
		}
	}
}
function MessageInfolist()
{
	if($_POST['ischecking'] == 1)
	{
		$diarr = $_POST['id'];
		if(!empty($diarr))
		{
			foreach($diarr as $k=>$v)
			{
				$int = db()->delete(PRE.'message',array('id'=>$v));
			}
		}
		if($int)
		{
			session_start();
			$_SESSION['flagEorre'] = 1;
			if( pingmwh() )
			{		
				header('location:index.php?act=OrderMng&ischecking=1');
			}		
			else
			{
				header('location:index.php?act=OrderMng_phone&ischecking=1');
			}
		}
		else 
		{
			if( pingmwh() )
			{
				echo "<script>alert('删除失败');location.href='index.php?act=OrderMng&ischecking=1';</script>";
			}		
			else
			{
				echo "<script>alert('删除失败');location.href='index.php?act=OrderMng_phone&ischecking=1';</script>";
			}
				
		}
	}
	else 
	{
		$diarr = $_POST['id'];
		if(!empty($diarr))
		{
			foreach($diarr as $k=>$v)
			{
				$int = db()->delete(PRE.'orders',array('id'=>$v));
			}
		}
		if($int)
		{
			session_start();
			$_SESSION['flagEorre'] = 1;
			if( pingmwh() )
			{		
				header('location:index.php?act=OrderMng');
			}		
			else
			{
				header('location:index.php?act=OrderMng_phone');
			}
		}
		else 
		{
			if( pingmwh() )
			{
				echo "<script>alert('删除失败');location.href='index.php?act=OrderMng';</script>";
			}		
			else
			{
				echo "<script>alert('删除失败');location.href='index.php?act=OrderMng_phone';</script>";
			}
		}
	}
}
function SurnameUpdate()
{
	$id = $_POST['id'];
	$data['xing'] = $_POST['xing'];
	$data['pinyin'] = $_POST['pinyin'];
	$data['bushou'] = $_POST['bushou'];
	$data['bihua'] = $_POST['bihua'];
	$data['wubi'] = $_POST['wubi'];
	$data['title'] = $_POST['title'];
	$data['meaning'] = $_POST['meaning'];
	$data['flag'] = $_POST['flag'];
	$int = db()->update(PRE.'surname', $data, array('id'=>$id));
	if($int)
	{
		session_start();
		$_SESSION['flagEorre'] = 1;
		header('location:index.php?act=Surname&page='.$_POST['page']);
	}
	else 
	{
		echo "<script>alert('修改失败');location.href='index.php?act=Surname';</script>";
	}
}