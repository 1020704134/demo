//= 变量区 =============================================

//--- 提交索引 ---
var vLayerLoadIndex = 0;	//提交索引
var vSubmitSwitch = 0;		//提交开关
var vCalcSwitch = 0;		//计算开关 -- 用于JS变量的计算

var vLayTableObj = "";				//Layui table 对象
var vLayTableObjSetValue = "";		//Layui table 对象 设置值

//--- 日志 ---
var vPageLogs = new Array();		//日志数组:页面日志
var vPageLogsTime = new Array();	//日志数组:页面日志时间
var vPageLogsEvent = new Array();	//日志数组:页面日志事件
var vPageLogsRrsult = new Array();	//日志数组:页面日志布尔
var vPageJsLogTime = "";			//日志变量：记录时间
var vPageJsLogNumber = 0;			//日志变量：本次日志数
var vPageJsLogSuccess = 0;			//日志变量：本次成功数
var vPageJsLogFail = 0;				//日志变量：请求失败数
var vPageJsLogSum = 0;				//日志变量：日志总数
var vPageJsLogSuccessSum = 0;		//日志变量：成功总数
var vPageJsLogFailSum = 0;			//日志变量：失败总数

//--- 弹出层 ---
var vLayerIndexArray = new Array();	//弹出层:弹出层索引数组


//= 函数区 =============================================

//--- 提交索引 ---
//提交索引:判断
function fSubmitSwitchJudge(){
	return vSubmitSwitch==1;
}
//提交索引:开始
function fSubmitSwitchStart(fpTime){
	vSubmitSwitch=1;
	layui.use('layer', function(){
		if(fly.isNull(fpTime)){fpTime = 20;}
		var vOpenTop = (document.documentElement.clientHeight-32)/2+"px";
		var vOpenLeft = (document.documentElement.clientWidth-32)/2+"px";
		vLayerLoadIndex = layer.load(2, {time:fpTime*1000, offset:[vOpenTop,vOpenLeft], area:["32px","32px"]});
	});
}
//提交索引:结束
function fSubmitSwitchEnd(){
	vSubmitSwitch=0;
	layui.use('layer', function(){
		layer.close(vLayerLoadIndex);
	});
}

//--- Layui相关 ---
function fGetRowData(obj, number, filedName) {
	var se = obj.tr.selector;
	var os = se.substring(se.indexOf('"') + 1, se.lastIndexOf('"'));
	var nse = se.replace(os, parseInt(os) + number);
	var res = "";
	$(nse + " td:not(.layui-table-col-special)").each(function () {
		if ($(this).attr("data-field") == filedName) {
			res = $(this).children(":first").html()
		}
	});
	return res;
}

//Layer加载
function fLayerLoad(fpTime){
	layui.use('layer', function(){
		if(fly.isNull(fpTime)){fpTime = 20;}
		var vOpenTop = (document.documentElement.clientHeight-32)/2+"px";
		var vOpenLeft = (document.documentElement.clientWidth-32)/2+"px";
		vLayerLoadIndex = layer.load(2, {time:fpTime*1000, offset:[vOpenTop,vOpenLeft], area:["32px","32px"]});
	});
}
//Layer加载关闭
function fLayerClose(fpLayerIndex){
	layui.use('layer', function(){
		if(fly.isNull(fpLayerIndex)){
			layer.close(vLayerLoadIndex);
		}else{
			layer.close(fpLayerIndex);
		}
	});
}
//Layer加载关闭
function fLayerCloseAll(){
	layui.use('layer', function(){
		layer.closeAll();
	});
}

//Layer消息
function fLayerMsg(fpInfor,fpTime,icon,fpFunction){
	layui.use('layer', function(){
		var vOpenTop = 0;
		if(fly.isNull(icon)){
			vOpenTop = (document.documentElement.clientHeight-48)/2 + "px";
		}else{
			vOpenTop = (document.documentElement.clientHeight-66)/2 + "px";
		}
		var vWidth = (55+fly.getLength(fpInfor).length*8+20)+"px";
		var vLayerIndex = layer.msg(fpInfor,{time:fpTime, icon:icon, offset:vOpenTop, area:[vWidth, 'auto']},fpFunction);
		vLayerIndexArray.push(vLayerIndex);	//添加弹窗索引到Layer弹出层索引数组
	});
}

