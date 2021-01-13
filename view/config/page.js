//================= 变量定义区 =================

//--- 页面变量区 ---
var submitIndex = 0;							//变量:提交按钮索引
var vSubmitSwitch = 0;							//变量:提交按钮控制开关

var inforSetTableObj = "";						//详细信息:设置表格对象
var inforSetId = "";							//详细信息:记录设置ID
var inforSetObj = "";							//详细信息:设置对象
var inforSetIndex = "";							//详细信息:修改索引

var vPageLogs = new Array();					//数组:页面日志
var vPageLogsTime = new Array();				//数组:页面日志时间
var vPageLogsEvent = new Array();				//数组:页面日志结果
var vPageLogsRrsult = new Array();				//数组:页面日志布尔
var vPageLogsTitle = new Array();				//数组:页面日志标题
var vPageLogsDescript = new Array();			//数组:页面日志描述

var vPageJsLogTime = "";						//日志：记录时间
var vPageJsLogNumber = 0;						//日志：本次日志数
var vPageJsLogSuccess = 0;						//日志：本次成功数
var vPageJsLogFail = 0;							//日志：请求失败数
var vPageJsLogSum = 0;							//日志：日志总数
var vPageJsLogSuccessSum = 0;					//日志：成功总数
var vPageJsLogFailSum = 0;						//日志：失败总数


//================= 方法定义区 =================

//日志添加：Logs add
function fPageLogs(logEvent,log,resultColor,resultBool,fpTitle="请求日志",fpDescript=""){
	fPageLogsCount(resultBool);
	vPageLogs.unshift(log);
	vPageLogsTime.unshift(fly.getTimeNow());
	vPageLogsEvent.unshift(logEvent);
	vPageLogsRrsult.unshift(resultColor);
	vPageLogsTitle.unshift(fpTitle);
	vPageLogsDescript.unshift(fpDescript);
	if(vPageLogs.length>100){
		vPageLogs.pop();
		vPageLogsTime.pop();
		vPageLogsEvent.pop();
		vPageLogsRrsult.pop();
		vPageLogsTitle.pop();
		vPageLogsDescript.pop();
	}
}

//日志统计：Logs count fail
function fPageLogsCountInit(){
	vPageJsLogTime = "";		//日志：记录时间
	vPageJsLogNumber = 0;		//日志：本次日志数
	vPageJsLogSuccess = 0;		//日志：本次成功数
	vPageJsLogFail = 0;			//日志：请求失败数
}

//日志统计：Logs count fail
function fPageLogsCount(fpPageJsLogResult){
	vPageJsLogTime = fly.getTimeNow();	//日志：记录时间
	vPageJsLogNumber += 1;	//日志：本次日志数
	vPageJsLogSum += 1;	//日志：日志总数
	if(fpPageJsLogResult){
		vPageJsLogSuccess += 1; //日志：本次成功数
		vPageJsLogSuccessSum += 1;	//日志：成功总数
	}else{
		vPageJsLogFail += 1;	//日志：请求失败数
		vPageJsLogFailSum += 1;	//日志：失败总数
	}
}

//Data Paging Address
function DataPagingAddress(tableName,id){
	return urlAjax+'type=public&method=paging&table='+tableName+'&orderby=addTime';
}

//Data Delete Address
function DataDeleteAddress(tableName,id){
	return urlAjax+'type=public&method=delete&table='+tableName+'&id='+id;
}

//Data Count Address
function DataCountAddress(tableName){
	return urlAjax+'type=public&method=count&table='+tableName;
}

//Infor
function LayuiOpenInfor(layer,address,title,btnId,btnTitle){
	var titleText = "信息";
	if(!fly.isNull(title)){titleText = title;}
	if(!fly.isNull(btnId)){
		if(fly.isNull(btnTitle)){
			btnTitle = "提交";
		}
		btnId = '<button class="layui-btn" id="'+btnId+'" style=" margin-top: 10px;display:block;">'+btnTitle+'</button>';
	}else{
		btnId = "";
	}
	layer.open({
	  type: 1
	  ,shadeClose: true
	  ,title: titleText
	  ,area: ['auto', 'auto']
	  ,content: '<div style="padding:20px;position:relative;"><textarea rows="3" cols="20" style="width: 330px; height: 160px; padding: 10px; color: #6f6f6f; font-size: 16px; line-height: 26px;" id="iTextarea">'+address+'</textarea>'+btnId+'</div>'
	  ,yes: function(){$(that).click();}
	  ,zIndex: layer.zIndex 
	  ,success: function(layero){
	  	var vTextarea = $("#iTextarea").val();
	  	vTextarea = fly.stringReplace(vTextarea,"¶","&para");
	  	$("#iTextarea").val(vTextarea);
	  	layer.setTop(layero);
	  }
	});
}


