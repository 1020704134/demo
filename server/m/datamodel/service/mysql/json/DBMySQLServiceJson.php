<?php


class DBMySQLServiceJson{
    
    
    //--- 数据相关 ---
    
    //数据添加（PDO）
    public static function OperationDataInsert($json){
    
        //--- 参数获取区 ---
        //数据表名
        $tableName = GetParameterJson("table_name",$json);
        if(!JudgeRegularTable($tableName)){return JsonModelParameterRegular("table_name");}
        //记录添加字段
        $insertField = GetParameterJson("insert_field",$json);
        if(!JudgeRegularFieldString($insertField)){return JsonModelParameterRegular("insert_field");}
        //主键字段
        $keyField = GetParameterJson("key_field",$json);
        if(!IsNull($keyField)&&!JudgeRegularFieldString($keyField)){return JsonModelParameterRegular("key_field");}
        $keyFieldInfor = GetParameterJson("key_field_infor",$json);
        
        //修改字段
        $updateField = GetParameterJson("update_field",$json);
        //修改值
        $updateValue = GetParameterJson("update_value",$json);
        //条件字段
        $whereField = GetParameterJson("where_field",$json);
        //条件值
        $whereValue = GetParameterJson("where_value",$json);
        
        //执行顺序：函数内部的执行步骤
        $ExecutionStep = GetParameterJson("execution_step",$json);
        if(!IsNull($ExecutionStep)&&!JudgeRegularFieldString($ExecutionStep)){return JsonModelParameterRegular("execution_step");}
        $vFirstStep = GetArrayMember($ExecutionStep, ",", 0);
        //调试输出
        $sqlDebug = GetParameterDebug($json);
    
        //--- 变量声明区 ---
        $sqlDebugString = "";                               //Sql调试输出字符串
        $sqlDebugCount = 0;                                 //Sql调试输出语句数量
        $vInserFieldArray = explode(",",$insertField);      //数据添加字段数组
    
        //--- 逻辑：查询记录是否已经存在 ---
    
        //判断数据表是否存在
        if(!DBMySQLJudge::JudgeTable($tableName)){ return JsonInforFalse("数据表不存在", $tableName); }
    
        
        //--- 步骤：修改 >>> 失败后 >>> 添加 ---
        if(!IsNull($vFirstStep)&&strtolower($vFirstStep)=="update"){
            if(!IsNull($updateField)&&!IsNull($whereField)){
                $vUpdateResult = MIndexDataUpdate($json);
                if(JudgeJsonTrue($vUpdateResult)){
                    return $vUpdateResult;
                }
            }           
        }
        
        
        //--- 步骤：添加 >>> 记录已存在 >>> 修改 ---
        
        //主键字段正确性判断
        $vKeyFieldJudge = true;
        //判断唯一Key是否为空
        if(!IsNull($keyField)){
            
            //主键字段性判断
            $vKeyFieldJudgeTimes = 0;
            //分割或主键条件
            $keyFieldOrArray = explode("|",$keyField);
            //分割主键字段或条件为数组
            for($c=0;$c<sizeof($keyFieldOrArray);$c++){
                
                //主键字段字符串
                $vKeyFieldString = $keyFieldOrArray[$c];
                
                //分割成员字符串为主键字段数组
                $keyFieldArray = explode(",",$vKeyFieldString);
                
                //排除值为none的主键字段
                $keyFieldArrayString = "";
                for($d=0;$d<sizeof($keyFieldArray);$d++){
                    $field = $keyFieldArray[$d];
                    $fieldValue = GetParameterJson($field,$json);
                    if($fieldValue!="none"){
                        $keyFieldArrayString .= $field.",";
                    }
                }
                
                //排除none值字段后为空的主键限制条件跳出主键校验循环
                //主键数组处理为空时跳出循环吗
                if(!IsNull($keyFieldArrayString)){
                    $keyFieldArrayString = HandleStringDeleteLast($keyFieldArrayString);
                }
                
                //主键字段字符串判断
                if($vKeyFieldString == $keyFieldArrayString){
                    $vKeyFieldJudgeTimes += 1;
                }else{
                    continue;
                }
                
                //将处理后的主键转化为主键数组
                $keyFieldArray = explode(",",$keyFieldArrayString);
                $vSelectArray = array();        //预处理数组
                $keyFieldValue = "";
                $keyWhereValue = "";
                $vSqlDebug = "";
                $field = "";
                for($i=0;$i<sizeof($keyFieldArray);$i++){
    
                    $field = $keyFieldArray[$i];    //主键字段
    
                    //--- 判断主键字段是否为添加字段 ---
                    $vKeyFieldJudgeBo = false;
                    $vInserKeyField = "";
                    for($j=0;$j<sizeof($vInserFieldArray);$j++){
                        $vInserKeyField = $vInserFieldArray[$j];
                        if($field==$vInserKeyField){
                            $vKeyFieldJudgeBo = true;
                            break;
                        }
                    }
                    if(!$vKeyFieldJudgeBo){return JsonInforFalse("记录添加字段中不存在该关键字段：{$field}", $tableName);}
    
                    //--- 根据主键字段进行记录查询 ---
                    $keyFieldValue = GetParameterJson($field,$json);
                    if(IsNull($keyFieldValue)){
                        return JsonModelParameterNull($field);
                    }
                    if($i==0){
                        //SQL及预处理数组
                        $keyWhereValue .= "{$field}=?";
                        array_push($vSelectArray, $keyFieldValue);
                        //SQL调试输出
                        if($sqlDebug){ $vSqlDebug .= $field . "=" . "'" . addslashes($keyFieldValue) . "'"; }
                    }else{
                        //SQL及预处理数组
                        $keyWhereValue .= " AND {$field}=?";
                        array_push($vSelectArray, $keyFieldValue);
                        //SQL调试输出
                        if($sqlDebug){ $vSqlDebug .= " AND " . $field . "=" . "'" . addslashes($keyFieldValue) . "'"; }
                    }
                }
                
                //SQL调试输出
                if($sqlDebug){
                    $sql = "SELECT TRUE FROM ".$tableName." WHERE ".$vSqlDebug." LIMIT 0,1;";
                    $sqlDebugString .= JsonObj(JsonKeyValue("sqlType","Select") . "," . JsonKeyValue("descript",$keyFieldArrayString) . "," . JsonKeyValue("sql", $sql)) . ",";
                    $sqlDebugCount += 1;
                }else{
                    //查询该主键记录是否存在
                    $sql = "SELECT TRUE FROM ".$tableName." WHERE ".$keyWhereValue." LIMIT 0,1;";
                    if(!IsNull(DBHelper::DataString($sql, $vSelectArray))){
                        //如果记录存在则进行记录值修改
                        if(!IsNull($updateField)&&!IsNull($whereField)){
                            return MIndexDataUpdate($json);
                        }
                        //返回添加记录提示：记录已存在
                        if(IsNull($keyFieldInfor)){ $keyFieldInfor = FlyCode::$Code_Insert_Already_Exist_Msg; }
                        return JsonInforFalse($keyFieldInfor, "{$tableName}:{$keyFieldArrayString}",FlyCode::$Code_Insert_Already_Exist);
                    }
                }
            }
            
            //主键字段正确性判断
            if($vKeyFieldJudgeTimes>0){
                $vKeyFieldJudge = true;
            }else{
                $vKeyFieldJudge = false;
            }
            
        }
        
        if(!$vKeyFieldJudge){
           return JsonInforFalse("记录主键字段值不得为空", $keyField); 
        }
    
    
        //--- 记录添加区 ---
    
        //数据表字段类型数据（用于字段值长度判断）
        $vTablefieldType = DBMySQLSelect::SelectFieldLength($tableName);
        $vTablefieldType = json_decode($vTablefieldType);
    
        $vInsertField = "";         //处理后的添加字段字符串
        $vInsertValue = "";         //添加字段值
        $vInsertValueArray = array();    //添加字段值
        $vInsertValueDebug = "";    //添加字段值（debug）
    
        //获取参数值，拼接记录添加字符串
        foreach($vInserFieldArray as $field){
            //获取参数值
            $fieldValue = GetParameterJson($field,$json);
            //必填字段不得为空
            if(IsNull($fieldValue)){ return JsonModelParameterNull($field); }
            //判断参数值是否超过字段长度:开启判断
            $length = mb_strlen($fieldValue);
            foreach($vTablefieldType as $fieldJson){
                //当字段名相同时判断字段类型及值长度
                if(strtolower($fieldJson->Field)==strtolower($field)){
                    //字段类型
                    $fieldType = $fieldJson->Type;
                    //判断是否寻找到varchar
                    if(is_numeric(strpos($fieldType, "varchar"))){
                        $fieldTypeHandle = substr(substr($fieldType,8),0,-1);
                        if($length>intval($fieldTypeHandle)){
                            return JsonModelParameterLength($field, $length, $fieldTypeHandle);
                        }
                    }
    
                }
            }
    
            //拼接记录添加字符串
            if($fieldValue!="none"){
                //SQL及预处理数组
                $vInsertField .= $field . ",";
                $vInsertValue .= "?,";
                array_push($vInsertValueArray, $fieldValue);
                //SQL调试输出
                if($sqlDebug){ $vInsertValueDebug .= "'" . addslashes($fieldValue) . "'" . ","; }
            }
        }
    
        //判断组合后的字段为空
        if(IsNull($vInsertField)){
            return JsonInforFalse("记录添加字段内容为空", $tableName);
        }
    
        //Insert添加基础字段：onlyKey,addTime
        $vOnlyKey = GetId("R");
        $vAddTime = GetTimeNow();
        $vInsertField = $vInsertField."onlyKey,addTime";
        $vInsertValue = $vInsertValue."?,?";
        array_push($vInsertValueArray, $vOnlyKey);
        array_push($vInsertValueArray, $vAddTime);
        //insert debug
        if($sqlDebug){ $vInsertValueDebug .= "'" . $vOnlyKey . "'" . "," . "'" . $vAddTime . "'"; }
    
     
        //SQL调试输出
        if($sqlDebug){
            $sql = "INSERT IGNORE INTO ".$tableName."(".$vInsertField.") VALUES (".$vInsertValueDebug.");";
            $sqlDebugString .= JsonObj(JsonKeyValue("sqlType","Insert") . "," . JsonKeyValue("descript",$keyFieldArrayString) . "," . JsonKeyValue("sql", $sql));
            return JsonModelDataString(JsonArray($sqlDebugString), $sqlDebugCount+1, "", "false");
        }
    
        $sql = "INSERT IGNORE INTO ".$tableName."(".$vInsertField.") VALUES (".$vInsertValue.");";
        if(DBHelper::DataSubmit($sql,$vInsertValueArray)){
            return JsonInforTrue("添加成功", "",$vOnlyKey,$tableName);
        }
    
        return JsonInforFalse("记录添加失败", $tableName);
    
    }
    