//Layer消息长度计算
function fLayerMsgLength(fpInfor){
	return (55+fly.getLength(fpInfor).length*8+30)+"px";
}

//Layer文本域
function fLayerTextArea(fpAreaText,fpTitle,fpWidth,fpHtml){
	//百分比宽度计算
	if(typeof fpWidth == "string" && fpWidth.indexOf("%")>0){
		fpWidth = fpWidth.replace("%","");
		fpWidth = document.documentElement.clientWidth * fpWidth/100;
	}
	//区域尺寸计算
	if(fly.isNull(fpWidth)){ fpWidth = 390; }
	fpWidth = parseInt(fpWidth);
	var vAreaWidth = fpWidth;
	var vAreaHeight = fpWidth-120;
	var vTextareaWidth = vAreaWidth-60;
	var vTextareaHeight = fpWidth - 230;
	if(vAreaHeight>500){
		vAreaHeight = 500;
		vTextareaHeight = vAreaHeight - 110;
	}
	//标题计算
	var titleText = "信息";
	if(!fly.isNull(fpTitle)){
		titleText = fpTitle;
	}
	//弹窗
	layui.use('layer', function(){
		var vOpenTop = (document.documentElement.clientHeight-vAreaHeight)/2+"px";
		var vOpenLeft = (document.documentElement.clientWidth-vTextareaWidth)/2+"px";
		var vLayerIndex = layer.open({
			 type: 1
			,shadeClose: true
			,title: titleText
			,offset:[vOpenTop,vOpenLeft]
			,area: [vAreaWidth+'px', 'auto']
			,content: '<div style="padding:20px;"><textarea rows="3" cols="20" style="width: '+vTextareaWidth+'px; height: '+vTextareaHeight+'px; padding: 10px; color: #6f6f6f; font-size: 16px; line-height: 26px;" id="iLayerTextarea">'+fpAreaText+'</textarea>'+fpHtml+'</div>'
			,yes: function(){$(that).click();}
			,zIndex: layer.zIndex 
			,success: function(layero){
				var vTextarea = $("#iLayerTextarea").val();
				vTextarea = fly.stringReplace(vTextarea,"¶","&para");
				$("#iLayerTextarea").val(vTextarea);
				layer.setTop(layero);
			}
		});	
		vLayerIndexArray.push(vLayerIndex);	//添加弹窗索引到Layer弹出层索引数组
	});
}

//Layer 图片框
function fLayerImage(fpImageUrl){
	layer.open({
		type: 1,
		title: false,
		closeBtn: 0,
		//area: '200px',
		skin: 'layui-layer-nobg', //没有背景色
		shadeClose: true,
		content: '<img src="'+fpImageUrl+'">'
	});
}	

//Layer Html 代码
function fLayerHtml(fpStringHtml){
	fpStringHtml = fly.dataDecode(fpStringHtml);
	var vHtmlSee  = '<div class="margin-20">';
		vHtmlSee += '	'+fpStringHtml;
		vHtmlSee += '</div>';
	var vOpenTop = document.documentElement.clientHeight * 0.1 + "px";	
	var vLayerIndex = layui.use('layer', function(){
		layer.open({
			type:1,
			offset:vOpenTop,
			shadeClose:'true',
			title: 'HTML代码预览',
			content: vHtmlSee,
			area: ['80%', '80%'],
		});
	});
}

//Layer Open Page
function fLayerPage(fpPagePath,fpLayerTitle){
	layer.open({
	  	type: 2,
	  	title:fpLayerTitle,
		area: ['90%', '90%'],
		shadeClose: true,
		fixed: false, //不固定
		maxmin: true,
		content: fpPagePath,
	});
}

