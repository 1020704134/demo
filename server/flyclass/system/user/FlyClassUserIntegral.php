<?php

/**------------------------------------*
 * 作者：shark
 * 创建时间：2020-05-14 20:36:32
 * Fly编码：1589459792146FLY576884
 * 类对象名：ObjFlyUserIntegral()
 * ------------------------------------ */

//引入区

class FlyClassUserIntegral{
    
    
    //---------- 类成员（member） ----------
    
    //类描述
    public static $classDescript = "用户积分";
    //类数据表名
    public static $tableName = "fly_user_integral";
    
    
    //---------- 私有方法（private） ----------
    
    //---------- 游客方法（visitor） ----------
    
    //---------- 系统方法（system） ----------
    
    /**
     * 函数名称：用户积分:系统:记录添加
     * 函数调用：ObjFlyUserIntegral() -> SystemFlyUserIntegralAdd
     * 创建时间：2020-05-14 20:36:31
     * */
    public function SystemFlyUserIntegralAdd($fpUserId,$fpIntegralEvent,$fpIntegralNumber,$fpIntegralSum){
        
        //--- Json组合区 ---
        //Json数组
        $jsonKeyValueArray = array(
            "table_name" => self::$tableName,
            "insert_field" => "descript,shelfState,userId,integralEvent,integralNumber,integralSum",
            "descript" => self::$classDescript,
            "shelfstate" => "false",
            "userid" => $fpUserId,
            "integralevent" => $fpIntegralEvent,
            "integralnumber" => $fpIntegralNumber,
            "integralsum" => $fpIntegralSum,
            "key_field" => "userId",
        );
        //执行:添加
        $vJsonResult = MIndexDataInsert(JsonHandleArray($jsonKeyValueArray));
        return $vJsonResult;
    }
    
    
    //---------- 用户方法（user） ----------
    
    /**
     * 函数名称：用户积分:登录积分
     * 函数调用：ObjFlyUserIntegral() -> UserFlyUserIntegralPaging($fpUserId)
     * 创建时间：2020-05-14 20:36:32
     * */
    public function UserFlyUserIntegralLogin($fpUserId){
        $integralSum = DBHelper::DataString("SELECT SUM(integralNumber) FROM fly_user_integral WHERE userId='{$fpUserId}'", null);
        if(IsNull($integralSum)){ $integralSum = 0; }
        $integralSum = intval($integralSum);
        $integralNumber = 50;
        $integralSum += $integralNumber;
        $integralEvent = "每日登录赠送积分";
        $vDate = GetTimeDate();
        //查询记录是否存在
        $vRecode = "SELECT id FROM fly_user_integral WHERE integralDate='{$vDate}' AND userId='{$fpUserId}'";
        if(!IsNull(DBHelper::DataString($vRecode,null))){
            return JsonInforFalse("记录已存在", $integralEvent);
        }
        //添加积分
        DBHelper::DataSubmit("INSERT INTO fly_user_integral(userId,integralDate,integralEvent,integralNumber,integralSum) VALUES('{$fpUserId}','{$vDate}','{$integralEvent}','{$integralNumber}','{$integralSum}')",null);
        return JsonInforTrue("赠送登录积分{$integralNumber}", "fly_user_integral");
    }
    