    //获取数据分页（PDO）
    public static function GetDataPaging($json){
    
        //--- 参数获取区 ---
        //参数:数据表
        $tableName = GetParameterJson("table_name",$json);
        if(!JudgeRegularTable($tableName)){return JsonModelParameterRegular("table_name");}
        //参数:数据字段
        $dataField = GetParameterJson("data_field",$json);
        if(!IsNull($dataField)&&!JudgeRegularFieldString($dataField)){return JsonModelParameterRegular("data_field");}
        //参数:页码
        $page = GetParameter("page",$json);
        if(!JudgeRegularIntRight($page)){return JsonModelParameterRegular("page");}
        //参数:条数
        $limit = GetParameter("limit",$json);
        if(!JudgeRegularIntRight($limit)){return JsonModelParameterRegular("limit");}
        //参数:模糊查询
        $likeField = GetParameter("like_field",$json);
        $likeKey = GetParameter("like_key",$json);
        //参数:排序
        $orderby = GetParameterNoCode("orderby",$json);
        //分组
        $vGroupBy = GetParameter("group_by",$json);
        //参数:条件
        $whereField = GetParameterJson("where_field",$json);
        $whereValue = GetParameterJson("where_value",$json);
        $whereSon = GetParameterJson("where_son",$json);
        //结果信息
        $resultTips = GetParameterJson("result_tips",$json);
        if(IsNull($resultTips)){ $resultTips = $tableName; }
        //调试输出
        $sqlDebug = GetParameterDebug($json);
        //完整SQL语句的传入
        $pJsonSql = GetParameterJson("sql",$json);
        $pJsonSqlCount = GetParameterJson("sql_count",$json);
        if(IsNull($pJsonSqlCount)){$pJsonSqlCount = $pJsonSql;}
    
        //--- 变量声明区 ---
        if(!DBMySQLJudge::JudgeTable($tableName)){ return JsonInforFalse("数据表不存在", $tableName); }
        $vTableDataField = DBMySQLSelect::TableFieldString($tableName);     //数据表字段
        $vTableDataFieldArray = GetArray($vTableDataField, ",");            //数据表字段数组
        $sqlDebugString = "";                                               //Sql调试输出字符串
        $sqlDebugCount = 0;                                                 //Sql调试输出语句数量
    
    
        //--- 函数逻辑区 ---
    
        //处理页码
        $pageInt = intval($page);
        //条数处理（最大请求1000条数据）
        $limitInt = intval($limit);
        if($limitInt>1000){ $limitInt = 1000; }
    
        //条数字符串
        $limitPageStr = " LIMIT " . ($pageInt-1)*$limitInt . "," . $limitInt;
    
        //模糊查询字符串
        $likeStr = "";
        $likeStrDebug = "";
        $vLikeArray = "";
        if(!(IsNull($likeField)||IsNull($likeKey))){
            $vLikeArray = array();
            $likeFieldArray = explode(',',$likeField);
            $likeKeyArray = explode(',',$likeKey);
            for($i=0;$i<sizeof($likeFieldArray);$i++){
                $vLikeField = $likeFieldArray[$i];
                if(!JudgeArrayMember($vTableDataFieldArray, $vLikeField)){
                    return JsonInforFalse("模糊查询字段不存在", $vLikeField);
                }
                $vLikeValue = addslashes($likeKeyArray[$i]);
                if($i==0){
                    //模糊查询
                    $likeStr = $vLikeField . " LIKE " . "'%{$vLikeValue}%'";
                    //array_push($vLikeArray, $likeKeyArray[$i]);
                    //SQL调试输出
                    if($sqlDebug){ $likeStrDebug = $vLikeField . " LIKE " . "'%{$likeKeyArray[$i]}%'"; }
                }else{
                    //模糊查询
                    $likeStr .= " AND " . $vLikeField . " LIKE " . "'%{$vLikeValue}%'";
                    //array_push($vLikeArray, $likeKeyArray[$i]);
                    //SQL调试输出
                    if($sqlDebug){ $likeStrDebug = " AND " . $vLikeField . " LIKE " . "'%{$likeKeyArray[$i]}%'"; }
                }
            }
        }
        
        //Group by
        $vGroupByString = "";
        if(!IsNull($vGroupBy)){
            $vGroupByString = " GROUP BY {$vGroupBy} ";
        }
    
        //Orderby
        $orderbyStr = "";
        if(!IsNull($orderby)){
            //判断排序字段是否存在
            $orderbyArray = explode(',',$orderby);
            for($i=0;$i<sizeof($orderbyArray);$i++){
                $orderbyFieldMember = $orderbyArray[$i];
                $orderbyFieldArray = explode(':',$orderbyFieldMember);
                $orderbyField = $orderbyFieldArray[0];
                if(IsNull($orderbyField)){
                    return JsonInforFalse("排序字段被定义为空", $orderby);
                }
                if(!JudgeArrayMember($vTableDataFieldArray, $orderbyField)){
                    return JsonInforFalse("排序字段不存在", $orderbyField);
                }
                if($orderbyFieldArray>1){
                    $vOrderbyDesc = $orderbyFieldArray[1];
                    if(IsNull($vOrderbyDesc[1])){
                        $orderbyStr .= $orderbyField . ",";
                    }else if(strtolower($vOrderbyDesc) == "desc"){
                        $orderbyStr .= $orderbyField . " DESC " . ",";
                    }else if(strtolower($vOrderbyDesc) == "asc"){
                        $orderbyStr .= $orderbyField . " ASC " . ",";
                    }
                }else{
                    $orderbyStr .= $orderbyField . ",";
                }
            }
            $orderbyStr = " ORDER BY " . HandleStringDeleteLast($orderbyStr);
        }
        
    
        //自定义字段判断:为空为全部字段
        $dataFieldArray = array();
        if(IsNull($dataField)){
            $dataField = $vTableDataField;
            $dataFieldArray = $vTableDataFieldArray;
        }else{
            $dataFieldArray = explode(',',$dataField);
        }
    
        //--- Where语句处理区 ---
        $vWhereValueArray = "";
        if(!IsNull($whereValue)){ $vWhereValueArray = GetArray($whereValue, ","); }
        $whereString = DBMySQLWhere::WherePdoHandle($whereField, $vWhereValueArray, $whereSon);
        $whereStringDebug = "";
        if($sqlDebug){ $whereStringDebug = DBMySQLWhere::WherePdoHandleDebug($whereField, $whereValue, $whereSon); }
    
    
        //--- Count ---
        $sqlCountStr = "";
    
        //判断Like字符串是否为空
        if(!IsNull($likeStr)&&!IsNull($whereString)){
            $likeStr = " AND " . $likeStr;
            $likeStrDebug = " AND " . $likeStrDebug;
        }else if(!IsNull($likeStr)&&IsNull($whereString)){
            $likeStr = " WHERE " . $likeStr;
            $likeStrDebug = " WHERE " . $likeStrDebug;
        }
    
        //合并SQL预处理数组
        $vSqlStmtArray = HandleArrayMerge($vWhereValueArray, $vLikeArray);
        if(IsNull($vSqlStmtArray)){$vSqlStmtArray = null;}
    
        
    
        //SQL调试输出
        if($sqlDebug){
            //传入的整体SQL语句不为空时
            if(!IsNull($pJsonSql)){
                //输出:统计总数SQL
                $sqlCountStr = $pJsonSqlCount . $likeStrDebug . $vGroupByString . $orderbyStr . ";";
                $sqlDebugString .= JsonObj(JsonKeyValue("sqlType","Select") . "," . JsonKeyValue("descript","count") . "," . JsonKeyValue("sql", $sqlCountStr)) . ",";
                $sqlDebugCount += 1;
                //输出:查询SQL
                $sql = $pJsonSql . $likeStrDebug . $vGroupByString . $orderbyStr . $limitPageStr . ";";
                $sqlDebugString .= JsonObj(JsonKeyValue("sqlType","Select") . "," . JsonKeyValue("descript","recode") . "," . JsonKeyValue("sql", $sql));
                return JsonModelDataString(JsonArray($sqlDebugString), $sqlDebugCount+1,"","false");
            }else{
                //输出:统计总数SQL
                $sqlCountStr = "SELECT COUNT(TRUE) number FROM " .$tableName . $whereStringDebug . $likeStrDebug . $vGroupByString . $orderbyStr . ";";
                $sqlDebugString .= JsonObj(JsonKeyValue("sqlType","Select") . "," . JsonKeyValue("descript","count") . "," . JsonKeyValue("sql", $sqlCountStr)) . ",";
                $sqlDebugCount += 1;
                //输出:查询SQL
                $sql = "SELECT ".$dataField." FROM " .$tableName . $whereStringDebug . $likeStrDebug . $vGroupByString . $orderbyStr . $limitPageStr . ";";
                $sqlDebugString .= JsonObj(JsonKeyValue("sqlType","Select") . "," . JsonKeyValue("descript","recode") . "," . JsonKeyValue("sql", $sql));
                return JsonModelDataString(JsonArray($sqlDebugString), $sqlDebugCount+1,"","false");
            }
        }else{
            //传入的整体SQL语句不为空时
            if(!IsNull($pJsonSql)){
                //统计总数
                $sqlCountStr = $pJsonSqlCount . $likeStr . $vGroupByString . $orderbyStr . ";";
                $count = DBHelper::DataString($sqlCountStr,$vSqlStmtArray);
                //查询记录
                $sql = $pJsonSql . $likeStr . $vGroupByString . $orderbyStr . $limitPageStr . ";";
                $list = DBHelper::DataList($sql, $vSqlStmtArray, $dataFieldArray);
                if(IsNull($list)){
                    return JsonModelDataNull("记录数为0", $resultTips);
                }
                return JsonModelSelectDataHave("有记录", $resultTips, $count, $list);
            }else{
                //统计总数
                $sqlCountStr = "SELECT COUNT(TRUE) number FROM " .$tableName . $whereString . $likeStr . $vGroupByString . $orderbyStr . ";";
                $count = DBHelper::DataString($sqlCountStr,$vSqlStmtArray);
                //查询记录
                $sql = "SELECT ".$dataField." FROM " .$tableName . $whereString . $likeStr . $vGroupByString . $orderbyStr . $limitPageStr . ";";
                $list = DBHelper::DataList($sql, $vSqlStmtArray, $dataFieldArray);
                if(IsNull($list)){
                    return JsonModelDataNull("记录数为0", $resultTips);
                }
                return JsonModelSelectDataHave("有记录", $resultTips, $count, $list);
            }
        }
    
        return JsonInforFalse("执行失败", $tableName);
    }
    
    
    
