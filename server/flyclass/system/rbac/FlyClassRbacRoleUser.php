<?php

/**------------------------------------*
  * 创建时间：2019-05-22 15:45:21
  * ------------------------------------ */

//--- 引用区 ---

class FlyClassRbacRoleUser{
    
    
    //---------- 类成员 ----------
    
    //类描述
    public static $classDescript = "用户角色";
    
    //表名称
    public static $tableName = "fly_rbac_role_user";
    
    //---------- 系统方法 ----------
    
    /**
     * 函数名称：RBAC角色用户:系统:记录添加
     * 函数调用：ObjFlyRbacRoleUser() -> SystemFlyRbacRoleUserAdd
     * 创建时间：2020-02-22 10:31:40
     * */
    public function SystemFlyRbacRoleUserAdd($fpRoleId,$fpUserId,$fpOverTime=null){
    
        $fpOverTime = HandleStringNone($fpOverTime);
        
        //--- Json组合区 ---
        //Json数组
        $jsonKeyValueArray = array(
            "table_name" => self::$tableName,
            "insert_field" => "roleId,userId,overTime",
            "roleid" => $fpRoleId,
            "userid" => $fpUserId,
            "overtime" => $fpOverTime,
            "key_field" => "roleId,userId",
        );
        //执行:添加
        $vJsonResult = MIndexDataInsert(JsonHandleArray($jsonKeyValueArray));
        return $vJsonResult;
    }
    
    
    //---------- 自定义方法 ----------
    
    
    /**
     * 数据分页查询
     * 创建时间：2019-05-22 15:36:23
     * */
    public function FlyClassRbacRoleUserSelect(){
    
        //--- 参数获取区 ---
        //参数：roleId 角色ID
        $roleId = GetParameter("roleid","");
        if(IsNull($roleId)){return JsonModelParameterNull("roleid");}
        //参数：userId 用户ID
        $userId = GetParameter("userid","");
        if(IsNull($userId)){return JsonModelParameterNull("userid");}
        
    
        $tableName = self::$tableName;
        $sql = "SELECT id FROM " .$tableName . " WHERE roleId=" . HandleStringAddQuotation($roleId)  . "AND userId=" . HandleStringAddQuotation($userId) . ";";
        $fieldArray = array("id");
        $list = DBHelper::DataList($sql, null, $fieldArray);
        if(!IsNull($list)){
            return JsonInforTrue("记录已存在", $roleId);
        }
        return JsonInforFalse("记录不存在", $roleId);
    }
    
    /**
     * 数据删除
     * 创建时间：2019-05-22 15:36:23
     * */
    public function FlyClassRbacRoleUserDeleteWhere(){
    
        //--- 参数获取区 ---   
        //参数：roleId 角色ID
        $roleId = GetParameter("roleid","");
        if(IsNull($roleId)){return JsonModelParameterNull("roleid");}
        //参数：userId 用户ID
        $userId = GetParameter("userid","");
        if(IsNull($userId)){return JsonModelParameterNull("userid");}
        
    
        //Json数组
        $jsonKeyValueArray = array(
            "table_name" => self::$tableName,
            "where_field" => "roleId,userId",
            "where_value" => "{$roleId},{$userId}",
        );
        //组合Json
        $json = JsonHandleArray($jsonKeyValueArray);
        //返回结果
        return MIndexDataDelete($json);
    }    
    

    //--- 基础方法 ---

    /**
     * 获取数据表名称
     * 创建时间：2019-06-03 17:28:49
     * */
    public function GetTableName(){
        return self::$tableName;
    }
    
    /**
     * 获取类描述
     * 创建时间：2019-06-03 17:28:49
     * */
    public function GetClassDescript(){
        return self::$classDescript;
    }
    
