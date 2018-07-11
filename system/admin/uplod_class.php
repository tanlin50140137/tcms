<?php
/**
 * 文件上传
 * @author TanLin
 */
class Uplod_class
{
	private $filepath;//移动路径
	private $srcpath;//默认图片
	private $filedata;
	private $outEorre = array('文件类型不允许','文件大小超出限制');
	public $flag = false;
	public function __construct($filedata,$filepath,$srcpath='')
	{
		$this->filedata = $filedata;
		$this->filepath = $filepath;
		$this->srcpath = $srcpath;		
	}
	public function setEorre($error)
	{
		$this->outEorre = $error;
	}
	public function uplod()
	{
		if($this->error())
		{	
			if(!$this->type())
			{
				echo $this->outEorre[0];exit;
			}
			if($this->size())
			{
				echo $this->outEorre[1];exit;
			}
			$filename = $this->name();
			$this->filepath .= $filename;
			#移动
			if(move_uploaded_file($this->tmp_name(), $this->filepath))
			{
				$this->flag = true;
				return 'pic/'.$filename;
			}
			else 
			{		
				$this->flag = false;		
				return $this->srcpath;
			}
		}
		else 
		{
			$this->flag = false;
			return $this->srcpath;
		}
	}
	public function error()
	{
		return ($this->filedata[__FUNCTION__] == 0);
	}
	public function size()
	{		
		return ($this->filedata[__FUNCTION__] > (1024*1024*2));
	}
	public function type()
	{
		$pic = array('jpg','jpeg','png','gif');
		$haystack = explode('/', $this->filedata[__FUNCTION__]);
		$needle = end($haystack); 
		return (in_array($needle, $pic));
	}
	public function name()
	{
		$haystack = explode('.', $this->filedata[__FUNCTION__]);
		$ext = end($haystack); 
		return uniqid('pic_').'.'.$ext;
	}
	public function tmp_name()
	{
		return $this->filedata[__FUNCTION__];
	}
}