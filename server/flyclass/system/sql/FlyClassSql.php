<?php

/**------------------------------------*
  * 作者：shark
  * 创建时间：2020-04-04 22:07:34
  * Fly编码：1586009254816FLY945155
  * 类对象名：ObjFlySql()
  * ------------------------------------ */

//引入区

class FlyClassSql{


    //---------- 类成员（member） ----------

    //类描述
    public static $classDescript = "SQL";
    //类数据表名
    public static $tableName = "fly_sql";


    //---------- 私有方法（private） ----------

    //---------- 游客方法（visitor） ----------

    /**
     * 函数名称：SQL:游客:记录查询
     * 函数调用：ObjFlySql() -> VisitorFlySqlPaging()
     * 创建时间：2020-04-04 22:07:34
     * */
    public function VisitorFlySqlPaging(){

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
        //$vDataField = "id,onlyKey,descript,indexNumber,updateTime,addTime,shelfState,state,adminId,relationId,sqlString,sqlDescript";

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
     * 函数名称：SQL:系统:记录添加
     * 函数调用：ObjFlySql() -> SystemFlySqlAdd
     * 创建时间：2020-04-04 22:07:34
     * */
    public function SystemFlySqlAdd($fpAdminId,$fpRelationId,$fpSqlString,$fpSqlDescript){

        //--- Json组合区 ---
        //Json数组
        $jsonKeyValueArray = array(
            "table_name" => self::$tableName,
            "insert_field" => "descript,shelfState,adminId,relationId,sqlString,sqlDescript",
            "descript" => self::$classDescript,
            "shelfstate" => "false",
            "adminid" => $fpAdminId,
            "relationid" => $fpRelationId,
            "sqlstring" => $fpSqlString,
            "sqldescript" => $fpSqlDescript,
            "key_field" => "adminId,relationId",
        );
        //执行:添加
        $vJsonResult = MIndexDataInsert(JsonHandleArray($jsonKeyValueArray));
        return $vJsonResult;
    }


    //---------- 用户方法（user） ----------

    /**
     * 函数名称：SQL:用户:记录查询
     * 函数调用：ObjFlySql() -> UserFlySqlPaging($fpUserId)
     * 创建时间：2020-04-04 22:07:34
     * */
    public function UserFlySqlPaging($fpUserId){

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
        //$vDataField = "id,onlyKey,descript,indexNumber,updateTime,addTime,shelfState,state,adminId,relationId,sqlString,sqlDescript";

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
     * 函数名称：SQL:管理员:记录添加
     * 函数调用：ObjFlySql() -> AdminFlySqlAdd($fpAdminId)
     * 创建时间：2020-04-04 22:07:34
     * */
    public function AdminFlySqlAdd($fpAdminId){


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

	    //参数:id
	    $pId = GetParameterNoCode("id","");
	    if(!IsNull($pId)&&!JudgeRegularIntRight($pId)){return JsonModelParameterException("id", $pId, 20, "值必须是正整数", __LINE__);}
	    //参数:SQL:sqlString
	    $pSqlString = GetParameterNoCode("sqlstring",$json);
	    if(IsNull($pSqlString)){ return JsonModelParameterNull("sqlstring"); }
	    $pSqlString = HandleStringFlyHtmlEncode($pSqlString);
	    //参数:SQL描述:sqlDescript
	    $pSqlDescript = GetParameterNoCode("sqldescript",$json);
	    if(!JudgeRegularFont($pSqlDescript)){return JsonModelParameterException("sqldescript", $pSqlDescript, 64, "内容格式错误", __LINE__);}

        //--- Json组合区 ---
        //Json数组
        $jsonKeyValueArray = array(
            "table_name" => self::$tableName,
            "insert_field" => "descript,shelfState,adminId,sqlString,sqlDescript",
            "descript" => self::$classDescript,
            "shelfstate" => $pShelfState,
            "adminid" => $fpAdminId,
            "sqlstring" => $pSqlString,
            "sqldescript" => $pSqlDescript,
            //Update
            "where_field" => "id",
            "where_value" => $pId,
            "update_field" => "sqlString,sqlDescript",
            "update_value" => "{$pSqlString},{$pSqlDescript}",
        );
        //执行:添加
        $vJsonResult = MIndexDataInsert(JsonHandleArray($jsonKeyValueArray));
        return $vJsonResult;
    }


    /**
     * 函数名称：SQL:管理员:记录查询
     * 函数调用：ObjFlySql() -> AdminFlySqlPaging($fpAdminId)
     * 创建时间：2020-04-04 22:07:34
     * */
    public function AdminFlySqlPaging($fpAdminId){

        //--- 参数限制区 ---
        $vParameterArray = GetArray(FLY_PARAMETER_LIMIT, ",");
        $bParameterJudge = JudgeParameterLimit($vParameterArray);
        if(JudgeJsonFalse($bParameterJudge)){return $bParameterJudge;}

        //--- 数据预定义 ---
        $json = "";

        //--- 参数获取区 ---
        $pPage = GetParameterNoCode("page","");     //参数:页码:page
        $pLimit = GetParameterNoCode("limit","");   //参数:条数:limit

        //参数：Relation
        $pRelationId = GetParameterNoCode("relation_id","");
        if(!IsNull($pRelationId)&&!JudgeRegularNumber($pRelationId)){ return JsonModelParameterException("relation_id", $pRelationId, 20, "值必须是正整数", __LINE__); }
        $vWhereSon = "";
        if(!IsNull($pRelationId)){ $vWhereSon = "id !='{$pRelationId}'"; }
        
        //参数：数据类型
        $pDataType = GetParameterNoCode("data_type","");
        if(!IsNull($pRelationId)&&$pDataType=="LIST"){
            //获取:fly_sql:关联ID
            $vSql = "SELECT relationId FROM fly_sql WHERE id=? LIMIT 0,1;";
            $vRelationId = DBHelper::DataString($vSql, [$pRelationId]);
            if(IsNull($vRelationId)){ return JsonModelDataNull("记录数为0", "不存在关联数据"); }
            $vRelationId = HandleStringDeleteFirst($vRelationId);
            $vRelationId = HandleStringDeleteLast($vRelationId);
            $vRelationId = HandleStringReplace($vRelationId, "-", ",");
            $vWhereSon = "id IN({$vRelationId})";
        }else if(!IsNull($pRelationId)&&$pDataType=="BELIST"){
            $vWhereSon = "relationId REGEXP '-{$pRelationId}-'";
        }
        
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
        //$vDataField = "id,onlyKey,descript,indexNumber,updateTime,addTime,shelfState,state,adminId,relationId,sqlString,sqlDescript";

        //--- Json组合区 ---
        $jsonKeyValueArray = array(
        	"table_name" => self::$tableName,
        	"data_field" => $vDataField,
        	"where_field" => "",
        	"where_value" => "",
            "where_son" => $vWhereSon,
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
     * 函数名称：SQL:管理员:记录修改
     * 函数调用：ObjFlySql() -> AdminFlySqlSet($fpAdminId)
     * 创建时间：2020-04-04 22:07:34
     * */
    public function AdminFlySqlSet($fpAdminId){

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

	    //参数:管理员ID:adminId
	    $pAdminId = GetParameterNoCode("adminid",$json);
	    if(!IsNull($pAdminId)&&!JudgeRegularIntRight($pAdminId)){return JsonModelParameterException("adminid", $pAdminId, 20, "值必须是正整数", __LINE__);}

	    //参数:关联ID:relationId
	    $pRelationId = GetParameterNoCode("relationid",$json);
	    if(!IsNull($pRelationId)&&!JudgeRegularFont($pRelationId)){return JsonModelParameterException("relationid", $pRelationId, 512, "内容格式错误", __LINE__);}

	    //参数:SQL:sqlString
	    $pSqlString = GetParameterNoCode("sqlstring",$json);
	    if(!IsNull($pSqlString)){ $pSqlString = HandleStringAddslashes(HandleStringFlyHtmlEncode($pSqlString)); }

	    //参数:SQL描述:sqlDescript
	    $pSqlDescript = GetParameterNoCode("sqldescript",$json);
	    if(!IsNull($pSqlDescript)&&!JudgeRegularFont($pSqlDescript)){return JsonModelParameterException("sqldescript", $pSqlDescript, 64, "内容格式错误", __LINE__);}

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
	    $jsonKeyValueArray = HandleFlyJsonAddUpdateField($jsonKeyValueArray, "adminId", $pAdminId);
	    $jsonKeyValueArray = HandleFlyJsonAddUpdateField($jsonKeyValueArray, "relationId", $pRelationId);
	    $jsonKeyValueArray = HandleFlyJsonAddUpdateField($jsonKeyValueArray, "sqlString", $pSqlString);
	    $jsonKeyValueArray = HandleFlyJsonAddUpdateField($jsonKeyValueArray, "sqlDescript", $pSqlDescript);

	    //判断字段值是否为空
	    $vUpdateFieldJudgeInfor = JudgeFlyJsonAddUpdateField($jsonKeyValueArray,"descript,indexnumber,shelfstate,state,adminid,relationid,sqlstring,sqldescript");
	    if(JudgeJsonFalse($vUpdateFieldJudgeInfor)){ return $vUpdateFieldJudgeInfor; }

	    //返回结果
	    return MIndexDataUpdate(JsonHandleArray($jsonKeyValueArray));

    }


    /**
     * 函数名称：SQL:管理员:记录状态修改
     * 函数调用：ObjFlySql() -> AdminFlySqlSetState($fpAdminId)
     * 创建时间：2020-04-04 22:07:34
     * */
    public function AdminFlySqlSetState($fpAdminId){

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
     * 函数名称：SQL:管理员:数据上下架状态修改
     * 函数调用：ObjFlySql() -> AdminFlySqlShelfState($fpAdminId)
     * 创建时间：2020-04-04 22:07:34
     * */
    public function AdminFlySqlShelfState($fpAdminId){
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
     * 函数名称：SQL:管理员:记录永久删除
     * 函数调用：ObjFlySql() -> AdminFlySqlDelete($fpAdminId)
     * 创建时间：2020-04-04 22:07:34
     * */
    public function AdminFlySqlDelete($fpAdminId){
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
    
    /**
     * 函数名称：SQL:管理员:关联
     * 函数调用：ObjFlySql() -> AdminFlySqlRelation($fpAdminId)
     * 创建时间：2020-04-05 16:57:00
     * */
    public function AdminFlySqlRelation($fpAdminId){
        
        //参数：id-主ID
        $pId = GetParameterNoCode("id","");
        if(!JudgeRegularIntRight($pId)){return JsonModelParameterInfor("id","参数值不符合规则",20);}
        //参数：relationId-关联ID
        $pRelationId = GetParameterNoCode("relation_id","");
        if(!JudgeRegularIntRight($pRelationId)){return JsonModelParameterInfor("relation_id","参数值不符合规则",20);}
        
        //判断主ID与关联ID是否一致
        if($pId==$pRelationId){
            return JsonInforFalse("两个关联ID不得相同", "{$pId}:{$pRelationId}");
        }
        
        //查询关联ID
        $vSql = "SELECT relationId FROM fly_sql WHERE id=? LIMIT 0,1;";
        $vRelationId = DBHelper::DataString($vSql, [$pId]);
        
        //关联ID组合
        $vRelationIdString = "";
        if(IsNull($vRelationId)){ 
            $vRelationIdString = "-{$pRelationId}-";
        }else{
            $vPos = strpos($vRelationId,"-{$pRelationId}-");
            if($vPos===0||$vPos>0){
                return JsonInforFalse("已存在关联关系", "{$pId}:{$pRelationId}");
            }
            $vRelationIdString = $vRelationId."{$pRelationId}-";
        }
        
        $vSql = "UPDATE fly_sql SET relationId='{$vRelationIdString}' WHERE id='{$pId}'";
        if(DBHelper::DataSubmit($vSql, null)){
            return JsonInforTrue("关联成功", "{$pId}:{$pRelationId}");
        }
        return JsonInforFalse("关联失败", "{$pId}:{$pRelationId}");
        
    }
    
    /**
     * 函数名称：SQL:管理员:删除关联
     * 函数调用：ObjFlySql() -> AdminFlySqlRelationDelete($fpAdminId)
     * 创建时间：2020-04-05 16:57:07
     * */
    public function AdminFlySqlRelationDelete($fpAdminId){
        //参数：id-主ID
        $pId = GetParameterNoCode("id","");
        if(!JudgeRegularIntRight($pId)){return JsonModelParameterInfor("id","参数值不符合规则",20);}
        //参数：relationId-关联ID
        $pRelationId = GetParameterNoCode("relation_id","");
        if(!JudgeRegularIntRight($pRelationId)){return JsonModelParameterInfor("relation_id","参数值不符合规则",20);}
        
        //查询关联ID
        $vSql = "SELECT relationId FROM fly_sql WHERE id=? LIMIT 0,1;";
        $vRelationId = DBHelper::DataString($vSql, [$pId]);
        
        //关联ID组合
        $vRelationIdString = "";
        $vPos = strpos($vRelationId,"-{$pRelationId}-");
        if(!($vPos===0||$vPos>0)){
            return JsonInforFalse("不存在关联关系", "{$pId}:{$pRelationId}");
        }else{
            if($vRelationId=="-{$pRelationId}-"){
                $vRelationIdString = "";
            }else{
                $vRelationIdString = HandleStringReplace($vRelationId, "-{$pRelationId}-", "-");
            }
        }
        
        $vSql = "UPDATE fly_sql SET relationId='{$vRelationIdString}' WHERE id='{$pId}'";
        if(DBHelper::DataSubmit($vSql, null)){
            return JsonInforTrue("取消关联成功", "{$pId}:{$pRelationId}");
        }
        return JsonInforFalse("取消关联失败", "{$pId}:{$pRelationId}");
    
    }


    //---------- 测试方法（test） ----------

    //---------- 基础方法（base） ----------


    /**
     * 函数名称：获取数据表名称
     * 函数调用：ObjFlySql() -> GetTableName()
     * 创建时间：2020-04-04 22:07:34
     * */
    public function GetTableName(){
        return self::$tableName;
    }

    /**
     * 函数名称：获取类描述
     * 函数调用：ObjFlySql() -> GetClassDescript()
     * 创建时间：2020-04-04 22:07:34
     * */
    public function GetClassDescript(){
        return self::$classDescript;
    }

    /**
     * 函数名称：获取数据表字段
     * 函数调用：ObjFlySql() -> GetTableField()
     * 创建时间：2020-04-04 22:07:34
     * */
    public function GetTableField(){
        return MIndexDBTableFields(JsonObj(JsonKeyValue("table_name", self::$tableName)));
    }

    /**
     * 函数名称：类数据表创建
     * 函数调用：ObjFlySql() -> OprationCreateTable()
     * 创建时间：2020-04-04 22:07:34
     * */
    public function OprationCreateTable(){
        $sql = "CREATE TABLE `fly_sql` (  `id` bigint(20) NOT NULL AUTO_INCREMENT COMMENT '表ID',  `onlyKey` varchar(36) DEFAULT NULL COMMENT '唯一Key',  `descript` varchar(36) DEFAULT NULL COMMENT '记录描述',  `indexNumber` int(11) DEFAULT '-1' COMMENT '序号',  `updateTime` timestamp NULL DEFAULT NULL COMMENT '修改时间',  `addTime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '添加时间',  `shelfState` varchar(36) DEFAULT 'true' COMMENT '上下架状态',  `state` varchar(36) DEFAULT 'STATE_NORMAL' COMMENT '记录状态',  `adminId` bigint(20) DEFAULT NULL COMMENT '管理员ID',  `relationId` varchar(512) DEFAULT NULL COMMENT '关联ID',  `sqlString` text COMMENT 'SQL',  `sqlDescript` varchar(64) DEFAULT NULL COMMENT 'SQL描述',  PRIMARY KEY (`id`) USING BTREE) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='SQL'";
        DBHelper::DataSubmit($sql, "");
        $vTableCheck = DBMySQLServiceJson::GetDBTableCheck('{"table_name":"'.self::$tableName.'"}');
        if(JudgeJsonTrue($vTableCheck)){
            return JsonInforTrue("创建成功", self::$tableName);
        }
        return JsonInforFalse("创建失败", self::$tableName);
    }

    /**
     * 函数名称：类数据表基础字段检测
     * 函数调用：ObjFlySql() -> OprationTableFieldBaseCheck()
     * 创建时间：2020-04-04 22:07:34
     * */
    public function OprationTableFieldBaseCheck(){
        return DBMySQLServiceJson::OprationFieldBaseCheck(JsonObj(JsonKeyValue("table_name", self::$tableName)));
    }




}
