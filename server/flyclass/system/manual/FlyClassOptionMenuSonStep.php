<?php

/**------------------------------------*
 * 作者：shark
 * 创建时间：2020-10-16 10:35:58
 * Fly编码：1602815758310FLY677431
 * 类对象名：ObjFlyOptionMenuSonStep()
 * ------------------------------------ */

//引入区

class FlyClassOptionMenuSonStep{
    
    
    //---------- 类成员（member） ----------
    
    //类描述
    public static $classDescript = "Fly-操作手册-操作步骤";
    //类数据表名
    public static $tableName = "fly_option_menu_son_step";
    
    
    //---------- 私有方法（private） ----------
    
    //---------- 游客方法（visitor） ----------
    
    /**
     * 函数名称：Fly-操作手册-操作步骤:游客:记录查询
     * 函数调用：ObjFlyOptionMenuSonStep() -> VisitorFlyOptionMenuSonStepPaging()
     * 创建时间：2020-10-16 10:35:58
     * */
    public function VisitorFlyOptionMenuSonStepPaging(){
        
        //--- 参数限制区 ---
        $vParameterArray = GetArray(FLY_PARAMETER_LIMIT, ",");
        $bParameterJudge = JudgeParameterLimit($vParameterArray);
        if(JudgeJsonFalse($bParameterJudge)){return $bParameterJudge;}
        
        //--- 接口调试输出 ---
        if(GetParameterRequest("interfacecode")=="true"){return WriteFunctionCode(__FILE__,__FUNCTION__);};	//输出接口主函数代码
        
        
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
        //$vDataField = "id,onlyKey,descript,indexNumber,updateTime,addTime,shelfState,state,menuSonId,stepTitle,stepTitleDescript,stepNumber,stepDescript,stepImage,stepImageTwo,stepImageThree,stepImageFour,stepImageFive";
        
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
     * 函数名称：Fly-操作手册-操作步骤:系统:记录添加
     * 函数调用：ObjFlyOptionMenuSonStep() -> SystemFlyOptionMenuSonStepAdd
     * 创建时间：2020-10-16 10:35:58
     * */
    public function SystemFlyOptionMenuSonStepAdd($fpMenuSonId,$fpStepTitle,$fpStepTitleDescript,$fpStepNumber,$fpStepDescript,$fpStepImage,$fpStepImageTwo,$fpStepImageThree,$fpStepImageFour,$fpStepImageFive){
        
        //--- Json组合区 ---
        //Json数组
        $jsonKeyValueArray = array(
            "table_name" => self::$tableName,
            "insert_field" => "menuSonId,stepTitle,stepTitleDescript,stepNumber,stepDescript,stepImage,stepImageTwo,stepImageThree,stepImageFour,stepImageFive",
            "menusonid" => $fpMenuSonId,
            "steptitle" => $fpStepTitle,
            "steptitledescript" => $fpStepTitleDescript,
            "stepnumber" => $fpStepNumber,
            "stepdescript" => $fpStepDescript,
            "stepimage" => $fpStepImage,
            "stepimagetwo" => $fpStepImageTwo,
            "stepimagethree" => $fpStepImageThree,
            "stepimagefour" => $fpStepImageFour,
            "stepimagefive" => $fpStepImageFive,
            //"key_field" => "menuSonId",
        );
        //执行:添加
        $vJsonResult = MIndexDataInsert(JsonHandleArray($jsonKeyValueArray));
        return $vJsonResult;
    }
    
    
    //---------- 用户方法（user） ----------
    
    /**
     * 函数名称：Fly-操作手册-操作步骤:用户:记录查询
     * 函数调用：ObjFlyOptionMenuSonStep() -> UserFlyOptionMenuSonStepPaging($fpUserId)
     * 创建时间：2020-10-16 10:35:58
     * */
    public function UserFlyOptionMenuSonStepPaging($fpUserId){
        
        //--- 参数限制区 ---
        $vParameterArray = GetArray(FLY_PARAMETER_LIMIT, ",");
        $bParameterJudge = JudgeParameterLimit($vParameterArray);
        if(JudgeJsonFalse($bParameterJudge)){return $bParameterJudge;}
        
        //--- 接口调试输出 ---
        if(GetParameterRequest("interfacecode")=="true"){return WriteFunctionCode(__FILE__,__FUNCTION__);};	//输出接口主函数代码
        
        
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
        //$vDataField = "id,onlyKey,descript,indexNumber,updateTime,addTime,shelfState,state,menuSonId,stepTitle,stepTitleDescript,stepNumber,stepDescript,stepImage,stepImageTwo,stepImageThree,stepImageFour,stepImageFive";
        
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
     * 函数名称：Fly-操作手册-操作步骤:管理员:记录添加
     * 函数调用：ObjFlyOptionMenuSonStep() -> AdminFlyOptionMenuSonStepAdd($fpAdminId)
     * 创建时间：2020-10-16 10:35:58
     * */
    public function AdminFlyOptionMenuSonStepAdd($fpAdminId){
        
        
        //--- 参数限制区 ---
        $vParameterArray = GetArray(FLY_PARAMETER_LIMIT, ",");
        $bParameterJudge = JudgeParameterLimit($vParameterArray);
        if(JudgeJsonFalse($bParameterJudge)){return $bParameterJudge;}
        
        //--- 接口调试输出 ---
        if(GetParameterRequest("interfacecode")=="true"){return WriteFunctionCode(__FILE__,__FUNCTION__);};	//输出接口主函数代码
        
        
        //--- 变量预定义 ---
        $json="";
        
        //--- 参数获取区 ---
        //参数:记录ID（用于修改记录）:id
        $pId = GetParameterNoCode("id","");
        if(!IsNull($pId)&&!JudgeRegularIntRight($pId)){return JsonModelParameterException("id", $pId, 20, "值必须是正整数", __LINE__);}
        
        //参数:菜单子项ID:menuSonId
        $pMenuSonId = GetParameterNoCode("menusonid",$json);
        if(!JudgeRegularInt($pMenuSonId)){return JsonModelParameterException("menusonid", $pMenuSonId, 11, "值必须是整数", __LINE__);}
        
        //参数:步骤标题:stepTitle
        $pStepTitle = GetParameterNoCode("steptitle",$json);
        if(!JudgeRegularFont($pStepTitle)){return JsonModelParameterException("steptitle", $pStepTitle, 64, "内容格式错误", __LINE__);}
        
        //参数:步骤标题描述:stepTitleDescript
        $pStepTitleDescript = GetParameterNoCode("steptitledescript",$json);
        if(!JudgeRegularFont($pStepTitleDescript)){return JsonModelParameterException("steptitledescript", $pStepTitleDescript, 256, "内容格式错误", __LINE__);}
        
        //参数:步骤编号:stepNumber
        $pStepNumber = GetParameterNoCode("stepnumber",$json);
        if(!JudgeRegularInt($pStepNumber)){return JsonModelParameterException("stepnumber", $pStepNumber, 11, "值必须是整数", __LINE__);}
        
        //参数:步骤描述:stepDescript
        $pStepDescript = GetParameterNoCode("stepdescript",$json);
        if(!JudgeRegularFont($pStepDescript)){return JsonModelParameterException("stepdescript", $pStepDescript, 256, "内容格式错误", __LINE__);}
        
        //参数:步骤图片一:stepImage
        $pStepImage = GetParameterNoCode("stepimage",$json);
        if(!JudgeRegularUrl($pStepImage)){return JsonModelParameterException("stepimage", $pStepImage, 128, "URL地址格式错误", __LINE__);}
        
        //参数:步骤图片二:stepImageTwo
        $pStepImageTwo = GetParameterNoCode("stepimagetwo",$json);
        if(!JudgeRegularUrl($pStepImageTwo)){return JsonModelParameterException("stepimagetwo", $pStepImageTwo, 128, "URL地址格式错误", __LINE__);}
        
        //参数:步骤图片三:stepImageThree
        $pStepImageThree = GetParameterNoCode("stepimagethree",$json);
        if(!JudgeRegularUrl($pStepImageThree)){return JsonModelParameterException("stepimagethree", $pStepImageThree, 128, "URL地址格式错误", __LINE__);}
        
        //参数:步骤图片四:stepImageFour
        $pStepImageFour = GetParameterNoCode("stepimagefour",$json);
        if(!JudgeRegularUrl($pStepImageFour)){return JsonModelParameterException("stepimagefour", $pStepImageFour, 128, "URL地址格式错误", __LINE__);}
        
        //参数:步骤图片五:stepImageFive
        $pStepImageFive = GetParameterNoCode("stepimagefive",$json);
        if(!JudgeRegularUrl($pStepImageFive)){return JsonModelParameterException("stepimagefive", $pStepImageFive, 128, "URL地址格式错误", __LINE__);}
        
        //--- Json组合区 ---
        //Json数组
        $jsonKeyValueArray = array(
            "table_name" => self::$tableName,
            "insert_field" => "menuSonId,stepTitle,stepTitleDescript,stepNumber,stepDescript,stepImage,stepImageTwo,stepImageThree,stepImageFour,stepImageFive",
            "menusonid" => $pMenuSonId,
            "steptitle" => $pStepTitle,
            "steptitledescript" => $pStepTitleDescript,
            "stepnumber" => $pStepNumber,
            "stepdescript" => $pStepDescript,
            "stepimage" => $pStepImage,
            "stepimagetwo" => $pStepImageTwo,
            "stepimagethree" => $pStepImageThree,
            "stepimagefour" => $pStepImageFour,
            "stepimagefive" => $pStepImageFive,
            //"key_field" => "menuSonId",
            //- 修改记录 -
            "where_field" => "",
            "where_value" => "",
            "update_field" => "",
            "update_value" => "",
            "execution_step" => "update,insert",
        );
        
        //修改字段判断组合
        $jsonKeyValueArray = HandleFlyJsonAddWhereField($jsonKeyValueArray, "id", $pId);
        $jsonKeyValueArray = HandleFlyJsonAddUpdateField($jsonKeyValueArray, "menuSonId", $pMenuSonId);
        $jsonKeyValueArray = HandleFlyJsonAddUpdateField($jsonKeyValueArray, "stepTitle", $pStepTitle);
        $jsonKeyValueArray = HandleFlyJsonAddUpdateField($jsonKeyValueArray, "stepTitleDescript", $pStepTitleDescript);
        $jsonKeyValueArray = HandleFlyJsonAddUpdateField($jsonKeyValueArray, "stepNumber", $pStepNumber);
        $jsonKeyValueArray = HandleFlyJsonAddUpdateField($jsonKeyValueArray, "stepDescript", $pStepDescript);
        $jsonKeyValueArray = HandleFlyJsonAddUpdateField($jsonKeyValueArray, "stepImage", $pStepImage);
        $jsonKeyValueArray = HandleFlyJsonAddUpdateField($jsonKeyValueArray, "stepImageTwo", $pStepImageTwo);
        $jsonKeyValueArray = HandleFlyJsonAddUpdateField($jsonKeyValueArray, "stepImageThree", $pStepImageThree);
        $jsonKeyValueArray = HandleFlyJsonAddUpdateField($jsonKeyValueArray, "stepImageFour", $pStepImageFour);
        $jsonKeyValueArray = HandleFlyJsonAddUpdateField($jsonKeyValueArray, "stepImageFive", $pStepImageFive);
        
        //执行:添加
        $vJsonResult = MIndexDataInsert(JsonHandleArray($jsonKeyValueArray));
        if(JudgeJsonTrue($vJsonResult)){
            ObjFlyUserAdminOperationLog() -> SystemFlyUserAdminOperationLogAdd($fpAdminId, __CLASS__, __FUNCTION__, "管理员添加记录", "0");
        }
        return $vJsonResult;
    }
    
    
    /**
     * 函数名称：Fly-操作手册-操作步骤:管理员:记录查询
     * 函数调用：ObjFlyOptionMenuSonStep() -> AdminFlyOptionMenuSonStepPaging($fpAdminId)
     * 创建时间：2020-10-16 10:35:58
     * */
    public function AdminFlyOptionMenuSonStepPaging($fpAdminId){
        
        //--- 参数限制区 ---
        $vParameterArray = GetArray(FLY_PARAMETER_LIMIT, ",");
        $bParameterJudge = JudgeParameterLimit($vParameterArray);
        if(JudgeJsonFalse($bParameterJudge)){return $bParameterJudge;}
        
        //--- 接口调试输出 ---
        if(GetParameterRequest("interfacecode")=="true"){return WriteFunctionCode(__FILE__,__FUNCTION__);};	//输出接口主函数代码
        
        
        //--- 数据预定义 ---
        $json = "";
        
        //--- 参数获取区 ---
        $pPage = GetParameterNoCode("page","");     //参数:页码:page
        $pLimit = GetParameterNoCode("limit","");   //参数:条数:limit
        
        //参数:菜单子项ID:menuSonId
        $pMenuSonId = GetParameterNoCode("menusonid",$json);
        if(!JudgeRegularInt($pMenuSonId)){return JsonModelParameterException("menusonid", $pMenuSonId, 11, "值必须是整数", __LINE__);}
        
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
        //$vDataField = "id,onlyKey,descript,indexNumber,updateTime,addTime,shelfState,state,menuSonId,stepTitle,stepTitleDescript,stepNumber,stepDescript,stepImage,stepImageTwo,stepImageThree,stepImageFour,stepImageFive";
        
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
        $jsonKeyValueArray = HandleFlyJsonAddWhereField($jsonKeyValueArray, "menuSonId", $pMenuSonId);
        $jsonKeyValueArray = HandleFlyJsonAddWhereField($jsonKeyValueArray, $pStateField, $pStateKey);
        //返回结果:分页
        return MIndexDataPaging(JsonHandleArray($jsonKeyValueArray));
        
    }
    
    
    
    /**
     * 函数名称：项目时间轴:管理员:记录查询
     * 函数调用：ObjFlyOptionMenuSonStep() -> AdminFlyOptionMenuSonStepPagePaging($fpAdminId)
     * 创建时间：2020-10-05 09:27:28
     * */
    public function AdminFlyOptionMenuSonStepPagePaging($fpAdminId){
        
        //--- 参数限制区 ---
        $vParameterArray = GetArray(FLY_PARAMETER_LIMIT, ",");
        $bParameterJudge = JudgeParameterLimit($vParameterArray);
        if(JudgeJsonFalse($bParameterJudge)){return $bParameterJudge;}
        
        //--- 数据预定义 ---
        $json = "";
        
        //字段定义
        $vDataField = "id,menuIcon,menuName,menuSonId,menuSonName,menuSonDescript";
        
        //--- Json组合区 ---
        $jsonKeyValueArray = array(
            "table_name" => self::$tableName,
            "data_field" => $vDataField,
            "page" => "1",
            "limit" => "5000",
            "sql" => "SELECT fly_option_menu.id,fly_option_menu.menuIcon,fly_option_menu.menuName,fly_option_menu_son.id menuSonId,fly_option_menu_son.menuSonName,fly_option_menu_son.menuSonDescript FROM fly_option_menu RIGHT JOIN fly_option_menu_son ON fly_option_menu.id = fly_option_menu_son.menuId  WHERE fly_option_menu.id IS NOT NULL",
            "sql_count" => "SELECT COUNT(TRUE) COUNT FROM fly_option_menu RIGHT JOIN fly_option_menu_son ON fly_option_menu.id = fly_option_menu_son.menuId  WHERE fly_option_menu.id IS NOT NULL",
        );
        //返回结果:分页
        return MIndexDataPaging(JsonHandleArray($jsonKeyValueArray));
        
    }
    
    
    /**
     * 函数名称：Fly-操作手册-操作步骤:管理员:记录修改
     * 函数调用：ObjFlyOptionMenuSonStep() -> AdminFlyOptionMenuSonStepSet($fpAdminId)
     * 创建时间：2020-10-16 10:35:58
     * */
    public function AdminFlyOptionMenuSonStepSet($fpAdminId){
        
        //--- 参数限制区 ---
        $vParameterArray = GetArray(FLY_PARAMETER_LIMIT, ",");
        $bParameterJudge = JudgeParameterLimit($vParameterArray);
        if(JudgeJsonFalse($bParameterJudge)){return $bParameterJudge;}
        
        //--- 接口调试输出 ---
        if(GetParameterRequest("interfacecode")=="true"){return WriteFunctionCode(__FILE__,__FUNCTION__);};	//输出接口主函数代码
        
        
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
        
        //参数:菜单子项ID:menuSonId
        $pMenuSonId = GetParameterNoCode("menusonid",$json);
        if(!IsNull($pMenuSonId)&&!JudgeRegularInt($pMenuSonId)){return JsonModelParameterException("menusonid", $pMenuSonId, 11, "值必须是整数", __LINE__);}
        
        //参数:步骤标题:stepTitle
        $pStepTitle = GetParameterNoCode("steptitle",$json);
        if(!IsNull($pStepTitle)&&!JudgeRegularFont($pStepTitle)){return JsonModelParameterException("steptitle", $pStepTitle, 64, "内容格式错误", __LINE__);}
        
        //参数:步骤标题描述:stepTitleDescript
        $pStepTitleDescript = GetParameterNoCode("steptitledescript",$json);
        if(!IsNull($pStepTitleDescript)&&!JudgeRegularFont($pStepTitleDescript)){return JsonModelParameterException("steptitledescript", $pStepTitleDescript, 256, "内容格式错误", __LINE__);}
        
        //参数:步骤编号:stepNumber
        $pStepNumber = GetParameterNoCode("stepnumber",$json);
        if(!IsNull($pStepNumber)&&!JudgeRegularInt($pStepNumber)){return JsonModelParameterException("stepnumber", $pStepNumber, 11, "值必须是整数", __LINE__);}
        
        //参数:步骤描述:stepDescript
        $pStepDescript = GetParameterNoCode("stepdescript",$json);
        if(!IsNull($pStepDescript)&&!JudgeRegularFont($pStepDescript)){return JsonModelParameterException("stepdescript", $pStepDescript, 256, "内容格式错误", __LINE__);}
        
        //参数:步骤图片一:stepImage
        $pStepImage = GetParameterNoCode("stepimage",$json);
        if(!IsNull($pStepImage)&&!JudgeRegularUrl($pStepImage)){return JsonModelParameterException("stepimage", $pStepImage, 128, "URL地址格式错误", __LINE__);}
        
        //参数:步骤图片二:stepImageTwo
        $pStepImageTwo = GetParameterNoCode("stepimagetwo",$json);
        if(!IsNull($pStepImageTwo)&&!JudgeRegularUrl($pStepImageTwo)){return JsonModelParameterException("stepimagetwo", $pStepImageTwo, 128, "URL地址格式错误", __LINE__);}
        
        //参数:步骤图片三:stepImageThree
        $pStepImageThree = GetParameterNoCode("stepimagethree",$json);
        if(!IsNull($pStepImageThree)&&!JudgeRegularUrl($pStepImageThree)){return JsonModelParameterException("stepimagethree", $pStepImageThree, 128, "URL地址格式错误", __LINE__);}
        
        //参数:步骤图片四:stepImageFour
        $pStepImageFour = GetParameterNoCode("stepimagefour",$json);
        if(!IsNull($pStepImageFour)&&!JudgeRegularUrl($pStepImageFour)){return JsonModelParameterException("stepimagefour", $pStepImageFour, 128, "URL地址格式错误", __LINE__);}
        
        //参数:步骤图片五:stepImageFive
        $pStepImageFive = GetParameterNoCode("stepimagefive",$json);
        if(!IsNull($pStepImageFive)&&!JudgeRegularUrl($pStepImageFive)){return JsonModelParameterException("stepimagefive", $pStepImageFive, 128, "URL地址格式错误", __LINE__);}
        
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
        $jsonKeyValueArray = HandleFlyJsonAddUpdateField($jsonKeyValueArray, "menuSonId", $pMenuSonId);
        $jsonKeyValueArray = HandleFlyJsonAddUpdateField($jsonKeyValueArray, "stepTitle", $pStepTitle);
        $jsonKeyValueArray = HandleFlyJsonAddUpdateField($jsonKeyValueArray, "stepTitleDescript", $pStepTitleDescript);
        $jsonKeyValueArray = HandleFlyJsonAddUpdateField($jsonKeyValueArray, "stepNumber", $pStepNumber);
        $jsonKeyValueArray = HandleFlyJsonAddUpdateField($jsonKeyValueArray, "stepDescript", $pStepDescript);
        $jsonKeyValueArray = HandleFlyJsonAddUpdateField($jsonKeyValueArray, "stepImage", $pStepImage);
        $jsonKeyValueArray = HandleFlyJsonAddUpdateField($jsonKeyValueArray, "stepImageTwo", $pStepImageTwo);
        $jsonKeyValueArray = HandleFlyJsonAddUpdateField($jsonKeyValueArray, "stepImageThree", $pStepImageThree);
        $jsonKeyValueArray = HandleFlyJsonAddUpdateField($jsonKeyValueArray, "stepImageFour", $pStepImageFour);
        $jsonKeyValueArray = HandleFlyJsonAddUpdateField($jsonKeyValueArray, "stepImageFive", $pStepImageFive);
        
        //判断字段值是否为空
        $vUpdateFieldJudgeInfor = JudgeFlyJsonAddUpdateField($jsonKeyValueArray,"descript,indexnumber,shelfstate,state,menusonid,steptitle,steptitledescript,stepnumber,stepdescript,stepimage,stepimagetwo,stepimagethree,stepimagefour,stepimagefive");
        if(JudgeJsonFalse($vUpdateFieldJudgeInfor)){ return $vUpdateFieldJudgeInfor; }
        
        //执行:修改
        $vJsonResult = MIndexDataUpdate(JsonHandleArray($jsonKeyValueArray));
        if(JudgeJsonTrue($vJsonResult)){
            ObjFlyUserAdminOperationLog() -> SystemFlyUserAdminOperationLogAdd($fpAdminId, __CLASS__, __FUNCTION__, "管理员修改记录", $pId);
        }
        return $vJsonResult;
        
    }
    
    
    /**
     * 函数名称：Fly-操作手册-操作步骤:管理员:记录状态修改
     * 函数调用：ObjFlyOptionMenuSonStep() -> AdminFlyOptionMenuSonStepSetState($fpAdminId)
     * 创建时间：2020-10-16 10:35:58
     * */
    public function AdminFlyOptionMenuSonStepSetState($fpAdminId){
        
        //--- 参数限制区 ---
        $vParameterArray = GetArray(FLY_PARAMETER_LIMIT, ",");
        $bParameterJudge = JudgeParameterLimit($vParameterArray);
        if(JudgeJsonFalse($bParameterJudge)){return $bParameterJudge;}
        
        //--- 接口调试输出 ---
        if(GetParameterRequest("interfacecode")=="true"){return WriteFunctionCode(__FILE__,__FUNCTION__);};	//输出接口主函数代码
        
        
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
        //执行:修改
        $vJsonResult = ServiceTableDataSystemSet(self::$tableName,"state","{$pState}","id","{$pId}");
        if(JudgeJsonTrue($vJsonResult)){
            ObjFlyUserAdminOperationLog() -> SystemFlyUserAdminOperationLogAdd($fpAdminId, __CLASS__, __FUNCTION__, "管理员修改记录状态", $pId);
        }
        return $vJsonResult;
    }
    
    /**
     * 函数名称：Fly-操作手册-操作步骤:管理员:数据上下架状态修改
     * 函数调用：ObjFlyOptionMenuSonStep() -> AdminFlyOptionMenuSonStepShelfState($fpAdminId)
     * 创建时间：2020-10-16 10:35:58
     * */
    public function AdminFlyOptionMenuSonStepShelfState($fpAdminId){
        //--- 参数限制区 ---
        $vParameterArray = GetArray(FLY_PARAMETER_LIMIT, ",");
        $bParameterJudge = JudgeParameterLimit($vParameterArray);
        if(JudgeJsonFalse($bParameterJudge)){return $bParameterJudge;}
        
        //--- 接口调试输出 ---
        if(GetParameterRequest("interfacecode")=="true"){return WriteFunctionCode(__FILE__,__FUNCTION__);};	//输出接口主函数代码
        
        
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
        //执行:上下降状态修改
        $vJsonResult = MIndexDataUpdate(JsonHandleArray($jsonKeyValueArray));
        if(JudgeJsonTrue($vJsonResult)){
            ObjFlyUserAdminOperationLog() -> SystemFlyUserAdminOperationLogAdd($fpAdminId, __CLASS__, __FUNCTION__, "管理员记录上下架修改", $pId);
        }
        return $vJsonResult;
        
    }
    
    /**
     * 函数名称：Fly-操作手册-操作步骤:管理员:记录永久删除
     * 函数调用：ObjFlyOptionMenuSonStep() -> AdminFlyOptionMenuSonStepDelete($fpAdminId)
     * 创建时间：2020-10-16 10:35:58
     * */
    public function AdminFlyOptionMenuSonStepDelete($fpAdminId){
        //--- 参数限制区 ---
        $vParameterArray = GetArray(FLY_PARAMETER_LIMIT, ",");
        $bParameterJudge = JudgeParameterLimit($vParameterArray);
        if(JudgeJsonFalse($bParameterJudge)){return $bParameterJudge;}
        
        //--- 接口调试输出 ---
        if(GetParameterRequest("interfacecode")=="true"){return WriteFunctionCode(__FILE__,__FUNCTION__);};	//输出接口主函数代码
        
        
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
        if(JudgeJsonTrue($vJsonResult)){
            ObjFlyUserAdminOperationLog() -> SystemFlyUserAdminOperationLogAdd($fpAdminId, __CLASS__, __FUNCTION__, "管理员删除记录", $pId);
        }
        return $vJsonResult;
    }
    
    
    //---------- 测试方法（test） ----------
    
    //---------- 基础方法（base） ----------
    
    
    /**
     * 函数名称：获取数据表名称
     * 函数调用：ObjFlyOptionMenuSonStep() -> GetTableName()
     * 创建时间：2020-10-16 10:35:57
     * */
    public function GetTableName(){
        return self::$tableName;
    }
    
    /**
     * 函数名称：获取类描述
     * 函数调用：ObjFlyOptionMenuSonStep() -> GetClassDescript()
     * 创建时间：2020-10-16 10:35:57
     * */
    public function GetClassDescript(){
        return self::$classDescript;
    }
    
    /**
     * 函数名称：获取数据表字段
     * 函数调用：ObjFlyOptionMenuSonStep() -> GetTableField()
     * 创建时间：2020-10-16 10:35:57
     * */
    public function GetTableField(){
        return MIndexDBTableFields(JsonObj(JsonKeyValue("table_name", self::$tableName)));
    }
    
    /**
     * 函数名称：类数据表创建
     * 函数调用：ObjFlyOptionMenuSonStep() -> OprationCreateTable()
     * 创建时间：2020-10-16 10:35:57
     * */
    public function OprationCreateTable(){
        $sql = "CREATE TABLE `fly_option_menu_son_step` (  `id` bigint(20) NOT NULL AUTO_INCREMENT COMMENT '表ID',  `onlyKey` varchar(36) DEFAULT NULL COMMENT '唯一Key',  `descript` varchar(36) DEFAULT NULL COMMENT '记录描述',  `indexNumber` int(11) DEFAULT '-1' COMMENT '序号',  `updateTime` timestamp NULL DEFAULT NULL COMMENT '修改时间',  `addTime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '添加时间',  `shelfState` varchar(36) DEFAULT 'true' COMMENT '上下架状态',  `state` varchar(36) DEFAULT 'STATE_NORMAL' COMMENT '记录状态',  `menuSonId` int(11) DEFAULT NULL COMMENT '菜单子项ID',  `stepTitle` varchar(64) DEFAULT NULL COMMENT '步骤标题',  `stepTitleDescript` varchar(256) DEFAULT NULL COMMENT '步骤标题描述',  `stepNumber` int(11) DEFAULT NULL COMMENT '步骤编号',  `stepDescript` varchar(256) DEFAULT NULL COMMENT '步骤描述',  `stepImage` varchar(128) DEFAULT NULL COMMENT '步骤图片一',  `stepImageTwo` varchar(128) DEFAULT NULL COMMENT '步骤图片二',  `stepImageThree` varchar(128) DEFAULT NULL COMMENT '步骤图片三',  `stepImageFour` varchar(128) DEFAULT NULL COMMENT '步骤图片四',  `stepImageFive` varchar(128) DEFAULT NULL COMMENT '步骤图片五',  PRIMARY KEY (`id`)) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COMMENT='Fly-操作手册-操作步骤'";
        DBHelper::DataSubmit($sql, "");
        $vTableCheck = DBMySQLServiceJson::GetDBTableCheck('{"table_name":"'.self::$tableName.'"}');
        if(JudgeJsonTrue($vTableCheck)){
            return JsonInforTrue("创建成功", self::$tableName);
        }
        return JsonInforFalse("创建失败", self::$tableName);
    }
    
    /**
     * 函数名称：类数据表基础字段检测
     * 函数调用：ObjFlyOptionMenuSonStep() -> OprationTableFieldBaseCheck()
     * 创建时间：2020-10-16 10:35:57
     * */
    public function OprationTableFieldBaseCheck(){
        return DBMySQLServiceJson::OprationFieldBaseCheck(JsonObj(JsonKeyValue("table_name", self::$tableName)));
    }
    
    
    
    
}
