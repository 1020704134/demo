<?php

/**------------------------------------*
 * 作者：shark
 * 创建时间：2021-01-13 14:56:56
 * Fly编码：1610521016758FLY952007
 * 类对象名：ObjTestOrder()
 * ------------------------------------ */

//引入区

class FlyClassTestOrder{
    
    
    //---------- 类成员（member） ----------
    
    //类描述
    public static $classDescript = "订单";
    //类数据表名
    public static $tableName = "test_order";
    
    
    //---------- 私有方法（private） ----------
    
    //---------- 游客方法（visitor） ----------
    
    /**
     * 函数名称：订单:游客:记录查询
     * 函数调用：ObjTestOrder() -> VisitorTestOrderPaging()
     * 创建时间：2021-01-13 14:56:56
     * */
    public function VisitorTestOrderPaging(){
        
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
        //$vDataField = "id,onlyKey,descript,indexNumber,updateTime,addTime,shelfState,state,recodeName,recodeGroup,userId,userNickname,productId,productName,productPrice,productNumber,productTotal,orderNumber,logisticsName,logisticsPhone,logisticsProvince,logisticsCity,logisticsArea,logisticsCode,logisticsAddress,logisticsCompany,logisticsCompanyCode,logisticsNumber";
        
        //渲染提示
        //$vResultTips = GetParameterRenderTips();
        //if(JudgeJsonFalseString($vResultTips)){return $vResultTips;}
        
        //--- Json组合区 ---
        $jsonKeyValueArray = array(
            "table_name" => self::$tableName,
            "data_field" => $vDataField,
            "where_field" => "shelfState",
            "where_value" => "true",
            "page" => $pPage,
            "limit" => $pLimit,
            //"orderby" => "id:desc",
            "like_field" => $pLikeField,
            "like_key" => $pLikeKey,
            //"result_tips" => $vResultTips,
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
     * 函数名称：订单:系统:记录添加
     * 函数调用：ObjTestOrder() -> SystemTestOrderAdd
     * 创建时间：2021-01-13 14:56:56
     * */
    public function SystemTestOrderAdd($fpRecodeName,$fpRecodeGroup,$fpUserId,$fpUserNickname,$fpProductId,$fpProductName,$fpProductPrice,$fpProductNumber,$fpProductTotal,$fpOrderNumber,$fpLogisticsName,$fpLogisticsPhone,$fpLogisticsProvince,$fpLogisticsCity,$fpLogisticsArea,$fpLogisticsCode,$fpLogisticsAddress,$fpLogisticsCompany,$fpLogisticsCompanyCode,$fpLogisticsNumber){
        
        //--- Json组合区 ---
        //Json数组
        $jsonKeyValueArray = array(
            "table_name" => self::$tableName,
            "insert_field" => "recodeName,recodeGroup,userId,userNickname,productId,productName,productPrice,productNumber,productTotal,orderNumber,logisticsName,logisticsPhone,logisticsProvince,logisticsCity,logisticsArea,logisticsCode,logisticsAddress,logisticsCompany,logisticsCompanyCode,logisticsNumber",
            "recodename" => $fpRecodeName,
            "recodegroup" => $fpRecodeGroup,
            "userid" => $fpUserId,
            "usernickname" => $fpUserNickname,
            "productid" => $fpProductId,
            "productname" => $fpProductName,
            "productprice" => $fpProductPrice,
            "productnumber" => $fpProductNumber,
            "producttotal" => $fpProductTotal,
            "ordernumber" => $fpOrderNumber,
            "logisticsname" => $fpLogisticsName,
            "logisticsphone" => $fpLogisticsPhone,
            "logisticsprovince" => $fpLogisticsProvince,
            "logisticscity" => $fpLogisticsCity,
            "logisticsarea" => $fpLogisticsArea,
            "logisticscode" => $fpLogisticsCode,
            "logisticsaddress" => $fpLogisticsAddress,
            "logisticscompany" => $fpLogisticsCompany,
            "logisticscompanycode" => $fpLogisticsCompanyCode,
            "logisticsnumber" => $fpLogisticsNumber,
            //"key_field" => "userId,productId",
        );
        //执行:添加
        $vJsonResult = MIndexDataInsert(JsonHandleArray($jsonKeyValueArray));
        return $vJsonResult;
    }
    
    
    //---------- 用户方法（user） ----------
    
    /**
     * 函数名称：订单:用户:记录查询
     * 函数调用：ObjTestOrder() -> UserTestOrderPaging($fpUserId)
     * 创建时间：2021-01-13 14:56:56
     * */
    public function UserTestOrderPaging($fpUserId){
        
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
        //$vDataField = "id,onlyKey,descript,indexNumber,updateTime,addTime,shelfState,state,recodeName,recodeGroup,userId,userNickname,productId,productName,productPrice,productNumber,productTotal,orderNumber,logisticsName,logisticsPhone,logisticsProvince,logisticsCity,logisticsArea,logisticsCode,logisticsAddress,logisticsCompany,logisticsCompanyCode,logisticsNumber";
        
        //渲染提示
        //$vResultTips = GetParameterRenderTips();
        //if(JudgeJsonFalseString($vResultTips)){return $vResultTips;}
        
        //--- Json组合区 ---
        $jsonKeyValueArray = array(
            "table_name" => self::$tableName,
            "data_field" => $vDataField,
            "where_field" => "userId",
            "where_value" => "{$fpUserId}",
            "page" => $pPage,
            "limit" => $pLimit,
            //"orderby" => "id:desc",
        "like_field" => $pLikeField,
        "like_key" => $pLikeKey,
        //"result_tips" => $vResultTips,
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
     * 函数名称：订单:管理员:记录添加
     * 函数调用：ObjTestOrder() -> AdminTestOrderAdd($fpAdminId)
     * 创建时间：2021-01-13 14:56:56
     * */
    public function AdminTestOrderAdd($fpAdminId){
        
        
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
        
        //参数:记录名称:recodeName
        $pRecodeName = GetParameterNoCode("recodename",$json);
        if(!JudgeRegularFont($pRecodeName)){return JsonModelParameterException("recodename", $pRecodeName, 36, "内容格式错误", __LINE__);}
        
        //参数:记录组:recodeGroup
        $pRecodeGroup = GetParameterNoCode("recodegroup",$json);
        if(!JudgeRegularFont($pRecodeGroup)){return JsonModelParameterException("recodegroup", $pRecodeGroup, 36, "内容格式错误", __LINE__);}
        
        //参数:用户ID:userId
        $pUserId = GetParameterNoCode("userid",$json);
        if(!JudgeRegularIntRight($pUserId)){return JsonModelParameterException("userid", $pUserId, 20, "值必须是正整数", __LINE__);}
        
        //参数:用户昵称:userNickname
        $pUserNickname = GetParameterNoCode("usernickname",$json);
        if(!JudgeRegularFont($pUserNickname)){return JsonModelParameterException("usernickname", $pUserNickname, 64, "内容格式错误", __LINE__);}
        
        //参数:产品ID:productId
        $pProductId = GetParameterNoCode("productid",$json);
        if(!JudgeRegularIntRight($pProductId)){return JsonModelParameterException("productid", $pProductId, 20, "值必须是正整数", __LINE__);}
        
        //参数:产品名称:productName
        $pProductName = GetParameterNoCode("productname",$json);
        if(!JudgeRegularFont($pProductName)){return JsonModelParameterException("productname", $pProductName, 64, "内容格式错误", __LINE__);}
        
        //参数:产品价格:productPrice
        $pProductPrice = GetParameterNoCode("productprice",$json);
        if(!JudgeRegularDouble($pProductPrice)){return JsonModelParameterException("productprice", $pProductPrice, 12, "值必须是数值", __LINE__);}
        
        //参数:产品购买数量:productNumber
        $pProductNumber = GetParameterNoCode("productnumber",$json);
        if(!JudgeRegularInt($pProductNumber)){return JsonModelParameterException("productnumber", $pProductNumber, 11, "值必须是整数", __LINE__);}
        
        //参数:产品购买总金额:productTotal
        $pProductTotal = abs($pProductPrice) * abs($pProductNumber);
        
        //参数:订单号:orderNumber
        $pOrderNumber = GetParameterNoCode("ordernumber",$json);
        if(!JudgeRegularFont($pOrderNumber)){return JsonModelParameterException("ordernumber", $pOrderNumber, 64, "内容格式错误", __LINE__);}
        
        //参数:收件人姓名:logisticsName
        $pLogisticsName = GetParameterNoCode("logisticsname",$json);
        if(!JudgeRegularFont($pLogisticsName)){return JsonModelParameterException("logisticsname", $pLogisticsName, 64, "内容格式错误", __LINE__);}
        
        //参数:收件人手机号:logisticsPhone
        $pLogisticsPhone = GetParameterNoCode("logisticsphone",$json);
        if(!JudgeRegularPhone($pLogisticsPhone)){return JsonModelParameterException("logisticsphone", $pLogisticsPhone, 64, "手机号格式错误", __LINE__);}
        
        //参数:收件地址省:logisticsProvince
        $pLogisticsProvince = GetParameterNoCode("logisticsprovince",$json);
        if(!JudgeRegularFont($pLogisticsProvince)){return JsonModelParameterException("logisticsprovince", $pLogisticsProvince, 64, "内容格式错误", __LINE__);}
        
        //参数:收件地址市:logisticsCity
        $pLogisticsCity = GetParameterNoCode("logisticscity",$json);
        if(!JudgeRegularFont($pLogisticsCity)){return JsonModelParameterException("logisticscity", $pLogisticsCity, 64, "内容格式错误", __LINE__);}
        
        //参数:收件地址区:logisticsArea
        $pLogisticsArea = GetParameterNoCode("logisticsarea",$json);
        if(!JudgeRegularFont($pLogisticsArea)){return JsonModelParameterException("logisticsarea", $pLogisticsArea, 64, "内容格式错误", __LINE__);}
        
        //参数:收件地址邮政编码:logisticsCode
        $pLogisticsCode = GetParameterNoCode("logisticscode",$json);
        if(!JudgeRegularFont($pLogisticsCode)){return JsonModelParameterException("logisticscode", $pLogisticsCode, 64, "内容格式错误", __LINE__);}
        
        //参数:收件详细地址:logisticsAddress
        $pLogisticsAddress = GetParameterNoCode("logisticsaddress",$json);
        if(!JudgeRegularFont($pLogisticsAddress)){return JsonModelParameterException("logisticsaddress", $pLogisticsAddress, 64, "内容格式错误", __LINE__);}
        
        //参数:快件公司:logisticsCompany
        $pLogisticsCompany = GetParameterNoCode("logisticscompany",$json);
        if(!JudgeRegularFont($pLogisticsCompany)){return JsonModelParameterException("logisticscompany", $pLogisticsCompany, 64, "内容格式错误", __LINE__);}
        
        //参数:快件公司编码:logisticsCompanyCode
        $pLogisticsCompanyCode = GetParameterNoCode("logisticscompanycode",$json);
        if(!JudgeRegularFont($pLogisticsCompanyCode)){return JsonModelParameterException("logisticscompanycode", $pLogisticsCompanyCode, 64, "内容格式错误", __LINE__);}
        
        //参数:快件单号:logisticsNumber
        $pLogisticsNumber = GetParameterNoCode("logisticsnumber",$json);
        if(!JudgeRegularFont($pLogisticsNumber)){return JsonModelParameterException("logisticsnumber", $pLogisticsNumber, 64, "内容格式错误", __LINE__);}
        
        //--- Json组合区 ---
        //Json数组
        $jsonKeyValueArray = array(
            "table_name" => self::$tableName,
            "insert_field" => "recodeName,recodeGroup,userId,userNickname,productId,productName,productPrice,productNumber,productTotal,orderNumber,logisticsName,logisticsPhone,logisticsProvince,logisticsCity,logisticsArea,logisticsCode,logisticsAddress,logisticsCompany,logisticsCompanyCode,logisticsNumber",
            "recodename" => $pRecodeName,
            "recodegroup" => $pRecodeGroup,
            "userid" => $pUserId,
            "usernickname" => $pUserNickname,
            "productid" => $pProductId,
            "productname" => $pProductName,
            "productprice" => $pProductPrice,
            "productnumber" => $pProductNumber,
            "producttotal" => $pProductTotal,
            "ordernumber" => $pOrderNumber,
            "logisticsname" => $pLogisticsName,
            "logisticsphone" => $pLogisticsPhone,
            "logisticsprovince" => $pLogisticsProvince,
            "logisticscity" => $pLogisticsCity,
            "logisticsarea" => $pLogisticsArea,
            "logisticscode" => $pLogisticsCode,
            "logisticsaddress" => $pLogisticsAddress,
            "logisticscompany" => $pLogisticsCompany,
            "logisticscompanycode" => $pLogisticsCompanyCode,
            "logisticsnumber" => $pLogisticsNumber,
            //"key_field" => "userId,productId",
            //- 修改记录 -
            "where_field" => "",
            "where_value" => "",
            "update_field" => "",
            "update_value" => "",
            "execution_step" => "update,insert",
        );
        
        //修改字段判断组合
        $jsonKeyValueArray = HandleFlyJsonAddWhereField($jsonKeyValueArray, "id", $pId);
        $jsonKeyValueArray = HandleFlyJsonAddUpdateField($jsonKeyValueArray, "recodeName", $pRecodeName);
        $jsonKeyValueArray = HandleFlyJsonAddUpdateField($jsonKeyValueArray, "recodeGroup", $pRecodeGroup);
        $jsonKeyValueArray = HandleFlyJsonAddUpdateField($jsonKeyValueArray, "userId", $pUserId);
        $jsonKeyValueArray = HandleFlyJsonAddUpdateField($jsonKeyValueArray, "userNickname", $pUserNickname);
        $jsonKeyValueArray = HandleFlyJsonAddUpdateField($jsonKeyValueArray, "productId", $pProductId);
        $jsonKeyValueArray = HandleFlyJsonAddUpdateField($jsonKeyValueArray, "productName", $pProductName);
        $jsonKeyValueArray = HandleFlyJsonAddUpdateField($jsonKeyValueArray, "productPrice", $pProductPrice);
        $jsonKeyValueArray = HandleFlyJsonAddUpdateField($jsonKeyValueArray, "productNumber", $pProductNumber);
        $jsonKeyValueArray = HandleFlyJsonAddUpdateField($jsonKeyValueArray, "productTotal", $pProductTotal);
        $jsonKeyValueArray = HandleFlyJsonAddUpdateField($jsonKeyValueArray, "orderNumber", $pOrderNumber);
        $jsonKeyValueArray = HandleFlyJsonAddUpdateField($jsonKeyValueArray, "logisticsName", $pLogisticsName);
        $jsonKeyValueArray = HandleFlyJsonAddUpdateField($jsonKeyValueArray, "logisticsPhone", $pLogisticsPhone);
        $jsonKeyValueArray = HandleFlyJsonAddUpdateField($jsonKeyValueArray, "logisticsProvince", $pLogisticsProvince);
        $jsonKeyValueArray = HandleFlyJsonAddUpdateField($jsonKeyValueArray, "logisticsCity", $pLogisticsCity);
        $jsonKeyValueArray = HandleFlyJsonAddUpdateField($jsonKeyValueArray, "logisticsArea", $pLogisticsArea);
        $jsonKeyValueArray = HandleFlyJsonAddUpdateField($jsonKeyValueArray, "logisticsCode", $pLogisticsCode);
        $jsonKeyValueArray = HandleFlyJsonAddUpdateField($jsonKeyValueArray, "logisticsAddress", $pLogisticsAddress);
        $jsonKeyValueArray = HandleFlyJsonAddUpdateField($jsonKeyValueArray, "logisticsCompany", $pLogisticsCompany);
        $jsonKeyValueArray = HandleFlyJsonAddUpdateField($jsonKeyValueArray, "logisticsCompanyCode", $pLogisticsCompanyCode);
        $jsonKeyValueArray = HandleFlyJsonAddUpdateField($jsonKeyValueArray, "logisticsNumber", $pLogisticsNumber);
        
        //执行:添加
        $vJsonResult = MIndexDataInsert(JsonHandleArray($jsonKeyValueArray));
        if(JudgeJsonTrue($vJsonResult)){
            ObjFlyUserAdminOperationLog() -> SystemFlyUserAdminOperationLogAdd($fpAdminId, __CLASS__, __FUNCTION__, "管理员添加记录", "0");
        }
        return $vJsonResult;
    }
    
    
    /**
     * 函数名称：订单:管理员:记录查询
     * 函数调用：ObjTestOrder() -> AdminTestOrderPaging($fpAdminId)
     * 创建时间：2021-01-13 14:56:56
     * */
    public function AdminTestOrderPaging($fpAdminId){
        
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
        //$vDataField = "id,onlyKey,descript,indexNumber,updateTime,addTime,shelfState,state,recodeName,recodeGroup,userId,userNickname,productId,productName,productPrice,productNumber,productTotal,orderNumber,logisticsName,logisticsPhone,logisticsProvince,logisticsCity,logisticsArea,logisticsCode,logisticsAddress,logisticsCompany,logisticsCompanyCode,logisticsNumber";
        
        //渲染提示
        //$vResultTips = GetParameterRenderTips();
        //if(JudgeJsonFalseString($vResultTips)){return $vResultTips;}
        
        //--- Json组合区 ---
        $jsonKeyValueArray = array(
            "table_name" => self::$tableName,
            "data_field" => $vDataField,
            "where_field" => "",
            "where_value" => "",
            "page" => $pPage,
            "limit" => $pLimit,
            //"orderby" => "id:desc",
            "like_field" => $pLikeField,
            "like_key" => $pLikeKey,
            //"result_tips" => $vResultTips,
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
     * 函数名称：订单:管理员:记录修改
     * 函数调用：ObjTestOrder() -> AdminTestOrderSet($fpAdminId)
     * 创建时间：2021-01-13 14:56:56
     * */
    public function AdminTestOrderSet($fpAdminId){
        
        //--- 参数限制区 ---
        $vParameterArray = GetArray(FLY_PARAMETER_LIMIT, ",");
        $bParameterJudge = JudgeParameterLimit($vParameterArray);
        if(JudgeJsonFalse($bParameterJudge)){return $bParameterJudge;}
        
        //--- 接口调试输出 ---
        if(GetParameterRequest("interfacecode")=="true"){return WriteFunctionCode(__FILE__,__FUNCTION__);};	//输出接口主函数代码
        
        
        //--- 数据预定义 ---
        $json = "";
        
        //--- 参数获取区 ---
        //参数:自增长ID:id
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
        
        //参数:记录名称:recodeName
        $pRecodeName = GetParameterNoCode("recodename",$json);
        if(!IsNull($pRecodeName)&&!JudgeRegularFont($pRecodeName)){return JsonModelParameterException("recodename", $pRecodeName, 36, "内容格式错误", __LINE__);}
        
        //参数:记录组:recodeGroup
        $pRecodeGroup = GetParameterNoCode("recodegroup",$json);
        if(!IsNull($pRecodeGroup)&&!JudgeRegularFont($pRecodeGroup)){return JsonModelParameterException("recodegroup", $pRecodeGroup, 36, "内容格式错误", __LINE__);}
        
        //参数:用户ID:userId
        $pUserId = GetParameterNoCode("userid",$json);
        if(!IsNull($pUserId)&&!JudgeRegularIntRight($pUserId)){return JsonModelParameterException("userid", $pUserId, 20, "值必须是正整数", __LINE__);}
        
        //参数:用户昵称:userNickname
        $pUserNickname = GetParameterNoCode("usernickname",$json);
        if(!IsNull($pUserNickname)&&!JudgeRegularFont($pUserNickname)){return JsonModelParameterException("usernickname", $pUserNickname, 64, "内容格式错误", __LINE__);}
        
        //参数:产品ID:productId
        $pProductId = GetParameterNoCode("productid",$json);
        if(!IsNull($pProductId)&&!JudgeRegularIntRight($pProductId)){return JsonModelParameterException("productid", $pProductId, 20, "值必须是正整数", __LINE__);}
        
        //参数:产品名称:productName
        $pProductName = GetParameterNoCode("productname",$json);
        if(!IsNull($pProductName)&&!JudgeRegularFont($pProductName)){return JsonModelParameterException("productname", $pProductName, 64, "内容格式错误", __LINE__);}
        
        //参数:产品价格:productPrice
        $pProductPrice = GetParameterNoCode("productprice",$json);
        if(!IsNull($pProductPrice)&&!JudgeRegularDouble($pProductPrice)){return JsonModelParameterException("productprice", $pProductPrice, 12, "值必须是数值", __LINE__);}
        
        //参数:产品购买数量:productNumber
        $pProductNumber = GetParameterNoCode("productnumber",$json);
        if(!IsNull($pProductNumber)&&!JudgeRegularInt($pProductNumber)){return JsonModelParameterException("productnumber", $pProductNumber, 11, "值必须是整数", __LINE__);}
        
        //参数:产品购买总金额:productTotal
        $pProductTotal = GetParameterNoCode("producttotal",$json);
        if(!IsNull($pProductTotal)&&!JudgeRegularDouble($pProductTotal)){return JsonModelParameterException("producttotal", $pProductTotal, 12, "值必须是数值", __LINE__);}
        
        //参数:订单号:orderNumber
        $pOrderNumber = GetParameterNoCode("ordernumber",$json);
        if(!IsNull($pOrderNumber)&&!JudgeRegularFont($pOrderNumber)){return JsonModelParameterException("ordernumber", $pOrderNumber, 64, "内容格式错误", __LINE__);}
        
        //参数:收件人姓名:logisticsName
        $pLogisticsName = GetParameterNoCode("logisticsname",$json);
        if(!IsNull($pLogisticsName)&&!JudgeRegularFont($pLogisticsName)){return JsonModelParameterException("logisticsname", $pLogisticsName, 64, "内容格式错误", __LINE__);}
        
        //参数:收件人手机号:logisticsPhone
        $pLogisticsPhone = GetParameterNoCode("logisticsphone",$json);
        if(!IsNull($pLogisticsPhone)&&!JudgeRegularPhone($pLogisticsPhone)){return JsonModelParameterException("logisticsphone", $pLogisticsPhone, 64, "手机号格式错误", __LINE__);}
        
        //参数:收件地址省:logisticsProvince
        $pLogisticsProvince = GetParameterNoCode("logisticsprovince",$json);
        if(!IsNull($pLogisticsProvince)&&!JudgeRegularFont($pLogisticsProvince)){return JsonModelParameterException("logisticsprovince", $pLogisticsProvince, 64, "内容格式错误", __LINE__);}
        
        //参数:收件地址市:logisticsCity
        $pLogisticsCity = GetParameterNoCode("logisticscity",$json);
        if(!IsNull($pLogisticsCity)&&!JudgeRegularFont($pLogisticsCity)){return JsonModelParameterException("logisticscity", $pLogisticsCity, 64, "内容格式错误", __LINE__);}
        
        //参数:收件地址区:logisticsArea
        $pLogisticsArea = GetParameterNoCode("logisticsarea",$json);
        if(!IsNull($pLogisticsArea)&&!JudgeRegularFont($pLogisticsArea)){return JsonModelParameterException("logisticsarea", $pLogisticsArea, 64, "内容格式错误", __LINE__);}
        
        //参数:收件地址邮政编码:logisticsCode
        $pLogisticsCode = GetParameterNoCode("logisticscode",$json);
        if(!IsNull($pLogisticsCode)&&!JudgeRegularFont($pLogisticsCode)){return JsonModelParameterException("logisticscode", $pLogisticsCode, 64, "内容格式错误", __LINE__);}
        
        //参数:收件详细地址:logisticsAddress
        $pLogisticsAddress = GetParameterNoCode("logisticsaddress",$json);
        if(!IsNull($pLogisticsAddress)&&!JudgeRegularFont($pLogisticsAddress)){return JsonModelParameterException("logisticsaddress", $pLogisticsAddress, 64, "内容格式错误", __LINE__);}
        
        //参数:快件公司:logisticsCompany
        $pLogisticsCompany = GetParameterNoCode("logisticscompany",$json);
        if(!IsNull($pLogisticsCompany)&&!JudgeRegularFont($pLogisticsCompany)){return JsonModelParameterException("logisticscompany", $pLogisticsCompany, 64, "内容格式错误", __LINE__);}
        
        //参数:快件公司编码:logisticsCompanyCode
        $pLogisticsCompanyCode = GetParameterNoCode("logisticscompanycode",$json);
        if(!IsNull($pLogisticsCompanyCode)&&!JudgeRegularFont($pLogisticsCompanyCode)){return JsonModelParameterException("logisticscompanycode", $pLogisticsCompanyCode, 64, "内容格式错误", __LINE__);}
        
        //参数:快件单号:logisticsNumber
        $pLogisticsNumber = GetParameterNoCode("logisticsnumber",$json);
        if(!IsNull($pLogisticsNumber)&&!JudgeRegularFont($pLogisticsNumber)){return JsonModelParameterException("logisticsnumber", $pLogisticsNumber, 64, "内容格式错误", __LINE__);}
        
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
        $jsonKeyValueArray = HandleFlyJsonAddUpdateField($jsonKeyValueArray, "recodeName", $pRecodeName);
        $jsonKeyValueArray = HandleFlyJsonAddUpdateField($jsonKeyValueArray, "recodeGroup", $pRecodeGroup);
        $jsonKeyValueArray = HandleFlyJsonAddUpdateField($jsonKeyValueArray, "userId", $pUserId);
        $jsonKeyValueArray = HandleFlyJsonAddUpdateField($jsonKeyValueArray, "userNickname", $pUserNickname);
        $jsonKeyValueArray = HandleFlyJsonAddUpdateField($jsonKeyValueArray, "productId", $pProductId);
        $jsonKeyValueArray = HandleFlyJsonAddUpdateField($jsonKeyValueArray, "productName", $pProductName);
        $jsonKeyValueArray = HandleFlyJsonAddUpdateField($jsonKeyValueArray, "productPrice", $pProductPrice);
        $jsonKeyValueArray = HandleFlyJsonAddUpdateField($jsonKeyValueArray, "productNumber", $pProductNumber);
        $jsonKeyValueArray = HandleFlyJsonAddUpdateField($jsonKeyValueArray, "productTotal", $pProductTotal);
        $jsonKeyValueArray = HandleFlyJsonAddUpdateField($jsonKeyValueArray, "orderNumber", $pOrderNumber);
        $jsonKeyValueArray = HandleFlyJsonAddUpdateField($jsonKeyValueArray, "logisticsName", $pLogisticsName);
        $jsonKeyValueArray = HandleFlyJsonAddUpdateField($jsonKeyValueArray, "logisticsPhone", $pLogisticsPhone);
        $jsonKeyValueArray = HandleFlyJsonAddUpdateField($jsonKeyValueArray, "logisticsProvince", $pLogisticsProvince);
        $jsonKeyValueArray = HandleFlyJsonAddUpdateField($jsonKeyValueArray, "logisticsCity", $pLogisticsCity);
        $jsonKeyValueArray = HandleFlyJsonAddUpdateField($jsonKeyValueArray, "logisticsArea", $pLogisticsArea);
        $jsonKeyValueArray = HandleFlyJsonAddUpdateField($jsonKeyValueArray, "logisticsCode", $pLogisticsCode);
        $jsonKeyValueArray = HandleFlyJsonAddUpdateField($jsonKeyValueArray, "logisticsAddress", $pLogisticsAddress);
        $jsonKeyValueArray = HandleFlyJsonAddUpdateField($jsonKeyValueArray, "logisticsCompany", $pLogisticsCompany);
        $jsonKeyValueArray = HandleFlyJsonAddUpdateField($jsonKeyValueArray, "logisticsCompanyCode", $pLogisticsCompanyCode);
        $jsonKeyValueArray = HandleFlyJsonAddUpdateField($jsonKeyValueArray, "logisticsNumber", $pLogisticsNumber);
        
        //判断字段值是否为空
        $vUpdateFieldJudgeInfor = JudgeFlyJsonAddUpdateField($jsonKeyValueArray,"descript,indexnumber,shelfstate,state,recodename,recodegroup,userid,usernickname,productid,productname,productprice,productnumber,producttotal,ordernumber,logisticsname,logisticsphone,logisticsprovince,logisticscity,logisticsarea,logisticscode,logisticsaddress,logisticscompany,logisticscompanycode,logisticsnumber");
        if(JudgeJsonFalse($vUpdateFieldJudgeInfor)){ return $vUpdateFieldJudgeInfor; }
        
        //执行:修改
        $vJsonResult = MIndexDataUpdate(JsonHandleArray($jsonKeyValueArray));
        if(JudgeJsonTrue($vJsonResult)){
            ObjFlyUserAdminOperationLog() -> SystemFlyUserAdminOperationLogAdd($fpAdminId, __CLASS__, __FUNCTION__, "管理员修改记录", $pId);
        }
        return $vJsonResult;
        
    }
    
    
    /**
     * 函数名称：订单:管理员:记录状态修改
     * 函数调用：ObjTestOrder() -> AdminTestOrderSetState($fpAdminId)
     * 创建时间：2021-01-13 14:56:56
     * */
    public function AdminTestOrderSetState($fpAdminId){
        
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
     * 函数名称：订单:管理员:数据上下架状态修改
     * 函数调用：ObjTestOrder() -> AdminTestOrderShelfState($fpAdminId)
     * 创建时间：2021-01-13 14:56:56
     * */
    public function AdminTestOrderShelfState($fpAdminId){
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
     * 函数名称：订单:管理员:记录永久删除
     * 函数调用：ObjTestOrder() -> AdminTestOrderDelete($fpAdminId)
     * 创建时间：2021-01-13 14:56:56
     * */
    public function AdminTestOrderDelete($fpAdminId){
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
     * 函数调用：ObjTestOrder() -> GetTableName()
     * 创建时间：2021-01-13 14:56:55
     * */
    public function GetTableName(){
        return self::$tableName;
    }
    
    /**
     * 函数名称：获取类描述
     * 函数调用：ObjTestOrder() -> GetClassDescript()
     * 创建时间：2021-01-13 14:56:55
     * */
    public function GetClassDescript(){
        return self::$classDescript;
    }
    
    /**
     * 函数名称：获取数据表字段
     * 函数调用：ObjTestOrder() -> GetTableField()
     * 创建时间：2021-01-13 14:56:55
     * */
    public function GetTableField(){
        return MIndexDBTableFields(JsonObj(JsonKeyValue("table_name", self::$tableName)));
    }
    
    /**
     * 函数名称：类数据表创建
     * 函数调用：ObjTestOrder() -> OprationCreateTable()
     * 创建时间：2021-01-13 14:56:55
     * */
    public function OprationCreateTable(){
        $sql = "CREATE TABLE `test_order` (  `id` bigint(20) NOT NULL AUTO_INCREMENT COMMENT '自增长ID',  `onlyKey` varchar(36) DEFAULT NULL COMMENT '唯一Key',  `descript` varchar(36) DEFAULT NULL COMMENT '记录描述',  `indexNumber` int(11) DEFAULT '-1' COMMENT '序号',  `updateTime` timestamp NULL DEFAULT NULL COMMENT '修改时间',  `addTime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '添加时间',  `shelfState` varchar(36) DEFAULT 'true' COMMENT '上下架状态',  `state` varchar(36) DEFAULT 'STATE_NORMAL' COMMENT '记录状态',  `recodeName` varchar(36) DEFAULT NULL COMMENT '记录名称',  `recodeGroup` varchar(36) DEFAULT NULL COMMENT '记录组',  `userId` bigint(20) DEFAULT NULL COMMENT '用户ID',  `userNickname` varchar(64) DEFAULT NULL COMMENT '用户昵称',  `productId` bigint(20) DEFAULT NULL COMMENT '产品ID',  `productName` varchar(64) DEFAULT NULL COMMENT '产品名称',  `productPrice` decimal(10,2) DEFAULT NULL COMMENT '产品价格',  `productNumber` int(11) DEFAULT NULL COMMENT '产品购买数量',  `productTotal` decimal(10,2) DEFAULT NULL COMMENT '产品购买总金额',  `orderNumber` varchar(64) DEFAULT NULL COMMENT '订单号',  `logisticsName` varchar(64) DEFAULT NULL COMMENT '收件人姓名',  `logisticsPhone` varchar(64) DEFAULT NULL COMMENT '收件人手机号',  `logisticsProvince` varchar(64) DEFAULT NULL COMMENT '收件地址省',  `logisticsCity` varchar(64) DEFAULT NULL COMMENT '收件地址市',  `logisticsArea` varchar(64) DEFAULT NULL COMMENT '收件地址区',  `logisticsCode` varchar(64) DEFAULT NULL COMMENT '收件地址邮政编码',  `logisticsAddress` varchar(64) DEFAULT NULL COMMENT '收件详细地址',  `logisticsCompany` varchar(64) DEFAULT NULL COMMENT '快件公司',  `logisticsCompanyCode` varchar(64) DEFAULT NULL COMMENT '快件公司编码',  `logisticsNumber` varchar(64) DEFAULT NULL COMMENT '快件单号',  PRIMARY KEY (`id`)) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='订单'";
        DBHelper::DataSubmit($sql, "");
        $vTableCheck = DBMySQLServiceJson::GetDBTableCheck('{"table_name":"'.self::$tableName.'"}');
        if(JudgeJsonTrue($vTableCheck)){
            return JsonInforTrue("创建成功", self::$tableName);
        }
        return JsonInforFalse("创建失败", self::$tableName);
    }
    
    /**
     * 函数名称：类数据表基础字段检测
     * 函数调用：ObjTestOrder() -> OprationTableFieldBaseCheck()
     * 创建时间：2021-01-13 14:56:55
     * */
    public function OprationTableFieldBaseCheck(){
        return DBMySQLServiceJson::OprationFieldBaseCheck(JsonObj(JsonKeyValue("table_name", self::$tableName)));
    }
    
    
    
    
}
