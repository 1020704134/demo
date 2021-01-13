<?php

/**------------------------------------*
 * 作者：shark
 * 创建时间：2020-01-07 23:27:29
 * Fly编码：1578410849860FLY412119
 * 类对象名：ObjFlyInterfaceParam()
 * ------------------------------------ */

//引入区

class FlyClassInterfaceParam{
    
    
    //---------- 类成员（member） ----------
    
    //类描述
    public static $classDescript = "接口参数";
    //类数据表名
    public static $tableName = "fly_interface_param";
    //类数据表字段
    public static $tableField = "id,onlyKey,descript,indexNumber,updateTime,addTime,shelfState,state,interfaceId,parameterName,parameterValue,parameterType,parameterMust,parameterDescript,parameterDemo";
    
    
    //---------- 私有方法（private） ----------
    
    //---------- 游客方法（visitor） ----------
    
    /**
     * 函数名称：接口参数:游客:记录查询
     * 函数调用：ObjFlyInterfaceParam() -> VisitorFlyInterfaceParamPaging()
     * 创建时间：2020-01-07 23:27:29
     * */
    public function VisitorFlyInterfaceParamPaging(){
        
        //--- 参数限制区 ---
        $vParameterArray = GetArray(FLY_PARAMETER_LIMIT, ",");
        $bParameterJudge = JudgeParameterLimit($vParameterArray);
        if(JudgeJsonFalse($bParameterJudge)){return $bParameterJudge;}
        
        //--- 数据预定义 ---
        $json = "";
        
        //--- 参数获取区 ---
        $pPage = GetParameter("page","");     //参数:页码:page
        $pLimit = GetParameter("limit","");   //参数:条数:limit
        
        //参数：id
        $pId = GetParameter("id","");
        if(!IsNull($pId)&&!JudgeRegularIntRight($pId)){return JsonModelParameterException("id", $pId, 20, "值必须是正整数", __LINE__);}
        if(!IsNull($pId)){ $pPage = 1; $pLimit = 1; }
        
        //参数判断:页码:page
        if(IsNull($pPage)||!JudgeRegularIntRight($pPage)){return JsonModelParameterException("page", $pPage, 9, "值必须是正整数", __LINE__);}
        //参数判断:条数:limit
        if(IsNull($pLimit)||!JudgeRegularIntRight($pLimit)){return JsonModelParameterException("limit", $pLimit, 9, "值必须是正整数", __LINE__);}
        
        //参数：记录状态
        $pWhereState = GetParameter("wherestate","");
        if(!IsNull($pWhereState)&&!($pWhereState=="STATE_NORMAL"||$pWhereState=="STATE_DELETE")){return JsonModelParameterException("wherestate", $pWhereState, 64, "值必须是STATE_NORMAL/STATE_DELETE", __LINE__);}
        
        //参数：上下架状态
        $pWhereShelfState = GetParameter("whereshelfstate","");
        if(!IsNull($pWhereShelfState)&&!($pWhereShelfState=="true"||$pWhereShelfState=="false")){return JsonModelParameterException("whereshelfstate", $pWhereShelfState, 36, "值必须是true/false", __LINE__);}
        
        //参数：like
        $pLikeField = GetParameter("like_field","");
        if(!IsNull($pLikeField)&&!DBMySQLJudge::JudgeTableField(self::$tableName,$pLikeField)){ return JsonInforFalse("搜索字段不存在", $pLikeField); }
        $pLikeKey = GetParameter("like_key","");
        if(!IsNull($pLikeKey)){ $pLikeKey = HandleStringAddslashes($pLikeKey); }
        
        //参数：state
        $pStateField = GetParameter("statefield","");
        if(!IsNull($pStateField)&&!DBMySQLJudge::JudgeTableField(self::$tableName,$pStateField)){ return JsonInforFalse("状态字段不存在", $pStateField); }
        $pStateKey = GetParameter("statekey","");
        if(!IsNull($pStateKey)&&!JudgeRegularState($pStateKey)){ return JsonInforFalse("状态码错误", $pStateKey); }
        
        //字段定义
        $vDataField = DBMySQLSelect::TableFieldString(self::$tableName);
        
        //--- Json组合区 ---
        $jsonKeyValueArray = array(
            "table_name" => self::$tableName,
            "data_field" => $vDataField,
            "where_field" => "shelfState",
            "where_value" => "true",
            "page" => $pPage,
            "limit" => $pLimit,
            "orderby" => "id:desc",
            "like_field" => $pLikeField,
            "like_key" => $pLikeKey,
        );
        
        //条件字段判断组合
        $jsonKeyValueArray = HandleFlyJsonAddWhereField($jsonKeyValueArray, "id", $pId);
        $jsonKeyValueArray = HandleFlyJsonAddWhereField($jsonKeyValueArray, "state", $pWhereState);
        $jsonKeyValueArray = HandleFlyJsonAddWhereField($jsonKeyValueArray, "shelfState", $pWhereShelfState);
        $jsonKeyValueArray = HandleFlyJsonAddWhereField($jsonKeyValueArray, $pStateField, $pStateKey);
        //返回结果:分页
        return MIndexDataPaging(JsonHandleArray($jsonKeyValueArray));
        
    }
    
    
    //---------- 系统方法（system） ----------
    
