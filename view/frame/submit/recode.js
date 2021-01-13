//该JS以来与 JQuery和Layui，使用前需先引入以上两个文件

//--- 页面加载完成 ---
$(function(){
	
	
	//--- 页面配置 ---
	//URL参数:记录ID
	var vPagePId = fly.getParameter("id");
	if(!fly.isNull(vPagePId)){ 
		$("#iPageTitleType").html("记录修改"); 
		$("#iRecordRevoke").hide();
	}else{
		$("#iPageTitleType").html("记录添加");
	}
	var vPagePField = fly.getParameter("field");
	var vPagePFieldValue = fly.getParameter("field_value");
		if(!fly.isNull(vPagePField)&&!fly.isNull(vPagePFieldValue)){
			$("[data-field='"+vPagePField+"']").val(vPagePFieldValue);
		}
	var vPagePGroup = fly.getParameter("group");
		if(fly.isNull(vPagePGroup)){vPagePGroup="";}
		$("[data-field='recodeGroup']").val(vPagePGroup);
	//页面配置：修改
	let vConfigSet = $("#iPageConfig span[data-type='set']");
	let vSetLine = vConfigSet.attr("data-line");
	let vSetMethod = vConfigSet.attr("data-method");
	let vSetInfor = vConfigSet.attr("data-infor");
	//页面配置：分页
	let vConfigPaging = $("#iPageConfig span[data-type='paging']");
	let vPagingLine = vConfigPaging.attr("data-line");
	let vPagingMethod = vConfigPaging.attr("data-method");
	let vPagingInfor = vConfigPaging.attr("data-infor");
	let vPagingParameter = vConfigPaging.attr("data-parameter");
		vPagingParameter = fRenderParameter(vPagingParameter);
		vPagingParameter = fParameterToJson(vPagingParameter);
	
	//--- 数据初始化 ---
	//省市区数据渲染
	if($("#iAreaProvince").size()>0){
		fAreaSelectRender();	
	}
	
	//--- 变量 ---
	var vEditorArray = [];		//富文本对象数组
	var vEditorIdArray = [];	//富文本ID数组

	
	//layui组件初始化
	layui.use(['form', 'layedit', 'laydate'], function(){
		
		//layui组件
		var form = layui.form ,layer = layui.layer ,layedit = layui.layedit ,laydate = layui.laydate;
		
		//富文本配置
		layedit.set({
			uploadImage: {
	      		url: urlAjax+'line=image&method=upload',
	    		type: 'post'
	      		,done: function(res){
	    			var picturePath = $('input[name="Imgsrc"]');
	      				picturePath.val(res.infor);
					}
				,error: function(){
					layer.msg("上传失败",{icon:5,time:1500});
				}
			} 
			,tool: [
				'html', 'undo', 'redo', 'strong', 'italic', 'underline', 'del', 'addhr'
				, '|','removeformat','fontFomatt','fontSize', 'colorpicker'
				, '|', 'left', 'center', 'right', '|', 'link', 'unlink', 'image_alt', 'anchors'
				, '|', 'table', 'fullScreen'
			]
			,height: '200px'
		});		
	
		//初始化富文本编辑框
		$(".cPageFieldTextarea").each(function(){
			let vId = $(this).attr("id");
			vEditorIdArray.push(vId);
			vEditorArray.push(layedit.build(vId));
		})
		
		//撤销
		$("#iRecordRevoke").click(function(){
			//清除富文本内容
			for(let key in vEditorArray){
				layedit.setContent(vEditorArray[key],"");
			}
			//清除Input内容
			$("input[data-field]").val("");
			//清除下拉选内容
			$('select[data-field]').val('请选择');
			form.render('select');
			//图片清除
			$("img[data-field]").attr("src","");
		})
		
		
		//数据提交
		var submitIndex = 0;
		function fTimeOut(){
			setTimeout(function(){
				if(submitIndex==1){
					submitIndex = 0;	
				}
			},5000);
		}
		
		//记录数据提交
		$("#iRecordDataSubmit").click(function(){
			//加载和控制层
			if(submitIndex == 1){return;}
			submitIndex = 1;
			var loadIndex = layer.load(2,{time:10*1000});
			//URL参数:记录ID
			var vPagePId = fly.getParameter("id");
			//页面设置：添加
			let vConfigAdd = $("#iPageConfig span[data-type='add']");
				fpLine = vConfigAdd.attr("data-line");
				fpMethod = vConfigAdd.attr("data-method");
				fpInfor = vConfigAdd.attr("data-infor");
			//页面配置：修改
			let vConfigSet = $("#iPageConfig span[data-type='set']");
			let vSetInfor = vConfigSet.attr("data-infor");	
			
			//--- 配置层 ---
			var vSuccessInfor = fpInfor;
			var vPostParameter = {"line":fpLine,"method":fpMethod};
			
			//--- 固定流程 ---
			let vValueDefault,vValue,vValueField,vFieldLower,vValueEncode;
			$("[data-field]").each(function(){
				vValueDefault = $(this).attr("data-default");
				vValueField = $(this).attr("data-field");
				vFieldLower = vValueField.toLowerCase();
				vValueEncode = $(this).attr("data-encode");
				//标签取值判断
				if($(this)[0].tagName=="IMG"){
					vValue = $(this).attr("src");	
				}else if($(this)[0].tagName=="TEXTAREA"){
					for(let i=0;i<vEditorIdArray.length;i++){
						if(vEditorIdArray[i]==$(this).attr("id")){
							vValue = fly.dataEncode(layedit.getContent(vEditorArray[i]));
						}
					}
				}else{
					vValue = $(this).val();	
				}
				//为空判断
				if(fly.isNull(vValue)){
					//默认值判断
					if(fly.isNull(vValueDefault)){
						vPostParameter[vFieldLower] = "";	
					}else{
						vPostParameter[vFieldLower] = vValueDefault;	
					}
				}else{
					//编码判断
					if(vValueEncode=="FLY_HTML_ENCODE"){
						vValue = fly.dataEncode(vValue);
					}
					vPostParameter[vFieldLower] = vValue;
				}
			})	
			//提交数据
			fTimeOut();	//5秒后关闭限制开关
			if(!fly.isNull(vPagePId)){vPostParameter["id"]=vPagePId;}
			$.post(urlAjax,vPostParameter,function(data){
				layer.close(loadIndex);	//关闭加载层
				var json = eval('('+data+')');
				if(json.result=="true"){
					submitIndex = 0;
					layer.closeAll();
					if(fly.isNull(vPagePId)){$("#iRecordRevoke").click();}
					return layer.msg(json.infor,{icon:6,time:800});
				}else{
					submitIndex = 0;
					return layer.msg(json.infor,{icon:5,time:800});
				}
				submitIndex = 0;
			}).error(function(){
				submitIndex = 0;
			})
			
		})
		
		
		//渲染数据
		if(!fly.isNull(vPagePId)){
			var vPostParameter = {"line":vPagingLine,"method":vPagingMethod,"page":"1","limit":"1","id":vPagePId};
				vPostParameter = $.extend(vPostParameter, vPagingParameter);
			//Layer
		    var loadIndex = layer.load(2,{time:10*1000});
		    //--- 数据渲染 ---
		    $.post(urlAjax,vPostParameter,function(data){
				var json = eval('('+data+')');
				if(json.result == "true"){
					var jsonData = json.data;
					if(json.count!="0"){
						jsonData = jsonData[0];
						//--- 固定流程 ---
						let vValueField,vFieldLower,vTagName,vJsonValue;
						$("[data-field]").each(function(){
							vValueField = $(this).attr("data-field");
							vFieldLower = vValueField.toLowerCase();
							vFieldId = "i"+vValueField.slice(0, 1).toUpperCase() + vValueField.slice(1);
							vTagName = $(this)[0].tagName;
							//字段名对比
							for(var key in jsonData){
								vJsonValue = jsonData[key];
								if(key==vValueField&&!fly.isNull(vJsonValue)){
									//标签取值判断
									if($(this)[0].tagName=="IMG"){
										$(this).attr("src",vJsonValue);	
									}else if($(this)[0].tagName=="TEXTAREA"){
										for(let i=0;i<vEditorIdArray.length;i++){
											if(vFieldId==$(this).attr("id")){
												layedit.setContent(vEditorArray[i],fly.dataDecode(vJsonValue));
											}
										}
									}else{
										$(this).val(vJsonValue);	
									}
								}
							}
						})	
					}
				}
				layer.close(loadIndex);
			});
		}
		
	})
	

});



