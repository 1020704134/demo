<?php

//--- 引用区 ---
//引入项目配置文件 [固定配置]
include_once $_SERVER['DOCUMENT_ROOT'] . "/server/m/config/DBConfig.php";

class DBHelper{
    
    
    //==================== 数据库链接 ====================
    
    //创建数据库链接
    public static function GetConnection($persistent=false){
        
        //获取DSN
        $dsn = DBConfig::GetDsn();
        
        try {
            
            //返回PDO对象
            if($persistent){
                $vPDO = new PDO($dsn,DBConfig::$dbUser,DBConfig::$dbPassWord,array(PDO::MYSQL_ATTR_INIT_COMMAND=>"set names utf8",PDO::ATTR_PERSISTENT=>true)); 
                $vPDO->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                return $vPDO;
            }else{
                $vPDO = new PDO($dsn,DBConfig::$dbUser,DBConfig::$dbPassWord,array(PDO::MYSQL_ATTR_INIT_COMMAND=>"set names utf8"));
                $vPDO->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                return $vPDO;
            }
            
        } catch (Exception $e) {
            
            //--- 异常日志 ---
            $vLogFilePath = $_SERVER['DOCUMENT_ROOT']."/server/log/database/mysql/".date('Ymd',time()).".txt";
            $fpFileInfor = GetPathFileName().":".__LINE__;
            WriteLog($vLogFilePath, "Exception", "创建PDO数据库链接失败", $fpFileInfor, $e->getMessage(), "");
            return "";
            
        }

    }
    
    //检测PDO链接是否可用
    public static function JudgeConnection($pdo){
        try{
            $pdo->getAttribute(PDO::ATTR_SERVER_INFO);
        } catch (Exception $e) {
            //--- 异常日志 ---
            $vLogFilePath = $_SERVER['HTTP_HOST']."/server/log/database/mysql/".date('Ymd',time())."txt";
            $fpFileInfor = GetPathFileName().":".__LINE__;
            WriteLog($vLogFilePath, "Exception", "检测PDO数据库链接", $fpFileInfor, $e->getMessage());
            //返回结果
            return false;
        }
        return true;
    }
    
    //关闭数据库链接
    public static function CloseConnection($dbh){
        $dbh = null;
    }
    
    
    //==================== SQL语句预处理 ====================
    
    /**
     * SQL语句预处理
     * 描述：简单SQL预处理的实现，原生实现
     * 功能：占位符、转义、拼接
     * 时间：2019-11-22 11:04:37
     * */
    public static function SQLStmt($sql,$stmtArray){
        
        //判断值数组是否为空
        if(IsNull($stmtArray)){
            return $sql;
        }
        
        //SQL语句预处理
        $index = 0;
        for($i=0;$i<sizeof($stmtArray);$i++){
            
            $vSub = 0;
            $vMember = addslashes($stmtArray[$i]);      //转义后的成员
            
            //寻找占位符的位置
            $vSub = strpos($sql, "?", $index);
            
            //找到占位符后替换占位符
            if($vSub>0){
                $sql = substr_replace($sql,$vMember,$vSub,1);
            }else{
                return $sql;
            }
            
            //计算查询起始位
            $index = $vSub + strlen($vMember);
            
        }
        return $sql;
    }
    
    
    //==================== SQL语句:PDO:直接结果 ====================
    
    
    //PDO数据提交
    public static function DataSubmit($sql, $stmtArray){
        try {
            //--- PDO：处理变量 ---
            $vStmtArraySize = sizeof($stmtArray);
            if (IsNull($stmtArray)) {
                $stmtArray = null;
                $vStmtArraySize = 0;
            }
            if ($vStmtArraySize != substr_count($sql, '?')) {
                throw new ClassFlyException("SQL预处理条件不对等");
            }
            //--- PDO：SQL执行 ---
            $pdo = DBHelper::GetConnection();       //创建连接
            $stmt = $pdo->prepare($sql);            //创建预处理对象
            $stmt->execute($stmtArray);             //SQL预处理并提交SQL语句
            $rowCount = $stmt->rowCount();          //记录数
            if ($rowCount == 0) {
                return false;
            }
            return true;
        } catch (Exception $e) {
            return false;
        }
        
    }
    