    //修改数据（PDO）
    public static function OperationDataUpdate($json){
         
        //--- 参数获取区 ---
        //参数:表名
        $tableName = GetParameterJson("table_name",$json);
        if(!JudgeRegularTable($tableName)){return JsonModelParameterRegular("table_name");}
        //参数:修改
        $updateField = GetParameterJson("update_field",$json);
        if(!JudgeRegularFieldString($updateField)){return JsonModelParameterException("update_field",$updateField,256,"值必须是字段字符串",__LINE__);}
        $updateValue = GetParameterJson("update_value",$json);
        if(IsNull($updateValue)){ return JsonModelParameterException("update_value",$updateValue,10240,"值必须是值字符串",__LINE__);}
        //参数:条件
        $whereField = GetParameterJson("where_field",$json);
        $whereValue = GetParameterJson("where_value",$json);
        $whereSon = GetParameterJson("where_son",$json);
        //调试输出
        $sqlDebug = GetParameterDebug($json);
    
        //--- 变量声明区 ---
        if(!DBMySQLJudge::JudgeTable($tableName)){ return JsonInforFalse("数据表不存在", $tableName); }
        $vTableDataField = DBMySQLSelect::TableFieldAllString($tableName);     //数据表字段
        $vTableDataFieldArray = GetArray($vTableDataField, ",");            //数据表字段数组
        $sqlDebugString = "";                                               //Sql调试输出字符串
        $sqlDebugCount = 0;                                                 //Sql调试输出语句数量
    
        //--- 函数逻辑区 ---
    
        //Where语句处理
        $vWhereValueArray = "";
        if(!IsNull($whereValue)){ $vWhereValueArray = GetArray($whereValue, ","); }
        $whereString = DBMySQLWhere::WherePdoHandle($whereField, $vWhereValueArray, $whereSon);
        if(IsNull($whereString)){ return JsonInforFalse("记录修改条件为空", $tableName); }
        //调试输出:Where语句处理
        $whereStringDebug = "";
        if($sqlDebug){ $whereStringDebug = DBMySQLWhere::WherePdoHandleDebug($whereField, $whereValue, $whereSon); }
    
        //Update语句处理
        $vUpdateFieldArray = explode(',',$updateField);
        $vUpdateValueArray = explode(',',$updateValue);
        if(sizeof($vUpdateFieldArray)!=sizeof($vUpdateValueArray)){
            return JsonInforFalse("修改字段与修改值不对等", $tableName);
        }
        //判断修改字段是否合法
        for($i=0;$i<sizeof($vUpdateFieldArray);$i++){
            //$vUpdateField = HandleStringUnderlineToHump($vUpdateFieldArray[$i]);
            $vUpdateField = $vUpdateFieldArray[$i];
            if(!JudgeArrayMember($vTableDataFieldArray, $vUpdateField)){
                return JsonInforFalse("修改字段不存在", $vUpdateField);
            }
        }
        //生成修改SQL预处理字符串
        $vUpdateString = DBMySQLUpdate::UpdateValue($vUpdateFieldArray);
        //组合修改SQL调试输出字符串
        $vUpdateStringDebug = "";
        if($sqlDebug){
            for($i=0;$i<sizeof($vUpdateFieldArray);$i++){
                if($i==0){
                    $vUpdateStringDebug = $vUpdateFieldArray[$i]."="."'".addslashes($vUpdateValueArray[$i])."'";
                }else{
                    $vUpdateStringDebug .= " , " . $vUpdateFieldArray[$i]."="."'".addslashes($vUpdateValueArray[$i])."'";
                }
            }
        }
    
        //判断updateTime是否存在，存在则对该字段时间进行修改
        if(DBMySQLJudge::JudgeTableField($tableName, "updateTime")){
            $vUpdateString = $vUpdateString . " , updateTime = " . "'" . GetTimeNow() . "'";
            $vUpdateStringDebug = $vUpdateStringDebug . " , updateTime = " . "'" . GetTimeNow() . "'";
        }
    
        //合并SQL预处理数组
        $vSqlStmtArray =  HandleArrayMerge($vUpdateValueArray, $vWhereValueArray);
        if(IsNull($vSqlStmtArray)){$vSqlStmtArray = null;}
    
        //SQL调试输出
        if($sqlDebug){
            $sqlDebugCount += 1;
            //输出:修改SQL
            $sql = "UPDATE ".$tableName." SET " . $vUpdateStringDebug . $whereStringDebug . ";";
            $sqlDebugString .= JsonObj(JsonKeyValue("sqlType","Update") . "," . JsonKeyValue("descript","update") . "," . JsonKeyValue("sql", $sql));
            return JsonModelDataString(JsonArray($sqlDebugString), $sqlDebugCount+1,"","false");
        }else{
            //修改记录
            $sql = "UPDATE ".$tableName." SET " . $vUpdateString . $whereString . ";";
            if(DBHelper::DataSubmit($sql, $vSqlStmtArray)){
                return JsonInforTrue("修改成功",$tableName);
            }
            return JsonInforFalse("修改失败",$tableName);
        }
    
        return JsonInforFalse("执行失败", $tableName);
    
    }
    
    
    //获取一条数据
    public static function GetDataOne($json){

        //--- 参数获取区 ---
        $tableName = GetParameter("table_name",$json);
        if(IsNull($tableName)){return JsonModelParameterNull("table_name");}
        $whereField = GetParameter("where_field",$json);
        $whereValue = GetParameter("where_value",$json);
        
        //自定义字段判断:为空为全部字段
        $fieldArray = null;
        $commentArray = null;
        $fieldListArray = DBMySQLSelect::TableFieldAll($tableName);
        $fieldArray = GetJsonArray($fieldListArray,"Field");
        $commentArray = GetJsonArray($fieldListArray,"Comment");
        $dataField = HandleArrayConnect($fieldArray,",");
        
        //--- Where语句处理区 ---
        $whereString = DBMySQLWhere::WhereHandle($whereField, $whereValue, "");
        
        //--- SQL语句组合区 ---
        $sql = "";
        if(IsNull($whereString)){
            return JsonInforFalse("该接口SQL语句的调用必须具有条件束缚",$tableName."表详细记录");
        }else{
            $sql = "SELECT ".$dataField." FROM " . $tableName . " WHERE " . $whereString . ";";
        }
        
        //--- 获取查询结果 ---
        $list = DBHelper::DataList($sql, null, $fieldArray);
        
        //--- 判断是否是数组形式返回 ---
        $dataStr = "";
        $dataCount = 0;
        $dataStr = FlyJson::JsonKeyArrayList($fieldArray, $commentArray, $list);
        $dataCount = sizeof($fieldArray);
        
        if(IsNull($list)){
            return JsonInforData("没有记录", "获取一条详细记录", $tableName, "0", "");
        }
        return JsonInforData("有记录", "获取一条详细记录", $tableName, $dataCount, JsonArray($dataStr));
    }    
    
