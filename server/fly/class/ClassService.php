<?php


    /** 用于业务相关代码，集成小逻辑实现功能的逻辑碎片方法*/

    //------------- 获取区 -------------
    

    /**
     * 获取PHP服务器Servlet信息
     * 创建时间：December 12,2018 09:21
     * 说明：返回PHP项目配置信息
     * 检测：逻辑
     * 检测时间：Decemeber 12,2018 09:22
     * */
    function ServiceServerCortorllerInfor(){
        //判断数据库链接是否正常
        $vCheckDataBaseLink = ObjSystem() -> CheckDataBaseLink();
        $vJudgeAdminTableBo = DBMySQLJudge::JudgeTable("fly_user_admin"); //判断管理员数据表是否存在
        if(JudgeJsonTrue($vCheckDataBaseLink)&&$vJudgeAdminTableBo){
            //判断是否有程序创建者
            $vAdminList = ObjRoleObjectAdmin() -> JudgeAdminCreater();
            if(JudgeJsonTrue($vAdminList)){
                //判断管理员是否是创建者
                $vAdmin = ObjRoleObjectAdmin() -> JudgeAdminIsCreater("",true);
                if(JudgeJsonFalse($vAdmin)){return WriteEcho($vAdmin);}
            }
        }
        //Java路径、Java路径请求
        $dataJson = "";
        //PHP
        $dataJson .= JsonQuotes("phpVersion:PHP版本").":".JsonQuotes(PHP_VERSION).",";
        $dataJson .= JsonQuotes("phpMaxExecutionTime:PHP最长执行时间").":".JsonQuotes(get_cfg_var("max_execution_time")."秒").",";
        //Server
        $dataJson .= JsonQuotes("serverSoftware:服务器软件").":".JsonQuotes($_SERVER["SERVER_SOFTWARE"]).",";
        $dataJson .= JsonQuotes("serverPort:服务器端口").":".JsonQuotes($_SERVER["SERVER_PORT"]).",";
        $dataJson .= JsonQuotes("serverProtocol:服务器通信协议和端口").":".JsonQuotes($_SERVER["SERVER_PROTOCOL"]).",";
        $dataJson .= JsonQuotes("serverIp:服务器IP").":".JsonQuotes($_SERVER['SERVER_ADDR']).",";
        //HTTP
        $dataJson .= JsonQuotes("httpHost:域名").":".JsonQuotes($_SERVER["HTTP_HOST"]).",";
        $dataJson .= JsonQuotes("httpAcceptLanguage:浏览器语言顺序").":".JsonQuotes($_SERVER["HTTP_ACCEPT_LANGUAGE"]).",";
        //System
        $dataJson .= JsonQuotes("systemIdentifier:处理器标识").":".JsonQuotes($_SERVER['PROCESSOR_IDENTIFIER']).",";
        $dataJson .= JsonQuotes("systemName:操作系统类型").":".JsonQuotes(PHP_OS).",";
        $dataJson .= JsonQuotes("systemVersion:操作系统版本号").":".JsonQuotes(php_uname()).",";
        $dataJson .= JsonQuotes("systemTime:操作系统时间").":".JsonQuotes(GetTimeNow()).",";
        $dataJson .= JsonQuotes("systemTimestamp:操作系统时间戳").":".JsonQuotes(GetTimestamp()).",";
        //DB
        $vCheckDataBaseLink = ObjSystem() -> CheckDataBaseLink();
        $vCheckLinkBo = "false";
        if(JudgeJsonTrue($vCheckDataBaseLink)){ $vCheckLinkBo = "true"; }
        $dataJson .= JsonQuotes("dbDescript:数据库描述").":".JsonQuotes(PROJECT_CONFIG_DB_DESCRIPT).",";
        $dataJson .= JsonQuotes("dbType:数据库类型").":".JsonQuotes(PROJECT_CONFIG_DB_TYPE).",";
        $dataJson .= JsonQuotes("dbHost:数据库IP").":".JsonQuotes(PROJECT_CONFIG_DB_HOST).",";
        $dataJson .= JsonQuotes("dbName:数据库名称").":".JsonQuotes(PROJECT_CONFIG_DB_NAME).",";
        $dataJson .= JsonQuotes("dbUser:数据库用户名").":".JsonQuotes(PROJECT_CONFIG_DB_USER).",";
        $dataJson .= JsonQuotes("dbPort:数据库端口").":".JsonQuotes(PROJECT_CONFIG_DB_PORT).",";
        $dataJson .= JsonQuotes("dbLinkBo:数据库连接结果").":".JsonQuotes($vCheckLinkBo).",";
        $dataJson .= JsonQuotes("dbLink:数据库连接信息").":".JsonQuotes(GetJsonValue($vCheckDataBaseLink, "infor")).",";
        //admin
        $vSystemCreater = ObjSystem() -> CheckCreater();
        $vCreaterBo = "false";
        if(JudgeJsonTrue($vSystemCreater)){ $vCreaterBo = "true"; }
        $dataJson .= JsonQuotes("adminCreaterBo:程序创建者是否存在").":".JsonQuotes($vCreaterBo).",";
        $dataJson .= JsonQuotes("adminCreater:程序创建者").":".JsonQuotes(GetJsonValue($vSystemCreater, "infor")).",";
        
        //组合Json
        $dataJson = JsonObj($dataJson);
        return JsonInforData("服务器信息", "项目环境检测", "", "1", $dataJson);
    }
    
    /**
     * 服务器详细信息
     * 创建时间：November 29,2018 17:00
     * 说明：根据请求返回获取服务器详细信息
     * 检测：Get提交、区域注释
     * */
    function ServletSeverInfor(){
        //MService 信息
        $dataJson = "";
        $dataJson .= JsonQuotes("systemName").":".JsonQuotes(PHP_OS).",";
        $dataJson .= JsonQuotes("systemVersion").":".JsonQuotes(php_uname()).",";
        $dataJson .= JsonQuotes("systemTime").":".JsonQuotes(GetTimeNow()).",";
        $dataJson .= JsonQuotes("systemTimestamp").":".JsonQuotes(GetTimestamp()).",";
        $dataJson .= JsonQuotes("hostIp").":".JsonQuotes($_SERVER['SERVER_ADDR']).",";
        $dataJson = JsonObj($dataJson);
        return JsonInforData("服务器详细信息", "PHP服务器", "", "1", $dataJson);
    }    
    
    /**
     * 服务器详细信息
     * 创建时间：November 29,2018 17:00
     * 说明：根据请求返回获取服务器详细信息
     * 检测：Get提交、区域注释
     * */
    function ServletSeverTime($json){
        //--- 参数获取区 ---
        //参数:时间类型
        $timetype = GetParameter("timetype",$json);
        $timetype = HandleStringToLowerCase($timetype);
        if(!(IsNull($timetype)||$timetype=="data"||$timetype=="time"||$timetype=="timestamp")){
            return JsonInforFalse("timetype 参数值必须是data、time、timestamp或为空", "系统时间类型");
        }
        
        if($timetype=="data"){
            return JsonInforTrue("日期","date",GetTimeDate());        
        }else if($timetype=="time"){
            return JsonInforTrue("时间","date",GetTime());
        }else if($timetype=="timestamp"){
            return JsonInforTrue("时间戳","timestamp",GetTimestamp());
        }
        return JsonInforTrue("日期时间","datetime",GetTimeNow());
    }    
    
    
    /**
     * 服务器IP
     * 创建时间：November 29,2018 16:55
     * 说明：根据请求返回获取服务器IP
     * 检测：Get提交、区域注释、参数注释、固参判断
     * */
    function ServletSeverIp(){
        $ip = $_SERVER['SERVER_ADDR'];
        return JsonInforTrue("IP获取", "服务器IP",$ip);
    }
    
    /**
     * 获取Java服务器Servlet信息
     * 创建时间：December 9,2018 22:18
     * 说明：返回M层服务器Servlet请求信息
     * 检测：逻辑
     * 检测时间：Decemeber 9,2018 22:19
     * */
    function ServiceMServerResult(){
        return JsonInforTrue("请求成功","M层Servlet");
    }
    
    /**
     * 获取Java服务器数据库连接情况
     * 创建时间：December 9,2018 22:51
     * 说明：返回M层服务器数据库连接情况
     * 检测：逻辑
     * 检测时间：Decemeber 9,2018 22:51
     * */
    function ServiceMServerDataBaseResult(){
        $pdo = DBHelper::GetConnection();
        if(DBHelper::JudgeConnection($pdo)){
            return JsonInforTrue("链接成功","M层DB");
        }else{
            return JsonInforFalse("链接失败","M层DB");
        }
    }
    
    //------------- 处理区 -------------


    /**
     * 获取数据表加载结果并进行处理
     * 创建时间：December 6,2018 10:02
     * 说明：加载数据表，对返回的结果进行判断
     * 检测：逻辑
     * 检测时间：Decemeber 6,2018 10:10
     * */
    function ServiceTableLoad($tablename){
        $json = MIndexTBLoad(JsonKeyValueObj("tablename", $tablename));
        $result = GetJsonValue($json, "result");
        $infor = GetJsonValue($json, "infor");
        return JsonKeyValueThreeObj("result", $result,"infor",$infor, "tablename", $tablename);
    }
    
    
    /**
     * 获取分页数据
     * 创建时间：December 28,2018 15:42
     * 说明：抽取分页数据请求的公共结构代码
     * 检测：逻辑
     * 检测时间：December 28,2018 15:43
     * */
    function ServiceTableDataPaging($tableName,$datafield){
        //Json数组
        $jsonKeyValueArray = array(
            "table_name" => $tableName,
            "data_field" => $datafield,
            "orderby" => "id:desc",
        );
        //组合Json
        $json = JsonHandleArray($jsonKeyValueArray);
        //返回结果
        return MIndexDataPaging($json);
    }
    
    /**
     * 获取分页数据（条件数据）
     * 创建时间：December 28,2018 22:26
     * 说明：抽取分页数据请求的公共结构代码，按条件执行
     * 检测：逻辑
     * 检测时间：December 28,2018 22:26
     * */
    function ServiceTableDataPagingWhere($tableName,$datafield,$whereField,$whereValue,$limit){
        //Json数组
        $jsonKeyValueArray = array(
            "table_name" => $tableName,
            "data_field" => $datafield,
            "where_field" => $whereField,
            "where_value" => $whereValue,
            "page" => "1",
            "limit" => $limit,
        );
        //组合Json
        $json = JsonHandleArray($jsonKeyValueArray);
        //返回结果
        return MIndexDataPaging($json);
    }
    
    /**
     * 获取修改数据
     * 创建时间：December 28,2018 15:52
     * 说明：抽取修改数据请求的公共结构代码
     * 检测：逻辑
     * 检测时间：December 28,2018 15:52
     * */
    function ServiceTableDataSet($tableName,$judgefield){
        //--- 参数获取区 ---
        //参数：id
	    $id = GetParameter("id","");
	    if(IsNull($id)){return JsonModelParameterNull("id");}
        //参数：fieldName
	    $fieldName = GetParameter("fieldname","");
	    if(IsNull($fieldName)){return JsonModelParameterNull("fieldname");}
	    //参数：fieldValue
	    $fieldValue = GetParameter("fieldvalue","");
	    if(IsNull($fieldValue)){return JsonModelParameterNull("fieldvalue");}
	    
	    
	    //--- 判断区 ---
	    //判断:是否是可修改的字段名
	    $judgefieldArray = explode(',',$judgefield);
	    $fieldNameArray = explode(',',$fieldName);
	    $judgeLength = 0;
	    for($c=0;$c<sizeof($fieldNameArray);$c++){
	        for($i=0;$i<sizeof($judgefieldArray);$i++){
	            if(JudgeStringEquest($fieldNameArray[$c],$judgefieldArray[$i])){
	                $judgeLength += 1;
	                break;
	            }
	        }
	    }
	    
        if($judgeLength!=sizeof($fieldNameArray)){
        	return JsonInforFalse("没有该字段的修改权限",$tableName);	
        }
	    
        //--- Json组合区 ---
        //Json数组
        $jsonKeyValueArray = array(
            "table_name" => $tableName,
            "update_field" => $fieldName,
            "update_value" => $fieldValue,
            "where_field" => "id",
            "where_value" => $id,
        );
        //组合Json
        $json = JsonHandleArray($jsonKeyValueArray);
        //返回结果
        return MIndexDataUpdate($json);
    }
	
    
    /**
     * 获取修改数据
     * 创建时间：December 28,2018 15:52
     * 说明：抽取修改数据请求的公共结构代码
     * 检测：逻辑
     * 检测时间：December 28,2018 15:52
     * */
    function ServiceTableDataSystemSet($tableName,$fpUpdateField,$fpUpdateValue,$fpWhereField,$fpWhereValue){
        
        //获取数据表字段字符串并进行可修改判断
        $dataField = DBMySQLSelect::TableFieldString($tableName);
        $dataFieldArray = GetArray($dataField, ",");
        $tableFieldString = "";
        for($i=0;$i<sizeof($dataFieldArray);$i++){
            $field = $dataFieldArray[$i];
            $fieldToLowerCase = HandleStringToLowerCase($field);
            if(!($fieldToLowerCase=="id"||$fieldToLowerCase=="onlykey"||$fieldToLowerCase=="addTime")){
                $tableFieldString .= $field.",";
            }
        }
        //组合字段
        if(!IsNull($tableFieldString)){
            $tableFieldString = HandleStringDeleteLast($tableFieldString);
        }
        
        //--- 判断区 ---
        //判断:是否是可修改的字段名
        $judgefieldArray = explode(',',$tableFieldString);
        $fieldNameArray = explode(',',$fpUpdateField);
        for($c=0;$c<sizeof($fieldNameArray);$c++){
            $vJudgeBo = false;
            for($i=0;$i<sizeof($judgefieldArray);$i++){
                if(JudgeStringEquest($fieldNameArray[$c],$judgefieldArray[$i])){
                    $vJudgeBo = true;
                    break;
                }
            }
            if(!$vJudgeBo){
                return JsonInforFalse("没有该字段的修改权限",$tableName);
            }
        }
        
        //--- Json组合区 ---
        //Json数组
        $jsonKeyValueArray = array(
            "table_name" => $tableName,
            "update_field" => $fpUpdateField,
            "update_value" => $fpUpdateValue,
            "where_field" => $fpWhereField,
            "where_value" => $fpWhereValue,
        );
        //返回结果
        return MIndexDataUpdate(JsonHandleArray($jsonKeyValueArray));
    }
        
    
    /**
     * 获取修改数据
     * 创建时间：December 28,2018 15:52
     * 说明：抽取修改数据请求的公共结构代码
     * 检测：逻辑
     * 检测时间：December 28,2018 15:52
     * */
    function ServiceTableDataSetDelete($tableName){
        //--- 参数获取区 ---
        //参数：id
        $id = GetParameter("id","");
        if(IsNull($id)){return JsonModelParameterNull("id");}

        //--- Json组合区 ---
        //Json数组
        $jsonKeyValueArray = array(
            "table_name" => $tableName,
            "update_field" => "state",
            "update_value" => "STATE_DELETE",
            "where_field" => "id",
            "where_value" => $id,
        );
        //组合Json
        $json = JsonHandleArray($jsonKeyValueArray);
        //返回结果
        return MIndexDataUpdate($json);
    }
	
	/**
     * 获取修改数据（条件数据）
     * 创建时间：December 28,2018 22:30
     * 说明：抽取修改数据请求的公共结构代码，按条件执行
     * 检测：逻辑
     * 检测时间：December 28,2018 22:30
     * */
    function ServiceTableDataSetWhere($tableName,$fieldName,$fieldValue,$id){
        //--- Json组合区 ---
        //Json数组
        $jsonKeyValueArray = array(
            "table_name" => $tableName,
            "update_field" => $fieldName,
            "update_value" => $fieldValue,
            "where_field" => "id",
            "where_value" => $id,
        );
        //组合Json
        $json = JsonHandleArray($jsonKeyValueArray);
        //返回结果
        return MIndexDataUpdate($json);
    }
    
    
    /**
     * 获取删除数据
     * 创建时间：2019-01-01 14:03
     * 说明：删除数据
     * 检测：逻辑
     * 检测时间：2019-01-01 14:03
     * */
    function ServiceTableDataDelete($tableName){
        
        //--- 参数获取区 ---
        //参数：id
        $id = GetParameter("id","");
        if(IsNull($id)){return JsonModelParameterNull("id");}
        
	    //Json数组
        $jsonKeyValueArray = array(
            "table_name" => $tableName,
            "where_field" => "id",
            "where_value" => $id,
        );
        //组合Json
        $json = JsonHandleArray($jsonKeyValueArray);
        //返回结果
        return MIndexDataDelete($json);
    }
    
    
    /**
     * 获取记录值（内部使用）
     * 创建时间：August 02,2019 12:33
     * 说明：抽取分页数据请求的公共结构代码
     * 检测：逻辑
     * 检测时间：August 02,2019 12:33
     * */
    function ServiceTableDataRecode($fpTableName,$json,$whereField,$sub=-1){
        
        if(IsNull($whereField)){
            return JsonInforFalse("条件字段不得为空", "记录获取");
        }
        
        $whereValueArray = GetArray($whereField, ",");
        
        //原始字段取值:dataField
        $vDataField = GetJsonValue($json,"data_field");
        if(IsNull($vDataField)){
            $vDataField = DBMySQLSelect::TableFieldString($fpTableName);
        }
        
        //原始字段取值:page
        $vPage = GetJsonValue($json,"page");
        if(IsNull($vPage)){ $vPage = 1; }
        
        //原始字段取值:limit
        $vLimit = GetJsonValue($json,"limit");
        if(IsNull($vLimit)){ $vLimit = 1; }
        
        $sWhereValue = "";
        for($i=0;$i<sizeof($whereValueArray);$i++){
            //参数名
            $vParameter = $whereValueArray[$i];
            //原始字段取值
            $vValue = GetJsonValue($json,$vParameter);
            //到小写字段取值
            if(IsNull($vValue)){
                $vValue = GetJsonValue($json,HandleStringToLowerCase($vParameter));
            }
            //判断参数值是否为空
            if(IsNull($vValue)){
                return JsonInforFalse("条件值为空", $vParameter);
            }
            //组合参数值
            $sWhereValue .= $vValue .",";
        }
        
        $sWhereValue = HandleStringDeleteLast($sWhereValue);
        
        //--- Json提交区 ---
        //Json数组
        $jsonKeyValueArray = array(
            "table_name" => $fpTableName,
            "data_field" => $vDataField,
            "where_field" => $whereField,
            "where_value" => $sWhereValue,
            "page" => $vPage,
            "limit" => $vLimit,
        );
        //返回结果:分页
        $result = MIndexDataPaging(JsonHandleArray($jsonKeyValueArray));
        if(JudgeJsonValue($result, "result", "true")&&GetJsonValue($result, "count")!=0){
            $jsonData = GetJsonValue($result, "data");
            if($sub==-1){
                return $jsonData;
            }else{
                return $jsonData[$sub];
            }
        }else if(JudgeJsonValue($result, "result", "true")&&GetJsonValue($result, "count")==0){
            return JsonInforFalse("记录数为0", $fpTableName.":".$sWhereValue);
        }
        
        return $result;
    }    
    
    /**
     * 获取记录值（Json）
     * 创建时间：August 23,2019 16:18
     * 说明：抽取分页数据请求的公共结构代码
     * 检测：逻辑
     * 检测时间：August 23,2019 16:18
     * */
    function ServiceTableDataRecodeJson($fpTableName,$json,$whereField){
    
        if(IsNull($whereField)){
            return JsonInforFalse("条件字段不得为空", "记录获取");
        }
    
        $whereValueArray = GetArray($whereField, ",");
    
        //原始字段取值:dataField
        $vDataField = GetJsonValue($json,"data_field");
        if(IsNull($vDataField)){
            $vDataField = DBMySQLSelect::TableFieldString($fpTableName);
        }
    
        //原始字段取值:page
        $vPage = GetJsonValue($json,"page");
        if(IsNull($vPage)){ $vPage = 1; }
    
        //原始字段取值:limit
        $vLimit = GetJsonValue($json,"limit");
        if(IsNull($vLimit)){ $vLimit = 1; }
    
        $sWhereValue = "";
        for($i=0;$i<sizeof($whereValueArray);$i++){
            //参数名
            $vParameter = $whereValueArray[$i];
            //原始字段取值
            $vValue = GetJsonValue($json,$vParameter);
            //到小写字段取值
            if(IsNull($vValue)){
                $vValue = GetJsonValue($json,HandleStringToLowerCase($vParameter));
            }
            //判断参数值是否为空
            if(IsNull($vValue)){
                return JsonInforFalse("条件值为空", $vParameter);
            }
            //组合参数值
            $sWhereValue .= $vValue .",";
        }
    
        $sWhereValue = HandleStringDeleteLast($sWhereValue);
    
        //--- Json提交区 ---
        //Json数组
        $jsonKeyValueArray = array(
            "table_name" => $fpTableName,
            "data_field" => $vDataField,
            "where_field" => $whereField,
            "where_value" => $sWhereValue,
            "page" => $vPage,
            "limit" => $vLimit,
        );
        //返回结果:分页
        return MIndexDataPaging(JsonHandleArray($jsonKeyValueArray));
    }
