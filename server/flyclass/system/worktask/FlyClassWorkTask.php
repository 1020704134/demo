<?php

/**------------------------------------*
  * 作者：shark
  * 创建时间：2020-03-01 19:51:39
  * Fly编码：1583063499646FLY617482
  * 类对象名：ObjFlyWorkTask()
  * ------------------------------------ */

//引入区

class FlyClassWorkTask{


    //---------- 类成员（member） ----------

    //类描述
    public static $classDescript = "工作任务";
    //类数据表名
    public static $tableName = "fly_work_task";


    //---------- 私有方法（private） ----------

    //---------- 游客方法（visitor） ----------

    /**
     * 函数名称：工作任务列表:游客:记录查询
     * 函数调用：ObjFlyWorkTask() -> VisitorFlyWorkTaskPaging()
     * 创建时间：2020-03-01 19:51:39
     * */
    public function VisitorFlyWorkTaskPaging(){

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
        //$vDataField = "id,onlyKey,descript,indexNumber,updateTime,addTime,shelfState,state,workerId,workerName,workType,workTypeIndex,workPowerType,workPowerTypeIndex,workTitle,workBody,workOneImage,workTwoImage,workThreeImage,workFourImage,workFiveImage,workTime,workState,workStateIndex";

        //--- Json组合区 ---
        $jsonKeyValueArray = array(
        	"table_name" => self::$tableName,
        	"data_field" => $vDataField,
        	"where_field" => "shelfState",
        	"where_value" => "true",
        	"page" => $pPage,
        	"limit" => $pLimit,
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
     * 函数名称：工作任务列表:系统:记录添加
     * 函数调用：ObjFlyWorkTask() -> SystemFlyWorkTaskAdd
     * 创建时间：2020-03-01 19:51:39
     * */
    public function SystemFlyWorkTaskAdd($fpWorkerId,$fpWorkerName,$fpWorkType,$fpWorkTypeIndex,$fpWorkPowerType,$fpWorkPowerTypeIndex,$fpWorkTitle,$fpWorkBody,$fpWorkOneImage,$fpWorkTwoImage,$fpWorkThreeImage,$fpWorkFourImage,$fpWorkFiveImage,$fpWorkTime,$fpWorkState,$fpWorkStateIndex){

        //--- Json组合区 ---
        //Json数组
        $jsonKeyValueArray = array(
            "table_name" => self::$tableName,
            "insert_field" => "descript,shelfState,workerId,workerName,workType,workTypeIndex,workPowerType,workPowerTypeIndex,workTitle,workBody,workOneImage,workTwoImage,workThreeImage,workFourImage,workFiveImage,workTime,workState,workStateIndex",
            "descript" => self::$classDescript,
            "shelfstate" => "false",
            "workerid" => $fpWorkerId,
            "workername" => $fpWorkerName,
            "worktype" => $fpWorkType,
            "worktypeindex" => $fpWorkTypeIndex,
            "workpowertype" => $fpWorkPowerType,
            "workpowertypeindex" => $fpWorkPowerTypeIndex,
            "worktitle" => $fpWorkTitle,
            "workbody" => $fpWorkBody,
            "workoneimage" => $fpWorkOneImage,
            "worktwoimage" => $fpWorkTwoImage,
            "workthreeimage" => $fpWorkThreeImage,
            "workfourimage" => $fpWorkFourImage,
            "workfiveimage" => $fpWorkFiveImage,
            "worktime" => $fpWorkTime,
            "workstate" => $fpWorkState,
            "workstateindex" => $fpWorkStateIndex,
            "key_field" => "workerId",
        );
        //执行:添加
        $vJsonResult = MIndexDataInsert(JsonHandleArray($jsonKeyValueArray));
        return $vJsonResult;
    }


    //---------- 用户方法（user） ----------

    /**
     * 函数名称：工作任务列表:用户:记录查询
     * 函数调用：ObjFlyWorkTask() -> UserFlyWorkTaskPaging($fpUserId)
     * 创建时间：2020-03-01 19:51:39
     * */
    public function UserFlyWorkTaskPaging($fpUserId){

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
        //$vDataField = "id,onlyKey,descript,indexNumber,updateTime,addTime,shelfState,state,workerId,workerName,workType,workTypeIndex,workPowerType,workPowerTypeIndex,workTitle,workBody,workOneImage,workTwoImage,workThreeImage,workFourImage,workFiveImage,workTime,workState,workStateIndex";

        //--- Json组合区 ---
        $jsonKeyValueArray = array(
        	"table_name" => self::$tableName,
        	"data_field" => $vDataField,
        	"where_field" => "userId",
        	"where_value" => "{$fpUserId}",
        	"page" => $pPage,
        	"limit" => $pLimit,
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
     * 函数名称：工作任务列表:管理员:记录添加
     * 函数调用：ObjFlyWorkTask() -> AdminFlyWorkTaskAdd($fpAdminId)
     * 创建时间：2020-03-01 19:51:39
     * */
    public function AdminFlyWorkTaskAdd($fpAdminId){


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

	    //参数:工作者ID:workerId
	    $pWorkerId = $fpAdminId;
	    
	    //获取:fly_user_admin:管理员姓名
	    $vSql = "SELECT adminName FROM fly_user_admin WHERE id=? LIMIT 0,1;";
	    $vAdminName = DBHelper::DataString($vSql, [$pWorkerId]);
	    if(IsNull($vAdminName)){ $vAdminName="none"; }
	    //参数:工作者姓名:workerName
	    $pWorkerName = $vAdminName;

	    //参数:工作类型[需求|BUG]:workType
	    $pWorkType = GetParameterNoCode("worktype",$json);
	    if(!JudgeRegularFont($pWorkType)){return JsonModelParameterException("worktype", $pWorkType, 36, "内容格式错误", __LINE__);}

	    //参数:工作类型索引:workTypeIndex
	    $pWorkTypeIndex = "100";
	    if($pWorkType=="BUG"){
	        $pWorkTypeIndex = "999";
	    }else if($pWorkType=="需求"){
	        $pWorkTypeIndex = "500";
	    }else if($pWorkType=="任务"){
	        $pWorkTypeIndex = "200";
	    }
	    
	    //参数:工作权重类型[普通|紧急]:workPowerType
	    $pWorkPowerType = GetParameterNoCode("workpowertype",$json);
	    if(!JudgeRegularFont($pWorkPowerType)){return JsonModelParameterException("workpowertype", $pWorkPowerType, 36, "内容格式错误", __LINE__);}

	    //参数:工作权重索引:workPowerTypeIndex
	    $pWorkPowerTypeIndex = "100";
	    if($pWorkPowerType=="普通"){
	        $pWorkPowerTypeIndex = "100";
	    }else if($pWorkPowerType=="紧急"){
	        $pWorkPowerTypeIndex = "600";
	    }else if($pWorkPowerType=="加急"){
	        $pWorkPowerTypeIndex = "700";
	    }

	    //参数:工作标题:workTitle
	    $pWorkTitle = GetParameterNoCode("worktitle",$json);
	    if(!JudgeRegularFont($pWorkTitle)){return JsonModelParameterException("worktitle", $pWorkTitle, 128, "内容格式错误", __LINE__);}

	    //参数:工作内容:workBody
	    $pWorkBody = GetParameterNoCode("workbody",$json);
	    if(IsNull($pWorkBody)){ return JsonModelParameterNull("workbody"); }
	    $pWorkBody = HandleStringFlyHtmlEncode($pWorkBody);
	    //参数:工作图片一:workOneImage
	    $pWorkOneImage = GetParameterNoCode("workoneimage",$json);
	    if(!JudgeRegularUrl($pWorkOneImage)){return JsonModelParameterException("workoneimage", $pWorkOneImage, 128, "URL地址格式错误", __LINE__);}

	    //参数:工作图片二:workTwoImage
	    $pWorkTwoImage = GetParameterNoCode("worktwoimage",$json);
	    if(!JudgeRegularUrl($pWorkTwoImage)){return JsonModelParameterException("worktwoimage", $pWorkTwoImage, 128, "URL地址格式错误", __LINE__);}

	    //参数:工作图片三:workThreeImage
	    $pWorkThreeImage = GetParameterNoCode("workthreeimage",$json);
	    if(!JudgeRegularUrl($pWorkThreeImage)){return JsonModelParameterException("workthreeimage", $pWorkThreeImage, 128, "URL地址格式错误", __LINE__);}

	    //参数:工作图片四:workFourImage
	    $pWorkFourImage = GetParameterNoCode("workfourimage",$json);
	    if(!JudgeRegularUrl($pWorkFourImage)){return JsonModelParameterException("workfourimage", $pWorkFourImage, 128, "URL地址格式错误", __LINE__);}

	    //参数:工作图片五:workFiveImage
	    $pWorkFiveImage = GetParameterNoCode("workfiveimage",$json);
	    if(!JudgeRegularUrl($pWorkFiveImage)){return JsonModelParameterException("workfiveimage", $pWorkFiveImage, 128, "URL地址格式错误", __LINE__);}

	    //参数:工作最迟解决时间:workTime
	    $pWorkTime = GetParameterNoCode("worktime",$json);
	    if(!JudgeRegularDate($pWorkTime)){return JsonModelParameterException("worktime", $pWorkTime, 0, "日期格式错误", __LINE__);}

	    //参数:工作状态:workState
	    $pWorkState = "WORK_CREATE";

	    //参数:工作状态:workStateIndex
	    $pWorkStateIndex = "100";

        //--- Json组合区 ---
        //Json数组
        $jsonKeyValueArray = array(
            "table_name" => self::$tableName,
            "insert_field" => "descript,shelfState,workerId,workerName,workType,workTypeIndex,workPowerType,workPowerTypeIndex,workTitle,workBody,workOneImage,workTwoImage,workThreeImage,workFourImage,workFiveImage,workTime,workState,workStateIndex",
            "descript" => self::$classDescript,
            "shelfstate" => $pShelfState,
            "workerid" => $pWorkerId,
            "workername" => $pWorkerName,
            "worktype" => $pWorkType,
            "worktypeindex" => $pWorkTypeIndex,
            "workpowertype" => $pWorkPowerType,
            "workpowertypeindex" => $pWorkPowerTypeIndex,
            "worktitle" => $pWorkTitle,
            "workbody" => $pWorkBody,
            "workoneimage" => $pWorkOneImage,
            "worktwoimage" => $pWorkTwoImage,
            "workthreeimage" => $pWorkThreeImage,
            "workfourimage" => $pWorkFourImage,
            "workfiveimage" => $pWorkFiveImage,
            "worktime" => $pWorkTime,
            "workstate" => $pWorkState,
            "workstateindex" => $pWorkStateIndex,
            "key_field" => "workerId,workType,workPowerType,workTitle,workTime",
        );
        //执行:添加
        $vJsonResult = MIndexDataInsert(JsonHandleArray($jsonKeyValueArray));
        return $vJsonResult;
    }


    /**
     * 函数名称：工作任务列表:管理员:记录查询
     * 函数调用：ObjFlyWorkTask() -> AdminFlyWorkTaskPaging($fpAdminId)
     * 创建时间：2020-03-01 19:51:39
     * */
    public function AdminFlyWorkTaskPaging($fpAdminId){

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
        //$vDataField = "id,onlyKey,descript,indexNumber,updateTime,addTime,shelfState,state,workerId,workerName,workType,workTypeIndex,workPowerType,workPowerTypeIndex,workTitle,workBody,workOneImage,workTwoImage,workThreeImage,workFourImage,workFiveImage,workTime,workState,workStateIndex";

        //--- Json组合区 ---
        $jsonKeyValueArray = array(
        	"table_name" => self::$tableName,
        	"data_field" => $vDataField,
        	"where_field" => "workerId",
        	"where_value" => "{$fpAdminId}",
        	"page" => $pPage,
        	"limit" => $pLimit,
        	"orderby" => "workStateIndex,workPowerTypeIndex:desc,workTypeIndex:desc",
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
     * 函数名称：工作任务:管理员:关闭工作任务
     * 函数调用：ObjFlyWorkTask() -> AdminFlyWorkTaskOver($fpAdminId)
     * 创建时间：2020-03-02 11:41:43
     * */
    public function AdminFlyWorkTaskOver($fpAdminId){
    
        $json = "";
    
        //参数:工作ID:worktaskId
        $pWorktaskId = GetParameterNoCode("worktaskid",$json);
        if(!JudgeRegularInt($pWorktaskId)){return JsonModelParameterException("worktaskid", $pWorktaskId, 11, "值必须是整数", __LINE__);}
    
        //参数:工作ID关闭判断:closejudge
        $pCloseJudge = GetParameterNoCode("close_judge",$json);
    
        //获取:fly_work_task:工作者ID、工作任务状态
        $vSql = "SELECT workerId,workState FROM fly_work_task WHERE id=?;";
        $vFlyWorkTaskList = DBHelper::DataList($vSql, [$pWorktaskId], ["workerId","workState"]);
        if(IsNull($vFlyWorkTaskList)){ return JsonInforFalse("工作任务不存在", "fly_work_order", __LINE__); }
        $pFlyWorkTaskFirst = GetJsonMember($vFlyWorkTaskList);
        $vWorkerId = $pFlyWorkTaskFirst -> workerId;	//发起人ID
        $vWorkState = $pFlyWorkTaskFirst -> workState;	//工作任务状态
    
        //判断操作权限
        if($vWorkerId != $fpAdminId){
            return JsonInforFalse("只有本人可以关闭工作任务", "工作任务ID:{$pWorktaskId}");
        }
    
        //判断工作任务状态
        if($vWorkState == "WORK_OVER"){
            return JsonInforFalse("该工作已完成", "工作任务ID:{$pWorktaskId}");
        }
    
        //判断工作任务是否可以被关闭
        if($pCloseJudge == "true"){
            return JsonInforTrue("工作任务可以被关闭", "工作任务ID:{$pWorktaskId}");
        }
    
        //修改:fly_work_task:工作任务状态
        $vSql = "UPDATE fly_work_task SET workState=?,workStateIndex='300' WHERE id=?;";
        $vUpdateResult = DBHelper::DataSubmit($vSql, ["WORK_OVER",$pWorktaskId]);
        if(!$vUpdateResult){ return JsonInforFalse("工作任务状态修改失败", "fly_work_task", __LINE__); }
        return JsonInforTrue("工作任务关闭成功", "工作ID:{$pWorktaskId}");
    
    }
    
    /**
     * 函数名称：工作任务列表:管理员:记录修改
     * 函数调用：ObjFlyWorkTask() -> AdminFlyWorkTaskSet($fpAdminId)
     * 创建时间：2020-03-01 19:51:39
     * */
    public function AdminFlyWorkTaskSet($fpAdminId){

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

	    //参数:工作者ID:workerId
	    $pWorkerId = GetParameterNoCode("workerid",$json);
	    if(!IsNull($pWorkerId)&&!JudgeRegularInt($pWorkerId)){return JsonModelParameterException("workerid", $pWorkerId, 11, "值必须是整数", __LINE__);}

	    //参数:工作者姓名:workerName
	    $pWorkerName = GetParameterNoCode("workername",$json);
	    if(!IsNull($pWorkerName)&&!JudgeRegularFont($pWorkerName)){return JsonModelParameterException("workername", $pWorkerName, 36, "内容格式错误", __LINE__);}

	    //参数:工作类型[需求|BUG]:workType
	    $pWorkType = GetParameterNoCode("worktype",$json);
	    if(!IsNull($pWorkType)&&!JudgeRegularFont($pWorkType)){return JsonModelParameterException("worktype", $pWorkType, 36, "内容格式错误", __LINE__);}

	    //参数:工作类型索引:workTypeIndex
	    $pWorkTypeIndex = GetParameterNoCode("worktypeindex",$json);
	    if(!IsNull($pWorkTypeIndex)&&!JudgeRegularInt($pWorkTypeIndex)){return JsonModelParameterException("worktypeindex", $pWorkTypeIndex, 11, "值必须是整数", __LINE__);}

	    //参数:工作权重类型[普通|紧急]:workPowerType
	    $pWorkPowerType = GetParameterNoCode("workpowertype",$json);
	    if(!IsNull($pWorkPowerType)&&!JudgeRegularFont($pWorkPowerType)){return JsonModelParameterException("workpowertype", $pWorkPowerType, 36, "内容格式错误", __LINE__);}

	    //参数:工作权重索引:workPowerTypeIndex
	    $pWorkPowerTypeIndex = GetParameterNoCode("workpowertypeindex",$json);
	    if(!IsNull($pWorkPowerTypeIndex)&&!JudgeRegularInt($pWorkPowerTypeIndex)){return JsonModelParameterException("workpowertypeindex", $pWorkPowerTypeIndex, 11, "值必须是整数", __LINE__);}

	    //参数:工作标题:workTitle
	    $pWorkTitle = GetParameterNoCode("worktitle",$json);
	    if(!IsNull($pWorkTitle)&&!JudgeRegularFont($pWorkTitle)){return JsonModelParameterException("worktitle", $pWorkTitle, 128, "内容格式错误", __LINE__);}

	    //参数:工作内容:workBody
	    $pWorkBody = GetParameterNoCode("workbody",$json);
	    if(!IsNull($pWorkBody)){ $pWorkBody = HandleStringAddslashes(HandleStringFlyHtmlEncode($pWorkBody)); }

	    //参数:工作图片一:workOneImage
	    $pWorkOneImage = GetParameterNoCode("workoneimage",$json);
	    if(!IsNull($pWorkOneImage)&&!JudgeRegularUrl($pWorkOneImage)){return JsonModelParameterException("workoneimage", $pWorkOneImage, 128, "URL地址格式错误", __LINE__);}

	    //参数:工作图片二:workTwoImage
	    $pWorkTwoImage = GetParameterNoCode("worktwoimage",$json);
	    if(!IsNull($pWorkTwoImage)&&!JudgeRegularUrl($pWorkTwoImage)){return JsonModelParameterException("worktwoimage", $pWorkTwoImage, 128, "URL地址格式错误", __LINE__);}

	    //参数:工作图片三:workThreeImage
	    $pWorkThreeImage = GetParameterNoCode("workthreeimage",$json);
	    if(!IsNull($pWorkThreeImage)&&!JudgeRegularUrl($pWorkThreeImage)){return JsonModelParameterException("workthreeimage", $pWorkThreeImage, 128, "URL地址格式错误", __LINE__);}

	    //参数:工作图片四:workFourImage
	    $pWorkFourImage = GetParameterNoCode("workfourimage",$json);
	    if(!IsNull($pWorkFourImage)&&!JudgeRegularUrl($pWorkFourImage)){return JsonModelParameterException("workfourimage", $pWorkFourImage, 128, "URL地址格式错误", __LINE__);}

	    //参数:工作图片五:workFiveImage
	    $pWorkFiveImage = GetParameterNoCode("workfiveimage",$json);
	    if(!IsNull($pWorkFiveImage)&&!JudgeRegularUrl($pWorkFiveImage)){return JsonModelParameterException("workfiveimage", $pWorkFiveImage, 128, "URL地址格式错误", __LINE__);}

	    //参数:工作最迟解决时间:workTime
	    $pWorkTime = GetParameterNoCode("worktime",$json);
	    if(!IsNull($pWorkTime)&&!JudgeRegularDate($pWorkTime)){return JsonModelParameterException("worktime", $pWorkTime, 0, "日期格式错误", __LINE__);}

	    //参数:工作状态:workState
	    $pWorkState = GetParameterNoCode("workstate",$json);
	    if(!IsNull($pWorkState)&&!JudgeRegularState($pWorkState)){return JsonModelParameterException("workstate", $pWorkState, 36, "状态值格式错误", __LINE__);}

	    //参数:工作状态:workStateIndex
	    $pWorkStateIndex = GetParameterNoCode("workstateindex",$json);
	    if(!IsNull($pWorkStateIndex)&&!JudgeRegularInt($pWorkStateIndex)){return JsonModelParameterException("workstateindex", $pWorkStateIndex, 11, "值必须是整数", __LINE__);}

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
	    $jsonKeyValueArray = HandleFlyJsonAddUpdateField($jsonKeyValueArray, "workerId", $pWorkerId);
	    $jsonKeyValueArray = HandleFlyJsonAddUpdateField($jsonKeyValueArray, "workerName", $pWorkerName);
	    $jsonKeyValueArray = HandleFlyJsonAddUpdateField($jsonKeyValueArray, "workType", $pWorkType);
	    $jsonKeyValueArray = HandleFlyJsonAddUpdateField($jsonKeyValueArray, "workTypeIndex", $pWorkTypeIndex);
	    $jsonKeyValueArray = HandleFlyJsonAddUpdateField($jsonKeyValueArray, "workPowerType", $pWorkPowerType);
	    $jsonKeyValueArray = HandleFlyJsonAddUpdateField($jsonKeyValueArray, "workPowerTypeIndex", $pWorkPowerTypeIndex);
	    $jsonKeyValueArray = HandleFlyJsonAddUpdateField($jsonKeyValueArray, "workTitle", $pWorkTitle);
	    $jsonKeyValueArray = HandleFlyJsonAddUpdateField($jsonKeyValueArray, "workBody", $pWorkBody);
	    $jsonKeyValueArray = HandleFlyJsonAddUpdateField($jsonKeyValueArray, "workOneImage", $pWorkOneImage);
	    $jsonKeyValueArray = HandleFlyJsonAddUpdateField($jsonKeyValueArray, "workTwoImage", $pWorkTwoImage);
	    $jsonKeyValueArray = HandleFlyJsonAddUpdateField($jsonKeyValueArray, "workThreeImage", $pWorkThreeImage);
	    $jsonKeyValueArray = HandleFlyJsonAddUpdateField($jsonKeyValueArray, "workFourImage", $pWorkFourImage);
	    $jsonKeyValueArray = HandleFlyJsonAddUpdateField($jsonKeyValueArray, "workFiveImage", $pWorkFiveImage);
	    $jsonKeyValueArray = HandleFlyJsonAddUpdateField($jsonKeyValueArray, "workTime", $pWorkTime);
	    $jsonKeyValueArray = HandleFlyJsonAddUpdateField($jsonKeyValueArray, "workState", $pWorkState);
	    $jsonKeyValueArray = HandleFlyJsonAddUpdateField($jsonKeyValueArray, "workStateIndex", $pWorkStateIndex);

	    //判断字段值是否为空
	    $vUpdateFieldJudgeInfor = JudgeFlyJsonAddUpdateField($jsonKeyValueArray,"descript,indexnumber,shelfstate,state,workerid,workername,worktype,worktypeindex,workpowertype,workpowertypeindex,worktitle,workbody,workoneimage,worktwoimage,workthreeimage,workfourimage,workfiveimage,worktime,workstate,workstateindex");
	    if(JudgeJsonFalse($vUpdateFieldJudgeInfor)){ return $vUpdateFieldJudgeInfor; }

	    //返回结果
	    return MIndexDataUpdate(JsonHandleArray($jsonKeyValueArray));

    }


    /**
     * 函数名称：工作任务列表:管理员:记录状态修改
     * 函数调用：ObjFlyWorkTask() -> AdminFlyWorkTaskSetState($fpAdminId)
     * 创建时间：2020-03-01 19:51:39
     * */
    public function AdminFlyWorkTaskSetState($fpAdminId){

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
     * 函数名称：工作任务列表:管理员:数据上下架状态修改
     * 函数调用：ObjFlyWorkTask() -> AdminFlyWorkTaskShelfState($fpAdminId)
     * 创建时间：2020-03-01 19:51:39
     * */
    public function AdminFlyWorkTaskShelfState($fpAdminId){
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
     * 函数名称：工作任务列表:管理员:记录永久删除
     * 函数调用：ObjFlyWorkTask() -> AdminFlyWorkTaskDelete($fpAdminId)
     * 创建时间：2020-03-01 19:51:39
     * */
    public function AdminFlyWorkTaskDelete($fpAdminId){
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
     * 函数名称：工作完成记录:管理员:工作任务查询
     * 函数调用：ObjFlyWorkTaskRecode() -> AdminFlyWorkTaskSelect($fpAdminId)
     * 创建时间：2020-03-02 19:23:21
     * */
    public function AdminFlyWorkTaskSelect($fpAdminId){
        //获取:fly_work_task:id
        $vSql = "SELECT id FROM fly_work_task WHERE workState!='WORK_OVER'";
        $vWorkRecode = DBHelper::DataString($vSql, null);
        if(!IsNull($vWorkRecode)){
            return JsonInforTrue("您有未完成的工作任务记录", "fly_work_task");
        }
        return JsonInforFalse("工作任务全部完成", "fly_work_task");
    }

    //---------- 测试方法（test） ----------

    //---------- 基础方法（base） ----------


    /**
     * 函数名称：获取数据表名称
     * 函数调用：ObjFlyWorkTask() -> GetTableName()
     * 创建时间：2020-03-01 19:51:39
     * */
    public function GetTableName(){
        return self::$tableName;
    }

    /**
     * 函数名称：获取类描述
     * 函数调用：ObjFlyWorkTask() -> GetClassDescript()
     * 创建时间：2020-03-01 19:51:39
     * */
    public function GetClassDescript(){
        return self::$classDescript;
    }

    /**
     * 函数名称：获取数据表字段
     * 函数调用：ObjFlyWorkTask() -> GetTableField()
     * 创建时间：2020-03-01 19:51:39
     * */
    public function GetTableField(){
        return MIndexDBTableFields(JsonObj(JsonKeyValue("table_name", self::$tableName)));
    }

    /**
     * 函数名称：类数据表创建
     * 函数调用：ObjFlyWorkTask() -> OprationCreateTable()
     * 创建时间：2020-03-01 19:51:39
     * */
    public function OprationCreateTable(){
        $sql = "CREATE TABLE `fly_work_target` (  `id` bigint(20) NOT NULL AUTO_INCREMENT COMMENT '表ID',  `onlyKey` varchar(36) DEFAULT NULL COMMENT '唯一Key',  `descript` varchar(36) DEFAULT NULL COMMENT '记录描述',  `indexNumber` int(11) DEFAULT '-1' COMMENT '序号',  `updateTime` timestamp NULL DEFAULT NULL COMMENT '修改时间',  `addTime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '添加时间',  `shelfState` varchar(36) DEFAULT 'true' COMMENT '上下架状态',  `state` varchar(36) DEFAULT 'STATE_NORMAL' COMMENT '记录状态',  `workerId` int(11) DEFAULT NULL COMMENT '工作者ID',  `workerName` varchar(36) DEFAULT NULL COMMENT '工作者姓名',  `workType` varchar(36) DEFAULT NULL COMMENT '工作类型[需求|BUG]',  `workTypeIndex` int(11) DEFAULT NULL COMMENT '工作类型索引',  `workPowerType` varchar(36) DEFAULT NULL COMMENT '工作权重类型[普通|紧急]',  `workPowerTypeIndex` int(11) DEFAULT NULL COMMENT '工作权重索引',  `workTitle` varchar(128) DEFAULT NULL COMMENT '工作标题',  `workBody` text COMMENT '工作内容',  `workOneImage` varchar(128) DEFAULT NULL COMMENT '工作图片一',  `workTwoImage` varchar(128) DEFAULT NULL COMMENT '工作图片二',  `workThreeImage` varchar(128) DEFAULT NULL COMMENT '工作图片三',  `workFourImage` varchar(128) DEFAULT NULL COMMENT '工作图片四',  `workFiveImage` varchar(128) DEFAULT NULL COMMENT '工作图片五',  `workTime` timestamp NULL DEFAULT NULL COMMENT '工作最迟解决时间',  `workState` varchar(36) DEFAULT 'WORK_CREATE' COMMENT '工作状态',  `workStateIndex` int(11) DEFAULT '100' COMMENT '工作状态',  PRIMARY KEY (`id`) USING BTREE) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='工作任务列表'";
        DBHelper::DataSubmit($sql, "");
        $vTableCheck = DBMySQLServiceJson::GetDBTableCheck('{"table_name":"'.self::$tableName.'"}');
        if(JudgeJsonTrue($vTableCheck)){
            return JsonInforTrue("创建成功", self::$tableName);
        }
        return JsonInforFalse("创建失败", self::$tableName);
    }

    /**
     * 函数名称：类数据表基础字段检测
     * 函数调用：ObjFlyWorkTask() -> OprationTableFieldBaseCheck()
     * 创建时间：2020-03-01 19:51:39
     * */
    public function OprationTableFieldBaseCheck(){
        return DBMySQLServiceJson::OprationFieldBaseCheck(JsonObj(JsonKeyValue("table_name", self::$tableName)));
    }




}