    /**
     * 函数名称：接口参数:系统:记录添加
     * 函数调用：ObjFlyInterfaceParam() -> SystemFlyInterfaceParamAdd
     * 创建时间：2020-01-07 23:27:29
     * */
    public function SystemFlyInterfaceParamAdd($fpInterfaceId,$fpParameterName,$fpParameterValue,$fpParameterType,$fpParameterMust,$fpParameterDescript,$fpParameterDemo){
        
        //--- Json组合区 ---
        //Json数组
        $jsonKeyValueArray = array(
            "table_name" => self::$tableName,
            "insert_field" => "descript,shelfState,interfaceId,parameterName,parameterValue,parameterType,parameterMust,parameterDescript,parameterDemo",
            "descript" => self::$classDescript,
            "shelfstate" => "false",
            "interfaceid" => $fpInterfaceId,
            "parametername" => $fpParameterName,
            "parametervalue" => $fpParameterValue,
            "parametertype" => $fpParameterType,
            "parametermust" => $fpParameterMust,
            "parameterdescript" => $fpParameterDescript,
            "parameterdemo" => $fpParameterDemo,
        );
        //执行:添加
        $vJsonResult = MIndexDataInsert(JsonHandleArray($jsonKeyValueArray));
        return $vJsonResult;
    }
    
    
    //---------- 用户方法（user） ----------
    
    /**
     * 函数名称：接口参数:用户:记录查询
     * 函数调用：ObjFlyInterfaceParam() -> UserFlyInterfaceParamPaging($fpUserId)
     * 创建时间：2020-01-07 23:27:29
     * */
    public function UserFlyInterfaceParamPaging($fpUserId){
        
        //--- 参数限制区 ---
        $vParameterArray = GetArray(FLY_PARAMETER_LIMIT, ",");
        $bParameterJudge = JudgeParameterLimit($vParameterArray);
        if(JudgeJsonFalse($bParameterJudge)){return $bParameterJudge;}
        
        //--- 数据预定义 ---
        $json = "";
        
        //--- 参数获取区 ---
        $pPage = GetParameter("page","");     //参数:页码:page
        $pLimit = GetParameter("limit","");   //参数:条数:limit
        
        //参数：id
        $pId = GetParameter("id","");
        if(!IsNull($pId)&&!JudgeRegularIntRight($pId)){return JsonModelParameterException("id", $pId, 20, "值必须是正整数", __LINE__);}
        if(!IsNull($pId)){ $pPage = 1; $pLimit = 1; }
        
        //参数判断:页码:page
        if(IsNull($pPage)||!JudgeRegularIntRight($pPage)){return JsonModelParameterException("page", $pPage, 9, "值必须是正整数", __LINE__);}
        //参数判断:条数:limit
        if(IsNull($pLimit)||!JudgeRegularIntRight($pLimit)){return JsonModelParameterException("limit", $pLimit, 9, "值必须是正整数", __LINE__);}
        
        //参数：记录状态
        $pWhereState = GetParameter("wherestate","");
        if(!IsNull($pWhereState)&&!($pWhereState=="STATE_NORMAL"||$pWhereState=="STATE_DELETE")){return JsonModelParameterException("wherestate", $pWhereState, 64, "值必须是STATE_NORMAL/STATE_DELETE", __LINE__);}
        
        //参数：上下架状态
        $pWhereShelfState = GetParameter("whereshelfstate","");
        if(!IsNull($pWhereShelfState)&&!($pWhereShelfState=="true"||$pWhereShelfState=="false")){return JsonModelParameterException("whereshelfstate", $pWhereShelfState, 36, "值必须是true/false", __LINE__);}
        
        //参数：like
        $pLikeField = GetParameter("like_field","");
        if(!IsNull($pLikeField)&&!DBMySQLJudge::JudgeTableField(self::$tableName,$pLikeField)){ return JsonInforFalse("搜索字段不存在", $pLikeField); }
        $pLikeKey = GetParameter("like_key","");
        if(!IsNull($pLikeKey)){ $pLikeKey = HandleStringAddslashes($pLikeKey); }
        
        //参数：state
        $pStateField = GetParameter("state_field","");
        if(!IsNull($pStateField)&&!DBMySQLJudge::JudgeTableField(self::$tableName,$pStateField)){ return JsonInforFalse("状态字段不存在", $pStateField); }
        $pStateKey = GetParameter("state_key","");
        if(!IsNull($pStateKey)&&!JudgeRegularState($pStateKey)){ return JsonInforFalse("状态码错误", $pStateKey); }
        
        //字段定义
        $vDataField = DBMySQLSelect::TableFieldString(self::$tableName);
        
        //--- Json组合区 ---
        $jsonKeyValueArray = array(
            "table_name" => self::$tableName,
            "data_field" => $vDataField,
            "where_field" => "userId",
            "where_value" => "{$fpUserId}",
            "page" => $pPage,
            "limit" => $pLimit,
            "orderby" => "id:desc",
            "like_field" => $pLikeField,
            "like_key" => $pLikeKey,
        );
        
        //条件字段判断组合
        $jsonKeyValueArray = HandleFlyJsonAddWhereField($jsonKeyValueArray, "id", $pId);
        $jsonKeyValueArray = HandleFlyJsonAddWhereField($jsonKeyValueArray, "state", $pWhereState);
        $jsonKeyValueArray = HandleFlyJsonAddWhereField($jsonKeyValueArray, "shelfState", $pWhereShelfState);
        $jsonKeyValueArray = HandleFlyJsonAddWhereField($jsonKeyValueArray, $pStateField, $pStateKey);
        //返回结果:分页
        return MIndexDataPaging(JsonHandleArray($jsonKeyValueArray));
        
    }
    
    
    //---------- 管理员方法（admin） ----------
    
