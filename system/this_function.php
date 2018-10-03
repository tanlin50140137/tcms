<?php
header('content-type:text/html;charset=utf-8');
/**
 * 自定义方法
*/
#实例化一个db对像
function db()
{
	return new This_Linked();
}
#处理xml
function xml_str($array)
{
	$xml = '<?xml version="1.0" encoding="UTF-8"?>'."\n";
	$xml .= '<box>'."\n";
	foreach($array as $key=>$val)
	{
		$xml .= '<'.$key.'>'.$val.'</'.$key.'>'."\n";
	}
	$xml .= '</box>';
	return $xml;
}
#处理xml
function xml_str2($array)
{
	$xml = '<?xml version="1.0" encoding="UTF-8"?>'."\n";
	$xml .= '<box>'."\n";
	foreach($array as $key=>$val)
	{
		$xml .= '<'.$key.'>'.urlencode($val).'</'.$key.'>'."\n";
	}
	$xml .= '</box>';
	return $xml;
}
#加载主题
function load_theme($dir='default')
{
	$dir = $dir==''?'404':$dir;	
		
	if(is_file(DIR_URL.'/subject/'.$dir.'/index.php'))
	{
		include_once DIR_URL.'/subject/'.$dir.'/index.php';
	}
	else 
	{
		header("content-type:text/html;charset=utf-8");
		echo '加载失败：主题首页不存在 或 未启用主题 !';
	}
}
#添加后缀
function AddHouZhui($m)
{
	if( $m !== '' )
	{
		$m = $m.'.html';
	}
	return $m;
}
#根目录url
function apth_url($url='')
{
	if( $url !='' )
	{
		$int = strpos($url, '/');
		if( $int !== 0 )
		{#有/
			$url = '/'.$url;
		}
	}
	return APTH_URL.$url;
}
#路由url
function site_url($url='')
{
	return SITE_URL.'/'.$url;
}
#路由dir
function base_url($dir='')
{
	return BASE_URL.'/'.$dir;
}
#路由dir
function dir_url($dir='')
{
	return DIR_URL.($dir==''?'':'/'.$dir);
}
#截取字符串
function subString($string,$len)
{
	if(mb_strlen($string,'utf-8')>=$len)
	{
		$string = mb_substr($string, 0,$len,'utf-8').'...';
	}
	return $string;
}
#置顶符串
function sateFormat($format)
{
	switch ($format)
	{
		case '0':
			$str = '<font color="#330000">公开</font>';
		break;
		case '1':
			$str = '<font color="#FF9900">审核</font>';
		break;
		case '2':
			$str = '<font color="#CC3300">草稿</font>';
		break;
	}
	return $str;
}
#审核状态
function stateFormat2($format)
{
	switch ($format)
	{
		case '0':
			$str = '<font color="#330000">待审核</font>';
		break;
		case '1':
			$str = '<font color="#FF9900">已审核</font>';
		break;
		case '2':
			$str = '<font color="#CC3300">未通过</font>';
		break;
	}
	return $str;
}
#用户级别
function getUserJb($int)
{
	switch ($int)
	{
		case 0:
			$str = '管理员';
		break;
		case 1:
			$str = '网站编辑员';
		break;
		case 2:
			$str = '作者';
		break;
		case 3:
			$str = '协作者';
		break;
		case 4:
			$str = '评论员';
		break;
		case 5:
			$str = '游客';
		break;
	}
	return $str;
}
#置顶符串
function topFormat($format)
{
	switch ($format)
	{
		case '101':
			$str = '<font color="#0066FF">[首页置顶]</font>|';
		break;
		case '102':
			$str = '<font color="#0066FF">[全局置顶]</font>|';
		break;
		case '103':
			$str = '<font color="#0066FF">[分类置顶]</font>|';
		break;
	}
	return $str;
}
#获取IP
function getIP()
{
	static $realip;
	if (isset($_SERVER)){
	      if (isset($_SERVER["HTTP_X_FORWARDED_FOR"])){
	          $realip = $_SERVER["HTTP_X_FORWARDED_FOR"];
	 	  } else if (isset($_SERVER["HTTP_CLIENT_IP"])) {
	         $realip = $_SERVER["HTTP_CLIENT_IP"];
	 	  } else {
	         $realip = $_SERVER["REMOTE_ADDR"];
	 	  }
	 } else {
	        if (getenv("HTTP_X_FORWARDED_FOR")){
	            $realip = getenv("HTTP_X_FORWARDED_FOR");
	        } else if (getenv("HTTP_CLIENT_IP")) {
	            $realip = getenv("HTTP_CLIENT_IP");
	        } else {
	            $realip = getenv("REMOTE_ADDR");
	        }
	 }
	 return $realip;
}
#转换时间函数
function timeFormat($format,$flag=0)
{
	switch ($flag)
	{
		case '0':
			$str = date('Y-m-d H:i:s',$format);
		break;
		case '1':
			$str = date('Y-m-d H:i',$format);
		break;
		case '2':
			$str = date('Y-m-d',$format);
		break;
		case '3':
			$str = date('Y年m月d日 H时i分s秒',$format);
		break;
		case '4':
			$str = date('Y年m月d日 H时i分',$format);
		break;
		case '5':
			$str = date('Y年m月d日',$format);
		break;
	}
	return $str;
}
function formatSeconds($value) { 
	$theTime = intval($value);// 秒 
	$theTime1 = 0;// 分 
	$theTime2 = 0;// 小时 
	// alert(theTime); 
	if($theTime > 60) { 
		$theTime1 = intval($theTime/60); 
		$theTime = intval($theTime%60); 
		// alert(theTime1+"-"+theTime); 
		if($theTime1 > 60) { 
			$theTime2 = intval($theTime1/60); 
			$theTime1 = intval($theTime1%60); 
		} 
	} 
	$result = "".intval($theTime)."秒"; 
	if($theTime1 > 0) { 
		$result = "".intval($theTime1)."分".$result; 
	} 
	if($theTime2 > 0) { 
		$result = "".intval($theTime2)."小时".$result; 
	} 
	return $result; 
} 
#将二维数组转成一维数组
function GetoNesArr($array,$key)
{
	if(!empty($array))
	{
		foreach($array as $k=>$v)
		{
			$a[] = $v[$key];
		}
		return array_unique($a);
	}
	else 
	{
		return array();
	}
}
#二维关联数组排序，可以指定字段排序,排序顺序标志 SORT_DESC 降序；SORT_ASC 升序    
function array_Two_sort($array,$string,$sort='SORT_ASC')
{
	$sort = array(    
            'direction' => $sort, //排序顺序标志 SORT_DESC 降序；SORT_ASC 升序    
            'field'     => $string,       //排序字段    
    );    
    $arrSort = array();    
    foreach($array as $uniqid => $row){    
        foreach($row as $key=>$value){    
            $arrSort[$key][$uniqid] = $value;    
        }    
    }   
    if($sort['direction']){    
        array_multisort($arrSort[$sort['field']], constant($sort['direction']), $array);    
    }       
    return $array;  
}
#无限级别分类
function GetFenLai($pid,$multiplier=0)
{
	static $rows;	
	#查询主题ID
	$theme = db()->select('id')->from(PRE.'theme')->where(array('addmenu'=>'OFF','flag'=>0))->get()->array_row();
	
	$multiplier = $rows==null?0:$multiplier+2;
	$sql = "select id,pid,userid,name,module,keywords,description,addmenu,artrows,state,templateid from ".PRE."template where pid={$pid} and forbidden='ON' and templateid={$theme['id']} order by sort desc";
	$rs = mysql_query($sql);
	while ($array = mysql_fetch_assoc($rs))
	{
		$array['name'] = str_repeat('&nbsp;', $multiplier).'|－'.$array['name'];
		$rows[] = $array;
		GetFenLai($array['id'],$multiplier);//递归
	}
	return $rows;
}
#无限级别分类
function GetFenLaiList($pid)
{
	static $rows;
	#查询主题ID
	$theme = db()->select('id')->from(PRE.'theme')->where(array('addmenu'=>'OFF','flag'=>0))->get()->array_row();
	
	$sql = "select id,pid,userid,name,module,keywords,description,addmenu,artrows,state,templateid from ".PRE."template where pid={$pid} and addmenu='OFF' and templateid={$theme['id']} order by sort desc";
	$rs = mysql_query($sql);
	while ($array = mysql_fetch_assoc($rs))
	{
		$rows[] = $array;
		GetFenLaiList($array['id']);//递归
	}
	return $rows;
}
#无限级别分类,静态化专用
function GetFenLaiList_static($pid)
{
	static $rows;
	#查询主题ID
	$theme = db()->select('id')->from(PRE.'theme')->where(array('addmenu'=>'OFF','flag'=>0))->get()->array_row();
	
	$sql = "select id,pid,userid,name,module,keywords,description,addmenu,artrows,state,templateid from ".PRE."template where pid={$pid} and templateid={$theme['id']} order by sort desc";
	$rs = mysql_query($sql);
	while ($array = mysql_fetch_assoc($rs))
	{
		$rows[] = $array;
		GetFenLaiList_static($array['id']);//递归
	}
	return $rows;
}
#栏目列表并子级
function get_columnList($pid)
{
	include Pagecall('static');
	#查询主题ID
	$theme = db()->select('id')->from(PRE.'theme')->where(array('addmenu'=>'OFF','flag'=>0))->get()->array_row();
	if( $pid !== '' )
	{		
		$mem = new Memcache();
	    $mem->connect("127.0.0.1", 11211);
		
	    $sql = "select id,pid,userid,name,module,keywords,description,addmenu,artrows,cover,state,templateid from ".PRE."template where pid={$pid} and addmenu='OFF' and templateid={$theme['id']} order by sort desc";
	    
	    $key = md5( $sql );
		
	    if( !$mem->get( $key ) )
	    {			
			$val = db()->query( $sql )->array_rows();
			
			$mem->set($key, $val, 0, 300);   	
    		$rows = $mem->get( $key );
	    }
	    else 
	    {
	    	$rows = $mem->get( $key );
	    }
	    
	}
	
	if( !empty($rows) )
	{
		foreach( $rows as $k => $v )
		{
			$rows[$k]['img'] = '';
			if($v['cover']!='')
			{
				if(strrpos($v['cover'], 'a-ettra01.jpg'))
				{
					$rows[$k]['img'] = apth_url($v['cover']);
				}
				else
				{
					$rows[$k]['img'] = $v['cover'];
				}
			}
			$rows[$k]['title'] = $v['name'];
			$rows[$k]['descriptions'] = subString(strip_tags($v['description']),50);
			if( $data['filter'] == 'ON' && $data['lanmu']==1 )
			{#静态链接
				$rows[$k]['link'] = getColumnStaticUrl($v['module']);
			}
			else 
			{#动态链接
				$rows[$k]['link'] = site_url('index.php?act=article_list&id='.$v['id']);
			}
				
			$sql = "select id,pid,userid,name,module,keywords,description,addmenu,artrows,cover,state,templateid from ".PRE."template where pid={$v['id']} and addmenu='OFF' and templateid={$theme['id']}";
			$rows[$k]['child'] = db()->query($sql)->array_rows();
			if( !empty($rows[$k]['child']) )
			{
				foreach($rows[$k]['child'] as $ks=>$val)
				{
					$rows[$k]['child'][$ks]['img'] = '';
					if($val['cover']!='')
					{
						if(strrpos($val['cover'], 'a-ettra01.jpg'))
						{
							$rows[$k]['child'][$ks]['img'] = apth_url($val['cover']);
						}
						else
						{
							$rows[$k]['child'][$ks]['img'] = $val['cover'];
						}
					}
					$rows[$k]['child'][$ks]['title'] = $val['name'];
					$rows[$k]['child'][$ks]['descriptions'] = subString(strip_tags($val['description']),50);
					if( $data['filter'] == 'ON' && $data['lanmu']==1 )
					{#静态链接
						$rows[$k]['child'][$ks]['link'] = getColumnStaticUrl($val['module']);
					}
					else 
					{#动态链接
						$rows[$k]['child'][$ks]['link'] = apth_url('index.php?act=article_list&id='.$val['id']);
					}
					
					$rows[$k]['child'][$ks]['child'] = get_columnList($val['id']);
				}
			}
		}
	}
	return $rows;
}
#获取火天信资料所有分类－无限级别
function GetHTXInfiniteClass($pid)
{
	$sql = 'select id,pid,name,nas,abstract,storage,sort,state,publitime,updatetime from '.PRE.'resource_class where pid='.$pid.' and state=1 order by sort desc';
	
	$mem = new Memcache();
	$mem->connect("127.0.0.1", 11211);
	$key = md5( $sql );
		
	if( !$mem->get( $key ) )
	{		
		$rows = db()->query( $sql )->array_rows();			
		if( !empty($rows) )
		{
			foreach( $rows as $k=>$v )
			{
				$sql = 'select id,pid,name,nas,abstract,storage,sort,state,publitime,updatetime from '.PRE.'resource_class where pid='.$v['id'].' and state=1 order by sort desc';
				$rows[$k]['child'] = db()->query($sql)->array_rows();
				if( !empty( $rows[$k]['child'] ) )
				{
					foreach($rows[$k]['child'] as $ks=>$val)
					{
						$rows[$k]['child'][$ks]['child'] = get_columnList($val['id']);
					}
				}
			}
		}
		$mem->set($key, $rows, 0, 300);   	
    	$row = $mem->get( $key );
	}
	else
	{
		$row = $mem->get( $key );
	}
	return $row;
}
#获取单个栏目信息，栏目列表,$colid＝传入栏目ID
function get_columninfo($colid)
{	
	if( !empty($colid) )
	{
		include Pagecall('static');
		#查询主题ID
		$theme = db()->select('id')->from(PRE.'theme')->where(array('addmenu'=>'OFF','flag'=>0))->get()->array_row();
			
		$where = "id={$colid}";
		
		$sql = "select id,pid,userid,name,module,keywords,description,addmenu,artrows,cover,state,templateid from ".PRE."template where {$where} and templateid={$theme['id']} order by sort desc";
		
		$mem = new Memcache();
	    $mem->connect("127.0.0.1", 11211);
		
	    $key = md5( $sql );
		
	    if( !$mem->get( $key ) )
	    {		
			$val = db()->query( $sql )->array_row();
			
			$mem->set($key, $val, 0, 300); 	
    		$row = $mem->get( $key );
	    }
	    else 
	    {
	    	$row = $mem->get( $key );
	    }
		
		
		if(!empty($row))
		{
			if(!strrpos($row['cover'], 'a-ettra01.jpg')&&$row['cover']!='')
			{#封面图片不是默认值时,使用封面
				$row['img'] = $row['cover'];
			}
			else 
			{#封面图片是默认值时,使用其他图片
				if( $row['imgurl']=='null' || $row['imgurl']=="" )
				{#内容图片不空时使用，默认图片
					$row['img'] = apth_url($row['cover']);
				}
				else 
				{
					$row['img'] = showImg($row['imgurl'],'rand');
				}
			}
			#多加一个无<html>描述
			$row['title'] = $row['name'];
			$row['descriptions'] = subString(strip_tags($row['description']),50);
			#动态链接与静态链接,文章列表
			if( $data['filter'] == 'ON' && $data['lanmu']==1 )
			{#静态链接
				$row['link'] = getColumnStaticUrl($row['module']);
			}
			else 
			{#动态链接
				$row['link'] = apth_url('index.php?act=article_list&id='.$row['id']);
			}
		}	
	}
	return $row;
}
#获取某个栏目下的封面,开启状态下的栏目
function get_columnCoverON($columnmodule)
{
	include Pagecall('static');
	
	#查询主题ID
	$theme = db()->select('id')->from(PRE.'theme')->where(array('addmenu'=>'OFF','flag'=>0))->get()->array_row();

	$template = db()->select('id')->from(PRE.'template')->where(array('module'=>$columnmodule,'templateid'=>$theme['id']))->get()->array_row();
	
	$idList = get_columlist_id($template['id']);

	if( !empty($idList) )
	{
		$listsql = join(',',$idList);
		$where = " id in({$template['id']},{$listsql}) ";
	}
	else 
	{
		$where = " id={$template['id']} ";
	}
	if(!empty($template))
	{
	$sql = "select id,pid,userid,name,module,keywords,description,addmenu,artrows,cover,state,templateid from ".PRE."template where {$where} and addmenu='OFF' and templateid={$theme['id']} order by sort desc";

	$rows = db()->query($sql)->array_rows();
	}
	if(!empty($rows))
	{
		foreach( $rows as $k=>$v )
		{
			$rows[$k]['img'] = '';
			if($v['cover']!='')
			{
				if(strrpos($v['cover'], 'a-ettra01.jpg'))
				{
					$rows[$k]['img'] = apth_url($v['cover']);
				}
				else
				{
					$rows[$k]['img'] = $v['cover'];
				}
			}
			$rows[$k]['title'] = $v['name'];
			$rows[$k]['descriptions'] = subString(strip_tags($v['description']),250);
			if( $data['filter'] == 'ON' && $data['lanmu']==1 )
			{#静态链接
				$rows[$k]['link'] = getColumnStaticUrl($v['module']);
			}
			else 
			{#动态链接
				$rows[$k]['link'] = apth_url('index.php?act=article_list&id='.$v['id']);
			}
		}
	}
	
	return $rows;
}
#获取某个栏目下的封面,关闭状态下的栏目
function get_columnCoverOFF($columnmodule)
{
	include Pagecall('static');
	
	#查询主题ID
	$theme = db()->select('id')->from(PRE.'theme')->where(array('addmenu'=>'OFF','flag'=>0))->get()->array_row();
	
	$template = db()->select('id')->from(PRE.'template')->where(array('module'=>$columnmodule,'templateid'=>$theme['id']))->get()->array_row();
	
	$idList = get_columlist_id($template['id']);

	if( !empty($idList) )
	{
		$listsql = join(',',$idList);
		$where = " id in({$template['id']},{$listsql}) ";
	}
	else 
	{
		$where = " id={$template['id']} ";
	}
	
	$sql = "select id,pid,userid,name,module,keywords,description,addmenu,artrows,cover,state,templateid from ".PRE."template where {$where} and addmenu='ON' and templateid={$theme['id']} order by sort desc";

	$rows = db()->query($sql)->array_rows();
	
	if(!empty($rows))
	{
		foreach( $rows as $k=>$v )
		{
			$rows[$k]['img'] = '';
			if($v['cover']!='')
			{
				if(strrpos($v['cover'], 'a-ettra01.jpg'))
				{
					$rows[$k]['img'] = apth_url($v['cover']);
				}
				else
				{
					$rows[$k]['img'] = $v['cover'];
				}
			}
			$rows[$k]['title'] = $v['name'];
			$rows[$k]['descriptions'] = subString(strip_tags($v['description']),250);
			if( $data['filter'] == 'ON' && $data['lanmu']==1 )
			{#静态链接
				$rows[$k]['link'] = getColumnStaticUrl($v['module']);
			}
			else 
			{#动态链接
				$rows[$k]['link'] = apth_url('index.php?act=article_list&id='.$v['id']);
			}
		}
	}
	
	return $rows;
}
#改变图片像素
function get_pixels($dir,$x,$y)
{
	return apth_url("system/img_pixels.php?dir=$dir&x=$x&y=$y");
}
#打开文件模型
function open_file_mode($id)
{
	return apth_url("system/file_mode.php?fileid=".$id);
}
#获取子级所有ID
function get_columlist_id($pid)
{
	$rows = get_columnList($pid);
	$idlist = '';
	if( !empty($rows) )
	{
		foreach( $rows as $k => $v )
		{
			$idlist[] = $v['id'];
		}
	}
	return $idlist;
}
#无限级别分类,模块使用
function GetFenLaiList2($pid,$multiplier=0)
{
	static $rows;
	$multiplier = $rows==null?0:$multiplier+2;
	#查询主题ID
	$theme = db()->select('id')->from(PRE.'theme')->where(array('addmenu'=>'OFF','flag'=>0))->get()->array_row();
	
	$sql = "select id,pid,userid,name,module,keywords,description,addmenu,artrows,state,templateid from ".PRE."template where pid={$pid} and addmenu='OFF' and templateid={$theme['id']} order by sort desc";
	$rs = mysql_query($sql);
	while ($array = mysql_fetch_assoc($rs))
	{
		$array['name'] = str_repeat('&nbsp;', $multiplier).'|－'.$array['name'];
		$rows[] = $array;
		GetFenLaiList2($array['id']);//递归
	}
	return $rows;
}
#展示图片
function showImg($imgStr,$int='rand')
{
	$imgApth = '';
	if( $imgStr !='' || $imgStr != 'null' )
	{
		if(!strrpos($imgStr, 'a-ettra01.jpg'))
		{
			$arr = json_decode($imgStr,true);
			if($int == 'rand')
			{
				$imgApth = str_replace(APTH_URL, '', $arr[mt_rand(0, (count($arr)-1))]);
			}
			elseif($int=='array')
			{
				$imgApth = $arr;
			}
			else
			{
				if($int<=0 || !is_numeric($int))
				{
					$int=0;
				}
				else
				{
					$int=($int-1);
				}
				$imgApth = str_replace(APTH_URL, '', $arr[$int]);
			}
		}
		else 
		{#默认封面图片
			$imgApth = apth_url($imgStr);
		}
	}
	return $imgApth;
}
#解压工具,在线解压
function pclzip($zip,$path)
{	
	require('compile/pclzip.lib.php'); 
	$archive = new PclZip($zip); 
	$arr = $archive->extract(PCLZIP_OPT_PATH, $path);
	$dir = substr($arr[0]['stored_filename'],0,strrpos($arr[0]['stored_filename'], '/'));
	if($arr)
	{
		#解压成功跳转至主题表单，填写主题信息
		return $dir;
	} 
	else 
	{
		return false;
	}
}
#压缩工具,在线压缩
function pclzip_compress( $path_zip, $path_filename )
{
	require('compile/pclzip.lib.php');
	
	if( file_exists( $path_filename ) )
	{
		$archive = new PclZip( $path_zip );		
		$v_list = $archive->create("{$path_filename}");
		if ($v_list != 0) 
		{
            return $v_list;
        }
        else 
        {
        	return false;
        }
	}
	else 
	{
		return false;
	}
}
#压缩工具,在线压缩,去掉部分路径
function pclzip_remove_dir( $path_zip, $path_filename, $remove_dir )
{
	require('compile/pclzip.lib.php');
	
	if( file_exists( $path_filename ) )
	{
		$archive = new PclZip( $path_zip );		
		$v_list = $archive->create("{$path_filename}",PCLZIP_OPT_REMOVE_PATH,"{$remove_dir}");
		if ($v_list != 0) 
		{
            return $v_list;
        }
        else 
        {
        	return false;
        }
	}
	else 
	{
		return false;
	}
}
#汉字转拼音,获取首字母拼音
function getFirstPY($abc)
{
	require('compile/pinyin.php');
	$py = new PinYin();
	return $py->getFirstPY($abc);
}
#汉字转拼音,获取全部拼音
function getAllPY($abc)
{
	require('compile/pinyin.php');
	$py = new PinYin();
	return $py->getAllPY($abc);
}
#获取版本号
function get_version()
{
	$filename = dir_url('subject/version.txt');
	$string = file_get_contents($filename);
	$version = explode('/', str_replace("\n", '', $string));
	if(!empty($version))
	{
		return $version;
	}
	else 
	{
		return '';
	}
}
#获取主题目录
function getThemeDir()
{
	$row = db()->select('id,themename')->from(PRE.'theme')->where(array('addmenu'=>'OFF','flag'=>0))->get()->array_row();
	return $row['themename'];
}
#获取栏目列表静态路径
function getColumnStaticUrl($columnname)
{
	$filename = apth_url('column/'.$columnname.'.html');
	return $filename;
}
#获取文章静态路径
function getArtStaticUrl($staticname)
{
	$filename = apth_url('artic/'.$staticname.'.html');
	return $filename;
}
#获取某个目录下的文件
function getDirFileName($path)
{
	$arr = array();
	if( is_dir($path) )
	{
		$handle = opendir($path);
		while ( ($item = readdir($handle)) !== false )
		{
			if( $item!='.' && $item!='..' )
			{
				$extArr = explode('.', $item);
				if( strtolower(end($extArr)) == 'css' )
				{
					$arr[] = $item;
				}
			}
		}
		closedir($handle);
		return $arr;
	}
	else 
	{
		return $arr;
	}
}
#嵌入页面，分页
function paging($delimiter='')
{
	include Pagecall('static');
	if( false && $data['filter'] == 'ON' && $data['fy'] == 1 )
	{#静态
		$html = '总数：'.ROWS_TOTAL.'　当前'.PAGE.'/'.PAGE_TOTAL.'页　';	
		if(ROWS_TOTAL>=SHOW_TOTAL)
		{
		$html .= '<a href="'.apth_url('paging/list_'.(PAGE-1==0?1:(PAGE-1)).(LIST_ID==""?"":"_".LIST_ID).'.html').'">上一页</a> '.$delimiter;
		$html .= ' <a href="'.apth_url('paging/list_'.(PAGE+1>=PAGE_TOTAL?PAGE_TOTAL:(PAGE+1)).(LIST_ID==""?"":"_".LIST_ID).'.html').'">下一页 </a> &nbsp; ';
		$html .= '<input type="text" size="2" id="GO" name="GO" value=""/> ';
		$html .= "<input type='submit' value='GO' onclick='gotopage()' id='button_go'/>";
		$html .= '
			<script>
			var submitgoval = "'.PAGE_TOTAL.'";
			function gotopage()
			{
				if(document.getElementById("GO").value!="")
				{
					if(!isNaN(document.getElementById("GO").value))
					{
						if(document.getElementById("GO").value>=1&&document.getElementById("GO").value<=submitgoval)
						{
						location.href="'.apth_url('paging/list_').'"+document.getElementById("GO").value+".html";				
						}
					}
				}
			}
			</script>
		';
		}
		return $html;
	}
	else 
	{#动态
		$html = '总数：'.ROWS_TOTAL.'　当前'.PAGE.'/'.PAGE_TOTAL.'页　';	
		if(ROWS_TOTAL>=SHOW_TOTAL)
		{
		$html .= '<a href="'.site_url("?act=article_list&page=".(PAGE-1).(FILED_S==""?"":"&filed=".FILED_S).(LIST_ID==""?"":"&id=".LIST_ID)).'">上一页</a> '.$delimiter;
		$html .= ' <a href="'.site_url("?act=article_list&page=".(PAGE+1).(FILED_S==""?"":"&filed=".FILED_S).(LIST_ID==""?"":"&id=".LIST_ID)).'">下一页 </a> &nbsp; ';
		$html .= '<input type="text" size="2" id="GO" name="GO" value=""/> ';
		$html .= "<input type='submit' value='GO' onclick='gotopage()' id='button_go'/>";
		$html .= '
			<script>
			var submitgoval = "'.PAGE_TOTAL.'";
			function gotopage()
			{
				if(document.getElementById("GO").value!="")
				{
					if(!isNaN(document.getElementById("GO").value))
					{
						if(document.getElementById("GO").value>=1&&document.getElementById("GO").value<=submitgoval)
						{
						location.href="'.site_url("?act=article_list".(FILED_S==""?"":"&filed=".FILED_S).(LIST_ID==""?"":"&id=".LIST_ID)).'&page="+document.getElementById("GO").value;
						}
					}
				}	
			}
			</script>
		';
		}
		return $html;
	}
}
#页面缓存
function PageCache()
{
	$filename = dir_url('subject/'.getThemeDir().'/cache');
	if(is_dir($filename))
	{
		$arr = scandir($filename);

		foreach($arr as $k=>$v)
		{
			if( $v!='.' && $v!='..' )
			{
				$f = $v;
			}
		}
	}
	$path = $filename.'/'.$f;
	#网站设置
	$setreview = db()->select('setTime')->from(PRE.'review_up')->get()->array_row();
	$timing = $setreview['setTime']==''?0:$setreview['setTime'];//设置缓存时间
	$currentTime = time();

	if( is_file( $path ) )
	{
		$fileTime = filectime($path);
		
		if($currentTime >= ($fileTime+$timing))
		{
			unlink($path);
		}
		else 
		{
			$str = file_get_contents($path);
			echo $str;		
			exit();
		}
	}
	
}
#删除目录DIR
function deletedir($dir)
{
	if(is_dir($dir))
	{
		$handle = opendir($dir);
		while( ($item=readdir($handle)) != false )
		{
			if( $item!='.' && $item!='..' )
			{
				$filename = $dir.'/'.$item;
				if(is_file($filename))
				{
					unlink($filename);
				}
				if(is_dir($filename))
				{
					$func = __FUNCTION__;
					$func($filename);
				}
			}
		}
		closedir($handle);
		rmdir($dir);
	}
}
#数据信息,验证用户昵称
function nickname($flag)
{
	session_start();
	
	$ip = getIP();
	
	if(!isset($_SESSION['username']))
	{
		$row = db()->select('name')->from(PRE.'review')->where(array('visitorip'=>$ip,'flag'=>$flag))->get()->array_row();
		$arr = false;
		if( !empty($row) )
		{
			$arr = $row['name'];
		}
	}
	else
	{
		$arr = $_SESSION['username'];
	}
	
	return $arr;
}
#数据信息,验证用户昵称
function nickname2($flag)
{
	session_start();
	
	$ip = getIP();
	
	if(!isset($_SESSION['ApplicationCenterUser']))
	{
		$path = 'system/remotelink.php';
		$filename = SERVICE_LINK.$path;
		#查询数据,服务商
		$json = vcurl($filename,'act=InDataComment&visitorip='.$ip.'&flag='.$flag);
		$rows = json_decode($json,true);
		$arr = $rows[0];
	}
	else
	{
		$arr = $_SESSION['ApplicationCenterUser'];
	}
	
	return $arr;
}
#转换字节单位
function getbyte($byte=0)
{
	$i = 0;
	while ($byte > 1024)
	{
		$byte /= 1024;
		$i++;
	}
	#单位
	$brr = array('B','KB','MB','GB','TB');
	return round($byte,2).$brr[$i];
}
#数值转换
function getInts($int)
{
	if($int<10)
	{
		$int = '0'.$int;
	}
	return $int;
}
########################################################################################################
#数据信息,评论列表
function This_review($id)
{
	$review = db()->select('listtotal,sort')->from(PRE.'review_up')->get()->array_row();
	#开启时生效
	if( !empty($review['listtotal']) )
	{
		$limit = " limit 0,{$review['listtotal']} ";
	}
	$asc = 'desc';
	if( $review['sort'] == 'OFF' )
	{
		$asc = 'asc';
	}
	
	$sql = "select id,pid,likes,name,tal,email,qq,body,pic,FROM_UNIXTIME(publitime) as publitime,visitorip,titleid,vifiy,filter,state from ".PRE."review where state=0 and flag=0 and titleid={$id} order by  publitime {$asc} , likes {$asc} {$limit}";
	$rows = db()->query($sql)->array_rows();
	$all = $rows;
	foreach( $rows as $k => $v )
	{
		if($v['pid'] != 0 )
		{
			$sql = "select id,pid,likes,name,tal,email,qq,body,pic,FROM_UNIXTIME(publitime) as publitime,visitorip,titleid,vifiy,filter,state from ".PRE."review where state=0 and flag=0 and id={$v['pid']} ";
			$row = db()->query($sql)->array_row();
			$all[$k]['chill'] = $row;
		}
	}
	return $all;
}
#数据信息,文章归档
function article_archive()
{
	$rows = db()->select('FROM_UNIXTIME(publitime,"%Y") as year,FROM_UNIXTIME(publitime,"%m") as nomth,count(id) as count')->from(PRE.'article')->group_by('FROM_UNIXTIME(publitime,"%Y"),FROM_UNIXTIME(publitime,"%m")')->order_by('FROM_UNIXTIME(publitime,"%Y") desc,FROM_UNIXTIME(publitime,"%m")  desc')->get()->array_rows();
	return $rows;
}
#数据信息,作者列表
function This_login()
{
	$rows = db()->select('userName,artrows')->from(PRE.'login')->order_by('id desc')->get()->array_rows();
	return $rows;
}
#数据信息,标签列表
function This_tag()
{
	$rows = db()->select('keywords,addmenu')->from(PRE.'tag')->order_by('id desc')->get()->array_rows();
	return $rows;
}
#数据信息,栏目列表
function This_template($sort='asc')
{
	$rows = db()->select('id,name,addmenu')->from(PRE.'template')->order_by("id {$sort}")->get()->array_rows();
	return $rows;
}
#数据信息,基础设置信息表，全局信息
function This_setting()
{
	$mem = new Memcache();
    $mem->connect("127.0.0.1", 11211);
	
    $key = md5( PRE.'setting' );
	
    if( !$mem->get( $key ) )
    {
		$val = db()->select('id,link,title,alias,keywords,description,copyright')->from(PRE.'setting')->get()->array_row();
		
		$mem->set($key, $val, 0, 300);   	
    	$row = $mem->get( $key );
    }
    else 
    {
    	$row = $mem->get( $key );
    }
	
	#当前版本
	$version = get_version();
	$row['version'] = $version[1];
	return $row;
}
#数据信息,锚点分类
function AnchorClass($id,$clid,$limit=5,$img=0)
{
	include Pagecall('static');
	
	if($img==1)
	{#图文
		$andimg = ' and imgurl!="null" ';
	}
	elseif($img==2)
	{#没有图片
		$andimg = ' and imgurl="null" ';
	}
	else 
	{#混合
		$andimg = '';
	}
	
	#查询主题ID
	$theme = db()->select('id')->from(PRE.'theme')->where(array('addmenu'=>'OFF','flag'=>0))->get()->array_row();
	
	$sql = 'select a.id,a.author,FROM_UNIXTIME(a.publitime,"%Y-%m-%d %H:%i") as publitime,a.title,a.alias,a.poslink,a.posflag,a.cover,a.imgurl,a.Thumurl,a.browse,a.description,a.keywords,b.classified from '.PRE.'article as a,'.PRE.'classified as b ';
	$sql .= ' where a.cipid=b.id and b.id='.$clid.$andimg.' and a.id<>'.$id.' and a.timerel<="'.time().'" and a.templateid='.$theme['id'].' order by a.publitime desc limit 0,'.$limit;
	$rows = db()->query($sql)->array_rows();
	
		if(!empty($rows))
		{
			foreach( $rows as $k=>$v )
			{
				if(!strrpos($v['cover'], 'a-ettra01.jpg'))
				{#封面图片不是默认值时,使用封面
					$rows[$k]['img'] = $v['cover'];
				}
				else 
				{#封面图片是默认值时,使用其他图片
					if( $v['imgurl']=='null' || $v['imgurl']=="" )
					{#内容图片不空时使用，默认图片
						$rows[$k]['img'] = apth_url($v['cover']);
					}
					else 
					{
						$rows[$k]['img'] = showImg($v['imgurl'],'rand');
					}
				}
				#多加一个无<html>描述
				$rows[$k]['descriptions'] = subString(strip_tags($v['description']),50);
				#动态链接与静态链接,文章列表
				if( $data['filter'] == 'ON' && $data['lanmu']==1 )
				{#静态链接
					$rows[$k]['link'] = getArtStaticUrl($v['poslink']);
				}
				else 
				{#动态链接
					$rows[$k]['link'] = apth_url('?act=article_content&id='.$v['id']);
				}
			}
		}	
	
	return $rows;
}
#数据信息,锚点栏目
function AnchorColumn($columnmodule,$limit=5,$img=0)
{
	include Pagecall('static');
	
	if($img==1)
	{#图文
		$andimg = ' and imgurl!="null" ';
	}
	elseif($img==2)
	{#没有图片
		$andimg = ' and imgurl="null" ';
	}
	else 
	{#混合
		$andimg = '';
	}
	#查询主题ID
	$theme = db()->select('id')->from(PRE.'theme')->where(array('addmenu'=>'OFF','flag'=>0))->get()->array_row();
	
	$template = db()->select('*')->from(PRE.'template')->where(array('module'=>$columnmodule,'templateid'=>$theme['id']))->get()->array_row();
	if(!empty($template))
	{
		$sql = 'select a.id,a.author,FROM_UNIXTIME(a.publitime,"%Y-%m-%d %H:%i") as publitime,a.title,a.alias,a.poslink,a.posflag,a.cover,a.body,a.imgurl,a.Thumurl,a.description,a.browse from '.PRE.'article as a ';
		$sql .= ' where a.columnid='.$template['id'].$andimg.' and a.timerel<="'.time().'" and a.templateid='.$theme['id'].' order by a.publitime desc limit 0,'.$limit;
		$rows = db()->query($sql)->array_rows();
	}
	else 
	{
		if( $columnmodule == '所有栏目' )
		{
			$sql = 'select a.id,a.author,FROM_UNIXTIME(a.publitime,"%Y-%m-%d %H:%i") as publitime,a.title,a.alias,a.poslink,a.posflag,a.cover,a.body,a.imgurl,a.Thumurl,a.description,a.browse from '.PRE.'article as a ';
			$sql .= ' where a.columnid=0'.$andimg.' and a.timerel<="'.time().'" and a.templateid=6 order by a.publitime desc limit 0,'.$limit;
			$rows = db()->query($sql)->array_rows();
		}		
	}
	if(!empty($rows))
	{
		foreach( $rows as $k=>$v )
		{
			if(!strrpos($v['cover'], 'a-ettra01.jpg')&&$v['cover']!='')
			{#封面图片不是默认值时,使用封面
				$rows[$k]['img'] = $v['cover'];
			}
			else 
			{#封面图片是默认值时,使用其他图片
				if( $v['imgurl']=='null' || $v['imgurl']=="" )
				{#内容图片不空时使用，默认图片
					$rows[$k]['img'] = apth_url($v['cover']);
				}
				else 
				{
					$rows[$k]['img'] = showImg($v['imgurl'],'rand');
				}
			}
			#多加一个无<html>描述
			$rows[$k]['descriptions'] = subString(strip_tags($v['description']),50);
			#动态链接与静态链接,文章列表
			if( $data['filter'] == 'ON' && $data['lanmu']==1 )
			{#静态链接
				$rows[$k]['link'] = getArtStaticUrl($v['poslink']);
			}
			else 
			{#动态链接
				$rows[$k]['link'] = apth_url('?act=article_content&id='.$v['id']);
			}
		}
	}
	return $rows;
}
#数据信息,锚点模块
function AnchorModule($modulename,$int=0,$columnmodule='',$r=5)
{
	#查询数据
	$row = db()->select('id,modulename,filename,htmlid,divorul,body,hiddenmenu,updatepro,sort,flag')->from(PRE.'module')->where(array('filename'=>$modulename))->get()->array_row();
	if( $row['flag'] == 4 || $row['flag'] == 1 )
	{
		if( $row['updatepro'] == 'ON' )
		{
			$modulePaht = apth_url('system/compile/module.php?act='.$row['filename'].'&divorul='.$row['divorul'].'&flag='.$int.'&columnmodule='.$columnmodule.'&r='.$r);
			$str = file_get_contents($modulePaht);
		}
		else 
		{
			$str = null;
		}
	}
	$body = $str==null?$row['body']:$str;
	return $body;
}
#数据信息列表,文章表,列表
function This_article($id=null,$sort='asc',$page=1,$showTotal=10,$cipid=null,$top=0,$tag=null,$author=null,$fileds=null,$title=null,$columnmodule=null)
{
	#查询主题ID
	$theme = db()->select('id')->from(PRE.'theme')->where(array('addmenu'=>'OFF','flag'=>0))->get()->array_row();
	if( $columnmodule != null && !empty($theme) )
	{#通过栏目称名获取栏目ID
		$template = db()->select('id')->from(PRE.'template')->where(array('module'=>$columnmodule,'templateid'=>$theme['id']))->get()->array_row();
		$id=$template['id'];
	}
	#分页
	$array = array(
		'a.id','a.author','FROM_UNIXTIME(a.publitime,"%Y-%m-%d %H:%i") as publitime','a.timerel','a.cipid',
		'a.state','a.top','a.nocomment','a.templateid','a.title','a.posflag','a.cover',
		'a.alias','a.keywords','a.description','a.poslink','a.imgurl',
		'a.Thumurl','a.browse','a.rcpid','b.classified','b.clalias','a.price','a.orprice','a.Sales','a.chain','a.sizetype','a.body',
		'b.addmenu','(select count(id) from '.PRE.'review where titleid=a.id) as verTotal'
	);
	
	$where = ' a.cipid=b.id ';
	$where .= " and a.state=0 and a.timerel<='".time()."' and a.templateid={$theme['id']} ";
	if( $cipid !=null )
	{
		$where .= " and a.cipid={$cipid} ";
	}
	if( !empty($id) )
	{
		$idList = get_columlist_id($id);
		if( !empty($idList) )
		{
			$listsql = ' or a.columnid='.join(' or a.columnid=',$idList);
		}
		$where .= " and (a.columnid={$id} {$listsql}) ";
	}
	$where .= " and (a.top=0 or a.top={$top}) ";
	if( $tag != null )
	{
		$where .= " and a.keywords like '%{$tag}%' ";
	}
	if( $title != null )
	{
		$where .= " and a.title like '%{$title}%' ";
	}
	if( $author != null )
	{
		$where .= " and a.author='{$author}' ";
	}
	if( $fileds != null )
	{
		$where .= " and FROM_UNIXTIME(a.publitime,'%Y-%m')='{$fileds}' ";
	}
	define(FILED_S, $fileds);
	//分页
	$rowstotal = db()->select($array)->from(PRE.'article as a, '.PRE.'classified as b')->where($where)->get()->array_nums();
		
	$pageTotal = ceil($rowstotal/$showTotal);
	if($page>=$pageTotal){$page=$pageTotal;}
	if($page<=1||!is_numeric($page)){$page = 1;}
	$offset = ($page-1)*$showTotal;
	$offset = $offset.','.$showTotal;
	
	define(ROWS_TOTAL, $rowstotal);
	define(SHOW_TOTAL, $showTotal);
	define(PAGE_TOTAL, $pageTotal);
	define(PAGE, $page);	
		
	$rows = db()->select($array)->from(PRE.'article as a, '.PRE.'classified as b')->where($where)->order_by("a.top desc,a.publitime {$sort}")->limit($offset)->get()->array_rows();
	
	return $rows;
}
#服务端处理数据
function server_data($articleList)
{
	include Pagecall('static');
	if(!empty($articleList))
	{
		foreach( $articleList as $k => $v )
		{
			if( time() >= $v['timerel'] )
			{
				#文章置顶
				if($v['top']>0)
				{
					$articleList[$k]['title'] = $v['title'].'[置顶]'; 
				}
				if(!strrpos($v['cover'], 'a-ettra01.jpg')&&$v['cover']!='')
				{#封面图片不是默认值时,使用封面
					$articleList[$k]['img'] = $v['cover'];
				}
				else 
				{#封面图片是默认值时,使用其他图片
					if( $v['imgurl']=='null' || $v['imgurl']=="" )
					{#内容图片不空时使用，默认图片
						$articleList[$k]['img'] = apth_url($v['cover']);
					}
					else 
					{
						$articleList[$k]['img'] = showImg($v['imgurl'],'rand');
					}
				}
				#多加一个无<html>描述
				$articleList[$k]['descriptions'] = subString(strip_tags($v['description']),50);
				#动态链接与静态链接,文章列表
				if( $data['filter'] == 'ON' && $data['lanmu']==1 )
				{#静态链接
					$articleList[$k]['link'] = getArtStaticUrl($v['poslink']);
				}
				else 
				{#动态链接
					$articleList[$k]['link'] = apth_url('?act=article_content&id='.$v['id']);
				}
			}
		}
	}
	return $articleList;
}
#数据信息内容页面,文章表,内容
function This_article_c()
{
	include Pagecall('static');
	
	#内容ID
	$id = trim(htmlspecialchars($_REQUEST['id'],ENT_QUOTES));
	#检测ID是否存在
	$int = db()->select('*')->from(PRE.'article')->where(array('id'=>$id))->get()->array_nums();
	if( $int == 0 )
	{
		header("content-type:text/html;charset=utf-8");
		echo "<script>alert('页面不存在');location.href='".SITE_URL.'/index.php'."'</script>";exit;
	}
	
	#浏览量
	db()->update(PRE.'article', 'browse=browse+1', array('id'=>$id));
	
	#查询主题ID
	$theme = db()->select('id')->from(PRE.'theme')->where(array('addmenu'=>'OFF','flag'=>0))->get()->array_row();
	
	#上一偏
	$up = db()->select('id,title,FROM_UNIXTIME(publitime,"%Y-%m-%d %H:%i") as publitime,poslink,browse,cover,imgurl,Thumurl')->from(PRE.'article')->where('id<'.$id.' and timerel<="'.time().'" and templateid='.$theme['id'])->order_by('id desc')->limit('0,1')->get()->array_row();
	if(!empty($up))
	{
		if(!strrpos($up['cover'], 'a-ettra01.jpg')&&$up['cover']!='')
		{#封面图片不是默认值时,使用封面
			$up['img'] = $up['cover'];
		}
		else 
		{#封面图片是默认值时,使用其他图片
			if( $up['imgurl']=='null' || $up['imgurl']=="" )
			{#内容图片不空时使用，默认图片
				$up['img'] = apth_url($up['cover']);
			}
			else 
			{
				$up['img'] = showImg($up['imgurl'],'rand');
			}
		}
		#动态链接与静态链接,文章列表
		if( $data['filter'] == 'ON' && $data['lanmu']==1 )
		{#静态链接
			$up['link'] = getArtStaticUrl($up['poslink']);
		}
		else 
		{#动态链接
			$up['link'] = apth_url('?act=article_content&id='.$up['id']);
		}
	}
	#下一偏
	$next = db()->select('id,title,FROM_UNIXTIME(publitime,"%Y-%m-%d %H:%i") as publitime,poslink,browse,cover,imgurl,Thumurl')->from(PRE.'article')->where('id>'.$id.' and timerel<="'.time().'" and templateid='.$theme['id'])->limit('0,1')->get()->array_row();
	if(!empty($next))
	{
		if(!strrpos($next['cover'], 'a-ettra01.jpg')&&$next['cover']!='')
		{#封面图片不是默认值时,使用封面
			$next['img'] = $next['cover'];
		}
		else 
		{#封面图片是默认值时,使用其他图片
			if( $next['imgurl']=='null' || $next['imgurl']=="" )
			{#内容图片不空时使用，默认图片
				$next['img'] = apth_url($next['cover']);
			}
			else 
			{
				$next['img'] = showImg($next['imgurl'],'rand');
			}
		}
		#动态链接与静态链接,文章列表
		if( $data['filter'] == 'ON' && $data['lanmu']==1 )
		{#静态链接
			$next['link'] = getArtStaticUrl($next['poslink']);
		}
		else 
		{#动态链接
			$next['link'] = apth_url('?act=article_content&id='.$next['id']);
		}
	}
	$field = 'a.id,a.author,FROM_UNIXTIME(a.publitime,"%Y-%m-%d %H:%i") as publitime,a.timerel,a.cipid,a.posflag,a.cover,';	
	$field .= 'a.state,a.top,a.nocomment,a.templateid,a.title,a.alias,a.columnid,';
	$field .= 'a.keywords,a.description,a.poslink,a.imgurl,a.Thumurl,a.browse,';
	$field .= 'a.rcpid,b.classified,b.clalias,b.addmenu,a.body,(select count(id) from '.PRE.'review where titleid=a.id) as verTotal';
	
	$where = ' a.cipid=b.id ';
	$where .= " and a.id={$id} and a.timerel<='".time()."' and a.templateid={$theme['id']} ";
	$row = db()->select($field)->from(PRE.'article as a,'.PRE.'classified as b')->where($where)->get()->array_row();
	if(!empty($row))
	{
		if(!strrpos($row['cover'], 'a-ettra01.jpg')&&$row['cover']!='')
		{#封面图片不是默认值时,使用封面
			$row['img'] = $row['cover'];
		}
		else 
		{#封面图片是默认值时,使用其他图片
			if( $row['imgurl']=='null' || $row['imgurl']=="" )
			{#内容图片不空时使用，默认图片
				$row['img'] = apth_url($row['cover']);
			}
			else 
			{
				$row['img'] = showImg($row['imgurl'],'rand');
			}
		}
		#多加一个无<html>描述
		$row['descriptions'] = subString(strip_tags($row['description']),50);
		#动态链接与静态链接,文章列表
		if( $data['filter'] == 'ON' && $data['lanmu']==1 )
		{#静态链接
			$row['link'] = getArtStaticUrl($row['poslink']);
		}
		else 
		{#动态链接
			$row['link'] = apth_url('?act=article_content&id='.$row['id']);
		}
	}
	$row['up'] = $next;
	$row['next'] = $up;
	return $row;
}
#面包绡
function get_bread($id)
{
	include Pagecall('static');

	#查询主题ID
	$theme = db()->select('id')->from(PRE.'theme')->where(array('addmenu'=>'OFF','flag'=>0))->get()->array_row();
	
	$sql1 = "select a.id,a.author,FROM_UNIXTIME(a.publitime,'%Y-%m-%d %H:%i') as publitime,a.title,a.alias,a.keywords,a.description,a.body,a.poslink,a.imgurl,a.Thumurl,a.cover,a.browse,a.price,a.orprice,a.Sales,a.chain,a.sizetype";
	$sql1 .= ",b.id as tid,b.pid,b.userid,b.name,b.module,b.keywords as k,b.description as d,b.addmenu,b.cover as c,b.artrows,b.state,b.templateid,c.classified from ".PRE."article as a,".PRE."template as b ,".PRE."classified as c where a.columnid=b.id and a.cipid=c.id ";
	$sql1 .= " and a.templateid={$theme['id']} and a.id={$id} ";	
	$row = db()->query($sql1)->array_row();

	if(!empty($row))
	{
		if(!strrpos($row['cover'], 'a-ettra01.jpg')&&$row['cover']!='')
		{#封面图片不是默认值时,使用封面
			$row['img'] = $row['cover'];
			$row['img_lanmu'] = $row['c'];
		}
		else 
		{#封面图片是默认值时,使用其他图片
			if( $row['imgurl']=='null' || $row['imgurl']=="" )
			{#内容图片不空时使用，默认图片
				$row['img'] = apth_url($row['cover']);
				$row['img_lanmu'] = apth_url($row['c']);
			}
			else 
			{
				$row['img'] = showImg($row['imgurl'],'rand');
			}
		}
		$row['title_lanmu'] = $row['name'];
		$row['type'] = $row['classified'];
		#多加一个无<html>描述
		$row['descriptions'] = subString(strip_tags($row['description']),50);
		#动态链接与静态链接,文章列表
		if( $data['filter'] == 'ON' && $data['lanmu']==1 )
		{#静态链接
			$row['link'] = getArtStaticUrl($row['poslink']);
			$row['link_lanmu'] = getColumnStaticUrl($row['module']);
		}
		else 
		{#动态链接
			$row['link'] = apth_url('?act=article_content&id='.$row['id']);
			$row['link_lanmu'] = apth_url('index.php?act=article_list&id='.$row['tid']);
		}
	}
	return $row;
}
#侧栏模块
function module()
{
	$arr = array('sidebar','sidebar2','sidebar3','sidebar4','sidebar5');
	foreach( $arr as $k => $v )
	{
		$row = db()->select('flags')->from(PRE.'storage')->where(array('name'=>$v))->get()->array_row();
		$string .= $row['flags'];
	}
	$extArr = explode('|', $string);
	array_pop($extArr);
	$rows = array();
 	if( !empty($extArr) )
	{
		foreach( $extArr as $k => $v )
		{
			$row = db()->select('id,modulename,filename,htmlid,divorul,body,hiddenmenu,updatepro,flag')->from(PRE.'module')->where(array('filename'=>$v))->get()->array_row();
			$rows[] = $row;
		}
	}
	$str = module_fu($rows);
	return $str;
}
#屏蔽错误提示
function set_ini_error($flag)
{
	if($flag['development'] == "ON")
	{
		ini_set('display_errors', 'Off');
	}
}
#关闭网站
function CloseSite()
{
	$review = db()->select('closesite')->from(PRE.'review_up')->get()->array_row();
	if( $review['closesite'] == "ON" )
	{
		header("content-type:text/html;charset=utf-8");
		echo '网站已经关闭，无法访问';exit;
	}
}
#浏览次数
function BrowseTimes()
{
	$theme = db()->select('id')->from(PRE.'theme')->where(array('addmenu'=>'OFF','flag'=>0))->get()->array_row();
	$int = db()->select('id')->from(PRE.'browse')->where('FROM_UNIXTIME(publitime,"%Y-%m-%d")="'.date('Y-m-d').'" and templateid='.$theme['id'])->get()->array_nums();
	if( $int == 0 )
	{
		db()->insert(PRE.'browse',array('browse'=>1,'publitime'=>time(),'templateid'=>$theme['id']));
	}
	else 
	{
		db()->update(PRE.'browse', 'browse=browse+1','FROM_UNIXTIME(publitime,"%Y-%m-%d")="'.date('Y-m-d').'" and templateid='.$theme['id']);
	}
	$row = db()->select('browse')->from(PRE.'browse')->where('FROM_UNIXTIME(publitime,"%Y-%m-%d")="'.date('Y-m-d').'" and templateid='.$theme['id'])->get()->array_row();
	return $row['browse'];
}
#异步浏览次数
function AsynBrowse()
{
	$html = '<script>
		$(function(){
			$.post("'.apth_url('system/external_request.php').'",{act:"BrowseClick"},function(d){});
		});
	</script>';
	return $html;
}
#文件安全性验证
function getFileVirify($filename)
{
	require('compile/virifyfile.php');
	
	$str = cFileTypeCheck::getFileType($filename);
	if( $str == 'other' ) 
	{
		return false;
	}
	else 
	{
		return true;
	}
}
#文件上传安全性，白名单
function white_list($needle,$haystack=null)
{
	if(in_array($needle, $haystack))
	{
		return true;
	}
	else 
	{
		return false;
	}
}
#侧栏模块,附加
function module_fu($array)
{
	$html = '';
	if( !empty($array) )
	{
		foreach( $array as $k => $v )
		{
			$html .= '<dl class="function" id="'.$v['htmlid'].'">';
			if( $v['hiddenmenu'] == 'ON' )
			{
				$html .= '<dt class="function_t">'.$v['modulename'].'</dt><dd class="function_c">';
			}
			if( $v['divorul'] == 2 )
			{
				if( $v['flag'] == 4 || $v['flag'] == 1 )
				{
					if( $v['updatepro'] == 'ON' )
					{
						$modulePaht = apth_url('system/compile/module.php');
						$str = vcurl($modulePaht, 'act='.trim($v['filename']).'&divorul='.trim($v['divorul']) );
						$html .= '<ul>'.($str==''?$v['body']:$str).'</ul>';
					}
					else 
					{
						$html .= '<ul>'.$v['body'].'</ul>';
					}
				}
				else 
				{
					$html .= '<ul>'.$v['body'].'</ul>';
				}
			}
			elseif( $v['divorul'] == 1 ) 
			{
				if( $v['flag'] == 4 || $v['flag'] == 1 )
				{
					if( $v['updatepro'] == 'ON' )
					{
						$modulePaht = apth_url('system/compile/module.php');
						$str = vcurl($modulePaht, 'act='.trim($v['filename']).'&divorul='.trim($v['divorul']) );
						$html .= '<div>'.($str==''?$v['body']:$str).'</div>';
					}
					else 
					{
						$html .= '<div>'.$v['body'].'</div>';
					}
				}
				else 
				{
					$html .= '<div>'.$v['body'].'</div>';
				}
			}
			$html .= '</dd></dl>';
		}
	}
	
	return $html;
}
#curl
function vcurl($url, $post = '', $cookie = '', $cookiejar = '', $referer = '') {
	$tmpInfo = '';
	$cookiepath = getcwd () . '. / ' . $cookiejar;
	$curl = curl_init ();
	curl_setopt ( $curl, CURLOPT_URL, $url );
	curl_setopt ( $curl, CURLOPT_USERAGENT, $_SERVER ['HTTP_USER_AGENT'] );
	if ($referer) {
		curl_setopt ( $curl, CURLOPT_REFERER, $referer );
	} else {
		curl_setopt ( $curl, CURLOPT_AUTOREFERER, 1 );
	}
	if ($post) {
		curl_setopt ( $curl, CURLOPT_POST, 1 );
		curl_setopt ( $curl, CURLOPT_POSTFIELDS, $post );
	}
	if ($cookie) {
		curl_setopt ( $curl, CURLOPT_COOKIE, $cookie );
	}
	if ($cookiejar) {
		curl_setopt ( $curl, CURLOPT_COOKIEJAR, $cookiepath );
		curl_setopt ( $curl, CURLOPT_COOKIEFILE, $cookiepath );
	}
	// curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
	curl_setopt ( $curl, CURLOPT_TIMEOUT, 100 );
	curl_setopt ( $curl, CURLOPT_HEADER, 0 );
	curl_setopt ( $curl, CURLOPT_RETURNTRANSFER, 1 );
	$tmpInfo = curl_exec ( $curl );
	if (curl_errno ( $curl )) {
		// echo ' < pre > < b > 错误: < /b><br / > ' . curl_error ( $curl );
	}
	curl_close ( $curl );
	return $tmpInfo;
}
#页面调整用插件,$plugid=插件ID
function Pagecall($plugid)
{	
	$filename = dir_url('subject/plugin/'.$plugid.'/include.php');
	if(is_file($filename))
	{
		return $filename;
	}
	else 
	{
		return dir_url('system/compile/index.php');
	}
}
#页面调整用插件,$plugid=插件ID,文件
function Pagecall_File($plugid,$filename)
{	
	$filename = dir_url('subject/plugin/'.$plugid.'/'.$filename);
	if(is_file($filename))
	{
		return $filename;
	}
	else 
	{
		return dir_url('system/compile/index.php');
	}
}
#页面调整用主题自带插件,$plugid=插件ID
function ThemePagecall($plugid)
{	
	$filename = dir_url('subject/'.$plugid.'/plugin/'.$plugid.'/include.php');
	if(is_file($filename))
	{
		return $filename;
	}
	else 
	{
		return dir_url('system/compile/index.php');
	}
}
#页面调整用主题自带插件,$plugid=插件ID,文件
function ThemePagecall_File($plugid,$filename)
{	
	$filename = dir_url('subject/'.$plugid.'/plugin/'.$plugid.'/'.$filename);
	if(is_file($filename))
	{
		return $filename;
	}
	else 
	{
		return dir_url('system/compile/index.php');
	}
}
#获取主题或插件上架的总数
function getRowsThP()
{
	#查询数据
	$sql = 'select a.id,a.author,a.homepage,a.themeas,a.description,a.themeimg,a.price,a.flag';	
	$sql .= ' from '.PRE.'theme as a';
	$rowsTotal = db()->query($sql)->array_nums();
	return $rowsTotal;
}
#获取订单提交接口
function getOrdersUrl()
{
	return apth_url('system/admin/block_execution.php?act=orders');
}
#获取订单提交接口,异步提交
function getOrdersUrl_asyn($form_id)
{
	$html = '<script>
		$(function(){
			$("#'.$form_id.'").submit(function(){
				$.ajax({
					url:"'.apth_url('system/admin/block_execution.php?act=orders_asyn').'",
					type:$(this).attr("method"),
					data:$(this).serialize(),
					success:function(data){
						if(data == "ok"){
							$("#'.$form_id.'")[0].reset();
							alert("提交成功");
						}else if(data == "on"){
							alert("提交失败");
						}else{
							alert(data);
						}
					}
				});
				return false;
			});
		});
	</script>';
	return $html;
}
#获取留言提交接口
function getMessageUrl()
{
	return apth_url('system/admin/block_execution.php?act=message');
}
#获取留言提交接口,异步提交
function getMessageUrl_asyn($form_id)
{
	$html = '<script>
		$(function(){
			$("#'.$form_id.'").submit(function(){
				$.ajax({
					url:"'.apth_url('system/admin/block_execution.php?act=message_asyn').'",
					type:$(this).attr("method"),
					data:$(this).serialize(),
					success:function(data){
						if(data == "ok"){
							$("#'.$form_id.'")[0].reset();
							alert("提交成功");
						}else if(data == "on"){
							alert("提交失败");
						}else{
							alert(data);
						}
					}
				});
				return false;
			});
		});
	</script>';
	return $html;
}
#获取验证码
function getViryfy()
{
	return '<input type="text" name="mor-virify" id="mor-virify" class="mor-virify" size="4" />
	<img src="'.apth_url('system/virify.php').'" id="ordmess-v" class="ordmess-v" align="absmiddle"/>
	<a href="javascript:;" onclick="document.getElementById(\'ordmess-v\').src = document.getElementById(\'ordmess-v\').src+\'?random=\'+Math.random();"><small>换一张</small></a>';
}
#获取验证码URL
function getVirifyUrl()
{
	return apth_url('system/virify.php');
}
#获取订单接口数据
function getOrderInfo($userid=null,$bool=true,$page=1,$searchArr=null)
{
	if( $userid != null )
	{
		$getip = db()->select('userip')->from(PRE.'orders')->where(array('id'=>$userid))->get()->array_row();
		$rows = db()->select('id,commodity,back,ordernumber,money,phone,email,FROM_UNIXTIME(publitime,"%Y-%m-%d %H:%i") as publitime,address,allinfor,status,userip')->from(PRE.'orders')->where(array('userip'=>$getip['userip']))->order_by('publitime desc')->get()->array_rows();
		if( empty( $rows ) )
		{
			$rows = db()->select('id,commodity,back,ordernumber,money,phone,email,FROM_UNIXTIME(publitime,"%Y-%m-%d %H:%i") as publitime,address,allinfor,status,userip')->from(PRE.'orders')->where(array('id'=>$userid))->order_by('publitime desc')->get()->array_rows();
		}
	}
	else
	{
		if($bool)
		{
		#网站设置
		$setreview = db()->select('rowstotal,searchmaxtotal')->from(PRE.'review_up')->get()->array_row();
		$num = $setreview['rowstotal']==''?10:$setreview['rowstotal'];
		
		$sql = "select id,commodity,back,ordernumber,money,phone,email,FROM_UNIXTIME(publitime,'%Y-%m-%d %H:%i') as publitime,address,allinfor,status,userip from ".PRE."orders ";
		if( $searchArr['ordernumber'] != '' )
		{
			$sql .= " where ordernumber='".trim($arr['ordernumber'])."' ";
		}
		elseif( $searchArr['phone'] != '' )
		{
			$sql .= " where phone='".trim($arr['phone'])."' ";
		}
		elseif( $searchArr['email'] != '' )
		{
			$sql .= " where email='".trim($arr['email'])."' ";
		}
		$sql .= " order by publitime desc ";
		
		$totaRrows = db()->query($sql)->array_nums();
		
		$totalshow = $num;
		$totalpage = ceil($totaRrows/$totalshow);
		if( $page>$totalpage || !is_numeric($page) ){ $page = $totalpage; }
		if( $page<1 ){ $page=1; }
		$offset = ($page-1)*$totalshow;
		
		$rows['totaRrows'] = $totaRrows;
		$rows['totalpage'] = $totalpage;
		$rows['page'] = $page;
		
		$rows['data'] = db()->query($sql)->array_rows();
		}
	}
	
	return $rows;
}
#获取留信接口数据
function getMessageInfo($userid=null,$bool=true,$page=1,$searchArr=null)
{
	if( $userid != null )
	{
		$getip = db()->select('userip')->from(PRE.'message')->where(array('id'=>$userid))->get()->array_row();	
		$rows = db()->select('id,name,age,back,pid,body,phone,email,FROM_UNIXTIME(publitime,"%Y-%m-%d %H:%i") as publitime,status,userip')->from(PRE.'message')->where(array('userip'=>$getip['userip']))->order_by('publitime desc')->get()->array_rows();
		if( empty( $rows ) )
		{
			$rows = db()->select('id,name,age,back,pid,body,phone,email,FROM_UNIXTIME(publitime,"%Y-%m-%d %H:%i") as publitime,status,userip')->from(PRE.'message')->where(array('id'=>$userid))->order_by('publitime desc')->get()->array_rows();
		}
	}
	else
	{
		if($bool)
		{
		#网站设置
		$setreview = db()->select('rowstotal,searchmaxtotal')->from(PRE.'review_up')->get()->array_row();
		$num = $setreview['rowstotal']==''?10:$setreview['rowstotal'];
		
		$sql = "select id,name,age,back,pid,body,phone,email,FROM_UNIXTIME(publitime,'%Y-%m-%d %H:%i') as publitime,status,userip from ".PRE."message ";
		if( $searchArr['phone'] != '' )
		{
			$sql .= " where phone='".trim($arr['phone'])."' ";
		}
		elseif( $searchArr['email'] != '' )
		{
			$sql .= " where email='".trim($arr['email'])."' ";
		}
		elseif( $searchArr['name'] != '' )
		{
			$sql .= " where name='".trim($arr['name'])."' ";
		}
		$sql .= " order by publitime desc ";
		
		$totaRrows = db()->query($sql)->array_nums();
		
		$totalshow = $num;
		$totalpage = ceil($totaRrows/$totalshow);
		if( $page>$totalpage || !is_numeric($page) ){ $page = $totalpage; }
		if( $page<1 ){ $page=1; }
		$offset = ($page-1)*$totalshow;
		
		$rows['totaRrows'] = $totaRrows;
		$rows['totalpage'] = $totalpage;
		$rows['page'] = $page;
		
		$rows['data'] = db()->query($sql)->array_rows();
		}
	}
	
	return $rows;
}
#会员登录，会员 信息接口
function GetUserInfo()
{
	session_start();

	if( isset($_COOKIE['user_login_name']) && $_COOKIE['user_login_name'] !='' )
	{
		$username = $_COOKIE['user_login_name'];  
	}
	else 
	{
		$username = $_SESSION['user_login_name']; 
	}
	
	$row = db()->select("id,username,useras,tel,email,pic,autograph,abstract,powername,power,integral,logintime,FROM_UNIXTIME(publitime,'%Y-%m-%d %H:%i') as publitime,outtime,state")->from(PRE."member")->where(array("username"=>$username))->get()->array_row();
	
	if( !empty($row) )
	{
		return $row;
	}
	else
	{
		return ;
	}
	
}
#会员获取提交接口
function GetUserpost()
{
	return APTH_URL."/subject/plugin/member/act_login.php";
}
#转换时间
function get_day_formt($t)
{
	$int = time()-$t;
	if( $int < 86400 )
	{#秒->分->时 换算
		$i = 0;
		while ( $int >= 60 )
		{
			$int /= 60;
			$i++;
		}
		$ext = array('秒前','分钟前','小时前');
	}
	
	if( $int >= 86400 && $int < 2592000)
	{#时->天 换算
		$i = 0;
		while ( $int >= 86400 )
		{
			$int /= 86400;
			$i++;
		}
		$ext = array('小时前','天前');
	}
	if( $int >= 2592000 && $int < 31104000)
	{#天->月 换算
		$i = 0;
		while ( $int >= 2592000 )
		{
			$int /= 2592000;
			$i++;
		}
		$ext = array('天前','个月前');
	}
	if( $int >= 31104000 )
	{#月->年 换算
		$i = 0;
		while ( $int >= 31104000 )
		{
			$int /= 31104000;
			$i++;
		}
		$ext = array('个月前','年前');
	}
	return floor($int).$ext[$i];
}
#判断当前终端
function isMobile(){ 
    $useragent=isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : ''; 
    $useragent_commentsblock=preg_match('|\(.*?\)|',$useragent,$matches)>0?$matches[0]:'';       
    function CheckSubstrs($substrs,$text){ 
        foreach($substrs as $substr) 
            if(false!==strpos($text,$substr)){ 
                return true; 
            } 
            return false; 
    }
    $mobile_os_list=array('Google Wireless Transcoder','Windows CE','WindowsCE','Symbian','Android','armv6l','armv5','Mobile','CentOS','mowser','AvantGo','Opera Mobi','J2ME/MIDP','Smartphone','Go.Web','Palm','iPAQ');
    $mobile_token_list=array('Profile/MIDP','Configuration/CLDC-','160×160','176×220','240×240','240×320','320×240','UP.Browser','UP.Link','SymbianOS','PalmOS','PocketPC','SonyEricsson','Nokia','BlackBerry','Vodafone','BenQ','Novarra-Vision','Iris','NetFront','HTC_','Xda_','SAMSUNG-SGH','Wapaka','DoCoMo','iPhone','iPod'); 
              
    $found_mobile=CheckSubstrs($mobile_os_list,$useragent_commentsblock) || 
              CheckSubstrs($mobile_token_list,$useragent); 
              
    if ($found_mobile){ //手机
        return true; 
    }else{ //电脑
        return false; 
    } 
}
#跳转终端
function LocationTerminal($url)
{
	if( isMobile() == true )
	{#手机
		header('location:'.$url);
	}
}