    /**
     * 函数名称：用户积分:用户:记录查询
     * 函数调用：ObjFlyUserIntegral() -> UserFlyUserIntegralPaging($fpUserId)
     * 创建时间：2020-05-14 20:36:32
     * */
    public function UserFlyUserIntegralPaging($fpUserId){
        
        //--- 参数限制区 ---
        $vParameterArray = GetArray(FLY_PARAMETER_LIMIT, ",");
        $bParameterJudge = JudgeParameterLimit($vParameterArray);
        if(JudgeJsonFalse($bParameterJudge)){return $bParameterJudge;}
        
        //--- 数据预定义 ---
        $json = "";
        
        //--- 参数获取区 ---
        $pPage = GetParameterNoCode("page","");     //参数:页码:page
        $pLimit = GetParameterNoCode("limit","");   //参数:条数:limit
        
        //参数：id
        $pId = GetParameterNoCode("id","");
        if(!IsNull($pId)&&!JudgeRegularIntRight($pId)){return JsonModelParameterException("id", $pId, 20, "值必须是正整数", __LINE__);}
        if(!IsNull($pId)){ $pPage = 1; $pLimit = 1; }
        
        //参数判断:页码:page
        if(IsNull($pPage)||!JudgeRegularIntRight($pPage)){return JsonModelParameterException("page", $pPage, 9, "值必须是正整数", __LINE__);}
        //参数判断:条数:limit
        if(IsNull($pLimit)||!JudgeRegularIntRight($pLimit)){return JsonModelParameterException("limit", $pLimit, 9, "值必须是正整数", __LINE__);}
        
        //参数：记录状态
        $pWhereState = GetParameterNoCode("wherestate","");
        if(!IsNull($pWhereState)&&!($pWhereState=="STATE_NORMAL"||$pWhereState=="STATE_DELETE")){return JsonModelParameterException("wherestate", $pWhereState, 64, "值必须是STATE_NORMAL/STATE_DELETE", __LINE__);}
        
        //参数：上下架状态
        $pWhereShelfState = GetParameterNoCode("whereshelfstate","");
        if(!IsNull($pWhereShelfState)&&!($pWhereShelfState=="true"||$pWhereShelfState=="false")){return JsonModelParameterException("whereshelfstate", $pWhereShelfState, 36, "值必须是true/false", __LINE__);}
        
        //参数：like
        $pLikeField = GetParameterNoCode("likefield","");
        if(!IsNull($pLikeField)&&!DBMySQLJudge::JudgeTableField(self::$tableName,$pLikeField)){ return JsonInforFalse("搜索字段不存在", $pLikeField,__LINE__); }
        $pLikeKey = GetParameterNoCode("likekey","");
        if(!IsNull($pLikeKey)){ $pLikeKey = HandleStringAddslashes($pLikeKey); }
        
        //参数：state
        $pStateField = GetParameterNoCode("statefield","");
        if(!IsNull($pStateField)&&!DBMySQLJudge::JudgeTableField(self::$tableName,$pStateField)){ return JsonInforFalse("状态字段不存在", $pStateField,__LINE__); }
        $pStateKey = GetParameterNoCode("statekey","");
        if(!IsNull($pStateKey)&&!JudgeRegularState($pStateKey)){ return JsonInforFalse("状态码错误", $pStateKey,__LINE__); }
        
        //字段定义
        $vDataField = DBMySQLSelect::TableFieldString(self::$tableName);
        //$vDataField = "id,onlyKey,descript,indexNumber,updateTime,addTime,shelfState,state,userId,integralEvent,integralNumber,integralSum";
        
        //--- Json组合区 ---
        $jsonKeyValueArray = array(
            "table_name" => self::$tableName,
            "data_field" => $vDataField,
            "where_field" => "userId",
            "where_value" => "{$fpUserId}",
            "page" => $pPage,
            "limit" => $pLimit,
            //"descbo" => "true",
            //"orderby" => "id",
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
     * 函数名称：用户积分:管理员:记录添加
     * 函数调用：ObjFlyUserIntegral() -> AdminFlyUserIntegralAdd($fpAdminId)
     * 创建时间：2020-05-14 20:36:31
     * */
    public function AdminFlyUserIntegralAdd($fpAdminId){
        
        
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
        
        //参数:用户ID:userId
        $pUserId = GetParameterNoCode("userid",$json);
        if(!JudgeRegularFont($pUserId)){return JsonModelParameterException("userid", $pUserId, 36, "内容格式错误", __LINE__);}
        
        //参数:积分事件:integralEvent
        $pIntegralEvent = GetParameterNoCode("integralevent",$json);
        if(!JudgeRegularFont($pIntegralEvent)){return JsonModelParameterException("integralevent", $pIntegralEvent, 36, "内容格式错误", __LINE__);}
        
        //参数:积分数:integralNumber
        $pIntegralNumber = GetParameterNoCode("integralnumber",$json);
        if(!JudgeRegularInt($pIntegralNumber)){return JsonModelParameterException("integralnumber", $pIntegralNumber, 11, "值必须是整数", __LINE__);}
        
        //参数:积分总数:integralSum
        $pIntegralSum = GetParameterNoCode("integralsum",$json);
        if(!JudgeRegularInt($pIntegralSum)){return JsonModelParameterException("integralsum", $pIntegralSum, 11, "值必须是整数", __LINE__);}
        
        //--- Json组合区 ---
        //Json数组
        $jsonKeyValueArray = array(
            "table_name" => self::$tableName,
            "insert_field" => "descript,shelfState,userId,integralEvent,integralNumber,integralSum",
            "descript" => self::$classDescript,
            "shelfstate" => $pShelfState,
            "userid" => $pUserId,
            "integralevent" => $pIntegralEvent,
            "integralnumber" => $pIntegralNumber,
            "integralsum" => $pIntegralSum,
            "key_field" => "userId",
        );
        //执行:添加
        $vJsonResult = MIndexDataInsert(JsonHandleArray($jsonKeyValueArray));
        return $vJsonResult;
    }
    
    
    /**
     * 函数名称：用户积分:管理员:记录查询
     * 函数调用：ObjFlyUserIntegral() -> AdminFlyUserIntegralPaging($fpAdminId)
     * 创建时间：2020-05-14 20:36:32
     * */
    public function AdminFlyUserIntegralPaging($fpAdminId){
        
        //--- 参数限制区 ---
        $vParameterArray = GetArray(FLY_PARAMETER_LIMIT, ",");
        $bParameterJudge = JudgeParameterLimit($vParameterArray);
        if(JudgeJsonFalse($bParameterJudge)){return $bParameterJudge;}
        
        //--- 数据预定义 ---
        $json = "";
        
        //--- 参数获取区 ---
        $pPage = GetParameterNoCode("page","");     //参数:页码:page
        $pLimit = GetParameterNoCode("limit","");   //参数:条数:limit
        
        //参数：id
        $pId = GetParameterNoCode("id","");
        if(!IsNull($pId)&&!JudgeRegularIntRight($pId)){return JsonModelParameterException("id", $pId, 20, "值必须是正整数", __LINE__);}
        if(!IsNull($pId)){ $pPage = 1; $pLimit = 1; }
        
        //参数判断:页码:page
        if(IsNull($pPage)||!JudgeRegularIntRight($pPage)){return JsonModelParameterException("page", $pPage, 9, "值必须是正整数", __LINE__);}
        //参数判断:条数:limit
        if(IsNull($pLimit)||!JudgeRegularIntRight($pLimit)){return JsonModelParameterException("limit", $pLimit, 9, "值必须是正整数", __LINE__);}
        
        //参数：记录状态
        $pWhereState = GetParameterNoCode("wherestate","");
        if(!IsNull($pWhereState)&&!($pWhereState=="STATE_NORMAL"||$pWhereState=="STATE_DELETE")){return JsonModelParameterException("wherestate", $pWhereState, 64, "值必须是STATE_NORMAL/STATE_DELETE", __LINE__);}
        
        //参数：上下架状态
        $pWhereShelfState = GetParameterNoCode("whereshelfstate","");
        if(!IsNull($pWhereShelfState)&&!($pWhereShelfState=="true"||$pWhereShelfState=="false")){return JsonModelParameterException("whereshelfstate", $pWhereShelfState, 36, "值必须是true/false", __LINE__);}
        
        //参数：like
        $pLikeField = GetParameterNoCode("likefield","");
        if(!IsNull($pLikeField)&&!DBMySQLJudge::JudgeTableField(self::$tableName,$pLikeField)){ return JsonInforFalse("搜索字段不存在", $pLikeField,__LINE__); }
        $pLikeKey = GetParameterNoCode("likekey","");
        if(!IsNull($pLikeKey)){ $pLikeKey = HandleStringAddslashes($pLikeKey); }
        
        //参数：state
        $pStateField = GetParameterNoCode("statefield","");
        if(!IsNull($pStateField)&&!DBMySQLJudge::JudgeTableField(self::$tableName,$pStateField)){ return JsonInforFalse("状态字段不存在", $pStateField,__LINE__); }
        $pStateKey = GetParameterNoCode("statekey","");
        if(!IsNull($pStateKey)&&!JudgeRegularState($pStateKey)){ return JsonInforFalse("状态码错误", $pStateKey,__LINE__); }
        
        //字段定义
        $vDataField = DBMySQLSelect::TableFieldString(self::$tableName);
        //$vDataField = "id,onlyKey,descript,indexNumber,updateTime,addTime,shelfState,state,userId,integralEvent,integralNumber,integralSum";
        
        //--- Json组合区 ---
        $jsonKeyValueArray = array(
            "table_name" => self::$tableName,
            "data_field" => $vDataField,
            "where_field" => "",
            "where_value" => "",
            "page" => $pPage,
            "limit" => $pLimit,
            //"descbo" => "true",
            //"orderby" => "id",
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
     * 函数名称：用户积分:管理员:记录修改
     * 函数调用：ObjFlyUserIntegral() -> AdminFlyUserIntegralSet($fpAdminId)
     * 创建时间：2020-05-14 20:36:31
     * */
    public function AdminFlyUserIntegralSet($fpAdminId){
        
        //--- 参数限制区 ---
        $vParameterArray = GetArray(FLY_PARAMETER_LIMIT, ",");
        $bParameterJudge = JudgeParameterLimit($vParameterArray);
        if(JudgeJsonFalse($bParameterJudge)){return $bParameterJudge;}
        
        //--- 数据预定义 ---
        $json = "";
        
        //--- 参数获取区 ---
        //参数:表ID:id
        $pId = GetParameterNoCode("id",$json);
        if(!JudgeRegularIntRight($pId)){return JsonModelParameterException("id", $pId, 20, "值必须是正整数", __LINE__);}
        
        //参数:记录描述:descript
        $pDescript = GetParameterNoCode("descript",$json);
        if(!IsNull($pDescript)&&!JudgeRegularFont($pDescript)){return JsonModelParameterException("descript", $pDescript, 36, "内容格式错误", __LINE__);}
        
        //参数:序号:indexNumber
        $pIndexNumber = GetParameterNoCode("indexnumber",$json);
        if(!IsNull($pIndexNumber)&&!JudgeRegularInt($pIndexNumber)){return JsonModelParameterException("indexnumber", $pIndexNumber, 11, "值必须是整数", __LINE__);}
        
        //参数:上下架状态:shelfState
        $pShelfState = GetParameterNoCode("shelfstate",$json);
        if(!IsNull($pShelfState)&&!JudgeRegularState($pShelfState)){return JsonModelParameterException("shelfstate", $pShelfState, 36, "状态值格式错误", __LINE__);}
        
        //参数:记录状态:state
        $pState = GetParameterNoCode("state",$json);
        if(!IsNull($pState)&&!JudgeRegularFont($pState)){return JsonModelParameterException("state", $pState, 36, "内容格式错误", __LINE__);}
        
        //参数:用户ID:userId
        $pUserId = GetParameterNoCode("userid",$json);
        if(!IsNull($pUserId)&&!JudgeRegularFont($pUserId)){return JsonModelParameterException("userid", $pUserId, 36, "内容格式错误", __LINE__);}
        
        //参数:积分事件:integralEvent
        $pIntegralEvent = GetParameterNoCode("integralevent",$json);
        if(!IsNull($pIntegralEvent)&&!JudgeRegularFont($pIntegralEvent)){return JsonModelParameterException("integralevent", $pIntegralEvent, 36, "内容格式错误", __LINE__);}
        
        //参数:积分数:integralNumber
        $pIntegralNumber = GetParameterNoCode("integralnumber",$json);
        if(!IsNull($pIntegralNumber)&&!JudgeRegularInt($pIntegralNumber)){return JsonModelParameterException("integralnumber", $pIntegralNumber, 11, "值必须是整数", __LINE__);}
        
        //参数:积分总数:integralSum
        $pIntegralSum = GetParameterNoCode("integralsum",$json);
        if(!IsNull($pIntegralSum)&&!JudgeRegularInt($pIntegralSum)){return JsonModelParameterException("integralsum", $pIntegralSum, 11, "值必须是整数", __LINE__);}
        
        //--- Json组合区 ---
        
        //Json数组
        $jsonKeyValueArray = array(
            "table_name" => self::$tableName,
            "update_field" => "",
            "update_value" => "",
            "where_field" => "id",
            "where_value" => "{$pId}",
        );
        
        //修改字段判断组合
        $jsonKeyValueArray = HandleFlyJsonAddUpdateField($jsonKeyValueArray, "descript", $pDescript);
        $jsonKeyValueArray = HandleFlyJsonAddUpdateField($jsonKeyValueArray, "indexNumber", $pIndexNumber);
        $jsonKeyValueArray = HandleFlyJsonAddUpdateField($jsonKeyValueArray, "shelfState", $pShelfState);
        $jsonKeyValueArray = HandleFlyJsonAddUpdateField($jsonKeyValueArray, "state", $pState);
        $jsonKeyValueArray = HandleFlyJsonAddUpdateField($jsonKeyValueArray, "userId", $pUserId);
        $jsonKeyValueArray = HandleFlyJsonAddUpdateField($jsonKeyValueArray, "integralEvent", $pIntegralEvent);
        $jsonKeyValueArray = HandleFlyJsonAddUpdateField($jsonKeyValueArray, "integralNumber", $pIntegralNumber);
        $jsonKeyValueArray = HandleFlyJsonAddUpdateField($jsonKeyValueArray, "integralSum", $pIntegralSum);
        
        //判断字段值是否为空
        $vUpdateFieldJudgeInfor = JudgeFlyJsonAddUpdateField($jsonKeyValueArray,"descript,indexnumber,shelfstate,state,userid,integralevent,integralnumber,integralsum");
        if(JudgeJsonFalse($vUpdateFieldJudgeInfor)){ return $vUpdateFieldJudgeInfor; }
        
        //返回结果
        return MIndexDataUpdate(JsonHandleArray($jsonKeyValueArray));
        
    }
    
    
    /**
     * 函数名称：用户积分:管理员:记录状态修改
     * 函数调用：ObjFlyUserIntegral() -> AdminFlyUserIntegralSetState($fpAdminId)
     * 创建时间：2020-05-14 20:36:32
     * */
    public function AdminFlyUserIntegralSetState($fpAdminId){
        
        //--- 参数限制区 ---
        $vParameterArray = GetArray(FLY_PARAMETER_LIMIT, ",");
        $bParameterJudge = JudgeParameterLimit($vParameterArray);
        if(JudgeJsonFalse($bParameterJudge)){return $bParameterJudge;}
        
        //--- 变量预定义 ---
        $json="";		//Json参数
        
        //--- 参数获取区 ---
        //参数：id
        $pId = GetParameterNoCode("id",$json);
        if(!JudgeRegularFont($pId)){return JsonModelParameterInfor("id","参数值不符合规则",20);}
        
        //参数:记录状态:state
        $pState = GetParameterNoCode("state",$json);
        if(IsNull($pState)||GetStringLength($pState)>36||!($pState=="STATE_NORMAL"||$pState=="STATE_DELETE")){return JsonModelParameterInfor("state","值必须是STATE_NORMAL/STATE_DELETE",36);}
        
        //--- 数据提交区 ---
        return ServiceTableDataSystemSet(self::$tableName,"state","{$pState}","id","{$pId}");
    }
    
    /**
     * 函数名称：用户积分:管理员:数据上下架状态修改
     * 函数调用：ObjFlyUserIntegral() -> AdminFlyUserIntegralShelfState($fpAdminId)
     * 创建时间：2020-05-14 20:36:32
     * */
    public function AdminFlyUserIntegralShelfState($fpAdminId){
        //--- 参数限制区 ---
        $vParameterArray = GetArray(FLY_PARAMETER_LIMIT, ",");
        $bParameterJudge = JudgeParameterLimit($vParameterArray);
        if(JudgeJsonFalse($bParameterJudge)){return $bParameterJudge;}
        
        //--- 参数获取区 ---
        //参数：id
        $pId = GetParameterNoCode("id","");
        if(IsNull($pId)){return JsonModelParameterNull("id");}
        //参数：id：正整数正则判断
        if(!JudgeRegularIntRight($pId)){return JsonInforFalse("id必须是正整数", "id");}
        
        //参数：shelfstate
        $pShelfState = GetParameterNoCode("shelfstate","");
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
     * 函数名称：用户积分:管理员:记录永久删除
     * 函数调用：ObjFlyUserIntegral() -> AdminFlyUserIntegralDelete($fpAdminId)
     * 创建时间：2020-05-14 20:36:32
     * */
    public function AdminFlyUserIntegralDelete($fpAdminId){
        //--- 参数限制区 ---
        $vParameterArray = GetArray(FLY_PARAMETER_LIMIT, ",");
        $bParameterJudge = JudgeParameterLimit($vParameterArray);
        if(JudgeJsonFalse($bParameterJudge)){return $bParameterJudge;}
        
        //--- 变量预定义 ---
        $json="";		//Json参数
        
        //--- 参数获取区 ---
        //参数：id
        $pId = GetParameterNoCode("id",$json);
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
     * 函数调用：ObjFlyUserIntegral() -> GetTableName()
     * 创建时间：2020-05-14 20:36:31
     * */
    public function GetTableName(){
        return self::$tableName;
    }
    
    /**
     * 函数名称：获取类描述
     * 函数调用：ObjFlyUserIntegral() -> GetClassDescript()
     * 创建时间：2020-05-14 20:36:31
     * */
    public function GetClassDescript(){
        return self::$classDescript;
    }
    
    /**
     * 函数名称：获取数据表字段
     * 函数调用：ObjFlyUserIntegral() -> GetTableField()
     * 创建时间：2020-05-14 20:36:31
     * */
    public function GetTableField(){
        return MIndexDBTableFields(JsonObj(JsonKeyValue("table_name", self::$tableName)));
    }
    
    /**
     * 函数名称：类数据表创建
     * 函数调用：ObjFlyUserIntegral() -> OprationCreateTable()
     * 创建时间：2020-05-14 20:36:31
     * */
    public function OprationCreateTable(){
        $sql = "CREATE TABLE `fly_user_integral` (  `id` bigint(20) NOT NULL AUTO_INCREMENT COMMENT '表ID',  `onlyKey` varchar(36) DEFAULT NULL COMMENT '唯一Key',  `descript` varchar(36) DEFAULT NULL COMMENT '记录描述',  `indexNumber` int(11) DEFAULT '-1' COMMENT '序号',  `updateTime` timestamp NULL DEFAULT NULL COMMENT '修改时间',  `addTime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '添加时间',  `shelfState` varchar(36) DEFAULT 'true' COMMENT '上下架状态',  `state` varchar(36) DEFAULT 'STATE_NORMAL' COMMENT '记录状态',  `userId` varchar(36) DEFAULT NULL COMMENT '用户ID',  `integralEvent` varchar(36) DEFAULT NULL COMMENT '积分事件',  `integralNumber` int(11) DEFAULT NULL COMMENT '积分数',  `integralSum` int(11) DEFAULT NULL COMMENT '积分总数',  PRIMARY KEY (`id`) USING BTREE) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='用户积分'";
        DBHelper::DataSubmit($sql, "");
        $vTableCheck = DBMySQLServiceJson::GetDBTableCheck('{"table_name":"'.self::$tableName.'"}');
        if(JudgeJsonTrue($vTableCheck)){
            return JsonInforTrue("创建成功", self::$tableName);
        }
        return JsonInforFalse("创建失败", self::$tableName);
    }
    
    /**
     * 函数名称：类数据表基础字段检测
     * 函数调用：ObjFlyUserIntegral() -> OprationTableFieldBaseCheck()
     * 创建时间：2020-05-14 20:36:31
     * */
    public function OprationTableFieldBaseCheck(){
        return DBMySQLServiceJson::OprationFieldBaseCheck(JsonObj(JsonKeyValue("table_name", self::$tableName)));
    }
    
    
    
    
}
