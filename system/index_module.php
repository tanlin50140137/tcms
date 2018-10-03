<?php
header('content-type:text/html;charset=utf-8');
/**
 * 所有模块必须写在函数里
 * */
#主页面 , Ready to start work (准备开始工作)
function index()
{
	#关闭网站ON时无法访问
	CloseSite();
	#浏览次数
	$browse = BrowseTimes();
	#页面缓存
	PageCache();

	#网站设置
	$setreview = db()->select('listtotal,searchmaxtotal,pagcache')->from(PRE.'review_up')->get()->array_row();
	#文章列表信息
	$page = $_REQUEST['page']==''?1:$_REQUEST['page'];
	$showTotal = $setreview['listtotal']==''?10:$setreview['listtotal'];
	$cipid = $_REQUEST['cipid']==''?null:$_REQUEST['cipid'];
	$top = $_REQUEST['top']==''?'102 or a.top=101 ':$_REQUEST['top'];
	$tag = $_REQUEST['tag']==''?null:$_REQUEST['tag'];
	$author = $_REQUEST['author']==''?null:$_REQUEST['author'];
	$fileds = $_REQUEST['filed']==''?null:$_REQUEST['filed'];
	$title = $_REQUEST['search']==''?null:$_REQUEST['search'];
	if($tag!=null||$author!=null||$fileds!=null||$title!=null){$showTotal = $setreview['searchmaxtotal']==''?10:$setreview['searchmaxtotal'];}
	//$articleList = This_article(null,'desc',$page,$showTotal,$cipid,$top,$tag,$author,$fileds,$title);
	#服务端处理数据
	//$art_List = server_data($articleList);
	#公共文件内容
	include 'subject/'.getThemeDir().'/common.php';		
	unset($articleList);
	#开启缓冲区
	ob_start();
	#静态化插件
	include Pagecall('static');
	require 'subject/'.getThemeDir().'/template/'.__FUNCTION__.'.html';
	if( $data['filter'] == 'ON' && $data['index'] == 1 )
	{	
		$filename = dir_url(getThemeDir().'/index.html');		
		#静态化
		$str = ob_get_contents();
		file_put_contents($filename, $str);	
	}
	else 
	{#动态时使用页面缓存
		if($setreview['pagcache']=='OFF')
		{#开启缓存时生效
		$filename = dir_url('/subject/'.getThemeDir().'/cache');
		if(!is_dir($filename))
		{
			mkdir($filename,0777,true);
		}
		#静态化
		$str = ob_get_contents();
		file_put_contents($filename.'/'.substr(md5(base64_decode(uniqid(mktime(),true))),10,10), $str);	
		}
	}
}
##################################################################################################
#文章列表页面
function article_list()
{
	#关闭网站ON时无法访问
	CloseSite();
	
	#查询模板名称
	$id = trim(htmlspecialchars($_REQUEST['id']==null?GetIndexValue(1):$_REQUEST['id'],ENT_QUOTES,'utf-8',false));
	
	$module = db()->select('module,forbidden')->from(PRE.'template')->where(array('id'=>$id))->get()->array_row();
	if(empty($module)||$module['module']=='article_list')
	{
		$module['module'] = __FUNCTION__;
		$id = null;
	}
	else 
	{
		if($module['forbidden']=='OFF')
		{
			$id = null;
		}
	}		
		
	#网站设置
	$setreview = db()->select('listtotal,searchmaxtotal,pagcache')->from(PRE.'review_up')->get()->array_row();
	#文章列表信息
	$page = $_REQUEST['page']==''?1:$_REQUEST['page'];
	$showTotal = $setreview['listtotal']==''?10:$setreview['listtotal'];
	$cipid = $_REQUEST['cipid']==''?null:$_REQUEST['cipid'];
	$top = $_REQUEST['top']==''?'102 or a.top=103 ':$_REQUEST['top'];
	$tag = $_REQUEST['tag']==''?null:$_REQUEST['tag'];
	$author = $_REQUEST['author']==''?null:$_REQUEST['author'];
	$fileds = $_REQUEST['filed']==''?null:$_REQUEST['filed'];
	$title = $_REQUEST['search']==''?null:$_REQUEST['search'];
	if($tag!=null||$author!=null||$fileds!=null||$title!=null){$showTotal = $setreview['searchmaxtotal']==''?10:$setreview['searchmaxtotal'];}
	$articleList = This_article($id,'desc',$page,$showTotal,$cipid,$top,$tag,$author,$fileds,$title);
	#服务端处理数据
	$art_List = server_data($articleList);

	unset($articleList);
	
	#公共文件内容
	include 'subject/'.getThemeDir().'/common.php';
	
	#开启缓冲区
	ob_start();
	#静态化插件
	include Pagecall('static');
	if(is_file('subject/'.getThemeDir().'/template/'.$module['module'].'.html'))
	{
		require 'subject/'.getThemeDir().'/template/'.$module['module'].'.html';
	}
	else
	{
		header("content-type:text/html;charset=utf-8");
		echo '页面不存在：template/目录下未发现 '.$module['module'].'.html　文件，请创建文件后重试';exit;
	}
	if( $data['filter'] == 'ON' && $data['lanmu'] == 1 )
	{
		if(!empty($_REQUEST['page']))
		{#分页
			$filename = dir_url('paging');
			if(!is_dir($filename))
			{
				mkdir($filename,0777,true);
			}
			#静态化
			$str = ob_get_contents();
			file_put_contents($filename.'/'.'list_'.$page.'.html', $str);	
		}
		else 
		{
			$filename = dir_url('column');
			if(!is_dir($filename))
			{
				mkdir($filename,0777,true);
			}
			#静态化
			$str = ob_get_contents();
			file_put_contents($filename.'/'.$module['module'].'.html', $str);
		}
	}
}
##################################################################################################
#文章内容页面
function article_content()
{
	#关闭网站ON时无法访问
	CloseSite();
	
	#评论列表
	$commentList = This_review($_REQUEST['id']);
	
	#用户昵称
	$nickname = nickname(0);
	#评论设置
	$setcomment = db()->from(PRE.'review_up')->select('vifiy,colsecomment,talbox,qqbox,emailbox')->get()->array_row();
	#插件表
	$chajian = db()->select('addmenu,themename')->from(PRE.'theme')->where(array('addmenu'=>'OFF','flag'=>1,'themename'=>'comment'))->get()->array_row();

	#文章内容
	$ArticleBoby = This_article_c();

	#文章内容所有图片
	if($ArticleBoby['imgurl']!='' || $ArticleBoby['imgurl']!='null')
	{
		$ArticleImg = showImg($ArticleBoby['imgurl'],'array');
	}
	#面包屑
	if(!empty($ArticleBoby))
	{
		$bread = get_bread($ArticleBoby['id']);
	}	
	
	#网站设置
	$setreview = db()->select('listtotal,pagcache')->from(PRE.'review_up')->get()->array_row();
	#相关内容	
	if(!empty($ArticleBoby['cipid']))
	{
		$relevant = AnchorClass($_REQUEST['id'],$ArticleBoby['cipid'],$setreview['listtotal'],0);
	}
	
	#公共文件内容
	include 'subject/'.getThemeDir().'/common.php';
	
	#开启缓冲区
	ob_start();
	#静态化插件
	include Pagecall('static');
	require 'subject/'.getThemeDir().'/template/'.__FUNCTION__.'.html';
	
	if( $data['filter'] == 'ON' && $data['lanmu'] == 1 )
	{
		#静态化
		$str = ob_get_contents();
		#文章静态化目录
		$dirapth = dir_url('artic');
		if(!is_dir($dirapth))
		{
			mkdir($dirapth,0777,true);
		}
		#生成静态文件
		file_put_contents($dirapth.'/'.$ArticleBoby['poslink'].'.html', $str);	
	}
}