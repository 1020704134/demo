<?php

class DBMySQLJudge{
    

    /**
     * 函数:判断数据库数据表是否存在
     * 说明:判断数据库中是否存在指定数据表
     * 更新时间:October 13, 2018 14:58:00
     * */
    public static function JudgeTable($tableName){
        $vDataBaseName = DBConfig::$dbName;
        $sql = "SELECT table_name FROM information_schema.TABLES WHERE TABLE_SCHEMA=? AND table_name=?;"; 
        $dbTableName = DBHelper::DataString($sql, [$vDataBaseName,$tableName]);
        return !IsNull($dbTableName);
    }
    
    /**
     * 函数:判断数据库数据表是否存在，不存在则进行创建
     * 说明:判断数据库中是否存在指定数据表
     * 更新时间:October 13, 2018 14:58:00
     * */
    public static function JudgeTableCreate($tableName,$sqlCreateStr){
        $sql = "SELECT table_name FROM information_schema.TABLES WHERE TABLE_SCHEMA='".DBConfig::$dbName."' AND table_name=?;";
        if(IsNull(DBHelper::DataString($sql,array($tableName)))){
            return DBHelper::DataSubmit($sqlCreateStr,null);
        }
        return true;
    }
    
    /**
     * 函数:判断数据表字段是否存在
     * 说明:判断数据库指定数据表的指定字段是否存在
     * 更新时间:October 13, 2018 14:59:00
     * */
    public static function JudgeTableField($tableName,$fieldName){
        $sql = "SELECT COLUMN_NAME field FROM INFORMATION_SCHEMA.COLUMNS WHERE table_name = ? AND COLUMN_NAME = ?;";
        return !IsNull(DBHelper::DataString($sql,[$tableName,$fieldName]));
    }
    
    

    
}

