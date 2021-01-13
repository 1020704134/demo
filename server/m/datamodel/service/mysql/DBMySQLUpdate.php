<?php

class DBMySQLUpdate{
    
    //-----Update·子函数-----
    
    /**
     * 函数:修改字符串子函数
     * 说明:根据传入的字段组成 field=?,field=? 的字符串
     * 更新时间:October 13, 2018 16:58:00
     * @throws SQLException
     * */
    public static function UpdateValue($fieldArray){
        $fieldNum = sizeof($fieldArray);
        if($fieldNum<1){
            return null;
        }
        $setString = "";
        for($i=0;$i<$fieldNum;$i++){
            $setString .= $i==$fieldNum-1?$fieldArray[$i]."=?" : $fieldArray[$i]."=?,";
        }
        return $setString;
    }
    
    //-----Update·修改多个字段-----
    
    /**
     * 函数:修改记录
     * 说明:没有Where条件修改记录的多个字段
     * 更新时间:October 13, 2018 16:59:00
     * @throws SQLException
     * */
    public static function UpdateRecord($tableName,$setArray,$valueArray){
        $sqlStr = "UPDATE ".$tableName." SET ".self::UpdateValue($setArray).";";
        return DBHelper::DataSubmit($sqlStr,$valueArray);
    }
    
    /**
     * 函数:修改记录有Where条件
     * 说明:传入表名称，设置字段数组，条件字符串，来修改记录
     * 更新时间:October 13, 2018 16:59:00
     * @throws SQLException
     * */
    public static function UpdateRecordWhere($tableName,$setArray,$whereStr,$stmtArray){
        //拼接Set字段和Value值字符串
        $setStr = self::UpdateValue(setArray);
        //拼接SQL字符串
        $sqlStr = "UPDATE ".$tableName." SET ".$setStr." WHERE ".$whereStr.";";
        //提交SQL语句
        return DBHelper::DataSubmit($sqlStr,$stmtArray);
    }
    
       
    /**
     * 函数:修改一条记录有Where条件
     * 说明:传入表名称，设置字段字符串，条件字符串，条件值，来修改一条记录
     * 更新时间:October 13, 2018 16:59:00
     * @throws SQLException
     * */
    public static function UpdateRecordOnes($tableName,$fieldName,$fieldValue,$whereField,$whereValue){
        //拼接SQL字符串
        $sqlStr = "UPDATE ".$tableName." SET ".$fieldName."=? WHERE ".$whereField."=?;";
        //提交SQL语句
        return DBHelper::DataSubmit($sqlStr,array($fieldValue,$whereValue));
    }
    
    /**
     * 函数:修改一条记录有Where条件
     * 说明:传入表名称，设置字段字符串，条件字符串，来修改一条记录
     * 更新时间:October 13, 2018 16:59:00
     * @throws SQLException
     * */
    public static function UpdateRecordOnesWhere($tableName,$fieldName,$fieldValue,$whereString){
        //拼接SQL字符串
        $sqlStr = "UPDATE ".$tableName." SET ".$fieldName."=? WHERE " . $whereString . ";";
        //提交SQL语句
        return DBHelper::DataSubmit($sqlStr,array(fieldValue));
    }
    
    
    
    /**
     * 修改表自动增长字段的起始数
     * 更新时间:October 13, 2018 13:17:00
     * @throws SQLException
     * */
    public static function TableStartAutoIdSet($tableName,$number){
        $sqlStr = "ALTER TABLE ".$tableName." AUTO_INCREMENT = ".$number.";";
        return DBHelper::DataSubmit($sqlStr,null);
    }
    

}

