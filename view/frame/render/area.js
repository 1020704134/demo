//--- 省市区数据渲染 ---

//ID选择器
var vRAreaProvinceId = "";
var vRAreaCityId = "";
var vRAreaAreaId = "";

//点击数
var vProvinceClickNumber = 0;
var vCityClickNumber = 0;
var vAreaClickNumber = 0;

//改变数
var vProvinceChangeNumber = 0;
var vCityChangeNumber = 0;
var vAreaChangeNumber = 0;

//区域数据初始化
function fRenderAreaInit(fpProvinceId,fpCityId,fpAreaId,fpCityFunction,fpAreaFunction){
	//选择器处理
	if(!fpProvinceId.indexOf("#")>=0){vRAreaProvinceId = "#"+fpProvinceId;}else{vRAreaProvinceId = fpProvinceId;}
	if(!fpCityId.indexOf("#")>=0){vRAreaCityId = "#"+fpCityId;}else{vRAreaCityId = fpCityId;}
	if(!fpAreaId.indexOf("#")>=0){vRAreaAreaId = "#"+fpAreaId;}else{vRAreaAreaId = fpAreaId;}
	//省
	var optionCode = '<option placeholder="请选择" data-role="none" disabled selected hidden>请选择</option>';
	for(var dataKey in jsonCity){optionCode += "<option value=\"" + dataKey + "\">" + dataKey + "</option>";}
	$(vRAreaProvinceId).html(optionCode);
	//市
	//var optionCode = "";
	//var optionVal = $(vRAreaProvinceId).val();
	//var cityArray = jsonCity[optionVal].items; 
	//for(var dataKey in cityArray){optionCode += "<option value=\"" + dataKey + "\">" + dataKey + "</option>";	}
	//$(vRAreaCityId).html(optionCode);
	//区
	//var optionCode = "";
	//var optionVal = $(vRAreaCityId).val();
	//var areaArray = cityArray[optionVal].items; 
	//for(var dataKey in areaArray){optionCode += "<option value=\"" + dataKey + "\">" + dataKey + "</option>";	}
	//$(vRAreaAreaId).html(optionCode);
	
	//--- 事件监听 ---
	//省：省变化事件
	$(vRAreaProvinceId).live("change",function(){
		vProvinceChangeNumber += 1;
		//执行变化事件
		if(vProvinceChangeNumber%2==1){
			//市
			var optionCode = '<option placeholder="请选择" data-role="none" disabled selected hidden>请选择</option>';
			var optionVal = $(vRAreaProvinceId).val();
			var cityArray = jsonCity[optionVal].items; 
			for(var dataKey in cityArray){optionCode += "<option value=\"" + dataKey + "\">" + dataKey + "</option>";	}
			$(vRAreaCityId).html(optionCode);
			//区
			var optionCode = '<option placeholder="请选择" data-role="none" disabled selected hidden>请选择</option>';
			var optionVal = $(vRAreaCityId).val();
			var areaArray = cityArray[optionVal].items; 
			for(var dataKey in areaArray){optionCode += "<option value=\"" + dataKey + "\">" + dataKey + "</option>";	}
			$(vRAreaAreaId).html(optionCode);
			//城市变化事件
			fpCityFunction($(vRAreaProvinceId).val(),$(vRAreaCityId).val());
		}
	})
	//市：市变化事件
	$(vRAreaCityId).live("change",function(){
		vCityChangeNumber += 1;
		if(vCityChangeNumber%2==1){
			//区
			var optionVal = $(vRAreaProvinceId).val();
			var cityArray = jsonCity[optionVal].items;
			var optionVal = $(vRAreaCityId).val();
			var areaArray = cityArray[optionVal].items; 
			var optionCode = '<option placeholder="请选择" data-role="none" disabled selected hidden>请选择</option>';
			for(var dataKey in areaArray){optionCode += "<option value=\"" + dataKey + "\">" + dataKey + "</option>";	}
			$(vRAreaAreaId).html(optionCode);
			fpAreaFunction($(vRAreaProvinceId).val(),$(vRAreaCityId).val(),$(vRAreaAreaId).val());	//触发市改变事件
		}
	})
	//区数据变化
	$(vRAreaAreaId).live("change",function(){
		vAreaChangeNumber += 1;
		if(vAreaChangeNumber%2==1){
			fpAreaFunction($(fpProvinceId).val(),$(fpCityId).val(),$(fpAreaId).val());	//触发区改变事件
		}
	})

}


