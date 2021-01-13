<?php 


    /** 
     * 判断Json值
     * $fpJsonString：Json字符串
     * 整理时间：2019-09-24 18:48:32
     * */
    function JudgeJsonString($fpJsonString){
        $data = @json_decode($fpJsonString, false);
        return ($data && is_object($data)) || (is_array($data) && !empty($data));
    }
    
    /** 
     * 判断Json值
     * $fpJson：Json字符串
     * $fpJsonKey：要判断的JsonKey
     * $fpJudgeString：要比较的字符串
     * 调整时间：2019-09-24 18:50:11
     * */
	function JudgeJsonValue($fpJson,$fpJsonKey,$fpJudgeString){
	    if(isset(json_decode($fpJson)->$fpJsonKey)){
	        return json_decode($fpJson)->$fpJsonKey==$fpJudgeString; 
	    }
	    return false;
	}
	
	/** 
	 * 判断Json值
	 * $fpJsonObj：Json对象
	 * $fpJsonKey：要判断的JsonKey
     * $fpJudgeString：要比较的字符串
     * 调整时间：2019-09-24 18:52:12
	 * */
	function JudgeJsonObjValue($fpJsonObj,$fpJsonKey,$fpJudgeString){
	    return $fpJsonObj->$fpJsonKey==$fpJudgeString;
	}
	
	//-------------------- Json 中间语言 --------------------
	
	/**
	 * 判断Json系统码
	 * $fpJson：Json字符串
	 * $fpCode：code
	 * 整理时间：2019-11-23 18:20:11
	 * */
	function JudgeJsonCodeSystem($fpJson,$fpCode){
	    $vScode = GetJsonValue($fpJson, FlyJson::$kRSCode);
	    return $vScode == $fpCode;
	}
	
	
	/**
	 * 判断Json执行码
	 * $fpJson：Json字符串
	 * $fpCode：code
	 * 整理时间：2019-11-23 18:20:24
	 * */
	function JudgeJsonCodeExecute($fpJson,$fpCode){
	    $vScode = GetJsonValue($fpJson, FlyJson::$kRECode);
	    return $vScode == $fpCode;
	}
	