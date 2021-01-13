<?php

/**------------------------------------*
  * 作者：shark
  * 创建时间：2020-01-21 14:59:27
  * Fly编码：1579589967338FLY690293
  * 类对象名：ObjWorkOrder()
  * ------------------------------------ */

//引入区

class FlyClassWorkOrder{


    //---------- 类成员（member） ----------

    //类描述
    public static $classDescript = "工单列表";
    //类数据表名
    public static $tableName = "fly_work_order";
    //类数据表字段
    public static $tableField = "id,onlyKey,descript,indexNumber,updateTime,addTime,shelfState,state,originatorId,originatorName,receiverId,receiverName,workOrderType,workOrderTypeIndex,vWorkOrderPowerType,vWorkOrderPowerTypeIndex,workOrderTitle,workOrderBody,workOrderOneImage,workOrderTwoImage,workOrderThreeImage,workOrderFourImage,workOrderFiveImage,workOrderTime";


    //---------- 私有方法（private） ----------

    //---------- 游客方法（visitor） ----------

    /**
     * 函数名称：工单列表:游客:记录查询
     * 函数调用：ObjWorkOrder() -> VisitorWorkOrderPaging()
     * 创建时间：2020-01-21 14:59:27
     * */
    public function VisitorWorkOrderPaging(){

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
        $pLikeField = GetParameter("likefield","");
        if(!IsNull($pLikeField)&&!DBMySQLJudge::JudgeTableField(self::$tableName,$pLikeField)){ return JsonInforFalse("搜索字段不存在", $pLikeField,__LINE__); }
        $pLikeKey = GetParameter("likekey","");
        if(!IsNull($pLikeKey)){ $pLikeKey = HandleStringAddslashes($pLikeKey); }

        //参数：state
        $pStateField = GetParameter("statefield","");
        if(!IsNull($pStateField)&&!DBMySQLJudge::JudgeTableField(self::$tableName,$pStateField)){ return JsonInforFalse("状态字段不存在", $pStateField,__LINE__); }
        $pStateKey = GetParameter("statekey","");
        if(!IsNull($pStateKey)&&!JudgeRegularState($pStateKey)){ return JsonInforFalse("状态码错误", $pStateKey,__LINE__); }

        //字段定义
        $vDataField = DBMySQLSelect::TableFieldString(self::$tableName);
        //$vDataField = "id,onlyKey,descript,indexNumber,updateTime,addTime,shelfState,state,originatorId,originatorName,receiverId,receiverName,workOrderType,workOrderTypeIndex,vWorkOrderPowerType,vWorkOrderPowerTypeIndex,workOrderTitle,workOrderBody,workOrderOneImage,workOrderTwoImage,workOrderThreeImage,workOrderFourImage,workOrderFiveImage,workOrderTime";

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
     * 函数名称：工单列表:系统:记录添加
     * 函数调用：ObjWorkOrder() -> SystemWorkOrderAdd
     * 创建时间：2020-01-21 14:59:26
     * */
    public function SystemWorkOrderAdd($fpOriginatorId,$fpOriginatorName,$fpReceiverId,$fpReceiverName,$fpWorkOrderType,$fpWorkOrderTypeIndex,$fpVWorkOrderPowerType,$fpVWorkOrderPowerTypeIndex,$fpWorkOrderTitle,$fpWorkOrderBody,$fpWorkOrderOneImage,$fpWorkOrderTwoImage,$fpWorkOrderThreeImage,$fpWorkOrderFourImage,$fpWorkOrderFiveImage,$fpWorkOrderTime){

        //--- Json组合区 ---
        //Json数组
        $jsonKeyValueArray = array(
            "table_name" => self::$tableName,
            "insert_field" => "descript,shelfState,originatorId,originatorName,receiverId,receiverName,workOrderType,workOrderTypeIndex,vWorkOrderPowerType,vWorkOrderPowerTypeIndex,workOrderTitle,workOrderBody,workOrderOneImage,workOrderTwoImage,workOrderThreeImage,workOrderFourImage,workOrderFiveImage,workOrderTime",
            "key_field" => "originatorId,receiverId",
            "descript" => self::$classDescript,
            "shelfstate" => "false",
            "originatorid" => $fpOriginatorId,
            "originatorname" => $fpOriginatorName,
            "receiverid" => $fpReceiverId,
            "receivername" => $fpReceiverName,
            "workordertype" => $fpWorkOrderType,
            "workordertypeindex" => $fpWorkOrderTypeIndex,
            "vworkorderpowertype" => $fpVWorkOrderPowerType,
            "vworkorderpowertypeindex" => $fpVWorkOrderPowerTypeIndex,
            "workordertitle" => $fpWorkOrderTitle,
            "workorderbody" => $fpWorkOrderBody,
            "workorderoneimage" => $fpWorkOrderOneImage,
            "workordertwoimage" => $fpWorkOrderTwoImage,
            "workorderthreeimage" => $fpWorkOrderThreeImage,
            "workorderfourimage" => $fpWorkOrderFourImage,
            "workorderfiveimage" => $fpWorkOrderFiveImage,
            "workordertime" => $fpWorkOrderTime,
        );
        //执行:添加
        $vJsonResult = MIndexDataInsert(JsonHandleArray($jsonKeyValueArray));
        return $vJsonResult;
    }


    //---------- 用户方法（user） ----------

    /**
     * 函数名称：工单列表:用户:记录查询
     * 函数调用：ObjWorkOrder() -> UserWorkOrderPaging($fpUserId)
     * 创建时间：2020-01-21 14:59:27
     * */
    public function UserWorkOrderPaging($fpUserId){

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
        $pLikeField = GetParameter("likefield","");
        if(!IsNull($pLikeField)&&!DBMySQLJudge::JudgeTableField(self::$tableName,$pLikeField)){ return JsonInforFalse("搜索字段不存在", $pLikeField,__LINE__); }
        $pLikeKey = GetParameter("likekey","");
        if(!IsNull($pLikeKey)){ $pLikeKey = HandleStringAddslashes($pLikeKey); }

        //参数：state
        $pStateField = GetParameter("statefield","");
        if(!IsNull($pStateField)&&!DBMySQLJudge::JudgeTableField(self::$tableName,$pStateField)){ return JsonInforFalse("状态字段不存在", $pStateField,__LINE__); }
        $pStateKey = GetParameter("statekey","");
        if(!IsNull($pStateKey)&&!JudgeRegularState($pStateKey)){ return JsonInforFalse("状态码错误", $pStateKey,__LINE__); }

        //字段定义
        $vDataField = DBMySQLSelect::TableFieldString(self::$tableName);
        //$vDataField = "id,onlyKey,descript,indexNumber,updateTime,addTime,shelfState,state,originatorId,originatorName,receiverId,receiverName,workOrderType,workOrderTypeIndex,vWorkOrderPowerType,vWorkOrderPowerTypeIndex,workOrderTitle,workOrderBody,workOrderOneImage,workOrderTwoImage,workOrderThreeImage,workOrderFourImage,workOrderFiveImage,workOrderTime";

        //--- Json组合区 ---
        $jsonKeyValueArray = array(
        	"table_name" => self::$tableName,
        	"data_field" => $vDataField,
        	"where_field" => "userId",
        	"where_value" => "{$fpUserId}",
        	"like_field" => $pLikeField,
        	"like_key" => $pLikeKey,
        	"page" => $pPage,
        	"limit" => $pLimit,
        	"orderby" => "id:desc",
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
     * 函数名称：工单列表:管理员:记录添加
     * 函数调用：ObjWorkOrder() -> AdminWorkOrderAdd($fpAdminId)
     * 创建时间：2020-01-21 14:59:26
     * */
    public function AdminWorkOrderAdd($fpAdminId){


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

	    //参数:发起人ID:originatorId
	    $pOriginatorId = GetParameter("originatorid",$json);
	    if(!JudgeRegularInt($pOriginatorId)){return JsonModelParameterException("originatorid", $pOriginatorId, 11, "值必须是整数", __LINE__);}

	    //参数:发起人姓名:originatorName
	    $pOriginatorName = GetParameter("originatorname",$json);
	    if(!JudgeRegularFont($pOriginatorName)){return JsonModelParameterException("originatorname", $pOriginatorName, 36, "内容格式错误", __LINE__);}

	    //参数:接收人ID:receiverId
	    $pReceiverId = GetParameter("receiverid",$json);
	    if(!JudgeRegularInt($pReceiverId)){return JsonModelParameterException("receiverid", $pReceiverId, 11, "值必须是整数", __LINE__);}

	    //参数:接收人姓名:receiverName
	    $pReceiverName = GetParameter("receivername",$json);
	    if(!JudgeRegularFont($pReceiverName)){return JsonModelParameterException("receivername", $pReceiverName, 36, "内容格式错误", __LINE__);}

	    //参数:工单类型[需求|BUG]:workOrderType
	    $pWorkOrderType = GetParameter("workordertype",$json);
	    if(!JudgeRegularFont($pWorkOrderType)){return JsonModelParameterException("workordertype", $pWorkOrderType, 36, "URL地址格式错误", __LINE__);}

	    //参数:工单类型索引:workOrderTypeIndex
	    $pWorkOrderTypeIndex = "100";
	    if($pWorkOrderType=="BUG"){
	        $pWorkOrderTypeIndex = "999";
	    }else if($pWorkOrderType=="需求"){
	        $pWorkOrderTypeIndex = "500";
	    }else if($pWorkOrderType=="任务"){
	        $pWorkOrderTypeIndex = "200";
	    }

	    //参数:工单权重类型[普通|紧急]:vWorkOrderPowerType
	    $pVWorkOrderPowerType = GetParameter("vworkorderpowertype",$json);
	    if(!JudgeRegularFont($pVWorkOrderPowerType)){return JsonModelParameterException("vworkorderpowertype", $pVWorkOrderPowerType, 36, "URL地址格式错误", __LINE__);}

	    //参数:工单权重索引:vWorkOrderPowerTypeIndex
	    $pVWorkOrderPowerTypeIndex = "100";
	    if($pVWorkOrderPowerType=="普通"){
	        $pVWorkOrderPowerTypeIndex = "100";
	    }else if($pVWorkOrderPowerType=="紧急"){
	        $pVWorkOrderPowerTypeIndex = "600";
	    }else if($pVWorkOrderPowerType=="加急"){
	        $pVWorkOrderPowerTypeIndex = "700";
	    }

	    //参数:工单标题:workOrderTitle
	    $pWorkOrderTitle = GetParameter("workordertitle",$json);
	    if(!JudgeRegularTitle($pWorkOrderTitle)){return JsonModelParameterException("workordertitle", $pWorkOrderTitle, 128, "URL地址格式错误", __LINE__);}

	    //参数:工单内容:workOrderBody
	    $pWorkOrderBody = GetParameter("workorderbody",$json);
	    if(IsNull($pWorkOrderBody)){ return JsonModelParameterNull("workorderbody"); }
	    $pWorkOrderBody = HandleStringFlyHtmlEncode($pWorkOrderBody);
	    //参数:工单问题图片一:workOrderOneImage
	    $pWorkOrderOneImage = GetParameter("workorderoneimage",$json);
	    if(!JudgeRegularUrl($pWorkOrderOneImage)){return JsonModelParameterException("workorderoneimage", $pWorkOrderOneImage, 128, "URL地址格式错误", __LINE__);}

	    //参数:工单问题图片二:workOrderTwoImage
	    $pWorkOrderTwoImage = GetParameter("workordertwoimage",$json);
	    if(!JudgeRegularUrl($pWorkOrderTwoImage)){return JsonModelParameterException("workordertwoimage", $pWorkOrderTwoImage, 128, "URL地址格式错误", __LINE__);}

	    //参数:工单问题图片三:workOrderThreeImage
	    $pWorkOrderThreeImage = GetParameter("workorderthreeimage",$json);
	    if(!JudgeRegularUrl($pWorkOrderThreeImage)){return JsonModelParameterException("workorderthreeimage", $pWorkOrderThreeImage, 128, "URL地址格式错误", __LINE__);}

	    //参数:工单问题图片四:workOrderFourImage
	    $pWorkOrderFourImage = GetParameter("workorderfourimage",$json);
	    if(!JudgeRegularUrl($pWorkOrderFourImage)){return JsonModelParameterException("workorderfourimage", $pWorkOrderFourImage, 128, "URL地址格式错误", __LINE__);}

	    //参数:工单问题图片五:workOrderFiveImage
	    $pWorkOrderFiveImage = GetParameter("workorderfiveimage",$json);
	    if(!JudgeRegularUrl($pWorkOrderFiveImage)){return JsonModelParameterException("workorderfiveimage", $pWorkOrderFiveImage, 128, "URL地址格式错误", __LINE__);}

	    //参数:工单最迟解决时间:workOrderTime
	    $pWorkOrderTime = GetParameter("workordertime",$json);
	    if(!JudgeRegularDate($pWorkOrderTime)){return JsonModelParameterException("workordertime", $pWorkOrderTime, 0, "日期格式错误", __LINE__);}

	    //判断 发起人 与 接收人
	    if($pOriginatorId==$pReceiverId){
	        return JsonInforFalse("发起人ID与接收人ID不得相同", "{$pOriginatorId}:{$pReceiverId}");
	    }
	    
        //--- Json组合区 ---
        //Json数组
        $jsonKeyValueArray = array(
            "table_name" => self::$tableName,
            "insert_field" => "descript,shelfState,originatorId,originatorName,receiverId,receiverName,workOrderType,workOrderTypeIndex,vWorkOrderPowerType,vWorkOrderPowerTypeIndex,workOrderTitle,workOrderBody,workOrderOneImage,workOrderTwoImage,workOrderThreeImage,workOrderFourImage,workOrderFiveImage,workOrderTime,workOrderStateIndex",
            "key_field" => "originatorId,receiverId,workOrderType,workOrderTitle,workOrderTime",
            "descript" => self::$classDescript,
            "shelfstate" => $pShelfState,
            "originatorid" => $pOriginatorId,
            "originatorname" => $pOriginatorName,
            "receiverid" => $pReceiverId,
            "receivername" => $pReceiverName,
            "workordertype" => $pWorkOrderType,
            "workordertypeindex" => $pWorkOrderTypeIndex,
            "vworkorderpowertype" => $pVWorkOrderPowerType,
            "vworkorderpowertypeindex" => $pVWorkOrderPowerTypeIndex,
            "workordertitle" => $pWorkOrderTitle,
            "workorderbody" => $pWorkOrderBody,
            "workorderoneimage" => $pWorkOrderOneImage,
            "workordertwoimage" => $pWorkOrderTwoImage,
            "workorderthreeimage" => $pWorkOrderThreeImage,
            "workorderfourimage" => $pWorkOrderFourImage,
            "workorderfiveimage" => $pWorkOrderFiveImage,
            "workordertime" => $pWorkOrderTime,
            "workOrderStateIndex" => "100",
        );
        //执行:添加
        $vJsonResult = MIndexDataInsert(JsonHandleArray($jsonKeyValueArray));
        return $vJsonResult;
    }


    /**
     * 函数名称：工单列表:管理员:记录查询
     * 函数调用：ObjWorkOrder() -> AdminWorkOrderPaging($fpAdminId)
     * 创建时间：2020-01-21 14:59:27
     * */
    public function AdminWorkOrderPaging($fpAdminId){

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
        
        //参数：待完成的工单
        $pReplyIng = GetParameter("replying","");
        if($pReplyIng == "true"){
            $pReplyIng = " AND workOrderState != 'WORK_ORDER_OVER' ";
        }else{
            $pReplyIng = "";
        }

        //参数：上下架状态
        $pWhereShelfState = GetParameter("whereshelfstate","");
        if(!IsNull($pWhereShelfState)&&!($pWhereShelfState=="true"||$pWhereShelfState=="false")){return JsonModelParameterException("whereshelfstate", $pWhereShelfState, 36, "值必须是true/false", __LINE__);}

        //参数：like
        $pLikeField = GetParameter("likefield","");
        if(!IsNull($pLikeField)&&!DBMySQLJudge::JudgeTableField(self::$tableName,$pLikeField)){ return JsonInforFalse("搜索字段不存在", $pLikeField,__LINE__); }
        $pLikeKey = GetParameter("likekey","");
        if(!IsNull($pLikeKey)){ $pLikeKey = HandleStringAddslashes($pLikeKey); }

        //参数：state
        $pStateField = GetParameter("statefield","");
        if(!IsNull($pStateField)&&!DBMySQLJudge::JudgeTableField(self::$tableName,$pStateField)){ return JsonInforFalse("状态字段不存在", $pStateField,__LINE__); }
        $pStateKey = GetParameter("statekey","");
        if(!IsNull($pStateKey)&&!JudgeRegularState($pStateKey)){ return JsonInforFalse("状态码错误", $pStateKey,__LINE__); }

        //字段定义
        $vDataField = DBMySQLSelect::TableFieldString(self::$tableName);
        //$vDataField = "id,onlyKey,descript,indexNumber,updateTime,addTime,shelfState,state,originatorId,originatorName,receiverId,receiverName,workOrderType,workOrderTypeIndex,vWorkOrderPowerType,vWorkOrderPowerTypeIndex,workOrderTitle,workOrderBody,workOrderOneImage,workOrderTwoImage,workOrderThreeImage,workOrderFourImage,workOrderFiveImage,workOrderTime";

        //--- Json组合区 ---
        $jsonKeyValueArray = array(
        	"table_name" => self::$tableName,
        	"data_field" => $vDataField,
        	"where_field" => "",
        	"where_value" => "",
            "where_son" => "(originatorId='{$fpAdminId}' OR receiverId='{$fpAdminId}'){$pReplyIng}",
            "like_field" => $pLikeField,
            "like_key" => $pLikeKey,
        	"page" => $pPage,
        	"limit" => $pLimit,
        	"orderby" => "workOrderStateIndex,vWorkOrderPowerTypeIndex:desc",
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
     * 函数名称：工单列表:管理员:记录修改
     * 函数调用：ObjWorkOrder() -> AdminWorkOrderSet($fpAdminId)
     * 创建时间：2020-01-21 14:59:26
     * */
    public function AdminWorkOrderSet($fpAdminId){

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

	    //参数:发起人ID:originatorId
	    $pOriginatorId = GetParameter("originatorid",$json);
	    if(!IsNull($pOriginatorId)&&!JudgeRegularInt($pOriginatorId)){return JsonModelParameterException("originatorid", $pOriginatorId, 11, "值必须是整数", __LINE__);}

	    //参数:发起人姓名:originatorName
	    $pOriginatorName = GetParameter("originatorname",$json);
	    if(!IsNull($pOriginatorName)&&!JudgeRegularFont($pOriginatorName)){return JsonModelParameterException("originatorname", $pOriginatorName, 36, "内容格式错误", __LINE__);}

	    //参数:接收人ID:receiverId
	    $pReceiverId = GetParameter("receiverid",$json);
	    if(!IsNull($pReceiverId)&&!JudgeRegularInt($pReceiverId)){return JsonModelParameterException("receiverid", $pReceiverId, 11, "值必须是整数", __LINE__);}

	    //参数:接收人姓名:receiverName
	    $pReceiverName = GetParameter("receivername",$json);
	    if(!IsNull($pReceiverName)&&!JudgeRegularFont($pReceiverName)){return JsonModelParameterException("receivername", $pReceiverName, 36, "内容格式错误", __LINE__);}

	    //参数:工单类型[需求|BUG]:workOrderType
	    $pWorkOrderType = GetParameter("workordertype",$json);
	    if(!IsNull($pWorkOrderType)&&!JudgeRegularLetterNumber($pWorkOrderType)){return JsonModelParameterException("workordertype", $pWorkOrderType, 36, "URL地址格式错误", __LINE__);}

	    //参数:工单类型索引:workOrderTypeIndex
	    $pWorkOrderTypeIndex = GetParameter("workordertypeindex",$json);
	    if(!IsNull($pWorkOrderTypeIndex)&&!JudgeRegularInt($pWorkOrderTypeIndex)){return JsonModelParameterException("workordertypeindex", $pWorkOrderTypeIndex, 11, "值必须是整数", __LINE__);}

	    //参数:工单权重类型[普通|紧急]:vWorkOrderPowerType
	    $pVWorkOrderPowerType = GetParameter("vworkorderpowertype",$json);
	    if(!IsNull($pVWorkOrderPowerType)&&!JudgeRegularLetterNumber($pVWorkOrderPowerType)){return JsonModelParameterException("vworkorderpowertype", $pVWorkOrderPowerType, 36, "URL地址格式错误", __LINE__);}

	    //参数:工单权重索引:vWorkOrderPowerTypeIndex
	    $pVWorkOrderPowerTypeIndex = GetParameter("vworkorderpowertypeindex",$json);
	    if(!IsNull($pVWorkOrderPowerTypeIndex)&&!JudgeRegularInt($pVWorkOrderPowerTypeIndex)){return JsonModelParameterException("vworkorderpowertypeindex", $pVWorkOrderPowerTypeIndex, 11, "值必须是整数", __LINE__);}

	    //参数:工单标题:workOrderTitle
	    $pWorkOrderTitle = GetParameter("workordertitle",$json);
	    if(!IsNull($pWorkOrderTitle)&&!JudgeRegularLetterNumber($pWorkOrderTitle)){return JsonModelParameterException("workordertitle", $pWorkOrderTitle, 128, "URL地址格式错误", __LINE__);}

	    //参数:工单内容:workOrderBody
	    $pWorkOrderBody = GetParameter("workorderbody",$json);
	    if(!IsNull($pWorkOrderBody)){ $pWorkOrderBody = HandleStringAddslashes(HandleStringFlyHtmlEncode($pWorkOrderBody)); }

	    //参数:工单问题图片一:workOrderOneImage
	    $pWorkOrderOneImage = GetParameter("workorderoneimage",$json);
	    if(!IsNull($pWorkOrderOneImage)&&!JudgeRegularUrl($pWorkOrderOneImage)){return JsonModelParameterException("workorderoneimage", $pWorkOrderOneImage, 128, "URL地址格式错误", __LINE__);}

	    //参数:工单问题图片二:workOrderTwoImage
	    $pWorkOrderTwoImage = GetParameter("workordertwoimage",$json);
	    if(!IsNull($pWorkOrderTwoImage)&&!JudgeRegularUrl($pWorkOrderTwoImage)){return JsonModelParameterException("workordertwoimage", $pWorkOrderTwoImage, 128, "URL地址格式错误", __LINE__);}

	    //参数:工单问题图片三:workOrderThreeImage
	    $pWorkOrderThreeImage = GetParameter("workorderthreeimage",$json);
	    if(!IsNull($pWorkOrderThreeImage)&&!JudgeRegularUrl($pWorkOrderThreeImage)){return JsonModelParameterException("workorderthreeimage", $pWorkOrderThreeImage, 128, "URL地址格式错误", __LINE__);}

	    //参数:工单问题图片四:workOrderFourImage
	    $pWorkOrderFourImage = GetParameter("workorderfourimage",$json);
	    if(!IsNull($pWorkOrderFourImage)&&!JudgeRegularUrl($pWorkOrderFourImage)){return JsonModelParameterException("workorderfourimage", $pWorkOrderFourImage, 128, "URL地址格式错误", __LINE__);}

	    //参数:工单问题图片五:workOrderFiveImage
	    $pWorkOrderFiveImage = GetParameter("workorderfiveimage",$json);
	    if(!IsNull($pWorkOrderFiveImage)&&!JudgeRegularUrl($pWorkOrderFiveImage)){return JsonModelParameterException("workorderfiveimage", $pWorkOrderFiveImage, 128, "URL地址格式错误", __LINE__);}

	    //参数:工单最迟解决时间:workOrderTime
	    $pWorkOrderTime = GetParameter("workordertime",$json);
	    if(!IsNull($pWorkOrderTime)&&!JudgeRegularDate($pWorkOrderTime)){return JsonModelParameterException("workordertime", $pWorkOrderTime, 0, "日期格式错误", __LINE__);}

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
	    $jsonKeyValueArray = HandleFlyJsonAddUpdateField($jsonKeyValueArray, "originatorId", $pOriginatorId);
	    $jsonKeyValueArray = HandleFlyJsonAddUpdateField($jsonKeyValueArray, "originatorName", $pOriginatorName);
	    $jsonKeyValueArray = HandleFlyJsonAddUpdateField($jsonKeyValueArray, "receiverId", $pReceiverId);
	    $jsonKeyValueArray = HandleFlyJsonAddUpdateField($jsonKeyValueArray, "receiverName", $pReceiverName);
	    $jsonKeyValueArray = HandleFlyJsonAddUpdateField($jsonKeyValueArray, "workOrderType", $pWorkOrderType);
	    $jsonKeyValueArray = HandleFlyJsonAddUpdateField($jsonKeyValueArray, "workOrderTypeIndex", $pWorkOrderTypeIndex);
	    $jsonKeyValueArray = HandleFlyJsonAddUpdateField($jsonKeyValueArray, "vWorkOrderPowerType", $pVWorkOrderPowerType);
	    $jsonKeyValueArray = HandleFlyJsonAddUpdateField($jsonKeyValueArray, "vWorkOrderPowerTypeIndex", $pVWorkOrderPowerTypeIndex);
	    $jsonKeyValueArray = HandleFlyJsonAddUpdateField($jsonKeyValueArray, "workOrderTitle", $pWorkOrderTitle);
	    $jsonKeyValueArray = HandleFlyJsonAddUpdateField($jsonKeyValueArray, "workOrderBody", $pWorkOrderBody);
	    $jsonKeyValueArray = HandleFlyJsonAddUpdateField($jsonKeyValueArray, "workOrderOneImage", $pWorkOrderOneImage);
	    $jsonKeyValueArray = HandleFlyJsonAddUpdateField($jsonKeyValueArray, "workOrderTwoImage", $pWorkOrderTwoImage);
	    $jsonKeyValueArray = HandleFlyJsonAddUpdateField($jsonKeyValueArray, "workOrderThreeImage", $pWorkOrderThreeImage);
	    $jsonKeyValueArray = HandleFlyJsonAddUpdateField($jsonKeyValueArray, "workOrderFourImage", $pWorkOrderFourImage);
	    $jsonKeyValueArray = HandleFlyJsonAddUpdateField($jsonKeyValueArray, "workOrderFiveImage", $pWorkOrderFiveImage);
	    $jsonKeyValueArray = HandleFlyJsonAddUpdateField($jsonKeyValueArray, "workOrderTime", $pWorkOrderTime);

	    //判断字段值是否为空
	    $vUpdateFieldJudgeInfor = JudgeFlyJsonAddUpdateField($jsonKeyValueArray,"descript,indexnumber,shelfstate,state,originatorid,originatorname,receiverid,receivername,workordertype,workordertypeindex,vworkorderpowertype,vworkorderpowertypeindex,workordertitle,workorderbody,workorderoneimage,workordertwoimage,workorderthreeimage,workorderfourimage,workorderfiveimage,workordertime");
	    if(JudgeJsonFalse($vUpdateFieldJudgeInfor)){ return $vUpdateFieldJudgeInfor; }

	    //返回结果
	    return MIndexDataUpdate(JsonHandleArray($jsonKeyValueArray));

    }

    /**
     * 函数名称：工单列表:管理员:关闭工单
     * 函数调用：ObjWorkOrder() -> AdminWorkOrderOver($fpAdminId)
     * 创建时间：2020-01-21 14:59:26
     * */
    public function AdminWorkOrderOver($fpAdminId){
        
        $json = "";
        
        //参数:工单ID:workorderId
        $pWorkorderId = GetParameterNoCode("workorderid",$json);
        if(!JudgeRegularInt($pWorkorderId)){return JsonModelParameterException("workorderid", $pWorkorderId, 11, "值必须是整数", __LINE__);}
        
        //参数:工单ID关闭判断:closejudge
        $pCloseJudge = GetParameterNoCode("close_judge",$json);
        
        //获取:fly_work_order:发起人ID、工单状态
        $vSql = "SELECT originatorId,workOrderState FROM fly_work_order WHERE id=?;";
        $vFlyWorkOrderList = DBHelper::DataList($vSql, [$pWorkorderId], ["originatorId","workOrderState"]);
        if(IsNull($vFlyWorkOrderList)){ return JsonInforFalse("工单不存在", "fly_work_order", __LINE__); }
        $pFlyWorkOrderFirst = GetJsonMember($vFlyWorkOrderList);
        $vOriginatorId = $pFlyWorkOrderFirst -> originatorId;	//发起人ID
        $vWorkOrderState = $pFlyWorkOrderFirst -> workOrderState;	//工单状态
        
        //判断操作权限
        if($vOriginatorId != $fpAdminId){
            return JsonInforFalse("只有发起人可以关闭工单", "工单ID:{$pWorkorderId}");
        }
        
        //判断工单状态
        if($vWorkOrderState == "WORK_ORDER_OVER"){
            return JsonInforFalse("工单已关闭", "工单ID:{$pWorkorderId}");
        }
        
        //判断工单是否可以被关闭
        if($pCloseJudge == "true"){
            return JsonInforTrue("工单可以被关闭", "工单ID:{$pWorkorderId}");
        }
        
        //修改:fly_work_order:工单状态
        $vSql = "UPDATE fly_work_order SET workOrderState=?,workOrderStateIndex='300' WHERE id=?;";
        $vUpdateResult = DBHelper::DataSubmit($vSql, ["WORK_ORDER_OVER",$pWorkorderId]);
        if(!$vUpdateResult){ return JsonInforFalse("工单状态修改失败", "fly_work_order", __LINE__); }
        return JsonInforTrue("工单关闭成功", "工单ID:{$pWorkorderId}");
        
    }
    

    /**
     * 函数名称：工单列表:管理员:记录状态修改
     * 函数调用：ObjWorkOrder() -> AdminWorkOrderSetState($fpAdminId)
     * 创建时间：2020-01-21 14:59:26
     * */
    public function AdminWorkOrderSetState($fpAdminId){

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
     * 函数名称：工单列表:管理员:数据上下架状态修改
     * 函数调用：ObjWorkOrder() -> AdminWorkOrderShelfState($fpAdminId)
     * 创建时间：2020-01-21 14:59:26
     * */
    public function AdminWorkOrderShelfState($fpAdminId){
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
     * 函数名称：工单列表:管理员:记录永久删除
     * 函数调用：ObjWorkOrder() -> AdminWorkOrderDelete($fpAdminId)
     * 创建时间：2020-01-21 14:59:26
     * */
    public function AdminWorkOrderDelete($fpAdminId){
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

    /**
     * 函数名称：工单列表:管理员:工单查询
     * 函数调用：ObjWorkOrder() -> AdminWorkOrderSelect($fpAdminId)
     * 创建时间：2020-03-02 19:28:50
     * */
    public function AdminWorkOrderSelect($fpAdminId){
        //获取:fly_work_task:id
        $vSql = "SELECT id FROM fly_work_order WHERE workOrderState!='WORK_ORDER_OVER' AND (originatorId='{$fpAdminId}' OR receiverId='{$fpAdminId}')";
        $vWorkRecode = DBHelper::DataString($vSql, null);
        if(!IsNull($vWorkRecode)){
            return JsonInforTrue("您有未完成的工单记录", "fly_work_task");
        }
        return JsonInforFalse("工单记录全部完成", "fly_work_task");
    }

    //---------- 测试方法（test） ----------

    //---------- 基础方法（base） ----------


    /**
     * 函数名称：获取数据表名称
     * 函数调用：ObjWorkOrder() -> GetTableName()
     * 创建时间：2020-01-21 14:59:26
     * */
    public function GetTableName(){
        return self::$tableName;
    }

    /**
     * 函数名称：获取类描述
     * 函数调用：ObjWorkOrder() -> GetClassDescript()
     * 创建时间：2020-01-21 14:59:26
     * */
    public function GetClassDescript(){
        return self::$classDescript;
    }

    /**
     * 函数名称：获取数据表字段
     * 函数调用：ObjWorkOrder() -> GetTableField()
     * 创建时间：2020-01-21 14:59:26
     * */
    public function GetTableField(){
        return MIndexDBTableFields(JsonObj(JsonKeyValue("table_name", self::$tableName)));
    }

    /**
     * 函数名称：类数据表创建
     * 函数调用：ObjWorkOrder() -> OprationCreateTable()
     * 创建时间：2020-01-21 14:59:26
     * */
    public function OprationCreateTable(){
        $sql = "CREATE TABLE `fly_work_order` (  `id` bigint(20) NOT NULL AUTO_INCREMENT COMMENT '表ID',  `onlyKey` varchar(36) DEFAULT NULL COMMENT '唯一Key',  `descript` varchar(36) DEFAULT NULL COMMENT '记录描述',  `indexNumber` int(11) DEFAULT '-1' COMMENT '序号',  `updateTime` timestamp NULL DEFAULT NULL COMMENT '修改时间',  `addTime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '添加时间',  `shelfState` varchar(36) DEFAULT 'true' COMMENT '上下架状态',  `state` varchar(36) DEFAULT 'STATE_NORMAL' COMMENT '记录状态',  `originatorId` int(11) DEFAULT NULL COMMENT '发起人ID',  `originatorName` varchar(36) DEFAULT NULL COMMENT '发起人姓名',  `receiverId` int(11) DEFAULT NULL COMMENT '接收人ID',  `receiverName` varchar(36) DEFAULT NULL COMMENT '接收人姓名',  `workOrderType` varchar(36) DEFAULT NULL COMMENT '工单类型[需求|BUG]',  `workOrderTypeIndex` int(11) DEFAULT NULL COMMENT '工单类型索引',  `vWorkOrderPowerType` varchar(36) DEFAULT NULL COMMENT '工单权重类型[普通|紧急]',  `vWorkOrderPowerTypeIndex` int(11) DEFAULT NULL COMMENT '工单权重索引',  `workOrderTitle` varchar(128) DEFAULT NULL COMMENT '工单标题',  `workOrderBody` text COMMENT '工单内容',  `workOrderOneImage` varchar(128) DEFAULT NULL COMMENT '工单问题图片一',  `workOrderTwoImage` varchar(128) DEFAULT NULL COMMENT '工单问题图片二',  `workOrderThreeImage` varchar(128) DEFAULT NULL COMMENT '工单问题图片三',  `workOrderFourImage` varchar(128) DEFAULT NULL COMMENT '工单问题图片四',  `workOrderFiveImage` varchar(128) DEFAULT NULL COMMENT '工单问题图片五',  `workOrderTime` timestamp NULL DEFAULT NULL COMMENT '工单最迟解决时间',  PRIMARY KEY (`id`) USING BTREE) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='工单列表'";
        DBHelper::DataSubmit($sql, "");
        $vTableCheck = DBMySQLServiceJson::GetDBTableCheck('{"tableName":"'.self::$tableName.'"}');
        if(JudgeJsonTrue($vTableCheck)){
            return JsonInforTrue("创建成功", self::$tableName);
        }
        return JsonInforFalse("创建失败", self::$tableName);
    }

    /**
     * 函数名称：类数据表基础字段检测
     * 函数调用：ObjWorkOrder() -> OprationTableFieldBaseCheck()
     * 创建时间：2020-01-21 14:59:26
     * */
    public function OprationTableFieldBaseCheck(){
        return DBMySQLServiceJson::OprationFieldBaseCheck(JsonObj(JsonKeyValue("table_name", self::$tableName)));
    }




}
