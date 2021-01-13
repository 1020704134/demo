<?php

/**------------------------------------*
  * 作者：shark
  * 创建时间：2019-12-21 16:57:07
  * Fly编码：1576918627119FLY226544
  * 类对象名：ObjFlyInterfaceService()
  * ------------------------------------ */

//引入区

class FlyClassInterfaceService{


    //---------- 类成员（member） ----------

    //类描述
    public static $classDescript = "业务线接口绑定关系";
    //类数据表名
    public static $tableName = "fly_interface_service";
    //类数据表字段
    public static $tableField = "id,onlyKey,descript,indexNumber,updateTime,addTime,shelfState,state,servicePage,serviceArea,serviceDescript,interfaceDescript,interfacePath,interfaceLine,interfaceLineMethod";


    //---------- 私有方法（private） ----------

    //---------- 游客方法（visitor） ----------

    /**
     * 函数名称：业务线接口绑定关系:游客:记录查询
     * 函数调用：ObjFlyInterfaceService() -> VisitorFlyInterfaceServicePaging()
     * 创建时间：2019-12-21 16:57:06
     * */
    public function VisitorFlyInterfaceServicePaging(){

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
        $pLike = GetParameter("like","");
        $pLiketype = GetParameter("liketype","");
        if(!IsNull($pLiketype)&&!DBMySQLJudge::JudgeTableField(self::$tableName,$pLiketype)){ return JsonInforFalse("搜索字段不存在", $pLiketype); }
        if(!IsNull($pLike)){ $pLike = HandleStringAddslashes($pLike); }

        //字段定义
        $vDataField = DBMySQLSelect::TableFieldString(self::$tableName);
        //$vDataField = "id,onlyKey,descript,indexNumber,updateTime,addTime,shelfState,state,servicePage,serviceArea,serviceDescript,interfaceDescript,interfacePath,interfaceLine,interfaceLineMethod";

        //--- Json组合区 ---
        $jsonKeyValueArray = array(
        	"table_name" => self::$tableName,
        	"data_field" => $vDataField,
        	"where_field" => "shelfState",
        	"where_value" => "true",
        	"page" => $pPage,
        	"limit" => $pLimit,
        	"orderby" => "id:desc",
        	"like_field" => $pLiketype,
        	"like_key" => $pLike,
        );

        //条件字段判断组合
        $jsonKeyValueArray = HandleFlyJsonAddWhereField($jsonKeyValueArray, "id", $pId);
        $jsonKeyValueArray = HandleFlyJsonAddWhereField($jsonKeyValueArray, "state", $pWhereState);
        $jsonKeyValueArray = HandleFlyJsonAddWhereField($jsonKeyValueArray, "shelfState", $pWhereShelfState);
        //返回结果:分页
        return MIndexDataPaging(JsonHandleArray($jsonKeyValueArray));

    }


    //---------- 系统方法（system） ----------

    /**
     * 函数名称：业务线接口绑定关系:系统:记录添加
     * 函数调用：ObjFlyInterfaceService() -> SystemFlyInterfaceServiceAdd
     * 创建时间：2019-12-21 16:57:06
     * */
    public function SystemFlyInterfaceServiceAdd($fpServicePage,$fpServiceArea,$fpServiceDescript,$fpInterfaceDescript,$fpInterfacePath,$fpInterfaceLine,$fpInterfaceLineMethod){

        //--- Json组合区 ---
        //Json数组
        $jsonKeyValueArray = array(
            "table_name" => self::$tableName,
            "insert_field" => "descript,shelfState,servicePage,serviceArea,serviceDescript,interfaceDescript,interfacePath,interfaceLine,interfaceLineMethod",
            "descript" => self::$classDescript,
            "shelfstate" => "false",
            "servicepage" => $fpServicePage,
            "servicearea" => $fpServiceArea,
            "servicedescript" => $fpServiceDescript,
            "interfacedescript" => $fpInterfaceDescript,
            "interfacepath" => $fpInterfacePath,
            "interfaceline" => $fpInterfaceLine,
            "interfacelinemethod" => $fpInterfaceLineMethod,
        );
        //执行:添加
        $vJsonResult = MIndexDataInsert(JsonHandleArray($jsonKeyValueArray));
        return $vJsonResult;
    }


    //---------- 用户方法（user） ----------

