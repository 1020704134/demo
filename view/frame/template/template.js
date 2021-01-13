//========== 处理函数 ==========
//去除首位空格
function fReplaceHeadAndTailSpace(fpString){
	fpString = fpString.replace(/[\r\n]/g,"");
	fpString = fpString.replace(/^\s+/g,"");
	fpString = fpString.replace(/\s+$/g,"");
	return fpString;
}
//去除"["之后的字符串
function fDelBracketsAfterString(fpString){
	var vIndex = fpString.indexOf("[");
	if(vIndex>0){
		return fpString.substr(0,vIndex);
	}
	return fpString;
}

//图片字段判断
function fJudgeFieldImage(fpFieldDescript){
	var vImageBo = false;
	if(fpFieldDescript.search(/\[图片\]/)>=0){
		vImageBo = true;
	}
	return vImageBo;
}


//========== 代码 ==========
//组合select:option代码
function fCodeSelectOption(fpOptionArray){
	let code = "";
	let vText,vValue,vMember;
	for(let i=0;i<fpOptionArray.length;i++){
		vMember = fpOptionArray[i];
		vText = vMember["text"];
		vValue = vMember["value"];
		if(fly.isNull(vValue)){vValue = vText;}
		code += '<option value="'+vValue+'">'+vText+'</option>';
	}
	return code;
}
//字段属性
function fCodeFieldAttr(fpField,fpPlaceholder,fpDefault,fpEncode){
	if(fpEncode=="fly"||fpEncode=="FLY"){fpEncode = "FLY_HTML_ENCODE";}
	return ' placeholder="'+fpPlaceholder+'" data-field="'+fpField.toLowerCase()+'" data-default="'+fpDefault+'" data-encode="'+fpEncode+'" ';
}

//组合select:option代码
function fJsonObj(fpString){ return eval('('+'{'+fpString+'}'+')'); }
function fJsonKVObj(fpKey,fpValue){ return eval('('+'{'+'"'+fpKey+'":"'+fpValue+'"'+'}'+')'); }
function fJsonKV(fpKey,fpValue){ return '"'+fpKey+'":"'+fpValue+'"'; }
function fJsonKS(fpKey,fpValue){ return '"'+fpKey+'":'+fpValue+''; }



//========== 上下架相关函数 ==========
//上下架处理函数
function fShelfStateHandle(fpField,fpValue){
	let vCode = "";
	if(fpValue=="false"){
		vCode += '	<div lay-event="layShelfStateSwitch">';
		vCode += '		<div class="layui-unselect layui-form-switch" lay-skin="_switch"><em>上架</em><i></i></div>';
		vCode += '	</div>';
	}else{
		vCode += '	<div lay-event="layShelfStateSwitch">';
		vCode += '		<div class="layui-unselect layui-form-switch layui-form-onswitch" lay-skin="_switch"><em>下架</em><i></i></div>';
		vCode += '	</div>';
	}
	return vCode;
}	
//上下架处理模板
function fShelfStateTemplet(fpField){
	return '<div>{{fShelfStateHandle("'+fpField+'",d.'+fpField+')}}</div>';
}
//上下架点击设置修改
function fShelfStateSet(fpThisObj,fpParameter){
	var vText = fpThisObj.find("em").text();
	var vThisObj = fpThisObj;
	var vTips = "";
	var vSwitch = "";
	if(vText == '下架'){
		vSwitch = "false";
		vTips = "下架";
	}else if(vText == '上架'){
		vSwitch = "true";
		vTips = "上架";
	}
	layer.confirm('确定要'+vTips+'吗？', function(){
		var postData = fpParameter;
			postData["shelfstate"] = vSwitch;
        var loadIndex = layer.load(2,{time:10*1000});
        $.post(urlAjax,postData,function(data){
		    var json = eval('('+data+')');
			if(json.result=="true"){
				if(vText == '下架'){
					vThisObj.find(".layui-unselect").removeClass("layui-form-onswitch");
					vThisObj.find(".layui-unselect em").text("上架");
				}else if(vText == '上架'){
					vThisObj.find(".layui-unselect").addClass("layui-form-onswitch");
					vThisObj.find(".layui-unselect em").text("下架");
				};
				layer.msg(json.infor,{icon:6,time:1000},function(){});
			}else{
				layer.msg(json.infor,{icon:5,time:1000},function(){});
			}
			layer.close(loadIndex);
   		});
	});
}
//- Demo
//模板代码
//,{field:'shelfState', title:'店铺状态',templet:fShelfStateTemplet("shelfState")}
//事件监听代码
//let vParameter = {"line":"system","method":"sysshopolshopsetshelfstate","id":obj.data.id};
//fShelfStateSet($(this),vParameter);