    /**
     * 记录上下架
     * 2019-12-25 10:21:55
     * */
    public static function OperationDataShelfState($json){
    
        //--- 参数获取区 ---
        $tableName = GetParameter("table_name",$json);
        if(IsNull($tableName)){return JsonModelParameterNull("table_name");}
    
        $id = GetParameter("id",$json);
        if(IsNull($id)){return JsonModelParameterNull("id");}
    
        $shelfstate = GetParameterNoCode("shelfstate",$json);
        if(IsNull($shelfstate)){return JsonModelParameterNull("shelfstate");}
        //参数：shelfstate：正整数正则判断
        if(!($shelfstate=="true"||$shelfstate=="false")){ return JsonInforFalse("上架状态值必须是true或false", "shelfstate"); }
    
        $sql = "UPDATE {$tableName} SET shelfState='{$shelfstate}' WHERE id='{$id}';";
    
        if(DBHelper::DataSubmit($sql, null)){
            $vShelfstateDescript = "上架";
            if($shelfstate=="false"){$vShelfstateDescript = "下架";}
            return JsonInforTrue("{$vShelfstateDescript}成功",$tableName);
        }
        return JsonInforFalse("修改失败",$tableName);
    }
        

    //数据记录删除(PDO)
    public static function OperationDataDelete($json){
        
        //--- 参数获取区 ---
        $tableName = GetParameterJson("table_name",$json);
        if(!JudgeRegularTable($tableName)){return JsonModelParameterNull("table_name");}
        
        $whereField = GetParameterJson("where_field",$json);
        $whereValue = GetParameterJson("where_value",$json);
        $whereSon = GetParameterJson("where_son",$json);
        $sqlDebug = GetParameterDebug($json);
        
        
        //--- Where语句处理区 ---
        $whereString = DBMySQLWhere::WhereHandle($whereField, $whereValue, $whereSon);
        
        //--- SQL语句组合区 ---
        $sql = "";
        if(IsNull($whereString)){
            return JsonInforFalse("该接口SQL语句的调用必须具有条件束缚", $tableName."表记录删除");
        }else{
            $sql = "DELETE FROM ".$tableName." WHERE ".$whereString.";";
        }
        
        if(!IsNull($sqlDebug)&&$sqlDebug=="true"){
            return JsonInforFalse($sql, "SQL调试");
        }
        
        //执行语句
        if(DBHelper::DataSubmit($sql,null)){
            return JsonInforTrue("删除成功", $tableName);
        }
        return JsonInforFalse("删除失败", $tableName);
    }
    
    
    //数据记录清除
    public static function OperationDataTruncate($json){
        
        //--- 参数获取区 ---
        $tableName = GetParameter("table_name",$json);
        if(!JudgeRegularTable($tableName)){return JsonModelParameterNull("table_name");}
        
        //执行语句
        $bo = DBMySQLDelete::TruncateTable($tableName);
        if($bo){
            return JsonInforTrue("数据清除完毕", $tableName);
        }
        return JsonInforFalse("数据清除失败", $tableName);
    }
    
      
    /**
     * 函数名称：数据记录索引重新判断
     * 创建时间：2020-01-10 10:48:32
     * */
    public static function OperationDataIndexNumberReorder($json){
    
        //--- 参数获取区 ---
        //参数:数据表名称
        $pTableName = GetParameter("table_name",$json);
        if(!JudgeRegularTable($pTableName)){return JsonModelParameterException("table_name", $pTableName, 36, "值必须是数据表名称", __LINE__);}
        
        //参数:记录ID:记录ID一
        $pIdOne = GetParameter("id_one",$json);
        if(!JudgeRegularIntRight($pIdOne)){return JsonModelParameterException("id_one", $pIdOne, 11, "值必须是正整数", __LINE__);}
    
        //参数:记录ID:记录ID二
        $pIdTwo = GetParameter("id_two",$json);
        if(!JudgeRegularIntRight($pIdTwo)){return JsonModelParameterException("id_two", $pIdTwo, 11, "值必须是正整数", __LINE__);}
    
        //判断两次传入的ID是否相同
        if($pIdOne==$pIdTwo){ return JsonInforFalse("不得传入相同的ID进行序号调整", "{$pTableName}:{$pIdOne}:{$pIdTwo}"); }
        
        //获取:fly_interface_param:表ID
        $vSql = "SELECT indexNumber FROM {$pTableName} WHERE id=? LIMIT 0,1;";
        $vIndexNumberOne = DBHelper::DataString($vSql, [$pIdOne]);
        if(IsNull($vIndexNumberOne)){ return JsonInforFalse("记录ID一不存在", $pTableName); }
        if($vIndexNumberOne == "-1" || IsNull($vIndexNumberOne)){ $vIndexNumberOne = $pIdOne; }
    
        //获取:fly_interface_param:表ID
        $vSql = "SELECT indexNumber FROM {$pTableName} WHERE id=? LIMIT 0,1;";
        $vIndexNumberTwo = DBHelper::DataString($vSql, [$pIdTwo]);
        if(IsNull($vIndexNumberTwo)){ return JsonInforFalse("记录ID二不存在", $pTableName); }
        if($vIndexNumberTwo == "-1" || IsNull($vIndexNumberTwo)){ $vIndexNumberTwo = $pIdTwo; }
    
        //修改:fly_interface_param:序号
        $vSql = "UPDATE {$pTableName} SET indexNumber=? WHERE id=?;";
        $vUpdateResult = DBHelper::DataSubmit($vSql, [$vIndexNumberTwo,$pIdOne]);
        if(!$vUpdateResult){ return JsonInforFalse("记录ID一修改失败", $pTableName); }
    
        //修改:fly_interface_param:序号
        $vSql = "UPDATE {$pTableName} SET indexNumber=? WHERE id=?;";
        $vUpdateResult = DBHelper::DataSubmit($vSql, [$vIndexNumberOne,$pIdTwo]);
        if(!$vUpdateResult){ return JsonInforFalse("记录ID二修改失败", $pTableName ); }
    
        return JsonInforTrue("{$pIdOne} > {$pIdTwo} 序号修改成功", $pTableName);
    }
    
    
    //添加空记录
    public static function OperationDataInsertNull($json){
        $tableName = GetParameter("table_name",$json);
        if(IsNull($tableName)){return JsonModelParameterNull("tableName");}
        //字段组合
        $vDataField = "";
        $vDataValue = "";
        //判断数据表是否有 onlyKey
        if(DBMySQLJudge::JudgeTableField($tableName, "onlyKey")){
            $vDataField .= "onlyKey";
            $vDataValue .= HandleStringAddQuotation(GetId("R"));
        }
        //判断数据表是否有 addTime
        if(DBMySQLJudge::JudgeTableField($tableName, "addTime")){
            $vDataField .= IsNull($vDataField)?"addTime":",addTime";
            $vDataValue .= IsNull($vDataValue)?HandleStringAddQuotation(GetTimeNow()):",".HandleStringAddQuotation(GetTimeNow());
        }
        //判断数据表是否有 shelfState
        if(DBMySQLJudge::JudgeTableField($tableName, "shelfState")){
            $vDataField .= IsNull($vDataField)?"shelfState":",shelfState";
            $vDataValue .= IsNull($vDataValue)?HandleStringAddQuotation("false"):",".HandleStringAddQuotation("false");
        }
        //判断数据表是否有 state
        if(DBMySQLJudge::JudgeTableField($tableName, "state")){
            $vDataField .= IsNull($vDataField)?"state":",state";
            $vDataValue .= IsNull($vDataValue)?HandleStringAddQuotation("STATE_NORMAL"):",".HandleStringAddQuotation("STATE_NORMAL");
        }
        //SQL
        $sql = "INSERT INTO ".$tableName."({$vDataField}) VALUES({$vDataValue});";
        if(DBHelper::DataSubmit($sql, null)){
            return JsonInforTrue("添加成功", $tableName);
        };
        return JsonInforFalse("添加失败", $tableName);
    }    
    
