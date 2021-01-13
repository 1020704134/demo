<?php

class DBMySQLDelete{

    
    /**清空表
     * @throws SQLException */
    public static function TruncateTable($tableName){
        $sqlStr = "TRUNCATE TABLE ".$tableName.";";
        DBHelper::DataSubmit($sqlStr,null);
        $count = DBHelper::DataString("SELECT count(TRUE) FROM ".$tableName,null);
        return $count==0?true:false;
    }
    

    /**
     * 函数:根据条件删除记录
     * 说明:根据条件删除记录，如果没有条件就清空数据表
     * 更新时间:October 13, 2018 15:45:00
     * @throws SQLException
     * */
    public static function DeleteRecord($tableName,$whereStr,$stmtArray){
        $sqlStr = "DELETE FROM ".$tableName." WHERE ".$whereStr.";";
        return DBHelper::DataSubmit($sqlStr,$stmtArray);
    }
    
    
    /**
     * 函数:删除数据表
     * 说明:如果数据表存在则删除数据表
     * 更新时间:October 13, 2018 15:49:00
     * @throws SQLException
     * */
    public static function DropTable($tableName){
        $sqlStr = "DROP TABLE IF EXISTS ".$tableName.";";
        return DBHelper::DataSubmit($sqlStr,null);
    }
    
}

