<?php 
    
	/** 获取参数*/
	function GetParameter($parameterName,$json=null){
	    
	    //--- 参数获取:Json ---
	    
	    //内部Json获取参数值,不进行urlencode编码
	    if(!IsNull($json)){
	        //原参数名称获取
	        $parameter = GetJsonValue($json, $parameterName);
	        //小写参数名获取
	        if(IsNull($parameter)){
	            $parameter = GetJsonValue($json, strtolower($parameterName));
	        }
	        if(!IsNull($parameter)){
	            return HandleStringParameter($parameter);
	        }
	    }
	    
	    //--- 参数获取:Get ---
	    
	    //参数名到小写
	    $parameterName = strtolower($parameterName);
	    
	    //Get方式请求,对参数拆分和Url编码
	    $parameter = @$_GET[$parameterName];
	    if(!IsNull($parameter)){
	        return HandleStringParameter($parameter);
	    }else{
	        $parameter = @$_GET[HandleStringReplace($parameterName, "_", "")];
	        if(!IsNull($parameter)){ 
	            return HandleStringParameter($parameter); 
	        }
	    }
	    
	    
	    //--- 参数获取:Post ---
	    
	    //Post方式请求:方式一:$_POST
	    $parameter = @$_POST[$parameterName];
	    if(!IsNull($parameter)){
	        return HandleStringParameter($parameter);
	    }else{
	        $parameter = @$_POST[HandleStringReplace($parameterName, "_", "")];
	        if(!IsNull($parameter)){
	            return HandleStringParameter($parameter);
	        }
	    }
	    
	    //Post方式请求:方式二:file_get_contents("php://input")
	    $request = file_get_contents("php://input");
	    $parameter = GetJsonValue($request, $parameterName);
	    if(!IsNull($parameter)){
	        return HandleStringParameter($parameter);
	    }else{
	        $parameter = GetJsonValue($request, HandleStringReplace($parameterName, "_", ""));
	        if(!IsNull($parameter)){
	            return HandleStringParameter($parameter);
	        }
	    }
	    
	    return $parameter;
	}
	

	
	/** 获取参数:从请求中进行获取*/
	function GetParameterRequest($parameterName,$fpLowerBo=false){
	    
	    //参数到小写
	    $parameterName = strtolower($parameterName);
	     
	    //Get方式请求
	    $parameter = @$_GET[$parameterName];
	    if(!IsNull($parameter)){
	        //参数值到小写
	        if($fpLowerBo){
	            $parameter = strtolower($parameter);
	        }
	        //对参数拆分和Url编码
	        return $parameter;
	    }
	     
	    //Post方式请求:方式一
	    $parameter = @$_POST[$parameterName];
	    if(!IsNull($parameter)){
	        //参数值到小写
	        if($fpLowerBo){
	            $parameter = strtolower($parameter);
	        }
	        //对参数拆分和Url编码
	        return $parameter;
	    }
	    
	    //Post方式请求:方式二
	    $request = file_get_contents("php://input");
	    $parameter = GetJsonValue($request, $parameterName);
	    if(!IsNull($parameter)){
	        //参数值到小写
	        if($fpLowerBo){
	            $parameter = strtolower($parameter);
	        }
	        //对参数拆分和Url编码
	        return $parameter;
	    }

	}
	
	
	/** 获取参数*/
	function GetParameterJson($parameterName,$fpJson){
        $parameter = GetJsonValue($fpJson, $parameterName);
        if(IsNull($parameter)){
            $parameter = GetJsonValue($fpJson, strtolower($parameterName));
        }
	    return $parameter;
	}
	
	
	/** 获取参数：不做任何编码处理*/
	function GetParameterNoCode($parameterName,$json=null){
	     
	    //--- 参数获取:Json ---
	     
	    //内部Json获取参数值,不进行urlencode编码
	    if(!IsNull($json)){
	        //原参数名称获取
	        $parameter = GetJsonValue($json, $parameterName);
	        //小写参数名获取
	        if(IsNull($parameter)){
	            $parameter = GetJsonValue($json, strtolower($parameterName));
	        }
	        if(!IsNull($parameter)){
	            return $parameter;
	        }
	    }
	     
	    //--- 参数获取:Get ---
	     
	    //参数名到小写
	    $parameterName = strtolower($parameterName);
	     
	    //Get方式请求,对参数拆分和Url编码
	    $parameter = @$_GET[$parameterName];
	    if(!IsNull($parameter)){
	        return $parameter;
	    }
	     
	     
	    //--- 参数获取:Post ---
	     
	    //Post方式请求:方式一:$_POST
	    $parameter = @$_POST[$parameterName];
	    if(!IsNull($parameter)){
	        return $parameter;
	    }
	     
	    //Post方式请求:方式二:file_get_contents("php://input")
	    $request = file_get_contents("php://input");
	    $parameter = GetJsonValue($request, $parameterName);
	    if(!IsNull($parameter)){
	        return $parameter;
	    }
	     
	    return $parameter;
	}
	
	
	//--- 获取特定参数并设置Json对象 2.0函数 ---
	
	/**
	 * 获取Table
	 * 正确时设置Json对象值，错误时将Json对象值设置为空
	 * 2020-03-07 21:08:19
	 * */
	function GetParameterTable(){
	    $vTable = GetParameter("table");
        if(!JudgeRegularTable($vTable)){ $vTable = ""; }
	    return JsonObj(JsonKeyValue("table_name", $vTable));
	}
	
	/**
	 * 获取Table及记录ID
	 * 正确时设置Json对象值，错误时将Json对象值设置为空
	 * 2020-03-07 21:08:19
	 * */
	function GetParameterTableId(){
	    $vTable = GetParameter("table");
	    $vId = GetParameter("id");
	    if(!JudgeRegularTable($vTable)){ $vTable = ""; }
	    if(!JudgeRegularId($vId)){ $vId = ""; }
	    return JsonObj(JsonKeyValue("table_name", $vTable).",".JsonKeyValue("where_field", "id").",".JsonKeyValue("where_value", $vId).",".JsonKeyValue("id", $vId));
	}
	
	/**
	 * 获取Table、Id、field、value
	 * 正确时设置Json对象值，错误时将Json对象值设置为空
	 * 2020-03-07 21:08:19
	 * */
	function GetParameterTableIdValue(){
	    $vTable = GetParameter("table");
	    $vId = GetParameter("id");
	    $vField = GetParameter("field");
	    $vValue = GetParameter("value");
	    if(!JudgeRegularTable($vTable)){ $vTable = ""; }
	    if(!JudgeRegularId($vId)){ $vId = ""; }
	    if(!JudgeRegularField($vField)){ $vField = ""; }
	    if(!JudgeRegularFont($vValue)){ $vValue = ""; }
	    return JsonObj(JsonKeyValue("table_name", $vTable).",".JsonKeyValue("where_field", "id").",".JsonKeyValue("where_value", $vId).",".JsonKeyValue("update_field", $vField).",".JsonKeyValue("update_value", $vValue).",".JsonKeyValue("id", $vId));
	}
	
	/**
	 * 获取分页所需字段
	 * 正确时设置Json对象值，错误时将Json对象值设置为空
	 * 2020-03-08 11:57:47
	 * */
	function GetParameterTablePaging(){
	    //参数获取
	    $vTable = GetParameter("table");
	    $vId = GetParameter("id");
	    $vWhereField = GetParameter("wherefield");
	    $vWhereValue = GetParameter("wherevalue");
	    $vLikeField = GetParameter("likefield");
	    $vLikeKey = GetParameter("likekey");
        //正则校验
	    if(!JudgeRegularTable($vTable)){ $vTable = ""; }
	    if(!IsNull($vTable)){if(!DBMySQLJudge::JudgeTable($vTable)){ $vTable = ""; }}
	    if(!JudgeRegularId($vId)){ $vId = ""; }
	    if(!JudgeRegularField($vWhereField)){ $vWhereField = ""; }
	    if(!IsNull($vTable)&&!IsNull($vWhereField)){if(!DBMySQLJudge::JudgeTableField($vTable, $vWhereField)){ $vWhereField = ""; }}
	    if(!JudgeRegularFont($vWhereValue)){ $vWhereValue = ""; }
	    if(!JudgeRegularField($vLikeField)){ $vLikeField = ""; }
	    if(!IsNull($vTable)&&!IsNull($vLikeField)){if(!DBMySQLJudge::JudgeTableField($vTable, $vLikeField)){ $vLikeField = ""; }}
	    if(!JudgeRegularFont($vLikeKey)){ $vLikeKey = ""; }
	    //组合Json字符串
	    $vJsonArray = [
	        JsonKeyValue("table_name", $vTable),
	        JsonKeyValue("where_field", $vWhereField),
	        JsonKeyValue("where_value", $vWhereValue),
	        JsonKeyValue("like_field", $vLikeField),
	        JsonKeyValue("like_key", $vLikeKey),
	        JsonKeyValue("id", $vId),
	    ];
	    return JsonHandleArrayString($vJsonArray);
	}	
	
	
	/**
	 * 获取渲染相关数据
	 * 辅助数据渲染是必要参数，避免因异步请求的而导致的变量缺失
	 * 2020-10-25 13:11:32
	 * */
	function GetParameterRenderTips(){
	    
	    //参数:渲染类型:render_type
	    $pRenderType = GetParameterNoCode("render_type");
	    if(!IsNull($pRenderType)&&!JudgeRegularLetterNumber($pRenderType)){ return JsonModelParameterException("render_type", $pRenderType, 36, "内部格式错误", __LINE__); }
	    if(IsNull($pRenderType)){$pRenderType = "a";}
	    
	    //参数:渲染Key:renderKey
	    $pRenderKey = GetParameterNoCode("render_key");
	    if(!IsNull($pRenderKey)&&!JudgeRegularState($pRenderKey)){return JsonModelParameterException("render_key", $pRenderKey, 36, "内部格式错误", __LINE__);}
	    
	    //参数:业务线:line
	    $pIndexLine = GetParameterNoCode("line");
	    if(!JudgeRegularLetterNumber($pIndexLine)){return JsonModelParameterException("line", $pIndexLine, 36, "内部格式错误", __LINE__);}
	    
	    //参数:业务线方法:method
	    $pIndexLineMethod = GetParameterNoCode("method");
	    if(!JudgeRegularLetterNumber($pIndexLineMethod)){return JsonModelParameterException("method", $pIndexLineMethod, 36, "内部格式错误", __LINE__);}
	    
	    //参数:记录名:recodeName
	    $pRecodeName = GetParameterNoCode("recode_name");
	    if(!IsNull($pRecodeName)&&!JudgeRegularLetterNumber($pRecodeName)){return JsonModelParameterException("recode_name", $pRecodeName, 36, "内部格式错误", __LINE__);}
	    
	    //参数:记录组:recodeGroup
	    $pRecodeGroup = GetParameterNoCode("recode_group");
	    if(!IsNull($pRecodeGroup)&&!JudgeRegularLetterNumber($pRecodeGroup)){return JsonModelParameterException("recode_group", $pRecodeGroup, 36, "内部格式错误", __LINE__);}
	    	    
	    return "{$pIndexLine}-{$pIndexLineMethod}-{$pRenderType}-{$pRecodeGroup}-{$pRecodeName}-{$pRenderKey}";
	}
	
	