//将参数值传入字段中
function fUrlParameterToField(fpParameterName,fpFieldName){
	let vFieldValue = fly.getParameter(fpFieldName);
	if(!fly.isNull(vFieldValue)){
		$("[data-field='"+fpParameterName+"']").val(vFieldValue);
	}
}

//处理数据：参数代码渲染
function fRenderParameter(fpCode){
	if(fly.isNull(fpCode)){return "";}
	var vRenderField,vField,vFieldArray,vFieldStringArray,vParameterValue;
	var vReg = /{{[\.a-zA-Z0-9_-]+}}/ig;
	vFieldArray = fpCode.match(vReg);
	if(fly.isNull(vFieldArray)){
		return fpCode;
	}
	for(let i=0;i<vFieldArray.length;i++){
		vRenderField = vFieldArray[i];
		vField = vRenderField.replace(/[{}]/ig,"");
		vFieldStringArray = vField.split(".");
		if(vFieldStringArray[0]=="url"&&vFieldStringArray.length>1){
			vParameterValue = fly.getParameter(vFieldStringArray[1]);
			fpCode = fpCode.replace(new RegExp(vRenderField,"g"),vParameterValue);
		}
	}
	return fpCode;
}

//参数字符串转Json对象数组
function fParameterToJson(fpParameter){
	if(fly.isNull(fpParameter)){return {};}
	var vParaArray = fpParameter.split("&");
	var vMember,vEqualArray;
	var vJsonObj={};
	for(let key in vParaArray){
		vMember = vParaArray[key];
		vEqualArray = vMember.split("=");
		if(vEqualArray.length==2){
			vJsonObj[vEqualArray[0]] = vEqualArray[1];	
		}
	}
	return vJsonObj;
}