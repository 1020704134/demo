<?php 


	/** 
	 * 判断参数请求准许
	 * $fpParameterArray：允许传入参数数组
	 * 整理时间：2019-09-24 18:15:43
	 * */
	function JudgeParameterPermit($fpParameterArray){
	    
	    //是否启用参数严格审核机制
	    if(!PROJECT_CONFIG_PARAMETER_CHECK){
	        return JsonInforTrue("参数准许审核通过", "判断准许传入参数", $fpParameterArray);
	    }
	    
	    //参数获取:GET
	    $requestGet = $_SERVER["QUERY_STRING"];
	    //参数获取:POST
	    $requestPost = file_get_contents("php://input");
	    
	    $requestParamter = "";
	    if(!IsNull($requestGet)){
	        $requestParamter = $requestGet;
	    }
	    if(!IsNull($requestPost)){
	        $requestParamter = $requestPost;
	    }
	    
	    if(IsNull($requestParamter)){
	        return JsonInforFalse("参数获取失败",  "GET/POST", FlyCode::$Code_Parameter_Null);
	    }
	    
	    //遍历参数
	    foreach (explode('&', $requestParamter) as $value) {
	        $judgeBo = false;
	        if(IsNull($value)){break;}
	        $parameter = explode('=', $value);
	        $parameter = $parameter[0];
	        for($i=0;$i<sizeof($fpParameterArray);$i++){
	           if(HandleStringToLowerCase($parameter)==HandleStringToLowerCase($fpParameterArray[$i])){
	               $judgeBo = true;
	               break;
	           } 
	        }
	        if(!$judgeBo){
	            return JsonInforFalse("多余参数不允许传入",  $parameter, FlyCode::$Code_Parameter_SURPLUS);
	        }
	    }
	    
	    return JsonInforTrue("参数准许审核通过", "GET/POST", $fpParameterArray);

	}
	
	
	/** 
	 * 判断参数请求限制
	 * $fpParameterArray：限制传入参数数组
	 * 整理时间：2019-09-24 18:16:21
	 * */
	function JudgeParameterLimit($fpParameterArray){
	     
	    //是否启用参数严格审核机制
	    if(!PROJECT_CONFIG_PARAMETER_CHECK){
	        return JsonInforTrue("参数限制审核通过", "判断限制传入参数", $fpParameterArray);
	    }
	     
	    //参数获取:GET
	    $requestGet = $_SERVER["QUERY_STRING"];
	    //参数获取:POST
	    $requestPost = file_get_contents("php://input");
	     
	    $requestParamter = "";
	    if(!IsNull($requestGet)){
	        $requestParamter = $requestGet;
	    }
	    if(!IsNull($requestPost)){
	        $requestParamter = $requestPost;
	    }
	     
	    if(IsNull($requestParamter)){
	        return JsonInforFalse("参数获取失败",  "GET/POST", FlyCode::$Code_Parameter_Null);
	    }
	     
	    //遍历参数
	    foreach (explode('&', $requestParamter) as $value) {
	        if(IsNull($value)){break;}
	        $parameter = explode('=', $value);
	        $parameter = $parameter[0];
	        for($i=0;$i<sizeof($fpParameterArray);$i++){
	            if(strtolower($parameter)==strtolower($fpParameterArray[$i])){
	                return JsonInforFalse("指定参数不允许传入",  $parameter, FlyCode::$Code_Parameter_SURPLUS);
	            }
	        }
	    }
	     
	    return JsonInforTrue("参数限制审核通过", "GET/POST", $fpParameterArray);
	
	}