//Data Delete
function LayuiDelete(layer,obj,deleteUrl,fpDeleteName){
	layer.confirm('确认删除'+fpDeleteName+'？', function(index){
		var url = DataDeleteAddress(tableName,obj.data.id);
		if(deleteUrl != ""){url = deleteUrl;}
		var loadIndex = layer.load(2,{time:10*1000});
		$.post(url,function(data){
			console.log(data);
    		var json = eval('('+data+')');
			if(json.result=="true"){
				obj.del();
				layer.msg("删除成功",{icon:6,time:800},function(){
					layer.close(index); //location.reload();
				});
			}else{
				layer.msg("删除失败",{icon:5,time:1500});
			}
			layer.close(loadIndex);
		});
	});
}

/*post*/
function LayuiPost(ajaxUrl,success){
	$.post(ajaxUrl, function(data) {
		var json = eval('(' + data + ')');
		if(json.result == "true") {
			success();
		} else {
			layer.msg(json.infor, { icon: 5, time: 1000 }, function() {});
		}
	});
}

/*删除*/
function LayuiDeleteTwo(deleteUrl,success,fpDeleteTitle){
	if(fly.isNull(fpDeleteTitle)){ fpDeleteTitle = "确认删除?"; }
	layer.confirm(fpDeleteTitle,function(){
		var url =  deleteUrl;
		var loadIndex = layer.load(2,{time:10*1000});
		$.post(url,function(data) {
			console.log("data",data);
			var json = eval('(' + data + ')');
			if(json.result == "true") {
				layer.msg("删除成功", { icon: 6, time: 1000 }, function() {
					success();
				});
			} else {
				layer.msg(json.infor, { icon: 5, time: 1000 }, function() {});
			}
		});
		layer.close(loadIndex);
	})
}

/*弹窗*/
function LayuiPopup(type,shadeClose,title,content,wide,high){
	layer.open({
		type:1,
		shadeClose:'true',
		title: title,
		content: content,
		area: [wide, high],
	});
}

//Data Infor
function LayuiInfor(layer,title,url,resulTrueFunction){
	layer.confirm(title, function(index){
		$.post(url,function(data){
    		var json = eval('('+data+')');
			if(json.result=="true"){
				layer.msg(json.infor,{icon:6,time:800},function(){
					resulTrueFunction();
					layer.close(index); 
				});
			}else{
				layer.msg(json.infor,{icon:5,time:1500});
			}
		});
	});
}


//Data Set
function LayuiSet(layer,setTitle,thisValue,url,setValueId){
	layer.prompt({
	  	formType: 0,
	  	value: thisValue,
	  	title: setTitle,
	  	shadeClose:'true',
	  	area: ['500px', '350px'] 	//自定义文本域宽高
	}, function(value, index, elem){
	  	$.post(url+value,function(data){
	  		console.log(data);
			var json = eval('('+data+')');
			if(json.result=="true"){
				layer.msg("修改成功",{icon:6,time:800},function(){
					layer.close(index);
					if(fly.isNull(setValueId)){
						location.reload();
					}else{
						$(setValueId).html(value);
					}
				});
			}else{
				layer.msg("修改失败",{icon: 5,time:1500});
			}
	  	});
	});
}

//Data Set -- Version 2.0
function LayuiSetTwo(layer,setTitle,obj,fieldName,url){
	layer.prompt({
	  	formType: 0,
	  	value: obj.data[fieldName],
	  	title: setTitle,
	  	shadeClose:'true',
	  	area: ['500px', '350px'] 	//自定义文本域宽高
	}, function(value, index, elem){
	  	$.post(url+value,function(data){
			var json = eval('('+data+')');
			if(json.result=="true"){
				layer.msg("修改成功",{icon:6,time:800},function(){
					eval("obj.update({"+ fieldName + ":'" + value + "'});");
					layer.closeAll();	
				});
			}else{
				layer.msg("修改失败",{icon: 5,time:1500});
			}
	  	});
	});
}