    //PDO数据查询:返回字符串结果
    public static function DataString($sql, $stmtArray, $fpField=null){
        //--- PDO：处理变量 ---
        $vStmtArraySize = sizeof($stmtArray);
        if (IsNull($stmtArray)) {
            $stmtArray = null;
            $vStmtArraySize = 0;
        }
        if ($vStmtArraySize != substr_count($sql, '?')) {
            throw new ClassFlyException("SQL预处理条件不对等");
        }
        //--- PDO：SQL执行 ---
        $pdo = DBHelper::GetConnection();       //创建连接
        $stmt = $pdo->prepare($sql);            //创建预处理对象
        //-SQL预处理-
        for($i=0;$i<sizeof($stmtArray);$i++){
            $stmt->bindParam($i+1, $stmtArray[$i]);
        }
        //-SQL执行-
        $stmt->execute();
        //-SQL结果集-
        $rows = $stmt->fetch();
        if(!$rows) {
            return "";
        }
        if(!IsNull($fpField)){
            return $rows[$fpField];
        }
        return $rows[0];
    }
    
    //PDO数据查询:返回字符串结果
    public static function DataBoolean($sql, $stmtArray){
        //--- PDO：处理变量 ---
        $vStmtArraySize = sizeof($stmtArray);
        if (IsNull($stmtArray)) {
            $stmtArray = null;
            $vStmtArraySize = 0;
        }
        if ($vStmtArraySize != substr_count($sql, '?')) {
            throw new ClassFlyException("SQL预处理条件不对等");
        }
        //--- PDO：SQL执行 ---
        $pdo = DBHelper::GetConnection();       //创建连接
        $stmt = $pdo->prepare($sql);            //创建预处理对象
        $stmt->execute($stmtArray);             //SQL预处理
        $rows = $stmt->fetch();
        if ($rows == 0) {
            return false;
        }
        return true;
    }
    
    //PDO数据查询:返回数字结果
    public static function DataList($sql, $stmtArray, $fieldArray, $fpToSlashBo=false){
        //--- PDO：处理变量 ---
        $vStmtArraySize = sizeof($stmtArray);
        if (IsNull($stmtArray)) {
            $stmtArray = null;
            $vStmtArraySize = 0;
        }
        if ($vStmtArraySize != substr_count($sql, '?')) {
            throw new ClassFlyException("SQL预处理条件不对等");
        }
        //--- PDO：SQL执行 ---
        $pdo = DBHelper::GetConnection();       //创建连接
        $stmt = $pdo->prepare($sql);            //创建预处理对象
        $stmt->execute($stmtArray);             //SQL预处理
        $dataJson = "";                         //Json记录结果数组
        while ($row = $stmt->fetch()) {
            $thisString = "";
            for ($i = 0; $i < sizeof($fieldArray); $i++) {
                $field = $fieldArray[$i];
                if($fpToSlashBo){
                    $thisString .= '"' . $field . '"' . ":" . '"' . HandleStringDbRecodeValue($row[$field]) . '"' . ',';
                }else{
                    $thisString .= '"' . $field . '"' . ":" . '"' . $row[$field] . '"' . ',';
                }
            }
            if (!IsNull($thisString)) {
                $dataJson .= '{' . substr($thisString, 0, -1) . '},';
            }
        }
        if (!IsNull($dataJson)) {
            $dataJson = '[' . substr($dataJson, 0, -1) . ']';
        }
        return $dataJson;
    }
    
    
    //==================== SQL语句:PDO:Json结果 ====================
    
