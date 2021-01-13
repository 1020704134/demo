//十六进制颜色随机
var vColorRandomArray = ["#000000","#003300","#006600","#009900","#00CC00","#330000","#333300","#336600","#339900","#33CC00","#660000","#663300","#666600","#669900","#66CC00","#990000","#993300","#996600","#999900","#CC0000","#CC6600","#CC9900","#FF0000","#FF3300","#FF6600","#FF9900","#000066","#003366","#006666","#009966","#00CC66","#330066","#333366","#336666","#339966","#33CC66","#660066","#663366","#666666","#669966","#66CC66","#990066","#996666","#999966","#CC0066","#CC3366","#CC6666","#CC9966","#FF0066","#FF6666","#FF9966","#0000CC","#0066CC","#0099CC","#00CCCC","#3300CC","#3366CC","#3399CC","#33CCCC","#6600CC","#6666CC","#6699CC","#66CCCC","#9900CC","#9966CC","#9999CC","#CC00CC","#CC66CC","#CC99CC","#FF00CC","#FF66CC"];
//颜色数组
var vColorArray = new Array();
var vBrowserViewType = "PC";

//Fly标准理念实现 1.0.1
fly = {
	
	//--- 判断区 ---
	
	//判断:为空判断
	isNull:function(fpValue){
		if(fpValue==""||fpValue==null||fpValue==undefined||fpValue=="undefined"){
			return true;
		}
		return false
	},
	
	//判断:为none判断
	isNone:function(fpValue){
		if(fpValue=="none"){return true;}
		return false
	},	
	
	//判断为空或为none
	isNullNone:function(value){
		if(value==""||value==null||value==undefined||value=="none"){
			return true;
		}
		return false
	},
	
	//判断:是否是数值
	isNumber:function(fpValue){
		var patrn = /^(-)?\d+(\.\d+)?$/;
	    if (patrn.exec(fpValue) == null || fpValue == "") {
	        return false
	    } else {
	        return true
	    }
	},	
	
	//判断:是否是Json
	isJson:function(fpString){
	    if(typeof fpString == 'string'){
	        try {
	            var obj=JSON.parse(fpString);
	            if(typeof obj == 'object' && obj != null){
	                return true;
	            }else{
	                return false;
	            }
	
	        } catch(e) {
	            return false;
	        }
	    }
	    return false;
	},
	
	//判断值返回 不同颜色的 span 标签
	span:function(fpJudge,fpGreenText,fpRedText){
		if(fpJudge){
			return "<span style=\"color:green;\">"+fpGreenText+"</span>";	
		}else{
			return "<span style=\"color:red;\">"+fpRedText+"</span>";	
		}
	},

	//--- 值处理区 ---
	valueNull:function(fpString,fpValue){
		if(fly.isNull(fpString)){
			if(!fly.isNull(fpValue)){
				return fpValue;	
			}
			return "";
		}
		return fpString;
	},
	
	slashLeftToRight:function(fpString){
    	return fpString.replace(/\\/ig,"/");
	},
	
	//--- 获取区 ---
	//获取字符长度，汉字为2位
	getLength:function(str){  
		var slength=0;  
		for(i=0;i<str.length;i++){  
			if ((str.charCodeAt(i)>=0) && (str.charCodeAt(i)<=255)){
				slength = slength+1;  
			}else{  
				slength = slength+2;
			}	
		}   
		return slength;  
	},
	
	//获取URL:获取当前主域名
	getUrlHost:function(){
		return window.location.protocol+'//'+window.location.host;
	},	
	
	//获取URL:获取当前主网址
	getUrl:function(){
		return window.location.protocol+'//'+window.location.host+window.location.pathname;
	},
	
	//获取参数:获取地址参数值（无中文乱码）---
	getParameter:function(fpName) {
		var reg = new RegExp("(^|&)" + fpName + "=([^&]*)(&|$)", "i");
		var reg = new RegExp("(^|&)" + fpName + "=([^&]*)(&|$)", "i");
	  	var parameter = window.location.search.substr(1).match(reg);
	  	if(parameter != null){
	  		return unescape(decodeURI(parameter[2]));
	  	}
	  	return "";
	},
	
	//获取当前日期:0000-00-00
    getTimeDate:function() {
        var date = new Date();
	    var month = date.getMonth() + 1;
	    var strDate = date.getDate();
	    if (month >= 1 && month <= 9) {month = "0" + month;}
	    if (strDate >= 0 && strDate <= 9) {strDate = "0" + strDate;}
	    var currentdate = date.getFullYear() + "-" + month + "-" + strDate;
	    return currentdate;
    },
	
	//获取当前格式时间
	getTimeNow:function() {
	    var date = new Date();
	    var month = date.getMonth() + 1;
	    var strDate = date.getDate();
	    var hour = date.getHours();
	    var minute = date.getMinutes();
	    var seconds = date.getSeconds();
	    if (month >= 1 && month <= 9) {month = "0" + month;}
	    if (strDate >= 0 && strDate <= 9) {strDate = "0" + strDate;}
	    if (hour < 10) {hour = "0" + hour;}    
	    if (minute < 10) {minute = "0" + minute;}    
	    if (seconds < 10) {seconds = "0" + seconds;}
	    var currentdate = date.getFullYear() + "-" + month + "-" + strDate + " " + hour + ":" + minute + ":" + seconds;
	    return currentdate;
	},
	
	/**
	 * 获取时间戳
	 * 不传任何参数时,默认获取当前时间戳
	 * key:时间戳关键字
	 * number:随机数数量
	 * */
	getTimestamp:function(fpKey,fpNumber){
		if(fly.isNull(fpNumber)){fpNumber = 0;}
		var numberString = "";
		for(var i=0;i<fpNumber;i++){
			numberString += "" + Math.floor(Math.random()*10); 
		}
		return Math.round(new Date())+fpKey+numberString;
	},	
	
	//获取中间文本
	getStringCenter:function(fpString,fpFindStart,fpFindEnd){
		var vFindStart = fpFindStart;
		var vFindEnd = fpFindEnd;
		var vStartSub = fpString.indexOf(vFindStart);
		if(vStartSub>=0){vStartSub+=vFindStart.length;}
		var vEndSub = fpString.indexOf(vFindEnd,vStartSub);
		return fpString.slice(vStartSub,vEndSub);
	},
	
	//--- 请求区 ---
	
	/**
	 * 常用方法:同步SessionAjax请求
	 * fpAccessUrl：请求地址
	 * fpParameter：请求参数
	 * functionSuccess：成功处理函数
	 * requestType：get、post
	 * asyncBo：false（同步）、true（异步）
	 * */
	ajax:function(fpAccessUrl,fpParameter,functionSuccess,requestType,asyncBo,dataType){
		//数据默认值
		if(fly.isNull(dataType)){dataType="json";}
		if(asyncBo==true||asyncBo=="true"){asyncBo=true;}else{asyncBo=false;}
		if(requestType!="post"){requestType='get';}
		if(requestType=="get"){
			fpAccessUrl = fpAccessUrl + fpParameter;
		}
		//Ajax请求
		$.ajax({
			url:fpAccessUrl,
			type: requestType,
			xhrFields: {withCredentials: true},		//同步Session
			crossDomain: true,
			contentType: 'application/json; charset=utf-8',
			async: asyncBo,
			data: fpParameter,
			dataType:dataType,
			success:function(result){
				functionSuccess(result);
			},
		});
	},
	
	
	//--- 处理区 ---
	
	/**
	 * 替换 文本中需要替换的字符
	 * 
	 * */
	stringReplace:function(fpString,fpFindString,fpReplaceString){
		return fpString.replace(new RegExp(fpFindString,"g"),fpReplaceString);
	},
	
	//替换 文本所有换行:用于处理返回数据对于的换行内容
	stringReplaceLine:function(fpString){
		return fpString.replace(new RegExp(/[\r\n]/g,"g"),"");
	},
	
	//替换 文本所有空格:用于处理字符串的前后空白
	stringReplaceSpace:function(fpString){
		return fpString.replace(/(^\s*)|(\s*$)/g,"");
	},
	
	//删除最后一个文本
	stringDeleteLast:function(fpString,fpNumber){
		if(fly.isNull(fpNumber)){fpNumber=1;}
		return fpString.substring(0,fpString.length-fpNumber);
	},
	
	//字符串到大写
	stringToUpper:function(str){
		return str.toUpperCase();
	},
	
	//字符串到小写
	stringToLower:function(str){
		return str.toLowerCase();
	},	
	
	//替换 首写字母大写
  	stringFirstToUpper:function(fpString){
  		var strResult="";
		for (var i=0;i<fpString.length;i++) {
			var strChar = fpString.charAt(i);
			if (i==0){strResult = strChar.toUpperCase();}else{strResult+= strChar;}
		}	
  		return strResult;
  	},
	
	
	//--- 判断区 ---	
	
	//判断 正则是否匹配成功
	judgeStringRegular:function(fpString,fpReplace){
		if(new RegExp(fpReplace).test(fpString)) {
	        return true;
	    }else{
	       return false;
	    }
	},


	//--- 转码区 ---
	
	
	/**
	 * HTML数据编码
	 * */
	dataEncode:function(fpData,fpSpaceBo){
		
		fpData = fpData.replace(/[\n\r]/ig,'^nr;');		//Nextline Return
		
		if(fly.isNull(fpSpaceBo)||!fpSpaceBo){
			fpData = fpData.replace(/\s+/g, ' ');	//HTML Entity	
		}
		
		fpData = fpData.replace(/&/ig,'^amp;');			//HTML Entity
		fpData = fpData.replace(/\//ig,'^sla;');		//Slash
		fpData = fpData.replace(/\\/ig,'^bsl;');		//Backslash
		fpData = fpData.replace(/\"/ig,'^quot;');		//HTML Entity
		fpData = fpData.replace(/\'/ig,'^apos;');		//HTML Entity
		fpData = fpData.replace(/\{/ig,'^lcb;');		//left curly brace
		fpData = fpData.replace(/\}/ig,'^rcb;');		//right curly brace
		fpData = fpData.replace(/\[/ig,'^lb;');			//left bracket
		fpData = fpData.replace(/\]/ig,'^rb;');			//right bracket
		fpData = fpData.replace(/\$/ig,'^usd;');		//United States Dollar
		fpData = fpData.replace(/\,/ig,'^com;');		//comma
		
		//fpData = fpData.replace(/</ig,'^lt;');			//less than
		//fpData = fpData.replace(/>/ig,'^gt;');			//greater than
		//fpData = fpData.replace(/\(/ig,'^lp;');			//left parenthesis
		//fpData = fpData.replace(/\)/ig,'^rp;');			//right parenthesis
		fpData = fpData.replace(/\?/ig,'^qm;');			//Question mark
		fpData = fpData.replace(/:/ig,'^col;');			//Colon
		fpData = fpData.replace(/=/ig,'^eq;');			//Equal
		return fpData;
	},

	/**
	 * HTML数据转码
	 * */
	dataDecode:function(fpData){
		
		fpData = fpData.replace(/\^apos;/ig,'\'');		//HTML Entity
		fpData = fpData.replace(/\^quot;/ig,'"');		//HTML Entity
		fpData = fpData.replace(/\^bsl;/ig,'\\');		//Backslash
		fpData = fpData.replace(/\^sla;/ig,'/');		//Slash
		fpData = fpData.replace(/\^nr;/ig,'\n');		//Nextline Return
		fpData = fpData.replace(/\^lb;/ig,'[');			//left bracket 
		fpData = fpData.replace(/\^rb;/ig,']');			//right bracket 
		fpData = fpData.replace(/\^lcb;/ig,'{');		//left curly brace
		fpData = fpData.replace(/\^rcb;/ig,'}');		//right curly brace
		fpData = fpData.replace(/\^usd;/ig,'$');		//United States Dollar
		fpData = fpData.replace(/\^com;/ig,',');		//comma
		
		//fpData = fpData.replace(/\^lt;/ig,'<');			//less than
		//fpData = fpData.replace(/\^gt;/ig,'>');			//greater than
		//fpData = fpData.replace(/\^lp;/ig,'(');			//left parenthesis
		//fpData = fpData.replace(/\^rp;/ig,')');			//right parenthesis
		fpData = fpData.replace(/\^qm;/ig,'?');			//Question mark
		fpData = fpData.replace(/\^col;/ig,':');		//Colon
		fpData = fpData.replace(/\^eq;/ig,'=');			//Equal 
		
		fpData = fpData.replace(/\^amp;/ig,'&');		//HTML Entity
		
		return fpData;
	},
	
	/**
	 * JS数据HTML格式化
	 * */
	dataJsonFormat:function(data){
		
		var code = "";
		var space = "";
		
		for(var i=0;i<data.length;i++){
			var c=data.charAt(i);
			if(c=="{"||c=="["){
				space += "&nbsp;&nbsp;&nbsp;&nbsp;";
			}else if(c=="}"||c=="]"){
				if(!fly.isNull(space)){
					space = space.substring(0,space.length-24);
				}
			}
			
			if(c==","){
				code += c+"<br/>"+space;
			}else if(c=="{"){
				code += c+"<br/>"+space;
			}else if(c=="}"){
				code += "<br/>"+space+c;
			//}else if(c=="["){
				//code += "<br/>"+space+c+"<br/>"+space;	
			}else{
				code += c;	
			}
　　　　	}
		
		//code = code.replace(/\[+\<br\/\>(&nbsp;)+/ig,'123');
		
		return code;
	},	
	
	
	//--- 视图区 ---
	
	//视图:隐藏或显示
	viewHideShow:function(element){
		if($(element).is(':hidden')){
			$(element).show(300);
		}else{
			$(element).hide();
		}
	},
	
	//视图:隐藏或显示按钮
	viewHideShowButton:function(element){
		var content = $(element).text(); 
		if(content.search("显示")>0){
			content = fly.stringReplace(content,"显示","隐藏");
		}else if(content.search("隐藏")>0){
			content = fly.stringReplace(content,"隐藏","显示");
		}
		$(element).text(content);
	},
	
	//范围随机数
	getRange:function (fpMin,fpMax){
		var Range = fpMax - fpMin;
		var Rand = Math.random();
		var num = fpMin + Math.round(Rand * Range); //四舍五入
		return num;
	},
			
	//十六进制颜色随机
	getColor:function(){
		var r = fly.getRange(0,255);
		var g = fly.getRange(108,220);
		var b = fly.getRange(108,225);
		var color = '#'+r.toString(16)+g.toString(16)+b.toString(16);
		return color;
	},
	
	//获取随机颜色
	getColorRandom:function(){
		var vRandom = fly.getRange(0,vColorRandomArray.length);
		var vColor = vColorRandomArray[vRandom]; 
		vColorRandomArray.splice(vRandom,1);
		return vColor;
	},
	
	//获取角色随机颜色
	getColorArray:function(fpRoleType){
		var vColor,vMemberArray;
		//当数组为空时直接添加值颜色
		if(vColorArray.length==0){
			vColor = fly.getColorRandom();
			vColorArray.push(fpRoleType+":"+vColor);
			return vColor;
		}
		//判断Key并返回值对应的颜色
		for(var i=0;i<vColorArray.length;i++){
			vMemberArray = vColorArray[i].split(":");
			if(fpRoleType==vMemberArray[0]){
				return vMemberArray[1];
			}
		}
		//生成新的颜色并进行返回
		vColor = fly.getColorRandom();
		vColorArray.push(fpRoleType+":"+vColor);
		return vColor;
	},
	
	//获取文件名
    getFileName:function(fpFile){
        vFileSub = fpFile.lastIndexOf("\\");
        if(vFileSub > 0){
            fpFile = fpFile.substring(vFileSub+1);
        }
        vFileSub = fpFile.lastIndexOf("/");
        if(vFileSub > 0){
            fpFile = fpFile.substring(vFileSub+1);
        }
        vFileSub = fpFile.lastIndexOf(".");
        if(vFileSub > 0){
            fpFile = fpFile.substring(0,vFileSub);
        }
        return fpFile;
    },
    
    //去除首尾空格
	replaceHeadAndTailSpace:function(fpString){
		fpString = fpString.replace(/[\r\n]/g,"");
		fpString = fpString.replace(/^\s+/g,"");
		fpString = fpString.replace(/\s+$/g,"");
		return fpString;
	},
	
	//显示关键字
	searchKey:function(fpValue,fpKey){
		fpValue = fpValue.toString();
		var vReplaceString = new RegExp(fpKey);
		return fpValue.replace(vReplaceString,"<span style=\"background-color:#fffa62;\">"+fpKey+"</span>");
	},

	//处理文字:Textarea
	handleTextarea:function(fpText){
		return fpText.replace(new RegExp(/[\r\n]/g,"g"),"<br/>");
	},
	
	//处理斜杠:\
	handleSlash:function(fpText){
		return fpText.replace(new RegExp(/\\/g,"g"),"/");
	},
	
	//获取文字长度:中文两个长度
	getByteLength:function(fpText) {
        var len = 0;
        for(var i=0;i<fpText.length;i++) {
           	var length = fpText.charCodeAt(i);
           	if(length>=0&&length<=128){
                len += 1;
            }else{
                len += 2;
            }
        }
        return len;
    },

	//浏览器类型判断:手机/电脑
	browserTypeJudge:function(){
		var sUserAgent = navigator.userAgent.toLowerCase();
		var bIsIpad = sUserAgent.match(/ipad/i) == "ipad";
		var bIsIphoneOs = sUserAgent.match(/iphone os/i) == "iphone os";
		var bIsMidp = sUserAgent.match(/midp/i) == "midp";
		var bIsUc7 = sUserAgent.match(/rv:1.2.3.4/i) == "rv:1.2.3.4";
		var bIsUc = sUserAgent.match(/ucweb/i) == "ucweb";
		var bIsAndroid = sUserAgent.match(/android/i) == "android";
		var bIsCE = sUserAgent.match(/windows ce/i) == "windows ce";
		var bIsWM = sUserAgent.match(/windows mobile/i) == "windows mobile";
		if (!(bIsIpad || bIsIphoneOs || bIsMidp || bIsUc7 || bIsUc || bIsAndroid || bIsCE || bIsWM) ){
			vBrowserViewType = "PC";
		}else{
			vBrowserViewType = "WAP";
		}
	},


}

		

fly.browserTypeJudge();		//判断浏览器访问类型

		