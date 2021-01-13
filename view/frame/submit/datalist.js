//该JS以来与 JQuery和Layui，使用前需先引入以上两个文件
//主要用于对数据列表的相关操作

//--- 加载完毕执行 ---
$(function(){
	
	//网页名称
	let vHrefArray = window.location.pathname.split('/');
	$("#iPageName").html("[ "+vHrefArray[vHrefArray.length - 1]+" ]");	
	
	//--- 显示 ---
	//移动端显示设置
	if(vBrowserViewType=="WAP"){
		$("#iFieldSelect").hide();
		$("#iSelectOption").hide();
		$("#iSearchTitle").hide();
	}
	
	
	//--- 页面按钮 ---
	//完全模式
	$("#iModeComplete").click(function(){
		$("td a").removeClass("layui-hide"); 
	})
	
	//全部用户
	$("#iAccessPagingAll").live("click",function(){
		$("#iSearchValue").val("");
		fDataPaging();
	})
			  
	//搜索用户
	$("#iAccessUserSearch").live("click",function(){
		var vSearchField = $("#iSearchField").val();
		var vSearchValue = $("#iSearchValue").val();
		if(fly.isNull(vSearchField)){return layer.msg("搜索字段不得为空");}
		if(fly.isNull(vSearchValue)){return layer.msg("搜索内容不得为空");}
		var vThisUrl = vConfigPagingUrl+"&likefield="+vSearchField+"&like="+vSearchValue;
		fDataPaging(vThisUrl,vSearchField,vSearchValue);
	})
	
});


//--- 数据渲染 ---
//区域数据：省、市、区
var vDataSetSelectProvince,vDataSetSelectCity,vDataSetSelectArea;
//修改渲染
function fRecodeDataRender(fpRecodeObjData){
	let vValueField,vFieldLower,vFieldId,vTagName,vJsonValue;
	$("[data-field]").each(function(){
		vValueField = $(this).attr("data-field");
		vFieldLower = vValueField.toLowerCase();
		vFieldId = "i"+vValueField.slice(0, 1).toUpperCase() + vValueField.slice(1);
		vTagName = $(this)[0].tagName;
		//字段名对比
		for(var key in fpRecodeObjData){
			vJsonValue = fpRecodeObjData[key];
			if(key.toLowerCase()==vValueField&&!fly.isNull(vJsonValue)){
				//标签取值判断
				if($(this)[0].tagName=="IMG"){
					$(this).attr("src",vJsonValue);
				}else if($(this)[0].tagName=="SELECT"){
					//SELECT选择:省、市、区
					if($(this).attr("id")=="iAreaProvince"){
						vDataSetSelectProvince = vJsonValue;
					}else if($(this).attr("id")=="iAreaCity"){
						vDataSetSelectCity = vJsonValue;
					}else if($(this).attr("id")=="iAreaArea"){	
						vDataSetSelectArea = vJsonValue;
					}else{
						//其他SELECT
						$(this).html('<option value="'+vJsonValue+'">'+vJsonValue+'</option>');
					}
				}else{
					$(this).val(vJsonValue);	
				}
				break;
			}
		}
	})	
}

//数据表表分页渲染
function fDataPaging(fpUrl,fpLikeField,fpLikeKey,fpConfig){
	var vLoadIndex = layer.load(2, {time: 10 * 1000});
	if(fly.isNull(fpUrl)){
		fpUrl = vConfigPagingUrl;
	}
	if(!fly.isNull(fpLikeField)){
		fpUrl += "&likefield="+fpLikeField+"&likekey="+fpLikeKey;
	}
	var vConfigLimit = "";
	try{ vConfigLimit = fpConfig["limit"]; }catch(e){}
	if(fly.isNull(vConfigLimit)){ vConfigLimit = 50; }
	layui.use('table', function(){
  		layui.table.render({
	    	elem: '#iLayTable'
	    	,url: fpUrl
	    	//,cellMinWidth: 80
	    	,cols: fGetPagingField(fpLikeField,fpLikeKey)
		    ,page: true
		    ,limits:[10,20,30,50,100]
	    	,limit:vConfigLimit
		    ,done: function(res, curr, count){
		    	if(res.result=="false"){layer.msg(res.infor);}
				$("#iLoadImage").hide();
				layer.close(vLoadIndex);
		    }
	  	});
  	});
}


//--- 管理员添加数据 ---
$("#iAdminAddSubmit").live("click",function(){
	//加载和控制层
	if(submitIndex == 1){return;}
	submitIndex = 1;
	var loadIndex = layer.load(2,{time:10*1000});
	
	//--- 配置层 ---
	var vPostParameter = vConfigAdd;
	
	//--- 固定流程 ---
	let vValueDefault,vValue,vValueField,vValueEncode;
	$(this).parents("div[data-submit='true']").find("[data-field]").each(function(){
		vValueDefault = $(this).attr("data-default");
		vValueField = $(this).attr("data-field");
		vValueEncode = $(this).attr("data-encode");
		//标签取值判断
		if($(this)[0].tagName=="IMG"){
			vValue = $(this).attr("src");	
		}else{
			vValue = $(this).val();	
		}
		//为空判断
		if(fly.isNull(vValue)){
			//默认值判断
			if(fly.isNull(vValueDefault)){
				vPostParameter[vValueField] = "";	
			}else{
				vPostParameter[vValueField] = vValueDefault;	
			}
		}else{
			//编码判断
			if(vValueEncode=="FLY_HTML_ENCODE"){
				vValue = fly.dataEncode(vValue);
			}
			vPostParameter[vValueField] = vValue;
		}
	})
	$.post(urlAjax,vPostParameter,function(data){
		layer.close(loadIndex);	//关闭加载层
		var json = eval('('+data+')');
		if(json.result=="true"){
			submitIndex = 0;
			layer.closeAll();
			fDataPaging();
			return layer.msg(json.infor,{icon:6,time:800});
		}else{
			submitIndex = 0;
			return layer.msg(json.infor,{icon:5,time:800});
		}
		submitIndex = 0;
	})
})