//添加日志
function LogAdd(id,body,type){
	if(type=="error"){
		$("#"+id).append('<p style="color:#FF3333;">'+body+' '+fly.getTimeNow()+'</p>');
	}else{
		$("#"+id).append('<p style="color:#6979b6;">'+body+' '+fly.getTimeNow()+'</p>');	
	}
}

//参数判断
function ParameterIsNull(infor){
	//关闭点击索引
	submitIndex = 0;
	//参数缺失输出
	layer.msg(infor+"不得为空 或 不符合规则",{icon:5,time:1500},function(){});
}

//错误信息提示
function LayerInforWrong(infor){
	//关闭点击索引
	submitIndex = 0;
	//错误信息输出
	layer.msg(infor,{icon:5,time:1500},function(){});
}


//layui特殊处理
function fLayerSuccess(fpLayerIndex){
	//移除内容的关闭按钮，添加标题的关闭按钮
	$(".layui-layer-content .layui-layer-setwin").remove();
	$(".layui-layer-title").append('<span class="layui-layer-setwin"><a class="layui-layer-ico layui-layer-close layui-layer-close1 cLayuiLayerSetwin" href="javascript:void(0);" data-layer-index="'+fpLayerIndex+'"></a></span>');
}
//关闭按钮被点击
$(".cLayuiLayerSetwin").live("mousedown",function(){
	fLayerClose($(this).attr("data-layer-index"));
})

//================= 事件区 =================

//日志被点击时
$("#iTopToolLogs").live("click",function(){
	var html = "";
	var htmlArray = "";
	var vRrsult = "";
	html  = '<div class="margin-20">';
	html += '<table class="layui-table tableinfor" id="iPageJsLogTable">';
	html += '	<tr><td style="color:#8e8e8e;margin-bottom:5px;">';
	html += '		<span><a class="a-blue" href="javascript:void(0)" id="iPageJsClearLog">清除日志</a></span>';
	html += '	</td></tr>';
	html += '	<tr><td style="height: 0px;border: 0px;padding: 0px; padding-bottom: 6px;"></td></tr>';
	if(vPageLogs.length>0){
		html += '	<tr><td style="color:#8e8e8e;margin-bottom:5px;">';
		html += '		记录时间：<span class="margin-right-10">'+vPageJsLogTime+'</span> 本次日志数：<span class="margin-right-10">'+vPageJsLogNumber+'</span> 本次成功数：<span class="margin-right-10">'+vPageJsLogSuccess+'</span> 请求失败数：<span class="margin-right-10">'+vPageJsLogFail+'</span> 日志总数：<span class="margin-right-10">'+vPageJsLogSum+'</span> 成功总数：<span class="margin-right-10">'+vPageJsLogSuccessSum+'</span> 失败总数：<span class="margin-right-10">'+vPageJsLogFailSum+'</span>';
		html += '	</td></tr>';
		html += '	<tr><td style="height: 0px;border: 0px;padding: 0px; padding-bottom: 6px;"></td></tr>';	
	}
	var vForNumber = 100;
	console.log("页面日志数："+vPageLogs.length);
	if(vPageLogs.length<=100){
		vForNumber = vPageLogs.length;
	}
	for(var i=0;i<vPageLogs.length;i++){
		vRrsult = vPageLogsRrsult[i].toString();
		var vLogDescript = vPageLogsDescript[i];
		if(!fly.isNull(vLogDescript)){
			vLogDescript = "　信息：<span style='color:#2ca55d;'>"+vLogDescript+"</span> ";
		}
		if(vRrsult=="true"){
			htmlArray += '	<tr><th class="parts-font-weight">事件：<span style="color:#006BCE">'+vPageLogsEvent[i]+'</span>　时间：<span style="color:#4c71da;">'+vPageLogsTime[i]+'</span>'+vLogDescript+'</tr>';	
		}else if(vRrsult=="false"){
			htmlArray += '	<tr><th class="parts-font-weight">事件：<span style="color:#FF5722;">'+vPageLogsEvent[i]+'</span>　时间：<span style="color:#4c71da;">'+vPageLogsTime[i]+'</span>'+vLogDescript+'</th></tr>';
		}else if(vRrsult=="green"){
			htmlArray += '	<tr><th class="parts-font-weight">事件：<span style="color:#4f984f;">'+vPageLogsEvent[i]+'</span>　时间：<span style="color:#4c71da;">'+vPageLogsTime[i]+'</span>'+vLogDescript+'</th></tr>';
		}else{
			htmlArray += '	<tr><th class="parts-font-weight">事件：<span>'+vPageLogsEvent[i]+'</span>　时间：<span style="color:#4c71da;">'+vPageLogsTime[i]+'</span>'+vLogDescript+'</th></tr>';
		}
		htmlArray += '	<tr><td style="word-wrap: break-word; word-break: break-all; overflow: hidden;">'+vPageLogs[i]+'</td></tr>';
	}
	//判断HTML数组组合内容是否为空
	if(fly.isNull(htmlArray)){
		htmlArray = '	<tr><td style="color:#8e8e8e;">暂无日志</td></tr>';
	}
	html+= htmlArray;
	html+= '</table></div>';
	layer.open({
		type:1,
		shadeClose:'true',
	  	title: '页面日志',
	 	content: html,
	  	area: ['80%', '80%'],
	});
})

