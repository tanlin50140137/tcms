<?php 
/**
 * 生成验证码
 * @author TanLin
 *
 */
class Virify
{
	/**
	 * 宽
	 * @var unknown_type
	 */
	protected $width = NULL;
	/**
	 * 高
	 * @var unknown_type
	 */
	protected $height = NULL;
	/**
	 * 字体目录
	 * @var unknown_type
	 */
	protected $font = NULL;
	/**
	 * 构造
	 * @param unknown_type $w
	 * @param unknown_type $h
	 */
	public function __construct($w,$h,$font)
	{
		$this->width = $w;
		$this->height = $h;
		$this->font = $font;
	}
	/**
	 * 随机获取一个字符
	 * @return string
	 */
	protected function getTxt()
	{
		$txt = str_shuffle('abcdefghjkmnpqrstuvwxyz23456789ABCDEFGHJKMNOPQRSTUVWXYZ');
		$getOnce = $txt[mt_rand(0, strlen($txt)-1)];
		return $getOnce;
	}
	/**
	 * 随机获取一个字体族科
	 * @return string
	 */
	protected function getFont()
	{
		$fonts = array("SEGOESCB.TTF","FZSTK.TTF","STXINGKA.TTF","STCAIYUN.TTF","SIMYOU.TTF","MSYH.TTF");
		$fontName = $fonts[mt_rand(0, count($fonts)-1)];
		return $fontName;
	}
	/**
	 * 执行生成GIF图片
	 */
	public function createImage()
	{
		header("content-type:image/gif");
		session_start();
		$LoadeImage = imagecreatetruecolor($this->width, $this->height);
		$cL2 = imagecolorallocate($LoadeImage, 255, 255, 255);
		imagefill($LoadeImage, 0, 0, $cL2);
		
		for($s=0;$s<=50;$s++)
		{
			$cL3 = imagecolorallocate($LoadeImage, mt_rand(80, 100), mt_rand(80, 100), mt_rand(80, 100));
			imagesetpixel($LoadeImage, mt_rand(10, 90), mt_rand(20, 30), $cL3);
		}
		
		$viTxt = "";
		$font = $this->font.'/'.$this->getFont();
		for($i=0;$i<4;$i++)
		{
			$cL1 = imagecolorallocate($LoadeImage, mt_rand(100, 190), mt_rand(100, 190), mt_rand(100, 190));
			$text = $this->getTxt();
			$viTxt .= $text;
			imagettftext($LoadeImage, 16, mt_rand(-15, 15), ($this->width)/10+15*$i, ($this->height)/2+5, $cL1, $font, $text);
		}
		$_SESSION['virify'] = $viTxt;
		imagegif($LoadeImage);
		imagedestroy($LoadeImage);
	}	
}
$Virify = new Virify(75,30,"font");
$Virify->createImage();
?>