    /**
     * 函数名称：业务线接口绑定关系:用户:记录查询
     * 函数调用：ObjFlyInterfaceService() -> UserFlyInterfaceServicePaging($fpUserId)
     * 创建时间：2019-12-21 16:57:06
     * */
    public function UserFlyInterfaceServicePaging($fpUserId){

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
        $pLike = GetParameter("like","");
        $pLiketype = GetParameter("liketype","");
        if(!IsNull($pLiketype)&&!DBMySQLJudge::JudgeTableField(self::$tableName,$pLiketype)){ return JsonInforFalse("搜索字段不存在", $pLiketype); }
        if(!IsNull($pLike)){ $pLike = HandleStringAddslashes($pLike); }

        //字段定义
        $vDataField = DBMySQLSelect::TableFieldString(self::$tableName);
        //$vDataField = "id,onlyKey,descript,indexNumber,updateTime,addTime,shelfState,state,servicePage,serviceArea,serviceDescript,interfaceDescript,interfacePath,interfaceLine,interfaceLineMethod";

        //--- Json组合区 ---
        $jsonKeyValueArray = array(
        	"table_name" => self::$tableName,
        	"data_field" => $vDataField,
        	"where_field" => "userId",
        	"where_value" => "{$fpUserId}",
        	"page" => $pPage,
        	"limit" => $pLimit,
        	"orderby" => "id:desc",
        	"like_field" => $pLiketype,
        	"like_key" => $pLike,
        );

        //条件字段判断组合
        $jsonKeyValueArray = HandleFlyJsonAddWhereField($jsonKeyValueArray, "id", $pId);
        $jsonKeyValueArray = HandleFlyJsonAddWhereField($jsonKeyValueArray, "state", $pWhereState);
        $jsonKeyValueArray = HandleFlyJsonAddWhereField($jsonKeyValueArray, "shelfState", $pWhereShelfState);
        //返回结果:分页
        return MIndexDataPaging(JsonHandleArray($jsonKeyValueArray));

    }


    //---------- 管理员方法（admin） ----------

    /**
     * 函数名称：业务线接口绑定关系:管理员:记录添加
     * 函数调用：ObjFlyInterfaceService() -> AdminFlyInterfaceServiceAdd($fpAdminId)
     * 创建时间：2019-12-21 16:57:06
     * */
    public function AdminFlyInterfaceServiceAdd($fpAdminId){


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

	    //参数:业务线页面:servicePage
	    $pServicePage = GetParameter("servicepage",$json);

	    //参数:业务区域:serviceArea
	    $pServiceArea = GetParameter("servicearea",$json);

	    //参数:业务描述:serviceDescript
	    $pServiceDescript = GetParameter("servicedescript",$json);

	    //参数:接口描述:interfaceDescript
	    $pInterfaceDescript = GetParameter("interfacedescript",$json);

	    //参数:接口请求路径:interfacePath
	    $pInterfacePath = GetParameter("interfacepath",$json);

	    //参数:接口业务线:interfaceLine
	    $pInterfaceLine = GetParameter("interfaceline",$json);

	    //参数:接口业务线方法:interfaceLineMethod
	    $pInterfaceLineMethod = GetParameter("interfacelinemethod",$json);

        //--- Json组合区 ---
        //Json数组
        $jsonKeyValueArray = array(
            "table_name" => self::$tableName,
            "insert_field" => "descript,shelfState,servicePage,serviceArea,serviceDescript,interfaceDescript,interfacePath,interfaceLine,interfaceLineMethod",
            "descript" => self::$classDescript,
            "shelfstate" => $pShelfState,
            "servicepage" => $pServicePage,
            "servicearea" => $pServiceArea,
            "servicedescript" => $pServiceDescript,
            "interfacedescript" => $pInterfaceDescript,
            "interfacepath" => $pInterfacePath,
            "interfaceline" => $pInterfaceLine,
            "interfacelinemethod" => $pInterfaceLineMethod,
            "key_field" => "servicePage,serviceArea",
        );
        //执行:添加
        $vJsonResult = MIndexDataInsert(JsonHandleArray($jsonKeyValueArray));
        
        if(JudgeJsonFalse($vJsonResult)&&JudgeJsonValue($vJsonResult, "infor", "记录已存在")){
            //Json数组
            $jsonKeyValueArray = array(
                "table_name" => self::$tableName,
                "update_field" => "serviceDescript,interfaceDescript,interfacePath,interfaceLine,interfaceLineMethod",
                "update_value" => "{$pServiceDescript},{$pInterfaceDescript},{$pInterfacePath},{$pInterfaceLine},{$pInterfaceLineMethod}",
                "where_field" => "servicePage,serviceArea",
                "where_value" => "{$pServicePage},{$pServiceArea}",
            );
            return MIndexDataUpdate(JsonHandleArray($jsonKeyValueArray));
        }
        
        return $vJsonResult;
    }