//清除日志被点击时
$("#iPageJsClearLog").live("click",function(){
	//--- 清除日志变量 ---
	vPageJsLogTime = "";						//日志：记录时间
	vPageJsLogNumber = 0;						//日志：本次日志数
	vPageJsLogSuccess = 0;						//日志：本次成功数
	vPageJsLogFail = 0;							//日志：请求失败数
	vPageJsLogSum = 0;							//日志：日志总数
	vPageJsLogSuccessSum = 0;					//日志：成功总数
	vPageJsLogFailSum = 0;						//日志：失败总数
	//--- 清除日志数组 ---
	vPageLogs = new Array();					//数组:页面日志
	vPageLogsTime = new Array();				//数组:页面日志时间
	vPageLogsEvent = new Array();				//数组:页面日志事件
	vPageLogsRrsult = new Array();				//数组:页面日志布尔
	vPageLogsTitle = new Array();				//数组:页面日志标题
	vPageLogsDescript = new Array();			//数组:页面日志描述
	//--- 初始化数据 ---
	var html  = "";
		html += '	<tr><td style="color:#8e8e8e;margin-bottom:5px;">';
		html += '		<span><a class="a-blue" href="javascript:void(0)" id="iPageJsClearLog">清除日志</a></span>';
		html += '	</td></tr>';
		html += '	<tr><td style="height: 0px;border: 0px;padding: 0px; padding-bottom: 6px;"></td></tr>';
		html += '	<tr><td style="color:#8e8e8e;">暂无日志</td></tr>';
	$("#iPageJsLogTable").html(html);
})	

/**公共函数:表总数*/
$("#buttonCount").live("click",function(){
	var dataHref = $(this).attr("data-href");
	var url = DataCountAddress(tableName);
	if(!(dataHref==undefined||dataHref=="")){url = dataHref;}
	$.get(url,function(data){
		var json = eval('('+data+')');
		layer.msg('总记 : '+json.msg, {time:1200});
	});
})

/**公共函数:路径*/
$("#aPath").live("click",function(){
	LayuiOpenInfor(layer,window.location.protocol+"//"+window.location.host+window.location.pathname);
})

/**公共函数:表字段*/
$("#buttonField").live("click",function(){
	window.location.href = urlHost+"/view/page/adminsuper/public/flytablefield.html?table="+tableName;
})

/**公共函数:清空表*/
$("#buttonClear").live("click",function(){
	layer.confirm('确认清空所有记录？', {
		btn: ['清空','取消'], //按钮
		shadeClose: true,
	}, function(){
		$.get(urlAjax+"type=public&method=tableclear&table="+tableName,function(){
			layer.msg('数据表已清空',{icon:1,time:1000},function(){
				window.location.reload();
			});
		});
	},function(){
	  	layer.close();
	});
})

/**公共函数:空记录*/
$("#buttonNullRecord").live("click",function(){
	$.get(urlAjax+"type=public&method=addnull&table="+tableName,function(data){
		var json = eval('('+data+')');
		if(json.infor=="true"){
			layer.msg(json.msg,{icon:6,time:800});
			window.location.reload();
		}else{
			layer.msg(json.msg,{icon:5,time:800});
		}
	});
})	

/**公共函数:数据表检测*/
$("#buttonTableCheck").live("click",function(){
	$.get(urlAjax+"type=public&method=tablecheck&table="+tableName,function(data){
		var json = eval('('+data+')');
		layer.msg(json.msg);
	});
})	