//Layer Value 框
function fLayerValue(fpTitle,fpValue,fpFunction){
	layui.use('layer', function(){
		var vOpenLeft = (document.documentElement.clientWidth-282)/2 + "px";
		var vOpenTop = (document.documentElement.clientHeight-168)/2 + "px";
		var vLayerIndex = layer.prompt({
			formType: 0,
			offset:[vOpenTop,vOpenLeft],
			title: fpTitle,
			value: fpValue,
			shadeClose:true,
		}, function(value, index, elem){
			fpFunction(value,index);
		});
		vLayerIndexArray.push(vLayerIndex);	//添加弹窗索引到Layer弹出层索引数组
	});
}	


//Layer
function fLayerReorder(fpObj,fpRecodeType,fpAccess,fpParameter,fpPagingFunction){
	var vDataId = "";
	var vDataUpId = "";
	if(fpRecodeType.toUpperCase()=="UP"){
		var vDataId = fpObj.data.id;
		var vDataUpId = fGetRowData(fpObj,-1,"id");
		if(fly.isNull(vDataUpId)){ return layer.msg("已是最上级排序"); }
	}else if(fpRecodeType.toUpperCase()=="DOWN"){
		var vDataId = fpObj.data.id;
		var vDataUpId = fGetRowData(fpObj,+1,"id");
		if(fly.isNull(vDataUpId)){ return layer.msg("已是最下级排序"); }
	}else{
		return layer.msg("不是正确的排序类型");
	}
	//提交控制
	if(vSubmitSwitch==1){return;}
	vSubmitSwitch = 1;
	//提交参数
	fpParameter["id_one"] = vDataId;
	fpParameter["id_two"] = vDataUpId;
	$.post(fpAccess,fpParameter,function(data){
		try{
			var json = eval('('+data+')');
			if(json.result=="true"){
				vSubmitSwitch = 0;
				fpPagingFunction();
				layer.msg(json.infor,{icon:6});
			}else{
				vSubmitSwitch = 0;
				layer.msg(json.infor,{icon:5});
			}
		}catch(e){
			vSubmitSwitch = 0;
		}
		
	})
}

//Layer表格账号提交
function fLayerFormUser(fpId,fpUserName,fpPassword,fpSubmitUrl){
	//样式
	var vFormDiv = "padding:20px;";
	var vLabelSpan = "text-align:right; display:inline-block; width:110px; padding:6px 15px; height:34px; line-height:20px; border:1px solid #e6e6e6; text-align:center; background-color:#FBFBFB; overflow:hidden; white-space:nowrap; text-overflow:ellipsis; box-sizing:border-box; float:left;";
	var vLabelLabel = "display:block; clear:both; height:40px;";
	var vLabelInput = "padding-left:10px; height:32px; border:1px solid #e6e6e6; border-radius:2px; float:left; width:300px; border-left:0;";
	var vLabelSubmit = "margin-left:110px; width:110px; display:inline-block; vertical-align:middle;height: 38px;line-height: 38px;padding: 0 18px;background-color: #009688;color: #fff;white-space: nowrap;text-align: center;font-size: 14px;border: none;border-radius: 2px;cursor: pointer;-moz-user-select: none;-webkit-user-select: none;-ms-user-select: none;";
	var vLabelSubmitUrl = "display:none;";
	//代码组合
	var vCode  = "";
		vCode += '<div style="'+vFormDiv+'">';
		vCode += '	<label style="'+vLabelLabel+'"><span style="'+vLabelSpan+'">ID</span><input style="'+vLabelInput+'" type="text" value="'+fpId+'" id="iFormUserId" disabled/></label>';
		vCode += '	<label style="'+vLabelLabel+'"><span style="'+vLabelSpan+'">账号</span><input style="'+vLabelInput+'" type="text" value="'+fpUserName+'" placeholder="账号" id="iFormUser"/></label>';
		vCode += '	<label style="'+vLabelLabel+'"><span style="'+vLabelSpan+'">密码</span><input style="'+vLabelInput+'" type="text" value="'+fpPassword+'" placeholder="密码" id="iFormPassword"/></label>';
		vCode += '	<label style="'+vLabelSubmitUrl+'"><span>提交地址</span><input type="text" value="'+fpSubmitUrl+'" id="iFormUserSubmitUrl" disabled/></label>';
		vCode += '	<input style="'+vLabelSubmit+'" type="button" value="提交" id="iFormUserSubmit"/>';
		vCode += '</div>';
	//打开添加窗口
	layui.use('layer', function(){
		var vOpenLeft = (document.documentElement.clientWidth-461)/2 + "px";
		var vOpenTop = (document.documentElement.clientHeight-241)/2 + "px";
		var vLayerIndex = layer.open({
			type:1,
			offset:[vOpenTop,vOpenLeft],
			shadeClose:'true',
			title: '账号管理',
			content: vCode,
			area: ['auto', 'auto'],
		});
		vLayerIndexArray.push(vLayerIndex);	//添加弹窗索引到Layer弹出层索引数组
	});	
	
	//提交被点击时
	$("#iFormUserSubmit").live("click",function(){
		var vFormUserId = $("#iFormUserId").val();
		var vFormUser = $("#iFormUser").val();
		var vFormPassword = $("#iFormPassword").val();
		var vFormUserSubmitUrl = $("#iFormUserSubmitUrl").val();
		if(fly.isNull(vFormUserId)){return fLayerMsg("关联ID不得为空");}
		if(fly.isNull(vFormUser)){return fLayerMsg("用户名不得为空");}
		if(fly.isNull(vFormPassword)){return fLayerMsg("用户密码不得为空");}
		if(fly.isNull(vFormUserSubmitUrl)){return fLayerMsg("提交地址不得为空");}
		$.post(vFormUserSubmitUrl+"&username="+vFormUser+"&password="+vFormPassword+"&id="+vFormUserId,function(data){
			try{
				var json = eval('('+data+')');
				if(json.result=="true"){
					return fLayerMsg("添加成功",1000,6);
				}else{
					if(fly.isNull(json.infor)){
						return fLayerMsg(json.infor,1500,5);
					}else{
						return fLayerMsg(json.infor+"（"+json.tips+"）",1500,5);
					}
				}
			}catch(e){
				return fLayerMsg("执行异常，请查看日志。",1500,5);
			}
			
		})
	})
}

