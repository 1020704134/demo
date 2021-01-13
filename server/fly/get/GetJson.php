<?php 
	
    /** 获取Json对象*/
    function GetJsonObject($json){
        if(!is_null(@json_decode($json))){
            return @json_decode($json);
        }
        return "";
    }

    /** 获取Json的值*/
    function GetJsonValue($json,$jsonKey){
        if(!is_null(@json_decode($json))){
            $json = @json_decode($json);
        }
        if(isset($json->$jsonKey)){
            return $json->$jsonKey;
        }
        return "";
    }
    
    /** 获取Json对象的值*/
    function GetJsonObjectValue($json,$jsonKey){
        if(isset($json->$jsonKey)){
            return $json->$jsonKey;
        }
        return "";
    }    
    
    /** 获取Json字符串*/
    function GetJsonString($json){
        return json_encode($json,JSON_FORCE_OBJECT);
    }
    
    
    /** 获取Json对象*/
    function GetJsonMember($json,$fpSub=0){
        if(!is_null(@json_decode($json))){
            $vOvj = @json_decode($json);
            return $vOvj[$fpSub];
        }
        return "";
    }