//图片点击弹出
$("td img").live("click",function(){
	layer.open({
	  type: 1,
	  title: false,
	  closeBtn: 0,
	  area: ['auto', 'auto'],
	  fixed: false, //不固定
	  skin: 'layui-layer-nobg', //没有背景色
	  shadeClose: true,
	  content: '<img style="max-width:800px;max-height:600px;" src="'+$(this).attr("src")+'">'
	});
})	

//路径按钮
$("#pathId").live("click",function(){
	LayuiOpenInfor(layer,self.location.href,"地址:本页路径");
})

//参数按钮
$("#iAUrlParam").live("click",function(){
	var vUrlParamter = self.location.search;
	if(!fly.isNull(vUrlParamter)){
		vUrlParamter = vUrlParamter.substr(1,vUrlParamter.length);
	}
	LayuiOpenInfor(layer,vUrlParamter,"地址:页面参数");
})


//Button:事件:添加
$("#buttonAdd").live("click",function(){
	layer.open({
		type:1,
		shadeClose:'true',
	  	title: '添加'+recordName,
		content: $("#htmlAddId").html(),
  		area: ['auto', 'auto'],
  		success: function(layero,index){
  			//打开窗口成功够进行处理阶段
			HandleAddWindow();
		}
	});
})

//----- 记录添加提交按钮 -----
$("#htmlAddBtnId").live("click",function(){
	//当添加窗口为打开状态时不可以再次点击
	if(judgeAddWindow==1){
		return;
	}
	judgeAddWindow = 1;
	//参数获取 
	var parameterString = "";
	$.each($(".input-add-value"),function(i, domObj){
		parameterString += "&"+domObj.name+"="+domObj.value;
	});
	//参数Key:关键字字段
	var keyfield = "";
	$.each($(".input-add-key"),function(i, domObj){
		keyfield += domObj.name+",";
	});
	keyfield = "&keyfield="+fly.stringDeleteLast(keyfield);
	//URL组合
	var data = 'type=public&method=addpost&table='+tableName+parameterString+keyfield;
	console.log(data);
	//JQuery:Get
	$.post(urlAjax,data,function(data){
		var json = eval('('+data+')');
		console.log(json);
		if(json.result=="true"){
			layer.msg(json.infor,{icon:6,time:1000},function(){
				window.location.reload();
			});
		}else{
			layer.msg(json.infor,{icon:5,time:1000});
		}
		judgeAddWindow = 0;
	});
})


//----- 图片操作 -----

var clickObj = "";
//选择图片被点击时 
$(".uploadImageClass").live("click",function(){
	clickObj = $(this);
	changeInputObj = clickObj.parent().siblings("form").find(".uploadInputFileClass");
	changeInputObj.click();
});

//图片上传
$(".uploadInputFileClass").live("change",function(){
	var imgObj = clickObj.next().find(":first");
	//遮罩层加载显示
	var uploadMaskObj = clickObj.prev();
	uploadMaskObj.show();
	//如果图片文件为空时
	$.ajax({
		async : false,
		cache : false,
		type : "post",
		data : new FormData($(this).parent()[0]),
		url : urlAjax+'line=image&method=upload',
		dataType : 'json', 
		contentType: false, //必须
		processData: false, //必须
		success : function(data){
			imgObj.attr('src',data.infor);
			console.log(data.infor);
			uploadMaskObj.hide();
		},
		error:function(xhr,status,info){}
	});
});

/*微信图片上传*/
function weChatchart(uploadInputFileClass,url){
	$(uploadInputFileClass).live("change",function(){
		var imgObj = clickObj.next().find(":first");
		//遮罩层加载显示
		var uploadMaskObj = clickObj.prev();
		uploadMaskObj.show();
		$.ajax({
			async : false,
			cache : false,
			type : "post",
			data : new FormData($(this).parent()[0]),
			url : urlAjax+'line=image&method=upload&original=true',
			dataType : 'json', 
			contentType: false, //必须
			processData: false, //必须
			success : function(data){
				imgObj.attr('src',data.infor);
				console.log(data.infor);
				uploadMaskObj.hide();
			},
			error:function(xhr,status,info){}
		});
	});	
}

