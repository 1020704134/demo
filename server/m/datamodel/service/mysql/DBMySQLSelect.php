<?php

class DBMySQLSelect{
    
    //---- 数据库查询 ----
    
    
    /**
     * 函数:判断数据库是否存在
     * 说明:判断MySQL中是否存在指定数据库
     * 更新时间:October 13, 2018 14:56:00
     * */
    public static function SelectDBNames(){
        $sql = "SELECT information_schema.SCHEMATA.SCHEMA_NAME FROM information_schema.SCHEMATA;";
        return DBHelper::DataList($sql,null,array("SCHEMA_NAME"));
    }
    
    /**获取MYSQL数据库版本
     * @throws Exception */
    public static function SelectDBVersion(){
        $sql = "SELECT VERSION() a;";
        $version = DBHelper::DataString(sql,null,"a");
        if(IsNull($version)){
            return "版本获取失败";
        }
        return $version;
    }
    
    /**
     * 函数:MySQL-数据库名称
     * 说明:获取当前连接的数据库名称
     * 创建时间:June 14, 2017 20:20:00
     * 更新时间:October 13, 2018 11:06:00
     * @throws SQLException
     * */
    public static function SelectDBName(){
        $sql = "SELECT DATABASE() dbname;";
        return DBHelper::DataString($sql, null, "dbname");
    }
    
    /**
     * 函数:MySQL-获取数据库全部数据表名称
     * 说明:查询当前连接的数据库中的所有表名称
     * 创建时间:2018-05-03 15:06
     * 更新时间:October 13, 2018 14:08:00
     * */
    public static function DBTablesName(){
        $dbName = self::SelectDBName();
        $sql = "SELECT table_name tableName FROM information_schema.TABLES WHERE table_schema = \"".$dbName."\";";
        return DBHelper::DataList($sql, null, array("tableName"));
    }
    
    
    /**
     * 函数:获取数据表字段
     * 说明:传入数据库名称和表名称获取表字段信息，返回字段集合
     * 更新时间:October 13, 2018 14:19:00
     * @throws Exception
     * */
    public static function SelectFieldLength($tableName){
        //获取表所有字段和长度
        $sql = "SHOW FULL COLUMNS FROM ".$tableName.";";
        $fieldsArray = array("Field","Type");
        return DBHelper::DataList($sql,null,$fieldsArray);
    }
    
    
    /**
     * 函数:获取数据表字段备注
     * 说明:传入数据库名称和表名称及字段名获取表字段备注
     * 更新时间:December 20, 2018 20:41:00
     * */
    public static function TableFieldDescriptText($tableName,$fieldName){
        $sql = "SHOW FULL COLUMNS FROM ".$tableName.";";
        $fieldsArray = array("Field","Comment");
        $data = DBHelper::DataList($sql,null,$fieldsArray);
        $dataJson = json_decode($data);
        foreach($dataJson as $json){
            if(strtolower($json->Field)==strtolower($fieldName)){
                return $json->Comment;
            }
        }
        return "";
    }

    
    //---- 数据查询 ----
    
    /**
     * 函数:获取数据表字段
     * 说明:传入数据库名称和表名称获取表字段信息，返回字段集合
     * 更新时间:Nevember 12, 2018 16:23:00
     * */
    public final static function TableField($tableName){
        $sql = "SHOW FULL COLUMNS FROM ".$tableName.";";
        return DBHelper::DataList($sql,null,array("Field"));
    }
    
    /**
     * 函数:获取数据表字段
     * 说明:获取数据表全部字段
     * 更新时间:2020-02-12 22:37:56
     * */
    public final static function TableFieldAllString($tableName){
        $sql = "SHOW FULL COLUMNS FROM ".$tableName.";";
        $list = DBHelper::DataList($sql,null,array("Field"));
        if(IsNull($list)){ return ""; }
        //Json数组字符串转Json对象
        $jsonArrayObj = GetJsonObject($list);
        $tableField = "";
        for($i=0;$i<sizeof($jsonArrayObj);$i++){
            $thisField = $jsonArrayObj[$i] -> Field;
            $tableField .= $thisField . ",";
        }
        return HandleStringDeleteLast($tableField);
    }
    
    /**
     * 函数:获取数据表字段
     * 说明:传入数据库名称和表名称获取表字段信息，返回字段集合
     * 更新时间:Nevember 12, 2018 16:23:00
     * */
    public final static function TableFieldString($tableName,$limitFieldArray=""){
        $sql = "SHOW FULL COLUMNS FROM ".$tableName.";";
        $list = DBHelper::DataList($sql,null,array("Field"));
        //Json数组字符串转Json对象
        $jsonArrayObj = GetJsonObject($list);
        //判断数组为空
        if(IsNull($jsonArrayObj)){
            return "";
        }
        
        //组合字段
        $tableField = "";
        if(IsNull($limitFieldArray)){
            for($i=0;$i<sizeof($jsonArrayObj);$i++){
                $thisField = $jsonArrayObj[$i] -> Field;
                if(strtolower($thisField)!="password"){
                    $tableField .= $thisField . ",";
                }
            }
        }else{
            for($i=0;$i<sizeof($jsonArrayObj);$i++){
                $judgeBool = false;
                $thisField = $jsonArrayObj[$i] -> Field;
                for($c=0;$c<sizeof($limitFieldArray);$c++){
                    $limitField = $limitFieldArray[$c];
                    if(HandleStringToLowerCase($thisField) == HandleStringToLowerCase($limitField)){
                        $judgeBool = true;
                        break;
                    }
                }
                if(!$judgeBool){
                    $tableField .= $thisField . ",";
                }
            }
        }
        if(!IsNull($tableField)){
            $tableField = HandleStringDeleteLast($tableField);
        }        
        return $tableField;
    }    
    
