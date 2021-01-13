<?php

/**------------------------------------*
  * 作者：shark
  * 创建时间：2020-02-25 11:55:41
  * Fly编码：1582602941894FLY986341
  * 类对象名：ObjFlyRbacRoleView()
  * ------------------------------------ */

//引入区

class FlyClassRbacRoleView{


    //---------- 类成员（member） ----------

    //类描述
    public static $classDescript = "RBAC角色界面";
    //类数据表名
    public static $tableName = "fly_rbac_role_view";


    //---------- 私有方法（private） ----------

    //---------- 游客方法（visitor） ----------

    /**
     * 函数名称：RBAC角色界面:游客:记录查询
     * 函数调用：ObjFlyRbacRoleView() -> VisitorFlyRbacRoleViewPaging()
     * 创建时间：2020-02-25 11:55:41
     * */
    public function VisitorFlyRbacRoleViewPaging(){

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
        //$vDataField = "id,onlyKey,descript,indexNumber,updateTime,addTime,shelfState,state,roleId,roleAccessPage,selector,operationType,operationWeight";

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
     * 函数名称：RBAC角色界面:系统:记录添加
     * 函数调用：ObjFlyRbacRoleView() -> SystemFlyRbacRoleViewAdd
     * 创建时间：2020-02-25 11:55:41
     * */
    public function SystemFlyRbacRoleViewAdd($fpRoleId,$fpRoleAccessPage,$fpSelector,$fpOperationType,$fpOperationWeight){

        //--- Json组合区 ---
        //Json数组
        $jsonKeyValueArray = array(
            "table_name" => self::$tableName,
            "insert_field" => "descript,shelfState,roleId,roleAccessPage,selector,operationType,operationWeight",
            "descript" => self::$classDescript,
            "shelfstate" => "false",
            "roleid" => $fpRoleId,
            "roleaccesspage" => $fpRoleAccessPage,
            "selector" => $fpSelector,
            "operationtype" => $fpOperationType,
            "operationweight" => $fpOperationWeight,
            "key_field" => "roleId",
        );
        //执行:添加
        $vJsonResult = MIndexDataInsert(JsonHandleArray($jsonKeyValueArray));
        return $vJsonResult;
    }


    //---------- 用户方法（user） ----------

    /**
     * 函数名称：RBAC角色界面:用户:记录查询
     * 函数调用：ObjFlyRbacRoleView() -> UserFlyRbacRoleViewPaging($fpUserId)
     * 创建时间：2020-02-25 11:55:41
     * */
    public function UserFlyRbacRoleViewPaging($fpUserId){

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
        //$vDataField = "id,onlyKey,descript,indexNumber,updateTime,addTime,shelfState,state,roleId,roleAccessPage,selector,operationType,operationWeight";

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
     * 函数名称：RBAC角色界面:管理员:记录添加
     * 函数调用：ObjFlyRbacRoleView() -> AdminFlyRbacRoleViewAdd($fpAdminId)
     * 创建时间：2020-02-25 11:55:41
     * */
    public function AdminFlyRbacRoleViewAdd($fpAdminId){


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

	    //参数:角色ID:roleId
	    $pRoleId = GetParameterNoCode("roleid",$json);
	    if(!JudgeRegularFont($pRoleId)){return JsonModelParameterException("roleid", $pRoleId, 36, "内容格式错误", __LINE__);}

	    //参数:角色请求页面:roleAccessPage
	    $pRoleAccessPage = GetParameterNoCode("roleaccesspage",$json);
	    if(!JudgeRegularUrl($pRoleAccessPage)){return JsonModelParameterException("roleaccesspage", $pRoleAccessPage, 128, "内容格式错误", __LINE__);}

	    //参数:选择器:selector
	    $pSelector = GetParameterNoCode("selector",$json);
	    if(!JudgeRegularSelector($pSelector)){return JsonModelParameterException("selector", $pSelector, 64, "内容格式错误", __LINE__);}

	    //参数:操作类型:operationType
	    $pOperationType = GetParameterNoCode("operationtype",$json);
	    if(!JudgeRegularLetter($pOperationType)){return JsonModelParameterException("operationtype", $pOperationType, 36, "内容格式错误", __LINE__);}

	    //参数:操作权重:operationWeight
	    $pOperationWeight = GetParameterNoCode("operationweight",$json);
	    if(!JudgeRegularInt($pOperationWeight)){return JsonModelParameterException("operationweight", $pOperationWeight, 11, "值必须是整数", __LINE__);}

        //--- Json组合区 ---
        //Json数组
        $jsonKeyValueArray = array(
            "table_name" => self::$tableName,
            "insert_field" => "descript,shelfState,roleId,roleAccessPage,selector,operationType,operationWeight",
            "descript" => self::$classDescript,
            "shelfstate" => $pShelfState,
            "roleid" => $pRoleId,
            "roleaccesspage" => $pRoleAccessPage,
            "selector" => $pSelector,
            "operationtype" => $pOperationType,
            "operationweight" => $pOperationWeight,
            "key_field" => "roleId,roleAccessPage,selector",
            "update_field" => "roleAccessPage,selector,operationType,operationWeight",
            "update_value" => "{$pRoleAccessPage},{$pSelector},{$pOperationType},{$pOperationWeight}",
            "where_field" => "roleId,roleAccessPage,selector",
            "where_value" => "{$pRoleId},{$pRoleAccessPage},{$pSelector}",
        );
        //执行:添加
        $vJsonResult = MIndexDataInsert(JsonHandleArray($jsonKeyValueArray));
        return $vJsonResult;
    }


    /**
     * 函数名称：RBAC角色界面:管理员:记录查询
     * 函数调用：ObjFlyRbacRoleView() -> AdminFlyRbacRoleViewPaging($fpAdminId)
     * 创建时间：2020-02-25 11:55:41
     * */
    public function AdminFlyRbacRoleViewPaging($fpAdminId){

        //--- 参数限制区 ---
        $vParameterArray = GetArray(FLY_PARAMETER_LIMIT, ",");
        $bParameterJudge = JudgeParameterLimit($vParameterArray);
        if(JudgeJsonFalse($bParameterJudge)){return $bParameterJudge;}

        //--- 数据预定义 ---
        $json = "";

        //--- 参数获取区 ---
        $pPage = GetParameterNoCode("page","");     //参数:页码:page
        $pLimit = GetParameterNoCode("limit","");   //参数:条数:limit

        //参数:角色ID:roleId
        $pRoleId = GetParameterNoCode("roleid",$json);
        
        //参数:角色请求页面:roleAccessPage
        $pRoleAccessPage = GetParameterNoCode("roleaccesspage",$json);
        
        //参数:组字段:boolean
        $vWhereField = "";
        $vWhereValue = "";
        $pGroupByBool = GetParameterNoCode("group_by_bool",$json);
        if($pGroupByBool=="true"){
            $pGroupByBool = "roleAccessPage";
            if(!JudgeRegularFont($pRoleId)){return JsonModelParameterException("roleid", $pRoleId, 36, "内容格式错误", __LINE__);}
            $vWhereField = "roleId";
            $vWhereValue = $pRoleId;
        }else{
            $pGroupByBool = "";
            if(!JudgeRegularUrl($pRoleAccessPage)){return JsonModelParameterException("roleaccesspage", $pRoleAccessPage, 128, "内容格式错误", __LINE__);}
            $vWhereField = "roleAccessPage";
            $vWhereValue = $pRoleAccessPage;
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
        //$vDataField = "id,onlyKey,descript,indexNumber,updateTime,addTime,shelfState,state,roleId,roleAccessPage,selector,operationType,operationWeight";

        //--- Json组合区 ---
        $jsonKeyValueArray = array(
        	"table_name" => self::$tableName,
        	"data_field" => $vDataField,
        	"where_field" => $vWhereField,
        	"where_value" => $vWhereValue,
        	"page" => $pPage,
        	"limit" => $pLimit,
        	"like_field" => $pLikeField,
        	"like_key" => $pLikeKey,
        	"group_by" => $pGroupByBool,
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
     * 函数名称：RBAC角色界面:管理员:页面事件查询
     * 函数调用：ObjFlyRbacRoleView() -> AdminFlyRbacRoleViewEventPaging($fpAdminId)
     * 创建时间：2020-02-25 11:55:41
     * 测试用例：2020-02-25 21:24:05
     * 测试预期：当用户请求页面时，请求其下角色界面的显示情况，按照元素选择器名称降序、权重降序、添加时间降序的方式获取元素事件Json结果
     * 数据库情况：
     *      id>addTime>roleId>roleAccessPage>selector>operationType>operationWeight
     *      9>2020-02-25 19:22:48>2>/view/page/admin/admin.html>#adminName>HIDE>300
     *      11>2020-02-25 19:30:29>3>/view/page/admin/admin.html>#adminName>HIDE>200
     *      12>2020-02-25 21:20:58>2>/view/page/admin/admin.html>#adminId>HIDE>100
     *      13>2020-02-25 21:21:02>3>/view/page/admin/admin.html>#adminId>HIDE>200
     *      14>2020-02-25 21:21:08>2>/view/page/admin/admin.html>#adminNick>HIDE>200
     *      15>2020-02-25 21:21:10>3>/view/page/admin/admin.html>#adminNick>HIDE>200
     * 测试参数：line=interface&method=adminflyrbacrolevieweventpaging&roleaccesspage=/view/page/admin/admin.html
     * 测试结果：{"selector":"#adminNick","operationType":"SHOW","operationWeight":"200"},{"selector":"#adminName","operationType":"HIDE","operationWeight":"300"},{"selector":"#adminId","operationType":"SHOW","operationWeight":"200"}
     * */
    public function AdminFlyRbacRoleViewEventPaging($fpAdminId){
        
        $json = "";
        
        $vAccessPath = $_SERVER['PHP_SELF'];
        
        //参数:角色请求页面:roleAccessPage
        $pRoleAccessPage = GetParameterNoCode("roleaccesspage",$json);
        if(!JudgeRegularUrl($pRoleAccessPage)){return JsonModelParameterException("roleaccesspage", $pRoleAccessPage, 128, "内容格式错误", __LINE__);}
        
        $tableName = "fly_rbac_role_view";
        $vSql = "SELECT selector,operationType,operationWeight FROM {$tableName} WHERE roleAccessPage='{$pRoleAccessPage}' AND roleId IN(SELECT a.roleId FROM fly_rbac_role_user a LEFT JOIN fly_rbac_role b ON a.roleId = b.id WHERE a.userAccessPath='{$vAccessPath}' AND a.userId = '{$fpAdminId}' AND (a.overTime IS NULL OR a.overTime>NOW()) AND b.shelfState='true') ORDER BY selector DESC,operationWeight DESC,addTime DESC";
        $list = DBHelper::DataList($vSql, null, ["selector","operationType","operationWeight"]);
        if(IsNull($list)){
            return JsonModelDataNull("记录数为0", $tableName);
        }
        $vListObj = GetJsonObject($list);
        $vListResult = ""; 
        $vListTimes = 0;
        for($i=0;$i<sizeof($vListObj);$i++){
            $vMember = $vListObj[$i];
            $vSelector = $vMember -> selector;
            $vOperationType = $vMember -> operationType;
            $vOperationWeight = $vMember -> operationWeight;
            if($i==0){
                $vListTimes += 1;
                $vListResult = JsonObj(JsonKeyValue("selector", $vSelector).','.JsonKeyValue("operationType", $vOperationType).','.JsonKeyValue("operationWeight", $vOperationWeight)).",";
            }else if($i>0){
                $vUpMember = $vListObj[$i-1];
                $vUpSelector = $vUpMember -> selector;
                if($vSelector==$vUpSelector){
                    continue;
                }else{
                    $vListTimes += 1;
                    $vListResult .= JsonObj(JsonKeyValue("selector", $vSelector).','.JsonKeyValue("operationType", $vOperationType).','.JsonKeyValue("operationWeight", $vOperationWeight)).",";
                }
            }
        }
        if(IsNull($vListResult)){
            $vListResult = JsonArray(HandleStringDeleteLast($vListResult));
        }
        return JsonModelSelectDataHave("有记录", "{$tableName}:{$fpAdminId}", $vListTimes, $vListResult);
    }


    /**
     * 函数名称：RBAC角色界面:管理员:记录修改
     * 函数调用：ObjFlyRbacRoleView() -> AdminFlyRbacRoleViewSet($fpAdminId)
     * 创建时间：2020-02-25 11:55:41
     * */
    public function AdminFlyRbacRoleViewSet($fpAdminId){

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

	    //参数:角色ID:roleId
	    $pRoleId = GetParameterNoCode("roleid",$json);
	    if(!IsNull($pRoleId)&&!JudgeRegularFont($pRoleId)){return JsonModelParameterException("roleid", $pRoleId, 36, "内容格式错误", __LINE__);}

	    //参数:角色请求页面:roleAccessPage
	    $pRoleAccessPage = GetParameterNoCode("roleaccesspage",$json);
	    if(!IsNull($pRoleAccessPage)&&!JudgeRegularFont($pRoleAccessPage)){return JsonModelParameterException("roleaccesspage", $pRoleAccessPage, 128, "内容格式错误", __LINE__);}

	    //参数:选择器:selector
	    $pSelector = GetParameterNoCode("selector",$json);
	    if(!IsNull($pSelector)&&!JudgeRegularFont($pSelector)){return JsonModelParameterException("selector", $pSelector, 64, "内容格式错误", __LINE__);}

	    //参数:操作类型:operationType
	    $pOperationType = GetParameterNoCode("operationtype",$json);
	    if(!IsNull($pOperationType)&&!JudgeRegularFont($pOperationType)){return JsonModelParameterException("operationtype", $pOperationType, 36, "内容格式错误", __LINE__);}

	    //参数:操作权重:operationWeight
	    $pOperationWeight = GetParameterNoCode("operationweight",$json);
	    if(!IsNull($pOperationWeight)&&!JudgeRegularInt($pOperationWeight)){return JsonModelParameterException("operationweight", $pOperationWeight, 11, "值必须是整数", __LINE__);}

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
	    $jsonKeyValueArray = HandleFlyJsonAddUpdateField($jsonKeyValueArray, "roleId", $pRoleId);
	    $jsonKeyValueArray = HandleFlyJsonAddUpdateField($jsonKeyValueArray, "roleAccessPage", $pRoleAccessPage);
	    $jsonKeyValueArray = HandleFlyJsonAddUpdateField($jsonKeyValueArray, "selector", $pSelector);
	    $jsonKeyValueArray = HandleFlyJsonAddUpdateField($jsonKeyValueArray, "operationType", $pOperationType);
	    $jsonKeyValueArray = HandleFlyJsonAddUpdateField($jsonKeyValueArray, "operationWeight", $pOperationWeight);

	    //判断字段值是否为空
	    $vUpdateFieldJudgeInfor = JudgeFlyJsonAddUpdateField($jsonKeyValueArray,"descript,indexnumber,shelfstate,state,roleid,roleaccesspage,selector,operationtype,operationweight");
	    if(JudgeJsonFalse($vUpdateFieldJudgeInfor)){ return $vUpdateFieldJudgeInfor; }

	    //返回结果
	    return MIndexDataUpdate(JsonHandleArray($jsonKeyValueArray));

    }


    /**
     * 函数名称：RBAC角色界面:管理员:记录状态修改
     * 函数调用：ObjFlyRbacRoleView() -> AdminFlyRbacRoleViewSetState($fpAdminId)
     * 创建时间：2020-02-25 11:55:41
     * */
    public function AdminFlyRbacRoleViewSetState($fpAdminId){

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
     * 函数名称：RBAC角色界面:管理员:数据上下架状态修改
     * 函数调用：ObjFlyRbacRoleView() -> AdminFlyRbacRoleViewShelfState($fpAdminId)
     * 创建时间：2020-02-25 11:55:41
     * */
    public function AdminFlyRbacRoleViewShelfState($fpAdminId){
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
     * 函数名称：RBAC角色界面:管理员:记录永久删除
     * 函数调用：ObjFlyRbacRoleView() -> AdminFlyRbacRoleViewDelete($fpAdminId)
     * 创建时间：2020-02-25 11:55:41
     * */
    public function AdminFlyRbacRoleViewDelete($fpAdminId){
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
     * 函数调用：ObjFlyRbacRoleView() -> GetTableName()
     * 创建时间：2020-02-25 11:55:41
     * */
    public function GetTableName(){
        return self::$tableName;
    }

    /**
     * 函数名称：获取类描述
     * 函数调用：ObjFlyRbacRoleView() -> GetClassDescript()
     * 创建时间：2020-02-25 11:55:41
     * */
    public function GetClassDescript(){
        return self::$classDescript;
    }

    /**
     * 函数名称：获取数据表字段
     * 函数调用：ObjFlyRbacRoleView() -> GetTableField()
     * 创建时间：2020-02-25 11:55:41
     * */
    public function GetTableField(){
        return MIndexDBTableFields(JsonObj(JsonKeyValue("table_name", self::$tableName)));
    }

    /**
     * 函数名称：类数据表创建
     * 函数调用：ObjFlyRbacRoleView() -> OprationCreateTable()
     * 创建时间：2020-02-25 11:55:41
     * */
    public function OprationCreateTable(){
        $sql = "CREATE TABLE `fly_rbac_role_view` (  `id` bigint(20) NOT NULL AUTO_INCREMENT COMMENT '表ID',  `onlyKey` varchar(36) DEFAULT NULL COMMENT '唯一Key',  `descript` varchar(36) DEFAULT NULL COMMENT '记录描述',  `indexNumber` int(11) DEFAULT '-1' COMMENT '序号',  `updateTime` timestamp NULL DEFAULT NULL COMMENT '修改时间',  `addTime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '添加时间',  `shelfState` varchar(36) DEFAULT 'true' COMMENT '上下架状态',  `state` varchar(36) DEFAULT 'STATE_NORMAL' COMMENT '记录状态',  `roleId` varchar(36) DEFAULT NULL COMMENT '角色ID',  `roleAccessPage` varchar(128) DEFAULT NULL COMMENT '角色请求页面',  `selector` varchar(64) DEFAULT NULL COMMENT '选择器',  `operationType` varchar(36) DEFAULT NULL COMMENT '操作类型',  `operationWeight` int(11) DEFAULT NULL COMMENT '操作权重',  PRIMARY KEY (`id`) USING BTREE) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='RBAC角色界面'";
        DBHelper::DataSubmit($sql, "");
        $vTableCheck = DBMySQLServiceJson::GetDBTableCheck('{"table_name":"'.self::$tableName.'"}');
        if(JudgeJsonTrue($vTableCheck)){
            return JsonInforTrue("创建成功", self::$tableName);
        }
        return JsonInforFalse("创建失败", self::$tableName);
    }

    /**
     * 函数名称：类数据表基础字段检测
     * 函数调用：ObjFlyRbacRoleView() -> OprationTableFieldBaseCheck()
     * 创建时间：2020-02-25 11:55:41
     * */
    public function OprationTableFieldBaseCheck(){
        return DBMySQLServiceJson::OprationFieldBaseCheck(JsonObj(JsonKeyValue("table_name", self::$tableName)));
    }




}