//--- Layui table layer ---
//Layer表格详细信息
function fLayerTableInfor(fpFieldUrl,fpLayuiTableObj){
	$.get(fpFieldUrl,function(data){
		var json = eval('('+data+')');
		if(json.result=="true"){
			var vJsonData = json.data;
			var vFieldArray = {};
			for(var i=0;i<vJsonData.length;i++){
				vFieldArray[vJsonData[i].Field] = vJsonData[i].Comment;
			}
			var vObjData = fpLayuiTableObj.data;
			var vObjDataMember = "";
			var addHtml  = '<div class="margin-20">';
				addHtml += '	<table cellspacing="0" cellpadding="0" border="0" class="layui-table" >';
				addHtml += '		<thead>';
				addHtml += '			<th style="width:130px;"><div class="layui-table-cell text-align-right" style="padding:0px;"><span>字段</span></div></th>';
				addHtml += '			<th style="width:220px;"><div class="layui-table-cell text-align-right"><span>名称</span></div></th>';
				addHtml += '			<th style="min-width:200px;"><div class="layui-table-cell"><span>内容</span></div></th>';
				addHtml += '		</thead>';
				addHtml += '		<tbody>';
				for(var key in vObjData){
					vObjDataMember = vObjData[key];
					addHtml += '			<tr><td class="text-align-right cTableDataInforFile">'+key+'</td><td><div class="layui-table-cell text-align-right cTableDataInforDescript">'+vFieldArray[key]+'</div></td><td><div class="layui-table-cell td-middle cTableDataInforValue">'+(fly.isNullNone(vObjDataMember)?"":vObjDataMember)+'</div></td></tr>';
				}
				addHtml += '		</tbody>';
				addHtml += '	</table>';
				addHtml += '</div>';
			var vOpenTop = document.documentElement.clientHeight * 0.1 + "px";
			var vLayerIndex = layui.use('layer', function(){
				layer.open({
					type:1,
					offset:vOpenTop,
					shadeClose:'true',
					title: '详细信息',
					content: addHtml,
					area: ['80%', '80%'],
				});
			});
			vLayerIndexArray.push(vLayerIndex);	//添加弹窗索引到Layer弹出层索引数组
		}else{
			return fLayerMsg(json.infor,1500,5);
		}
	})
}