    /**
     * 函数名称：业务线接口绑定关系:管理员:记录查询
     * 函数调用：ObjFlyInterfaceService() -> AdminFlyInterfaceServicePaging($fpAdminId)
     * 创建时间：2019-12-21 16:57:07
     * */
    public function AdminFlyInterfaceServicePaging($fpAdminId){

        //--- 参数限制区 ---
        $vParameterArray = GetArray(FLY_PARAMETER_LIMIT, ",");
        $bParameterJudge = JudgeParameterLimit($vParameterArray);
        if(JudgeJsonFalse($bParameterJudge)){return $bParameterJudge;}

        //--- 数据预定义 ---
        $json = "";

        //--- 参数获取区 ---
        $pServiceArea = GetParameter("servicearea","");   //参数:业务区域:servicearea
        $pPageName = GetParameter("pagename","");   //参数:网页名称:pagename
        $pPage = GetParameter("page","");     //参数:页码:page
        $pLimit = GetParameter("limit","");   //参数:条数:limit

        //参数：id
        $pId = GetParameter("id","");
        if(!IsNull($pId)&&!JudgeRegularIntRight($pId)){return JsonModelParameterException("id", $pId, 20, "值必须是正整数", __LINE__);}
        if(!IsNull($pId)){ $pPage = 1; $pLimit = 1; }

        //参数判断:业务区域:servicearea
        if(!JudgeRegularUrl($pServiceArea)){return JsonModelParameterException("servicearea", $pServiceArea, 64, "值必区域数据", __LINE__);}
        //参数判断:网页名称:pagename
        if(!JudgeRegularUrl($pPageName)){return JsonModelParameterException("pagename", $pPageName, 64, "值必须是网页名称", __LINE__);}
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
        $pLike = GetParameter("like","");
        $pLiketype = GetParameter("liketype","");
        if(!IsNull($pLiketype)&&!DBMySQLJudge::JudgeTableField(self::$tableName,$pLiketype)){ return JsonInforFalse("搜索字段不存在", $pLiketype); }
        if(!IsNull($pLike)){ $pLike = HandleStringAddslashes($pLike); }

        //字段定义
        $vDataField = DBMySQLSelect::TableFieldString(self::$tableName);
        //$vDataField = "id,onlyKey,descript,indexNumber,updateTime,addTime,shelfState,state,servicePage,serviceArea,serviceDescript,interfaceDescript,interfacePath,interfaceLine,interfaceLineMethod";

        //--- Json组合区 ---
        $jsonKeyValueArray = array(
        	"table_name" => self::$tableName,
        	"data_field" => $vDataField,
        	"where_field" => "",
        	"where_value" => "",
        	"page" => $pPage,
        	"limit" => $pLimit,
        	"orderby" => "id:desc",
        	"like_field" => $pLiketype,
        	"like_key" => $pLike,
        );

        //条件字段判断组合
        $jsonKeyValueArray = HandleFlyJsonAddWhereField($jsonKeyValueArray, "servicePage", $pPageName);
        $jsonKeyValueArray = HandleFlyJsonAddWhereField($jsonKeyValueArray, "serviceArea", $pServiceArea);
        $jsonKeyValueArray = HandleFlyJsonAddWhereField($jsonKeyValueArray, "id", $pId);
        $jsonKeyValueArray = HandleFlyJsonAddWhereField($jsonKeyValueArray, "state", $pWhereState);
        $jsonKeyValueArray = HandleFlyJsonAddWhereField($jsonKeyValueArray, "shelfState", $pWhereShelfState);
        //返回结果:分页
        return MIndexDataPaging(JsonHandleArray($jsonKeyValueArray));

    }


