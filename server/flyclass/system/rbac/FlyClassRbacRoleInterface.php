<?php

/**------------------------------------*
  * 作者：shark
  * 创建时间：2020-02-19 13:06:50
  * Fly编码：1582088810456FLY362710
  * 类对象名：ObjFlyRbacRoleInterface()
  * ------------------------------------ */

//引入区

class FlyClassRbacRoleInterface{


    //---------- 类成员（member） ----------

    //类描述
    public static $classDescript = "RBAC角色接口";
    //类数据表名
    public static $tableName = "fly_rbac_role_interface";


    //---------- 私有方法（private） ----------

    //---------- 游客方法（visitor） ----------

    //---------- 系统方法（system） ----------

    //---------- 用户方法（user） ----------

    //---------- 管理员方法（admin） ----------

    /**
     * 函数名称：RBAC角色接口:管理员:记录添加
     * 函数调用：ObjFlyRbacRoleInterface() -> AdminFlyRbacRoleInterfaceAdd($fpAdminId)
     * 创建时间：2020-02-19 13:06:50
     * */
    public function AdminFlyRbacRoleInterfaceAdd($fpAdminId){


        //--- 参数限制区 ---
        $vParameterArray = GetArray(FLY_PARAMETER_LIMIT, ",");
        $bParameterJudge = JudgeParameterLimit($vParameterArray);
        if(JudgeJsonFalse($bParameterJudge)){return $bParameterJudge;}

        //--- 变量预定义 ---
        $json="";

        //--- 参数获取区 ---
        //参数:interfaceIndexLine
        $pInterfaceId = "";
        $pInterfacePath = GetParameterNoCode("interfacepath",$json);
        $pInterfaceIndexLine = GetParameterNoCode("interfaceindexline",$json);
        $pInterfaceIndexLineDescript = GetParameterNoCode("interfaceindexlinedescript",$json);
        $pInterfaceIndexMethod = GetParameterNoCode("interfaceindexmethod",$json);
        $pInterfaceIndexMethodDescript = GetParameterNoCode("interfaceindexmethoddescript",$json);
        if(!IsNull($pInterfaceIndexLine)&&!IsNull($pInterfaceIndexMethod)){
            if(!JudgeRegularUrl($pInterfacePath)){return JsonModelParameterException("interfacepath", $pInterfacePath, 36, "接口请求路径格式错误", __LINE__);}
            if(!JudgeRegularLetter($pInterfaceIndexLine)){return JsonModelParameterException("interfaceindexline", $pInterfaceIndexLine, 36, "接口业务线名格式错误", __LINE__);}
            if(!JudgeRegularFont($pInterfaceIndexLineDescript)){return JsonModelParameterException("interfaceindexlinedescript", $pInterfaceIndexLineDescript, 64, "接口业务线描述格式错误", __LINE__);}
            if(!JudgeRegularLetter($pInterfaceIndexMethod)){return JsonModelParameterException("interfaceindexmethod", $pInterfaceIndexMethod, 36, "接口业务线方法名格式错误", __LINE__);}
            if(!JudgeRegularFont($pInterfaceIndexMethodDescript)){return JsonModelParameterException("interfaceindexmethoddescript", $pInterfaceIndexMethodDescript, 64, "接口业务线方法描述格式错误", __LINE__);}
            //获取:fly_interface:表ID
            $vSql = "SELECT id FROM fly_interface WHERE interfaceIndexLine=? AND interfaceIndexMethod=? LIMIT 0,1;";
            $vInterfaceId = DBHelper::DataString($vSql, [$pInterfaceIndexLine,$pInterfaceIndexMethod]);
            //当记录不存在时添加记录
            if(IsNull($vInterfaceId)){ 
                ObjFlyInterface() -> SystemFlyInterfaceAdd($pInterfacePath, $pInterfaceIndexLine, $pInterfaceIndexLineDescript, $pInterfaceIndexMethod, $pInterfaceIndexMethodDescript, "POST、GET", "none");
                //获取:fly_interface:表ID
                $vSql = "SELECT id FROM fly_interface WHERE interfaceIndexLine=? AND interfaceIndexMethod=? LIMIT 0,1;";
                $vInterfaceId = DBHelper::DataString($vSql, [$pInterfaceIndexLine,$pInterfaceIndexMethod]);
            }
            $pInterfaceId = $vInterfaceId;
        }
        
	    //参数:角色ID:roleId
	    $pRoleId = GetParameterNoCode("roleid",$json);
	    if(!JudgeRegularFont($pRoleId)){return JsonModelParameterException("roleid", $pRoleId, 36, "内容格式错误", __LINE__);}

	    //参数:接口ID:interfaceId
	    if(IsNull($pInterfaceId)){
	        $pInterfaceId = GetParameterNoCode("interfaceid",$json);
	    }
	    if(!JudgeRegularFont($pInterfaceId)){return JsonModelParameterException("interfaceid", $pInterfaceId, 36, "内容格式错误", __LINE__);}

	    //参数:接口Key:interfaceKey
	    $pInterfaceKey = GetParameterNoCode("interfacekey",$json);
	    if(!JudgeRegularUrl($pInterfaceKey)){return JsonModelParameterException("interfacekey", $pInterfaceKey, 128, "内容格式错误", __LINE__);}
	    
	    //参数：是否关联
	    $pIsRelation = GetParameterNoCode("isrelation",$json);
	    if(!JudgeRegularState($pIsRelation)){return JsonModelParameterException("isrelation", $pIsRelation, 10, "内容格式错误", __LINE__);}
	    
        //--- Json组合区 ---
        //Json数组
        $jsonKeyValueArray = array(
            "table_name" => self::$tableName,
            "insert_field" => "descript,shelfState,roleId,interfaceId,interfaceKey,isRelation",
            "key_field" => "roleId,interfaceId",
            "update_field" => "isRelation",
            "update_value" => "{$pIsRelation}",
            "where_field" => "roleId,interfaceId",
            "where_value" => "{$pRoleId},{$pInterfaceId}",
            "descript" => self::$classDescript,
            "shelfState" => "true",
            "roleid" => $pRoleId,
            "interfaceid" => $pInterfaceId,
            "interfacekey" => $pInterfaceKey,
            "isRelation" => $pIsRelation,
        );
        //执行:添加
        $vInsertResult = MIndexDataInsert(JsonHandleArray($jsonKeyValueArray));
        if(JudgeJsonTrue($vInsertResult)){
            return JsonInforTrue($pRoleId, $pInterfaceId, $pIsRelation);
        }
        return $vInsertResult;
    }