/**
 * Layer表记录删除
 * obj：layui：table 对象
 * fpDeleteUrl：删除数据提交地址
 * fpDelId："确认删除" 提示要删除的记录ID
 * */
function fLayerTableDelete(obj,fpDeleteUrl,fpDelId=""){
	if(vSubmitSwitch==1){return;}
	vSubmitSwitch = 1;
	layui.use('layer', function(){
		if(fly.isNull(fpDeleteUrl)){
			return layer.msg("记录删除提交地址不得为空",{time:1500,icon:5});
		}
		if(!fly.isNull(fpDelId)){fpDelId = " " + fpDelId + " ";}
		var vLayerIndex = layer.confirm('确认删除'+fpDelId+'？',{"end":function(){vSubmitSwitch = 0;}}, function(index){
			$.post(fpDeleteUrl,function(data){
				var json = eval('('+data+')');
				if(json.result=="true"){
					obj.del();
					fLayerMsg("删除成功",800,6,function(){
						layer.close(index);
					});
				}else{
					fLayerMsg(json.infor,1500,5);
				}
				vSubmitSwitch = 0;
			});
		});
		vLayerIndexArray.push(vLayerIndex);	//添加弹窗索引到Layer弹出层索引数组
	});	
}


//--- 日志 ---

//日志:日志添加：Logs add
function fPageLogAdd(fpLogEvent,fpLog,fpLogColor,fpResultBool){
	//添加日志
	vPageLogs.unshift(fpLog);
	vPageLogsTime.unshift(fly.getTimeNow());
	vPageLogsEvent.unshift(fpLogEvent);
	vPageLogsRrsult.unshift(fpLogColor);
	if(vPageLogs.length>100){
		vPageLogs.pop();
		vPageLogsTime.pop();
		vPageLogsEvent.pop();
		vPageLogsRrsult.pop();
	}
	//日志统计
	vPageJsLogTime = fly.getTimeNow();	//日志：记录时间
	vPageJsLogNumber += 1;	//日志：本次日志数
	vPageJsLogSum += 1;	//日志：日志总数
	if(fpResultBool){
		vPageJsLogSuccess += 1; //日志：本次成功数
		vPageJsLogSuccessSum += 1;	//日志：成功总数
	}else{
		vPageJsLogFail += 1;	//日志：请求失败数
		vPageJsLogFailSum += 1;	//日志：失败总数
	}
}

//日志:日志统计：Logs count fail
function fPageLogInit(){
	vPageJsLogTime = "";		//日志：记录时间
	vPageJsLogNumber = 0;		//日志：本次日志数
	vPageJsLogSuccess = 0;		//日志：本次成功数
	vPageJsLogFail = 0;			//日志：请求失败数
}