//========== 时间格式 ==========
//时间模板
function fTimeTemplet(){
	return '<div><div>{{d.addTime}}</div><div>{{d.updateTime}}</div></div>';
}


//========== 关键字相关函数 ==========
//显示搜索关键字处理函数
function fSearchKeyHandle(fpValue,fpKey){
	fpValue = fpValue.toString();
	var vReplaceString = new RegExp(fpKey);
	return fpValue.replace(vReplaceString,"<span style=\"background-color:#fffa62;\">"+fpKey+"</span>");
}
//搜索模板
function fSearchKeyTemplet(fpDataField,fpLikeField,fpLikeKey){
	if(fpDataField==fpLikeField){
		return '<div>{{fSearchKeyHandle(d.'+fpDataField+',"'+fpLikeKey+'")}}</div>';
	}
	return "";
}
//- Demo
//templet:fSearchKeyTemplet("id",fpLikeField,fpLikeKey)


//========== 图标模板 ==========
function fIconTemplet(fpDataField){
	return '<div><i class="layui-icon icon">{{d.'+fpDataField+'}}</i></div>';
}

//========== 审核函数（工具按钮） ==========
//事件：审核设置
function fEventCheckSet(fpInterface,fpObj,fpField,fpTitle){
	layer.confirm('确定 '+'<span style="color:green;">审核通过</span>'+' '+fpTitle+'？', function(){
        var loadIndex = layer.load(2,{time:10*1000});
        $.post(urlAjax,fpInterface,function(data){
		    var json = eval('('+data+')');
		    if(json.result=="true"){
		    	fpObj.update({fpField:"<span style='color:green;'>已审核</span>"});
				layer.msg(json.infor,{icon:6,time:1000},function(){});
			}else{
				layer.msg(json.infor,{icon:5,time:1000},function(){});
			}
			layer.close(loadIndex);
   		});
	});
}
//- Demo
//fEventCheckSet("line=system&method=sysshopolshopset&shelfstate=true&id="+obj.data.id,obj,"shelfState","测试");


//========== 审核相关函数（内容按钮） ==========
//审核处理函数
var vCheckVarInterface = "";
var vCheckVarTitle = "";
var vCheckVarField = "";
var vCheckVarOpenIndex = ""; 
function fCheckHandle(fpFieldValue){
	if(fpFieldValue=="false"){
		return '<span style="color:red;"><a class="layui-btn layui-btn-xs layui-btn-danger" onclick="fCheckClick(this)">待审核</a></span>';
	}
	return '<span style="color:green;">已审核</span>';
}
//审核模板
function fCheckTemplet(fpField,fpInterface,fpTitle){
	vCheckVarInterface = fpInterface;
	vCheckVarTitle = fpTitle;
	vCheckVarField = fpField;
	return "<div>{{fCheckHandle(d."+fpField+")}}</div>";
}
//审核点击
function fCheckClick(fpObj){
	let vJQObj = $(fpObj);
	let vTr = vJQObj.parents("tr");
	let vId = vTr.find("td[data-field='id']").text();
	if(vCheckVarTitle==undefined){vCheckVarTitle="";}
	vCheckVarOpenIndex = layer.confirm('确定 '+'<span style="color:green;">审核通过</span>'+' '+vCheckVarTitle+'？', function(){
		var postData = vCheckVarInterface+"&id="+vId;
        var loadIndex = layer.load(2,{time:10*1000});
        $.post(urlAjax,postData,function(data){
		    var json = eval('('+data+')');
		    if(json.result=="true"){
		    	vTr.find("td[data-field='"+vCheckVarField+"']").html("<div class=\"layui-table-cell laytable-cell-1-shopName\"><span style='color:green;'>已审核</span></div>");
				layer.msg(json.infor,{icon:6,time:1000},function(){});
			}else{
				layer.msg(json.infor,{icon:5,time:1000},function(){});
			}
			layer.close(loadIndex);
			layer.close(vCheckVarOpenIndex);
   		});
	});
}
//- Demo:审核按钮
//,{field:'shopName', title:'审核测试',templet:fCheckTemplet("shopName",vConfigSet+"&shopname=true","ces")}



