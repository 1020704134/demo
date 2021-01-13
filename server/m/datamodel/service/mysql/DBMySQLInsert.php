<?php

class DBMySQLInsert{
    
    
    /**
     * 函数:Insert子函数
     * 说明:根据传入的数字返回一个由问号组成的Insert值字符串,适用于单条数据值字符串生成
     * 更新时间:October 13, 2018 15:52:00
     * */
    public static function InsertValueQuestion($num){
        $valueString = "";
        for($i=0;$i<num;$i++){
            $valueString .= "?,";
        }
        if(IsNull($valueString)){
            $valueString = substr($valueString, 0, -1);
        }
        return $valueString;
    }
    
    
    /**
     * 函数:添加记录
     * 函数:根据字段和值插入一条数据
     * 更新时间:October 13, 2018 16:37:00
     * @throws Exception
     * */
    public static function InsertRecord($tableName,$fieldArray,$valueArray){
        //拼接字段字符串
        $fieldStr = HandleArrayConnect($fieldArray,",");
        //拼接值字符串
        $valueNumberStr = self::InsertValueQuestion(sizeof($valueArray));
        //拼接SQL字符串
        $sqlStr = "INSERT IGNORE INTO ".$tableName."(".$fieldStr.") VALUES (".$valueNumberStr.");";
        //提交SQL语句
        return DBHelper::DataSubmit($sqlStr,$valueArray);
    }
    
    
 
    
}