    //--- 数据库相关 ---
    
    //查询数据表是否存在
    public static function GetDBTableCheck($json){
        
        //--- 参数获取区 ---
        $tableName = GetParameter("table_name",$json);
        if(!JudgeRegularTable($tableName)){return JsonModelParameterNull("table_name");}
        
        //查询数据表是否存在
        if(DBMySQLJudge::JudgeTable($tableName)){
            return JsonInforTrue("数据表存在", $tableName);
        }
        return JsonInforFalse("数据表不存在", $tableName);
        
    }
    
    //字段添加
    public static function OperationDBTableFieldAdd($json){
        
        //--- 参数获取区 ---
        $tableName = GetParameter("table_name",$json);
        if(IsNull($tableName)){return JsonModelParameterNull("table_name");}
        $fieldNew = GetParameter("field_new",$json);
        if(IsNull($fieldNew)){return JsonModelParameterNull("field_new");}
        $fieldType = GetParameter("field_type",$json);
        if(IsNull($fieldType)){return JsonModelParameterNull("field_type");}
        $fieldLength = GetParameter("field_length",$json);
        if(IsNull($fieldLength)){return JsonModelParameterNull("field_length");}
        $fieldUp = GetParameter("field_up",$json);
        if(IsNull($fieldUp)){return JsonModelParameterNull("field_up");}
        $fieldNotNull = GetParameter("field_not_null",$json);
        $fieldDefault = GetParameter("field_default",$json);
        $fieldDescript = GetParameter("field_descript",$json);
        $sqlDebug = GetParameterDebug($json);
        
        
        //判断数据表是否存在
        if(!DBMySQLJudge::JudgeTable($tableName)){
            return JsonInforFalse("数据表不存在", $tableName);
        }
        
        //判断表字段是否存在
        if(DBMySQLJudge::JudgeTableField($tableName, $fieldNew)){
            return JsonInforFalse("表字段已存在", $tableName.":".$fieldNew);
        }
        
        //为空判断
        $fieldNotNullStr = "NULL";
        if(!IsNull($fieldNotNull)&&strtolower($fieldNotNull)=="true"){
            $fieldNotNullStr = "NOT NULL";
        }
        
        //描述判断
        if(IsNull($fieldDescript)){
            $fieldDescript = "none";
        }
        
        //字段类型（到小写）
        $fieldTypeLower = strtolower($fieldType);
        
        //字段长度
        $fieldLength = $fieldTypeLower."(".$fieldLength.")";
        
        //默认值字符串组合
        $fieldDefaultStr = "";
        if(!IsNull($fieldDefault)){
            $fieldDefaultStr = " DEFAULT ".'"'.$fieldDefault.'"';
        }
        
        //提交添加字段
        $sql = "ALTER TABLE `".DBConfig::$dbName."`.`".$tableName."` ADD COLUMN `".$fieldNew."` " . $fieldLength . $fieldDefaultStr . " " . $fieldNotNullStr ." COMMENT '".$fieldDescript."' AFTER `". $fieldUp ."`;";
        
        //SQL调试
        if(!IsNull($sqlDebug)&&$sqlDebug=="true"){
            return JsonInforFalse($sql, "SQL调试");
        }
        
        //添加字段
        DBHelper::DataSubmit($sql, null);
        if(DBMySQLJudge::JudgeTableField($tableName, $fieldNew)){
            return JsonInforTrue($fieldNew."字段添加成功", $tableName);
        }
        return JsonInforFalse($fieldNew."字段添加失败", $tableName);
    }
    
    //字段删除
    public static function OperationDBTableFieldDelete($json){
        //--- 参数获取区 ---
        $tableName = GetParameter("table_name",$json);
        if(IsNull($tableName)){return JsonModelParameterNull("table_name");}
        $fieldDelete = GetParameter("field_delete",$json);
        if(IsNull($fieldDelete)){return JsonModelParameterNull("field_delete");}
        $sqlDebug = GetParameterDebug($json);
        
        //判断数据表是否存在
        if(!DBMySQLJudge::JudgeTable($tableName)){
            return JsonInforFalse("数据表不存在", $tableName);
        }
        
        //判断表字段是否存在
        if(!DBMySQLJudge::JudgeTableField($tableName, $fieldDelete)){
            return JsonInforFalse("表字段不存在", $tableName.":".$fieldDelete);
        }
        
        $sql = "ALTER TABLE ".DBConfig::$dbName.".".$tableName." DROP COLUMN `".$fieldDelete."`;";
        
        //SQL调试
        if(!IsNull($sqlDebug)&&$sqlDebug=="true"){
            return JsonInforFalse($sql, "SQL调试");
        }
        
        //删除字段
        DBHelper::DataSubmit($sql, null);
        if(!DBMySQLJudge::JudgeTableField($tableName, $fieldDelete)){
            return JsonInforTrue($fieldDelete."字段删除成功", $tableName);
        }
        return JsonInforFalse($fieldDelete."字段删除失败", $tableName);
        
    }
    