//========== 搜索字段选项卡渲染 ==========
function fSelectOption(fpParameter,fpFieldArray,fpSelectId){
	fly.ajax(urlAjax,fpParameter,function(data){
		var json = data;
		if(json.result=="true"){
			var vThisJsonDataTableField = json.data;
			var vThisFieldJson = "";
			var vThisFieldJsonField = "";
			var vHtmlOption = "";
			for(var i=0;i<vThisJsonDataTableField.length;i++){
				vThisFieldJson = vThisJsonDataTableField[i];
				vThisFieldJsonField = vThisFieldJson["Field"];
				for(let c=0;c<fpFieldArray.length;c++){
					if(vThisFieldJsonField==fpFieldArray[c]){
						vHtmlOption += '<option value="'+vThisFieldJsonField+'">'+fDelBracketsAfterString(vThisFieldJson["Comment"])+'</option>';
						break;
					}
				}
			}
			//RBAC类型
			$("#"+fpSelectId).html(vHtmlOption);
			//初始化组件  
			layui.use('form', function() { var form = layui.form; form.render(); });
		}else{
			layui.use('layer', function(){
			  var layer = layui.layer;
			  return layer.msg("字段数据获取失败",{icon:5});
			}); 
		}
	})
}
//- Demo:搜索字段
//fSelectOption({"line":"system","method":"sysshopolshopgettablefield"},["userId","shopName","shopBrand"],"iSearchField");