//日志:日志被点击时
$(".cPageLogs").live("click",function(){
	var html = "";
	var htmlArray = "";
	var vRrsult = "";
	html  = '<div class="margin-20">';
	html += '<table class="layui-table tableinfor" id="iPageLogTable">';
	html += '	<tr><td style="color:#8e8e8e;margin-bottom:5px;">';
	html += '		<span><a class="a-blue cPageLogsClear" href="javascript:void(0)">清除日志</a></span>';
	html += '	</td></tr>';
	html += '	<tr><td style="height: 0px;border: 0px;padding: 0px; padding-bottom: 6px;"></td></tr>';
	if(vPageLogs.length>0){
		html += '	<tr><td style="color:#8e8e8e;margin-bottom:5px;">';
		html += '		记录时间：<span class="margin-right-10">'+vPageJsLogTime+'</span> 本次日志数：<span class="margin-right-10">'+vPageJsLogNumber+'</span> 本次成功数：<span class="margin-right-10">'+vPageJsLogSuccess+'</span> 请求失败数：<span class="margin-right-10">'+vPageJsLogFail+'</span> 日志总数：<span class="margin-right-10">'+vPageJsLogSum+'</span> 成功总数：<span class="margin-right-10">'+vPageJsLogSuccessSum+'</span> 失败总数：<span class="margin-right-10">'+vPageJsLogFailSum+'</span>';
		html += '	</td></tr>';
		html += '	<tr><td style="height: 0px;border: 0px;padding: 0px; padding-bottom: 6px;"></td></tr>';	
	}
	var vForNumber = 100;
	if(vPageLogs.length<=100){
		vForNumber = vPageLogs.length;
	}
	for(var i=0;i<vPageLogs.length;i++){
		vRrsult = vPageLogsRrsult[i].toString();
		if(vRrsult=="true"){
			htmlArray += '	<tr><th class="parts-font-weight">日志事件：<span class="color-blue">'+vPageLogsEvent[i]+'</span>　日志时间：<span>'+vPageLogsTime[i]+'</span> </th></tr>';	
		}else if(vRrsult=="false"){
			htmlArray += '	<tr><th class="parts-font-weight">日志事件：<span style="color:#FF5722;">'+vPageLogsEvent[i]+'</span>　日志时间：<span>'+vPageLogsTime[i]+'</span> </th></tr>';
		}else if(vRrsult=="green"){
			htmlArray += '	<tr><th class="parts-font-weight">日志事件：<span style="color:#4f984f;">'+vPageLogsEvent[i]+'</span>　日志时间：<span>'+vPageLogsTime[i]+'</span> </th></tr>';
		}else{
			htmlArray += '	<tr><th class="parts-font-weight">日志事件：<span>'+vPageLogsEvent[i]+'</span>　日志时间：<span>'+vPageLogsTime[i]+'</span> </th></tr>';
		}
		htmlArray += '	<tr><td style="word-wrap: break-word; word-break: break-all; overflow: hidden;">'+vPageLogs[i]+'</td></tr>';
	}
	//判断HTML数组组合内容是否为空
	if(fly.isNull(htmlArray)){
		htmlArray = '	<tr><td style="color:#8e8e8e;">暂无日志</td></tr>';
	}
	html+= htmlArray;
	html+= '</table></div>';
	layui.use('layer', function(){
		var vOpenTop = document.documentElement.clientHeight * 0.1 + "px";
		var vLayerIndex = layer.open({
			type:1,
			offset:vOpenTop,
			shadeClose:'true',
			title: '页面日志&nbsp;&nbsp;日志数:<span class="cPageLogsCount">'+vPageLogs.length+'</span>',
			content: html,
			area: ['80%', '80%'],
		});
		vLayerIndexArray.push(vLayerIndex);	//添加弹窗索引到Layer弹出层索引数组
	});	
	
	//日志:清除日志被点击时
	$(".cPageLogsClear").live("click",function(){
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
		//--- 初始化数据 ---
		var html  = "";
			html += '	<tr><td style="color:#8e8e8e;margin-bottom:5px;">';
			html += '		<span><a class="a-blue cPageLogsClear" href="javascript:void(0)">清除日志</a></span>';
			html += '	</td></tr>';
			html += '	<tr><td style="height: 0px;border: 0px;padding: 0px; padding-bottom: 6px;"></td></tr>';
			html += '	<tr><td style="color:#8e8e8e;">暂无日志</td></tr>';
		$("#iPageLogTable").html(html);
		//--- 显示初始化 ---
		$(".cPageLogsCount").html("0");
	})	
	
})


//--- Layuim模板类 ---

