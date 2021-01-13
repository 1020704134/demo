<?php

    /**获取数组数量*/
    function GetArraySize($arr){
        return sizeof($arr);
    }

    /**分割字符串成数组*/
    function GetArray($string,$split){
        return explode($split,$string);
    }
    
    /**获取字符串数组成员*/
    function GetArrayMember($string,$split,$fpSub){
        if(IsNull($string)){ return ""; }
        $vArray = explode($split,$string);
        $fpSub = intval($fpSub);
        if(IsNull($fpSub)&&$fpSub<0){
            return "";
        }
        if((sizeof($vArray)-1)>=$fpSub){
            return $vArray[$fpSub];
        }
        return "";
    }
        
    /**分割字符串成数组*/
    function GetJsonArray($json,$sub){
        $resultArray = array();
        $string = "";
        $jsonArray = json_decode($json);
        foreach($jsonArray as $member){
            array_push($resultArray,$member -> $sub);
        }
        return $resultArray;
    }

    /**
     * 获取数组值（带默认值）
     * @param $array
     * @param $key
     * @param null $default 默认值
     * @return mixed|null
     * @date 19/12/25
     */
    function GetArrayValue($array, $key, $default = null){
        return isset($array[$key]) ? $array[$key] : $default;
    }