//========== 详细信息 ==========
//详细信息列表函数
var vLayuiTableTrObj;
var vInterfaceInforSetParam;
function fDataInfor(fpFieldInterface,fpObj,fpSetInterface,fpNotSetArray){
	var vLoadIndex = layer.load(2, {time: 10 * 1000});
	vLayuiTableTrObj = fpObj;
	fpSetInterface["id"] = fpObj.data.id;
	vInterfaceInforSetParam = fpSetInterface;
	$.get(urlAjax+fpFieldInterface,function(data){
		var json = eval('('+data+')');
		if(json.result=="true"){
			var vJsonData = json.data;
			var vFieldArray = {};
			for(var i=0;i<vJsonData.length;i++){
				vFieldArray[vJsonData[i].Field] = vJsonData[i].Comment;
			}
			var vObjData = fpObj.data;
			var vObjDataMember = "";
			var vObjId = "";
			let vKeyFieldValue,vFieldDescript; 
			var vSetKeyBool = false;
			var addHtml  = '<div class="margin-20">';
				addHtml += '	<table cellspacing="0" cellpadding="0" border="0" class="layui-table" >';
				addHtml += '		<thead>';
				addHtml += '			<th style="width:130px;"><div class="layui-table-cell text-align-right" style="padding:0px;"><span>字段</span></div></th>';
				addHtml += '			<th style="width:220px;"><div class="layui-table-cell text-align-right"><span>名称</span></div></th>';
				addHtml += '			<th style="min-width:200px;"><div class="layui-table-cell"><span>内容</span></div></th>';
				addHtml += '			<th style="min-width:80px;"><div class="layui-table-cell"><span>操作</span></div></th>';
				addHtml += '		</thead>';
				addHtml += '		<tbody>';
				for(var key in vObjData){
					vSetKeyBool = false;
					vObjDataMember = fly.dataDecode(vObjData[key]);
					vFieldDescript = vFieldArray[key];
					if(key == "id"){ vObjId = vObjDataMember; }
					vKeyFieldValue = fly.isNullNone(vObjDataMember)?"":vObjDataMember;
					if(fJudgeFieldImage(vFieldDescript)){
						vKeyFieldValue = '<img src="'+vKeyFieldValue+'"/><div style="display:none;">'+vKeyFieldValue+'</div>';
					}
					addHtml += '			<tr>';
					addHtml += '				<td class="text-align-right cFieldName">'+key+'</td>';
					addHtml += '				<td><div class="layui-table-cell text-align-right">'+fDelBracketsAfterString(vFieldDescript)+'</div></td>';
					addHtml += '				<td><div class="layui-table-cell td-middle cFieldValue">'+vKeyFieldValue+'</div></td>';
					if(!fly.isNull(fpNotSetArray)){
						for(let i=0;i<fpNotSetArray.length;i++){
							if(key==fpNotSetArray[i]){
								addHtml += '				<td></td>';
								vSetKeyBool = true;
								break;
							}
						}
					}
					if(!vSetKeyBool){
						if(key=="id"||key=="onlyKey"||key=="addTime"){
							addHtml += '				<td></td>';
						}else{
							if(fJudgeFieldImage(vFieldDescript)){
								addHtml += '				<td><a class="layui-btn layui-btn-xs layui-btn-xs" onclick="fLayuiTableInforSet(this)">修改</a><a style="margin-left:4px;" class="layui-btn layui-btn-xs layui-btn-xs" onclick="fLayuiTableInforImageSet(this)">图片修改</a></td>';	
							}else{
								addHtml += '				<td><a class="layui-btn layui-btn-xs layui-btn-xs" onclick="fLayuiTableInforSet(this)">修改</a></td>';	
							}
						}	
					}
					addHtml += '			</tr>';
				}
				addHtml += '		</tbody>';
				addHtml += '	</table>';
				addHtml += '</div>';
			layer.open({
				type:1,
				id:1,
				shadeClose:'true',
			  	title: '详细信息：'+'<span>'+fpObj.data.id+'</span>',
			 	content: addHtml,
			  	area: ['80%', '80%'],
			  	success:function(layero, index){
					fLayerSuccess(index);
					layer.close(vLoadIndex);
				},
			});
		}else{
			return layer.msg(json.tips+":"+json.infor,{icon:5});
		}
	})
}
//修改函数
var vLayerIndexSetOpen;
var vLayerTableTrObj;
function fLayuiTableInforSet(fpThis){
	vLayerTableTrObj = $(fpThis).parents("tr");
	var fpSetInterface = vInterfaceInforSetParam;
	var vFieldName = vLayerTableTrObj.find(".cFieldName").html();
	var vFieldValue = vLayerTableTrObj.find(".cFieldValue").text();
	vLayerIndexSetOpen = layer.prompt({
	  	formType: 0,
	  	value: vFieldValue,
	  	title: '<span id="iFieldName">'+vFieldName+'</span>' + ' 字段值修改',
	  	shadeClose:'true',
	  	area: ['500px', '350px'] 	//自定义文本域宽高
	}, function(value, index, elem){
		if(vSubmitSwitch==1){
			return;
		}
		vSubmitSwitch = 1;
	  	var vUpdateFieldName = $("#iFieldName").html();
	  	var vUpdateFieldNameLower = vUpdateFieldName.toLowerCase();
	  	fpSetInterface[vUpdateFieldNameLower] = value;
	  	var loadIndex = layer.load(2,{time:10*1000});	//加载层
		$.post(urlAjax,fpSetInterface,function(data){
			var json = eval('('+data+')');
			if(json.result=="true"){
				vLayerTableTrObj.find(".cFieldValue").html(value);
				eval("vLayuiTableTrObj.update({"+ vUpdateFieldName + ":'"+value+"'});");
				layer.close(vLayerIndexSetOpen);
				layer.msg("修改成功",{icon:6,time:600},function(){vSubmitSwitch = 0;});
			}else{
				vSubmitSwitch = 0;
				layer.msg(json.infor,{icon:5},function(){vSubmitSwitch = 0;});
			}
			layer.close(loadIndex); //关闭加载层
		})
	});
}
//图片修改函数
function fLayuiTableInforImageSet(fpThis){
	vLayerTableTrObj = $(fpThis).parents("tr");
	let vSetField = $(fpThis).parents("tr").find(".cFieldName").html();
	let vImageUrl = $(fpThis).parents("tr").find(".cFieldValue").text();
	if(!(vImageUrl.indexOf("//")>=0)){vImageUrl="";}
	let vCode  = "";
		vCode += '<div id="" class="layui-layer-content" style="height: 200px;">';
		vCode += '	<div class="user-add">';
		vCode += fCodeImage(vImageUrl,vSetField);
		vCode += '		<input style="margin-top:6px;" type="button" value="提交" class="layui-btn layui-submit" onclick="fSetImage(this)">';
		vCode += '	</div>';
		vCode += '</div>';
	//打开添加窗口
	vLayerIndexSetOpen = layer.open({
		type:1,
		shadeClose:'true',
		title: vSetField+" 字段图片修改",
		content: vCode,
		area: ['auto', 'auto'],
		//success:fpSuccessFunction,
	});	
}

