//--- 监听：区域选择 ---
//区域选择渲染调用函数
function fAreaSelectRender(){
	//省
	var optionCode = "";
	for(var dataKey in jsonCity){optionCode += "<option value=\"" + dataKey + "\">" + dataKey + "</option>";}
	$("#iAreaProvince").html(optionCode);
	//市
	var optionCode = "";
	var optionVal = $("#iAreaProvince").val();
	var cityArray = jsonCity[optionVal].items; 
	for(var dataKey in cityArray){optionCode += "<option value=\"" + dataKey + "\">" + dataKey + "</option>";	}
	$("#iAreaCity").html(optionCode);
	//区
	var optionCode = "";
	var optionVal = $("#iAreaCity").val();
	var areaArray = cityArray[optionVal].items; 
	for(var dataKey in areaArray){optionCode += "<option value=\"" + dataKey + "\">" + dataKey + "</option>";	}
	$("#iAreaArea").html(optionCode);
}

//监听:选择框:省
$("#iAreaProvince").live("change",function(){
	//市
	var optionCode = "";
	var optionVal = $("#iAreaProvince").val();
	var cityArray = jsonCity[optionVal].items; 
	for(var dataKey in cityArray){optionCode += "<option value=\"" + dataKey + "\">" + dataKey + "</option>";	}
	$("#iAreaCity").html(optionCode);
	//区
	var optionCode = "";
	var optionVal = $("#iAreaCity").val();
	var areaArray = cityArray[optionVal].items; 
	for(var dataKey in areaArray){optionCode += "<option value=\"" + dataKey + "\">" + dataKey + "</option>";	}
	$("#iAreaArea").html(optionCode);
	layui.use(['form'], function(){
		layui.form.render('select');
	})
})

//监听:选择框:市
$("#iAreaCity").live("change",function(){
	//区
	var optionVal = $("#iAreaProvince").val();
	var cityArray = jsonCity[optionVal].items;
	var optionVal = $("#iAreaCity").val();
	var areaArray = cityArray[optionVal].items; 
	var optionCode = "";
	for(var dataKey in areaArray){optionCode += "<option value=\"" + dataKey + "\">" + dataKey + "</option>";	}
	$("#iAreaArea").html(optionCode);
	layui.use(['form'], function(){
		layui.form.render('select');
	})
})


//省市区数据渲染
//vDataSetSelectProvince,vDataSetSelectCity,vDataSetSelectArea
function fAreaDataRender(){
	$("#iAreaProvince option").each(function(){
		if(fly.isNull(vDataSetSelectProvince)){return true;}
		if($(this).text()==vDataSetSelectProvince){
			$(this).attr("selected", true);
			//市
			var optionCode = "";
			var cityArray = jsonCity[vDataSetSelectProvince].items; 
			for(var dataKey in cityArray){optionCode += "<option value=\"" + dataKey + "\">" + dataKey + "</option>";	}
			$("#iAreaCity").html(optionCode);
			$("#iAreaCity option").each(function(){
				if($(this).text()==vDataSetSelectCity){
					$(this).attr("selected", true);
					//区
					var areaArray = cityArray[vDataSetSelectCity].items; 
					for(var dataKey in areaArray){optionCode += "<option value=\"" + dataKey + "\">" + dataKey + "</option>";	}
					$("#iAreaArea").html(optionCode);
					$("#iAreaArea option").each(function(){
						if($(this).text()==vDataSetSelectArea){
							$(this).attr("selected", true);
						}
					})
				}
			})
		}
	})
}