    /**
     * 函数:获取数据表字段
     * 说明:传入数据库名称和表名称获取表字段信息，返回字段集合
     * 更新时间:Nevember 12, 2018 16:23:00
     * */
    public final static function TableFieldCustomString($tableName,$fpDataFieldStr){
        
        $sql = "SHOW FULL COLUMNS FROM ".$tableName.";";
        $list = DBHelper::DataList($sql,null,array("Field"));
        //Json数组字符串转Json对象
        $jsonArrayObj = GetJsonObject($list);
        //判断数组为空
        if(IsNull($jsonArrayObj)||IsNull($fpDataFieldStr)){
            return "";
        }
        
        //组合字段
        $tableField = "";
        $dataFieldArray = GetArray($fpDataFieldStr, ",");
        for($i=0;$i<sizeof($jsonArrayObj);$i++){
            $judgeBool = false;
            $thisField = $jsonArrayObj[$i] -> Field;
            if(strtolower($thisField)=="password"){
                continue;
            }
            for($c=0;$c<sizeof($dataFieldArray);$c++){
                $dataField = $dataFieldArray[$c];
                if(strtolower($thisField) == strtolower($dataField)){
                    $judgeBool = true;
                    break;
                }
            }
            if(!$judgeBool){
                $tableField .= $thisField . ",";
            }
        }
        //处理拼接后的字符串
        if(!IsNull($tableField)){
            $tableField = HandleStringDeleteLast($tableField);
        }
        return $tableField;
    }
    
    /**
     * 函数:获取数据表字段
     * 说明:传入数据库名称和表名称获取表字段信息，返回字段集合
     * 更新时间:October 13, 2018 14:19:00
     * */
    public final static function TableFieldAll($tableName,$fpField=""){
        if(!IsNull($fpField)){ $fpField = " WHERE Field='{$fpField}' ";}
        $sql = "SHOW FULL COLUMNS FROM ".$tableName."{$fpField};";
        $fieldsArray = array("Field","Type","Collation","Null","Key","Default","Comment");
        return DBHelper::DataList($sql,null,$fieldsArray);
    }
    


    /**
     * 函数:查询数据库的表字段
     * 说明:传入数据库名称和表名称获取表字段备注，返回字段备注集合
     * 更新时间:October 13, 2018 14:19:00
     * */
    public final static function TableFieldJson($tableName){
        $sql = "SHOW FULL COLUMNS FROM ".$tableName.";";
        $fieldsArray = array("Field","Type","Collation","Null","Key","Default","Comment");
        $list = DBHelper::DataList($sql,null,$fieldsArray);
        //为空判断
        if(IsNull($list)){
            return JsonInforData("数据表字段查询", "分页数据记录", $tableName, "0", "");
        }else{
            $dataCount = sizeof(json_decode($list));
            return JsonInforData("数据表字段查询", "分页数据记录", $tableName, $dataCount, $list);
        }
    }
    
    
    /**
     * 函数:查询记录
     * 说明:传入数据库名称、字段字符串、结果字符串、条件字符串、stmt成员数组，组合Select语句并执行，进行数据查询
     * 更新时间:October 13, 2018 14:21:00
     * */
    public static function SelectRecord($tableName,$fieldStr,$fieldArray,$whereStr,$stmtArray){
        $sql = "";
        if(IsNull($whereStr)){
            $sql = "SELECT ".fieldStr." FROM ".$tableName.";";
        }else{
            $sql = "SELECT ".fieldStr." FROM ".$tableName." WHERE ".$whereStr.";";
        }
        return DBHelper::DataList($sql,$stmtArray,$fieldArray);
    }
   
    /**
     * 函数:查询记录数
     * 说明:传入参数查询数据记录
     * 更新时间:October 13, 2018 14:21:00
     * */
    public static function SelectCountRecord($tableName,$whereStr,$stmtArray){
        $sql = "";
        if(IsNull($whereStr)){
            $sql = "SELECT count(true) count FROM ".$tableName.";";
        }else{
            $sql = "SELECT count(true) count FROM ".$tableName." WHERE ".$whereStr.";";
        }
        return DBHelper::DataString($sql,$stmtArray,null);
    }
   
  
    
}