//========== 图片相关 ==========
//图片上传代码
function fCodeImage(fpImageUrl,fpImageField,fpDescript,fpWriteType="MUST"){
	if(fly.isNull(fpDescript)){fpDescript = "修改图片";}
	let vMustIcon = "",vMustDefault = "";
	if(fpWriteType=="MUST"){
		vMustIcon = '<i class="must-fill">*</i>';	
	}else{
		vMustDefault = "none";
	}
	let vCode  = "";
		vCode += '		<label class="height-63" style="width:480px;">';
		vCode += '			<span class="height-62">'+vMustIcon+fpDescript+'</span>';
		vCode += '			<!-- 图片LOGO -->';
		vCode += '			<div class="updataClickClass">';
		vCode += '				<form enctype="multipart/form-data">';
		vCode += '					<input name="file" type="file" style="display:none;" class="cInputFile">';
		vCode += '				</form>';
		vCode += '				<div class="container float-left position-relative">';
		vCode += '					<!-- 遮罩层 -->';
		vCode += '					<div class="uploadMaskClass">';
		vCode += '						<i class="layui-icon layui-anim layui-anim-rotate layui-anim-loop color-white font-size-28 margin-top-15"></i>';
		vCode += '					</div>';
		vCode += '					<!-- add file -->';
		vCode += '					<label class="layui-upload-drag cUploadImage" style="padding:11px 0px 11px 40px !important; width: 167px; display: inline-flex !important; margin-top: 0px !important; border-left: 0px; margin-left: 1px !important;">';
		vCode += '						<i class="layui-icon uploadImageiClass"></i>';
		vCode += '						<p class="width-140 logoImageClass">请选择图片</p>';
		vCode += '					</label>';
		vCode += '					<label class="layui-upload-drag uploadImageUrlClass">';
		vCode += '						<img src="'+fpImageUrl+'" class="images-upload" data-field="'+fpImageField.toLowerCase()+'" data-default="'+vMustDefault+'" >';
		vCode += '					</label>';
		vCode += '				</div>';
		vCode += '			</div>';
		vCode += '		</label>';
	return vCode;
}
//----- 上传监听 -----
var vUploadClickObj = "";
//选择图片被点击时 
$(".cUploadImage").live("click",function(){
	vUploadClickObj = $(this);
	vUploadClickObj.parent().siblings("form").find(".cInputFile").click();
});
//图片上传
$(".cInputFile").live("change",function(){
	if($(this).get(0).files.length==0){return;}
	var imgObj = vUploadClickObj.next().find(":first");
	//遮罩层加载显示
	var uploadMaskObj = vUploadClickObj.prev();
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
			uploadMaskObj.hide();
		},
		error:function(xhr,status,info){}
	});
});
//----- 提交图片修改 -----
function fSetImage(fpObj){
	var vImageSrc,vField;
	let vTagName,vValue;
	let vParameter = {};
	$(fpObj).parents(".user-add").find("[data-field]").each(function(){
		vTagName = $(this)[0].tagName;
		if(vTagName=="IMG"){
			vValue = $(this).attr("src");
		}else if(vTagName=="INPUT"){
			vValue = $(this).val();
		}else{
			vValue = $(this).text();
		}
		vField = $(this).attr("data-field");
		vImageSrc = vValue;
		vParameter[vField.toLowerCase()] = vValue;
	})
	vParameter = $.extend({}, vInterfaceInforSetParam,vParameter);
	var vLoadIndex = layer.load(2, {time: 10 * 1000});
	$.post(urlAjax,vParameter,function(data){
		var json = eval('('+data+')');
		if(json.result=="true"){
			vLayerTableTrObj.find(".cFieldValue").html('<img src="'+vImageSrc+'"/><div style="display:none;">'+vImageSrc+'</div>');
			eval("vLayuiTableTrObj.update({"+ vField + ":'"+vImageSrc+"'});");
			layer.close(vLayerIndexSetOpen);
			layer.msg("修改成功",{icon:6,time:600},function(){vSubmitSwitch = 0;});
		}else{
			vSubmitSwitch = 0;
			layer.msg(json.infor,{icon:5},function(){vSubmitSwitch = 0;});
		}
		layer.close(vLoadIndex);
	})
}