    /**
     * 函数名称：RBAC角色接口:管理员:记录查询
     * 函数调用：ObjFlyRbacRoleInterface() -> AdminFlyRbacRoleInterfacePaging($fpAdminId)
     * 创建时间：2020-02-19 13:06:50
     * */
    public function AdminFlyRbacRoleInterfacePaging($fpAdminId){

        //--- 参数限制区 ---
        $vParameterArray = GetArray(FLY_PARAMETER_LIMIT, ",");
        $bParameterJudge = JudgeParameterLimit($vParameterArray);
        if(JudgeJsonFalse($bParameterJudge)){return $bParameterJudge;}

        //--- 数据预定义 ---
        $json = "";

        //--- 参数获取区 ---
        $pPage = "1";     //参数:页码:page
        $pLimit = "1000";   //参数:条数:limit
        
        //参数:角色ID:roleId
        $pRoleId = GetParameterNoCode("roleid",$json);
        if(!JudgeRegularNumber($pRoleId)){return JsonModelParameterException("roleid", $pRoleId, 36, "内容格式错误", __LINE__);}
        
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
        //$vDataField = DBMySQLSelect::TableFieldString(self::$tableName);
        $vDataField = "id,shelfState,interfaceId,isRelation";

        //--- Json组合区 ---
        $jsonKeyValueArray = array(
        	"table_name" => self::$tableName,
        	"data_field" => $vDataField,
        	"where_field" => "roleId",
        	"where_value" => "{$pRoleId}",
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

    /**
     * 函数名称：RBAC角色接口:管理员:数据上下架状态修改
     * 函数调用：ObjFlyRbacRoleInterface() -> AdminFlyRbacRoleInterfaceShelfState($fpAdminId)
     * 创建时间：2020-02-19 13:06:50
     * */
    public function AdminFlyRbacRoleInterfaceShelfState($fpAdminId){
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
     * 函数名称：RBAC角色接口:管理员:记录永久删除
     * 函数调用：ObjFlyRbacRoleInterface() -> AdminFlyRbacRoleInterfaceDelete($fpAdminId)
     * 创建时间：2020-02-19 13:06:50
     * */
    public function AdminFlyRbacRoleInterfaceDelete($fpAdminId){
        //--- 参数限制区 ---
        $vParameterArray = GetArray(FLY_PARAMETER_LIMIT, ",");
        $bParameterJudge = JudgeParameterLimit($vParameterArray);
        if(JudgeJsonFalse($bParameterJudge)){return $bParameterJudge;}

        //--- 变量预定义 ---
        $json="";		//Json参数

        //--- 参数获取区 ---
        $pRoleId = GetParameterNoCode("roleid",$json);
        if(!JudgeRegularNumber($pRoleId)){return JsonModelParameterException("roleid", $pRoleId, 36, "内容格式错误", __LINE__);}
        //参数:接口ID:interfaceId
        $pInterfaceId = GetParameterNoCode("interfaceid",$json);
        if(!JudgeRegularFont($pInterfaceId)){return JsonModelParameterException("interfaceid", $pInterfaceId, 36, "内容格式错误", __LINE__);}
        
        //Json数组
        $jsonKeyValueArray = array(
            "table_name" => self::$tableName,
            "where_field" => "roleId,interfaceId",
            "where_value" => "{$pRoleId},{$pInterfaceId}",
        );
        //执行:删除
        $vJsonResult = MIndexDataDelete(JsonHandleArray($jsonKeyValueArray));
        return $vJsonResult;
    }



}