    //数据表字段修改
    public static function OperationDBTableFieldSet($json){
        
        //--- 参数获取区 ---
        $tableName = GetParameter("table_name",$json);
        if(IsNull($tableName)){return JsonModelParameterNull("table_name");}
        $fieldSet = GetParameter("field_set",$json);
        if(IsNull($fieldSet)){return JsonModelParameterNull("field_set");}
        $fieldNew = GetParameter("field_new",$json);
        if(IsNull($fieldNew)){return JsonModelParameterNull("field_new");}
        $fieldType = GetParameter("field_type",$json);
        if(IsNull($fieldType)){return JsonModelParameterNull("field_type");}
        $fieldLength = GetParameter("field_length",$json);
        if(IsNull($fieldLength)){return JsonModelParameterNull("field_length");}
        $fieldNotNull = GetParameter("field_not_null",$json);
        $fieldDefault = GetParameter("field_default",$json);
        $fieldDescript = GetParameter("field_descript",$json);
        $sqlDebug = GetParameterDebug($json);
        
        //判断数据表是否存在
        if(!DBMySQLJudge::JudgeTable($tableName)){
            return JsonInforFalse("数据表不存在", $tableName);
        }
        
        //判断表字段是否存在
        if(!DBMySQLJudge::JudgeTableField($tableName,$fieldSet)){
            return JsonInforFalse("表字段不存在", $tableName.":".$fieldSet);
        }
        
        //为空判断
        $fieldNotNullStr = "NULL";
        if(!IsNull($fieldNotNull)&&strtolower($fieldNotNull)=="true"){
            $fieldNotNullStr = "NOT NULL";
        }
        
        //描述判断
        if(IsNull($fieldDescript)){
            $fieldDescript = "none";
        }
                
        //字段类型（到小写）
        $fieldTypeLower = strtolower($fieldType);
        
        //字段长度
        $fieldLength = $fieldTypeLower."(".$fieldLength.")";
        
        //默认值字符串组合
        $fieldDefaultStr = "";
        if(!IsNull($fieldDefault)){
            $fieldDefaultStr = " DEFAULT ".'"'.$fieldDefault.'"';
        }
        
        //提交添加字段
        $sql = "ALTER TABLE `".DBConfig::$dbName."`.`".$tableName."` CHANGE `".$fieldSet."` `".$fieldNew."` ".$fieldLength." ".$fieldDefaultStr." ".$fieldNotNullStr . " COMMENT '".$fieldDescript."';";
        
        //SQL调试
        if(!IsNull($sqlDebug)&&$sqlDebug=="true"){
            return JsonInforFalse($sql, "SQL调试");
        }
        
        //修改字段
        DBHelper::DataSubmit($sql, null);
        if(DBMySQLJudge::JudgeTableField($tableName, $fieldNew)){
            return JsonInforTrue($fieldNew."字段修改成功", $tableName);
        }
        return JsonInforFalse($fieldNew."字段修改失败", $tableName);
    }
    
    
    //数据表字段:获取表字段数量
    public static function GetDBTableFieldNumber($json){
        
        //--- 参数获取区 ---
        $tableName = GetParameter("table_name",$json);
        if(IsNull($tableName)){return JsonModelParameterNull("table_name");}
        
        //--- 数据获取区 ---
        $sql = "SELECT COUNT(TRUE) number FROM information_schema.COLUMNS WHERE TABLE_SCHEMA='".DBConfig::$dbName."' AND table_name=?;";
        return JsonInforTrue(DBHelper::DataString($sql,array($tableName),"number"),$tableName."表字段数量");
    }
    
    //数据表字段:基础字段检测
    public static function OprationFieldBaseCheck($json){
        //--- 参数获取区 ---
        $tableName = GetParameterJson("table_name",$json);
        if(IsNull($tableName)){return JsonModelParameterNull("table_name");}
        //基础字段提交
        $sql = "ALTER TABLE `{$tableName}` ADD COLUMN `id` BIGINT(20) NOT NULL AUTO_INCREMENT COMMENT '表ID' FIRST, ADD PRIMARY KEY(`id`);";
        DBHelper::DataSubmit($sql, "");
        $sql = "ALTER TABLE `{$tableName}` ADD COLUMN `onlyKey` VARCHAR(36) NULL COMMENT '唯一Key' AFTER `id`;";
        DBHelper::DataSubmit($sql, "");
        $sql = "ALTER TABLE `{$tableName}` ADD COLUMN `descript` VARCHAR(36) NULL COMMENT '记录描述' AFTER `onlyKey`;";
        DBHelper::DataSubmit($sql, "");
        $sql = "ALTER TABLE `{$tableName}` ADD COLUMN `indexNumber` INT(11) DEFAULT '-1' NULL COMMENT '序号' AFTER `descript`;";
        DBHelper::DataSubmit($sql, "");
        $sql = "ALTER TABLE `{$tableName}` ADD COLUMN `updateTime` TIMESTAMP NULL COMMENT '修改时间' AFTER `indexNumber`;";
        DBHelper::DataSubmit($sql, "");
        $sql = "ALTER TABLE `{$tableName}` ADD COLUMN `addTime` TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL COMMENT '添加时间' AFTER `updateTime`;";
        DBHelper::DataSubmit($sql, "");
        $sql = "ALTER TABLE `{$tableName}` ADD COLUMN `shelfState` VARCHAR(36) DEFAULT 'true' NULL COMMENT '上下架状态' AFTER `addTime`;";
        DBHelper::DataSubmit($sql, "");
        $sql = "ALTER TABLE `{$tableName}` ADD COLUMN `state` VARCHAR(36) DEFAULT 'STATE_NORMAL' NULL COMMENT '记录状态' AFTER `shelfState`;";
        DBHelper::DataSubmit($sql, "");
        //返回结果
        return JsonInforTrue("数据表基础字段检测完毕", $tableName);
    }
    
    //数据表字段:表字段检测
    public static function OprationFieldCheck($fpTableName,$fpFieldArray){
        $vFieldArray = $fpFieldArray;
        //表字段判断
        $vTableField = DBMySQLSelect::TableFieldString($fpTableName);
        $vTableFieldArray = GetArray($vTableField, ",");
        $vFieldSub = "";
        for($i=0;$i<sizeof($vFieldArray);$i++){
            $thisFieldBo = false;
            $thisField = $vFieldArray[$i];
            $vFieldSub = $i;
            for($c=0;$c<sizeof($vTableFieldArray);$c++){
                if($vTableFieldArray[$c] == $thisField["field"]){
                    $thisFieldBo = true;
                    break;
                }
            }
            if(!$thisFieldBo){
                //--- 变量声明 ---
                //数据表
                $tableName = $fpTableName;
                //字段名
                $vThisField = $thisField["field"];
                //字段类型
                $vThisType = $thisField["type"];
                //默认值
                $vThisDefault = $thisField["default"];
                if(!IsNull($vThisDefault)){ $vThisDefault = "DEFAULT '".$vThisDefault."'"; }
                //是否为空
                $vThisNull = $thisField["null"];
                //描述
                $vThisComment = $thisField["comment"];
                //是否为主键
                $vThisKey = $thisField["key"];
                if(!IsNull($vThisKey)){ $vThisKey = ", ADD PRIMARY KEY(`".$vThisKey."`)"; }
                //自增长ID
                $vThisExtra = $thisField["extra"];
        
                //检测添加字段
                $sql = "";
                if($vFieldSub==0||sizeof($vTableFieldArray)==0){
                    $sql = "ALTER TABLE `{$tableName}` ADD COLUMN `{$vThisField}` {$vThisType} {$vThisDefault} {$vThisNull} {$vThisExtra} COMMENT '{$vThisComment}' FIRST, ADD PRIMARY KEY(`id`) {$vThisKey};";
                }else{
                    $vUpFieldInfor = $vFieldArray[$vFieldSub-1];
                    $vUpField = $vUpFieldInfor["field"];
                    $sql = "ALTER TABLE `{$tableName}` ADD COLUMN `{$vThisField}` {$vThisType} {$vThisDefault} {$vThisNull} {$vThisExtra} COMMENT '{$vThisComment}' AFTER `{$vUpField}` {$vThisKey} ;";
                }
                //提交SQL
                DBHelper::DataSubmit($sql, "");
            }
        }
        return JsonInforTrue("表字段检测完成", $fpTableName);
    }    
    
