<?php
//include "TopSdk.php";
//include "config/config.php";
/**
 * 
 * 阿里大鱼，短信接口
 * TanLin 2017-10-31 Tel:18677197764 Email:50140137@qq.com
 * 
 * 调用：
 * $ali = new Alidayu_message();
 * 
 * 参数：
 * $tel,手机号码
 * $smsparam,内容数
 * $smsTemplateCode,模板ID
 * $data1 = $ali->Send($tel,$smsparam,$smsTemplateCode);发送短信接口
 * 
 * -------------------------------------------------------------------
 * 
 * 参数：
 * $tel,手机号码
 * $bizid,上次验证码Modle值
 * $smsTemplateCode,短信模板
 * $querydate,查询时间,时间格式：年月日-yyyymmdd
 * $virily,手机获取验证码
 * $data2 = $ali->Query($tel,$bizid,$smsTemplateCode,$querydate,$virily);查询短信接口
 * 
 * 备注说明：短信模板及传参方法，请在 config/config.php 配置文件中查看。
 * 
 * */
class Alidayu_message
{
	/**
	 * 阿里大鱼Appkey
	 * @var string
	 */
	private $appkey = '23311532';
	/**
	 * 阿里大鱼SecretKey
	 * @var string
	 */
	private $secretKey = 'c6da93fe298d705e69b0c4ee252e01c4';
	/**
	 * 短信模板编号
	 * @var unknown_type
	 */
	private $SmsTemplateCode = 'SMS_6807951';
	/**
	 * 模板样式
	 * @var string
	 */
	private $SmsType = 'normal';
	/**
	 * 短信签名
	 * @var string
	 */
	private $SmsFreeSignName = '火天信工程网';
	/**
	 * 文本，选填
	 * @var string
	 */
	private $Extend = '';
	private $BizId;
	private $QueryDate;
	private $RecNum;
	private $SmsParam;
		
	/**
	 * 设置接收短信手机号码
	 * @param string $tel
	 */
	public function setTel($tel)
	{
		return $tel;
	}
	/**
	 * 设置验证码，自定义
	 * @param string $code
	 */
	public function setSmsParam($smsparam)
	{
		return $smsparam;
	}
	/**
	 * 短信模板
	 * @param string $SmsTemplateCode
	 * @return string
	 */
	public function setTemplateCode($SmsTemplateCode)
	{
		return $SmsTemplateCode;
	}
	/**
	 * 设置短信模板，model值
	 * @param string $bizid
	 * @return string
	 */
	public function setBizId($bizid)
	{
		return $bizid;
	}
	/**
	 * 设置短信查询时间
	 * @param string $querydate
	 * @return string
	 */
	public function setQueryDate($querydate)
	{
		return $querydate;
	}
	/**
	 * 验证签名
	 * @return TopClient
	 */
	private function getTopClient()
	{
		$c = new TopClient;
		$c ->appkey = $this->appkey;
		$c ->secretKey = $this->secretKey;
		
		return $c;
	}
	/**
	 * 配置短信发送
	 * @return AlibabaAliqinFcSmsNumSendRequest
	 */
	private function setSendRequest()
	{
		$req = new AlibabaAliqinFcSmsNumSendRequest;
		$req ->setExtend( $this->Extend );
		$req ->setSmsType( $this->SmsType );
		$req ->setSmsFreeSignName( $this->SmsFreeSignName );
		$req ->setSmsParam( $this->SmsParam );
		$req ->setRecNum( $this->RecNum );
		$req ->setSmsTemplateCode( $this->SmsTemplateCode );
		
		return $req;
	}
	/**
	 * 配置短信查询
	 * @return AlibabaAliqinFcSmsNumQueryRequest
	 */
	private function setQueryRequest()
	{
		$req = new AlibabaAliqinFcSmsNumQueryRequest;
		$req ->setBizId( $this->BizId );
		$req ->setRecNum( $this->RecNum );
		$req ->setQueryDate( $this->QueryDate );
		$req ->setCurrentPage( "1" );
		$req ->setPageSize( "10" );
		
		return $req;
	}
	/**
	 * 发送短信验证
	 * @param string $tel,接收手机人手机号码
	 * @param string $smsparam，设置参数
	 * @param string $smsTemplateCode，模板ID
	 * @return mixed
	 */
	public function Send($tel,$smsparam,$smsTemplateCode)
	{	
		$this->RecNum = $this->setTel($tel);
		$this->SmsParam = $this->setSmsParam($smsparam);
		$this->SmsTemplateCode = $this->setTemplateCode($smsTemplateCode);
		
		$c = $this->getTopClient();
		$req = $this->setSendRequest();
		$resp = $c ->execute( $req );
		
		if($resp->result->err_code == 0 && $resp->result->msg == 'OK' )
		{
			$arr['model'] = (string)$resp->result->model;
			$arr['msg'] = (string)$resp->result->msg;
			$arr['success'] = (string)$resp->result->success;
			$arr['err_code'] = (string)$resp->result->err_code;									
			return $arr;
		}
		else 
		{
			return $resp;
		}			
	}
	/**
	 * 短信查询接口
	 * @param string $bizid，modle值
	 * @param string $tel,手机号码
	 * @param string $smsTemplateCode,模板ID
	 * @param string $querydate,时间如：20171031
	 * @param string $virily，验证码
	 * @return bool
	 */
	public function Query($tel,$bizid,$smsTemplateCode,$querydate,$virily)
	{
		$this->RecNum = $this->setTel($tel);
		$this->BizId = $this->setBizId($bizid);
		$this->SmsTemplateCode = $this->setTemplateCode($smsTemplateCode);
		$this->QueryDate = $this->setQueryDate($querydate);		
		
		$c = $this->getTopClient();
		$req = $this->setQueryRequest();
		$resp = $c ->execute( $req );
		
		$bool = strrpos((string)$resp->values->fc_partner_sms_detail_dto->sms_content, $virily);
		
		return $bool;
	}
}
/*使用案例
$ali = new Alidayu_message();

echo "发短信<br/>";
$tel = '12377197764';
$smsparam = "{code:'".mt_rand(10000, 99999)."',product:'火天信资料库'}";
$getData1 = $ali->Send($tel,$smsparam,$smsTemplateCode[4]);
print_r($getData1);
echo '<br/>';

echo "查询短信<br/>";
$tel = '12377197764';
$bizid = '317015409434649980^0';
$querydate = date('Ymd');
$getData2 = $ali->Query($tel,$bizid,$smsTemplateCode[4],$querydate,'58728');
var_dump($getData2);
*/