<?php

/**------------------------------------*
 * 作者：shark
 * 创建时间：2020-10-01 17:13:13
 * Fly编码：1601543593017FLY842957
 * 类对象名：ObjSysShopolStock()
 * ------------------------------------ */

//引入区

class FlyClassSysShopolStock{
    
    
    //---------- 类成员（member） ----------
    
    //类描述
    public static $classDescript = "系统-线上店铺-商品库存";
    //类数据表名
    public static $tableName = "sys_shopol_stock";
    
    
    //---------- 私有方法（private） ----------
    
    //计算库存
    private function fCalcStockSum($fpShopId,$fpGoodsId,$fpNumber){
        //参数:库存总量:stockSum
        $pStockSum = DBHelper::DataString("SELECT stockSum FROM sys_shopol_stock WHERE shopId='{$fpShopId}' AND goodsId='{$fpGoodsId}' ORDER BY id DESC LIMIT 0,1", null);
        if(IsNull($pStockSum)){
            $pStockSum = intval($fpNumber);
        }else{
            $pStockSum = intval($pStockSum) + intval($fpNumber);
        }
        return $pStockSum;
    }
    
    
    //---------- 游客方法（visitor） ----------
    
    /**
     * 函数名称：系统-线上店铺-商品库存:游客:记录查询
     * 函数调用：ObjSysShopolStock() -> VisitorSysShopolStockPaging()
     * 创建时间：2020-10-01 17:13:12
     * */
    public function VisitorSysShopolStockPaging(){
        
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
        //$vDataField = "id,onlyKey,descript,indexNumber,updateTime,addTime,shelfState,state,shopId,goodsId,userId,operationType,number,stockSum,stockDescript";
        
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
     * 函数名称：系统-线上店铺-商品库存:系统:记录添加
     * 函数调用：ObjSysShopolStock() -> SystemSysShopolStockAdd
     * 创建时间：2020-10-01 17:13:12
     * */
    public function SystemSysShopolStockAdd($fpShopId,$fpGoodsId,$fpUserId,$fpOperationType,$fpNumber,$fpStockSum,$fpStockDescript){
        
        //--- Json组合区 ---
        //Json数组
        $jsonKeyValueArray = array(
            "table_name" => self::$tableName,
            "insert_field" => "descript,shelfState,shopId,goodsId,userId,operationType,number,stockSum,stockDescript",
            "descript" => self::$classDescript,
            "shelfstate" => "false",
            "shopid" => $fpShopId,
            "goodsid" => $fpGoodsId,
            "userid" => $fpUserId,
            "operationtype" => $fpOperationType,
            "number" => $fpNumber,
            "stocksum" => $fpStockSum,
            "stockdescript" => $fpStockDescript,
            "key_field" => "shopId,goodsId,userId",
        );
        //执行:添加
        $vJsonResult = MIndexDataInsert(JsonHandleArray($jsonKeyValueArray));
        return $vJsonResult;
    }
    
    
    //---------- 用户方法（user） ----------
    
    /**
     * 函数名称：系统-线上店铺-商品库存:用户:记录查询
     * 函数调用：ObjSysShopolStock() -> UserSysShopolStockPaging($fpUserId)
     * 创建时间：2020-10-01 17:13:12
     * */
    public function UserSysShopolStockPaging($fpUserId){
        
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
        //$vDataField = "id,onlyKey,descript,indexNumber,updateTime,addTime,shelfState,state,shopId,goodsId,userId,operationType,number,stockSum,stockDescript";
        
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
     * 函数名称：系统-线上店铺-商品库存:管理员:记录添加
     * 函数调用：ObjSysShopolStock() -> AdminSysShopolStockAdd($fpAdminId)
     * 创建时间：2020-10-01 17:13:12
     * */
    public function AdminSysShopolStockAdd($fpAdminId){
        
        
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
        
        //参数:店铺ID:shopId
        $pShopId = GetParameterNoCode("shopid",$json);
        if(!JudgeRegularIntRight($pShopId)){return JsonModelParameterException("shopid", $pShopId, 20, "值必须是正整数", __LINE__);}
        
        //参数:商品ID:goodsId
        $pGoodsId = GetParameterNoCode("goodsid",$json);
        if(!JudgeRegularIntRight($pGoodsId)){return JsonModelParameterException("goodsid", $pGoodsId, 20, "值必须是正整数", __LINE__);}
        
        //参数:用户ID:userId
        $pUserId = $fpAdminId;
        
        //参数:操作类型:operationType:网站管理员
        $pOperationType = "ADMIN_WEB";
        
        //参数:数量:number
        $pNumber = GetParameterNoCode("number",$json);
        if(!JudgeRegularInt($pNumber)){return JsonModelParameterException("number", $pNumber, 11, "值必须是整数", __LINE__);}
        
        //参数:库存总量:stockSum
        $pStockSum = self::fCalcStockSum($pShopId, $pGoodsId, $pNumber);
        
        //参数:库存操作描述:stockDescript
        $pStockDescript = GetParameterNoCode("stockdescript",$json);
        if(!JudgeRegularFont($pStockDescript)){return JsonModelParameterException("stockdescript", $pStockDescript, 36, "内容格式错误", __LINE__);}
        
        //--- Json组合区 ---
        //Json数组
        $jsonKeyValueArray = array(
            "table_name" => self::$tableName,
            "insert_field" => "descript,shelfState,shopId,goodsId,userId,operationType,number,stockSum,stockDescript",
            "descript" => self::$classDescript,
            "shelfstate" => $pShelfState,
            "shopid" => $pShopId,
            "goodsid" => $pGoodsId,
            "userid" => $pUserId,
            "operationtype" => $pOperationType,
            "number" => $pNumber,
            "stocksum" => $pStockSum,
            "stockdescript" => $pStockDescript,
        );
        //执行:添加
        $vJsonResult = MIndexDataInsert(JsonHandleArray($jsonKeyValueArray));
        if(JudgeJsonTrue($vJsonResult)){
            ObjFlyUserAdminOperationLog() -> SystemFlyUserAdminOperationLogAdd($fpAdminId, __CLASS__, __FUNCTION__, "管理员添加记录", "0");
        }
        return $vJsonResult;
    }
    
    
    /**
     * 函数名称：系统-线上店铺-商品库存:管理员:记录查询
     * 函数调用：ObjSysShopolStock() -> AdminSysShopolStockPaging($fpAdminId)
     * 创建时间：2020-10-01 17:13:12
     * */
    public function AdminSysShopolStockPaging($fpAdminId){
        
        //--- 参数限制区 ---
        $vParameterArray = GetArray(FLY_PARAMETER_LIMIT, ",");
        $bParameterJudge = JudgeParameterLimit($vParameterArray);
        if(JudgeJsonFalse($bParameterJudge)){return $bParameterJudge;}
        
        //--- 数据预定义 ---
        $json = "";
        
        //--- 参数获取区 ---
        $pPage = GetParameterNoCode("page","");     //参数:页码:page
        $pLimit = GetParameterNoCode("limit","");   //参数:条数:limit
        
        //参数:店铺ID:shopId
        $pShopId = GetParameterNoCode("shopid",$json);
        if(!JudgeRegularIntRight($pShopId)){return JsonModelParameterException("shopid", $pShopId, 20, "值必须是正整数", __LINE__);}
        
        //参数:商品ID:goodsId
        $pGoodsId = GetParameterNoCode("goodsid",$json);
        if(!JudgeRegularIntRight($pGoodsId)){return JsonModelParameterException("goodsid", $pGoodsId, 20, "值必须是正整数", __LINE__);}
        
        
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
        //$vDataField = "id,onlyKey,descript,indexNumber,updateTime,addTime,shelfState,state,shopId,goodsId,userId,operationType,number,stockSum,stockDescript";
        
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
            "where_field" => "shopId,goodsId",
            "where_value" => "{$pShopId},{$pGoodsId}",
        );
        
        //条件字段判断组合
        $jsonKeyValueArray = HandleFlyJsonAddWhereField($jsonKeyValueArray, "id", $pId);
        $jsonKeyValueArray = HandleFlyJsonAddWhereField($jsonKeyValueArray, "state", $pWhereState);
        $jsonKeyValueArray = HandleFlyJsonAddWhereField($jsonKeyValueArray, "shelfState", $pWhereShelfState);
        $jsonKeyValueArray = HandleFlyJsonAddWhereField($jsonKeyValueArray, $pStateField, $pStateKey);
        //返回结果:分页
        $result = MIndexDataPaging(JsonHandleArray($jsonKeyValueArray));
        //设置状态数据说明
        $vStateAdminWeb = JsonHandleArrayString([JsonKeyValue("field", "operationType"),JsonKeyValue("state", "ADMIN_WEB"),JsonKeyValue("descript", "网站管理员")]);
        $vStateShopWeb = JsonHandleArrayString([JsonKeyValue("field", "operationType"),JsonKeyValue("state", "SHOP_WEB"),JsonKeyValue("descript", "店铺管理员")]);
        $vStateUser = JsonHandleArrayString([JsonKeyValue("field","operationType"),JsonKeyValue("state", "USER"),JsonKeyValue("descript", "用户")]);
        $vJsonState = JsonKeyString("stateList", JsonHandleObjArrayString([$vStateAdminWeb,$vStateShopWeb,$vStateUser]));
        $result = JsonHandleFlyInforExtend($result,$vJsonState);
        return $result;
        
    }
    
    
    /**
     * 函数名称：系统-线上店铺-商品库存:管理员:记录修改
     * 函数调用：ObjSysShopolStock() -> AdminSysShopolStockSet($fpAdminId)
     * 创建时间：2020-10-01 17:13:12
     * */
    public function AdminSysShopolStockSet($fpAdminId){
        
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
        
        //参数:店铺ID:shopId
        $pShopId = GetParameterNoCode("shopid",$json);
        if(!IsNull($pShopId)&&!JudgeRegularIntRight($pShopId)){return JsonModelParameterException("shopid", $pShopId, 20, "值必须是正整数", __LINE__);}
        
        //参数:商品ID:goodsId
        $pGoodsId = GetParameterNoCode("goodsid",$json);
        if(!IsNull($pGoodsId)&&!JudgeRegularIntRight($pGoodsId)){return JsonModelParameterException("goodsid", $pGoodsId, 20, "值必须是正整数", __LINE__);}
        
        //参数:用户ID:userId
        $pUserId = GetParameterNoCode("userid",$json);
        if(!IsNull($pUserId)&&!JudgeRegularIntRight($pUserId)){return JsonModelParameterException("userid", $pUserId, 20, "值必须是正整数", __LINE__);}
        
        //参数:操作类型:operationType
        $pOperationType = GetParameterNoCode("operationtype",$json);
        if(!IsNull($pOperationType)&&!JudgeRegularFont($pOperationType)){return JsonModelParameterException("operationtype", $pOperationType, 36, "内容格式错误", __LINE__);}
        
        //参数:数量:number
        $pNumber = GetParameterNoCode("number",$json);
        if(!IsNull($pNumber)&&!JudgeRegularInt($pNumber)){return JsonModelParameterException("number", $pNumber, 11, "值必须是整数", __LINE__);}
        
        //参数:库存总量:stockSum
        $pStockSum = GetParameterNoCode("stocksum",$json);
        if(!IsNull($pStockSum)&&!JudgeRegularInt($pStockSum)){return JsonModelParameterException("stocksum", $pStockSum, 11, "值必须是整数", __LINE__);}
        
        //参数:库存操作描述:stockDescript
        $pStockDescript = GetParameterNoCode("stockdescript",$json);
        if(!IsNull($pStockDescript)&&!JudgeRegularFont($pStockDescript)){return JsonModelParameterException("stockdescript", $pStockDescript, 36, "内容格式错误", __LINE__);}
        
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
        $jsonKeyValueArray = HandleFlyJsonAddUpdateField($jsonKeyValueArray, "shopId", $pShopId);
        $jsonKeyValueArray = HandleFlyJsonAddUpdateField($jsonKeyValueArray, "goodsId", $pGoodsId);
        $jsonKeyValueArray = HandleFlyJsonAddUpdateField($jsonKeyValueArray, "userId", $pUserId);
        $jsonKeyValueArray = HandleFlyJsonAddUpdateField($jsonKeyValueArray, "operationType", $pOperationType);
        $jsonKeyValueArray = HandleFlyJsonAddUpdateField($jsonKeyValueArray, "number", $pNumber);
        $jsonKeyValueArray = HandleFlyJsonAddUpdateField($jsonKeyValueArray, "stockSum", $pStockSum);
        $jsonKeyValueArray = HandleFlyJsonAddUpdateField($jsonKeyValueArray, "stockDescript", $pStockDescript);
        
        //判断字段值是否为空
        $vUpdateFieldJudgeInfor = JudgeFlyJsonAddUpdateField($jsonKeyValueArray,"descript,indexnumber,shelfstate,state,shopid,goodsid,userid,operationtype,number,stocksum,stockdescript");
        if(JudgeJsonFalse($vUpdateFieldJudgeInfor)){ return $vUpdateFieldJudgeInfor; }
        
        //执行:修改
        $vJsonResult = MIndexDataUpdate(JsonHandleArray($jsonKeyValueArray));
        if(JudgeJsonTrue($vJsonResult)){
            ObjFlyUserAdminOperationLog() -> SystemFlyUserAdminOperationLogAdd($fpAdminId, __CLASS__, __FUNCTION__, "管理员修改记录", $pId);
        }
        return $vJsonResult;
        
    }
    
    
    /**
     * 函数名称：系统-线上店铺-商品库存:管理员:记录状态修改
     * 函数调用：ObjSysShopolStock() -> AdminSysShopolStockSetState($fpAdminId)
     * 创建时间：2020-10-01 17:13:12
     * */
    public function AdminSysShopolStockSetState($fpAdminId){
        
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
        //执行:修改
        $vJsonResult = ServiceTableDataSystemSet(self::$tableName,"state","{$pState}","id","{$pId}");
        if(JudgeJsonTrue($vJsonResult)){
            ObjFlyUserAdminOperationLog() -> SystemFlyUserAdminOperationLogAdd($fpAdminId, __CLASS__, __FUNCTION__, "管理员修改记录状态", $pId);
        }
        return $vJsonResult;
    }
    
    /**
     * 函数名称：系统-线上店铺-商品库存:管理员:数据上下架状态修改
     * 函数调用：ObjSysShopolStock() -> AdminSysShopolStockShelfState($fpAdminId)
     * 创建时间：2020-10-01 17:13:12
     * */
    public function AdminSysShopolStockShelfState($fpAdminId){
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
        //执行:上下降状态修改
        $vJsonResult = MIndexDataUpdate(JsonHandleArray($jsonKeyValueArray));
        if(JudgeJsonTrue($vJsonResult)){
            ObjFlyUserAdminOperationLog() -> SystemFlyUserAdminOperationLogAdd($fpAdminId, __CLASS__, __FUNCTION__, "管理员记录上下架修改", $pId);
        }
        return $vJsonResult;
        
    }
    
    /**
     * 函数名称：系统-线上店铺-商品库存:管理员:记录永久删除
     * 函数调用：ObjSysShopolStock() -> AdminSysShopolStockDelete($fpAdminId)
     * 创建时间：2020-10-01 17:13:12
     * */
    public function AdminSysShopolStockDelete($fpAdminId){
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
        if(JudgeJsonTrue($vJsonResult)){
            ObjFlyUserAdminOperationLog() -> SystemFlyUserAdminOperationLogAdd($fpAdminId, __CLASS__, __FUNCTION__, "管理员删除记录", $pId);
        }
        return $vJsonResult;
    }
    
    
    //---------- 测试方法（test） ----------
    
    //---------- 基础方法（base） ----------
    
    
    /**
     * 函数名称：获取数据表名称
     * 函数调用：ObjSysShopolStock() -> GetTableName()
     * 创建时间：2020-10-01 17:13:12
     * */
    public function GetTableName(){
        return self::$tableName;
    }
    
    /**
     * 函数名称：获取类描述
     * 函数调用：ObjSysShopolStock() -> GetClassDescript()
     * 创建时间：2020-10-01 17:13:12
     * */
    public function GetClassDescript(){
        return self::$classDescript;
    }
    
    /**
     * 函数名称：获取数据表字段
     * 函数调用：ObjSysShopolStock() -> GetTableField()
     * 创建时间：2020-10-01 17:13:12
     * */
    public function GetTableField(){
        return MIndexDBTableFields(JsonObj(JsonKeyValue("table_name", self::$tableName)));
    }
    
    /**
     * 函数名称：类数据表创建
     * 函数调用：ObjSysShopolStock() -> OprationCreateTable()
     * 创建时间：2020-10-01 17:13:12
     * */
    public function OprationCreateTable(){
        $sql = "CREATE TABLE `sys_shopol_stock` (  `id` bigint(20) NOT NULL AUTO_INCREMENT COMMENT '表ID',  `onlyKey` varchar(36) DEFAULT NULL COMMENT '唯一Key',  `descript` varchar(36) DEFAULT NULL COMMENT '记录描述',  `indexNumber` int(11) DEFAULT '-1' COMMENT '序号',  `updateTime` timestamp NULL DEFAULT NULL COMMENT '修改时间',  `addTime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '添加时间',  `shelfState` varchar(36) DEFAULT 'true' COMMENT '上下架状态',  `state` varchar(36) DEFAULT 'STATE_NORMAL' COMMENT '记录状态',  `shopId` bigint(20) DEFAULT NULL COMMENT '店铺ID',  `goodsId` bigint(20) DEFAULT NULL COMMENT '商品ID',  `userId` bigint(20) DEFAULT NULL COMMENT '用户ID',  `operationType` varchar(36) DEFAULT NULL COMMENT '操作类型',  `number` int(11) DEFAULT NULL COMMENT '数量',  `stockSum` int(11) DEFAULT NULL COMMENT '库存总量',  `stockDescript` varchar(36) DEFAULT NULL COMMENT '库存操作描述',  PRIMARY KEY (`id`) USING BTREE) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='系统-线上店铺-商品库存'";
        DBHelper::DataSubmit($sql, "");
        $vTableCheck = DBMySQLServiceJson::GetDBTableCheck('{"table_name":"'.self::$tableName.'"}');
        if(JudgeJsonTrue($vTableCheck)){
            return JsonInforTrue("创建成功", self::$tableName);
        }
        return JsonInforFalse("创建失败", self::$tableName);
    }
    
    /**
     * 函数名称：类数据表基础字段检测
     * 函数调用：ObjSysShopolStock() -> OprationTableFieldBaseCheck()
     * 创建时间：2020-10-01 17:13:12
     * */
    public function OprationTableFieldBaseCheck(){
        return DBMySQLServiceJson::OprationFieldBaseCheck(JsonObj(JsonKeyValue("table_name", self::$tableName)));
    }
    
    
    
    
}
