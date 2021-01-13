<?php

    //--- 引入区 ---
    //请求头
    include_once $_SERVER['DOCUMENT_ROOT'] . "/server/c/header/header.php";

    //--- 参数限制区（判断非法参数） ---
    $vParameterArray = GetArray(FLY_PARAMETER_LIMIT, ",");
    $bParameterJudge = JudgeParameterLimit($vParameterArray);
    if(JudgeJsonFalse($bParameterJudge)){return WriteEcho($bParameterJudge);}
    
    //--- 全局参数 ---
    $tableName = "test_order";
    
    //--- 参数获取区 ---
    $pUserId = GetParameterRequest("user_id"); //用户ID
    $pInforType = GetParameterRequest("infor_type"); //消息类型
    $pProduct = GetParameterRequest("product"); //产品名称
    if(!IsNull($pProduct)&&!JudgeRegularFont($pProduct)){return WriteEcho(JsonModelParameterException("product", $pProduct, 64, "值必须是文本", __LINE__));}
    $pUserNickname = GetParameterRequest("user_nickname");       //用户名称
    if(!IsNull($pUserNickname)&&!JudgeRegularFont($pUserNickname)){return WriteEcho(JsonModelParameterException("user_nickname", $pUserNickname, 64, "值必须是文本", __LINE__));}
    $pSortBy = GetParameterRequest("sort_by");  //排序字段
    if(!IsNull($pSortBy)&&!JudgeRegularField($pSortBy)){return WriteEcho(JsonModelParameterException("sort_by", $pSortBy, 64, "值必须是文本", __LINE__));}
    $pSortOrder = GetParameterRequest("sort_order");    //升序降序
    if(!IsNull($pSortOrder)){$pSortOrder = HandleStringToLowerCase($pSortOrder);}
    if($pSortOrder!="desc"){$pSortOrder = "asc";}
    $vOrderByString = "";
    if(!IsNull($pSortBy)){
        $vOrderByString = "{$pSortBy}:{$pSortOrder}";
    }
    //页码
    $pPage = GetParameterNoCode("page","");     //参数:页码:page
    $pLimit = GetParameterNoCode("limit","");   //参数:条数:limit
    //参数：id
    $pId = GetParameterNoCode("id","");
    if(!IsNull($pId)&&!JudgeRegularIntRight($pId)){return WriteEcho(JsonModelParameterException("id", $pId, 20, "值必须是正整数", __LINE__));}
    if(!IsNull($pId)){ $pPage = 1; $pLimit = 1; }
    //业务逻辑
    if($pInforType=="ranking"){
        $pPage = "1";
        $pLimit = "100";
    }
    //参数判断:页码:page
    if(IsNull($pPage)||!JudgeRegularIntRight($pPage)){return WriteEcho(JsonModelParameterException("page", $pPage, 9, "值必须是正整数", __LINE__));}
    //参数判断:条数:limit
    if(IsNull($pLimit)||!JudgeRegularIntRight($pLimit)){return WriteEcho(JsonModelParameterException("limit", $pLimit, 9, "值必须是正整数", __LINE__));}
    //参数：记录状态
    $pWhereState = GetParameterNoCode("wherestate","");
    if(!IsNull($pWhereState)&&!($pWhereState=="STATE_NORMAL"||$pWhereState=="STATE_DELETE")){return WriteEcho(JsonModelParameterException("wherestate", $pWhereState, 64, "值必须是STATE_NORMAL/STATE_DELETE", __LINE__));}
    //参数：上下架状态
    $pWhereShelfState = GetParameterNoCode("whereshelfstate","");
    if(!IsNull($pWhereShelfState)&&!($pWhereShelfState=="true"||$pWhereShelfState=="false")){return WriteEcho(JsonModelParameterException("whereshelfstate", $pWhereShelfState, 36, "值必须是true/false", __LINE__));}
    //参数：like
    $pLikeField = GetParameterNoCode("likefield","");
    if(!IsNull($pLikeField)&&!DBMySQLJudge::JudgeTableField($tableName,$pLikeField)){ return WriteEcho(JsonInforFalse("搜索字段不存在", $pLikeField,__LINE__)); }
    $pLikeKey = GetParameterNoCode("likekey","");
    if(!IsNull($pLikeKey)){ $pLikeKey = HandleStringAddslashes($pLikeKey); }
    //参数：state
    $pStateField = GetParameterNoCode("statefield","");
    if(!IsNull($pStateField)&&!DBMySQLJudge::JudgeTableField($tableName,$pStateField)){ return WriteEcho(JsonInforFalse("状态字段不存在", $pStateField,__LINE__)); }
    $pStateKey = GetParameterNoCode("statekey","");
    if(!IsNull($pStateKey)&&!JudgeRegularState($pStateKey)){ return WriteEcho(JsonInforFalse("状态码错误", $pStateKey,__LINE__)); }
    
    //--- 业务逻辑区 ---
    
    
    if(!IsNull($pUserId)){
        if(!JudgeRegularId($pUserId)){
            return WriteEcho(JsonInforFalse("请传入正确的用户ID", $pUserId,__LINE__));
        }
        //查询用户信息
        $sql = "SELECT phoneNumber,loginTimes,loginTime,loginIp,userIdCardName,addTime FROM test_user WHERE id={$pUserId}";
        $vUserData = DBHelper::DataList($sql, null, ["phoneNumber","loginTimes","loginTime","loginIp","userIdCardName","addTime"]);
        if(IsNull($vUserData)){return WriteEcho(JsonInforFalse("用户不存在", $pUserId,__LINE__));}
        $vUserData = HandleStringDeleteFirst($vUserData);
        $vUserData = HandleStringDeleteLast($vUserData);
        //查询用户购买数量
        $jsonKeyValueArray = array(
            "table_name" => $tableName,
            "data_field" => "productId,productName,sumNumber,productTotal,tMonth",
            "where_field" => "",
            "where_value" => "",
            "page" => $pPage,
            "limit" => $pLimit,
            "sql" => "SELECT productId,productName,SUM(productNumber) sumNumber,SUM(productTotal) productTotal,DATE_FORMAT(ADDTIME,'%Y-%m') AS tMonth FROM test_order WHERE userId='{$pUserId}' GROUP BY tMonth,productId ORDER BY sumNumber DESC",
            "sql_count" => "SELECT productId,productName,SUM(productNumber) sumNumber,SUM(productTotal) productTotal,DATE_FORMAT(ADDTIME,'%Y-%m') AS tMonth FROM test_order WHERE userId='{$pUserId}' GROUP BY tMonth,productId ORDER BY sumNumber DESC",
        );
        $vResult = MIndexDataPaging(JsonHandleArray($jsonKeyValueArray));
        return WriteEcho(JsonHandleFlyInforExtend($vResult, JsonKeyString("userInfor", $vUserData)));
    }else if($pInforType=="ranking"){
        //查询用户购买数量
        $jsonKeyValueArray = array(
            "table_name" => $tableName,
            "data_field" => "productId,productName,sumNumber,productTotal,tMonth",
            "where_field" => "",
            "where_value" => "",
            "page" => $pPage,
            "limit" => $pLimit,
            "sql" => "SELECT a.userId,SUM(a.productNumber) sumNumber,SUM(a.productTotal) productTotal,b.userNick,b.phoneNumber FROM test_order AS a LEFT OUTER JOIN test_user AS b ON a.userId=b.id GROUP BY a.userId ORDER BY a.productTotal DESC",
            "sql_count" => "SELECT COUNT(TRUE) count FROM test_order AS a LEFT OUTER JOIN test_user AS b ON a.userId=b.id GROUP BY a.userId ORDER BY a.productTotal DESC",
        );
        return WriteEcho(MIndexDataPaging(JsonHandleArray($jsonKeyValueArray)));
    }else{
        //订单信息
        //字段定义
        $vDataField = DBMySQLSelect::TableFieldString($tableName);
        
        //--- Json组合区 ---
        $jsonKeyValueArray = array(
            "table_name" => $tableName,
            "data_field" => $vDataField,
            "where_field" => "",
            "where_value" => "",
            "page" => $pPage,
            "limit" => $pLimit,
            "orderby" => $vOrderByString,
            "like_field" => $pLikeField,
            "like_key" => $pLikeKey,
            //"result_tips" => $vResultTips,
        );
        
        //条件字段判断组合
        $jsonKeyValueArray = HandleFlyJsonAddWhereField($jsonKeyValueArray, "id", $pId);
        $jsonKeyValueArray = HandleFlyJsonAddWhereField($jsonKeyValueArray, "state", $pWhereState);
        $jsonKeyValueArray = HandleFlyJsonAddWhereField($jsonKeyValueArray, "shelfState", $pWhereShelfState);
        $jsonKeyValueArray = HandleFlyJsonAddWhereField($jsonKeyValueArray, "productName", $pProduct);
        $jsonKeyValueArray = HandleFlyJsonAddWhereField($jsonKeyValueArray, $pStateField, $pStateKey);
        //返回结果:分页
        return WriteEcho(MIndexDataPaging(JsonHandleArray($jsonKeyValueArray)));
        
    }
    
    