    /**
     * 函数名称：接口参数:管理员:升序
     * 函数调用：ObjFlyInterfaceParam() -> AdminFlyInterfaceParamIndex($fpAdminId)
     * 创建时间：2020-01-10 10:48:32
     * */
    public function AdminFlyInterfaceParamIndex($fpAdminId){
        
        //--- 变量预定义 ---
        $json="";
        
        //--- 参数获取区 ---
        
        //参数:记录ID:记录ID一
        $pIdOne = GetParameter("idone",$json);
        if(!JudgeRegularIntRight($pIdOne)){return JsonModelParameterException("idone", $pIdOne, 11, "值必须是正整数", __LINE__);}
        
        //参数:记录ID:记录ID二
        $pIdTwo = GetParameter("idtwo",$json);
        if(!JudgeRegularIntRight($pIdTwo)){return JsonModelParameterException("idtwo", $pIdTwo, 11, "值必须是正整数", __LINE__);}
        
        //获取:fly_interface_param:表ID
        $vSql = "SELECT indexNumber FROM fly_interface_param WHERE id=? LIMIT 0,1;";
        $vIndexNumberOne = DBHelper::DataString($vSql, [$pIdOne]);
        if(IsNull($vIndexNumberOne)){ return JsonInforFalse("记录ID一不存在", self::$tableName); }
        if($vIndexNumberOne == "-1" || IsNull($vIndexNumberOne)){ $vIndexNumberOne = $pIdOne; }
        
        //获取:fly_interface_param:表ID
        $vSql = "SELECT indexNumber FROM fly_interface_param WHERE id=? LIMIT 0,1;";
        $vIndexNumberTwo = DBHelper::DataString($vSql, [$pIdTwo]);
        if(IsNull($vIndexNumberTwo)){ return JsonInforFalse("记录ID二不存在", self::$tableName); }
        if($vIndexNumberTwo == "-1" || IsNull($vIndexNumberTwo)){ $vIndexNumberTwo = $pIdTwo; }
        
        //修改:fly_interface_param:序号
        $vSql = "UPDATE fly_interface_param SET indexNumber=? WHERE id=?;";
        $vUpdateResult = DBHelper::DataSubmit($vSql, [$vIndexNumberTwo,$pIdOne]);
        if(!$vUpdateResult){ return JsonInforFalse("记录ID一修改失败", self::$tableName); }
        
        //修改:fly_interface_param:序号
        $vSql = "UPDATE fly_interface_param SET indexNumber=? WHERE id=?;";
        $vUpdateResult = DBHelper::DataSubmit($vSql, [$vIndexNumberOne,$pIdTwo]);
        if(!$vUpdateResult){ return JsonInforFalse("记录ID二修改失败", self::$tableName); }
        
        return JsonInforTrue("{$pIdOne} > {$pIdTwo} 序号修改成功", self::$tableName);
    }
    
