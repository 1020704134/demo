<?php

/**------------------------------------*
  * 作者：shark
  * 创建时间：2020-02-29 14:32:52
  * Fly编码：1582957972699FLY328817
  * 类对象名：ObjFlyWorkOrderRecode()
  * ------------------------------------ */

//引入区

class FlyClassWorkOrderRecode{


    //---------- 类成员（member） ----------

    //类描述
    public static $classDescript = "工单回复记录";
    //类数据表名
    public static $tableName = "fly_work_order_recode";


    //---------- 私有方法（private） ----------

    //---------- 游客方法（visitor） ----------

    /**
     * 函数名称：工单回复记录:游客:记录查询
     * 函数调用：ObjFlyWorkOrderRecode() -> VisitorFlyWorkOrderRecodePaging()
     * 创建时间：2020-02-29 14:32:52
     * */
    public function VisitorFlyWorkOrderRecodePaging(){

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

        //参数：debug
        $pDebug = GetParameterNoCode("debug","");
        if($pDebug!="true"){$pDebug = "false";}

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
        //$vDataField = "id,onlyKey,descript,indexNumber,updateTime,addTime,shelfState,state,workorderId,replyType,replyBody,replyOneImage,replyTwoImage,replyThreeImage,replyFourImage,replyFiveImage";

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
        	"sql_debug" => $pDebug,
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
     * 函数名称：工单回复记录:系统:记录添加
     * 函数调用：ObjFlyWorkOrderRecode() -> SystemFlyWorkOrderRecodeAdd
     * 创建时间：2020-02-29 14:32:52
     * */
    public function SystemFlyWorkOrderRecodeAdd($fpWorkorderId,$fpReplyType,$fpReplyBody,$fpReplyOneImage,$fpReplyTwoImage,$fpReplyThreeImage,$fpReplyFourImage,$fpReplyFiveImage){

        //--- Json组合区 ---
        //Json数组
        $jsonKeyValueArray = array(
            "table_name" => self::$tableName,
            "insert_field" => "descript,shelfState,workorderId,replyType,replyBody,replyOneImage,replyTwoImage,replyThreeImage,replyFourImage,replyFiveImage",
            "descript" => self::$classDescript,
            "shelfstate" => "false",
            "workorderid" => $fpWorkorderId,
            "replytype" => $fpReplyType,
            "replybody" => $fpReplyBody,
            "replyoneimage" => $fpReplyOneImage,
            "replytwoimage" => $fpReplyTwoImage,
            "replythreeimage" => $fpReplyThreeImage,
            "replyfourimage" => $fpReplyFourImage,
            "replyfiveimage" => $fpReplyFiveImage,
            "key_field" => "workorderId",
        );
        //执行:添加
        $vJsonResult = MIndexDataInsert(JsonHandleArray($jsonKeyValueArray));
        return $vJsonResult;
    }


    //---------- 用户方法（user） ----------

    /**
     * 函数名称：工单回复记录:用户:记录查询
     * 函数调用：ObjFlyWorkOrderRecode() -> UserFlyWorkOrderRecodePaging($fpUserId)
     * 创建时间：2020-02-29 14:32:52
     * */
    public function UserFlyWorkOrderRecodePaging($fpUserId){

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

        //参数：debug
        $pDebug = GetParameterNoCode("debug","");
        if($pDebug!="true"){$pDebug = "false";}

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
        //$vDataField = "id,onlyKey,descript,indexNumber,updateTime,addTime,shelfState,state,workorderId,replyType,replyBody,replyOneImage,replyTwoImage,replyThreeImage,replyFourImage,replyFiveImage";

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
        	"sql_debug" => $pDebug,
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
     * 函数名称：工单回复记录:管理员:记录添加
     * 函数调用：ObjFlyWorkOrderRecode() -> AdminFlyWorkOrderRecodeAdd($fpAdminId)
     * 创建时间：2020-02-29 14:32:52
     * */
    public function AdminFlyWorkOrderRecodeAdd($fpAdminId){


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

	    //参数:工单ID:workorderId
	    $pWorkorderId = GetParameterNoCode("workorderid",$json);
	    if(!JudgeRegularInt($pWorkorderId)){return JsonModelParameterException("workorderid", $pWorkorderId, 11, "值必须是整数", __LINE__);}

	    //参数:回复类型[提问|回复]:replyType
	    $pReplyType = "";
	    //$pReplyType = GetParameterNoCode("replytype",$json);
	    //if(!JudgeRegularFont($pReplyType)){return JsonModelParameterException("replytype", $pReplyType, 36, "内容格式错误", __LINE__);}
	    //if(!($pReplyType=="提问"||$pReplyType=="回复")){return JsonInforFalse("replytype必须是指定值", "提问、回复");}

	    //参数:回复内容:replyBody
	    $pReplyBody = GetParameterNoCode("replybody",$json);
	    if(IsNull($pReplyBody)){ return JsonModelParameterNull("replybody"); }
	    $pReplyBody = HandleStringFlyHtmlEncode($pReplyBody);
	    //参数:回复图片一:replyOneImage
	    $pReplyOneImage = GetParameterNoCode("replyoneimage",$json);
	    if(!IsNull($pReplyOneImage)&&!JudgeRegularUrl($pReplyOneImage)){return JsonModelParameterException("replyoneimage", $pReplyOneImage, 128, "URL地址格式错误", __LINE__);}
	    $pReplyOneImage = HandleStringNone($pReplyOneImage);

	    //参数:回复图片二:replyTwoImage
	    $pReplyTwoImage = GetParameterNoCode("replytwoimage",$json);
	    if(!IsNull($pReplyTwoImage)&&!JudgeRegularUrl($pReplyTwoImage)){return JsonModelParameterException("replytwoimage", $pReplyTwoImage, 128, "URL地址格式错误", __LINE__);}
	    $pReplyTwoImage = HandleStringNone($pReplyTwoImage);

	    //参数:回复图片三:replyThreeImage
	    $pReplyThreeImage = GetParameterNoCode("replythreeimage",$json);
	    if(!IsNull($pReplyThreeImage)&&!JudgeRegularUrl($pReplyThreeImage)){return JsonModelParameterException("replythreeimage", $pReplyThreeImage, 128, "URL地址格式错误", __LINE__);}
	    $pReplyThreeImage = HandleStringNone($pReplyThreeImage);

	    //参数:回复图片四:replyFourImage
	    $pReplyFourImage = GetParameterNoCode("replyfourimage",$json);
	    if(!IsNull($pReplyFourImage)&&!JudgeRegularUrl($pReplyFourImage)){return JsonModelParameterException("replyfourimage", $pReplyFourImage, 128, "URL地址格式错误", __LINE__);}
	    $pReplyFourImage = HandleStringNone($pReplyFourImage);

	    //参数:回复图片五:replyFiveImage
	    $pReplyFiveImage = GetParameterNoCode("replyfiveimage",$json);
	    if(!IsNull($pReplyFiveImage)&&!JudgeRegularUrl($pReplyFiveImage)){return JsonModelParameterException("replyfiveimage", $pReplyFiveImage, 128, "URL地址格式错误", __LINE__);}
	    $pReplyFiveImage = HandleStringNone($pReplyFiveImage);
        
	    //获取:fly_work_order:发起人ID、接收人ID
	    $vSql = "SELECT originatorId,receiverId,workOrderState FROM fly_work_order WHERE id=?;";
	    $vFlyWorkOrderList = DBHelper::DataList($vSql, [$pWorkorderId], ["originatorId","receiverId"]);
	    if(IsNull($vFlyWorkOrderList)){ return JsonInforFalse("工单不存在", "fly_work_order", __LINE__); }
	    $pFlyWorkOrderFirst = GetJsonMember($vFlyWorkOrderList);
	    $vOriginatorId = $pFlyWorkOrderFirst -> originatorId;	//发起人ID
	    $vReceiverId = $pFlyWorkOrderFirst -> receiverId;	//接收人ID
	    $vWorkOrderState = $pFlyWorkOrderFirst -> workOrderState;	//工单状态
	    
	    //工单已结束
	    if($vWorkOrderState == "WORK_ORDER_OVER"){
	        return JsonInforFalse("工单已结束", "工单ID：{$pWorkorderId}");
	    }
	    
	    //判断是否是指定人员可操作的工单
	    if(!($fpAdminId==$vOriginatorId||$fpAdminId==$vReceiverId)){
	        return JsonInforFalse("您没有操作权限", "{$fpAdminId}!={$vOriginatorId}/{$vReceiverId}");
	    }
	    
	    //回复类型判断
	    $pReplyTypeTips = "";
	    if($fpAdminId==$vOriginatorId){
	        $pReplyType = "提问";
	        $pReplyTypeTips = "回复"; 
	    }else if($fpAdminId==$vReceiverId){
	        $pReplyType = "回复";
	        $pReplyTypeTips = "提问";
	    }
	    
	    //获取:fly_work_order_recode:回复者ID
	    $vSql = "SELECT responderId FROM fly_work_order_recode WHERE workorderId=? ORDER BY id DESC LIMIT 0,1;";
	    $vResponderId = DBHelper::DataString($vSql, [$pWorkorderId]);
	    if(IsNull($vResponderId)&&$fpAdminId==$vOriginatorId){ 
	        return JsonInforFalse("请等待回复", "fly_work_order_recode", __LINE__); 
	    }
	    if($vResponderId==$fpAdminId){
	        return JsonInforFalse("已{$pReplyType},请等待{$pReplyTypeTips}！", "工单ID：{$pWorkorderId}");
	    }
	    
        //--- Json组合区 ---
        //Json数组
        $jsonKeyValueArray = array(
            "table_name" => self::$tableName,
            "insert_field" => "descript,shelfState,workorderId,responderId,replyType,replyBody,replyOneImage,replyTwoImage,replyThreeImage,replyFourImage,replyFiveImage",
            "descript" => self::$classDescript,
            "shelfstate" => $pShelfState,
            "workorderid" => $pWorkorderId,
            "responderId" => $fpAdminId,
            "replytype" => $pReplyType,
            "replybody" => $pReplyBody,
            "replyoneimage" => $pReplyOneImage,
            "replytwoimage" => $pReplyTwoImage,
            "replythreeimage" => $pReplyThreeImage,
            "replyfourimage" => $pReplyFourImage,
            "replyfiveimage" => $pReplyFiveImage,
        );
        //执行:添加
        $vJsonResult = MIndexDataInsert(JsonHandleArray($jsonKeyValueArray));
        if(JudgeJsonTrue($vJsonResult)){
            //--- 添加沟通次数 ---
            if($fpAdminId==$vOriginatorId){
                //沟通
                $vSqlTimes = "UPDATE fly_work_order SET workOrderQuestionsTimes=workOrderQuestionsTimes+1,workOrderState='WORK_ORDER_QUESTION',workOrderStateIndex='200' WHERE id=?;";
                DBHelper::DataSubmit($vSqlTimes, [$pWorkorderId]);
            }else if($fpAdminId==$vReceiverId){
                //回复
                $vSqlTimes = "UPDATE fly_work_order SET workOrderReplyTimes=workOrderReplyTimes+1,workOrderState='WORK_ORDER_REPLY',workOrderStateIndex='200' WHERE id=?;";
                DBHelper::DataSubmit($vSqlTimes, [$pWorkorderId]);
            }
            //返回成功添加工单结果
            return JsonInforTrue($pReplyType."成功", "工单ID：{$pWorkorderId}");
        }
        return $vJsonResult;
    }


    /**
     * 函数名称：工单回复记录:管理员:记录查询
     * 函数调用：ObjFlyWorkOrderRecode() -> AdminFlyWorkOrderRecodePaging($fpAdminId)
     * 创建时间：2020-02-29 14:32:52
     * */
    public function AdminFlyWorkOrderRecodePaging($fpAdminId){

        //--- 参数限制区 ---
        $vParameterArray = GetArray(FLY_PARAMETER_LIMIT, ",");
        $bParameterJudge = JudgeParameterLimit($vParameterArray);
        if(JudgeJsonFalse($bParameterJudge)){return $bParameterJudge;}

        //--- 数据预定义 ---
        $json = "";

        //--- 参数获取区 ---
        //$pPage = GetParameterNoCode("page","");     //参数:页码:page
        //$pLimit = GetParameterNoCode("limit","");   //参数:条数:limit
        $pPage = 1;
        $pLimit = 1000;
        
        //参数:工单ID:workorderId
        $pWorkorderId = GetParameterNoCode("workorderid",$json);
        if(!JudgeRegularInt($pWorkorderId)){return JsonModelParameterException("workorderid", $pWorkorderId, 11, "值必须是整数", __LINE__);}

        //参数：id
        $pId = GetParameterNoCode("id","");
        if(!IsNull($pId)&&!JudgeRegularIntRight($pId)){return JsonModelParameterException("id", $pId, 20, "值必须是正整数", __LINE__);}
        if(!IsNull($pId)){ $pPage = 1; $pLimit = 1; }

        //参数判断:页码:page
        if(IsNull($pPage)||!JudgeRegularIntRight($pPage)){return JsonModelParameterException("page", $pPage, 9, "值必须是正整数", __LINE__);}
        //参数判断:条数:limit
        if(IsNull($pLimit)||!JudgeRegularIntRight($pLimit)){return JsonModelParameterException("limit", $pLimit, 9, "值必须是正整数", __LINE__);}

        //参数：debug
        $pDebug = GetParameterNoCode("debug","");
        if($pDebug!="true"){$pDebug = "false";}

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

        //获取:fly_work_order:发起人ID、接收人ID
        $vSql = "SELECT id FROM fly_work_order WHERE id=?;";
        if(!DBHelper::DataBoolean($vSql, [$pWorkorderId])){ return JsonInforFalse("工单不存在", "fly_work_order", __LINE__); }
        
        //字段定义
        $vDataField = DBMySQLSelect::TableFieldString(self::$tableName);
        //$vDataField = "id,onlyKey,descript,indexNumber,updateTime,addTime,shelfState,state,workorderId,replyType,replyBody,replyOneImage,replyTwoImage,replyThreeImage,replyFourImage,replyFiveImage";

        //--- Json组合区 ---
        $jsonKeyValueArray = array(
        	"table_name" => self::$tableName,
        	"data_field" => $vDataField,
        	"where_field" => "workorderId",
        	"where_value" => $pWorkorderId,
        	"page" => $pPage,
        	"limit" => $pLimit,
        	"like_field" => $pLikeField,
        	"like_key" => $pLikeKey,
        	"sql_debug" => $pDebug,
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
     * 函数名称：工单回复记录:管理员:记录修改
     * 函数调用：ObjFlyWorkOrderRecode() -> AdminFlyWorkOrderRecodeSet($fpAdminId)
     * 创建时间：2020-02-29 14:32:52
     * */
    public function AdminFlyWorkOrderRecodeSet($fpAdminId){

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

	    //参数:工单ID:workorderId
	    $pWorkorderId = GetParameterNoCode("workorderid",$json);
	    if(!IsNull($pWorkorderId)&&!JudgeRegularInt($pWorkorderId)){return JsonModelParameterException("workorderid", $pWorkorderId, 11, "值必须是整数", __LINE__);}

	    //参数:回复类型[提问|回复]:replyType
	    $pReplyType = GetParameterNoCode("replytype",$json);
	    if(!IsNull($pReplyType)&&!JudgeRegularFont($pReplyType)){return JsonModelParameterException("replytype", $pReplyType, 36, "内容格式错误", __LINE__);}

	    //参数:回复内容:replyBody
	    $pReplyBody = GetParameterNoCode("replybody",$json);
	    if(!IsNull($pReplyBody)){ $pReplyBody = HandleStringAddslashes(HandleStringFlyHtmlEncode($pReplyBody)); }

	    //参数:回复图片一:replyOneImage
	    $pReplyOneImage = GetParameterNoCode("replyoneimage",$json);
	    if(!IsNull($pReplyOneImage)&&!JudgeRegularUrl($pReplyOneImage)){return JsonModelParameterException("replyoneimage", $pReplyOneImage, 128, "URL地址格式错误", __LINE__);}

	    //参数:回复图片二:replyTwoImage
	    $pReplyTwoImage = GetParameterNoCode("replytwoimage",$json);
	    if(!IsNull($pReplyTwoImage)&&!JudgeRegularUrl($pReplyTwoImage)){return JsonModelParameterException("replytwoimage", $pReplyTwoImage, 128, "URL地址格式错误", __LINE__);}

	    //参数:回复图片三:replyThreeImage
	    $pReplyThreeImage = GetParameterNoCode("replythreeimage",$json);
	    if(!IsNull($pReplyThreeImage)&&!JudgeRegularUrl($pReplyThreeImage)){return JsonModelParameterException("replythreeimage", $pReplyThreeImage, 128, "URL地址格式错误", __LINE__);}

	    //参数:回复图片四:replyFourImage
	    $pReplyFourImage = GetParameterNoCode("replyfourimage",$json);
	    if(!IsNull($pReplyFourImage)&&!JudgeRegularUrl($pReplyFourImage)){return JsonModelParameterException("replyfourimage", $pReplyFourImage, 128, "URL地址格式错误", __LINE__);}

	    //参数:回复图片五:replyFiveImage
	    $pReplyFiveImage = GetParameterNoCode("replyfiveimage",$json);
	    if(!IsNull($pReplyFiveImage)&&!JudgeRegularUrl($pReplyFiveImage)){return JsonModelParameterException("replyfiveimage", $pReplyFiveImage, 128, "URL地址格式错误", __LINE__);}

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
	    $jsonKeyValueArray = HandleFlyJsonAddUpdateField($jsonKeyValueArray, "workorderId", $pWorkorderId);
	    $jsonKeyValueArray = HandleFlyJsonAddUpdateField($jsonKeyValueArray, "replyType", $pReplyType);
	    $jsonKeyValueArray = HandleFlyJsonAddUpdateField($jsonKeyValueArray, "replyBody", $pReplyBody);
	    $jsonKeyValueArray = HandleFlyJsonAddUpdateField($jsonKeyValueArray, "replyOneImage", $pReplyOneImage);
	    $jsonKeyValueArray = HandleFlyJsonAddUpdateField($jsonKeyValueArray, "replyTwoImage", $pReplyTwoImage);
	    $jsonKeyValueArray = HandleFlyJsonAddUpdateField($jsonKeyValueArray, "replyThreeImage", $pReplyThreeImage);
	    $jsonKeyValueArray = HandleFlyJsonAddUpdateField($jsonKeyValueArray, "replyFourImage", $pReplyFourImage);
	    $jsonKeyValueArray = HandleFlyJsonAddUpdateField($jsonKeyValueArray, "replyFiveImage", $pReplyFiveImage);

	    //判断字段值是否为空
	    $vUpdateFieldJudgeInfor = JudgeFlyJsonAddUpdateField($jsonKeyValueArray,"descript,indexnumber,shelfstate,state,workorderid,replytype,replybody,replyoneimage,replytwoimage,replythreeimage,replyfourimage,replyfiveimage");
	    if(JudgeJsonFalse($vUpdateFieldJudgeInfor)){ return $vUpdateFieldJudgeInfor; }

	    //返回结果
	    return MIndexDataUpdate(JsonHandleArray($jsonKeyValueArray));

    }


    /**
     * 函数名称：工单回复记录:管理员:记录状态修改
     * 函数调用：ObjFlyWorkOrderRecode() -> AdminFlyWorkOrderRecodeSetState($fpAdminId)
     * 创建时间：2020-02-29 14:32:52
     * */
    public function AdminFlyWorkOrderRecodeSetState($fpAdminId){

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
     * 函数名称：工单回复记录:管理员:数据上下架状态修改
     * 函数调用：ObjFlyWorkOrderRecode() -> AdminFlyWorkOrderRecodeShelfState($fpAdminId)
     * 创建时间：2020-02-29 14:32:52
     * */
    public function AdminFlyWorkOrderRecodeShelfState($fpAdminId){
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
     * 函数名称：工单回复记录:管理员:记录永久删除
     * 函数调用：ObjFlyWorkOrderRecode() -> AdminFlyWorkOrderRecodeDelete($fpAdminId)
     * 创建时间：2020-02-29 14:32:52
     * */
    public function AdminFlyWorkOrderRecodeDelete($fpAdminId){
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
     * 函数调用：ObjFlyWorkOrderRecode() -> GetTableName()
     * 创建时间：2020-02-29 14:32:52
     * */
    public function GetTableName(){
        return self::$tableName;
    }

    /**
     * 函数名称：获取类描述
     * 函数调用：ObjFlyWorkOrderRecode() -> GetClassDescript()
     * 创建时间：2020-02-29 14:32:52
     * */
    public function GetClassDescript(){
        return self::$classDescript;
    }

    /**
     * 函数名称：获取数据表字段
     * 函数调用：ObjFlyWorkOrderRecode() -> GetTableField()
     * 创建时间：2020-02-29 14:32:52
     * */
    public function GetTableField(){
        return MIndexDBTableFields(JsonObj(JsonKeyValue("table_name", self::$tableName)));
    }

    /**
     * 函数名称：类数据表创建
     * 函数调用：ObjFlyWorkOrderRecode() -> OprationCreateTable()
     * 创建时间：2020-02-29 14:32:52
     * */
    public function OprationCreateTable(){
        $sql = "CREATE TABLE `fly_work_order_recode` (  `id` bigint(20) NOT NULL AUTO_INCREMENT COMMENT '表ID',  `onlyKey` varchar(36) DEFAULT NULL COMMENT '唯一Key',  `descript` varchar(36) DEFAULT NULL COMMENT '记录描述',  `indexNumber` int(11) DEFAULT '-1' COMMENT '序号',  `updateTime` timestamp NULL DEFAULT NULL COMMENT '修改时间',  `addTime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '添加时间',  `shelfState` varchar(36) DEFAULT 'true' COMMENT '上下架状态',  `state` varchar(36) DEFAULT 'STATE_NORMAL' COMMENT '记录状态',  `workorderId` int(11) DEFAULT NULL COMMENT '工单ID',  `replyType` varchar(36) DEFAULT NULL COMMENT '回复类型[提问|回复]',  `replyBody` text COMMENT '回复内容',  `replyOneImage` varchar(128) DEFAULT NULL COMMENT '回复图片一',  `replyTwoImage` varchar(128) DEFAULT NULL COMMENT '回复图片二',  `replyThreeImage` varchar(128) DEFAULT NULL COMMENT '回复图片三',  `replyFourImage` varchar(128) DEFAULT NULL COMMENT '回复图片四',  `replyFiveImage` varchar(128) DEFAULT NULL COMMENT '回复图片五',  PRIMARY KEY (`id`) USING BTREE) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='工单回复记录'";
        DBHelper::DataSubmit($sql, "");
        $vTableCheck = DBMySQLServiceJson::GetDBTableCheck('{"table_name":"'.self::$tableName.'"}');
        if(JudgeJsonTrue($vTableCheck)){
            return JsonInforTrue("创建成功", self::$tableName);
        }
        return JsonInforFalse("创建失败", self::$tableName);
    }

    /**
     * 函数名称：类数据表基础字段检测
     * 函数调用：ObjFlyWorkOrderRecode() -> OprationTableFieldBaseCheck()
     * 创建时间：2020-02-29 14:32:52
     * */
    public function OprationTableFieldBaseCheck(){
        return DBMySQLServiceJson::OprationFieldBaseCheck(JsonObj(JsonKeyValue("table_name", self::$tableName)));
    }




}
