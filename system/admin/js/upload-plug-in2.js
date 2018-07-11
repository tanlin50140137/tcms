/**
 * TanLin 2017-10-12 08:59
 * 
 * Tel:18677197764 Email:50140137@qq.com  , 欢迎前来探讨学习
 * 
 * 上传进度条插件
 * 
 */

var InputFileObj = null;/*控件对像*/
var ProgressObj = null;/*进度条对像*/
var PercentObj = null;/*百分比对像*/
	
var AjaxUrl = null;
var Prompt = null;
var PixelSize = null;
	
var colse = null;

var config = null;

function UpTxtFile(fileId,progress,percent,url,text,maxsize,parameter)
{
	config = parameter;
	InputFileObj = document.getElementById(fileId);/*控件对像*/
	ProgressObj = document.getElementById(progress);/*进度条对像*/
	PercentObj = document.getElementById(percent);/*百分比对像*/
	
	AjaxUrl = url;
	Prompt = text;
	PixelSize = maxsize;

	InputFileObj.onchange = function(){
			
		config[3] = document.getElementById('cipid-ling').value;
		if( document.getElementById('pclzip-ling').checked == true ){
			config[4] = 1;
		}else{
			config[4] = 0;
		}
		
		if( config[3] == -1 ){
			document.getElementById('cipid-ling').focus();
			return;
		}
					
		colse = window.setInterval(sendfile,1000)	
	}
	
	document.getElementById('cipid-ling').onchange = function(){
		config[3] = this.value;
		if( document.getElementById('pclzip-ling').checked == true ){
			config[4] = 1;
		}else{
			config[4] = 0;
		}
		if(!(InputFileObj.files && InputFileObj.files[0])){
			InputFileObj.focus();
			return;
		}
		if( config[3] == -1 ){
			this.focus();
			return;
		}
		colse = window.setInterval(sendfile,1000)
	}
}

var GetXMLHttpRequest = {
	
	ObjXHR : function(){	
		var xhr = null;
		if (window.XMLHttpRequest)
		{
			xhr = new XMLHttpRequest();
		}
		else if (window.ActiveXObject)
		{
			xhr = new ActiveXObject("Microsoft.XMLHTTP");
		}
		return xhr;	
	}
}

var xhr = GetXMLHttpRequest.ObjXHR();

var sta = 0;

var sendfile = (function(){
	 const LENGTH = PixelSize;/*每次切10M*/	 
	 var end = sta+LENGTH;
	 var flag = false;/*标志上1块有没有发送完毕*/
	 
	 var blob = null;
	 var fd = null;
	 
	 /*百分比*/
	 var percent = 0;
	 
return (function(){
		if(flag == true){
			 return;
		}
		
		var mov = InputFileObj.files[0];

		/*如果sta>mov.size,就结束了*/
		if(sta>mov.size){
			clearInterval(clock);			
			return;
		}
		
	 	blob = mov.slice(sta,end);
	 	fd = new FormData();
	 	fd.append('file',blob);
	 	fd.append('name',mov.name);
	 	fd.append("size",mov.size);
	 	fd.append("type",mov.type);
	 	fd.append("tar",sta);
	    
	 	fd.append("act",config[0]);
	 	fd.append("op",config[1]);
	 	fd.append("flag",config[2]);
	 	fd.append("cipid",config[3]);
	 	fd.append("pclzip",config[4]);
	 		 	
	 	up(fd);
	 		 	
	 	sta = end;
	 	end = sta+LENGTH;
	 	flag = false;
	 	
	 	percent = 100*(end / mov.size);
	 	var intproval = percent.toFixed(2);
	    
	    if( intproval >= 100 )
	    {
	    	intproval = 100;
	    	
	    	InputFileObj.value='';
	    	sta = 0;
	    }
	 	
	 	ProgressObj.style.width = intproval+'%';
	    PercentObj.innerHTML = (intproval==100?Prompt:intproval+'%');
	 		 		 	
	 });
	 
})();

function up(fd){
	xhr.open('POST',AjaxUrl,false);
	xhr.send(fd);
}