<?php

    /**
     * 判断是否是整数
     * 整理时间：2019-09-24 18:07:27
     * */
    function IsInt($fpString){
        return is_int($fpString);
    }
    
    /**
     * 判断是否是正整数
     * 整理时间：2019-09-24 18:07:27
     * */
    function IsIntPositive($fpString){
        return is_int($fpString)&&intval($fpString)>0;
    }
    
    /**
     * 判断是否是自然数
     * 整理时间：2019-09-28 19:05:52
     * */
    function IsIntNatural($fpString){
        return is_int($fpString)&&intval($fpString)>-1;
    }

    /**
     * 判断是否是数字
     * 整理时间：2019-09-24 18:07:27
     * */
    function IsNumber($fpString){
        return is_numeric($fpString);
    }
    
    /**
     * 判断是否是整数并大于制定数
     * 整理时间：2019-09-28 18:57:41
     * */
    function IsNumberGreater($fpString,$fpNumber){
        if(is_numeric($fpString)){
            return floatval($fpString)>$fpNumber;
        }
        return false;
    }