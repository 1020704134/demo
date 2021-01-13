<?php 
	

    /** 获取随机ID*/
    function GetId($key){
        $arr = array();
        while(count($arr)<10){
            $arr[] = rand(0,9);
            $arr = array_unique($arr);
        }
        //毫秒时间戳
        list($t1,$t2) = explode(' ', microtime());
        $vTimestampMilli = sprintf('%.0f',(floatval($t1)+floatval($t2))*1000);
        return $vTimestampMilli.$key.implode($arr);
    }
    
    /** 获取随机字母数字*/
    function GetIdNumber($len){
        $chars = "0123456789";
        $charsArray = str_split($chars);
        $charsLen = strlen($chars);
        $str = "";
        $vRand = "";
        for($i=0; $i<$len; $i++){
            $vRand = rand(0, $charsLen-1);
            $str .= $charsArray[$vRand];
        }
        return $str;
    }    

    /** 获取随机字母数字*/
    function GetIdLetterNumber($len){
        $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
        $charsArray = str_split($chars);
        $charsLen = strlen($chars);
        $str = "";
        $vRand = "";
        for($i=0; $i<$len; $i++){
            $vRand = rand(0, $charsLen-1);
            $str .= $charsArray[$vRand];
        }
        return $str;
    }
    
    
    