    /**
     * 函数名称：RBAC角色用户:管理员:记录添加
     * 函数调用：ObjFlyRbacRoleUser() -> AdminFlyRbacRoleUserAdd($fpAdminId)
     * 创建时间：2020-02-23 12:51:57
     * */
    public function AdminFlyRbacRoleUserAdd($fpAdminId){


        //--- 参数限制区 ---
        $vParameterArray = GetArray(FLY_PARAMETER_LIMIT, ",");
        $bParameterJudge = JudgeParameterLimit($vParameterArray);
        if(JudgeJsonFalse($bParameterJudge)){return $bParameterJudge;}

        //--- 变量预定义 ---
        $json="";

        //--- 参数获取区 ---

	    //参数:用户请求路径:userAccessPath
	    $pUserAccessPath = GetParameterNoCode("useraccesspath",$json);
	    if(!JudgeRegularUrl($pUserAccessPath)){return JsonModelParameterException("useraccesspath", $pUserAccessPath, 36, "内容格式错误", __LINE__);}

	    //参数:角色ID:roleId
	    $pRoleId = GetParameterNoCode("roleid",$json);
	    if(!JudgeRegularNumber($pRoleId)){return JsonModelParameterException("roleid", $pRoleId, 36, "内容格式错误", __LINE__);}

	    //参数:用户ID:userId
	    $pUserId = GetParameterNoCode("userid",$json);
	    if(!JudgeRegularNumber($pUserId)){return JsonModelParameterException("userid", $pUserId, 36, "内容格式错误", __LINE__);}

	    //参数:到期时间:overTime
	    $pOverTime = GetParameterNoCode("overtime",$json);
	    if(IsNull($pOverTime)){
	        $pOverTime = "none";
	    }else{
	        if(!JudgeRegularDate($pOverTime)){return JsonModelParameterException("overtime", $pOverTime, 0, "日期格式错误", __LINE__);}    
	    }

        //--- Json组合区 ---
        //Json数组
        $jsonKeyValueArray = array(
            "table_name" => self::$tableName,
            "insert_field" => "userAccessPath,roleId,userId,overTime",
            "useraccesspath" => $pUserAccessPath,
            "roleid" => $pRoleId,
            "userid" => $pUserId,
            "overtime" => $pOverTime,
            "key_field" => "roleId,userId",
        );
        //执行:添加
        $vJsonResult = MIndexDataInsert(JsonHandleArray($jsonKeyValueArray));
        return $vJsonResult;
    }
    
    
    /**
     * 函数名称：RBAC角色用户:管理员:记录查询
     * 函数调用：ObjFlyRbacRoleUser() -> AdminFlyRbacRoleUserPaging($fpAdminId)
     * 创建时间：2020-02-23 12:51:58
     * */
    public function AdminFlyRbacRoleUserPaging($fpAdminId){
    
        //--- 参数限制区 ---
        $vParameterArray = GetArray(FLY_PARAMETER_LIMIT, ",");
        $bParameterJudge = JudgeParameterLimit($vParameterArray);
        if(JudgeJsonFalse($bParameterJudge)){return $bParameterJudge;}
    
        //--- 数据预定义 ---
        $json = "";
    
        //--- 参数获取区 ---
        $pPage = "1";     //参数:页码:page
        $pLimit = "1000";   //参数:条数:limit
    
        //参数:用户请求路径:userAccessPath
        $pUserAccessPath = GetParameterNoCode("useraccesspath",$json);
        if(!JudgeRegularUrl($pUserAccessPath)){return JsonModelParameterException("useraccesspath", $pUserAccessPath, 36, "内容格式错误", __LINE__);}
        
        //参数:用户id:userId
        $pUserId = GetParameterNoCode("userid",$json);
        if(!JudgeRegularNumber($pUserId)){return JsonModelParameterException("userid", $pUserId, 36, "内容格式错误", __LINE__);}
        
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
        $vDataField = "id,addTime,roleId,userId,overTime";
    
        //--- Json组合区 ---
        $jsonKeyValueArray = array(
            "table_name" => self::$tableName,
            "data_field" => $vDataField,
            "where_field" => "userAccessPath,userId",
            "where_value" => "{$pUserAccessPath},{$pUserId}",
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
     * 函数名称：RBAC角色用户:管理员:记录状态修改
     * 函数调用：ObjFlyRbacRoleUser() -> AdminFlyRbacRoleUserSetState($fpAdminId)
     * 创建时间：2020-02-23 12:51:58
     * */
    public function AdminFlyRbacRoleUserSetState($fpAdminId){
    
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
     * 函数名称：RBAC角色用户:管理员:数据上下架状态修改
     * 函数调用：ObjFlyRbacRoleUser() -> AdminFlyRbacRoleUserShelfState($fpAdminId)
     * 创建时间：2020-02-23 12:51:58
     * */
    public function AdminFlyRbacRoleUserShelfState($fpAdminId){
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
     * 函数名称：RBAC角色用户:管理员:记录永久删除
     * 函数调用：ObjFlyRbacRoleUser() -> AdminFlyRbacRoleUserDelete($fpAdminId)
     * 创建时间：2020-02-23 12:51:58
     * */
    public function AdminFlyRbacRoleUserDelete($fpAdminId){
        //--- 参数限制区 ---
        $vParameterArray = GetArray(FLY_PARAMETER_LIMIT, ",");
        $bParameterJudge = JudgeParameterLimit($vParameterArray);
        if(JudgeJsonFalse($bParameterJudge)){return $bParameterJudge;}
    
        //--- 变量预定义 ---
        $json="";		//Json参数
    
        //--- 参数获取区 ---
        //参数:用户请求路径:userAccessPath
	    $pUserAccessPath = GetParameterNoCode("useraccesspath",$json);
	    if(!JudgeRegularUrl($pUserAccessPath)){return JsonModelParameterException("useraccesspath", $pUserAccessPath, 36, "内容格式错误", __LINE__);}

	    //参数:角色ID:roleId
	    $pRoleId = GetParameterNoCode("roleid",$json);
	    if(!JudgeRegularNumber($pRoleId)){return JsonModelParameterException("roleid", $pRoleId, 36, "内容格式错误", __LINE__);}

	    //参数:用户ID:userId
	    $pUserId = GetParameterNoCode("userid",$json);
	    if(!JudgeRegularNumber($pUserId)){return JsonModelParameterException("userid", $pUserId, 36, "内容格式错误", __LINE__);}
        
    
        //Json数组
        $jsonKeyValueArray = array(
            "table_name" => self::$tableName,
            "where_field" => "userAccessPath,roleId,userId",
            "where_value" => "{$pUserAccessPath},{$pRoleId},{$pUserId}",
        );
        //执行:删除
        $vJsonResult = MIndexDataDelete(JsonHandleArray($jsonKeyValueArray));
        return $vJsonResult;
    }
    


    /**
     * 数据分页查询
     * 创建时间：2019-05-22 15:45:21
     * */
    public function FlyRbacRoleUserPaging(){
        return ServiceTableDataPaging(self::$tableName,"id,onlyKey,fatherId,typeName,groupName,roleId,userId,overTime,indexNumber,updateTime,addTime,state");
    }
    
    /**
     * 数据分页查询UserId
     * 创建时间：2019-08-27 17:34:48
     * */
    public function FlyRbacRoleUserIdIn(){
        
        $json = "";
        
        //参数：roleId
        $roleId = GetParameter("roleid",$json);
        if(IsNull($roleId)){return JsonModelParameterNull("roleid");}
        //参数：$userId
        $userId = GetParameter("userid",$json);
        if(IsNull($userId)){return JsonModelParameterNull("userid");}
         
        $vSql = "SELECT userId FROM ".self::$tableName." WHERE roleId = '{$roleId}' AND userId IN({$userId});";
        $vResult = DBHelper::DataList($vSql, null, array("userId"));
        if(IsNull($vResult)){
            return JsonModelDataString('""',"0");
        }
        return JsonModelDataString($vResult,sizeof(GetJsonObject($vResult)));
    }    
    
    
    /**
     * 用户账户角色查询
     * 创建时间：2019-08-27 17:34:48
     * */
    public function FlyRbacRoleUserIdSelect(){
    
        $json = "";
    
        //参数：$userId
        $userId = GetParameter("userid",$json);
        if(IsNull($userId)){return JsonModelParameterNull("userid");}
         
        $vSql = "SELECT companyId,companyName,departmentId,departmentName,roleName,roleId,roleDescript,a.addTime,b.userId FROM fly_rbac_role AS a,fly_rbac_role_user AS b WHERE a.id = b.roleId AND userId='{$userId}';";
        $vResult = DBHelper::DataList($vSql, null, array("companyId","companyName","departmentId","departmentName","roleName","roleId","roleDescript","addTime","userId"));
        if(IsNull($vResult)){
            return JsonModelDataString('""',"0");
        }
        return JsonModelDataString($vResult,sizeof(GetJsonObject($vResult)));
    }    

    
    /**
     * 数据删除
     * 创建时间：2019-05-22 15:36:23
     * */
    public function FlyRbacRoleUserIdDeleteWhere(){
    
        //--- 参数获取区 ---
        //参数：roleId
        $roleId = GetParameter("roleid","");
        if(IsNull($roleId)){return JsonModelParameterNull("roleid");}
        //参数：userId
        $userId = GetParameter("userid","");
        if(IsNull($userId)){return JsonModelParameterNull("userid");}
    
        //Json数组
        $jsonKeyValueArray = array(
            "table_name" => self::$tableName,
            "where_field" => "roleId,userId",
            "where_value" => "{$roleId},{$userId}",
        );
        //组合Json
        $json = JsonHandleArray($jsonKeyValueArray);
        //返回结果
        return MIndexDataDelete($json);
    }

    /**
     * 数据修改
     * 创建时间：2019-05-22 15:45:21
     * */
    public function FlyRbacRoleUserSet(){
        return ServiceTableDataSet(self::$tableName,"roleId,userId,overTime");
    }


    /**
     * 数据删除
     * 创建时间：2019-05-22 15:45:21
     * */
    public function FlyRbacRoleUserDelete(){
        return ServiceTableDataDelete(self::$tableName);
    }


}