//Layui模板:Yes or No
function fTempletYesNo(fpField,fpSelect){
	var vYesSelect = "";
	var vNoSelect = "";
	var vFieldFirstToUpper = fly.stringFirstToUpper(fpField);
	fpSelect = fly.stringToUpper(fpSelect);
	if(fpSelect == "YES" || fpSelect == "TRUE"){
		vYesSelect = "layui-form-onswitch";
	}else if(fpSelect == "NO" || fpSelect == "FALSE"){
		vNoSelect = "layui-form-onswitch";
	}
	var vCode  = '';
		vCode += '<!--是否-->\n';
		vCode += '<script type="text/html" id="templet'+vFieldFirstToUpper+'">\n';
		vCode += '	{{# if(d.'+fpField+' == "true" || d.'+fpField+' == "yes"){ }}\n';
		vCode += '		<div lay-event="laye'+vFieldFirstToUpper+'">\n';
		vCode += '			<div class="layui-unselect layui-form-switch '+vYesSelect+'" lay-skin="switch"><em>是</em><i></i></div>\n';
		vCode += '		</div>\n';
		vCode += '	{{# }else{ }}\n';
		vCode += '		<div lay-event="layeLower">\n';
		vCode += '			<div class="layui-unselect layui-form-switch '+vNoSelect+'" lay-skin="switch"><em>否</em><i></i></div>\n';
		vCode += '		</div>\n';
		vCode += '	{{# } }}\n';
		vCode += '</script>\n';
	$("body").after(vCode);	
}	

/**
 * Layui模板:文字
 * fpField:模板字段
 * fpValueJson:值颜色Json对象
 * fpElseColor:其他值显示时的颜色
 * */
