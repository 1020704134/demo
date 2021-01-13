//Fly-富文本-对象
var layeditIndex = "";		//富文本编辑框索引
var layedit;				//富文本编辑框对象

//Fly-富文本-公司介绍
richtext = {
	
	init:function(){
		layui.use('layedit', function(){
		  	layedit = layui.layedit;
		  	layedit.set({
			  uploadImage: {
			    url: urlAjax+'line=image&method=uploadlayui'
			  }
			});
		  	layeditIndex = layedit.build('iRichtext',{tool: ['strong', 'italic', 'underline','del', '|', 'face', 'left', 'center', 'right', 'link', 'unlink', 'image']}); //建立编辑器
		  	$("#iRichtextContentTitleDiv").removeClass("display-hide");
			$("#iRichtextContentHtmlDiv").removeClass("display-hide");
			$("#iRichtextSubmit").removeClass("display-hide");
		});
	  	
	}

}


//HTML模式
$("#iRichtextContentTextareaHtml").live("click",function(){
	$("#iRichtextContentHtmlDiv").addClass("display-hide");
	$("#iRichtextContentEditDiv").removeClass("display-hide");
	$("#iRichtextCodetext").val(layedit.getContent(layeditIndex));
})
	
//预览
$("#iRichtextContentTextareaEdit").live("click",function(){
	var iRichtextCodetext = $("#iRichtextCodetext").val();
	$("#iRichtextContentHtmlDiv iframe").contents().find("body").html(iRichtextCodetext);
	$("#iRichtextContentEditDiv").addClass("display-hide");
	$("#iRichtextContentHtmlDiv").removeClass("display-hide");
})
		
//当HTML代码发生变化时更新到预览
$("#iRichtextCodetext").live("change",function(){
	var iRichtextCodetext = $("#iRichtextCodetext").val();
	$("#iRichtextContentHtmlDiv iframe").contents().find("body").html(iRichtextCodetext);
})
		