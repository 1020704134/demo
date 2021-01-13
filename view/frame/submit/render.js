//轮播初始化
function fRenderBanner(){
	layui.use('carousel', function(){
		var carousel = layui.carousel;
	  	//建造实例
	  	carousel.render({
		    elem: '#iBanner'
		    ,width: '100%' //设置容器宽度
		    ,arrow: 'always' //始终显示箭头
		    //,anim: 'updown' //切换动画方式
	  	});
	});
}

//代码属性新增
function fCodeAttrAdd(fpCode,fpTagName,fpAttrName,fpAttrValue){
	var vFindStr = "<"+fpTagName.toLowerCase();
	if(fpCode.indexOf('"'+fpAttrName+'"')>-1){
		return fpCode;
	}
	var vSub = fpCode.indexOf(vFindStr);
	if(vSub==-1){
		return fpCode;
	}
	vSub += vFindStr.length;
	return fpCode.slice(0, vSub) + " "+fpAttrName+"=\""+fpAttrValue+"\"" + fpCode.slice(vSub);
}

//代码属性获取
function fCodeAttrGet(fpCode,fpAttrName){
	var vRenderField,vField,vFieldArray;
	var vAttrStr = " "+fpAttrName+"=\"";
	var vReg = new RegExp(vAttrStr+"[A-Za-z0-9-_,、]+\"","g");
	vFieldArray = fpCode.match(vReg);
	if(fly.isNull(vFieldArray)){return "";}
	var vMatchValue = vFieldArray[0]; 
		vMatchValue = vMatchValue.replace(new RegExp(vAttrStr,"g"),"");
		vMatchValue = vMatchValue.replace(/"/g,"");
	return vMatchValue;
}

//代码字段数据
function fCodeField(fpCode,fpData){
	var vRenderField,vField,vFieldArray,vRFArray,vRFArrayLength;
	var vReg = /{{[\.a-zA-Z0-9_-]+}}/ig;
	vFieldArray = fpCode.match(vReg);
	if(fly.isNull(vFieldArray)){
		return fpCode;
	}
	for(let i=0;i<vFieldArray.length;i++){
		vRenderField = vFieldArray[i];
		vField = vRenderField.replace(/[{}]/ig,"");
		if(vField.indexOf(".")==-1){
			fpCode = fpCode.replace(new RegExp(vRenderField,"g"),fpData[vField]);	
		}else{
			vRFArray = vField.split(".");
			vRFArrayLength = vRFArray.length;
			if(vRFArrayLength==1){
				
			}else if(vRFArrayLength==2){
				
			}else if(vRFArrayLength==3){
				if(vRFArray[0]=="decode"&&vRFArray[1]=="html"){
					fpCode = fpCode.replace(new RegExp(vRenderField,"g"),fly.dataDecode(fpData[vRFArray[2]]));
				}
			}
		}
	}
	return fpCode;
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

//判断选择器开头，并返回选择器前缀
function fSelectorJudge(fpSelector){
	if(fly.isNull(fpSelector)){return "";}
	let vFirst = fpSelector.substr(0,1);
	if(vFirst == "c"){
		return "." + fpSelector;
	}else if(vFirst == "i"){
		return "#" + fpSelector;
	}	
	return fpSelector;
}

//渲染操作
function fRenderEvent(fpCode){
	let vRenderEvent = fly.getStringCenter(fpCode,"data-event=\"",'"');
	if(fly.isNull(vRenderEvent)){return;}
	//获取事件相关变量
	let vRenderEventArray = vRenderEvent.split(":");
	let vEvent = vRenderEventArray[0];					//事件
	let vEventSelector = vRenderEventArray[1];			//选择器
	let vEventValue = vRenderEventArray[2];				//事件值
	//操作执行
	vEventSelector = fSelectorJudge(vEventSelector);
	if(vEvent=="addClass"){								//添加类
		$(vEventSelector).addClass(vEventValue);	
		$(vEventSelector).removeClass("loading-hide");	//移除隐藏
	}
}

//处理数据：页面参数代码渲染
function fRenderPageParameter(){
	var vCode,vRenderField,vField,vFieldArray;
	//预渲染：URL参数替换
	var vReg = /{{url.[\.a-zA-Z0-9_-]+}}/ig;
	vCode = $("body").prop("outerHTML");
	vFieldArray = vCode.match(vReg);
	for(let i=0;i<vFieldArray.length;i++){
		vRenderField = vFieldArray[i];
		vField = vRenderField.replace(/[{}]/ig,"");
		vFieldStringArray = vField.split(".");
		//URL参数值替换，替换参数配置
		vParameterValue = fly.getParameter(vFieldStringArray[1]);
		vCode = vCode.replace(new RegExp(vRenderField,"g"),vParameterValue);
	}
	//代码替换
	$("body").html(vCode);
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




//----- 页面加载完毕执行 -----

$(function(){
	
	//步骤0、渲染变量
	//1、请求路径
	var vGCAjaxUrl = "";		//获取请求路径（当渲染块配置了请求路径是优先使用请求块配置）
	
	//步骤1、配置获取
	//1、获取全局配置
	var vGlobalConfig = $("[data-config-type=global_config]");	//全局页面配置
	if(vGlobalConfig.size()==0){
		layui.use('layer', function(){
			var layer = layui.layer;
			layer.msg("页面全局配置未配置");
		}); 		 
	}else if(vGlobalConfig.size()==1){
		vGCAjaxUrl = vGlobalConfig.attr("data-ajax");
	}
	
	//步骤2、预渲染
	//1、URL参数预渲染
	fRenderPageParameter();
	
	//步骤3、数据渲染
	var vObjArray = {};
	$("[data-line]").each(function(){
		
		//--- 渲染配置 ---
		//请求配置
		let cAjax = $(this).attr("data-ajax");						//请求路径
		let cLine = $(this).attr("data-line");						//请求业务线
		let cMehtod = $(this).attr("data-method");					//请求业务线方法
		let cParameter = $(this).attr("data-parameter");			//请求参数
		//渲染方式
		let cRenderType = $(this).attr("data-render");				//渲染类型
		let cDataRenderKey = $(this).attr("data-render-key");		//数据组名称
		let cRenderTrigger= $(this).attr("data-trigger");			//渲染完成后触发事件
		//数据名称
		let cRecodeName = $(this).attr("data-name");				//数据名称
		let cRecodeGroup = $(this).attr("data-group");				//数据组名称
		
		//--- 配置值判断 ---
		if(fly.isNull(cAjax)){cAjax = vGCAjaxUrl; }					//判断：请求路径
		cAjax = eval(cAjax);										//处理：将变量名转为变量值
		if(fly.isNull(cRecodeName)){cRecodeName = "";}				//判断：记录名
		if(fly.isNull(cRecodeGroup)){cRecodeGroup = "";}			//判断：记录组名
		if(!fly.isNull(cParameter)){								//判断：请求参数	
			cParameter = "&"+cParameter;	
		}	
		
		//情况较少，不需要判断
		//相同数据只请求一次，对相同数据（配置名、line、method）进行对比后渲染
			
		//数据渲染
		layui.use('layer', function(){
			//Layer组件
			var layer = layui.layer;
			//加载动画
			var vLoadIndex = layui.layer.load(2, {time: 10 * 1000});
			//请求数据
			$.get(cAjax+"line="+cLine+"&method="+cMehtod+"&render_type="+cRenderType+"&render_key="+cDataRenderKey+"&recode_name="+cRecodeName+"&recode_group="+cRecodeGroup+cParameter,function(data){
				//转为Json
				var json = eval('('+data+')');
				//正确的Json执行结果
				if(json.result=="true"){
					var vTips = json.tips;								//Json:tips				
					var vTipsArray = vTips.split("-");					//tips数组
					var vLine = vTipsArray[0];							//请求业务线
					var vLineMethod = vTipsArray[1];					//请求业务线方法
					var vRenderType = vTipsArray[2];					//渲染类型
					var vRecodeGroup = vTipsArray[3];					//数据数组
					var vRecodeName = vTipsArray[4];					//数据名称
					var vRenderKey = vTipsArray[5];						//渲染Key
					if(vRenderType=="a"){
						let vJsonData = json.data[0];					//记录
						let vObj = $("[data-line='"+vLine+"'][data-method='"+vLineMethod+"'][data-name='"+vRecodeName+"']");	//定位数据
						vObj.removeClass("loading-hide");				//移除隐藏
						let vObjHtml = vObj.prop("outerHTML");			//获取当前元素的HTML
							vObjHtml = fCodeField(vObjHtml,vJsonData);	//替换字段值
							vObjHtml = fRenderParameter(vObjHtml);		//替换值中的参数值
						vObj.replaceWith(vObjHtml);						//替换对象代码
						fRenderEvent(vObjHtml);							//渲染事件
					}else if(vRenderType=="group"){
						let vJsonData = json.data;						//记录
						let vListObj = $("[data-line='"+vLine+"'][data-method='"+vLineMethod+"'][data-group='"+vRecodeGroup+"']");
						vListObj.removeClass("loading-hide");			//移除隐藏
						let vObjHtml = vListObj.prop("outerHTML");		//获取当前元素的HTML
						let vRenderTrigger = fCodeAttrGet(vObjHtml,"data-trigger");		//渲染完成：触发事件
						let vCode = "";									//列表代码
						let vDataCode = "";								//记录代码
						for(let i=0;i<vJsonData.length;i++){			//渲染数据
							//添加dataName属性，由于列表请求数据只有组名，而没有记录名，将请求到的数据记录名写入标签属性中
							vDataCode = fCodeAttrAdd(vObjHtml,vListObj[0].tagName,"data-name",vJsonData[i]["recodeName"]); 
							vDataCode = fCodeField(vDataCode,vJsonData[i]);				//渲染字段值
							vCode += vDataCode;							//组合列表代码
						}
						vCode = fRenderParameter(vCode);
						vListObj.replaceWith(vCode);
						//渲染
						if(vRenderTrigger=="banner"){
							fRenderBanner();	
						}
					}else if(vRenderType=="list"){
						let vJsonData = json.data;	
						let vListObj = $("[data-line='"+vLine+"'][data-method='"+vLineMethod+"'][data-render-key='"+vRenderKey+"']");
							vListObj.removeClass("loading-hide");			//移除隐藏
						let vObjHtml = vListObj.html();
						let vCode = "";									//列表代码
						let vDataCode = vObjHtml;						//记录代码
						for(let i=0;i<vJsonData.length;i++){			//渲染数据
							vCode += fCodeField(vDataCode,vJsonData[i]);				//渲染字段值
						}
						vCode = fRenderParameter(vCode);
						vListObj.html(vCode);
					}
				}
				layer.close(vLoadIndex);
			})
		}); 			
	})


})



//----------- 自定义渲染 ----------
//渲染页面主题颜色
//var pBrandId = fly.getParameter("brand_id");
//var vParameter = {"line":"visitor","method":"brandconfigvalue","brand_id":pBrandId,"recode_name":"PageColor"};
//$.post(urlAjaxBrand,vParameter,function(data){
//	var json = eval('('+data+')');
//	if(json.result=="true"){
//		var vJsonData = json.data[0];
//		var vConfigValue = vJsonData["configValue"];
//		$(".cThemeStyle").addClass(vConfigValue);
//	}				
//})