function fTempletValue(fpField,fpValueJson,fpElseColor){
	if(fly.isNull(fpValueJson)){
		return;
	}else{
		//颜色处理
		fpElseColor = fly.isNull(fpElseColor)?"":fpElseColor;
		//字段处理
		var vFieldFirstToUpper = fly.stringFirstToUpper(fpField);
		var vJsonSub = 0;
		var vJsonKey,vJsonValue,vJsonValueArray;
		var vJsonValueColor = "";
		var vJsonValueText = "";
		var vCode  = '';
			vCode += '<!--文字内容-->\n';
			vCode += '<script type="text/html" id="templet'+vFieldFirstToUpper+'">\n';
			for( var key in fpValueJson ){
				vJsonValueColor = "";
				vJsonValueText = "";
				vJsonSub += 1;
				vJsonKey = key;
				vJsonValue = fpValueJson[key];
				vJsonValueArray = vJsonValue.split(":");
				if(vJsonValueArray.length == 1){
					vJsonValueColor = vJsonValueArray[0];
				}else if(vJsonValueArray.length == 2){
					vJsonValueColor = vJsonValueArray[0];
					vJsonValueText = vJsonValueArray[1];
				}
				if(vJsonSub == 1){
					vCode += '	{{# if(d.'+fpField+' == "'+vJsonKey+'"){ }}\n';
					vCode += '		<span style="color:'+vJsonValueColor+'">'+vJsonValueText+'</span>\n';
				}else{
					vCode += '	{{# }else if(d.'+fpField+' == "'+vJsonKey+'"){ }}\n';
					vCode += '		<span style="color:'+vJsonValueColor+'">'+vJsonValueText+'</span>\n';
				}
				//结尾拼接
				if(vJsonSub == Object.keys(fpValueJson).length){
					vCode += '	{{# }else{ }}\n';
					vCode += '		<span style="color:'+fpElseColor+'">{{d.'+fpField+'}}</span>\n';
					vCode += '	{{# } }}\n';
				}	
			}			
			vCode += '</script>\n';
		$("body").after(vCode);	
	}
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

//--- JQuery处理类 ---

//JQuery:选择器
function fJqSelector(fpId){
	if(!(fpId.search(/^#/)>-1 || fpId.search(/^\./)>-1)){
		fpId = "#"+fpId;
	}
	return fpId;
}


//--- 页面配置类 ---

//页面设置:基础:按钮类型
function fButtonType(fpType){
	if(fly.isNull(fpType)){return "";}
	fpType = fly.stringToUpper(fpType);
	if(fpType == "BUTTON" || fpType == "BUTTON-BIG"){
		return " layui-btn ";
	}else if(fpType == "BUTTON-MIDDLE"){
		return " layui-btn layui-btn-sm ";
	}else if(fpType == "BUTTON-SMALL"){
		return " layui-btn layui-btn-xs ";
	}
	return "";
}

//去除"["之后的字符串
function fDelBracketsAfterString(fpString){
	var vIndex = fpString.indexOf("[");
	if(vIndex>0){
		return fpString.substr(0,vIndex);
	}
	return fpString;
}


//页面设置:功能:刷新
function fPageSetRefresh(fpId,fpType,fpStyle){
	$(fJqSelector(fpId)).html('<a class="'+fButtonType(fpType)+'" style="'+fpStyle+'" href="javascript:void(0)" onclick="location.reload()">[ 刷新 ]</a>');
}

//页面设置:功能:新开
function fPageSetWindowOpen(fpId,fpType,fpStyle){
	$(fJqSelector(fpId)).html('<a class="'+fButtonType(fpType)+'" style="'+fpStyle+'"  href="javascript:void(0)" onclick="window.open(window.location.href)">[ 新开 ]</a>');
}

//页面设置:功能:新开
function fPageSetWindowClose(fpId,fpType,fpStyle){
	$(fJqSelector(fpId)).html('<a class="'+fButtonType(fpType)+'" style="'+fpStyle+'"  href="javascript:void(0)" onclick="window.close()">[ 关闭 ]</a>');
}

//页面设置:功能:返回
function fPageSetReturn(fpId,fpType,fpStyle){
	$(fJqSelector(fpId)).html('<a class="'+fButtonType(fpType)+'" style="'+fpStyle+'"  href="javascript:history.back(-1)" style="margin-left:10px;">[ 返回 ]</a>');
}

//参数判断
function ParameterIsNull(infor){
	vSubmitSwitch = 0;	//关闭点击索引
	layer.msg(infor+"不得为空 或 不符合规则",{icon:5,time:1500},function(){});	//参数缺失输出
}


//--- 窗体发生变化时 ---
window.onresize = function () {
	
	if(vCalcSwitch==1){return;}
	vCalcSwitch = 1;
	var vId,vArrayIndex,vElement,vOpenLeft,vOpenTop;
	var vLayerIndexHandleArray = new Array();
	
	//隐藏所有弹窗
	for(var i=0;i<vLayerIndexArray.length;i++){
		vArrayIndex = vLayerIndexArray[i];
		vId = "layui-layer"+vArrayIndex;
		vElement = $("#"+vId);
		if($(vElement).length>0){
			//对象存在隐藏对象
			vElement.hide();
			//存在的弹窗对象加入新的数组（对象不存在删除对象数组成员）
			vLayerIndexHandleArray.push(vArrayIndex);
		}
	}	
	vLayerIndexArray = vLayerIndexHandleArray;
	
	var vTimerIndex = window.setTimeout(function(){
		for(var i=0;i<vLayerIndexArray.length;i++){
			vId = "layui-layer"+vLayerIndexArray[i];
			vElement = $("#"+vId);
			vOpenTop = (document.documentElement.clientHeight-vElement.outerHeight(true))/2 + "px";
			vOpenLeft = (document.documentElement.clientWidth-vElement.outerWidth(true))/2 + "px";
			vElement.css("top",vOpenTop);
			vElement.css("left",vOpenLeft);
			document.getElementById(vId).style.top = vOpenTop;
			eval('$("#'+vId+'").css("top","'+vOpenTop+'");');
			eval('$("#'+vId+'").css("left","'+vOpenLeft+'");');
			vElement.show();
		}
		vCalcSwitch = 0;
		clearTimeout(vTimerIndex);
	},1);
	
}


//--- 监听元素页面点击事件 ---

//函数：图片上传
var clickObj = "";
//选择图片被点击时 
$(".uploadImageClass").live("click",function(){
	clickObj = $(this);
	changeInputObj = clickObj.parents(".updataClickClass").find(".uploadInputFileClass");
	changeInputObj.click();
});
//图片上传
$(".uploadInputFileClass").live("change",function(){
	var imgObj = clickObj.parents(".updataClickClass").find(".imgSrc");
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
//图片关闭
$(".cUploadImageClose").live("click",function(){
	$(this).parents(".uploadImageClass").find(".imgSrc").attr("src","");
})


//--- Layui 相关点击事件 ---
$(".layui-table-cell img").live("click",function(){
	var vImageSrc = $(this).attr("src");
	if(!fly.isNull(vImageSrc)){ fLayerImage(vImageSrc); }
})