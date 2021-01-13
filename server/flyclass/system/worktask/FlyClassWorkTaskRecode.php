<?php

/**------------------------------------*
  * 作者：shark
  * 创建时间：2020-03-02 17:19:56
  * Fly编码：1583140796841FLY247791
  * 类对象名：ObjFlyWorkTaskRecode()
  * ------------------------------------ */

//引入区

class FlyClassWorkTaskRecode{


    //---------- 类成员（member） ----------

    //类描述
    public static $classDescript = "工作完成记录";
    //类数据表名
    public static $tableName = "fly_work_task_recode";


    //---------- 私有方法（private） ----------

    //---------- 游客方法（visitor） ----------

    /**
     * 函数名称：工作完成记录:游客:记录查询
     * 函数调用：ObjFlyWorkTaskRecode() -> VisitorFlyWorkTaskRecodePaging()
     * 创建时间：2020-03-02 17:19:56
     * */
    public function VisitorFlyWorkTaskRecodePaging(){

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
        //$vDataField = "id,onlyKey,descript,indexNumber,updateTime,addTime,shelfState,state,workTaskId,workBody,workOneImage,workTwoImage,workThreeImage,workFourImage,workFiveImage";

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
     * 函数名称：工作完成记录:系统:记录添加
     * 函数调用：ObjFlyWorkTaskRecode() -> SystemFlyWorkTaskRecodeAdd
     * 创建时间：2020-03-02 17:19:56
     * */
    public function SystemFlyWorkTaskRecodeAdd($fpWorkTaskId,$fpWorkBody,$fpWorkOneImage,$fpWorkTwoImage,$fpWorkThreeImage,$fpWorkFourImage,$fpWorkFiveImage){

        //--- Json组合区 ---
        //Json数组
        $jsonKeyValueArray = array(
            "table_name" => self::$tableName,
            "insert_field" => "descript,shelfState,workTaskId,workBody,workOneImage,workTwoImage,workThreeImage,workFourImage,workFiveImage",
            "descript" => self::$classDescript,
            "shelfstate" => "false",
            "worktaskid" => $fpWorkTaskId,
            "workbody" => $fpWorkBody,
            "workoneimage" => $fpWorkOneImage,
            "worktwoimage" => $fpWorkTwoImage,
            "workthreeimage" => $fpWorkThreeImage,
            "workfourimage" => $fpWorkFourImage,
            "workfiveimage" => $fpWorkFiveImage,
            "key_field" => "workTaskId",
        );
        //执行:添加
        $vJsonResult = MIndexDataInsert(JsonHandleArray($jsonKeyValueArray));
        return $vJsonResult;
    }


    //---------- 用户方法（user） ----------

    /**
     * 函数名称：工作完成记录:用户:记录查询
     * 函数调用：ObjFlyWorkTaskRecode() -> UserFlyWorkTaskRecodePaging($fpUserId)
     * 创建时间：2020-03-02 17:19:56
     * */
    public function UserFlyWorkTaskRecodePaging($fpUserId){

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
        //$vDataField = "id,onlyKey,descript,indexNumber,updateTime,addTime,shelfState,state,workTaskId,workBody,workOneImage,workTwoImage,workThreeImage,workFourImage,workFiveImage";

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
     * 函数名称：工作完成记录:管理员:记录添加
     * 函数调用：ObjFlyWorkTaskRecode() -> AdminFlyWorkTaskRecodeAdd($fpAdminId)
     * 创建时间：2020-03-02 17:19:56
     * */
    public function AdminFlyWorkTaskRecodeAdd($fpAdminId){


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

	    //参数:工作任务ID:workTaskId
	    $pWorkTaskId = GetParameterNoCode("worktaskid",$json);
	    if(!JudgeRegularInt($pWorkTaskId)){return JsonModelParameterException("worktaskid", $pWorkTaskId, 11, "值必须是整数", __LINE__);}

	    //参数:完成内容:workBody
	    $pWorkBody = GetParameterNoCode("workbody",$json);
	    if(!JudgeRegularFont($pWorkBody)){return JsonModelParameterException("workbody", $pWorkBody, 512, "内容格式错误", __LINE__);}

	    //参数:图片一:workOneImage
	    $pWorkOneImage = GetParameterNoCode("workoneimage",$json);
	    if(!IsNull($pWorkOneImage)&&!JudgeRegularUrl($pWorkOneImage)){return JsonModelParameterException("workoneimage", $pWorkOneImage, 128, "URL地址格式错误", __LINE__);}
	    $pWorkOneImage = HandleStringNone($pWorkOneImage);

	    //参数:图片二:workTwoImage
	    $pWorkTwoImage = GetParameterNoCode("worktwoimage",$json);
	    if(!IsNull($pWorkTwoImage)&&!JudgeRegularUrl($pWorkTwoImage)){return JsonModelParameterException("worktwoimage", $pWorkTwoImage, 128, "URL地址格式错误", __LINE__);}
	    $pWorkTwoImage = HandleStringNone($pWorkTwoImage);

	    //参数:图片三:workThreeImage
	    $pWorkThreeImage = GetParameterNoCode("workthreeimage",$json);
	    if(!IsNull($pWorkThreeImage)&&!JudgeRegularUrl($pWorkThreeImage)){return JsonModelParameterException("workthreeimage", $pWorkThreeImage, 128, "URL地址格式错误", __LINE__);}
	    $pWorkThreeImage = HandleStringNone($pWorkThreeImage);

	    //参数:图片四:workFourImage
	    $pWorkFourImage = GetParameterNoCode("workfourimage",$json);
	    if(!IsNull($pWorkFourImage)&&!JudgeRegularUrl($pWorkFourImage)){return JsonModelParameterException("workfourimage", $pWorkFourImage, 128, "URL地址格式错误", __LINE__);}
	    $pWorkFourImage = HandleStringNone($pWorkFourImage);

	    //参数:图片五:workFiveImage
	    $pWorkFiveImage = GetParameterNoCode("workfiveimage",$json);
	    if(!IsNull($pWorkFiveImage)&&!JudgeRegularUrl($pWorkFiveImage)){return JsonModelParameterException("workfiveimage", $pWorkFiveImage, 128, "URL地址格式错误", __LINE__);}
	    $pWorkFiveImage = HandleStringNone($pWorkFiveImage);

        //获取:fly_work_task:工作者ID、工作状态
        $vSql = "SELECT workerId,workState FROM fly_work_task WHERE id=?;";
        $vFlyWorkTaskList = DBHelper::DataList($vSql, [$pWorkTaskId], ["workerId","workState"]);
        if(IsNull($vFlyWorkTaskList)){ return JsonInforFalse("记录不存在", "fly_work_task", __LINE__); }
        $pFlyWorkTaskFirst = GetJsonMember($vFlyWorkTaskList);
        $vWorkerId = $pFlyWorkTaskFirst -> workerId;	//工作者ID
        $vWorkState = $pFlyWorkTaskFirst -> workState;	//工作状态
	    
	    //操作权限判断
	    if($vWorkerId!=$fpAdminId){
	        return JsonInforFalse("您没有操作权限", "{$vWorkerId}!={$fpAdminId}");
	    }
	    
	    //工单已结束
	    if($vWorkState == "WORK_OVER"){
	        return JsonInforFalse("工作已完成", "工作ID：{$pWorkTaskId}");
	    }
	    
        //--- Json组合区 ---
        //Json数组
        $jsonKeyValueArray = array(
            "table_name" => self::$tableName,
            "insert_field" => "descript,shelfState,workTaskId,workBody,workOneImage,workTwoImage,workThreeImage,workFourImage,workFiveImage",
            "descript" => self::$classDescript,
            "shelfstate" => $pShelfState,
            "worktaskid" => $pWorkTaskId,
            "workbody" => $pWorkBody,
            "workoneimage" => $pWorkOneImage,
            "worktwoimage" => $pWorkTwoImage,
            "workthreeimage" => $pWorkThreeImage,
            "workfourimage" => $pWorkFourImage,
            "workfiveimage" => $pWorkFiveImage,
        );
        //执行:添加
        $vJsonResult = MIndexDataInsert(JsonHandleArray($jsonKeyValueArray));
        if(JudgeJsonTrue($vJsonResult)){
            //修改:fly_work_task:任务更新次数
            $vSql = "UPDATE fly_work_task SET workState='WORK_BRING',workReplaceTimes=workReplaceTimes+1 WHERE id=?;";
            $vUpdateResult = DBHelper::DataSubmit($vSql, [$pWorkTaskId]);
        }
        return $vJsonResult;
    }


    /**
     * 函数名称：工作完成记录:管理员:记录查询
     * 函数调用：ObjFlyWorkTaskRecode() -> AdminFlyWorkTaskRecodePaging($fpAdminId)
     * 创建时间：2020-03-02 17:19:56
     * */
    public function AdminFlyWorkTaskRecodePaging($fpAdminId){

        //--- 参数限制区 ---
        $vParameterArray = GetArray(FLY_PARAMETER_LIMIT, ",");
        $bParameterJudge = JudgeParameterLimit($vParameterArray);
        if(JudgeJsonFalse($bParameterJudge)){return $bParameterJudge;}

        //--- 数据预定义 ---
        $json = "";

        //--- 参数获取区 ---
        //参数:工作任务ID:workTaskId
        $pWorkTaskId = GetParameterNoCode("worktaskid",$json);
        if(!JudgeRegularInt($pWorkTaskId)){return JsonModelParameterException("worktaskid", $pWorkTaskId, 11, "值必须是整数", __LINE__);}
        
        //$pPage = GetParameterNoCode("page","");     //参数:页码:page
        //$pLimit = GetParameterNoCode("limit","");   //参数:条数:limit
        $pPage = "1";
        $pLimit = "1000";

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
        //$vDataField = "id,onlyKey,descript,indexNumber,updateTime,addTime,shelfState,state,workTaskId,workBody,workOneImage,workTwoImage,workThreeImage,workFourImage,workFiveImage";

        //--- Json组合区 ---
        $jsonKeyValueArray = array(
        	"table_name" => self::$tableName,
        	"data_field" => $vDataField,
        	"where_field" => "workTaskId",
        	"where_value" => "{$pWorkTaskId}",
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
     * 函数名称：工作完成记录:管理员:记录修改
     * 函数调用：ObjFlyWorkTaskRecode() -> AdminFlyWorkTaskRecodeSet($fpAdminId)
     * 创建时间：2020-03-02 17:19:56
     * */
    public function AdminFlyWorkTaskRecodeSet($fpAdminId){

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

	    //参数:工作任务ID:workTaskId
	    $pWorkTaskId = GetParameterNoCode("worktaskid",$json);
	    if(!IsNull($pWorkTaskId)&&!JudgeRegularInt($pWorkTaskId)){return JsonModelParameterException("worktaskid", $pWorkTaskId, 11, "值必须是整数", __LINE__);}

	    //参数:完成内容:workBody
	    $pWorkBody = GetParameterNoCode("workbody",$json);
	    if(!IsNull($pWorkBody)&&!JudgeRegularFont($pWorkBody)){return JsonModelParameterException("workbody", $pWorkBody, 512, "内容格式错误", __LINE__);}

	    //参数:图片一:workOneImage
	    $pWorkOneImage = GetParameterNoCode("workoneimage",$json);
	    if(!IsNull($pWorkOneImage)&&!JudgeRegularUrl($pWorkOneImage)){return JsonModelParameterException("workoneimage", $pWorkOneImage, 128, "URL地址格式错误", __LINE__);}

	    //参数:图片二:workTwoImage
	    $pWorkTwoImage = GetParameterNoCode("worktwoimage",$json);
	    if(!IsNull($pWorkTwoImage)&&!JudgeRegularUrl($pWorkTwoImage)){return JsonModelParameterException("worktwoimage", $pWorkTwoImage, 128, "URL地址格式错误", __LINE__);}

	    //参数:图片三:workThreeImage
	    $pWorkThreeImage = GetParameterNoCode("workthreeimage",$json);
	    if(!IsNull($pWorkThreeImage)&&!JudgeRegularUrl($pWorkThreeImage)){return JsonModelParameterException("workthreeimage", $pWorkThreeImage, 128, "URL地址格式错误", __LINE__);}

	    //参数:图片四:workFourImage
	    $pWorkFourImage = GetParameterNoCode("workfourimage",$json);
	    if(!IsNull($pWorkFourImage)&&!JudgeRegularUrl($pWorkFourImage)){return JsonModelParameterException("workfourimage", $pWorkFourImage, 128, "URL地址格式错误", __LINE__);}

	    //参数:图片五:workFiveImage
	    $pWorkFiveImage = GetParameterNoCode("workfiveimage",$json);
	    if(!IsNull($pWorkFiveImage)&&!JudgeRegularUrl($pWorkFiveImage)){return JsonModelParameterException("workfiveimage", $pWorkFiveImage, 128, "URL地址格式错误", __LINE__);}

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
	    $jsonKeyValueArray = HandleFlyJsonAddUpdateField($jsonKeyValueArray, "workTaskId", $pWorkTaskId);
	    $jsonKeyValueArray = HandleFlyJsonAddUpdateField($jsonKeyValueArray, "workBody", $pWorkBody);
	    $jsonKeyValueArray = HandleFlyJsonAddUpdateField($jsonKeyValueArray, "workOneImage", $pWorkOneImage);
	    $jsonKeyValueArray = HandleFlyJsonAddUpdateField($jsonKeyValueArray, "workTwoImage", $pWorkTwoImage);
	    $jsonKeyValueArray = HandleFlyJsonAddUpdateField($jsonKeyValueArray, "workThreeImage", $pWorkThreeImage);
	    $jsonKeyValueArray = HandleFlyJsonAddUpdateField($jsonKeyValueArray, "workFourImage", $pWorkFourImage);
	    $jsonKeyValueArray = HandleFlyJsonAddUpdateField($jsonKeyValueArray, "workFiveImage", $pWorkFiveImage);

	    //判断字段值是否为空
	    $vUpdateFieldJudgeInfor = JudgeFlyJsonAddUpdateField($jsonKeyValueArray,"descript,indexnumber,shelfstate,state,worktaskid,workbody,workoneimage,worktwoimage,workthreeimage,workfourimage,workfiveimage");
	    if(JudgeJsonFalse($vUpdateFieldJudgeInfor)){ return $vUpdateFieldJudgeInfor; }

	    //返回结果
	    return MIndexDataUpdate(JsonHandleArray($jsonKeyValueArray));

    }


    /**
     * 函数名称：工作完成记录:管理员:记录状态修改
     * 函数调用：ObjFlyWorkTaskRecode() -> AdminFlyWorkTaskRecodeSetState($fpAdminId)
     * 创建时间：2020-03-02 17:19:56
     * */
    public function AdminFlyWorkTaskRecodeSetState($fpAdminId){

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
     * 函数名称：工作完成记录:管理员:数据上下架状态修改
     * 函数调用：ObjFlyWorkTaskRecode() -> AdminFlyWorkTaskRecodeShelfState($fpAdminId)
     * 创建时间：2020-03-02 17:19:56
     * */
    public function AdminFlyWorkTaskRecodeShelfState($fpAdminId){
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
     * 函数名称：工作完成记录:管理员:记录永久删除
     * 函数调用：ObjFlyWorkTaskRecode() -> AdminFlyWorkTaskRecodeDelete($fpAdminId)
     * 创建时间：2020-03-02 17:19:56
     * */
    public function AdminFlyWorkTaskRecodeDelete($fpAdminId){
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
     * 函数调用：ObjFlyWorkTaskRecode() -> GetTableName()
     * 创建时间：2020-03-02 17:19:56
     * */
    public function GetTableName(){
        return self::$tableName;
    }

    /**
     * 函数名称：获取类描述
     * 函数调用：ObjFlyWorkTaskRecode() -> GetClassDescript()
     * 创建时间：2020-03-02 17:19:56
     * */
    public function GetClassDescript(){
        return self::$classDescript;
    }

    /**
     * 函数名称：获取数据表字段
     * 函数调用：ObjFlyWorkTaskRecode() -> GetTableField()
     * 创建时间：2020-03-02 17:19:56
     * */
    public function GetTableField(){
        return MIndexDBTableFields(JsonObj(JsonKeyValue("table_name", self::$tableName)));
    }

    /**
     * 函数名称：类数据表创建
     * 函数调用：ObjFlyWorkTaskRecode() -> OprationCreateTable()
     * 创建时间：2020-03-02 17:19:56
     * */
    public function OprationCreateTable(){
        $sql = "CREATE TABLE `fly_work_task_recode` (  `id` bigint(20) NOT NULL AUTO_INCREMENT COMMENT '表ID',  `onlyKey` varchar(36) DEFAULT NULL COMMENT '唯一Key',  `descript` varchar(36) DEFAULT NULL COMMENT '记录描述',  `indexNumber` int(11) DEFAULT '-1' COMMENT '序号',  `updateTime` timestamp NULL DEFAULT NULL COMMENT '修改时间',  `addTime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '添加时间',  `shelfState` varchar(36) DEFAULT 'true' COMMENT '上下架状态',  `state` varchar(36) DEFAULT 'STATE_NORMAL' COMMENT '记录状态',  `workTaskId` int(11) DEFAULT NULL COMMENT '工作任务ID',  `workBody` varchar(512) DEFAULT NULL COMMENT '完成内容',  `workOneImage` varchar(128) DEFAULT NULL COMMENT '图片一',  `workTwoImage` varchar(128) DEFAULT NULL COMMENT '图片二',  `workThreeImage` varchar(128) DEFAULT NULL COMMENT '图片三',  `workFourImage` varchar(128) DEFAULT NULL COMMENT '图片四',  `workFiveImage` varchar(128) DEFAULT NULL COMMENT '图片五',  PRIMARY KEY (`id`) USING BTREE) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='工作完成记录'";
        DBHelper::DataSubmit($sql, "");
        $vTableCheck = DBMySQLServiceJson::GetDBTableCheck('{"table_name":"'.self::$tableName.'"}');
        if(JudgeJsonTrue($vTableCheck)){
            return JsonInforTrue("创建成功", self::$tableName);
        }
        return JsonInforFalse("创建失败", self::$tableName);
    }

    /**
     * 函数名称：类数据表基础字段检测
     * 函数调用：ObjFlyWorkTaskRecode() -> OprationTableFieldBaseCheck()
     * 创建时间：2020-03-02 17:19:56
     * */
    public function OprationTableFieldBaseCheck(){
        return DBMySQLServiceJson::OprationFieldBaseCheck(JsonObj(JsonKeyValue("table_name", self::$tableName)));
    }




}