    /**
     * 函数名称：接口参数:管理员:记录添加
     * 函数调用：ObjFlyInterfaceParam() -> AdminFlyInterfaceParamAdd($fpAdminId)
     * 创建时间：2020-01-07 23:27:29
     * */
    public function AdminFlyInterfaceParamAdd($fpAdminId){
        
        
        //--- 参数限制区 ---
        $vParameterArray = GetArray(FLY_PARAMETER_LIMIT, ",");
        $bParameterJudge = JudgeParameterLimit($vParameterArray);
        if(JudgeJsonFalse($bParameterJudge)){return $bParameterJudge;}
        
        //--- 变量预定义 ---
        $json="";
        
        //--- 参数获取区 ---
        
        //参数:记录描述:descript
        $pDescript = self::$classDescript;
        
        //参数:上下架状态:shelfState
        $pShelfState = "true";
        
        //参数:接口ID:interfaceId
        $pInterfaceId = GetParameter("interfaceid",$json);
        if(!JudgeRegularInt($pInterfaceId)){return JsonModelParameterException("interfaceid", $pInterfaceId, 11, "值必须是整数", __LINE__);}
        
        //参数:参数名称:parameterName
        $pParameterName = GetParameter("parametername",$json);
        if(!JudgeRegularFont($pParameterName)){return JsonModelParameterException("parametername", $pParameterName, 64, "内容格式错误", __LINE__);}
        
        //参数:参数值:parameterValue
        $pParameterValue = GetParameter("parametervalue",$json);
        if(!JudgeRegularFont($pParameterValue)){return JsonModelParameterException("parametervalue", $pParameterValue, 64, "内容格式错误", __LINE__);}
        
        //参数:参数值类型:parameterType
        $pParameterType = GetParameter("parametertype",$json);
        if(!JudgeRegularFont($pParameterType)){return JsonModelParameterException("parametertype", $pParameterType, 36, "内容格式错误", __LINE__);}
        
        //参数:参数是否必填:parameterMust
        $pParameterMust = GetParameter("parametermust",$json);
        if(!JudgeRegularFont($pParameterMust)){return JsonModelParameterException("parametermust", $pParameterMust, 36, "内容格式错误", __LINE__);}
        
        //参数:参数说明:parameterDescript
        $pParameterDescript = GetParameter("parameterdescript",$json);
        if(!JudgeRegularFont($pParameterDescript)){return JsonModelParameterException("parameterdescript", $pParameterDescript, 64, "内容格式错误", __LINE__);}
        
        //参数:参数例程:parameterDemo
        $pParameterDemo = GetParameter("parameterdemo",$json);
        if(!JudgeRegularFont($pParameterDemo)){return JsonModelParameterException("parameterdemo", $pParameterDemo, 64, "内容格式错误", __LINE__);}
        
        //--- Json组合区 ---
        //Json数组
        $jsonKeyValueArray = array(
            "table_name" => self::$tableName,
            "insert_field" => "descript,shelfState,interfaceId,parameterName,parameterValue,parameterType,parameterMust,parameterDescript,parameterDemo",
            "key_field" => "interfaceId,parameterName",
            "descript" => self::$classDescript,
            "shelfstate" => $pShelfState,
            "interfaceid" => $pInterfaceId,
            "parametername" => $pParameterName,
            "parametervalue" => $pParameterValue,
            "parametertype" => $pParameterType,
            "parametermust" => $pParameterMust,
            "parameterdescript" => $pParameterDescript,            
            "parameterdemo" => $pParameterDemo,
        );
        //执行:添加
        $vJsonResult = MIndexDataInsert(JsonHandleArray($jsonKeyValueArray));
        return $vJsonResult;
    }
    
    
    /**
     * 函数名称：接口参数:管理员:记录查询
     * 函数调用：ObjFlyInterfaceParam() -> AdminFlyInterfaceParamPaging($fpAdminId)
     * 创建时间：2020-01-07 23:27:29
     * */
    public function AdminFlyInterfaceParamPaging($fpAdminId){
        
        //--- 参数限制区 ---
        $vParameterArray = GetArray(FLY_PARAMETER_LIMIT, ",");
        $bParameterJudge = JudgeParameterLimit($vParameterArray);
        if(JudgeJsonFalse($bParameterJudge)){return $bParameterJudge;}
        
        //--- 数据预定义 ---
        $json = "";
        
        //--- 参数获取区 ---
        //参数:接口路径:interfacePath
        $pInterfacePath = GetParameter("interfacepath",$json);
        if(!JudgeRegularUrl($pInterfacePath)){return JsonModelParameterException("interfacepath", $pInterfacePath, 128, "内容格式错误", __LINE__);}
        
        //参数:接口索引业务线:interfaceIndexLine
        $pInterfaceIndexLine = GetParameter("interfaceindexline");
        if(!JudgeRegularLetterNumber($pInterfaceIndexLine)){return JsonModelParameterException("interfaceindexline", $pInterfaceIndexLine, 128, "内容格式错误", __LINE__);}
        
        //参数:接口索引业务线方法:interfaceIndexMethod
        $pInterfaceIndexMethod = GetParameter("interfaceindexmethod");
        if(!JudgeRegularLetterNumber($pInterfaceIndexMethod)){return JsonModelParameterException("interfaceindexmethod", $pInterfaceIndexMethod, 128, "内容格式错误", __LINE__);}
        
        //获取:fly_interface:表ID
        $vSql = "SELECT id FROM fly_interface WHERE interfacePath=? AND interfaceIndexLine=? AND interfaceIndexMethod=? LIMIT 0,1;";
        $vInterfaceId = DBHelper::DataString($vSql, [$pInterfacePath,$pInterfaceIndexLine,$pInterfaceIndexMethod]);
        if(IsNull($vInterfaceId)){ return JsonInforFalse("接口不存在", "fly_interface"); }
        
        $pPage = "1";     //参数:页码:page
        $pLimit = "1000";   //参数:条数:limit
        
        //参数：id
        $pId = GetParameter("id","");
        if(!IsNull($pId)&&!JudgeRegularIntRight($pId)){return JsonModelParameterException("id", $pId, 20, "值必须是正整数");}
        if(!IsNull($pId)){ $pPage = 1; $pLimit = 1; }
        
        //参数判断:页码:page
        if(IsNull($pPage)||!JudgeRegularIntRight($pPage)){return JsonModelParameterException("page", $pPage, 9, "值必须是正整数", __LINE__);}
        //参数判断:条数:limit
        if(IsNull($pLimit)||!JudgeRegularIntRight($pLimit)){return JsonModelParameterException("limit", $pLimit, 9, "值必须是正整数", __LINE__);}
        
        //参数：记录状态
        $pWhereState = GetParameter("wherestate","");
        if(!IsNull($pWhereState)&&!($pWhereState=="STATE_NORMAL"||$pWhereState=="STATE_DELETE")){return JsonModelParameterException("wherestate", $pWhereState, 64, "值必须是STATE_NORMAL/STATE_DELETE", __LINE__);}
        
        //参数：上下架状态
        $pWhereShelfState = GetParameter("whereshelfstate","");
        if(!IsNull($pWhereShelfState)&&!($pWhereShelfState=="true"||$pWhereShelfState=="false")){return JsonModelParameterException("whereshelfstate", $pWhereShelfState, 36, "值必须是true/false", __LINE__);}
        
        //参数：like
        $pLikeField = GetParameter("like_field","");
        if(!IsNull($pLikeField)&&!DBMySQLJudge::JudgeTableField(self::$tableName,$pLikeField)){ return JsonInforFalse("搜索字段不存在", $pLikeField); }
        $pLikeKey = GetParameter("like_key","");
        if(!IsNull($pLikeKey)){ $pLikeKey = HandleStringAddslashes($pLikeKey); }
        
        //参数：state
        $pStateField = GetParameter("state_field","");
        if(!IsNull($pStateField)&&!DBMySQLJudge::JudgeTableField(self::$tableName,$pStateField)){ return JsonInforFalse("状态字段不存在", $pStateField); }
        $pStateKey = GetParameter("state_key","");
        if(!IsNull($pStateKey)&&!JudgeRegularState($pStateKey)){ return JsonInforFalse("状态码错误", $pStateKey); }
        
        //字段定义
        $vDataField = DBMySQLSelect::TableFieldString(self::$tableName);
        //$vDataField = "id,onlyKey,descript,indexNumber,updateTime,addTime,shelfState,state,interfaceId,parameterName,parameterValue,parameterType,parameterMust,parameterDescript,parameterDemo";
        
        //--- Json组合区 ---
        $jsonKeyValueArray = array(
            "table_name" => self::$tableName,
            "data_field" => $vDataField,
            "where_field" => "interfaceId",
            "where_value" => "{$vInterfaceId}",
            "page" => $pPage,
            "limit" => $pLimit,
            "orderby" => "indexNumber:desc",
            "like_field" => $pLikeField,
            "like_key" => $pLikeKey,
        );
        
        //条件字段判断组合
        $jsonKeyValueArray = HandleFlyJsonAddWhereField($jsonKeyValueArray, "id", $pId);
        $jsonKeyValueArray = HandleFlyJsonAddWhereField($jsonKeyValueArray, "state", $pWhereState);
        $jsonKeyValueArray = HandleFlyJsonAddWhereField($jsonKeyValueArray, "shelfState", $pWhereShelfState);
        $jsonKeyValueArray = HandleFlyJsonAddWhereField($jsonKeyValueArray, $pStateField, $pStateKey);
        //返回结果:分页
        return MIndexDataPaging(JsonHandleArray($jsonKeyValueArray));
        
    }
    
    
    /**
     * 函数名称：接口参数:管理员:记录修改
     * 函数调用：ObjFlyInterfaceParam() -> AdminFlyInterfaceParamSet($fpAdminId)
     * 创建时间：2020-01-07 23:27:29
     * */
    public function AdminFlyInterfaceParamSet($fpAdminId){
        
        //--- 参数限制区 ---
        $vParameterArray = GetArray(FLY_PARAMETER_LIMIT, ",");
        $bParameterJudge = JudgeParameterLimit($vParameterArray);
        if(JudgeJsonFalse($bParameterJudge)){return $bParameterJudge;}
        
        //--- 数据预定义 ---
        $json = "";
        
        //--- 参数获取区 ---
        //参数:表ID:id
        $pId = GetParameter("id",$json);
        if(!JudgeRegularIntRight($pId)){return JsonModelParameterException("id", $pId, 20, "值必须是正整数", __LINE__);}
        
        //参数:记录描述:descript
        $pDescript = GetParameter("descript",$json);
        if(!IsNull($pDescript)&&!JudgeRegularFont($pDescript)){return JsonModelParameterException("descript", $pDescript, 36, "内容格式错误", __LINE__);}
        
        //参数:序号:indexNumber
        $pIndexNumber = GetParameter("indexnumber",$json);
        if(!IsNull($pIndexNumber)&&!JudgeRegularInt($pIndexNumber)){return JsonModelParameterException("indexnumber", $pIndexNumber, 11, "值必须是整数", __LINE__);}
        
        //参数:上下架状态:shelfState
        $pShelfState = GetParameter("shelfstate",$json);
        if(!IsNull($pShelfState)&&!JudgeRegularState($pShelfState)){return JsonModelParameterException("shelfstate", $pShelfState, 36, "状态值格式错误", __LINE__);}
        
        //参数:记录状态:state
        $pState = GetParameter("state",$json);
        if(!IsNull($pState)&&!JudgeRegularFont($pState)){return JsonModelParameterException("state", $pState, 36, "内容格式错误", __LINE__);}
        
        //参数:接口ID:interfaceId
        $pInterfaceId = GetParameter("interfaceid",$json);
        if(!IsNull($pInterfaceId)&&!JudgeRegularInt($pInterfaceId)){return JsonModelParameterException("interfaceid", $pInterfaceId, 11, "值必须是整数", __LINE__);}
        
        //参数:参数名称:parameterName
        $pParameterName = GetParameter("parametername",$json);
        if(!IsNull($pParameterName)&&!JudgeRegularFont($pParameterName)){return JsonModelParameterException("parametername", $pParameterName, 64, "内容格式错误", __LINE__);}
        
        //参数:参数值:parameterValue
        $pParameterValue = GetParameter("parametervalue",$json);
        if(!IsNull($pParameterValue)&&!JudgeRegularFont($pParameterValue)){return JsonModelParameterException("parametervalue", $pParameterValue, 64, "内容格式错误", __LINE__);}
        
        //参数:参数值类型:parameterType
        $pParameterType = GetParameter("parametertype",$json);
        if(!IsNull($pParameterType)&&!JudgeRegularFont($pParameterType)){return JsonModelParameterException("parametertype", $pParameterType, 36, "内容格式错误", __LINE__);}
        
        //参数:参数是否必填:parameterMust
        $pParameterMust = GetParameter("parametermust",$json);
        if(!IsNull($pParameterMust)&&!JudgeRegularFont($pParameterMust)){return JsonModelParameterException("parametermust", $pParameterMust, 36, "内容格式错误", __LINE__);}
        
        //参数:参数说明:parameterDescript
        $pParameterDescript = GetParameter("parameterdescript",$json);
        if(!IsNull($pParameterDescript)&&!JudgeRegularFont($pParameterDescript)){return JsonModelParameterException("parameterdescript", $pParameterDescript, 64, "内容格式错误", __LINE__);}
        
	    //参数:参数备注:parameterRemarks
	    $pParameterRemarks = GetParameter("parameterremarks",$json);

        //参数:参数例程:parameterDemo
        $pParameterDemo = GetParameter("parameterdemo",$json);
        if(!IsNull($pParameterDemo)&&!JudgeRegularFont($pParameterDemo)){return JsonModelParameterException("parameterdemo", $pParameterDemo, 64, "内容格式错误", __LINE__);}
        
        //--- Json组合区 ---
        
        //Json数组
        $jsonKeyValueArray = array(
            "table_name" => self::$tableName,
            "updatefield" => "",
            "updatevalue" => "",
            "where_field" => "id",
            "where_value" => "{$pId}",
        );
        
        //修改字段判断组合
        $jsonKeyValueArray = HandleFlyJsonAddUpdateField($jsonKeyValueArray, "descript", $pDescript);
        $jsonKeyValueArray = HandleFlyJsonAddUpdateField($jsonKeyValueArray, "indexNumber", $pIndexNumber);
        $jsonKeyValueArray = HandleFlyJsonAddUpdateField($jsonKeyValueArray, "shelfState", $pShelfState);
        $jsonKeyValueArray = HandleFlyJsonAddUpdateField($jsonKeyValueArray, "state", $pState);
        $jsonKeyValueArray = HandleFlyJsonAddUpdateField($jsonKeyValueArray, "interfaceId", $pInterfaceId);
        $jsonKeyValueArray = HandleFlyJsonAddUpdateField($jsonKeyValueArray, "parameterName", $pParameterName);
        $jsonKeyValueArray = HandleFlyJsonAddUpdateField($jsonKeyValueArray, "parameterValue", $pParameterValue);
        $jsonKeyValueArray = HandleFlyJsonAddUpdateField($jsonKeyValueArray, "parameterType", $pParameterType);
        $jsonKeyValueArray = HandleFlyJsonAddUpdateField($jsonKeyValueArray, "parameterMust", $pParameterMust);
        $jsonKeyValueArray = HandleFlyJsonAddUpdateField($jsonKeyValueArray, "parameterDescript", $pParameterDescript);
        $jsonKeyValueArray = HandleFlyJsonAddUpdateField($jsonKeyValueArray, "parameterRemarks", $pParameterRemarks);
        $jsonKeyValueArray = HandleFlyJsonAddUpdateField($jsonKeyValueArray, "parameterDemo", $pParameterDemo);
        
        //判断字段值是否为空
        $vUpdateFieldJudgeInfor = JudgeFlyJsonAddUpdateField($jsonKeyValueArray,"descript,indexnumber,shelfstate,state,interfaceid,parametername,parametervalue,parametertype,parametermust,parameterdescript,parameterdemo");
        if(JudgeJsonFalse($vUpdateFieldJudgeInfor)){ return $vUpdateFieldJudgeInfor; }
        
        //返回结果
        return MIndexDataUpdate(JsonHandleArray($jsonKeyValueArray));
        
    }
    
    
    /**
     * 函数名称：接口参数:管理员:记录状态修改
     * 函数调用：ObjFlyInterfaceParam() -> AdminFlyInterfaceParamSetState($fpAdminId)
     * 创建时间：2020-01-07 23:27:29
     * */
    public function AdminFlyInterfaceParamSetState($fpAdminId){
        
        //--- 参数限制区 ---
        $vParameterArray = GetArray(FLY_PARAMETER_LIMIT, ",");
        $bParameterJudge = JudgeParameterLimit($vParameterArray);
        if(JudgeJsonFalse($bParameterJudge)){return $bParameterJudge;}
        
        //--- 变量预定义 ---
        $json="";		//Json参数
        
        //--- 参数获取区 ---
        //参数：id
        $pId = GetParameter("id",$json);
        if(!JudgeRegularFont($pId)){return JsonModelParameterInfor("id","参数值不符合规则",20);}
        
        //参数:记录状态:state
        $pState = GetParameter("state",$json);
        if(IsNull($pState)||GetStringLength($pState)>36||!($pState=="STATE_NORMAL"||$pState=="STATE_DELETE")){return JsonModelParameterInfor("state","值必须是STATE_NORMAL/STATE_DELETE",36);}
        
        //--- 数据提交区 ---
        return ServiceTableDataSystemSet(self::$tableName,"state","{$pState}","id","{$pId}");
    }
    