    /**
     * 函数名称：业务线接口绑定关系:管理员:记录修改
     * 函数调用：ObjFlyInterfaceService() -> AdminFlyInterfaceServiceSet($fpAdminId)
     * 创建时间：2019-12-21 16:57:06
     * */
    public function AdminFlyInterfaceServiceSet($fpAdminId){

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

	    //参数:业务线页面:servicePage
	    $pServicePage = GetParameter("servicepage",$json);
	    if(!IsNull($pServicePage)&&!JudgeRegularFont($pServicePage)){return JsonModelParameterException("servicepage", $pServicePage, 36, "内容格式错误", __LINE__);}

	    //参数:业务区域:serviceArea
	    $pServiceArea = GetParameter("servicearea",$json);
	    if(!IsNull($pServiceArea)&&!JudgeRegularFont($pServiceArea)){return JsonModelParameterException("servicearea", $pServiceArea, 128, "内容格式错误", __LINE__);}

	    //参数:业务描述:serviceDescript
	    $pServiceDescript = GetParameter("servicedescript",$json);
	    if(!IsNull($pServiceDescript)&&!JudgeRegularFont($pServiceDescript)){return JsonModelParameterException("servicedescript", $pServiceDescript, 128, "内容格式错误", __LINE__);}

	    //参数:接口描述:interfaceDescript
	    $pInterfaceDescript = GetParameter("interfacedescript",$json);
	    if(!IsNull($pInterfaceDescript)&&!JudgeRegularFont($pInterfaceDescript)){return JsonModelParameterException("interfacedescript", $pInterfaceDescript, 128, "内容格式错误", __LINE__);}

	    //参数:接口请求路径:interfacePath
	    $pInterfacePath = GetParameter("interfacepath",$json);
	    if(!IsNull($pInterfacePath)&&!JudgeRegularFont($pInterfacePath)){return JsonModelParameterException("interfacepath", $pInterfacePath, 128, "内容格式错误", __LINE__);}

	    //参数:接口业务线:interfaceLine
	    $pInterfaceLine = GetParameter("interfaceline",$json);
	    if(!IsNull($pInterfaceLine)&&!JudgeRegularFont($pInterfaceLine)){return JsonModelParameterException("interfaceline", $pInterfaceLine, 128, "内容格式错误", __LINE__);}

	    //参数:接口业务线方法:interfaceLineMethod
	    $pInterfaceLineMethod = GetParameter("interfacelinemethod",$json);
	    if(!IsNull($pInterfaceLineMethod)&&!JudgeRegularFont($pInterfaceLineMethod)){return JsonModelParameterException("interfacelinemethod", $pInterfaceLineMethod, 128, "内容格式错误", __LINE__);}

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
	    $jsonKeyValueArray = HandleFlyJsonAddUpdateField($jsonKeyValueArray, "servicePage", $pServicePage);
	    $jsonKeyValueArray = HandleFlyJsonAddUpdateField($jsonKeyValueArray, "serviceArea", $pServiceArea);
	    $jsonKeyValueArray = HandleFlyJsonAddUpdateField($jsonKeyValueArray, "serviceDescript", $pServiceDescript);
	    $jsonKeyValueArray = HandleFlyJsonAddUpdateField($jsonKeyValueArray, "interfaceDescript", $pInterfaceDescript);
	    $jsonKeyValueArray = HandleFlyJsonAddUpdateField($jsonKeyValueArray, "interfacePath", $pInterfacePath);
	    $jsonKeyValueArray = HandleFlyJsonAddUpdateField($jsonKeyValueArray, "interfaceLine", $pInterfaceLine);
	    $jsonKeyValueArray = HandleFlyJsonAddUpdateField($jsonKeyValueArray, "interfaceLineMethod", $pInterfaceLineMethod);

	    //判断字段值是否为空
	    $vUpdateFieldJudgeInfor = JudgeFlyJsonAddUpdateField($jsonKeyValueArray,"descript,indexnumber,shelfstate,state,servicepage,servicearea,servicedescript,interfacedescript,interfacepath,interfaceline,interfacelinemethod");
	    if(JudgeJsonFalse($vUpdateFieldJudgeInfor)){ return $vUpdateFieldJudgeInfor; }

	    //返回结果
	    return MIndexDataUpdate(JsonHandleArray($jsonKeyValueArray));

    }