    //获取数据表字段
    public static function GetDBTableFields($json){
        
        //--- 参数获取区 ---
        $pTableName = GetParameter("table_name",$json);
        if(!JudgeRegularTable($pTableName)){return JsonModelParameterNull("table_name");}
        $pFieldName = GetParameter("field_name",$json);
        if(!IsNull($pFieldName)&&!JudgeRegularField($pFieldName)){return JsonParameterWrong("field_name", $pFieldName);}
        if(!IsNull($pFieldName)){ $pFieldName = " WHERE Field='{$pFieldName}' ";}
        
        $pModel = GetParameter("model",$json);
        
        //--- 数据获取区 ---
        $sql = "SHOW FULL COLUMNS FROM ".$pTableName."{$pFieldName};";
        $fieldsArray = array("Field","Type","Collation","Null","Key","Default","Extra","Comment");
        //简化模式
        if($pModel=="FIELD"){ $fieldsArray = array("Field"); }
        //数据获取
        $data = DBHelper::DataList($sql,null,$fieldsArray);
        return JsonModelSelectDataHave($pTableName,"数据表字段", sizeof(json_decode($data)), $data);
    }
    
    //获取所有数据表
    public static function GetDBTables($json){
        //--- 参数获取区 ---
        $like = GetParameter("like",$json);
        $liketype = GetParameter("like_type",$json);
        $orderby = GetParameter("orderby",$json);
        $desc = GetParameter("desc",$json);
        
        $sqlLike = "";
        $like = addslashes($like);
        if(IsNull($liketype)||$liketype=="tablename"){
            if(!IsNull($like)){$sqlLike = " AND table_name LIKE '%".$like."%'";}
        }else if($liketype=="tablefield"){
            if(!IsNull($like)){$sqlLike = " AND column_name LIKE '%".$like."%'";}
        }else if($liketype=="tablefielddescript"){
            if(!IsNull($like)){$sqlLike = " AND column_comment LIKE '%".$like."%'";}
        }
        
        $sqlOrderby = "";
        if(JudgeRegularField($orderby)){
            if(!IsNull($desc)&&$desc=="true"){
                $sqlOrderby = " ORDER BY ".$orderby." DESC";
            }else{
                $sqlOrderby = " ORDER BY ".$orderby;
            }
        }
        
        //--- 逻辑区 ---
        $dbName = DBConfig::$dbName;
        $sql = "";
        if(IsNull($liketype)||$liketype=="tablename"){
            $sql = "SELECT table_name tablename,CREATE_TIME createTime,TABLE_ROWS recodeNumber,AUTO_INCREMENT autoId,TABLE_COMMENT tableComment FROM information_schema.TABLES WHERE table_schema = \"".$dbName."\" ".$sqlLike.$sqlOrderby.";";
        }else{
            $sql = "SELECT table_name tablename,CREATE_TIME createTime,TABLE_ROWS recodeNumber,AUTO_INCREMENT autoId,TABLE_COMMENT tableComment FROM information_schema.COLUMNS WHERE table_schema = \"".$dbName."\" ".$sqlLike." GROUP BY table_name ".$sqlOrderby.";";
        }
        $data = DBHelper::DataList($sql, null, array("tablename","createTime","recodeNumber","autoId","tableComment"));
        return JsonModelSelectDataHave("数据表",$dbName,sizeof(json_decode($data)), $data);
    }
     
    
    //操作:数据表删除
    public static function OperationDBTableDelete($json){
        
        //--- 参数获取区 ---
        $tableName = GetParameter("table_name",$json);
        if(IsNull($tableName)){return JsonModelParameterNull("table_name");}
        
        //--- 流程处理区 ---
        //删除数据表
        DBMySQLDelete::DropTable($tableName);
        //查询数据表是否存在
        if(!DBMySQLJudge::JudgeTable($tableName)){
            return JsonInforTrue("数据表删除成功", $tableName);
        }
        return JsonInforFalse("数据表删除失败", $tableName);
    }
    
    
    //操作:数据表自增长ID修改
    public static function OperationDBTableAutoIncrementSet($json){
        //--- 参数获取区 ---
        $tableName = GetParameter("table_name",$json);
        if(IsNull($tableName)){return JsonModelParameterNull("table_name");}
        $autoNumber = GetParameter("auto_number",$json);
        if(IsNull($autoNumber)){return JsonModelParameterNull("auto_number");}
    
        //SQL语句
        $sql = "ALTER TABLE {$tableName} AUTO_INCREMENT={$autoNumber};";
    
        //提交SQL
        DBHelper::DataSubmit($sql, null);
    
        //查询SQL
        $querySql = "SHOW TABLE STATUS WHERE NAME = '{$tableName}';";
        $tableAutoId = DBHelper::DataString($querySql,null,"Auto_increment");
        if($tableAutoId==$autoNumber){
            return JsonInforTrue("修改成功", $tableName);
        }
        return JsonInforFalse("修改失败", $tableName);
    }
    
    
    //获取:数据表自增长ID修改
    public static function GetDBTableAutoIncrement($json){
        //--- 参数获取区 ---
        $tableName = GetParameter("table_name",$json);
        if(!JudgeRegularTable($tableName)){return JsonModelParameterNull("table_name");}
        
        //查询SQL
        $querySql = "SHOW TABLE STATUS WHERE NAME = '{$tableName}';";
        $tableAutoId = DBHelper::DataString($querySql,null,"Auto_increment");
        if(IsNull($tableAutoId)){
            return JsonInforFalse("没有数据", $tableName);
        }
        return JsonInforTrue($tableAutoId, $tableName);
    }
    
    
    //获取:数据表创建语句
    public static function GetDBTableCreateSql($json){
        //--- 参数获取区 ---
        $tableName = GetParameter("table_name",$json);
        if(!JudgeRegularTable($tableName)){return JsonModelParameterNull("table_name");}
    
        //查询SQL
        $sql = "SHOW CREATE TABLE {$tableName};";
        $vTableCreateSql = DBHelper::DataString($sql,null,"Create Table");
        if(IsNull($vTableCreateSql)){
            return JsonInforFalse("没有数据", $tableName);
        }
        return $vTableCreateSql;
    }
    
    //获取数据库数据表数量
    public static function GetDBTableNumber(){
        $sql = "SELECT COUNT(table_name) number FROM information_schema.TABLES WHERE table_schema = \"".DBConfig::$dbName."\";";
        return JsonInforTrue(DBHelper::DataString($sql,null,"number"),DBConfig::$dbName."数据库数据表数量");
    }
       
    //修改数据表名称
    public static function OperationDBTableNameSet($json){
        //--- 参数获取区 ---
        $tableName = GetParameter("table_name",$json);
        if(IsNull($tableName)){return JsonModelParameterNull("table_name");}
        $newTableName = GetParameter("new_table_name",$json);
        if(IsNull($newTableName)){return JsonModelParameterNull("new_table_name");}
        
        //SQL语句
        $sql = "RENAME TABLE `".$tableName."` TO `".$newTableName."`;";
        
        //提交SQL
        DBHelper::DataSubmit($sql, null);
        if(DBMySQLJudge::JudgeTable($newTableName)){
            return JsonInforTrue("修改成功", $newTableName); 
        }
        return JsonInforFalse("修改失败", $newTableName);
    }
    