    /**
     * 函数名称：接口参数:管理员:数据上下架状态修改
     * 函数调用：ObjFlyInterfaceParam() -> AdminFlyInterfaceParamShelfState($fpAdminId)
     * 创建时间：2020-01-07 23:27:29
     * */
    public function AdminFlyInterfaceParamShelfState($fpAdminId){
        //--- 参数限制区 ---
        $vParameterArray = GetArray(FLY_PARAMETER_LIMIT, ",");
        $bParameterJudge = JudgeParameterLimit($vParameterArray);
        if(JudgeJsonFalse($bParameterJudge)){return $bParameterJudge;}
        
        //--- 参数获取区 ---
        //参数：id
        $pId = GetParameter("id","");
        if(IsNull($pId)){return JsonModelParameterNull("id");}
        //参数：id：正整数正则判断
        if(!JudgeRegularIntRight($pId)){return JsonInforFalse("id必须是正整数", "id");}
        
        //参数：shelfstate
        $pShelfState = GetParameter("shelfstate","");
        if(IsNull($pShelfState)){return JsonModelParameterNull("shelfstate");}
        //参数：shelfstate：正整数正则判断
        if(!($pShelfState=="true"||$pShelfState=="false")){ return JsonInforFalse("上架状态值必须是true或false", "shelfstate"); }
        
        //--- Json提交区 ---
        //Json数组
        $jsonKeyValueArray = array(
            "table_name" => self::$tableName,
            "update_field" => "shelfState",
            "update_value" => "{$pShelfState}",
            "where_field" => "id",
            "where_value" => "{$pId}",
        );
        //执行:修改
        $vJsonResult = MIndexDataUpdate(JsonHandleArray($jsonKeyValueArray));
        return $vJsonResult;
        
    }
    