//========== 删除数据 ==========
//事件：数据删除
function fEventDataDelete(obj,fpDeleteUrl,fpDelId=""){
	if(vSubmitSwitch==1){return;}
	vSubmitSwitch = 1;
	layui.use('layer', function(){
		if(fly.isNull(fpDeleteUrl)){
			return layer.msg("记录删除提交地址不得为空",{time:1500,icon:5});
		}
		if(!fly.isNull(fpDelId)){fpDelId = " " + fpDelId + " ";}
		var vLayerIndex = layer.confirm('确认删除'+fpDelId+'？',{"end":function(){vSubmitSwitch = 0;}}, function(index){
			var vLoadIndex = layer.load(2, {time:10 * 1000, zIndex:1000000000});
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
				layer.close(vLoadIndex);
				vSubmitSwitch = 0;
			});
		});
		vLayerIndexArray.push(vLayerIndex);	//添加弹窗索引到Layer弹出层索引数组
	});	
}


//========== 图片模板 ==========
//模板：图片模板
function fTempletImage(fpField){
	var vCode  = "<script type=\"text/html\">";
		vCode += "{{# var vImageUrl = d."+fpField+";}}";
		vCode += "{{# if(vImageUrl.indexOf('//')>=0){ }}";
		vCode += "	<div><img style=\"max-width:150px;\" src=\"{{d."+fpField+"}}\"/></div></div>";
		vCode += "{{# }else{ }}";
		vCode += "	<div><div data-url=\"{{d."+fpField+"}}\">{{d."+fpField+"}}</div></div>";
		vCode += "{{# } }}";
		vCode += "</script>";
	return vCode;
}


//========== 状态描述 ==========
function fTempletStateJson(fpState,fpColor,fpText){
	return {"state":fpState,"color":fpColor,"text":fpText};
}
function fTempletState(fpField,fpStateJson){
	if(fly.isNull(fpStateJson)||fly.isNull(fpField)){return "";}
	if(fpStateJson.length==0){return "";}
	//逻辑
	let vJson,vColor,vText,vForString="";
	for(let i=0;i<fpStateJson.length;i++){
		vJson = fpStateJson[i];
		vColor = vJson["color"];
		vText = vJson["text"];
		if(fly.isNull(vColor)){vColor = "";}
		if(i==0){
			vForString += "{{# if(vState==\""+fpStateJson[i]["state"]+"\"){ }}";
			vForString += "	<span style=\"color:"+fpStateJson[i]["color"]+";\">"+fpStateJson[i]["text"]+"</span>";
		}else{
			vForString += "{{# }else if(vState==\""+fpStateJson[i]["state"]+"\"){ }}";
			vForString += "	<span style=\"color:"+fpStateJson[i]["color"]+";\">"+fpStateJson[i]["text"]+"</span>";
		}
	}
	//模板代码
	var vCode  = "<script type=\"text/html\">";
		vCode += "{{# var vState = d."+fpField+";}}";
		vCode += vForString;
		vCode += "{{# } }}";
		vCode += "</script>";
	return vCode;
}



//========== 弹窗 ==========
//Infor
function LayerOpenTextarea(layer,fpBody,fpTitle,fpOnclick,fpOnclickText,fpConfig){
	var titleText = "信息";
	var vWidth = fly.isNull(fpConfig.width)?"330":fpConfig.width;
	var vHeight = fly.isNull(fpConfig.height)?"160":fpConfig.height;
	if(!fly.isNull(fpTitle)){titleText = fpTitle;}
	if(!fly.isNull(fpOnclick)){
		if(fly.isNull(fpTitle)){
			fpTitle = "提交";
		}
		fpOnclick = '<button class="layui-btn" onclick="'+fpOnclick+'(this)" style="margin-top:10px;display:block;">'+fpOnclickText+'</button>';
	}else{
		fpOnclick = "";
	}
	layer.open({
		type: 1
		,shadeClose: true
		,title: titleText
		,area: ['auto', 'auto']
		,content: '<div style="padding:20px;position:relative;"><textarea rows="3" cols="20" style="width:'+vWidth+'px; height:'+vHeight+'px; padding: 10px; color: #6f6f6f; font-size: 16px; line-height: 26px;" id="iLayerOpenTextarea"></textarea>'+fpOnclick+'</div>'
		,yes: function(){$(that).click();}
		,zIndex: layer.zIndex 
		,success: function(layero){
			fpBody = fly.dataDecode(fpBody); 
			fpBody = fly.stringReplace(fpBody,"¶","&para");
			$("#iLayerOpenTextarea").val(fpBody);
			layer.setTop(layero);
		}
	});
}