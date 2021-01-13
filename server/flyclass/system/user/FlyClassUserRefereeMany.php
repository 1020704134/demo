<?php

/**------------------------------------*
 * 作者：shark
 * 创建时间：2020-07-21 18:39:26
 * Fly编码：1595327966476FLY897676
 * 类对象名：ObjFlyUserRefereeMany()
 * ------------------------------------ */

//引入区

class FlyClassUserRefereeMany{
    
    
    //---------- 类成员（member） ----------
    
    //类描述
    public static $classDescript = "用户推荐奖励";
    //类数据表名
    public static $tableName = "fly_user_referee_many";
    
    
    //---------- 私有方法（private） ----------
    
    //---------- 游客方法（visitor） ----------
    
    /**
     * 函数名称：用户推荐奖励:游客:记录查询
     * 函数调用：ObjFlyUserRefereeMany() -> VisitorFlyUserRefereeManyPaging()
     * 创建时间：2020-07-21 18:39:26
     * */
    public function VisitorFlyUserRefereeManyPaging(){
        
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
        //$vDataField = "id,onlyKey,descript,indexNumber,updateTime,addTime,shelfState,state,userId,refereeUserId,money,moneyDescript";
        
        //--- Json组合区 ---
        $jsonKeyValueArray = array(
            "table_name" => self::$tableName,
            "data_field" => $vDataField,
            "where_field" => "shelfState",
            "where_value" => "true",
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
    
    
    //---------- 系统方法（system） ----------
    
    /**
     * 函数名称：用户推荐奖励:系统:记录添加
     * 函数调用：ObjFlyUserRefereeMany() -> SystemFlyUserRefereeManyAdd
     * 创建时间：2020-07-21 18:39:26
     * */
    public function SystemFlyUserRefereeManyAdd($fpUserId,$fpRefereeUserId,$fpMoney,$fpMoneyDescript){
        
        //--- Json组合区 ---
        //Json数组
        $jsonKeyValueArray = array(
            "table_name" => self::$tableName,
            "insert_field" => "descript,shelfState,userId,refereeUserId,money,moneyDescript",
            "descript" => self::$classDescript,
            "shelfstate" => "false",
            "userid" => $fpUserId,
            "refereeuserid" => $fpRefereeUserId,
            "money" => $fpMoney,
            "moneydescript" => $fpMoneyDescript,
            "key_field" => "userId,refereeUserId",
        );
        //执行:添加
        $vJsonResult = MIndexDataInsert(JsonHandleArray($jsonKeyValueArray));
        return $vJsonResult;
    }
    
    
    //---------- 用户方法（user） ----------
    
    /**
     * 函数名称：用户推荐奖励:用户:记录查询
     * 函数调用：ObjFlyUserRefereeMany() -> UserFlyUserRefereeManyPaging()
     * 创建时间：2020-07-21 18:39:26
     * */
    public function UserFlyUserRefereeManyPaging($fpUserId=null){
        
        $vUserId = "";
        if(IsNull($fpUserId)){
            $vUserId = GetParameter('user_id', "");
            if(!JudgeRegularNumber($vUserId)){return JsonInforFalse("用户ID格式异常", "user_id");}
        }else{
            $vUserId = $fpUserId;
        }
        
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
        //$vDataField = "id,onlyKey,descript,indexNumber,updateTime,addTime,shelfState,state,userId,refereeUserId,money,moneyDescript";
        
        //--- Json组合区 ---
        $jsonKeyValueArray = array(
            "table_name" => self::$tableName,
            "data_field" => $vDataField,
            "where_field" => "refereeUserId",
            "where_value" => "{$vUserId}",
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
     * 函数名称：用户推荐奖励:用户:记录查询
     * 函数调用：ObjFlyUserRefereeMany() -> UserFlyUserRefereeManyPaging()
     * 创建时间：2020-07-21 18:39:26
     * */
    public function UserFlyUserRefereeManyStateSet(){
        
        $vMoneyId = GetParameter('id', "");
        if(!JudgeRegularNumber($vMoneyId)){return JsonInforFalse("记录ID格式异常", "id");}
        
        //Json数组
        $jsonKeyValueArray = array(
            "table_name" => self::$tableName,
            "update_field" => "shelfState",
            "update_value" => "true",
            "where_field" => "id",
            "where_value" => "{$vMoneyId}",
        );
        
        //返回结果
        return MIndexDataUpdate(JsonHandleArray($jsonKeyValueArray));
        
    }
    
    //---------- 管理员方法（admin） ----------
    
    /**
     * 函数名称：用户推荐奖励:管理员:记录添加
     * 函数调用：ObjFlyUserRefereeMany() -> AdminFlyUserRefereeManyAdd($fpAdminId)
     * 创建时间：2020-07-21 18:39:26
     * */
    public function AdminFlyUserRefereeManyAdd($fpAdminId){
        
        
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
        if(!JudgeRegularInt($pUserId)){return JsonModelParameterException("userid", $pUserId, 11, "值必须是整数", __LINE__);}
        
        //参数:推荐用户ID:refereeUserId
        $pRefereeUserId = GetParameterNoCode("refereeuserid",$json);
        if(!JudgeRegularInt($pRefereeUserId)){return JsonModelParameterException("refereeuserid", $pRefereeUserId, 11, "值必须是整数", __LINE__);}
        
        //参数:奖励金额:money
        $pMoney = GetParameterNoCode("money",$json);
        if(!JudgeRegularDouble($pMoney)){return JsonModelParameterException("money", $pMoney, 12, "值必须是数值", __LINE__);}
        
        //参数:奖励备注:moneyDescript
        $pMoneyDescript = GetParameterNoCode("moneydescript",$json);
        if(!JudgeRegularFont($pMoneyDescript)){return JsonModelParameterException("moneydescript", $pMoneyDescript, 36, "内容格式错误", __LINE__);}
        
        //--- Json组合区 ---
        //Json数组
        $jsonKeyValueArray = array(
            "table_name" => self::$tableName,
            "insert_field" => "descript,shelfState,userId,refereeUserId,money,moneyDescript",
            "descript" => self::$classDescript,
            "shelfstate" => $pShelfState,
            "userid" => $pUserId,
            "refereeuserid" => $pRefereeUserId,
            "money" => $pMoney,
            "moneydescript" => $pMoneyDescript,
            "key_field" => "userId,refereeUserId",
        );
        //执行:添加
        $vJsonResult = MIndexDataInsert(JsonHandleArray($jsonKeyValueArray));
        return $vJsonResult;
    }
    
    
    /**
     * 函数名称：用户推荐奖励:管理员:记录查询
     * 函数调用：ObjFlyUserRefereeMany() -> AdminFlyUserRefereeManyPaging($fpAdminId)
     * 创建时间：2020-07-21 18:39:26
     * */
    public function AdminFlyUserRefereeManyPaging($fpAdminId){
        
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
        //$vDataField = "id,onlyKey,descript,indexNumber,updateTime,addTime,shelfState,state,userId,refereeUserId,money,moneyDescript";
        
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
     * 函数名称：用户推荐奖励:管理员:记录修改
     * 函数调用：ObjFlyUserRefereeMany() -> AdminFlyUserRefereeManySet($fpAdminId)
     * 创建时间：2020-07-21 18:39:26
     * */
    public function AdminFlyUserRefereeManySet($fpAdminId){
        
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
        if(!IsNull($pUserId)&&!JudgeRegularInt($pUserId)){return JsonModelParameterException("userid", $pUserId, 11, "值必须是整数", __LINE__);}
        
        //参数:推荐用户ID:refereeUserId
        $pRefereeUserId = GetParameterNoCode("refereeuserid",$json);
        if(!IsNull($pRefereeUserId)&&!JudgeRegularInt($pRefereeUserId)){return JsonModelParameterException("refereeuserid", $pRefereeUserId, 11, "值必须是整数", __LINE__);}
        
        //参数:奖励金额:money
        $pMoney = GetParameterNoCode("money",$json);
        if(!IsNull($pMoney)&&!JudgeRegularDouble($pMoney)){return JsonModelParameterException("money", $pMoney, 12, "值必须是数值", __LINE__);}
        
        //参数:奖励备注:moneyDescript
        $pMoneyDescript = GetParameterNoCode("moneydescript",$json);
        if(!IsNull($pMoneyDescript)&&!JudgeRegularFont($pMoneyDescript)){return JsonModelParameterException("moneydescript", $pMoneyDescript, 36, "内容格式错误", __LINE__);}
        
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
        $jsonKeyValueArray = HandleFlyJsonAddUpdateField($jsonKeyValueArray, "refereeUserId", $pRefereeUserId);
        $jsonKeyValueArray = HandleFlyJsonAddUpdateField($jsonKeyValueArray, "money", $pMoney);
        $jsonKeyValueArray = HandleFlyJsonAddUpdateField($jsonKeyValueArray, "moneyDescript", $pMoneyDescript);
        
        //判断字段值是否为空
        $vUpdateFieldJudgeInfor = JudgeFlyJsonAddUpdateField($jsonKeyValueArray,"descript,indexnumber,shelfstate,state,userid,refereeuserid,money,moneydescript");
        if(JudgeJsonFalse($vUpdateFieldJudgeInfor)){ return $vUpdateFieldJudgeInfor; }
        
        //返回结果
        return MIndexDataUpdate(JsonHandleArray($jsonKeyValueArray));
        
    }
    
    
    /**
     * 函数名称：用户推荐奖励:管理员:记录状态修改
     * 函数调用：ObjFlyUserRefereeMany() -> AdminFlyUserRefereeManySetState($fpAdminId)
     * 创建时间：2020-07-21 18:39:26
     * */
    public function AdminFlyUserRefereeManySetState($fpAdminId){
        
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
     * 函数名称：用户推荐奖励:管理员:数据上下架状态修改
     * 函数调用：ObjFlyUserRefereeMany() -> AdminFlyUserRefereeManyShelfState($fpAdminId)
     * 创建时间：2020-07-21 18:39:26
     * */
    public function AdminFlyUserRefereeManyShelfState($fpAdminId){
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
     * 函数名称：用户推荐奖励:管理员:记录永久删除
     * 函数调用：ObjFlyUserRefereeMany() -> AdminFlyUserRefereeManyDelete($fpAdminId)
     * 创建时间：2020-07-21 18:39:26
     * */
    public function AdminFlyUserRefereeManyDelete($fpAdminId){
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
     * 函数调用：ObjFlyUserRefereeMany() -> GetTableName()
     * 创建时间：2020-07-21 18:39:26
     * */
    public function GetTableName(){
        return self::$tableName;
    }
    
    /**
     * 函数名称：获取类描述
     * 函数调用：ObjFlyUserRefereeMany() -> GetClassDescript()
     * 创建时间：2020-07-21 18:39:26
     * */
    public function GetClassDescript(){
        return self::$classDescript;
    }
    
    /**
     * 函数名称：获取数据表字段
     * 函数调用：ObjFlyUserRefereeMany() -> GetTableField()
     * 创建时间：2020-07-21 18:39:26
     * */
    public function GetTableField(){
        return MIndexDBTableFields(JsonObj(JsonKeyValue("table_name", self::$tableName)));
    }
    
    /**
     * 函数名称：类数据表创建
     * 函数调用：ObjFlyUserRefereeMany() -> OprationCreateTable()
     * 创建时间：2020-07-21 18:39:26
     * */
    public function OprationCreateTable(){
        $sql = "CREATE TABLE `fly_user_referee_many` (  `id` bigint(20) NOT NULL AUTO_INCREMENT COMMENT '表ID',  `onlyKey` varchar(36) DEFAULT NULL COMMENT '唯一Key',  `descript` varchar(36) DEFAULT NULL COMMENT '记录描述',  `indexNumber` int(11) DEFAULT '-1' COMMENT '序号',  `updateTime` timestamp NULL DEFAULT NULL COMMENT '修改时间',  `addTime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '添加时间',  `shelfState` varchar(36) DEFAULT 'true' COMMENT '上下架状态',  `state` varchar(36) DEFAULT 'STATE_NORMAL' COMMENT '记录状态',  `userId` int(11) DEFAULT NULL COMMENT '用户ID',  `refereeUserId` int(11) DEFAULT NULL COMMENT '推荐用户ID',  `money` decimal(10,2) DEFAULT NULL COMMENT '奖励金额',  `moneyDescript` varchar(36) DEFAULT NULL COMMENT '奖励备注',  PRIMARY KEY (`id`)) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='用户推荐奖励'";
        DBHelper::DataSubmit($sql, "");
        $vTableCheck = DBMySQLServiceJson::GetDBTableCheck('{"table_name":"'.self::$tableName.'"}');
        if(JudgeJsonTrue($vTableCheck)){
            return JsonInforTrue("创建成功", self::$tableName);
        }
        return JsonInforFalse("创建失败", self::$tableName);
    }
    
    /**
     * 函数名称：类数据表基础字段检测
     * 函数调用：ObjFlyUserRefereeMany() -> OprationTableFieldBaseCheck()
     * 创建时间：2020-07-21 18:39:26
     * */
    public function OprationTableFieldBaseCheck(){
        return DBMySQLServiceJson::OprationFieldBaseCheck(JsonObj(JsonKeyValue("table_name", self::$tableName)));
    }
    
    
    
    
}