    /**
     * 函数名称：接口参数:管理员:记录永久删除
     * 函数调用：ObjFlyInterfaceParam() -> AdminFlyInterfaceParamDelete($fpAdminId)
     * 创建时间：2020-01-07 23:27:29
     * */
    public function AdminFlyInterfaceParamDelete($fpAdminId){
        //--- 参数限制区 ---
        $vParameterArray = GetArray(FLY_PARAMETER_LIMIT, ",");
        $bParameterJudge = JudgeParameterLimit($vParameterArray);
        if(JudgeJsonFalse($bParameterJudge)){return $bParameterJudge;}
        
        //--- 变量预定义 ---
        $json="";		//Json参数
        
        //--- 参数获取区 ---
        //参数：id
        $pId = GetParameter("id",$json);
        if(!JudgeRegularIntRight($pId)){return JsonModelParameterInfor("id","参数值不符合规则",20);}
        
        //Json数组
        $jsonKeyValueArray = array(
            "table_name" => self::$tableName,
            "where_field" => "id",
            "where_value" => "{$pId}",
        );
        //执行:删除
        $vJsonResult = MIndexDataDelete(JsonHandleArray($jsonKeyValueArray));
        return $vJsonResult;
    }
    
    
    //---------- 测试方法（test） ----------
    
