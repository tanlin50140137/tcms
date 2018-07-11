/**
 * TanLin 2017-10-12 08:59
 * 
 * Tel:18677197764 Email:50140137@qq.com  , 欢迎前来探讨学习
 * 
 * 上传进度条插件
 * 
 */

var InputFileObj = null;//控件对像
var ProgressObj = null;//进度条对像
var PercentObj = null;//百分比对像
	
var AjaxUrl = null;
var Prompt = null;
var PixelSize = null;
	
var colse = null;

var config = null;

function UpTxtFile(fileId,progress,percent,url,text,maxsize,parameter)
{
	config = parameter;
	InputFileObj = document.getElementById(fileId);//控件对像
	ProgressObj = document.getElementById(progress);//进度条对像
	PercentObj = document.getElementById(percent);//百分比对像
	
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
					
		colse = window.setInterval(run,1000)	
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
		colse = window.setInterval(run,1000)
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

var tar = 0;

var run = function(){
	
	var upfile = InputFileObj.files[0];
	
	document.getElementById("max-up-box-txt").innerHTML = "大小:"+GetFileSize(upfile.size)+" 类型:"+upfile.type;
	
	var HLENG = PixelSize>upfile.size?upfile.size:PixelSize;
	var len = tar+HLENG;
	var bdol=0;
	var flag=true;
		
	return (function(){
				
		if(flag==false){
			return;
		}
		
		if(len>=upfile.size)
		{					
			window.clearInterval(colse);
		}
		
		upfile = InputFileObj.files[0];	
		bdol = 	upfile.slice(tar,len);

		var form = new FormData();
		    form.append("file",bdol);	
		    form.append("name",upfile.name);
		    form.append("size",upfile.size);
		    form.append("type",upfile.type);
		    form.append("tar",tar);
		    
		    form.append("act",config[0]);
		    form.append("op",config[1]);
		    form.append("flag",config[2]);
		    form.append("cipid",config[3]);
		    form.append("pclzip",config[4]);
		    
			up(form);	
	    
	    tar = len;
	    flag=false;
	    
	    var progres = 100*(len/upfile.size);
	    
	    var intproval = progres.toFixed(2);
	    
	    if( intproval >= 100 )
	    {
	    	intproval = 100;
	    	
	    	InputFileObj.value='';
			tar = 0;
	    }
	    	    
	    ProgressObj.style.width = intproval+'%';
	    /*PercentObj.innerHTML = (intproval==100?Prompt:'已上传:'+GetFileSize(tar)+' 进度:'+intproval+'%');*/
	    if(intproval==100){
	    	
	    	PercentObj.innerHTML = '已上传:'+GetFileSize(len)+' 进度:'+intproval+'%';
	    	
	    	if( arr[2] == 1 ){
	    		window.location.href = "index.php?act=ResourcesMng";
	    	}else{
	    		window.location.href = "index.php?act=TrainingVideo";
	    	}	    	
	    }else{
	    	PercentObj.innerHTML = '已上传:'+GetFileSize(len)+' 进度:'+intproval+'%';
	    }
	    
	})();	
		
}

function up(form)
{
	xhr.open('POST', AjaxUrl, false);
	xhr.send(form);
}

function GetFileSize(filesize)
{
	var i = 0;
	while( filesize > 1024 ){
		filesize /= 1024;
		i++;
	}
	var ext = new Array();
		ext[0] = "B";
		ext[1] = "KB";
		ext[2] = "MB";
		ext[3] = "GB";
		ext[4] = "TB";
		
	return 	filesize.toFixed(2)+ext[i];
}