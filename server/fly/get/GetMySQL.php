<?php 
	
    /**
     * 获取MySQL字段长度:ID字段
     * $fpValue：参数值
     * 整理时间：2019-09-26 17:45:24
     * */
    function GetMySQLFieldLengthBigint($fpValue){
        return mb_strlen($fpValue).":"."bigint(20)";
    }
    
    /**
     * 获取MySQL字段长度:Timestamp类型字段
     * $fpValue：参数值
     * 整理时间：2019-09-26 17:45:36
     * */
    function GetMySQLFieldLengthTimestamp($fpValue){
        return $fpValue.":"."yyyy-MM-dd HH:mm:ss";
    }
    
    /**
     * 获取MySQL字段长度:Varchar类型字段
     * $fpValue：参数值
     * 整理时间：2019-09-26 17:47:22
     * */
    function GetMySQLFieldLengthVarchar($fpValue,$fpLength){
        return $fpValue.":"."varchar({$fpLength})";
    }    
    
    /**
     * 获取MySQL字段长度:Int类型字段
     * $fpValue：参数值
     * 整理时间：2019-09-26 17:47:53
     * */
    function GetMySQLFieldLengthInt($fpValue){
        return $fpValue.":"."int(11)";
    }
    
    /**
     * 获取MySQL字段长度:Text类型字段
     * $fpValue：参数值
     * 整理时间：2019-09-26 17:47:53
     * */
    function GetMySQLFieldLengthText($fpValue){
        return $fpValue.":"."text";
    }