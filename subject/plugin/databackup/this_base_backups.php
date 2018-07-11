<?php
/**
 * This_cms_system系统数据库备分专用
 * @author TanLin
 * $db = new This_base_backups($host, $user, $pwd, $dbname);
 * $int = $db -> export($directory=''); 导出
 * $int = $db -> import($filename);　导入
 */
class This_base_backups
{
	
	private $host;
	private $user;
	private $pwd;
	public $dbname;
	private $link;
	public $tablename;
	public $filename;
	
	public function __construct($host, $user, $pwd, $dbname)
	{
		ini_set("max_execution_time", "180");
		
		$this->host = $host;	
		$this->user = $user;
		$this->pwd = $pwd;
		$this->dbname = $dbname;
		
		$this->link = mysql_connect($this->host, $this->user, $this->pwd) or exit('数据库连接错误');
		
		$dbint = mysql_select_db($this->dbname);
		
		if( !$dbint )
		{
			mysql_query("create database if not exists `{$dbname}` default character set 'utf8';");
			
			mysql_select_db($dbname );			
		}
		
		mysql_query('set names utf8');
	}
	/**
	 * 执行导出
	 * $directory,备份目录，后面加“/”
	 */
	public function export($directory='')
	{
		$this->get_tablename();
		
		$data = $this->get_create_data();
		
		$str = array_values($data);
		
		$datastr = join(" ", $str);
		
		$this->filename = date('YmdHis').'.sql';
		
		$int = file_put_contents(dir_url($directory.$this->filename), $datastr);
		
		return $int;
		
	}
	/**
	 * 执行导入
	 * $filename,文件名或某目录下的文件
	 */
	public function import($filename)
	{
		$filenames = dir_url($filename);
	
		if(file_exists($filenames))
		{
			$str = file_get_contents($filenames);
			
			$string = str_replace(array("\n", "%dbname%", "%400%"), array('', $this->dbname, PRE), $str);
		
			$arr = explode('#777#', $string);
			if( !empty($arr) )
			{
				foreach( $arr as $k => $v )
				{
					$int = mysql_query($v) or exit('sql语法错误 '.mysql_errno()."  <br/>\n\n  ".mysql_error()." <br/>\n\n ".$v);
				}
			}
		}
		
		return $int;
	}
	/**
	 * 获取表名
	 */
	public function get_tablename()
	{
		$result = mysql_list_tables($this->dbname);
		while ($row = mysql_fetch_row($result))
		{
			$rows[] = $row;
		}
		$this->tablename = $rows;
	}
	/**
	 * 获取路径
	 */
	public function get_dir_url()
	{
		$string = $_SERVER['REQUEST_URI'];
		
		$arr = explode('/', $string);
		
		$dirpath = $_SERVER['DOCUMENT_ROOT'].'/'.($arr[1]==''?'':$arr[1].'/');
		
		return $dirpath;
	}
	/**
	 * 获取表结构并表数据
	 */
	function get_create_data()
	{
		$data[] = "create database if not exists `%dbname%` default character set 'utf8';";
		if(!empty($this->tablename))
		{
			foreach($this->tablename as $v)
			{	
				$result = mysql_query("show create table ".$v[0]);
				
				while ($arr = mysql_fetch_row($result))
				{
					$a[] = $arr;
				}
			}
		}
			
		if(!empty($a))
		{
			foreach($a as $v)
			{
				$result = mysql_query('select * from '.$v[0]);
				$data[] = "\n#777#\ndrop table if exists `".str_replace(PRE, "%400%", $v[0])."`;\n#777#\n".str_replace(PRE, "%400%", $v[1]).";";
				
				while ($input = mysql_fetch_row($result))
				{
					$pieces = array_values($input);		
					$string = "\n#777#\nINSERT INTO `".str_replace(PRE, "%400%", $v[0])."` VALUES ('".str_replace(array('"',"\n"), array('\"',''), join("','", $pieces))."');";	
					$data[] = $string;
				}
				
			}
		}
		return $data;
	}
}