//分销图片上传  iSpotLogoImg
$(".uploadImageClass").live("click",function(){
	clickObj = $(this);
	changeInputObj = clickObj.parent().siblings("form").find(".distributionuploadFile");
	changeInputObj.click();
});
$(".distributionuploadFile").live("change",function(){
	//图片对象
	var imgObj = clickObj.next().find(":first");
	var uploadMaskObj = clickObj.prev();
	uploadMaskObj.show();
	$.ajax({
		async : false,
		cache : false,
		type : "post",
		data : new FormData($(this).parent()[0]),
		url : urlAjaxPartner+'line=image&method=upload',
		dataType : 'json', 
		contentType: false, //必须
		processData: false, //必须
		success : function(data){
			imgObj.attr('src',data.infor);
			console.log(data.infor);
			uploadMaskObj.hide();
		},
		error:function(xhr,status,info){}
	});
});


/*RGB颜色转换为16进制*/
String.prototype.colorHex = function(){
	var reg = /^#([0-9a-fA-f]{3}|[0-9a-fA-f]{6})$/;
	var that = this;
	if(/^(rgb|RGB)/.test(that)){
		var aColor = that.replace(/(?:\(|\)|rgb|RGB)*/g,"").split(",");
		var strHex = "#";
		for(var i=0; i<aColor.length; i++){
			var hex = Number(aColor[i]).toString(16);
			if(hex === "0"){
				hex += hex;	
			}
			strHex += hex;
		}
		if(strHex.length !== 7){
			strHex = that;	
		}
		return strHex;
	}else if(reg.test(that)){
		var aNum = that.replace(/#/,"").split("");
		if(aNum.length === 6){
			return that;	
		}else if(aNum.length === 3){
			var numHex = "#";
			for(var i=0; i<aNum.length; i+=1){
				numHex += (aNum[i]+aNum[i]);
			}
			return numHex;
		}
	}else{
		return that;	
	}
};



//================= 代码区 ：加载时执行  =================

//加载JS模板代码
$("#javascriptModelId").html(
	
	'<!-- 模板:图片-->'+
	'<script type="text/html" id="templetImgHandle">'+
	'	{{# if(!(d.imageUrl=="none"||d.imageUrl=="")){ }}'+
	'		<img width="200" src="{{d.imageUrl}}"/>'+
	'	{{# } }}'+
	'</script>'+
	
	'<!-- 模板:图片上移131%-->'+
	'<script type="text/html" id="templetImgTopHandle">'+
	'	{{# if(!(d.imageUrl=="none"||d.imageUrl=="")){ }}'+
	'		<img width="200" style="position:relative;top:-131%" src="{{d.imageUrl}}"/>'+
	'	{{# } }}'+
	'</script>'+
	
	'<!--状态处理-->'+
    '<script type="text/html" id="templetStateHandle">'+
	'  	{{#  if(d.state == "NORMAL"){ }}'+
	'    	<span style="color:#C9C9C9">正常</span>'+
	'  	{{#  } else { }}'+
	'    	未通过'+
	'  	{{#  } }}'+
	'</script>'+
	
	'<!-- 解码处理 -->'+
	'<script type="text/html" id="templetResultDeCode">'+
	'	{{ fly.dataDecode(d.requestResult) }}'+
	'</script>'

)



//================= 代码区 : 加载时执行 =================

//全局变量
var vTitleOrderNumber = "排序序号";						//字段标题
var vTitleAddTime = "添加时间";							//字段标题
var vTitle = "表ID";									//ID标题
//参数获取
var parameterOrderby = fly.getParameter("orderby");		//排序方式


//================= 代码区 : 加载完毕执行 =================

//加载完毕执行
$(function(){
	
	//排序方式
	if(parameterOrderby=="orderNumber"){
		$("#iOrderOrderNumber").removeClass("color-blue");
		$("#iOrderOrderNumber").addClass("color-gray");
		vTitleOrderNumber = "<span class='color-blue-order'>"+vTitleOrderNumber+"➷"+"</span>";
	}else if(parameterOrderby=="addTime"){
		$("#iOrderAddTime").removeClass("color-blue");
		$("#iOrderAddTime").addClass("color-gray");
		vTitleAddTime = "<span class='color-blue-order'>"+vTitleAddTime+"➷"+"</span>";
	}else if(parameterOrderby=="id"){
		$("#iId").removeClass("color-blue");
		$("#iId").addClass("color-gray");
		vTitle = "<span class='color-blue-order'>"+vTitle+"➷"+"</span>";
	}else{
		parameterOrderby = "addTime";
		$("#iOrderAddTime").removeClass("color-blue");
		$("#iOrderAddTime").addClass("color-gray");
		vTitleAddTime = "<span class='color-blue-order'>"+vTitleAddTime+"➷"+"</span>";
	}
	
	//初始化组件:layer
	layui.use('layer', function(){});
	
	//代码:顶部:All
	if(typeof(pageName)!='undefined'){
		var pageNameObjName = pageName.show?pageName.name:"";
		var tableNameObjName = "";
		if(!fly.isNull(tableName)&&tableName.show){
			tableNameObjName = tableName.name;	
		}
		var pageConfigPaging = "";
		if(typeof(pageConfig)!='undefined'){
			if(pageConfig.paging!=undefined&&pageConfig.show==true){
				pageConfigPaging = ' <a id="iPagePaging" href="'+pageConfig.paging+"&page=1&limit=10"+'" target="_blank">[ 分页数据 ]</a>';
			}else{
				for(var i=0;i<pageConfig.length;i++){
					pageConfigPaging += ' <a id="'+pageConfig[i].id+'" href="'+pageConfig[i].url+'&page=1&limit=10" target="_blank">[ '+pageConfig[i].title+' ]</a>';	
				}
			}
		}
		var urlPagePathFile = window.location.pathname;
		var urlPagePathFileArray = urlPagePathFile.split('/');
		pageConfigPaging += ' <span>[ '+ urlPagePathFileArray[urlPagePathFileArray.length-1] +' ]<span>';
		$("#codeTopAll").html(
			' <span style="margin-left: 2px;">'+pageNameObjName+'</span>'+
			' <span style="margin-left: 2px;">'+tableNameObjName+'</span>'+
			'<a href="javascript:history.back(-1)" style="margin-left:10px;">[ 返回 ]</a>'+
			' <a href="javascript:void(0)" onclick="location.reload()" >[ 刷新 ]</a>'+
			' <a href="javascript:void(0)" id="pathId">[ 路径 ]</a>'+
			' <a href="javascript:void(0)" id="iAUrlParam">[ 参数 ]</a>'+
			' <a href="javascript:void(0)"  onclick="window.open(window.location.href)">[ 新开 ]</a>'+
			' <a href="javascript:void(0)"  onclick="window.close()">[ 关闭 ]</a>'+
			' <a href="javascript:void(0)"  id="iTopToolLogs">[ 日志 ]</a>'+
			pageConfigPaging
		);
		
		//隐藏工具按钮
		$("#codeTopAll a").hide();
		if(typeof(pageConfig)!='undefined' && pageConfig.tool==true){
			$("#codeTopAll a").show();
		}
		
	}
	

	//代码:按钮
	var codeButtonHtml = ''+ 
		'<button class="layui-btn" id="buttonCount" style="margin-right:4px;">总记录数</button>'+
		'<button class="layui-btn" id="buttonField" style="margin-right:4px;">字段说明</button>'+
		'<button class="layui-btn layui-btn-primary" id="buttonClear" style="margin-right:4px;">清空记录</button>'+
		'<button class="layui-btn" id="buttonTableCheck" style="margin-right:4px;">数据表检测</button>'
	if(typeof(recordName)!='undefined'){
		codeButtonHtml = '<button class="layui-btn" id="buttonAdd" style="margin-right:4px;">添加'+recordName+'</button>'+ codeButtonHtml;
	}	
	$("#codeButton").html(codeButtonHtml);
	
	
	var $htmlcode = {
	
		//追加按钮
		ButtonCodeAdd:function(buttonName,buttonId,addType){
			if(addType==""||addType=="after"){
				$("#codeTopAll").after('<button class="layui-btn" id="'+buttonId+'" style="margin-right:4px;">'+buttonName+'<span class="pageNameClass"></span></button>');
			}else if(addType=="before"){
				$("#codeTopAll").before('<button class="layui-btn" id="'+buttonId+'" style="margin-right:4px;">'+buttonName+'<span class="pageNameClass"></span></button>');
			}
		}
	}
	
	//隐藏页面配置好的元素
	if(typeof(hideArray)!='undefined'){
		for(var i = 0;i <hideArray.length; i++){
		    $(hideArray[i]).hide();
		}	
	}
	
	/*删除图片*/
	$(".cCloseImg").live("click",function(){
		$(this).siblings("img").attr("src","");
		$(this).parents(".updataClickClass").find(".uploadInputFileClass").val("");
	});
	
	//图片点击
	$(".logoImageClassTwo").live("click",function(){
		$(this).parent().siblings().find(".iSpotLogoImg").click();
	});

});