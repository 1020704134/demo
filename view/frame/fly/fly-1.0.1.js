//Fly标准理念实现 1.0.1
fly = {
	//---判断:为空判断---
	isNull:function(value){
		if(value==""||value==null||value==undefined||value=="undefined"){
			return true;
		}
		return false
	},
	
	//---判断:为none判断---
	isNone:function(value){
		if(value=="none"){
			return true;
		}
		return false
	},	
	
	//---判断:为空判断---
	isNullNone:function(value){
		if(value==""||value==null||value==undefined||value=="none"){
			return true;
		}
		return false
	},
		
	
	//---判断:是否是数字---
	isNumber:function(value){
		var patrn = /^(-)?\d+(\.\d+)?$/;
	    if (patrn.exec(value) == null || value == "") {
	        return false
	    } else {
	        return true
	    }
	},	
	
	//相似度校验
	isPercentage:function(value){
		 var reg = /^(?:0|[1-9][0-9]?|100)$/;
		 if(reg.exec(value) == null || value == ""){
		 	return false
		 }else{
		 	return true
		 }
	},
	
	//身份证号
	isIdCard:function(value){
		var reg = /^[1-9]\d{5}(18|19|20|(3\d))\d{2}((0[1-9])|(1[0-2]))(([0-2][1-9])|10|20|30|31)\d{3}[0-9Xx]$/;
		if(reg.exec(value) == null || value == ""){
			return false
		}else{
			return true
		}
	},
	
	//抽象 == 抽
	//函数：如果用到两次以上都需要写成一个函数
	//为空校验、正则校验
	isNumberPositive:function(value,numberMin,numberMax){
		if(fly.isNull(numberMin)||fly.isNull(numberMax)){
			var patrn = /^\d+$/;
		    if(patrn.exec(value) == null || value == "") {
		        return false;
		    }else{
		        return true;
		    }
			}else{
				var patrn = eval("/^\\d{"+numberMin+","+numberMax+"}$/"); 
		    if(patrn.exec(value) == null || value == "") {
		        return false;
		    }else{
		        return true;
		    }
		}
	},
	 //密码   6到16位  英文数字的组合
	 isPassWordCheck:function(value){
	 		var patrn = /^(?![0-9]+$)(?![a-zA-Z]+$)[0-9A-Za-z]{6,16}$/;
		 	if(patrn.exec(value) == null || value == ""){
				return false;
			}else{
				return true;
			}
	 },
	 
	 /*手机号*/
	isPhone:function(value){
		var Phone = /^1([38]\d|5[0-35-9]|7[3678])\d{8}$/;
		if(Phone.exec(value) == null || value == ""){
			return false;
		}else{
			return true;
		}
	},
	
	//为拼音的正则表达式
	isPinyin:function(value){
		var Pinyin = /^[a-zA-Z]+$/;
		if(Pinyin.exec(value) == null || value == ""){
			return false;
		}else{
			return true;
		}
	},
	
	//大写首字母正则表达式
	isAcronymCapital:function(value){
		var Acronym = /^[A-Z]+$/;
		if(Acronym.exec(value) == null || value == ""){
			return false;
		}else{
			return true;
		}
	},
	
	/*正整数*/
	isPositive:function(value){
		var Integer = /^[1-9]\d*$/;
		if(Integer.exec(value) == null || value == ""){
			return false;
		}else{
			return true;
		}
	},
	
	//正则:经度纬度 
  	islongitude:function(value){
	  	var longitude = /^[0-9]+([.]{1}[0-9]+){0,1}$/;
	  	if(longitude.exec(value) == null || value == ""){
	  		return false;
	  	}else{
	  		return true;
	  	}
	},
	
	//正则:网站
	isWebsite:function(value){
		var Website = /^((ht|f)tps?):\/\/[\w\-]+(\.[\w\-]+)+([\w\-.,@?^=%&:\/~+#]*[\w\-@?^=%&\/~+#])?$/;
		if(Website.exec(value) != null || value != ""){
			return true;
		}else{
			return false;
		}
	},
	/*商品编号  21位数字*/
	isNumber21:function(value){
		var namber = /^[0-9]{21}/;
		if(namber.exec(value) == null || value == ""){
			return true;
		}else{
			return false;
		}
	},
	
	

	
	//--- 获取区 ---
	
	//---获取:获取地址---
	
	getUrlHost:function(){
		return window.location.protocol+'//'+window.location.host;
	},	
	
	getUrl:function(){
		return window.location.protocol+'//'+window.location.host+window.location.pathname;
	},
	
	//---获取:获取地址参数值（无中文乱码）---
	getUrlHandle:function(name){
		var parameters = window.location.search;
		var parametersHandle = "";
		if(fly.isNull(parameters)){
			parametersHandle = "?orderby="+name;
		}else{
			parameters = parameters.substr(1);
			var arr = parameters.split("&"); //各个参数放到数组里
			var num = 0;
			var parameterName = "";
			var parameterValue = "";
			for(var i=0;i<arr.length;i++){ 
		    	var num = arr[i].indexOf("=");
		    	if(num>0){
		     		parameterName = arr[i].substring(0,num);
		     		parameterValue = arr[i].substr(num+1);
		     		if(parameterName.toLowerCase()!="orderby"){
		     			parametersHandle += "&"+parameterName+"="+parameterValue;	
		     		}
		     	} 
		    } 
		    parametersHandle = "?orderby="+name+parametersHandle;
		}
		return window.location.href=window.location.protocol+'//'+window.location.host+window.location.pathname+parametersHandle;
	},
	
	//---获取:获取地址参数值（无中文乱码）---
	getParameter:function(name) {
		var reg = new RegExp("(^|&)" + name + "=([^&]*)(&|$)", "i");
		var reg = new RegExp("(^|&)" + name + "=([^&]*)(&|$)", "i");
	  	var parameter = window.location.search.substr(1).match(reg);
	  	if(parameter != null){
	  		return unescape(decodeURI(parameter[2]));
	  	};
	  	return "";
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
	    var currentdate = 
	    		date.getFullYear() + "-" + 
	    		month + "-" + 
	    		strDate + " " + 
	    		hour + ":" + 
	    		minute + ":" + 
	    		seconds;
	    return currentdate;
	},
	
	//获取格式时间
	getTimeFormat:function(fpDate){
		//shijianchuo是整数，否则要parseInt转换
		var time = new Date(fpDate);
		var y = time.getFullYear();
		var m = time.getMonth()+1;
		var d = time.getDate();
		var h = time.getHours();
		var mm = time.getMinutes();
		var s = time.getSeconds();
		//时间处理
		m = m<10?'0'+m:m;
		d = d<10?'0'+d:d;
		h = h<10?'0'+h:h;
		mm = mm<10?'0'+mm:mm;
		s = s<10?'0'+s:s;
		return y+'-'+m+'-'+d+' '+h+':'+mm+':'+s;
	},
	
	//获取时间戳
	getTimestamp:function(key,number){
		if(fly.isNull(number)){
			number = 0;
		}
		var numberString = "";
		for(var i=0;i<number;i++){
			numberString += "" + Math.floor(Math.random()*10); 
		}
		return Math.round(new Date())+key+numberString;
	},


	//获取匹配的字符数量
	getStringCharNumber:function(fpString,fpChar){
		let vReplace = new RegExp(fpChar,"ig");
		if(vReplace.test(fpString)){
	        return fpString.match(vReplace).length;
	    }
	    return 0;
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
	
	//---常用方法:同步SessionAjax请求---
	ajaxSession:function(url,requestData,functionHandleData,requestType){
		var ajaxType = "get";
		if(!fly.isNull(requestType)){
			if(requestType.toLowerCase()=="post"){
				ajaxType = "post";	
			}	
		}
		$.ajax({
			url:url,
			type: ajaxType,
			xhrFields: {withCredentials: true},		//加上这句话
			crossDomain: true,
			contentType: 'application/json; charset=utf-8',
			async: false,
			data: requestData,
			dataType:"json",
			success:function(result){
				functionHandleData(result);
			},
		});
	},
	
	
	//--- 处理区 ---
	
	//替换 文本中需要替换的字符
	stringReplace:function(str,findStr,replaceStr){
		return str.replace(new RegExp(findStr,"g"),replaceStr);
	},
	
	//替换 文本所有换行:用于处理返回数据对于的换行内容
	stringReplaceLine:function(str){
		return str.replace(new RegExp(/[\r\n]/g,"g"),"");
	},
	
	//替换 文本所有空格:用于处理字符串的前后空白
	stringReplaceSpace:function(str){
		return str.replace(/(^\s*)|(\s*$)/g,"");
	},
	
	//删除 文本所有换行:用于处理返回数据对于的换行内容
	stringDeleteLast:function(str){
		return str.substring(0,str.length-1);
	},
	
	//替换 首写字母大写
  	stringFirstToUpper:function(str){
  		var strResult="";
		for (var i=0;i<str.length;i++) {
			var strChar = str.charAt(i);
			if (i==0){strResult = strChar.toUpperCase();}else{strResult+= strChar;}
		}	
  		return strResult;
  	},
	
	//判断 正则是否匹配成功
	judgeStringRegular:function(str,reg){
		if(new RegExp(reg).test(str)) {
	        return true;
	    }else{
	       return false;
	    }
	},

	//--- 转码区 ---
	
	
	//Data Code Escape
	dataEncode:function(data,spaceBo){
		
		if(fly.isNull(data)){return "";}
		
		if(fly.isNull(spaceBo)||!spaceBo){
			data = data.replace(/\s+/g, ' ');	//HTML Entity	
		}
		
		data = data.replace(/&/ig,'^amp;');			//HTML Entity
		
		data = data.replace(/[\n\r]/ig,'^nr;');		//Nextline Return
		data = data.replace(/\//ig,'^sla;');		//Slash
		data = data.replace(/\\/ig,'^bsl;');		//Backslash
		data = data.replace(/\"/ig,'^quot;');		//HTML Entity
		data = data.replace(/\'/ig,'^apos;');		//HTML Entity
		data = data.replace(/\{/ig,'^lcb;');		//left curly brace
		data = data.replace(/\}/ig,'^rcb;');		//right curly brace
		data = data.replace(/\[/ig,'^lb;');			//left bracket
		data = data.replace(/\]/ig,'^rb;');			//right bracket
		data = data.replace(/\$/ig,'^usd;');		//United States Dollar
		data = data.replace(/\,/ig,'^com;');		//comma
		
		//data = data.replace(/</ig,'^lt;');			//less than
		//data = data.replace(/>/ig,'^gt;');			//greater than
		//data = data.replace(/\(/ig,'^lp;');			//left parenthesis
		//data = data.replace(/\)/ig,'^rp;');			//right parenthesis
		data = data.replace(/\?/ig,'^qm;');			//Question mark
		data = data.replace(/:/ig,'^col;');			//Colon
		data = data.replace(/=/ig,'^eq;');			//Equal
		return data;
	},

	//Data Code Decode
	dataDecode:function(data){
		
		data = data.replace(/\^apos;/ig,'\'');		//HTML Entity
		data = data.replace(/\^quot;/ig,'"');		//HTML Entity
		data = data.replace(/\^bsl;/ig,'\\');		//Backslash
		data = data.replace(/\^sla;/ig,'/');		//Slash
		data = data.replace(/\^nr;/ig,'\n');		//Nextline Return
		data = data.replace(/\^lb;/ig,'[');			//left bracket 
		data = data.replace(/\^rb;/ig,']');			//right bracket 
		data = data.replace(/\^lcb;/ig,'{');		//left curly brace
		data = data.replace(/\^rcb;/ig,'}');		//right curly brace
		data = data.replace(/\^usd;/ig,'$');		//United States Dollar
		data = data.replace(/\^com;/ig,',');		//comma
		
		//data = data.replace(/\^lt;/ig,'<');			//less than
		//data = data.replace(/\^gt;/ig,'>');			//greater than
		//data = data.replace(/\^lp;/ig,'(');			//left parenthesis
		//data = data.replace(/\^rp;/ig,')');			//right parenthesis
		data = data.replace(/\^qm;/ig,'?');			//Question mark
		data = data.replace(/\^col;/ig,':');		//Colon
		data = data.replace(/\^eq;/ig,'=');			//Equal
		
		data = data.replace(/\^amp;/ig,'&');		//HTML Entity
		
		return data;
	},
	
	//Data Code Decode
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
	
	//Data Code Decode
	dataJsonFormatLine:function(data){
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
				code += c+"\n"+space;
			}else if(c=="{"){
				code += c+"\n"+space;
			}else if(c=="}"){
				code += "\n"+space+c;
			//}else if(c=="["){
				//code += "\n"+space+c+"\n"+space;	
			}else{
				code += c;	
			}
　　　　	}
		return code;
	},	
	
	//隐藏或显示
	hideShow:function(element){
		if($(element).is(':hidden')){
			$(element).show(300);
		}else{
			$(element).hide();
		}
	},
	
	//隐藏或显示按钮
	hideShowButton:function(element){
		var content = $(element).text(); 
		if(content.search("显示")>0){
			content = fly.stringReplace(content,"显示","隐藏");
		}else if(content.search("隐藏")>0){
			content = fly.stringReplace(content,"隐藏","显示");
		}
		$(element).text(content);
	},
	
	
	isjson:function(str){
	    if(typeof str == 'string'){
	        try {
	            var obj=JSON.parse(str);
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
	
	show:function(jqElement,addClass){
		$(jqElement).removeClass("display-hide");
		if(fly.isNull(addClass)){
			$(jqElement).addClass("display-inline-block");	
		}else if(addClass!="NONE"){
			$(jqElement).addClass(addClass);
		}
		$(jqElement).show();
	},
	
	hide:function(jqElement,removeClass){
		$(jqElement).addClass("display-hide");
		if(fly.isNull(removeClass)){
			$(jqElement).removeClass("display-inline-block");	
		}else if(removeClass!="NONE"){
			$(jqElement).removeClass(removeClass);
		}
		$(jqElement).hide();
	},
	
	//Unicode编码
	encodeUnicode:function(data){
   		if(data == ''){return '请输入汉字'};
   		var str =''; 
   		for(var i=0;i<data.length;i++){
      		str+="\\u"+parseInt(data[i].charCodeAt(0),10).toString(16);
   		}
   		return str;
	},
	
	//Unicode解码
	decodeUnicode:function(data){
    	if(data == ''){return '请输入十六进制unicode'};
    	data = data.split("\\u");
    	var str ='';
    	for(var i=0;i<data.length;i++){
        	str+=String.fromCharCode(parseInt(data[i],16).toString(10));
    	}
    	return str;
	}
	
}