    //---------- 基础方法（base） ----------
    
    
    /**
     * 函数名称：获取数据表名称
     * 函数调用：ObjFlyInterfaceParam() -> GetTableName()
     * 创建时间：2020-01-07 23:27:29
     * */
    public function GetTableName(){
        return self::$tableName;
    }
    
    /**
     * 函数名称：获取类描述
     * 函数调用：ObjFlyInterfaceParam() -> GetClassDescript()
     * 创建时间：2020-01-07 23:27:29
     * */
    public function GetClassDescript(){
        return self::$classDescript;
    }
    
    /**
     * 函数名称：获取数据表字段
     * 函数调用：ObjFlyInterfaceParam() -> GetTableField()
     * 创建时间：2020-01-07 23:27:29
     * */
    public function GetTableField(){
        return MIndexDBTableFields(JsonObj(JsonKeyValue("table_name", self::$tableName)));
    }
    
    /**
     * 函数名称：类数据表创建
     * 函数调用：ObjFlyInterfaceParam() -> OprationCreateTable()
     * 创建时间：2020-01-07 23:27:29
     * */
    public function OprationCreateTable(){
        $sql = "CREATE TABLE `fly_interface_param` (  `id` bigint(20) NOT NULL AUTO_INCREMENT COMMENT '表ID',  `onlyKey` varchar(36) DEFAULT 'none' COMMENT '唯一Key',  `descript` varchar(36) DEFAULT NULL COMMENT '记录描述',  `indexNumber` int(11) DEFAULT '-1' COMMENT '序号',  `updateTime` timestamp NULL DEFAULT NULL COMMENT '修改时间',  `addTime` timestamp NULL DEFAULT CURRENT_TIMESTAMP COMMENT '添加时间',  `shelfState` varchar(36) DEFAULT 'true' COMMENT '上下架状态',  `state` varchar(36) DEFAULT 'STATE_NORMAL' COMMENT '记录状态',  `interfaceId` int(11) DEFAULT NULL COMMENT '接口ID',  `parameterName` varchar(64) DEFAULT NULL COMMENT '参数名称',  `parameterValue` varchar(64) DEFAULT NULL COMMENT '参数值',  `parameterType` varchar(36) DEFAULT NULL COMMENT '参数值类型',  `parameterMust` varchar(36) DEFAULT 'YES' COMMENT '参数是否必填',  `parameterDescript` varchar(64) DEFAULT NULL COMMENT '参数说明',  `parameterDemo` varchar(64) DEFAULT NULL COMMENT '参数例程',  PRIMARY KEY (`id`) USING BTREE) ENGINE=InnoDB AUTO_INCREMENT=691 DEFAULT CHARSET=utf8";
        DBHelper::DataSubmit($sql, "");
        $vTableCheck = DBMySQLServiceJson::GetDBTableCheck('{"tableName":"'.self::$tableName.'"}');
        if(JudgeJsonTrue($vTableCheck)){
            return JsonInforTrue("创建成功", self::$tableName);
        }
        return JsonInforFalse("创建失败", self::$tableName);
    }
    
    /**
     * 函数名称：类数据表基础字段检测
     * 函数调用：ObjFlyInterfaceParam() -> OprationTableFieldBaseCheck()
     * 创建时间：2020-01-07 23:27:29
     * */
    public function OprationTableFieldBaseCheck(){
        return DBMySQLServiceJson::OprationFieldBaseCheck(JsonObj(JsonKeyValue("table_name", self::$tableName)));
    }
    
    
    
    
}