    //PDO数据提交
    public static function DataSubmitJson($sql,$stmtArray,$fpInfor=""){
        //提交SQL语句
        try {
            
            //--- PDO：处理变量 ---
            $vStmtArraySize = sizeof($stmtArray);
            if(IsNull($stmtArray)){ $stmtArray = null; $vStmtArraySize = 0; }
            if($vStmtArraySize!=substr_count($sql,'?')){return JsonInforFalse("SQL预处理条件不对等", "SQL提交", FlyCode::$Code_SQL_Stmt_Check);}
            //--- PDO：SQL执行 ---
            $pdo = DBHelper::GetConnection();       //创建连接
            $stmt = $pdo->prepare($sql);            //创建预处理对象
            $stmt->execute($stmtArray);             //SQL预处理并提交SQL语句
            $rowCount = $stmt->rowCount();          //记录数
            //执行成功返回影响记录数
            return JsonInforTrue("执行成功", "影响记录数为{$rowCount}");
            
        } catch (Exception $e) {
            
            //异常
            $vExceptionString = $e->getMessage();
        
            //创建异常日志
            $vLogFilePath = $_SERVER['DOCUMENT_ROOT']."/server/log/database/mysql/".date('Ymd',time()).".txt";
            $fpFileInfor = GetPathFileName().":".__LINE__;
            WriteLog($vLogFilePath, "Exception", "PDO数据提交", $fpFileInfor, $vExceptionString,$sql);
        
            //返回SQL执行异常
            return JsonInforException("执行异常", $vExceptionString, __FILE__, __FUNCTION__, __LINE__);
        
        }
        
    }

    //PDO数据提交
    public static function DataInsertJson($sql,$stmtArray,$fpInfor,$fpOnlyKey){
        //提交SQL语句
        try {
    
            //--- PDO：处理变量 ---
            $vStmtArraySize = sizeof($stmtArray);
            if(IsNull($stmtArray)){ $stmtArray = null; $vStmtArraySize = 0; }
            if($vStmtArraySize!=substr_count($sql,'?')){return JsonInforFalse("SQL预处理条件不对等", "SQL提交", FlyCode::$Code_SQL_Stmt_Check);}
            //--- PDO：SQL执行 ---
            $pdo = DBHelper::GetConnection();       //创建连接
            $stmt = $pdo->prepare($sql);            //创建预处理对象
            $stmt->execute($stmtArray);             //SQL预处理并提交SQL语句
            $rowCount = $stmt->rowCount();          //记录数
            //执行成功返回影响记录数
            if($rowCount == 1){
                return JsonInforTrue("添加成功", "影响记录数为{$rowCount}", $fpOnlyKey);
            }
            return JsonInforFalse($fpInfor, "SQL记录添加:添加失败", FlyCode::$Code_Insert_Fail);
    
        } catch (Exception $e) {
    
            //异常
            $vExceptionString = $e->getMessage();
    
            //创建异常日志
            $vLogFilePath = $_SERVER['DOCUMENT_ROOT']."/server/log/database/mysql/".date('Ymd',time()).".txt";
            $fpFileInfor = GetPathFileName().":".__LINE__;
            WriteLog($vLogFilePath, "Exception", "PDO数据提交", $fpFileInfor, $vExceptionString,$sql);
    
            //返回SQL执行异常
            return JsonInforException("执行异常", $vExceptionString, __FILE__, __FUNCTION__, __LINE__);
    
        }
    
    }
    
    
    //PDO数据查询:返回数字结果
    public static function DataStringJson($sql,$stmtArray,$fpInfor="",$fpTable=""){
        
        //查询SQL语句
        try {
        
            //--- PDO：处理变量 ---
            $vStmtArraySize = sizeof($stmtArray);
            if(IsNull($stmtArray)){ $stmtArray = null; $vStmtArraySize = 0; }
            if($vStmtArraySize!=substr_count($sql,'?')){return JsonInforFalse("SQL预处理条件不对等", "SQL字符串查询", FlyCode::$Code_SQL_Stmt_Check);}
            //--- PDO：SQL执行 ---
            $pdo = DBHelper::GetConnection();       //创建连接
            $stmt = $pdo->prepare($sql);            //创建预处理对象
            $stmt->execute($stmtArray);             //SQL预处理
            $rows = $stmt->fetch();
            if($rows==0){
                return JsonInforFalse($fpInfor, "SQL数据查询:无记录", FlyCode::$Code_Select_Success_Null, $fpTable);
            }
            return JsonInforTrue($fpInfor, "SQL数据查询:有记录", $rows[0], $fpTable, FlyCode::$Code_Select_Success_Have); 
            
        } catch (Exception $e) {
        
            //异常
            $vExceptionString = $e->getMessage();
        
            //创建异常日志
            $vLogFilePath = $_SERVER['DOCUMENT_ROOT']."/server/log/database/mysql/".date('Ymd',time()).".txt";
            $fpFileInfor = GetPathFileName().":".__LINE__;
            WriteLog($vLogFilePath, "Exception", "PDO数据提交", $fpFileInfor, $vExceptionString,$sql);
        
            //返回SQL执行异常
            return JsonInforException("执行异常", $vExceptionString, __FILE__, __FUNCTION__, __LINE__);
        
        }
        
    }
    
