<?php

class DBSQLite{
    
    //数据库存储路径 
    private $dbPath = __DIR__."/db/log.db";
    
    //创建连接
    private function GetConnection(){
        $pdo = new PDO("sqlite:".($this->dbPath));
        return $pdo;
    }
    
    /**
     * 检测日志数据表是否存在
     * 2020-04-07 17:47:19
     * */
    public function CheckTableOperationLog(){
        $pdo = $this -> GetConnection();
        $pdo -> exec("CREATE TABLE IF NOT EXISTS operation_log(
            id INTEGER PRIMARY KEY,
            userType TEXT,
            userId TEXT,
            oDes TEXT,
            oFile TEXT,
            oFun TEXT,
            oLine TEXT,
            oResult TEXT,
            oRInfor TEXT,
            addTime TEXT)"
        );
    }
    
    /**
     * 添加操作日志
     * 2020-04-07 11:51:18
     * */
    public function OpreationLog($fpUserType,$fpUserId,$fpODescript,$fpOFile,$fpOFunction,$fpOLine,$fpOResult,$fpORInfor){
        $fpOFile = HandleStringReplace(HandleStringReplace($fpOFile,"\\","/"),$_SERVER['DOCUMENT_ROOT'],'');
        $fpODescript = UnicodeEncode($fpODescript);
        $fpORInfor = UnicodeEncode($fpORInfor);
        $pdo = $this -> GetConnection();
        $vAddTime = GetTimeNow();
        $vSql = "INSERT INTO operation_log(userType,userId,oDes,oFile,oFun,oLine,oResult,oRInfor,addTime) values('{$fpUserType}','{$fpUserId}','{$fpODescript}','{$fpOFile}','{$fpOFunction}','{$fpOLine}','{$fpOResult}','{$fpORInfor}','{$vAddTime}')";
        $stmt = $pdo->prepare($vSql);
        $res = $stmt->execute();
    }
    
    /**
     * 查询操作日志
     * 2020-04-07 20:04:42
     * */
    public function OpreationLogPaging(){
        //参数:页码
        $fpUserType = GetParameter("user_type","");
        if(!IsNull($fpUserType)&&!JudgeRegularNumberLetter($fpUserType)){  return JsonModelParameterRegular("user_type"); }
        //参数:页码
        $fpUserId = GetParameter("user_id","");
        if(!IsNull($fpUserId)&&!JudgeRegularIntRight($fpUserId)){ return JsonModelParameterRegular("user_id"); }
        //参数:页码
        $page = GetParameter("page","");
        if(!JudgeRegularIntRight($page)){ $page = 1; }
        //参数:条数
        $limit = GetParameter("limit","");
        if(!JudgeRegularIntRight($limit)){return JsonModelParameterRegular("limit");}
        //处理页码
        $pageInt = intval($page);
        //条数处理（最大请求1000条数据）
        $limitInt = intval($limit);
        if($limitInt>1000){ $limitInt = 1000; }
        //条数字符串
        $limitPageStr = " LIMIT " . ($pageInt-1)*$limitInt . "," . $limitInt;
        
        $vWhere = "";
        if(!IsNull($fpUserType)&&!IsNull($fpUserId)){
            $vWhere = " WHERE userType='{$fpUserType}' AND userId='{$fpUserId}' ";
        }
        
        //查询总数
        $pdo = $this->GetConnection();
        $query = $pdo->query("SELECT COUNT(id) count FROM operation_log {$vWhere}");            //创建预处理对象
        $row = $query->fetch(PDO::FETCH_ASSOC);
        $fpCount = $row["count"];
        
        //查询数据
        $pdo = $this->GetConnection();
        $vSql = "SELECT id,userType,userId,oDes,oFile,oFun,oLine,oResult,oRInfor,addTime FROM operation_log {$vWhere} ORDER BY id DESC {$limitPageStr}";
        $stmt = $pdo->query($vSql);
        
        $fieldArray = ["id","userType","userId","oDes","oFile","oFun","oLine","oResult","oRInfor","addTime"];
        $dataJson = "";                         //Json记录结果数组
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $thisString = "";
            for ($i = 0; $i < sizeof($fieldArray); $i++) {
                $field = $fieldArray[$i];
                $value = $row[$field];
                if($field=='oDes'||$field=='oRInfor'){
                    $value = UnicodeDecode($value);
                }else{
                    $value = HandleStringDbRecodeValue($value);
                }
                $thisString .= '"' . $field . '"' . ":" . '"' . $value . '"' . ',';
            }
            if (!IsNull($thisString)) {
                $dataJson .= '{' . substr($thisString, 0, -1) . '},';
            }
        }
        if(!IsNull($dataJson)){
            $dataJson = '['.substr($dataJson, 0, -1).']';
            return JsonInforData("操作日志", "{$fpUserType}:{$fpUserId}", "SQLITE", $fpCount, $dataJson);
        }
        return JsonInforData(操作日志, "{$fpUserType}:{$fpUserId}", "SQLITE", "", "");
    }

}