    //修改数据表描述
    public static function OperationDBTableCommentSet($json){
        //--- 参数获取区 ---
        $tableName = GetParameterNoCode("table_name",$json);
        if(IsNull($tableName)){return JsonModelParameterNull("table_name");}
        $fComment = GetParameterNoCode("comment",$json);
        if(IsNull($fComment)){return JsonModelParameterNull("comment");}
    
        //SQL语句
        $sql = "ALTER TABLE {$tableName} COMMENT '{$fComment}';";
    
        //提交SQL
        DBHelper::DataSubmit($sql, null);
        return JsonInforTrue("修改成功", $tableName);
    }    
    
    //创建数据表
    public static function OprationDBTableCreateSubmit($json){
    
        //--- 参数获取区 ---
    
        //数据表名称
        $pTablename = GetParameter("table_name",$json);
        if(IsNull($pTablename)){return JsonModelParameterNull("table_name");}
    
        //创建语句
        $pCreateSql = GetParameterNoCode("createsql",$json);
        if(IsNull($pCreateSql)){return JsonModelParameterNull("createsql");}
        $pCreateSql = HandleStringFlyHtmlDecode($pCreateSql);
        
        //判断数据表是否存在
        $sql = "SELECT table_name FROM information_schema.TABLES WHERE table_name=?;";
        $dbTableName = DBHelper::DataString($sql, [$pTablename], "table_name");
        if(!IsNull($dbTableName)){
            return JsonInforFalse("数据表已存在", $pTablename);
        }
    
        //提交SQL语句:创建数据表
        DBHelper::DataSubmit($pCreateSql, null);
        //判断数据表
        if(DBMySQLJudge::JudgeTable($pTablename)){
            return JsonInforTrue("创建成功", $pTablename);
        }
        return JsonInforFalse("创建失败", $pTablename);
    
    }    
    
    //数据库名称
    public static function GetDBName(){
        return JsonInforTrue(DBConfig::$dbName,"数据库名称");
    }
    
    //获取数据库链接用户名
    public static function GetDBUser(){
        return JsonInforTrue(DBConfig::$dbUser,"MySQL用户名");
    }
    
    //获取数据库链接密码
    public static function GetDBPassword() {
        return JsonInforTrue(DBConfig::$dbPassWord,"MySQL密码");
    }
    
    //获取数据库版本
    public static function GetDBVersion(){
        $sql = "SELECT VERSION() a;";
        $version = DBHelper::DataString($sql,null,"a");
        return JsonInforTrue($version,"MySQL版本");
    }
    
    //获取数据库信息
    public static function GetDBInfor(){
        
        $jsonStr = "";
        
        //数据库版本
        $version = DBHelper::DataString("SELECT VERSION() a;",null,"a");
        $jsonStr .= JsonKeyValue("dbversion",$version);
        //数据库名称
        $jsonStr .= "," . JsonKeyValue("dbname",DBConfig::$dbName);
        //数据库用户名
        $jsonStr .= "," . JsonKeyValue("dbuser",DBConfig::$dbUser);
        //数据库密码
        //$jsonStr .= "," . JsonKeyValue("dbpassword",DBConfig::$dbPassWord);
        //数据库数据表数量
        $sql = "SELECT COUNT(table_name) number FROM information_schema.TABLES WHERE table_schema = \"".DBConfig::$dbName."\";";
        $jsonStr .= "," . JsonKeyValue("dbtablenumber",DBHelper::DataString($sql,null,"number"));
        
        //组合Json对象
        $jsonStr = JsonArray(JsonObj($jsonStr));
        
        return JsonModelSelectDataHave("数据库信息",DBConfig::$dbName,1,$jsonStr);
        
    }
    
    //获取数据库变量信息
    public static function GetDBVariable(){
        $sql = "SHOW VARIABLES;";
        $data = DBHelper::DataList($sql, null, array("Variable_name","Value"),true);
        return JsonModelSelectDataHave("数据库变量信息","MySQL",sizeof(GetJsonObject($data)), $data);
    }
    
    
    //--- 云服务:表库 ---
    
    //操作:数据表表库表数量
    public static function GetTBNumber($json){
        //--- 参数获取区 ---
        global $disanyunServiceUrl;
        $url = $disanyunServiceUrl."type=token&line=tablebase&method=tbnumber";
        $result = GetHttp($url);
        //$jsonResult = GetJsonValue($result, "result");
        return $result;
    }
    
    
    //操作:数据表加载
    public static function GetTBNames($json){
        //获取在线表库
        global $disanyunServiceUrl;
        $url = $disanyunServiceUrl."type=token&line=tablebase&method=tbnames";
        $result = GetHttp($url);
        $resultJson = GetJsonObject($result);
        $jResult = $resultJson->result;
        $jCode = $resultJson->code;
        $jFcode = $resultJson->fcode;
        $jMsg = $resultJson->msg;
        $jCount = $resultJson->count;
        $jVersion = $resultJson->version;
        $jDataname = $resultJson->dataname;
        $jData = $resultJson->data;
        //获取本地数据表
        $jsonResult = "";
        $tables = DBMySQLServiceJson::GetDBTables("");
        $tablesData = GetJsonValue($tables, "data");
        if(!IsNull($tablesData)){
            $jDataTableName = "";
            $dataTableName = "";
            foreach($jData as $jKey){
                $jLoad = "false";
                $jDataTableName = $jKey->tablename;
                foreach($tablesData as $key){
                   $dataTableName = $key->tablename;
                   if($jDataTableName==$dataTableName){
                       $jLoad = "true";
                       break;
                   }
               }
               $jsonResult .= JsonKeyValueThreeObj("tablename", $jDataTableName, "load", $jLoad, "descript", $jKey->descript).",";
            }
            $jsonResult = HandleStringDeleteLast($jsonResult);
            $jsonResult = JsonObj(JsonKeyValue("result", $jResult).",".JsonKeyValue("code", $jCode).",".JsonKeyValue("fcode", $jFcode).",".JsonKeyValue("msg", $jMsg).",".JsonKeyValue("count", $jCount).",".JsonKeyValue("version", $jVersion).",".JsonKeyValue("dataname", $jDataname).",".JsonKeyArray("data", $jsonResult));
            return $jsonResult;
        }else{
            $result = HandleStringReplace($result, '"load":"true",', '"load":"false",');
            return $result;
        }
    }
    
    //操作:数据表加载
    public static function OperationTBLoad($json){
        //--- 参数获取区 ---
        $tableName = GetParameter("table_name",$json);
        if(IsNull($tableName)){return JsonModelParameterNull("table_name");}
        //判断数据表是否已经加载
        if(DBMySQLJudge::JudgeTable($tableName)){
            return JsonInforFalse("数据表已加载", $tableName);
        }
        //加载数据表
        global $disanyunServiceUrl;
        $url = $disanyunServiceUrl."type=token&line=tablebase&method=tbload&tablename=".$tableName;
        $result = GetHttp($url);
        $jsonResult = GetJsonValue($result, "result");
        if($jsonResult=="true"){
            $sql = GetJsonValue($result, "infor");
            DBHelper::DataSubmit($sql, null);   //提交SQL语句
            if(DBMySQLJudge::JudgeTable($tableName)){
                return JsonInforTrue("加载成功", $tableName);
            }else{
                return JsonInforFalse("加载失败", $tableName);
            }
        }else{
            return $result;
        }
    }
    
    //操作:数据表加载
    public static function OperationTBCopy($json){
        //--- 参数获取区 ---
        //参数:数据表名称
        $tablename = GetParameter("table_name",$json);
        if(IsNull($tablename)){return JsonModelParameterNull("table_name");}
        //参数:新数据表名称
        $copyname = GetParameter("copy_name",$json);
        if(IsNull($copyname)){return JsonModelParameterNull("copy_name");}
        
        //数据请求
        global $disanyunServiceUrl;
        $url = $disanyunServiceUrl."type=token&line=tablebase&method=tbcopy&tablename={$tablename}&copyname={$copyname}";
        $result = GetHttp($url);
        $jsonResult = GetJsonValue($result, "result");
        if($jsonResult=="true"){
            $sql = GetJsonValue($result, "infor");
            DBHelper::DataSubmit($sql, null);   //提交SQL语句
            if(DBMySQLJudge::JudgeTable($copyname)){
                return JsonInforTrue("加载成功", $copyname);
            }else{
                return JsonInforFalse("加载失败", $copyname);
            }
        }else{
            return $result;
        }
        
    }
    
    
    
    
    
    
}