    /**
     * 函数名称：业务线接口绑定关系:管理员:记录状态修改
     * 函数调用：ObjFlyInterfaceService() -> AdminFlyInterfaceServiceSetState($fpAdminId)
     * 创建时间：2019-12-21 16:57:06
     * */
    public function AdminFlyInterfaceServiceSetState($fpAdminId){

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
     * 函数名称：业务线接口绑定关系:管理员:数据上下架状态修改
     * 函数调用：ObjFlyInterfaceService() -> AdminFlyInterfaceServiceShelfState($fpAdminId)
     * 创建时间：2019-12-21 16:57:06
     * */
    public function AdminFlyInterfaceServiceShelfState($fpAdminId){
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
     * 函数名称：业务线接口绑定关系:管理员:记录永久删除
     * 函数调用：ObjFlyInterfaceService() -> AdminFlyInterfaceServiceDelete($fpAdminId)
     * 创建时间：2019-12-21 16:57:06
     * */
    public function AdminFlyInterfaceServiceDelete($fpAdminId){
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
     * 函数调用：ObjFlyInterfaceService() -> GetTableName()
     * 创建时间：2019-12-21 16:57:06
     * */
    public function GetTableName(){
        return self::$tableName;
    }

    /**
     * 函数名称：获取数据表字段
     * 函数调用：ObjFlyInterfaceService() -> GetTableField()
     * 创建时间：2019-12-21 16:57:06
     * */
    public function GetTableField(){
        return MIndexDBTableFields(JsonObj(JsonKeyValue("table_name", self::$tableName)));
    }

    /**
     * 函数名称：获取类描述
     * 函数调用：ObjFlyInterfaceService() -> GetClassDescript()
     * 创建时间：2019-12-21 16:57:06
     * */
    public function GetClassDescript(){
        return self::$classDescript;
    }

    /**
     * 函数名称：类数据表创建
     * 函数调用：ObjFlyInterfaceService() -> OprationCreateTable()
     * 创建时间：2019-12-21 16:57:06
     * */
    public function OprationCreateTable(){
        $sql = "CREATE TABLE `fly_interface_service` (  `id` bigint(20) NOT NULL AUTO_INCREMENT COMMENT '表ID',  `onlyKey` varchar(36) DEFAULT NULL COMMENT '唯一Key',  `descript` varchar(36) DEFAULT NULL COMMENT '记录描述',  `indexNumber` int(11) DEFAULT '-1' COMMENT '序号',  `updateTime` timestamp NULL DEFAULT NULL COMMENT '修改时间',  `addTime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '添加时间',  `shelfState` varchar(36) DEFAULT 'true' COMMENT '上下架状态',  `state` varchar(36) DEFAULT 'STATE_NORMAL' COMMENT '记录状态',  `servicePage` varchar(36) DEFAULT NULL COMMENT '业务线页面',  `serviceArea` varchar(128) DEFAULT NULL COMMENT '业务区域',  `serviceDescript` varchar(128) DEFAULT NULL COMMENT '业务描述',  `interfaceDescript` varchar(128) DEFAULT NULL COMMENT '接口描述',  `interfacePath` varchar(128) DEFAULT NULL COMMENT '接口请求路径',  `interfaceLine` varchar(128) DEFAULT NULL COMMENT '接口业务线',  `interfaceLineMethod` varchar(128) DEFAULT NULL COMMENT '接口业务线方法',  PRIMARY KEY (`id`) USING BTREE) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT";
        DBHelper::DataSubmit($sql, "");
        $vTableCheck = DBMySQLServiceJson::GetDBTableCheck('{"tableName":"'.self::$tableName.'"}');
        if(JudgeJsonTrue($vTableCheck)){
            return JsonInforTrue("创建成功", self::$tableName);
        }
        return JsonInforFalse("创建失败", self::$tableName);
    }

    /**
     * 函数名称：类数据表基础字段检测
     * 函数调用：ObjFlyInterfaceService() -> OprationTableFieldBaseCheck()
     * 创建时间：2019-12-21 16:57:06
     * */
    public function OprationTableFieldBaseCheck(){
        return DBMySQLServiceJson::OprationFieldBaseCheck(JsonObj(JsonKeyValue("table_name", self::$tableName)));
    }




}