    //PDO数据查询:返回数字结果
    public static function DataListJson($sql,$stmtArray,$fieldArray,$fpCount="",$fpTableName="",$fpInfor=""){
        
        //查询SQL语句
        try {
        
            //--- PDO：处理变量 ---
            $vStmtArraySize = sizeof($stmtArray);
            if(IsNull($stmtArray)){ $stmtArray = null; $vStmtArraySize = 0; }
            if($vStmtArraySize!=substr_count($sql,'?')){return JsonInforFalse("SQL预处理条件不对等", "SQL数据集查询", FlyCode::$Code_SQL_Stmt_Check);}
            //--- PDO：SQL执行 ---
            $pdo = DBHelper::GetConnection();       //创建连接
            $stmt = $pdo->prepare($sql);            //创建预处理对象
            $stmt->execute($stmtArray);             //SQL预处理
            $rowCount = $stmt->rowCount();          //记录数
            //当没有传入的统计数时，使用函数内部统计的记录数
            if(IsNull($fpCount)||$fpCount=="0"){$fpCount = $rowCount;}  
            $dataJson = "";                         //Json记录结果数组
            while ($row = $stmt->fetch()){
                $thisString = "";
                for($i=0;$i<sizeof($fieldArray);$i++){
                    $field = $fieldArray[$i];
                    $thisString .= '"'.$field.'"'.":".'"'.$row[$field].'"'.',';
                }
                if(!IsNull($thisString)){
                    $dataJson .= '{'.substr($thisString, 0, -1).'},';
                }
            }
            if(!IsNull($dataJson)){
                $dataJson = '['.substr($dataJson, 0, -1).']';
                return JsonInforData($fpInfor, "SQL数据集查询:有记录", $fpTableName, $fpCount, $dataJson);
            }
            return JsonInforData($fpInfor, "SQL数据集查询:无记录", $fpTableName, "", "");
        
        } catch (Exception $e) {
        
            //异常
            $vExceptionString = $e->getMessage();
        
            //创建异常日志
            $vLogFilePath = $_SERVER['DOCUMENT_ROOT']."/server/log/database/mysql/".date('Ymd',time()).".txt";
            $fpFileInfor = GetPathFileName().":".__LINE__;
            WriteLog($vLogFilePath, "Exception", "PDO数据提交", $fpFileInfor, $vExceptionString,$sql);
        
            //返回SQL执行异常
            return JsonInforException("执行异常", $vExceptionString, __FILE__, __FUNCTION__, __LINE__);
        
        }
    }

}


