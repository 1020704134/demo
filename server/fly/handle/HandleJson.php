<?php 

    //-------------------- Json 基本语法 --------------------
    
    /** Json对象*/
    function JsonObj($jsonString){return "{{$jsonString}}";}
    /** Json数组*/
    function JsonArray($jsonString){return "[{$jsonString}]";}
    /** Json双引号*/
    function JsonQuotes($jsonString){return "\"{$jsonString}\"";}
    /** Json逗号*/
    function JsonComma($jsonStringOne,$jsonStringTwo){return $jsonStringOne.",".$jsonStringTwo;}
    
    /** JsonString*/
    function JsonKeyValue($key,$value){return "\"{$key}\":\"{$value}\"";}
    /** JsonString*/
    function JsonKeyString($key,$string){return "\"{$key}\":".$string;}
    /** JsonString*/
    function JsonKeyArray($key,$value){return "\"{$key}\":[{$value}]";}
    
    /** Json:key:value*/
    function JsonKeyValueObj($key,$value){return "{\"{$key}\":\"{$value}\"}";} 
    /** Json:key:value,key:value*/
    function JsonKeyValueTwoObj($key,$value,$keyTwo,$valueTwo){return "{\"{$key}\":\"{$value}\",\"{$keyTwo}\":\"{$valueTwo}\"}";}
    /** Json:key:value,key:value,key:value*/
    function JsonKeyValueThreeObj($key,$value,$keyTwo,$valueTwo,$keyThree,$valueThree){return "{\"{$key}\":\"{$value}\",\"{$keyTwo}\":\"{$valueTwo}\",\"{$keyThree}\":\"{$valueThree}\"}";}
    
    
    //-------------------- Json 复杂组合 --------------------
    
    /**
     * 将 string 数组处理成 Json字符串
     * $fpJsonArrayString：字符串数组
     * 整理时间：2019-09-25 19:15:06
     * */
    function JsonHandleArrayString($fpJsonArrayString){
        if(!is_array($fpJsonArrayString)){return "";}
        if(IsNull($fpJsonArrayString)){return "";}
        $json = "";
        for($i=0;$i<sizeof($fpJsonArrayString);$i++){
            $json .= $fpJsonArrayString[$i] . ",";
        }
        return JsonObj(HandleStringDeleteLast($json));
    }    
    
    
    /**
     * 将 string 数组处理成 Json对象字符串
     * $fpJsonArrayString：Json字符串对象数组
     * 整理时间：2020-10-01 21:52:41
     * */
    function JsonHandleObjArrayString($fpJsonArrayString){
        if(!is_array($fpJsonArrayString)){return "";}
        if(IsNull($fpJsonArrayString)){return "";}
        $json = "";
        for($i=0;$i<sizeof($fpJsonArrayString);$i++){
            $json .= $fpJsonArrayString[$i] . ",";
        }
        return JsonArray(HandleStringDeleteLast($json));
    }    
    
    /** 
     * 将 key=>value 数组处理成 Json字符串
     * $fpJsonKeyValueArray：key=>value 数组
     * 整理时间：2019-09-25 19:15:06
     * */
    function JsonHandleArray($fpJsonKeyValueArray){
        if(!is_array($fpJsonKeyValueArray)){return $fpJsonKeyValueArray;}
        $json = "";
        foreach ($fpJsonKeyValueArray as $key => $value) {
            $json .= JsonQuotes($key).":".JsonQuotes($value).",";
        }
        if(IsNull($json)){
            return "";
        }
        return JsonObj(HandleStringDeleteLast($json));
    }
    
    /**
     * 将 key=>value 数组处理成 Json字符串
     * $fpJsonKeyValueArray：key=>value 数组
     * 整理时间：2019-09-25 19:15:06
     * */
    function JsonHandleArrays($fpJsonKeyValueArray){
        $vJsonString = "";
        foreach ($fpJsonKeyValueArray as $keyOne => $valueOne) {
            $json = "";
            foreach ($valueOne as $key => $value) {
                //判断是否是Array，是Array进行组合处理
                if(is_array($value)){
                    $value = FlyJson::JsonHandleValue($value);
                }
                //组合Json
                $json .= JsonQuotes($key).":".JsonQuotes($value).",";
            }
            $vJsonString .= JsonObj(HandleStringDeleteLast($json)) . ",";
        }
        //判断是否为空
        if(IsNull($vJsonString)){
            return "";
        }
        //处理结果
        return JsonArray(HandleStringDeleteLast($vJsonString));
    }
    
    /** 
     * 将 key=>value 数组处理成 key:?,value:? 字符串
     * $fpJsonKeyValueArray：key=>value 数组
     * 整理时间：2019-09-25 19:16:48
     * */
    function JsonHandleArrayKeyValue($fpJsonKeyValueArray){
        $json = "";
        foreach ($fpJsonKeyValueArray as $key => $value) {
            $json .= JsonObj('"key"'.':'.JsonQuotes($key).",".'"value"'.':'.JsonQuotes($value)).",";
        }
        if(IsNull($json)){
            return "";
        }
        return HandleStringDeleteLast($json);
    }
    
    
    /**
     * 记录详细信息
     * 说明:传入记录集合和字段集合组合记录的详细信息
     * 形式:	{"field":"name","descript":"描述","value":"测试"}
     * 时间:December 14, 2018 17:30:00
     * @throws Exception
     * */
    function JsonHandleFieldListComment($fpFieldArray,$commentArray,$list){
        if(IsNull($list)||IsNull($fpFieldArray)){
            return "";
        }
        $list = json_decode($list);
        $stringArray = $list[0];
        $thisJson = "";
        for($i=0;$i<sizeof($fpFieldArray);$i++){
            $thisJson .= JsonObj(JsonKeyValue("field", $fpFieldArray[$i]).",".JsonKeyValue("comment", $commentArray[$i]).",".JsonKeyValue("value",GetJsonObjectValue($stringArray, $fpFieldArray[$i]))).",";
        }
        return substr($thisJson,0,-1);
    }
    
    /**
     * 函数:集合数据组合
     * 说明:传入关键字数组和集合中的每个字符串组合成为Json对象字符串
     * 形式:	{"id":"1","onlykey":"kki"}
     * 时间:October 2, 2018 14:21:00
     * @throws Exception
     * */
    function JsonHandleFieldList($fieldArray,$list){
        if(IsNull($list)||IsNull($fieldArray)){
            return "";
        }
        $listJson = GetJsonObject($list);
        $stringArray = $listJson[0];
        $thisJson = "";
        for($i=0;$i<sizeof($fieldArray);$i++){
            if(isset($fieldArray[$i])&&GetJsonObjectValue($stringArray, $fieldArray[$i])){
                $thisJson .= JsonKeyValue($fieldArray[$i],GetJsonObjectValue($stringArray, $fieldArray[$i])) .",";
            }
        }
        return JsonObj(substr($thisJson,0,-1));
    }
    
    /**
     * 判断两个Json并进行合并
     * 整理时间：2019-09-29 10:08:40
     *  */
    function JsonJudgeMerge($fpJsonOut,$fpJsonIn){
        if(IsNull($fpJsonOut)&&IsNull($fpJsonIn)){
            return "";
        }
        if(!IsNull($fpJsonOut)&&IsNull($fpJsonIn)){
            return $fpJsonOut;
        }
        if(IsNull($fpJsonOut)&&!IsNull($fpJsonIn)){
            return $fpJsonIn;
        }
        //合并Json
        $vJson = "";
        $vJsonOutArray = json_decode($fpJsonOut,true);
        $vJsonInArray = json_decode($fpJsonIn,true);
        foreach ($vJsonOutArray as $vOneKey => $vOneValue){
            //以外部传入Json数据为准，增加接口使用的灵活性
            $vJsonInArray[$vOneKey] = $vOneValue;
        }
        return JsonHandleArray($vJsonInArray);
    }
    
    
    /**
     * 将
     * 更新时间：2019-09-29 10:08:40
     *  */
    function JsonHandleArrayObj($fpKeyValueArray){
        $vJson = "";
        for($i=0;$i<sizeof($fpKeyValueArray);$i++){
            $vJson .= $fpKeyValueArray[$i] . ",";
        }
        if(!IsNull($vJson)){
            $vJson = JsonObj(HandleStringDeleteLast($vJson));
        }
        return $vJson;
    }
    
